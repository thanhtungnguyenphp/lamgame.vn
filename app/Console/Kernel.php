<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        // Generate sitemap daily at 2 AM
        $schedule->command('sitemap:generate')
            ->dailyAt('02:00')
            ->appendOutputTo(storage_path('logs/sitemap.log'));

        // Push new content to Google Index every 6 hours
        $schedule->command('google:push-index --type=jobs --limit=20')
            ->everySixHours()
            ->appendOutputTo(storage_path('logs/google-index.log'));

        // Ping sitemap to Google + Bing after sitemap regeneration
        $schedule->command('google:push-index --type=ping-sitemap')
            ->dailyAt('02:15')
            ->appendOutputTo(storage_path('logs/google-index.log'));

        // =============================================
        // LOTTERY SCRAPING
        // Retry mỗi 5 phút trong khung giờ.
        // Command tự skip nếu đã có kết quả (cache flag).
        // =============================================

        // Miền Nam: quay 16:15 → scrape 16:35 ~ 17:15
        $schedule->command('lottery:scrape --region=mien-nam')
            ->everyFiveMinutes()
            ->between('16:35', '17:15')
            ->withoutOverlapping()
            ->appendOutputTo(storage_path('logs/lottery-scrape.log'));

        // Miền Trung: quay 17:15 → scrape 17:35 ~ 18:15
        $schedule->command('lottery:scrape --region=mien-trung')
            ->everyFiveMinutes()
            ->between('17:35', '18:15')
            ->withoutOverlapping()
            ->appendOutputTo(storage_path('logs/lottery-scrape.log'));

        // Miền Bắc: quay 18:15 → scrape 18:35 ~ 19:15
        $schedule->command('lottery:scrape --region=mien-bac')
            ->everyFiveMinutes()
            ->between('18:35', '19:15')
            ->withoutOverlapping()
            ->appendOutputTo(storage_path('logs/lottery-scrape.log'));

        // Vietlot (Mega, Power, Max3D, Max3D Pro): quay 18:00 → scrape 18:05 ~ 18:45
        $schedule->job(new \App\Jobs\ScrapeVietlotLottery())
            ->everyFiveMinutes()
            ->between('18:05', '18:45');

        // Keno — mỗi 10 phút trong khung giờ quay
        $schedule->job(new \App\Jobs\ScrapeVietlotLottery('keno'))
            ->everyTenMinutes()
            ->between('6:00', '22:00');
    }

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
