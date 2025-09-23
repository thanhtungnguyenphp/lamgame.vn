<?php

namespace Webkul\Menu\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\View;
use Webkul\Menu\Http\ViewComposers\MenuComposer;
use Webkul\Menu\Http\ViewComposers\FrontendMenuComposer;

class MenuServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        $this->loadRoutesFrom(__DIR__ . '/../Routes/admin-routes.php');

        $this->loadRoutesFrom(__DIR__ . '/../Routes/shop-routes.php');

        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'menu');

        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'menu');

        Event::listen('bagisto.admin.layout.head', function($viewRenderEventManager) {
            $viewRenderEventManager->addTemplate('menu::admin.layouts.style');
        });

        // Register View Composers for custom menu (admin)
        View::composer([
            'shop::components.layouts.header.custom-menu',
            'shop::components.layouts.header.mobile-custom-menu'
        ], MenuComposer::class);
        
        // Register View Composers for frontend
        $this->registerFrontendViewComposers();
        
        // Register blade directives
        $this->registerBladeDirectives();
    }

    /**
     * Register services.
     */
    public function register(): void
    {
        $this->registerConfig();
        
        // Register frontend menu repository
        $this->app->singleton('menu.frontend', function ($app) {
            return new \Webkul\Menu\Repositories\FrontendMenuRepository();
        });
    }

    /**
     * Register package config.
     *
     * @return void
     */
    protected function registerConfig(): void
    {
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/menu.php', 'menu.admin'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/acl.php', 'acl'
        );
    }
    
    /**
     * Register frontend view composers
     */
    protected function registerFrontendViewComposers()
    {
        // Apply menu composer to all frontend layouts
        View::composer([
            'layouts.master',
            'shop::layouts.master', 
            'themes::layouts.master',
            '*master*',
            '*layout*'
        ], FrontendMenuComposer::class);
        
        // Apply to specific menu partials
        View::composer([
            'menu::frontend.*',
            '*menu*',
            '*nav*',
            '*navigation*'
        ], FrontendMenuComposer::class);
    }
    
    /**
     * Register custom Blade directives
     */
    protected function registerBladeDirectives()
    {
        // @menu directive
        \Blade::directive('menu', function ($expression) {
            return "<?php echo app('menu.frontend')->renderMenu({$expression}); ?>";
        });
        
        // @menuItem directive  
        \Blade::directive('menuItem', function ($expression) {
            return "<?php echo app('menu.frontend')->renderMenuItem({$expression}); ?>";
        });
        
        // @mobileMenu directive
        \Blade::directive('mobileMenu', function ($expression) {
            return "<?php echo app('menu.frontend')->renderMobileMenu({$expression}); ?>";
        });
    }
}
