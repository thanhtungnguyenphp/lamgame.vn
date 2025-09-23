<?php

namespace App\Providers;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\ParallelTesting;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Enable Debugbar only when the package exists and IP is allowed.
        $allowedIPs = array_map('trim', explode(',', (string) config('app.debug_allowed_ips')));
        $allowedIPs = array_filter($allowedIPs);

        if (! class_exists('Barryvdh\\Debugbar\\Facades\\Debugbar') || empty($allowedIPs)) {
            return;
        }

        // Resolve facade dynamically to avoid hard dependency at compile time.
        $debugbar = \Barryvdh\Debugbar\Facades\Debugbar::getFacadeRoot();

        if (in_array(Request::ip(), $allowedIPs)) {
            method_exists($debugbar, 'enable') && $debugbar->enable();
        } else {
            method_exists($debugbar, 'disable') && $debugbar->disable();
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Force HTTPS for all URLs when behind proxy (Cloudflare)
        if (config('app.env') === 'production' || request()->header('CF-Visitor')) {
            URL::forceScheme('https');
        }
        
        // Also force HTTPS if X-Forwarded-Proto header is present
        if (request()->header('X-Forwarded-Proto') === 'https') {
            URL::forceScheme('https');
        }

        // Set default pagination view
        Paginator::defaultView('pagination.custom');
        Paginator::defaultSimpleView('pagination.simple-custom');
        
        ParallelTesting::setUpTestDatabase(function (string $database, int $token) {
            Artisan::call('db:seed');
        });
    }
}
