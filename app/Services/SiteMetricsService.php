<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * Service to provide verified site metrics for E-E-A-T compliance
 * 
 * All metrics are pulled directly from database to ensure accuracy.
 * Cached for 1 hour to balance performance and freshness.
 */
class SiteMetricsService
{
    /**
     * Cache TTL in seconds (1 hour)
     */
    protected const CACHE_TTL = 3600;

    /**
     * Cache key prefix
     */
    protected const CACHE_KEY = 'site_metrics';

    /**
     * Get all site metrics
     */
    public function getMetrics(): array
    {
        return Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () {
            return [
                'registered_users' => $this->getRegisteredUsersCount(),
                'published_sources' => $this->getPublishedSourcesCount(),
                'forum_posts' => $this->getForumPostsCount(),
                'job_listings' => $this->getActiveJobsCount(),
                'total_downloads' => $this->getTotalDownloadsCount(),
                'total_orders' => $this->getTotalOrdersCount(),
                'blog_posts' => $this->getBlogPostsCount(),
                'sellers' => $this->getActiveSellersCount(),
                'updated_at' => now()->toIso8601String(),
            ];
        });
    }

    /**
     * Get single metric
     */
    public function getMetric(string $key): mixed
    {
        $metrics = $this->getMetrics();
        return $metrics[$key] ?? null;
    }

    /**
     * Clear cached metrics (call after significant data changes)
     */
    public function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }

    /**
     * Count of registered customers/developers
     */
    protected function getRegisteredUsersCount(): int
    {
        return DB::table('customers')
            ->where('status', 1)
            ->count();
    }

    /**
     * Count of published source game products
     */
    protected function getPublishedSourcesCount(): int
    {
        return app(SourceGameCatalogService::class)->publishedCount();
    }

    /**
     * Count of active forum posts
     */
    protected function getForumPostsCount(): int
    {
        return DB::table('forum_posts')
            ->where('status', 'published')
            ->count();
    }

    /**
     * Count of active job postings (game-related only)
     */
    protected function getActiveJobsCount(): int
    {
        return DB::table('job_postings')
            ->where('status', 'active')
            ->where('is_game_related', true)
            ->whereNull('deleted_at')
            ->where(function ($query) {
                $query->whereNull('application_deadline')
                    ->orWhere('application_deadline', '>=', now());
            })
            ->count();
    }

    /**
     * Total downloads/purchases of source games
     */
    protected function getTotalDownloadsCount(): int
    {
        return DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('product_flat', 'order_items.product_id', '=', 'product_flat.product_id')
            ->where('product_flat.type', 'downloadable')
            ->where('orders.status', 'completed')
            ->count();
    }

    /**
     * Total completed orders
     */
    protected function getTotalOrdersCount(): int
    {
        return DB::table('orders')
            ->where('status', 'completed')
            ->count();
    }

    /**
     * Count of published blog posts
     */
    protected function getBlogPostsCount(): int
    {
        return DB::table('blogs')
            ->where('status', 'published')
            ->whereNull('deleted_at')
            ->count();
    }

    /**
     * Count of active sellers
     */
    protected function getActiveSellersCount(): int
    {
        return DB::table('source_game_sellers')
            ->where('status', 'approved')
            ->count();
    }

    /**
     * Get metrics formatted for display
     */
    public function getFormattedMetrics(): array
    {
        $metrics = $this->getMetrics();
        
        return [
            'registered_users' => $this->formatNumber($metrics['registered_users']),
            'published_sources' => $this->formatNumber($metrics['published_sources']),
            'forum_posts' => $this->formatNumber($metrics['forum_posts']),
            'job_listings' => $this->formatNumber($metrics['job_listings']),
            'total_downloads' => $this->formatNumber($metrics['total_downloads']),
            'blog_posts' => $this->formatNumber($metrics['blog_posts']),
        ];
    }

    /**
     * Format number for display (e.g., 1234 -> "1.2K")
     */
    protected function formatNumber(int $number): string
    {
        if ($number >= 1000000) {
            return round($number / 1000000, 1) . 'M';
        }
        if ($number >= 1000) {
            return round($number / 1000, 1) . 'K';
        }
        return (string) $number;
    }

    /**
     * Get metrics with human-readable labels (for API/debug)
     */
    public function getMetricsWithLabels(): array
    {
        $metrics = $this->getMetrics();
        
        return [
            [
                'key' => 'registered_users',
                'value' => $metrics['registered_users'],
                'label' => 'Registered Developers',
                'description' => 'Email-verified user accounts',
            ],
            [
                'key' => 'published_sources',
                'value' => $metrics['published_sources'],
                'label' => 'Source Codes',
                'description' => 'Active downloadable products',
            ],
            [
                'key' => 'forum_posts',
                'value' => $metrics['forum_posts'],
                'label' => 'Forum Discussions',
                'description' => 'Active forum posts',
            ],
            [
                'key' => 'job_listings',
                'value' => $metrics['job_listings'],
                'label' => 'Job Listings',
                'description' => 'Active and non-expired job postings',
            ],
            [
                'key' => 'total_downloads',
                'value' => $metrics['total_downloads'],
                'label' => 'Total Downloads',
                'description' => 'Completed source game orders',
            ],
            [
                'key' => 'blog_posts',
                'value' => $metrics['blog_posts'],
                'label' => 'Blog Posts',
                'description' => 'Published blog articles',
            ],
        ];
    }
}
