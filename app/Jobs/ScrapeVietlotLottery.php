<?php

namespace App\Jobs;

use App\Services\Lottery\LotteryNotificationService;
use App\Services\Lottery\VietlotScraper;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ScrapeVietlotLottery implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 60;

    public function __construct(
        private ?string $game = null,
    ) {}

    public function handle(VietlotScraper $scraper, LotteryNotificationService $notifyService): void
    {
        $games = $this->game ? [$this->game] : config('lottery.games');
        $date = Carbon::today()->toDateString();

        foreach ($games as $game) {
            $success = $scraper->scrape($game);

            if ($success) {
                // Không push FCM cho Keno (quá nhiều kỳ/ngày)
                if ($game !== 'keno') {
                    $notifyService->notifyVietlotResult($game, $date);
                }
            }
        }
    }
}
