<?php

use App\Http\Middleware\EncryptCookies;
use App\Http\Middleware\SeoMetaRobots;
use App\Http\Middleware\TrustProxies;
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

        /**
         * Add the overridden middleware at the end of the list.
         */
        $middleware->replaceInGroup('web', BaseEncryptCookies::class, EncryptCookies::class);
        $middleware->replace(\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class, \App\Http\Middleware\VerifyCsrfToken::class);
        
        /**
         * Register middleware aliases
         */
        $middleware->alias([
            'seller'        => \App\Http\Middleware\CheckSeller::class,
            'quota'         => \App\Http\Middleware\CheckSubscriptionQuota::class,
            'firebase.auth' => \App\Http\Middleware\FirebaseAuth::class,
            'api.key'       => \App\Http\Middleware\ApiKeyAuth::class,
        ]);
    })
    ->withSchedule(function (Schedule $schedule) {
        $schedule->command('blog:publish-scheduled')->everyFiveMinutes();

        $schedule->command('sitemap:generate')
            ->dailyAt('02:00')
            ->appendOutputTo(storage_path('logs/sitemap.log'));

        $schedule->command('google:push-index --type=jobs --limit=20')
            ->everySixHours()
            ->appendOutputTo(storage_path('logs/google-index.log'));

        $schedule->command('google:push-index --type=indexnow --limit=20')
            ->dailyAt('02:15')
            ->appendOutputTo(storage_path('logs/google-index.log'));
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
