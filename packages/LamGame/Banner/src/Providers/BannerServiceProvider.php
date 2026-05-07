<?php

namespace LamGame\Banner\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use LamGame\Banner\Models\Banner;
use LamGame\Banner\Observers\BannerObserver;
use LamGame\Banner\Repositories\BannerRepository;
use LamGame\Banner\Console\Commands\ClearBannerCacheCommand;

class BannerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'banner');
        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'banner');
        $this->loadRoutesFrom(__DIR__ . '/../Routes/api.php');
        $this->loadRoutesFrom(__DIR__ . '/../Routes/api-management.php');
        $this->loadRoutesFrom(__DIR__ . '/../Routes/admin.php');
        
        $this->registerObservers();
        $this->publishAssets();
        $this->registerConfig();
        $this->registerCommands();
    }

    /**
     * Register services.
     */
    public function register(): void
    {
        $this->registerRepositories();
        $this->mergeConfigFrom(__DIR__ . '/../Config/banner.php', 'banner');
    }

    /**
     * Register repositories.
     */
    protected function registerRepositories(): void
    {
        $this->app->singleton(BannerRepository::class);
    }

    /**
     * Register observers.
     */
    protected function registerObservers(): void
    {
        Banner::observe(BannerObserver::class);
    }

    /**
     * Publish package assets.
     */
    protected function publishAssets(): void
    {
        $this->publishes([
            __DIR__ . '/../Resources/assets' => public_path('vendor/banner'),
        ], 'banner-assets');

        $this->publishes([
            __DIR__ . '/../Config/banner.php' => config_path('banner.php'),
        ], 'banner-config');

        $this->publishes([
            __DIR__ . '/../Database/Migrations' => database_path('migrations'),
        ], 'banner-migrations');

        $this->publishes([
            __DIR__ . '/../Resources/views' => resource_path('views/vendor/banner'),
        ], 'banner-views');

        $this->publishes([
            __DIR__ . '/../Resources/lang' => resource_path('lang/vendor/banner'),
        ], 'banner-lang');
    }

    /**
     * Register package configuration.
     */
    protected function registerConfig(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../Config/acl.php', 'acl');
        
        // Add Banner menu items with proper icon handling
        $bannerMenuItems = include __DIR__ . '/../Config/menu.php';
        
        // Get existing menu items and ensure they have icons
        $existingMenu = config('menu.admin', []);
        $processedMenu = array_map(function ($item) {
            if (!isset($item['icon'])) {
                $item['icon'] = ''; // Set empty icon as default
            }
            return $item;
        }, $existingMenu);
        
        // Merge with banner menu items
        $finalMenu = array_merge($processedMenu, $bannerMenuItems);
        
        // Set the final menu configuration
        config(['menu.admin' => $finalMenu]);
    }


    /**
     * Register admin routes.
     */
    protected function registerAdminRoutes(): void
    {
        Route::middleware(['web', 'admin'])
            ->prefix(config('app.admin_url', 'admin'))
            ->group(__DIR__ . '/../Routes/admin.php');
    }

    /**
     * Register console commands.
     */
    protected function registerCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                ClearBannerCacheCommand::class,
            ]);
        }
    }

    /**
     * Get the services provided by the provider.
     */
    public function provides(): array
    {
        return [
            BannerRepository::class,
        ];
    }
}