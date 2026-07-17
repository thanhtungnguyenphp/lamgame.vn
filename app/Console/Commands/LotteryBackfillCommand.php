<?php

namespace App\Console\Commands;

use App\Models\LotteryDraw;
use App\Models\LotteryResult;
use App\Models\LotteryProvince;
use App\Services\Lottery\TraditionalScraper;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class LotteryBackfillCommand extends Command
{
    protected $signature = 'lottery:backfill
                            {--from= : Start date YYYY-MM-DD (default: 30 days ago)}
                            {--to= : End date YYYY-MM-DD (default: yesterday)}
                            {--region=all : mien-nam, mien-trung, mien-bac, or all}';

    protected $description = 'Backfill missing lottery data — re-scrape ngày thiếu đài';

    public function handle(TraditionalScraper $scraper): int
    {
        $from = $this->option('from') ?: Carbon::today()->subDays(30)->toDateString();
        $to = $this->option('to') ?: Carbon::yesterday()->toDateString();
        $regionOpt = $this->option('region');

        $regions = $regionOpt === 'all'
            ? config('lottery.regions')
            : [$regionOpt];

        $this->info("Backfill: {$from} → {$to} | Regions: " . implode(', ', $regions));
        $this->newLine();

        $totalFixed = 0;

        foreach ($regions as $region) {
            $this->info("📍 Processing {$region}...");

            $current = Carbon::parse($from);
            $end = Carbon::parse($to);

            while ($current->lte($end)) {
                $date = $current->toDateString();
                $dow = $current->dayOfWeek; // 0=Sun
                $dbDow = $dow === 0 ? 7 : $dow; // DB stores Sunday as 7

                // Expected provinces for this day
                $expectedCount = DB::table('lottery_schedules as s')
                    ->join('lottery_provinces as p', 'p.id', '=', 's.province_id')
                    ->where('p.region', $region)
                    ->where('s.day_of_week', $dbDow)
                    ->count();

                // Miền Bắc always 1
                if ($region === 'mien-bac') {
                    $expectedCount = 1;
                }

                // Actual provinces in DB
                $draw = LotteryDraw::where('type', 'traditional')
                    ->where('region', $region)
                    ->where('date', $date)
                    ->first();

                $actualCount = 0;
                if ($draw) {
                    $actualCount = LotteryResult::where('draw_id', $draw->id)->count();
                }

                if ($actualCount < $expectedCount) {
                    $this->warn("  {$date} ({$current->format('D')}): {$actualCount}/{$expectedCount} đài — RE-SCRAPING...");

                    // Clear old cache & data to force re-scrape
                    $cacheKey = "lottery:scraped:{$region}:{$date}";
                    Cache::forget($cacheKey);

                    // Delete incomplete draw + results
                    if ($draw) {
                        LotteryResult::where('draw_id', $draw->id)->delete();
                        $draw->delete();
                    }

                    // Re-scrape
                    $success = $scraper->scrape($region, $date);

                    if ($success) {
                        // Verify
                        $newDraw = LotteryDraw::where('type', 'traditional')
                            ->where('region', $region)
                            ->where('date', $date)
                            ->first();
                        $newCount = $newDraw ? LotteryResult::where('draw_id', $newDraw->id)->count() : 0;

                        if ($newCount >= $expectedCount) {
                            $this->info("    ✅ Fixed: {$newCount}/{$expectedCount} đài");
                            $totalFixed++;
                        } else {
                            $this->error("    ⚠️ Partial: {$newCount}/{$expectedCount} (source may not have full data)");
                        }
                    } else {
                        $this->error("    ❌ Scrape failed for {$date}");
                    }

                    // Rate limit: wait 1 second between scrapes
                    sleep(1);
                }

                $current->addDay();
            }

            $this->newLine();
        }

        $this->info("✅ Backfill complete. Fixed {$totalFixed} draws.");
        return self::SUCCESS;
    }
}
