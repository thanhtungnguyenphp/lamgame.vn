<?php

namespace App\Jobs;

use App\Models\UserTicket;
use App\Models\LotteryProvince;
use App\Services\Lottery\LotteryCheckService;
use App\Services\Lottery\LotteryNotificationService;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CheckUserTickets implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 2;
    public int $backoff = 30;

    public function __construct(
        private string $region,
        private ?string $date = null,
    ) {}

    public function handle(LotteryCheckService $checkService, LotteryNotificationService $notifyService): void
    {
        $date = $this->date ?: Carbon::today()->toDateString();

        $tickets = UserTicket::pending()
            ->forDate($date)
            ->forRegion($this->region)
            ->get();

        if ($tickets->isEmpty()) return;

        // Cache province names
        $provinces = LotteryProvince::pluck('name', 'code')->toArray();

        foreach ($tickets as $ticket) {
            try {
                $result = $checkService->check(
                    $ticket->numbers,
                    $ticket->region,
                    $date,
                    $ticket->province_code,
                );

                $won = $result['total_matches'] > 0;

                $ticket->update([
                    'status'         => $won ? 'won' : 'lost',
                    'matched_prizes' => $won ? $result['matches'] : null,
                    'notified_at'    => now(),
                ]);

                $provinceName = $provinces[$ticket->province_code ?? ''] ?? $ticket->region;

                $notifyService->notifyTicketResult($ticket->fcm_token, [
                    'ticket_id'      => $ticket->ticket_id,
                    'numbers'        => $ticket->numbers,
                    'draw_date'      => $date,
                    'status'         => $won ? 'won' : 'lost',
                    'matched_prizes' => $won ? $result['matches'] : [],
                    'province_name'  => $provinceName,
                ]);
            } catch (\Exception $e) {
                Log::error("CheckUserTickets failed for ticket {$ticket->ticket_id}", [
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }
}
