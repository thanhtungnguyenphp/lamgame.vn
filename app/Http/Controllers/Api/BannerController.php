<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\ForumPost;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class BannerController extends Controller
{
    /**
     * Return current job counts from published job products.
     */
    public function jobs(): JsonResponse
    {
        try {
            $data = Cache::remember('banner_jobs_truth_v2', 300, function (): array {
                $jobs = DB::table('products as p')
                    ->join('product_flat as pf', function ($join) {
                        $join->on('p.id', '=', 'pf.product_id')
                            ->where('pf.locale', '=', 'vi');
                    })
                    ->where('p.sku', 'like', 'JOB_%')
                    ->where('pf.status', 1)
                    ->where('pf.visible_individually', 1);

                $companies = (clone $jobs)
                    ->pluck('pf.name')
                    ->map(function ($name) {
                        $parts = explode(' - ', (string) $name, 2);

                        return isset($parts[1]) ? trim($parts[1]) : null;
                    })
                    ->filter()
                    ->unique()
                    ->take(5)
                    ->values()
                    ->all();

                return [
                    'count' => (clone $jobs)->count(),
                    'companies' => $companies,
                    'latest_salary_range' => null,
                    'new_this_week' => (clone $jobs)->where('p.created_at', '>=', now()->subWeek())->count(),
                    'updated_at' => now()->toISOString(),
                ];
            });

            return $this->success($data);
        } catch (\Throwable $e) {
            report($e);

            return $this->failure('Unable to fetch jobs data', $this->getFallbackJobsData());
        }
    }

    /**
     * Return the most active published forum topic using stored counters only.
     */
    public function topics(): JsonResponse
    {
        try {
            $data = Cache::remember('banner_topics_truth_v2', 300, function (): array {
                $topic = ForumPost::published()
                    ->where('created_at', '>=', now()->subDays(7))
                    ->orderByDesc('comments_count')
                    ->orderByDesc('likes_count')
                    ->first();

                if (! $topic) {
                    return $this->getFallbackTopicsData();
                }

                return [
                    'title' => $topic->title,
                    'author' => $topic->author_name,
                    'stats' => [
                        'comments' => (int) ($topic->comments_count ?? 0),
                        'views' => (int) ($topic->views_count ?? 0),
                        'likes' => (int) ($topic->likes_count ?? 0),
                    ],
                    'url' => route('forum.posts.show', $topic->slug),
                    'updated_at' => now()->toISOString(),
                ];
            });

            return $this->success($data);
        } catch (\Throwable $e) {
            report($e);

            return $this->failure('Unable to fetch topics data', $this->getFallbackTopicsData());
        }
    }

    /**
     * Return the latest published blog and its stored metrics.
     */
    public function blogs(): JsonResponse
    {
        try {
            $data = Cache::remember('banner_blogs_truth_v2', 300, function (): array {
                $blog = Blog::published()
                    ->orderByDesc('published_at')
                    ->first();

                if (! $blog) {
                    return $this->getFallbackBlogsData();
                }

                return [
                    'title' => $blog->name,
                    'author' => $blog->author ?: 'LamGame Team',
                    'excerpt' => $blog->short_description ?: '',
                    'stats' => [
                        'views' => (int) ($blog->views ?? 0),
                        'shares' => (int) ($blog->shares ?? 0),
                        'reading_time' => $blog->reading_time
                            ? ((int) $blog->reading_time).' phút đọc'
                            : $this->calculateReadingTime((string) ($blog->content ?? '')),
                    ],
                    'url' => route('blog.show', $blog->slug),
                    'published_at' => optional($blog->published_at)->toISOString(),
                    'updated_at' => now()->toISOString(),
                ];
            });

            return $this->success($data);
        } catch (\Throwable $e) {
            report($e);

            return $this->failure('Unable to fetch blog data', $this->getFallbackBlogsData());
        }
    }

    /**
     * Return a real downloadable product and confirmed purchase count.
     * There is no download-event table, so downloads remains zero rather than estimated.
     */
    public function sources(): JsonResponse
    {
        try {
            $data = Cache::remember('banner_sources_truth_v2', 300, function (): array {
                $product = DB::table('products as p')
                    ->join('product_flat as pf', function ($join) {
                        $join->on('p.id', '=', 'pf.product_id')
                            ->where('pf.locale', '=', 'vi');
                    })
                    ->where('p.type', 'downloadable')
                    ->where('pf.status', 1)
                    ->orderByDesc('p.updated_at')
                    ->select('p.id', 'pf.name')
                    ->first();

                if (! $product) {
                    return $this->getFallbackSourcesData();
                }

                $purchases = DB::table('order_items')
                    ->join('orders', 'orders.id', '=', 'order_items.order_id')
                    ->where('order_items.product_id', $product->id)
                    ->whereIn('orders.status', ['processing', 'completed'])
                    ->count();

                return [
                    'project' => $product->name,
                    'idea' => null,
                    'stats' => [
                        'downloads' => 0,
                        'purchases' => $purchases,
                        'stars' => 0,
                        'contributors' => 0,
                    ],
                    'updated_at' => now()->toISOString(),
                ];
            });

            return $this->success($data);
        } catch (\Throwable $e) {
            report($e);

            return $this->failure('Unable to fetch sources data', $this->getFallbackSourcesData());
        }
    }

    /**
     * Return all banner data in one request.
     */
    public function all(): JsonResponse
    {
        try {
            return $this->success([
                'jobs' => $this->responseData($this->jobs()),
                'topics' => $this->responseData($this->topics()),
                'blogs' => $this->responseData($this->blogs()),
                'sources' => $this->responseData($this->sources()),
                'updated_at' => now()->toISOString(),
            ]);
        } catch (\Throwable $e) {
            report($e);

            return response()->json([
                'success' => false,
                'message' => 'Unable to fetch banner data',
                'data' => [
                    'jobs' => $this->getFallbackJobsData(),
                    'topics' => $this->getFallbackTopicsData(),
                    'blogs' => $this->getFallbackBlogsData(),
                    'sources' => $this->getFallbackSourcesData(),
                ],
            ], 500);
        }
    }

    private function success(array $data): JsonResponse
    {
        return response()->json(['success' => true, 'data' => $data]);
    }

    private function failure(string $message, array $data): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'data' => $data,
        ], 500);
    }

    private function responseData(JsonResponse $response): array
    {
        return (array) ($response->getData(true)['data'] ?? []);
    }

    private function getFallbackJobsData(): array
    {
        return [
            'count' => 0,
            'companies' => [],
            'latest_salary_range' => null,
            'new_this_week' => 0,
            'updated_at' => now()->toISOString(),
        ];
    }

    private function getFallbackTopicsData(): array
    {
        return [
            'title' => null,
            'author' => null,
            'stats' => ['comments' => 0, 'views' => 0, 'likes' => 0],
            'url' => route('forum.index'),
            'updated_at' => now()->toISOString(),
        ];
    }

    private function getFallbackBlogsData(): array
    {
        return [
            'title' => null,
            'author' => null,
            'excerpt' => '',
            'stats' => ['views' => 0, 'shares' => 0, 'reading_time' => '0 phút đọc'],
            'url' => null,
            'published_at' => null,
            'updated_at' => now()->toISOString(),
        ];
    }

    private function getFallbackSourcesData(): array
    {
        return [
            'project' => null,
            'idea' => null,
            'stats' => [
                'downloads' => 0,
                'purchases' => 0,
                'stars' => 0,
                'contributors' => 0,
            ],
            'updated_at' => now()->toISOString(),
        ];
    }

    private function calculateReadingTime(string $content): string
    {
        $wordCount = str_word_count(strip_tags($content));
        $readingTimeMinutes = $wordCount > 0 ? max(1, (int) ceil($wordCount / 200)) : 0;

        return $readingTimeMinutes.' phút đọc';
    }
}
