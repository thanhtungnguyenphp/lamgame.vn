<?php

namespace App\Console\Commands;

use App\Jobs\CheckUserTickets;
use App\Models\LotteryDraw;
use App\Services\Lottery\LotteryNotificationService;
use App\Services\Lottery\TraditionalScraper;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class LotteryScrapeCommand extends Command
{
    protected $signature = 'lottery:scrape
                            {--region=all : mien-nam, mien-trung, mien-bac, or all}
                            {--date= : Date YYYY-MM-DD (default: today)}
                            {--force : Bỏ qua cache, scrape lại dù đã có}';

    protected $description = 'Scrape KQXS truyền thống + push FCM + dò vé tự động';

    public function handle(TraditionalScraper $scraper, LotteryNotificationService $notifyService): int
    {
        $date = $this->option('date') ?: Carbon::today()->toDateString();
        $regionOpt = $this->option('region');
        $force = $this->option('force');

        $regions = $regionOpt === 'all'
            ? config('lottery.regions')
            : [$regionOpt];

        foreach ($regions as $region) {
            $cacheKey = "lottery:scraped:{$region}:{$date}";

            // Skip nếu đã scrape thành công
            if (!$force && Cache::get($cacheKey)) {
                $this->line("⏭ {$region} {$date} — đã có kết quả, skip.");
                continue;
            }

            // Kiểm tra DB
            if (!$force && LotteryDraw::traditional()->forRegion($region)->forDate($date)->completed()->exists()) {
                Cache::put($cacheKey, true, Carbon::tomorrow());
                $this->line("⏭ {$region} {$date} — đã có trong DB, skip.");
                continue;
            }

            $this->info("Scraping {$region} — {$date}...");

            if ($scraper->scrape($region, $date)) {
                Cache::put($cacheKey, true, Carbon::tomorrow());
                $this->info("✅ {$region} OK — gửi FCM + dò vé...");

                $notifyService->notifyTraditionalResult($region, $date);
                CheckUserTickets::dispatch($region, $date);
            } else {
                $this->error("❌ {$region} FAILED — sẽ retry lần sau.");
                return self::FAILURE;
            }
        }

        return self::SUCCESS;
    }
}
