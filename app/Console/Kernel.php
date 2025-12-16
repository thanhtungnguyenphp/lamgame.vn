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
