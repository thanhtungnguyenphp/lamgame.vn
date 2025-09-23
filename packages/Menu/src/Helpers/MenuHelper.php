<?php

namespace Webkul\Menu\Helpers;

use Illuminate\Support\Facades\Cache;
use Webkul\Menu\Repositories\FrontendMenuRepository;
use Webkul\Menu\Services\MenuCacheManager;

class MenuHelper
{
    /**
     * The frontend menu repository.
     */
    protected $menuRepository;

    /**
     * The menu cache manager.
     */
    protected $cacheManager;

    public function __construct()
    {
        $this->menuRepository = app(FrontendMenuRepository::class);
        $this->cacheManager = app(MenuCacheManager::class);
    }

    /**
     * Render menu by name with caching.
     */
    public function renderMenu($menuName = null, $options = [])
    {
        $channelId = $this->getCurrentChannelId();
        
        if ($this->cacheManager->isCacheEnabled()) {
            $cacheKey = "menu_render_{$menuName}_{$channelId}_" . md5(serialize($options));
            $duration = $this->cacheManager->getCacheDuration();
            
            return Cache::remember($cacheKey, $duration, function () use ($menuName, $channelId, $options) {
                return $this->menuRepository->renderMenu($menuName, $channelId, $options);
            });
        }

        return $this->menuRepository->renderMenu($menuName, $channelId, $options);
    }

    /**
     * Get menu data as array.
     */
    public function getMenuAsArray($menuName, $channelId = null)
    {
        $channelId = $channelId ?? $this->getCurrentChannelId();
        
        if ($this->cacheManager->isCacheEnabled()) {
            $menu = $this->cacheManager->cacheMenu($menuName, $channelId);
        } else {
            $menu = $this->menuRepository->getMenuByName($menuName, $channelId);
        }

        return $menu ? $this->menuRepository->menuItemsToArray($menu->menuItems) : [];
    }

    /**
     * Get primary navigation menu.
     */
    public function getPrimaryMenu($channelId = null)
    {
        $channelId = $channelId ?? $this->getCurrentChannelId();
        
        if ($this->cacheManager->isCacheEnabled()) {
            $menus = $this->cacheManager->cacheFrontendMenus($channelId);
        } else {
            $menus = $this->menuRepository->getMenusForChannel($channelId);
        }

        return $menus->firstWhere('name', 'Primary Menu') 
            ?? $menus->firstWhere('name', 'Main Menu')
            ?? $menus->first();
    }

    /**
     * Get header menu with fallback.
     */
    public function getHeaderMenu($channelId = null)
    {
        $channelId = $channelId ?? $this->getCurrentChannelId();
        
        if ($this->cacheManager->isCacheEnabled()) {
            $menus = $this->cacheManager->cacheFrontendMenus($channelId);
        } else {
            $menus = $this->menuRepository->getMenusForChannel($channelId);
        }

        return $menus->firstWhere('name', 'Header Menu')
            ?? $this->getPrimaryMenu($channelId);
    }

    /**
     * Get footer menu.
     */
    public function getFooterMenu($channelId = null)
    {
        $channelId = $channelId ?? $this->getCurrentChannelId();
        
        if ($this->cacheManager->isCacheEnabled()) {
            $menus = $this->cacheManager->cacheFrontendMenus($channelId);
        } else {
            $menus = $this->menuRepository->getMenusForChannel($channelId);
        }

        return $menus->firstWhere('name', 'Footer Menu');
    }

    /**
     * Get mobile menu with fallback.
     */
    public function getMobileMenu($channelId = null)
    {
        $channelId = $channelId ?? $this->getCurrentChannelId();
        
        if ($this->cacheManager->isCacheEnabled()) {
            $menus = $this->cacheManager->cacheFrontendMenus($channelId);
        } else {
            $menus = $this->menuRepository->getMenusForChannel($channelId);
        }

        return $menus->firstWhere('name', 'Mobile Menu')
            ?? $this->getPrimaryMenu($channelId);
    }

