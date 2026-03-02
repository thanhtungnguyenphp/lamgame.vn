<?php

namespace App\Console\Commands;

use App\Services\Lottery\TraditionalScraper;
use Carbon\Carbon;
use Illuminate\Console\Command;

class LotteryScrapeCommand extends Command
{
    protected $signature = 'lottery:scrape
                            {--region=all : mien-nam, mien-trung, mien-bac, or all}
                            {--date= : Date YYYY-MM-DD (default: today)}';

    protected $description = 'Scrape kết quả xổ số truyền thống từ xoso.com.vn';

    public function handle(TraditionalScraper $scraper): int
    {
        $date = $this->option('date') ?: Carbon::today()->toDateString();
        $regionOpt = $this->option('region');

        $regions = $regionOpt === 'all'
            ? config('lottery.regions')
            : [$regionOpt];

        foreach ($regions as $region) {
            $this->info("Scraping {$region} — {$date}...");

            if ($scraper->scrape($region, $date)) {
                $this->info("✅ {$region} OK");
            } else {
                $this->error("❌ {$region} FAILED");
            }
        }

        return self::SUCCESS;
    }
}
