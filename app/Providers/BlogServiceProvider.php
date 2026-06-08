<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\BlogRepository;

class BlogServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Force bind our custom BlogRepository to override the package one
        $this->app->bind(
            \Webbycrown\BlogBagisto\Repositories\BlogRepository::class,
            BlogRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Remove Blog from admin sidebar — Migrated to Ohha Studio (2026-06-08)
        $this->app->booted(function () {
            $menu = $this->app['config']->get('menu.admin', []);
            $menu = array_filter($menu, fn($item) => !str_starts_with($item['key'] ?? '', 'blog'));
            $this->app['config']->set('menu.admin', array_values($menu));
        });
    }
}
