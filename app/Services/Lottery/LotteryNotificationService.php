<?php

namespace App\Services\Lottery;

use App\Models\LotteryDraw;
use App\Models\LotteryResult;
use App\Services\FcmNotificationService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class LotteryNotificationService
{
    public function __construct(
        private FcmNotificationService $fcm,
    ) {}

    /**
     * Gửi push notification khi có KQXS truyền thống.
     */
    public function notifyTraditionalResult(string $region, string $date): void
    {
        $topic = config("firebase.topics.{$region}");
        if (!$topic) return;

        $draw = LotteryDraw::traditional()
            ->forRegion($region)
            ->forDate($date)
            ->completed()
            ->with('results.province')
            ->first();

        if (!$draw) return;

        $regionNames = [
            'mien-nam'   => 'Miền Nam',
            'mien-trung' => 'Miền Trung',
            'mien-bac'   => 'Miền Bắc',
        ];

        $dateFormatted = Carbon::parse($date)->format('d/m/Y');

        // Build body: giải ĐB của từng đài
        $bodyParts = [];
        foreach ($draw->results as $result) {
            $provinceName = $result->province?->name ?? 'N/A';
            $db = $result->prize_data['giai_db'][0] ?? '---';
            $bodyParts[] = "{$provinceName}: ĐB {$db}";
        }

        $this->fcm->sendToTopic($topic, [
            'title' => "KQXS {$regionNames[$region]} - {$dateFormatted}",
            'body'  => implode(' | ', $bodyParts),
        ], [
            'type'   => 'kqxs',
            'region' => $region,
            'date'   => $date,
        ]);
    }

    /**
     * Gửi push notification khi có KQ Vietlot.
     */
    public function notifyVietlotResult(string $game, string $date): void
    {
        $topic = config('firebase.topics.vietlot');
        if (!$topic) return;

        $draw = LotteryDraw::vietlot()
            ->forGame($game)
            ->forDate($date)
            ->completed()
            ->with('results')
            ->first();

        if (!$draw) return;

        $gameNames = [
            'mega645'   => 'Mega 6/45',
            'power655'  => 'Power 6/55',
            'max3d'     => 'Max 3D',
            'max3d_pro' => 'Max 3D Pro',
            'keno'      => 'Keno',
        ];

        $dateFormatted = Carbon::parse($date)->format('d/m/Y');
        $result = $draw->results->first();
        $numbers = $result?->prize_data['numbers'] ?? [];

        $body = is_array($numbers)
            ? implode(' - ', $numbers)
            : (string) $numbers;

        $this->fcm->sendToTopic($topic, [
            'title' => "{$gameNames[$game]} - {$dateFormatted}",
            'body'  => "Kết quả: {$body}",
        ], [
            'type' => 'vietlot',
            'game' => $game,
            'date' => $date,
        ]);
    }

    /**
     * Gửi push notification kết quả dò vé cho user.
     */
    public function notifyTicketResult(string $fcmToken, array $ticket): void
    {
        $dateFormatted = Carbon::parse($ticket['draw_date'])->format('d/m/Y');
        $number = $ticket['numbers'][0] ?? '';

        if ($ticket['status'] === 'won') {
            $matches = $ticket['matched_prizes'] ?? [];
            $firstMatch = $matches[0] ?? [];
            $prizeName = $firstMatch['prize_name'] ?? 'giải';

            $this->fcm->sendToToken($fcmToken, [
                'title' => "🎉 Vé {$number} trúng {$prizeName}!",
                'body'  => "{$ticket['province_name']} - {$dateFormatted}. Tap để xem chi tiết.",
            ], [
                'type'      => 'ticket_result',
                'ticket_id' => $ticket['ticket_id'],
                'status'    => 'won',
                'matches'   => json_encode($matches),
            ]);
        } else {
            $this->fcm->sendToToken($fcmToken, [
                'title' => "Kết quả dò vé {$number}",
                'body'  => "Không trúng giải. {$ticket['province_name']} - {$dateFormatted}",
            ], [
                'type'      => 'ticket_result',
                'ticket_id' => $ticket['ticket_id'],
                'status'    => 'lost',
            ]);
        }
    }
}
