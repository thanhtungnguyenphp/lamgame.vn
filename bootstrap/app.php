<?php

use App\Http\Middleware\EncryptCookies;
use App\Http\Middleware\NoIndexApiResponse;
use App\Http\Middleware\SeoMetaRobots;
use App\Http\Middleware\TrustProxies;
use Sentry\Laravel\Integration;
use Illuminate\Support\Facades\Route;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Cookie\Middleware\EncryptCookies as BaseEncryptCookies;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull;
use Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance;
use Webkul\Core\Http\Middleware\SecureHeaders;
use Webkul\Installer\Http\Middleware\CanInstall;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        then: function () {
            Route::middleware('api')->prefix('api')->group(base_path('routes/api-job-v2.php'));
            Route::middleware('api')->prefix('api')->group(base_path('routes/api-job-manage.php'));
            Route::middleware('api')->prefix('api')->group(base_path('routes/api-ecommerce-manage.php'));
            Route::middleware('api')->prefix('api')->group(base_path('routes/api-reviews-hire.php'));
            Route::middleware('api')->prefix('api')->group(base_path('routes/api-forum-manage.php'));
            Route::middleware('api')->prefix('api')->group(base_path('routes/api-blog-manage.php'));
            Route::middleware('api')->prefix('api')->group(base_path('routes/api-manage-extended.php'));

            // Alias: /api-sport/* → same controllers as /api/v1/sport/*
            Route::middleware('api')->prefix('api-sport')->group(base_path('routes/api-sport-alias.php'));
        },
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        /**
         * Remove the default Laravel middleware that prevents requests during maintenance mode. There are three
         * middlewares in the shop that need to be loaded before this middleware. Therefore, we need to remove this
         * middleware from the list and add the overridden middleware at the end of the list.
         *
         * As of now, this has been added in the Admin and Shop providers. I will look for a better approach in Laravel 11 for this.
         */
        $middleware->remove(PreventRequestsDuringMaintenance::class);

        /**
         * Remove the default Laravel middleware that converts empty strings to null. First, handle all nullable cases,
         * then remove this line.
         */
        $middleware->remove(ConvertEmptyStringsToNull::class);

        // Add TrustProxies middleware for Cloudflare
        $middleware->append(TrustProxies::class);
        $middleware->append(SecureHeaders::class);
        $middleware->append(CanInstall::class);
        $middleware->append(SeoMetaRobots::class);

        // Add X-Robots-Tag: noindex to all API responses
        $middleware->appendToGroup('api', NoIndexApiResponse::class);

        /**
         * Add the overridden middleware at the end of the list.
         */
        $middleware->replaceInGroup('web', BaseEncryptCookies::class, EncryptCookies::class);
        $middleware->replace(\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class, \App\Http\Middleware\VerifyCsrfToken::class);
        
        /**
         * Register middleware aliases
         */
        $middleware->alias([
            'seller'          => \App\Http\Middleware\CheckSeller::class,
            'employer'        => \App\Http\Middleware\CheckEmployer::class,
            'quota'           => \App\Http\Middleware\CheckSubscriptionQuota::class,
            'firebase.auth'   => \App\Http\Middleware\FirebaseAuth::class,
            'api.key'         => \App\Http\Middleware\ApiKeyAuth::class,
            'forum.rate'      => \App\Http\Middleware\ForumRateLimiter::class,
            'forum.honeypot'  => \App\Http\Middleware\ForumHoneypot::class,
            'throttle:ai'     => \App\Http\Middleware\AiRateLimit::class,
        ]);
        
        // Add site metrics injection to web group
        $middleware->appendToGroup('web', \App\Http\Middleware\InjectSiteMetrics::class);
    })
    ->withSchedule(function (Schedule $schedule) {
        $schedule->command('blog:publish-scheduled')->everyFiveMinutes();

        $schedule->command('forum:calculate-hot-scores')
            ->hourly()
            ->withoutOverlapping()
            ->appendOutputTo(storage_path('logs/forum-hot-scores.log'));

        $schedule->command('sitemap:generate')
            ->dailyAt('02:00')
            ->appendOutputTo(storage_path('logs/sitemap.log'));

        $schedule->command('google:push-index --type=jobs --limit=20')
            ->everySixHours()
            ->appendOutputTo(storage_path('logs/google-index.log'));

        $schedule->command('google:push-index --type=indexnow --limit=20')
            ->dailyAt('02:15')
            ->appendOutputTo(storage_path('logs/google-index.log'));

        $schedule->command('seo:auto-index')
            ->everyThreeHours()
            ->appendOutputTo(storage_path('logs/seo-auto-index.log'));

        // === LOTTERY SCRAPING ===
        // Retry mỗi 5 phút trong khung giờ. Tự skip nếu đã có kết quả (cache).

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

        // DISABLED: 2026-08-26 — Vietlott.vn returns 403 Cloudflare challenge
        // Vietlot (Mega, Power, Max3D, Max3D Pro): quay 18:00 → scrape 18:05 ~ 18:45
        // $schedule->job(new \App\Jobs\ScrapeVietlotLottery())
        //     ->everyFiveMinutes()
        //     ->between('18:05', '18:45');

        // DISABLED: 2026-08-26 — Vietlott.vn returns 403 Cloudflare challenge
        // Keno — mỗi 10 phút trong khung giờ quay
        // $schedule->job(new \App\Jobs\ScrapeVietlotLottery('keno'))
        //     ->everyTenMinutes()
        //     ->between('6:00', '22:00');

        // RETRY — chạy lại tất cả miền lúc 20:00 để bắt data thiếu đài
        $schedule->command('lottery:scrape --region=all --force')
            ->dailyAt('20:00')
            ->withoutOverlapping()
            ->appendOutputTo(storage_path('logs/lottery-scrape.log'));

        // === SPORT CRAWL ===
        $schedule->command('sport:sync-fixtures')->dailyAt('06:00')->withoutOverlapping();
        $schedule->command('sport:sync-live')->everyThirtySeconds()->withoutOverlapping()
            ->when(fn () => \Illuminate\Support\Facades\Cache::get('sport:has_live_matches', false));
        $schedule->command('sport:sync-standings')->twiceDaily(2, 14)->withoutOverlapping();
        $schedule->command('sport:sync-highlights')->everySixHours()->withoutOverlapping();
        $schedule->command('sport:sync-articles')->cron('0 1,7,13,19 * * *')->withoutOverlapping();
        $schedule->command('sport:cleanup')->weekly()->withoutOverlapping();

        // === SPORT NOTIFICATIONS ===
        $schedule->job(new \App\Jobs\Sport\DailyDigestNotificationJob)->dailyAt('08:00');
        $schedule->call(function () {
            $upcoming = \App\Models\Sport\SportMatch::where('status', 'scheduled')
                ->whereBetween('start_time', [now()->addMinutes(14), now()->addMinutes(16)])
                ->pluck('id');
            foreach ($upcoming as $matchId) {
                \App\Jobs\Sport\MatchStartNotificationJob::dispatch($matchId);
            }
        })->everyMinute()->name('sport-match-start-notify')->withoutOverlapping();
    })
    ->withExceptions(function (Exceptions $exceptions) {
        if (class_exists(\Sentry\Laravel\Integration::class)) {
            \Sentry\Laravel\Integration::handles($exceptions);
        }
    })->create();
