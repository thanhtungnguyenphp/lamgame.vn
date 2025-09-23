<?php

namespace Webkul\Menu\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Event;
use Webkul\Menu\Models\Menu;
use Webkul\Menu\Repositories\FrontendMenuRepository;

class MenuCacheManager
{
    /**
     * The frontend menu repository.
     */
    protected $menuRepository;

    /**
     * Cache configuration.
     */
    protected $config;

    public function __construct(FrontendMenuRepository $menuRepository)
    {
        $this->menuRepository = $menuRepository;
        $this->config = config('menu.cache', []);
    }

    /**
     * Get cache key for frontend menus.
     */
    public function getFrontendCacheKey($channelId, $type = 'menus')
    {
        $prefix = $this->config['prefix'] ?? 'menu_';
        return "{$prefix}frontend_{$type}_{$channelId}";
    }

    /**
     * Get cache key for specific menu.
     */
    public function getMenuCacheKey($menuName, $channelId)
    {
        $prefix = $this->config['prefix'] ?? 'menu_';
        return "{$prefix}menu_{$menuName}_{$channelId}";
    }

    /**
     * Cache frontend menus for a channel.
     */
    public function cacheFrontendMenus($channelId)
    {
        $cacheKey = $this->getFrontendCacheKey($channelId);
        $duration = $this->config['strategies']['frontend']['duration'] ?? 3600;
        $tags = $this->config['strategies']['frontend']['tags'] ?? ['frontend_menus', 'menus'];

        return Cache::tags($tags)->remember($cacheKey, $duration, function () use ($channelId) {
            return $this->menuRepository->getMenusForChannel($channelId);
        });
    }

    /**
     * Cache specific menu by name.
     */
    public function cacheMenu($menuName, $channelId)
    {
        $cacheKey = $this->getMenuCacheKey($menuName, $channelId);
        $duration = $this->config['strategies']['frontend']['duration'] ?? 3600;
        $tags = $this->config['strategies']['frontend']['tags'] ?? ['frontend_menus', 'menus'];

        return Cache::tags($tags)->remember($cacheKey, $duration, function () use ($menuName, $channelId) {
            return $this->menuRepository->getMenuByName($menuName, $channelId);
        });
    }

    /**
     * Clear all menu caches.
     */
    public function clearAllMenuCaches()
    {
        $tags = $this->config['tags'] ?? ['menus'];
        
        if (method_exists(Cache::getStore(), 'tags')) {
            Cache::tags($tags)->flush();
        } else {
            // Fallback for cache stores that don't support tags
            $this->clearChannelCaches();
        }

        Event::dispatch('menu.cache.cleared.all');
    }

    /**
     * Clear menu caches for a specific channel.
     */
    public function clearChannelCache($channelId)
    {
        $keys = [
            $this->getFrontendCacheKey($channelId),
        ];

        // Also clear specific menu caches for this channel
        $menus = Menu::where('channel_id', $channelId)->pluck('name');
        foreach ($menus as $menuName) {
            $keys[] = $this->getMenuCacheKey($menuName, $channelId);
        }

        foreach ($keys as $key) {
            Cache::forget($key);
        }

        Event::dispatch('menu.cache.cleared.channel', $channelId);
    }

    /**
     * Clear caches for all channels.
     */
    public function clearChannelCaches($channelIds = null)
    {
        if ($channelIds === null) {
            $channelIds = \DB::table('channels')->pluck('id');
        }

        if (!is_array($channelIds)) {
            $channelIds = [$channelIds];
        }

        foreach ($channelIds as $channelId) {
            $this->clearChannelCache($channelId);
        }
    }

    /**
     * Clear cache for specific menu.
     */
    public function clearMenuCache($menuName, $channelId)
    {
        $keys = [
            $this->getMenuCacheKey($menuName, $channelId),
            $this->getFrontendCacheKey($channelId), // Also clear channel cache
        ];

        foreach ($keys as $key) {
            Cache::forget($key);
        }

        Event::dispatch('menu.cache.cleared.menu', [$menuName, $channelId]);
    }

    /**
     * Warm up menu caches.
     */
    public function warmupCaches($channelIds = null)
    {
        if (!$this->config['warmup']['enabled'] ?? true) {
            return;
        }

        if ($channelIds === null) {
            $channelIds = $this->config['warmup']['channels'] === 'all' 
                ? \DB::table('channels')->pluck('id')->toArray()
                : (array) $this->config['warmup']['channels'];
        }

        if (!is_array($channelIds)) {
            $channelIds = [$channelIds];
        }

        foreach ($channelIds as $channelId) {
            $this->warmupChannelCache($channelId);
        }

        Event::dispatch('menu.cache.warmed_up', $channelIds);
    }

    /**
     * Warm up cache for specific channel.
     */
    public function warmupChannelCache($channelId)
    {
        // Load frontend menus into cache
        $this->cacheFrontendMenus($channelId);

        // Load individual menus into cache
        $menus = Menu::where('channel_id', $channelId)->pluck('name');
        foreach ($menus as $menuName) {
            $this->cacheMenu($menuName, $channelId);
        }
    }

    /**
     * Get cache statistics.
     */
    public function getCacheStats()
    {
        $stats = [
            'frontend_enabled' => $this->config['strategies']['frontend']['enabled'] ?? true,
            'cache_duration' => $this->config['duration'] ?? 3600,
            'cached_channels' => [],
            'total_cached_menus' => 0,
        ];

        // Count cached items (implementation depends on cache driver)
        $channelIds = \DB::table('channels')->pluck('id');
        
        foreach ($channelIds as $channelId) {
            $cacheKey = $this->getFrontendCacheKey($channelId);
            $isCached = Cache::has($cacheKey);
            
            if ($isCached) {
                $stats['cached_channels'][] = $channelId;
                $menuCount = Menu::where('channel_id', $channelId)->count();
                $stats['total_cached_menus'] += $menuCount;
            }
        }

        return $stats;
    }

    /**
     * Register cache event listeners.
     */
    public function registerEventListeners()
    {
        $events = $this->config['auto_clear_events'] ?? [];

        foreach ($events as $event) {
            Event::listen($event, function ($data) {
                $this->handleCacheInvalidation($data);
            });
        }
    }

    /**
     * Handle cache invalidation based on events.
     */
    protected function handleCacheInvalidation($data)
    {
        if (is_object($data) && method_exists($data, 'getChannel')) {
            $channelId = $data->getChannel();
            $this->clearChannelCache($channelId);
        } elseif (is_array($data) && isset($data['channel_id'])) {
            $this->clearChannelCache($data['channel_id']);
        } else {
            // Clear all caches as fallback
            $this->clearAllMenuCaches();
        }
    }

    /**
     * Check if caching is enabled for a strategy.
     */
    public function isCacheEnabled($strategy = 'frontend')
    {
        return $this->config['strategies'][$strategy]['enabled'] ?? true;
    }

    /**
     * Get cache duration for a strategy.
     */
    public function getCacheDuration($strategy = 'frontend')
    {
        return $this->config['strategies'][$strategy]['duration'] ?? 3600;
    }
}
