<?php

namespace Webkul\JobManagement\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class JobManagementServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/../Routes/admin-routes.php');
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'job_management');
        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'job_management');
    }

    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../Config/menu.php', 'menu.admin'
        );
    }
}
