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
        $schedule->command('google:push-index --type=all --limit=20')
            ->everySixHours()
            ->appendOutputTo(storage_path('logs/google-index.log'));

        // =============================================
        // Lottery Scraping — retry mỗi 5 phút trong khung giờ
        // Job tự skip nếu đã có kết quả (cache flag)
        // =============================================

        // Miền Nam: quay 16:15, scrape 16:20 → 17:00 (retry 8 lần)
        $schedule->job(new \App\Jobs\ScrapeTraditionalLottery('mien-nam'))
            ->everyFiveMinutes()
            ->between('16:20', '17:00');

        // Miền Trung: quay 17:15, scrape 17:20 → 18:00
        $schedule->job(new \App\Jobs\ScrapeTraditionalLottery('mien-trung'))
            ->everyFiveMinutes()
            ->between('17:20', '18:00');

        // Miền Bắc: quay 18:15, scrape 18:20 → 19:00
        $schedule->job(new \App\Jobs\ScrapeTraditionalLottery('mien-bac'))
            ->everyFiveMinutes()
            ->between('18:20', '19:00');

        // Vietlot (Mega, Power, Max3D, Max3D Pro): quay 18:00, scrape 18:05 → 18:45
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
