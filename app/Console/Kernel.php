<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Generate sitemap daily at 2 AM
        $schedule->command('sitemap:generate')
            ->dailyAt('02:00')
            ->appendOutputTo(storage_path('logs/sitemap.log'));

        // Push new content to Google Index every 6 hours
        $schedule->command('google:push-index --type=all --limit=20')
            ->everySixHours()
            ->appendOutputTo(storage_path('logs/google-index.log'));

        // --- Lottery Scraping ---
        // XS Truyền thống — scrape 5 phút sau giờ quay
        $schedule->job(new \App\Jobs\ScrapeTraditionalLottery('mien-nam'))->dailyAt('16:20');
        $schedule->job(new \App\Jobs\ScrapeTraditionalLottery('mien-trung'))->dailyAt('17:20');
        $schedule->job(new \App\Jobs\ScrapeTraditionalLottery('mien-bac'))->dailyAt('18:20');

        // Vietlot (Mega, Power, Max3D, Max3D Pro) — sau 18:00
        $schedule->job(new \App\Jobs\ScrapeVietlotLottery())->dailyAt('18:05');

        // Keno — mỗi 10 phút trong khung giờ quay
        $schedule->job(new \App\Jobs\ScrapeVietlotLottery('keno'))
            ->everyTenMinutes()
            ->between('6:00', '22:00');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