    /**
     * Check if menu item is active based on current URL.
     */
    public function isMenuItemActive($menuItem, $currentUrl = null)
    {
        $currentUrl = $currentUrl ?? request()->url();
        
        // Exact match
        if ($menuItem->url === $currentUrl) {
            return true;
        }

        // Check if current URL starts with menu item URL (for parent pages)
        if (strpos($currentUrl, rtrim($menuItem->url, '/')) === 0) {
            return true;
        }

        // Check children recursively
        if ($menuItem->children) {
            foreach ($menuItem->children as $child) {
                if ($this->isMenuItemActive($child, $currentUrl)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Get breadcrumb trail for current page.
     */
    public function getBreadcrumb($url = null, $channelId = null)
    {
        $url = $url ?? request()->url();
        $channelId = $channelId ?? $this->getCurrentChannelId();

        return $this->menuRepository->getBreadcrumb($url, $channelId);
    }

    /**
     * Get menu tree as nested array with active states.
     */
    public function getMenuTree($menuName, $currentUrl = null, $channelId = null)
    {
        $channelId = $channelId ?? $this->getCurrentChannelId();
        $currentUrl = $currentUrl ?? request()->url();
        
        $menu = $this->cacheManager->isCacheEnabled() 
            ? $this->cacheManager->cacheMenu($menuName, $channelId)
            : $this->menuRepository->getMenuByName($menuName, $channelId);

        if (!$menu) {
            return [];
        }

        return $this->buildMenuTreeWithActiveStates($menu->menuItems, $currentUrl);
    }

    /**
     * Build menu tree with active states.
     */
    protected function buildMenuTreeWithActiveStates($items, $currentUrl)
    {
        return $items->map(function ($item) use ($currentUrl) {
            return [
                'id' => $item->id,
                'title' => $item->title,
                'url' => $item->url,
                'target' => $item->target,
                'icon' => $item->icon,
                'sort_order' => $item->sort_order,
                'active' => $this->isMenuItemActive($item, $currentUrl),
                'children' => $item->children ? 
                    $this->buildMenuTreeWithActiveStates($item->children, $currentUrl) : []
            ];
        })->toArray();
    }

    /**
     * Generate structured data (JSON-LD) for menu navigation.
     */
    public function getNavigationStructuredData($menuName = null, $channelId = null)
    {
        $channelId = $channelId ?? $this->getCurrentChannelId();
        $menu = $menuName 
            ? ($this->cacheManager->isCacheEnabled() 
                ? $this->cacheManager->cacheMenu($menuName, $channelId)
                : $this->menuRepository->getMenuByName($menuName, $channelId))
            : $this->getPrimaryMenu($channelId);

        if (!$menu) {
            return null;
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'SiteNavigationElement',
            'name' => $menu->name,
            'hasPart' => $this->buildNavigationStructuredDataItems($menu->menuItems)
        ];
    }

    /**
     * Build navigation structured data items.
     */
    protected function buildNavigationStructuredDataItems($items)
    {
        return $items->map(function ($item) {
            $data = [
                '@type' => 'SiteNavigationElement',
                'name' => $item->title,
                'url' => $item->url,
            ];

            if ($item->children && $item->children->count() > 0) {
                $data['hasPart'] = $this->buildNavigationStructuredDataItems($item->children);
            }

            return $data;
        })->toArray();
    }

    /**
     * Get menu statistics.
     */
    public function getMenuStats($channelId = null)
    {
        $channelId = $channelId ?? $this->getCurrentChannelId();
        
        $menus = $this->cacheManager->isCacheEnabled() 
            ? $this->cacheManager->cacheFrontendMenus($channelId)
            : $this->menuRepository->getMenusForChannel($channelId);

        $stats = [
            'total_menus' => $menus->count(),
            'total_items' => 0,
            'max_depth' => 0,
            'menus' => []
        ];

        foreach ($menus as $menu) {
            $menuStats = [
                'name' => $menu->name,
                'items_count' => $menu->menuItems->count(),
                'total_items' => $this->countAllMenuItems($menu->menuItems),
                'max_depth' => $this->calculateMenuDepth($menu->menuItems)
            ];

            $stats['menus'][] = $menuStats;
            $stats['total_items'] += $menuStats['total_items'];
            $stats['max_depth'] = max($stats['max_depth'], $menuStats['max_depth']);
        }

        return $stats;
    }

    /**
     * Count all menu items recursively.
     */
    protected function countAllMenuItems($items)
    {
        $count = $items->count();
        
        foreach ($items as $item) {
            if ($item->children) {
                $count += $this->countAllMenuItems($item->children);
            }
        }

        return $count;
    }

    /**
     * Calculate maximum menu depth.
     */
    protected function calculateMenuDepth($items, $currentDepth = 1)
    {
        $maxDepth = $currentDepth;
        
        foreach ($items as $item) {
            if ($item->children && $item->children->count() > 0) {
                $childDepth = $this->calculateMenuDepth($item->children, $currentDepth + 1);
                $maxDepth = max($maxDepth, $childDepth);
            }
        }

        return $maxDepth;
    }

    /**
     * Get current channel ID.
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
     * Clear menu cache.
     */
    public function clearCache($channelId = null)
    {
        if ($channelId) {
            $this->cacheManager->clearChannelCache($channelId);
        } else {
            $this->cacheManager->clearAllMenuCaches();
        }
    }

    /**
     * Warm up menu cache.
     */
    public function warmupCache($channelId = null)
    {
        $this->cacheManager->warmupCaches($channelId);
    }
}
