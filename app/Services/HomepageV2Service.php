<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class HomepageV2Service
{
    private const CACHE_TTL = 300; // 5 minutes
    private const PRODUCTS_PER_PAGE = 8;

    /**
     * Get all homepage data (cached)
     */
    public function getHomepageData(): array
    {
        return Cache::remember('homepage_v2_data', self::CACHE_TTL, function () {
            return [
                'categories' => $this->getCategories(),
                'trending' => $this->getTrendingProducts(4),
                'best_selling' => $this->getBestSellingProducts(4),
                'staff_picks' => $this->getStaffPicks(4),
                'products' => $this->getFilteredProducts(),
                'stats' => $this->getStats(),
                'latestBlogs' => $this->getLatestBlogs(3),
                'hotForumTopics' => $this->getHotForumTopics(3),
            ];
        });
    }

    /**
     * Get categories with product counts
     */
    public function getCategories(): array
    {
        $genres = DB::table('product_flat')
            ->select('genre', DB::raw('COUNT(*) as count'))
            ->where('channel', 'default')
            ->where('locale', 'vi')
            ->where('status', 1)
            ->whereNotNull('genre')
            ->groupBy('genre')
            ->orderByDesc('count')
            ->get();

        $icons = [
            'FPS' => '🎯', 'RPG' => '⚔️', 'Action' => '💥',
            'MOBA' => '🏟️', 'Racing' => '🏎️', 'Puzzle' => '🧩',
            'Strategy' => '♟️', 'Survival' => '🏕️', 'Platformer' => '🦘',
        ];

        $total = $genres->sum('count');

        $categories = [[
            'name' => 'Tất cả',
            'slug' => 'all',
            'count' => $total,
            'icon' => '🎮',
            'active' => true,
        ]];

        foreach ($genres as $genre) {
            $categories[] = [
                'name' => $genre->genre,
                'slug' => strtolower($genre->genre),
                'count' => $genre->count,
                'icon' => $icons[$genre->genre] ?? '🎮',
                'active' => false,
            ];
        }

        return $categories;
    }

    /**
     * Get trending products (highest views/recent activity)
     */
    public function getTrendingProducts(int $limit = 4): array
    {
        return $this->getProductsByBadge('trending', $limit)
            ?: $this->getProductsBySales($limit); // Fallback to sales
    }

    /**
     * Get best selling products
     */
    public function getBestSellingProducts(int $limit = 4): array
    {
        return DB::table('product_flat')
            ->select($this->productColumns())
            ->leftJoin('products', 'product_flat.product_id', '=', 'products.id')
            ->where('product_flat.channel', 'default')
            ->where('product_flat.locale', 'vi')
            ->where('product_flat.status', 1)
            ->orderByDesc('product_flat.sales_count')
            ->limit($limit)
            ->get()
            ->map(fn($p) => $this->formatProduct($p))
            ->toArray();
    }

    /**
     * Get staff picks
     */
    public function getStaffPicks(int $limit = 4): array
    {
        return DB::table('product_flat')
            ->select($this->productColumns())
            ->leftJoin('products', 'product_flat.product_id', '=', 'products.id')
            ->where('product_flat.channel', 'default')
            ->where('product_flat.locale', 'vi')
            ->where('product_flat.status', 1)
            ->where('product_flat.is_staff_pick', true)
            ->orderByDesc('product_flat.sales_count')
            ->limit($limit)
            ->get()
            ->map(fn($p) => $this->formatProduct($p))
            ->toArray();
    }

    /**
     * Get filtered products for the grid
     */
    public function getFilteredProducts(array $filters = [], string $sort = 'trending', int $page = 1): array
    {
        $query = DB::table('product_flat')
            ->select($this->productColumns())
            ->leftJoin('products', 'product_flat.product_id', '=', 'products.id')
            ->where('product_flat.channel', 'default')
            ->where('product_flat.locale', 'vi')
            ->where('product_flat.status', 1);

        // Apply filters
        if (!empty($filters['engine'])) {
            $query->where('product_flat.engine', $filters['engine']);
        }
        if (!empty($filters['genre'])) {
            $query->where('product_flat.genre', $filters['genre']);
        }
        if (!empty($filters['difficulty'])) {
            $query->where('product_flat.difficulty_level', $filters['difficulty']);
        }
        if (isset($filters['price_min'])) {
            $query->where('product_flat.display_price_usd', '>=', $filters['price_min']);
        }
        if (isset($filters['price_max']) && $filters['price_max'] > 0) {
            $query->where('product_flat.display_price_usd', '<=', $filters['price_max']);
        }

        // Sort
        switch ($sort) {
            case 'newest':
                $query->orderByDesc('product_flat.created_at');
                break;
            case 'best_selling':
                $query->orderByDesc('product_flat.sales_count');
                break;
            case 'price_low':
                $query->orderBy('product_flat.display_price_usd');
                break;
            case 'price_high':
                $query->orderByDesc('product_flat.display_price_usd');
                break;
            case 'rating':
                $query->orderByDesc('products.avg_rating');
                break;
            case 'trending':
            default:
                $query->orderByDesc('product_flat.sales_count')->orderByDesc('product_flat.created_at');
                break;
        }

        $offset = ($page - 1) * self::PRODUCTS_PER_PAGE;
        $total = (clone $query)->count();
        $products = $query->offset($offset)->limit(self::PRODUCTS_PER_PAGE)->get();

        return [
            'items' => $products->map(fn($p) => $this->formatProduct($p))->toArray(),
            'total' => $total,
            'page' => $page,
            'per_page' => self::PRODUCTS_PER_PAGE,
            'has_more' => ($offset + self::PRODUCTS_PER_PAGE) < $total,
        ];
    }

    /**
     * Get site stats
     */
    public function getStats(): array
    {
        return Cache::remember('homepage_v2_stats', 3600, function () {
            $productCount = DB::table('product_flat')
                ->where('channel', 'default')
                ->where('locale', 'vi')
                ->where('status', 1)
                ->count();

            return [
                'source_count' => max($productCount, 74),
                'developer_count' => 12000,
                'buyer_count' => 850,
                'avg_rating' => 4.9,
            ];
        });
    }

    /**
     * Get latest blog posts (game dev related)
     */
    public function getLatestBlogs(int $limit = 3): array
    {
        try {
            $blogs = DB::table('blogs')
                ->select('id', 'name', 'short_description', 'slug', 'src', 'author', 'published_at')
                ->where('status', 1)
                ->whereNotNull('published_at')
                ->orderByDesc('published_at')
                ->limit($limit)
                ->get();

            return $blogs->map(function ($blog) {
                $thumbnail = '/images/placeholder-game.svg';
                if (!empty($blog->src)) {
                    $thumbnail = '/storage/' . $blog->src;
                }

                return [
                    'title' => $blog->name ?? '',
                    'excerpt' => $blog->short_description ?? '',
                    'url' => '/blog/' . $blog->slug,
                    'thumbnail' => $thumbnail,
                    'author' => $blog->author ?? 'LamGame',
                    'date' => $blog->published_at ? \Carbon\Carbon::parse($blog->published_at)->diffForHumans() : '',
                ];
            })->toArray();
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Get hot forum topics
     */
    public function getHotForumTopics(int $limit = 3): array
    {
        try {
            $posts = DB::table('forum_posts')
                ->select('id', 'title', 'slug', 'views_count', 'likes_count', 'comments_count', 'created_at', 'customer_id')
                ->where('status', 'published')
                ->orderByDesc('views_count')
                ->limit($limit)
                ->get();

            return $posts->map(function ($post) {
                $author = DB::table('customers')->where('id', $post->customer_id)->value('first_name') ?? 'Anonymous';
                return [
                    'title' => $post->title,
                    'url' => '/forum/posts/' . $post->slug,
                    'author' => $author,
                    'category' => '',
                    'time_ago' => \Carbon\Carbon::parse($post->created_at)->diffForHumans(),
                    'replies' => $post->comments_count ?? 0,
                    'views' => $post->views_count ?? 0,
                    'likes' => $post->likes_count ?? 0,
                ];
            })->toArray();
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Columns to select for product display
     */
    private function productColumns(): array
    {
        return [
            'product_flat.id',
            'product_flat.product_id',
            'product_flat.name',
            'product_flat.short_description',
            'product_flat.url_key',
            'product_flat.price',
            'product_flat.special_price',
            'product_flat.engine',
            'product_flat.platform',
            'product_flat.genre',
            'product_flat.genre_tags',
            'product_flat.sales_count',
            'product_flat.is_staff_pick',
            'product_flat.badge_type',
            'product_flat.display_price_usd',
            'product_flat.difficulty_level',
            'product_flat.created_at',
            DB::raw('COALESCE(products.avg_rating, 0) as avg_rating'),
            DB::raw('COALESCE(products.review_count, 0) as review_count'),
        ];
    }

    /**
     * Format product for frontend
     */
    private function formatProduct($product): array
    {
        $price = $product->display_price_usd ?? ($product->price ? $product->price / 25000 : 0);

        return [
            'id' => $product->product_id ?? $product->id,
            'name' => $product->name,
            'description' => $product->short_description,
            'url' => '/source-game/' . ($product->url_key ?? ''),
            'thumbnail' => $this->getProductThumbnail($product->product_id ?? $product->id),
            'price' => round($price, 2),
            'original_price' => $product->special_price ? round($product->price / 25000, 2) : null,
            'engine' => $product->engine,
            'platform' => json_decode($product->platform ?? '[]', true) ?: ['PC', 'Mobile'],
            'genre' => $product->genre,
            'genre_tags' => json_decode($product->genre_tags ?? '[]', true) ?: [],
            'sales_count' => $product->sales_count ?? 0,
            'rating' => round($product->avg_rating ?? 4.5, 1),
            'review_count' => $product->review_count ?? 0,
            'badge' => $product->badge_type,
            'is_staff_pick' => (bool)($product->is_staff_pick ?? false),
            'difficulty' => $product->difficulty_level,
            'is_free' => $price <= 0,
        ];
    }

    /**
     * Get product thumbnail URL
     */
    private function getProductThumbnail(int $productId): string
    {
        $image = DB::table('product_images')
            ->where('product_id', $productId)
            ->orderBy('position')
            ->value('path');

        if ($image) {
            return '/storage/' . $image;
        }

        // Default placeholder
        return '/images/placeholder-game.svg';
    }

    private function getProductsByBadge(string $badge, int $limit): array
    {
        return DB::table('product_flat')
            ->select($this->productColumns())
            ->leftJoin('products', 'product_flat.product_id', '=', 'products.id')
            ->where('product_flat.channel', 'default')
            ->where('product_flat.locale', 'vi')
            ->where('product_flat.status', 1)
            ->where('product_flat.badge_type', $badge)
            ->orderByDesc('product_flat.sales_count')
            ->limit($limit)
            ->get()
            ->map(fn($p) => $this->formatProduct($p))
            ->toArray();
    }

    private function getProductsBySales(int $limit): array
    {
        return DB::table('product_flat')
            ->select($this->productColumns())
            ->leftJoin('products', 'product_flat.product_id', '=', 'products.id')
            ->where('product_flat.channel', 'default')
            ->where('product_flat.locale', 'vi')
            ->where('product_flat.status', 1)
            ->orderByDesc('product_flat.sales_count')
            ->limit($limit)
            ->get()
            ->map(fn($p) => $this->formatProduct($p))
            ->toArray();
    }
}
