<?php

namespace App\Jobs;

use App\Services\Lottery\LotteryNotificationService;
use App\Services\Lottery\TraditionalScraper;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ScrapeTraditionalLottery implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 60;

    public function __construct(
        private string $region,
        private ?string $date = null,
    ) {}

    public function handle(TraditionalScraper $scraper, LotteryNotificationService $notifyService): void
    {
        $date = $this->date ?: Carbon::today()->toDateString();

        $success = $scraper->scrape($this->region, $this->date);

        if ($success) {
            // Gửi FCM push thông báo có KQXS mới
            $notifyService->notifyTraditionalResult($this->region, $date);

            // Tự động dò vé số pending cho region + date này
            CheckUserTickets::dispatch($this->region, $date);
        }
    }
}
