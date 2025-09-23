<?php

namespace Webkul\Menu\Http\ViewComposers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Cache;
use Webkul\Menu\Repositories\MenuRepository;

class MenuComposer
{
    public function __construct(
        protected MenuRepository $menuRepository
    ) {}

    /**
     * Bind data to the view.
     */
    public function compose(View $view): void
    {
        try {
            $currentChannel = core()->getCurrentChannel();
            $cacheKey = "menu_data_channel_{$currentChannel->id}";
            
            // Cache menu data for 1 hour
            $customMenus = Cache::remember($cacheKey, 3600, function () use ($currentChannel) {
                return $this->menuRepository->getMenusForChannel($currentChannel->id);
            });

            $view->with([
                'customMenus' => $customMenus,
                'hasMenuError' => false
            ]);
        } catch (\Exception $e) {
            // Fallback with error handling
            $view->with([
                'customMenus' => collect(),
                'hasMenuError' => true,
                'menuError' => config('app.debug') ? $e->getMessage() : null
            ]);
        }
    }

    /**
     * Clear menu cache for a specific channel
     */
    public static function clearCache($channelId = null): void
    {
        if ($channelId) {
            Cache::forget("menu_data_channel_{$channelId}");
        } else {
            // Clear all channel menu caches
            $channels = core()->getAllChannels();
            foreach ($channels as $channel) {
                Cache::forget("menu_data_channel_{$channel->id}");
            }
        }
    }
}
