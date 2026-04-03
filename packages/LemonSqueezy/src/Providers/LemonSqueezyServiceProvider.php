<?php

namespace LemonSqueezy\Providers;

use Illuminate\Support\ServiceProvider;

class LemonSqueezyServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/paymentmethods.php', 'payment_methods'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/system.php', 'core'
        );
    }

    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/../Http/routes.php');

        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'lemonsqueezy');

        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'lemonsqueezy');

        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        $this->app->register(EventServiceProvider::class);
    }
}
