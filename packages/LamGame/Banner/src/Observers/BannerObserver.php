<?php

namespace LamGame\Banner\Observers;

use LamGame\Banner\Models\Banner;
use LamGame\Banner\Repositories\BannerRepository;
use Illuminate\Support\Facades\Log;

class BannerObserver
{
    public function __construct(
        private BannerRepository $bannerRepository
    ) {}

    /**
     * Handle the Banner "created" event.
     */
    public function created(Banner $banner): void
    {
        $this->clearRelatedCache($banner, 'created');
    }

    /**
     * Handle the Banner "updated" event.
     */
    public function updated(Banner $banner): void
    {
        $this->clearRelatedCache($banner, 'updated');
    }

    /**
     * Handle the Banner "deleted" event.
     */
    public function deleted(Banner $banner): void
    {
        $this->clearRelatedCache($banner, 'deleted');
    }

    /**
     * Handle the Banner "restored" event.
     */
    public function restored(Banner $banner): void
    {
        $this->clearRelatedCache($banner, 'restored');
    }

    /**
     * Clear cache related to the banner.
     */
    private function clearRelatedCache(Banner $banner, string $action): void
    {
        try {
            // Clear cache for this specific banner
            $this->bannerRepository->clearBannerCache($banner);
            
            Log::info("Banner cache cleared automatically", [
                'banner_id' => $banner->id,
                'banner_name' => $banner->name,
                'position' => $banner->position,
                'action' => $action,
                'timestamp' => now()->toISOString(),
            ]);
            
        } catch (\Exception $e) {
            Log::error("Failed to clear banner cache in observer", [
                'banner_id' => $banner->id ?? 'unknown',
                'action' => $action,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }
}