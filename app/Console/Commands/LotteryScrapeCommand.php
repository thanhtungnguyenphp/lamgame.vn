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

            // Skip nếu đã scrape thành công VÀ đủ đài
            if (!$force && Cache::get($cacheKey)) {
                // Verify có đủ đài không
                if ($this->hasCompleteData($region, $date)) {
                    $this->line("⏭ {$region} {$date} — đã có kết quả, skip.");
                    continue;
                }
                // Thiếu đài → force re-scrape
                $this->warn("⚠ {$region} {$date} — thiếu đài, re-scraping...");
                Cache::forget($cacheKey);
            }

            // Kiểm tra DB — chỉ skip nếu đủ đài
            if (!$force && LotteryDraw::traditional()->forRegion($region)->forDate($date)->completed()->exists()) {
                if ($this->hasCompleteData($region, $date)) {
                    Cache::put($cacheKey, true, Carbon::tomorrow());
                    $this->line("⏭ {$region} {$date} — đã có trong DB, skip.");
                    continue;
                }
                // Thiếu → xóa data cũ để scrape lại
                $this->warn("⚠ {$region} {$date} — DB thiếu đài, clearing & re-scraping...");
                $draw = LotteryDraw::traditional()->forRegion($region)->forDate($date)->first();
                if ($draw) {
                    \App\Models\LotteryResult::where('draw_id', $draw->id)->delete();
                    $draw->delete();
                }
            }

            // Force mode: xóa data cũ
            if ($force) {
                $draw = LotteryDraw::traditional()->forRegion($region)->forDate($date)->first();
                if ($draw) {
                    \App\Models\LotteryResult::where('draw_id', $draw->id)->delete();
                    $draw->delete();
                }
                Cache::forget($cacheKey);
            }

            $this->info("Scraping {$region} — {$date}...");

            if ($scraper->scrape($region, $date)) {
                Cache::put($cacheKey, true, Carbon::tomorrow());
                $this->info("✅ {$region} OK — gửi FCM + dò vé...");

                $notifyService->notifyTraditionalResult($region, $date);
                CheckUserTickets::dispatch($region, $date);
            } else {
                $this->error("❌ {$region} FAILED — sẽ retry lần sau.");
            }
        }

        return self::SUCCESS;
    }

    /**
     * Kiểm tra data có đủ đài theo lịch không
     */
    private function hasCompleteData(string $region, string $date): bool
    {
        if ($region === 'mien-bac') return true; // Miền Bắc chỉ 1 đài

        $dow = Carbon::parse($date)->dayOfWeek;
        $dbDow = $dow === 0 ? 7 : $dow;

        $expectedCount = \DB::table('lottery_schedules as s')
            ->join('lottery_provinces as p', 'p.id', '=', 's.province_id')
            ->where('p.region', $region)
            ->where('s.day_of_week', $dbDow)
            ->count();

        $draw = LotteryDraw::traditional()->forRegion($region)->forDate($date)->first();
        if (!$draw) return false;

        $actualCount = \App\Models\LotteryResult::where('draw_id', $draw->id)->count();
        return $actualCount >= $expectedCount;
    }
}
