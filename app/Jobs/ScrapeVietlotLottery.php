<?php

namespace App\Jobs;

use App\Models\LotteryDraw;
use App\Services\Lottery\LotteryNotificationService;
use App\Services\Lottery\XosoMeScraper;
use App\Services\Lottery\VietlotScraper;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ScrapeVietlotLottery implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 1;

    public function __construct(
        private ?string $game = null,
    ) {}

    public function handle(XosoMeScraper $primaryScraper, VietlotScraper $fallbackScraper, LotteryNotificationService $notifyService): void
    {
        $games = $this->game ? [$this->game] : config('lottery.games');
        $date = Carbon::today()->toDateString();

        foreach ($games as $game) {
            $cacheKey = "lottery:scraped:vietlot:{$game}:{$date}";

            // Keno không cache (nhiều kỳ/ngày)
            if ($game !== 'keno' && Cache::get($cacheKey)) {
                continue;
            }

            // Primary: xoso.me + GitHub fallback (handles all games)
            $success = $primaryScraper->scrape($game, $date);

            // Fallback: vietlott.vn (may fail due to Cloudflare)
            if (!$success) {
                $success = $fallbackScraper->scrape($game, $date);
            }

            if ($success && $game !== 'keno') {
                Cache::put($cacheKey, true, Carbon::tomorrow());
                $notifyService->notifyVietlotResult($game, $date);
                Log::info("Vietlot scraped OK: {$game} {$date}");
            }
        }
    }
}
