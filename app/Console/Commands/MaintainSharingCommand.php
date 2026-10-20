<?php

namespace App\Console\Commands;

use App\Jobs\ProcessSharingOutbox;
use App\Models\LotteryDraw;
use App\Models\LotterySchedule;
use App\Models\SharingOutboxEvent;
use App\Models\TicketShare;
use App\Services\Lottery\LotteryCheckService;
use App\Services\Sharing\SharingService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MaintainSharingCommand extends Command
{
    protected $signature = 'sharing:maintain {--region=} {--date=}';
    protected $description = 'Expire Lotto Live shares, check completed draws, and dispatch transactional outbox events.';

    public function handle(SharingService $sharing, LotteryCheckService $checker): int
    {
        TicketShare::whereIn('status', ['awaiting_registration', 'pending'])->where('expires_at', '<=', now())->chunkById(100, function ($shares) use ($sharing) {
            foreach ($shares as $share) DB::transaction(function () use ($share, $sharing) {
                $locked = TicketShare::whereKey($share->id)->lockForUpdate()->first();
                if ($locked) $sharing->expireIfNecessary($locked);
            });
        });

        $query = TicketShare::whereIn('status', ['pending', 'accepted']);
        if ($this->option('region')) $query->where('region', $this->option('region'));
        if ($this->option('date')) $query->whereDate('draw_date', $this->option('date'));
        $query->chunkById(100, function ($shares) use ($checker) {
            foreach ($shares as $share) $this->checkShare($share, $checker);
        });

        SharingOutboxEvent::where('status', 'pending')->where(fn ($q) => $q->whereNull('available_at')->orWhere('available_at', '<=', now()))->limit(100)->get()
            ->each(fn ($event) => ProcessSharingOutbox::dispatch($event->event_id));
        return self::SUCCESS;
    }

    private function checkShare(TicketShare $share, LotteryCheckService $checker): void
    {
        $draw = LotteryDraw::traditional()->forRegion($share->region)->forDate($share->draw_date->toDateString())->completed()->with('results')->first();
        if (!$draw || !$this->drawIsComplete($draw, $share)) return;

        $sourceHash = hash('sha256', $draw->results->map(fn ($result) => [$result->province_id, $result->prize_data])->sortBy(fn ($value) => $value[0])->values()->toJson());
        if ($share->result_final && hash_equals((string) $share->result_source_hash, $sourceHash)) return;

        DB::transaction(function () use ($share, $checker, $sourceHash) {
            $locked = TicketShare::whereKey($share->id)->lockForUpdate()->first();
            if (!$locked || ($locked->result_final && hash_equals((string) $locked->result_source_hash, $sourceHash))) return;
            if ($locked->result_final) {
                $locked->update(['result_status' => 'correction_pending', 'result_final' => false]);
            }
            $result = $checker->check([$locked->number], $locked->region, $locked->draw_date->toDateString(), $locked->province_code);
            $matches = collect($result['matches'])->map(fn ($match) => trim("{$match['province']} — {$match['prize_name']} ({$match['full_prize_number']})"))->values()->all();
            $locked->update([
                'result_status' => $matches ? 'won' : 'lost', 'result_final' => true,
                'matched_prizes' => $matches, 'result_version' => $locked->result_version + 1, 'result_source_hash' => $sourceHash,
            ]);
            foreach (array_filter([$locked->owner_uid, $locked->status === 'accepted' ? $locked->recipient_uid : null]) as $recipientUid) {
                SharingOutboxEvent::firstOrCreate(['dedup_key' => "{$locked->share_id}:share_result:{$locked->result_version}:{$recipientUid}"], [
                    'event_id' => 'evt_' . Str::lower(Str::random(24)), 'share_id' => $locked->share_id,
                    'recipient_uid' => $recipientUid, 'type' => 'share_result', 'result_version' => $locked->result_version,
                    'payload' => ['share_id' => $locked->share_id], 'status' => 'pending', 'available_at' => now(),
                ]);
            }
        });
    }

    private function drawIsComplete(LotteryDraw $draw, TicketShare $share): bool
    {
        $expected = LotterySchedule::where('day_of_week', $share->draw_date->dayOfWeekIso)
            ->whereHas('province', fn ($q) => $q->where('region', $share->region))->count();
        return $draw->results->isNotEmpty() && ($share->region === 'mien-bac' || $draw->results->count() >= $expected);
    }
}
