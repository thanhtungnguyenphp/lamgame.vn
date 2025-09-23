<?php

namespace Webkul\Menu\Http\ViewComposers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Cache;
use Webkul\Menu\Repositories\FrontendMenuRepository;

class FrontendMenuComposer
{
    /**
     * The menu repository implementation.
     */
    protected $menuRepository;

    /**
     * Create a new frontend menu composer.
     */
    public function __construct(FrontendMenuRepository $menuRepository)
    {
        $this->menuRepository = $menuRepository;
    }

    /**
     * Bind data to the view.
     */
    public function compose(View $view)
    {
        // Get current channel ID
        $channelId = $this->getCurrentChannelId();
        
        // Get menus for current channel with caching
        $menus = Cache::remember(
            "frontend_menus_{$channelId}",
            config('menu.cache_duration', 3600), // 1 hour default
            function () use ($channelId) {
                return $this->menuRepository->getMenusForChannel($channelId);
            }
        );

        // Share menu data with the view
        $view->with([
            'frontendMenus' => $menus,
            'primaryMenu' => $this->getPrimaryMenu($menus),
            'headerMenu' => $this->getHeaderMenu($menus),
            'footerMenu' => $this->getFooterMenu($menus),
            'mobileMenu' => $this->getMobileMenu($menus),
        ]);
    }

    /**
     * Get current channel ID
     */
    protected function getCurrentChannelId()
    {
        // Try to get from current context (Bagisto way)
        if (function_exists('core') && core()->getCurrentChannel()) {
            return core()->getCurrentChannel()->id;
        }
        
        // Fallback to default channel
        return 1;
    }

    /**
     * Get primary menu (usually main navigation)
     */
    protected function getPrimaryMenu($menus)
    {
        return $menus->firstWhere('name', 'Primary Menu') 
            ?? $menus->firstWhere('name', 'Main Menu')
            ?? $menus->first();
    }

    /**
     * Get header menu
     */
    protected function getHeaderMenu($menus)
    {
        return $menus->firstWhere('name', 'Header Menu')
            ?? $this->getPrimaryMenu($menus);
    }

    /**
     * Get footer menu
     */
    protected function getFooterMenu($menus)
    {
        return $menus->firstWhere('name', 'Footer Menu');
    }

    /**
     * Get mobile menu (could be different from desktop)
     */
    protected function getMobileMenu($menus)
    {
        return $menus->firstWhere('name', 'Mobile Menu')
            ?? $this->getPrimaryMenu($menus);
    }

    /**
     * Clear menu cache
     */
    public static function clearCache($channelId = null)
    {
        if ($channelId) {
            Cache::forget("frontend_menus_{$channelId}");
        } else {
            // Clear all menu caches
            Cache::flush(); // Or use more specific cache tags if available
        }
    }
}
