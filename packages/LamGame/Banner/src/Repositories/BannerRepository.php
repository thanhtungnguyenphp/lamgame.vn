<?php

namespace LamGame\Banner\Repositories;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use LamGame\Banner\Models\Banner;
use Webkul\Core\Eloquent\Repository;

class BannerRepository extends Repository
{
    /**
     * Specify model class name.
     */
    public function model(): string
    {
        return Banner::class;
    }

    /**
     * Cache TTL in seconds (1 hour)
     */
    private const CACHE_TTL = 3600;

    /**
     * Get banners for display with caching.
     */
    public function getBannersForDisplay(array $filters = []): Collection
    {
        $cacheKey = $this->generateCacheKey($filters);
        
        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($filters) {
            Log::info('Banner cache miss, fetching from database', $filters);
            
            return $this->model
                ->forDisplay($filters)
                ->with(['translations', 'channel'])
                ->get()
                ->map(function ($banner) {
                    return $this->transformBannerForApi($banner);
                });
        });
    }

    /**
     * Get banner by position with caching.
     */
    public function getByPosition(
        string $position,
        string $deviceType = 'all',
        ?int $channelId = null,
        ?string $locale = null,
        int $limit = null
    ): Collection {
        $filters = [
            'position' => $position,
            'device_type' => $deviceType,
            'channel_id' => $channelId,
            'locale' => $locale,
        ];

        $banners = $this->getBannersForDisplay($filters);

        return $limit ? $banners->take($limit) : $banners;
    }

    /**
     * Increment banner impressions.
     */
    public function incrementImpressions(int $bannerId): void
    {
        try {
            $banner = $this->find($bannerId);
            if ($banner) {
                $banner->incrementImpressions();
                
                // Clear cache for this banner's positions
                $this->clearBannerCache($banner);
            }
        } catch (\Exception $e) {
            Log::error('Failed to increment banner impressions', [
                'banner_id' => $bannerId,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Increment banner clicks.
     */
    public function incrementClicks(int $bannerId): void
    {
        try {
            $banner = $this->find($bannerId);
            if ($banner) {
                $banner->incrementClicks();
                
                // Clear cache for this banner's positions
                $this->clearBannerCache($banner);
            }
        } catch (\Exception $e) {
            Log::error('Failed to increment banner clicks', [
                'banner_id' => $bannerId,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Clear all banner caches.
     */
    public function clearAllCache(): void
    {
        try {
            // For Redis cache driver
            if (config('cache.default') === 'redis' && method_exists(Cache::getStore(), 'getRedis')) {
                $patterns = [
                    'banners:*',
                    'banner_display:*',
                ];

                foreach ($patterns as $pattern) {
                    $keys = Cache::getRedis()->keys($pattern);
                    if (!empty($keys)) {
                        Cache::getRedis()->del($keys);
                    }
                }
            } else {
                // For other cache drivers (file, array, etc.), flush all cache
                // This is less efficient but works with any cache driver
                Cache::flush();
            }
            
            Log::info('All banner caches cleared');
        } catch (\Exception $e) {
            Log::warning('Failed to clear banner cache, falling back to cache flush', [
                'error' => $e->getMessage()
            ]);
            
            // Fallback: just flush all cache
            Cache::flush();
        }
    }

    /**
     * Clear cache for specific banner.
     */
    public function clearBannerCache(Banner $banner): void
    {
        // Clear the main cache key (for /api/banners endpoint)
        $mainCacheKey = $this->generateCacheKey([]);
        Cache::forget($mainCacheKey);
        
        // Clear caches for all possible combinations of this banner
        $positions = [$banner->position, '*', null];
        $devices = ['all', 'desktop', 'tablet', 'mobile', '*', null];
        $channels = [$banner->channel_id, null, '*'];
        $locales = [$banner->locale, null, '*'];

        foreach ($positions as $position) {
            foreach ($devices as $device) {
                foreach ($channels as $channel) {
                    foreach ($locales as $locale) {
                        $cacheKey = $this->generateCacheKey([
                            'position' => $position,
                            'device_type' => $device,
                            'channel_id' => $channel,
                            'locale' => $locale,
                        ]);
                        Cache::forget($cacheKey);
                    }
                }
            }
        }

        Log::info('Banner cache cleared', ['banner_id' => $banner->id, 'main_cache_key' => $mainCacheKey]);
    }

    /**
     * Get banner analytics data.
     */
    public function getAnalytics(array $filters = []): array
    {
        $query = $this->model->query();

        if (isset($filters['position'])) {
            $query->where('position', $filters['position']);
        }

        if (isset($filters['device_type'])) {
            $query->where('device_type', $filters['device_type']);
        }

        if (isset($filters['channel_id'])) {
            $query->where('channel_id', $filters['channel_id']);
        }

        if (isset($filters['date_from'])) {
            $query->where('created_at', '>=', $filters['date_from']);
        }

        if (isset($filters['date_to'])) {
            $query->where('created_at', '<=', $filters['date_to']);
        }

        $banners = $query->get();

        return [
            'total_banners' => $banners->count(),
            'active_banners' => $banners->where('status', true)->count(),
            'total_impressions' => $banners->sum('impressions_count'),
            'total_clicks' => $banners->sum('clicks_count'),
            'average_ctr' => $banners->avg(function ($banner) {
                return $banner->getClickThroughRate();
            }),
            'top_performers' => $banners->sortByDesc(function ($banner) {
                return $banner->getClickThroughRate();
            })->take(5)->values(),
            'by_position' => $banners->groupBy('position')->map(function ($group, $position) {
                return [
                    'position' => $position,
                    'count' => $group->count(),
                    'impressions' => $group->sum('impressions_count'),
                    'clicks' => $group->sum('clicks_count'),
                    'ctr' => $group->avg(function ($banner) {
                        return $banner->getClickThroughRate();
                    }),
                ];
            })->values(),
        ];
    }

    /**
     * Generate cache key for banner filters.
     */
    private function generateCacheKey(array $filters): string
    {
        $key = 'banner_display:';
        $key .= 'pos_' . ($filters['position'] ?? 'all');
        $key .= ':dev_' . ($filters['device_type'] ?? 'all');
        $key .= ':ch_' . ($filters['channel_id'] ?? 'all');
        $key .= ':loc_' . ($filters['locale'] ?? 'all');
        
        return $key;
    }

    /**
     * Transform banner for API response.
     */
    private function transformBannerForApi(Banner $banner): array
    {
        return [
            'id' => $banner->id,
            'name' => $banner->name,
            'type' => $banner->type,
            'position' => $banner->position,
            'device_type' => $banner->device_type,
            'title' => $banner->title,
            'content' => $banner->content,
            'image' => $banner->image_url,
            'responsive_images' => $banner->responsive_images,
            'image_alt' => $banner->image_alt,
            'focal_point' => $banner->focal_point ?? 'center 30%',
            'link' => $banner->link,
            'target' => $banner->target,
            'css_classes' => $banner->css_classes_string,
            'html_attributes' => $this->getHtmlAttributesString($banner),
            'settings' => $banner->settings,
            'sort_order' => $banner->sort_order,
            'start_date' => $banner->start_date?->toISOString(),
            'end_date' => $banner->end_date?->toISOString(),
            'is_active' => $banner->is_active,
            'channel' => $banner->channel ? [
                'id' => $banner->channel->id,
                'name' => $banner->channel->name,
                'code' => $banner->channel->code,
            ] : null,
        ];
    }

    /**
     * Delete a banner by ID.
     */
    public function deleteBanner(int $id): array
    {
        try {
            $banner = $this->find($id);
            
            if (!$banner) {
                throw new \Illuminate\Database\Eloquent\ModelNotFoundException('Banner not found');
            }

            // Store banner data before deletion for response
            $bannerData = [
                'id' => $banner->id,
                'name' => $banner->name,
                'position' => $banner->position,
                'type' => $banner->type,
            ];

            // Clear cache before deletion
            $this->clearBannerCache($banner);
            
            // Delete the banner
            $deleted = $banner->delete();
            
            if ($deleted) {
                // Clear all banner caches to ensure consistency
                $this->clearAllCache();
                
                Log::info('Banner deleted from repository', [
                    'banner_id' => $id,
                    'banner_name' => $bannerData['name'],
                    'deleted_at' => now()->toISOString(),
                ]);
            }
            
            return $bannerData;
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::warning('Attempted to delete non-existent banner', ['banner_id' => $id]);
            throw $e;
        } catch (\Exception $e) {
            Log::error('Failed to delete banner in repository', [
                'banner_id' => $id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Soft delete a banner (if using soft deletes).
     */
    public function softDelete(int $id): array
    {
        try {
            $banner = $this->find($id);
            
            if (!$banner) {
                throw new \Illuminate\Database\Eloquent\ModelNotFoundException('Banner not found');
            }

            // Store banner data before soft deletion
            $bannerData = [
                'id' => $banner->id,
                'name' => $banner->name,
                'position' => $banner->position,
                'type' => $banner->type,
                'status' => 'soft_deleted',
            ];

            // Update status instead of deleting
            $banner->update([
                'status' => false,
                'deleted_at' => now(),
            ]);
            
            // Clear cache
            $this->clearBannerCache($banner);
            $this->clearAllCache();
            
            Log::info('Banner soft deleted from repository', [
                'banner_id' => $id,
                'banner_name' => $bannerData['name'],
                'soft_deleted_at' => now()->toISOString(),
            ]);
            
            return $bannerData;
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::warning('Attempted to soft delete non-existent banner', ['banner_id' => $id]);
            throw $e;
        } catch (\Exception $e) {
            Log::error('Failed to soft delete banner in repository', [
                'banner_id' => $id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Get HTML attributes as string.
     */
    private function getHtmlAttributesString(Banner $banner): string
    {
        if (is_array($banner->attributes)) {
            return collect($banner->attributes)
                ->map(function ($value, $key) {
                    return "{$key}=\"{$value}\"";
                })
                ->implode(' ');
        }
        
        return $banner->attributes ?? '';
    }
}
