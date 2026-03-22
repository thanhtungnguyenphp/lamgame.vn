<?php

namespace App\Jobs;

use App\Models\LotteryDraw;
use App\Services\Lottery\LotteryNotificationService;
use App\Services\Lottery\TraditionalScraper;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ScrapeTraditionalLottery implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 1;

    public function __construct(
        private string $region,
        private ?string $date = null,
    ) {}

    public function handle(TraditionalScraper $scraper, LotteryNotificationService $notifyService): void
    {
        $date = $this->date ?: Carbon::today()->toDateString();
        $cacheKey = "lottery:scraped:{$this->region}:{$date}";

        // Đã scrape thành công rồi → skip
        if (Cache::get($cacheKey)) {
            return;
        }

        // Kiểm tra DB đã có kết quả chưa
        $exists = LotteryDraw::traditional()
            ->forRegion($this->region)
            ->forDate($date)
            ->completed()
            ->exists();

        if ($exists) {
            Cache::put($cacheKey, true, Carbon::tomorrow());
            return;
        }

        $success = $scraper->scrape($this->region, $this->date);

        if ($success) {
            Cache::put($cacheKey, true, Carbon::tomorrow());

            // Gửi FCM push
            $notifyService->notifyTraditionalResult($this->region, $date);

            // Tự động dò vé
            CheckUserTickets::dispatch($this->region, $date);

            Log::info("Lottery scraped OK: {$this->region} {$date}");
        } else {
            Log::warning("Lottery scrape failed, will retry: {$this->region} {$date}");
        }
    }
}
