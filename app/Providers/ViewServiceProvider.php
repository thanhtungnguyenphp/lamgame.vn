<?php

namespace App\Providers;

use App\Services\SiteMetricsService;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(SiteMetricsService::class, function ($app) {
            return new SiteMetricsService();
        });
    }

    public function boot(): void
    {
        // Share site metrics with all views
        View::composer('*', function ($view) {
            static $metrics = null;
            
            if ($metrics === null) {
                $metrics = app(SiteMetricsService::class)->getMetrics();
            }
            
            $view->with('siteMetrics', $metrics);
        });
    }
}
