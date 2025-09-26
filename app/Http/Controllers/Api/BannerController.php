<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\ForumPost;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class BannerController extends Controller
{
    /**
     * Get jobs data for banner
     */
    public function jobs(): JsonResponse
    {
        try {
            $data = Cache::remember('banner_jobs', 300, function () {
                // Simulate job data - replace with real job queries
                $jobCount = rand(40, 70);
                
                return [
                    'count' => $jobCount,
                    'companies' => ['VNG', 'Gameloft', 'Nexon', 'Amanotes', 'VTC'],
                    'latest_salary_range' => '20-45tr VNĐ',
                    'new_this_week' => rand(15, 25),
                    'updated_at' => now()->toISOString()
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Unable to fetch jobs data',
                'data' => $this->getFallbackJobsData()
            ], 500);
        }
    }

    /**
     * Get hot topics data for banner
     */
    public function topics(): JsonResponse 
    {
        try {
            $data = Cache::remember('banner_topics', 300, function () {
                // Try to get real forum data if available
                $hotTopic = ForumPost::with('comments')
                    ->where('created_at', '>=', Carbon::now()->subDays(7))
                    ->withCount(['comments', 'votes'])
                    ->orderByDesc('comments_count')
                    ->orderByDesc('votes_count')
                    ->first();

                if ($hotTopic) {
                    return [
                        'title' => $hotTopic->title,
                        'author' => $hotTopic->author->name ?? 'Anonymous',
                        'stats' => [
                            'comments' => $hotTopic->comments_count,
                            'views' => $hotTopic->views ?? rand(200, 800),
                            'likes' => $hotTopic->votes_count
                        ],
                        'url' => route('forum.post', $hotTopic->id),
                        'updated_at' => now()->toISOString()
                    ];
                }

                // Fallback to sample data
                return $this->getFallbackTopicsData();
            });

            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Unable to fetch topics data',
                'data' => $this->getFallbackTopicsData()
            ], 500);
        }
    }

    /**
     * Get latest blog data for banner
     */
    public function blogs(): JsonResponse
    {
        try {
            $data = Cache::remember('banner_blogs', 300, function () {
                // Try to get real blog data if available
                $latestBlog = Blog::where('status', 'published')
                    ->where('created_at', '>=', Carbon::now()->subDays(30))
                    ->orderByDesc('created_at')
                    ->first();

                if ($latestBlog) {
                    return [
                        'title' => $latestBlog->title,
                        'author' => $latestBlog->author->name ?? 'LamGame Team',
                        'excerpt' => substr(strip_tags($latestBlog->content), 0, 100) . '...',
                        'stats' => [
                            'views' => $latestBlog->views ?? rand(150, 400),
                            'shares' => rand(20, 80),
                            'reading_time' => $this->calculateReadingTime($latestBlog->content)
                        ],
                        'url' => route('blog.show', $latestBlog->slug),
                        'published_at' => $latestBlog->created_at->toISOString(),
                        'updated_at' => now()->toISOString()
                    ];
                }

                // Fallback to sample data
                return $this->getFallbackBlogsData();
            });

            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Unable to fetch blog data',
                'data' => $this->getFallbackBlogsData()
            ], 500);
        }
    }

    /**
     * Get sources/games data for banner
     */
    public function sources(): JsonResponse
    {
        try {
            $data = Cache::remember('banner_sources', 300, function () {
                // Simulate source/game data - replace with real queries
                $projects = [
                    'Roguelike Unity Kit',
                    'RPG Inventory System',
                    'Mobile Game Template',
                    'VR Framework Unity',
                    '2D Platformer Starter Kit',
                    'Multiplayer Networking Solution'
                ];

                $ideas = [
                    'VR adventure Việt Nam folklore',
                    'Educational game về lịch sử VN',
                    'Puzzle game với văn hóa dân gian',
                    'Multiplayer game Việt Nam theme',
                    'AR game tour du lịch Việt Nam',
                    'Indie game về đề tài môi trường'
                ];

                return [
                    'project' => $projects[array_rand($projects)],
                    'idea' => $ideas[array_rand($ideas)],
                    'stats' => [
                        'downloads' => rand(500, 2000),
                        'stars' => rand(50, 200),
                        'contributors' => rand(3, 15)
                    ],
                    'updated_at' => now()->toISOString()
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Unable to fetch sources data',
                'data' => $this->getFallbackSourcesData()
            ], 500);
        }
    }

    /**
     * Get all banner data in one request
     */
    public function all(): JsonResponse
    {
        try {
            $data = [
                'jobs' => $this->jobs()->getData()->data,
                'topics' => $this->topics()->getData()->data,
                'blogs' => $this->blogs()->getData()->data,
                'sources' => $this->sources()->getData()->data,
                'updated_at' => now()->toISOString()
            ];

            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Unable to fetch banner data',
                'data' => [
                    'jobs' => $this->getFallbackJobsData(),
                    'topics' => $this->getFallbackTopicsData(),
                    'blogs' => $this->getFallbackBlogsData(),
                    'sources' => $this->getFallbackSourcesData()
                ]
            ], 500);
        }
    }

    private function getFallbackJobsData(): array
    {
        return [
            'count' => rand(45, 65),
            'companies' => ['VNG', 'Gameloft', 'Nexon'],
            'latest_salary_range' => '20-40tr VNĐ',
            'new_this_week' => rand(15, 25),
            'updated_at' => now()->toISOString()
        ];
    }

    private function getFallbackTopicsData(): array
    {
        $topics = [
            'Unity vs Unreal cho game mobile?',
            'Cách tối ưu memory trong Unity 2023?',
            'Kinh nghiệm làm game indie Việt Nam',
            'Best practices cho multiplayer game',
            'Làm thế nào để publish game lên Steam?'
        ];

        return [
            'title' => $topics[array_rand($topics)],
            'author' => 'GameDev' . rand(1, 99),
            'stats' => [
                'comments' => rand(50, 150),
                'views' => rand(200, 600),
                'likes' => rand(30, 80)
            ],
            'updated_at' => now()->toISOString()
        ];
    }

    private function getFallbackBlogsData(): array
    {
        $blogs = [
            'Tối ưu hóa performance Unity cho game 3D',
            'Xử lý collision detection hiệu quả',
            'Kinh nghiệm phát triển game mobile Việt Nam',
            'Machine Learning trong game AI',
            'Thiết kế UI/UX cho mobile game'
        ];

        return [
            'title' => $blogs[array_rand($blogs)],
            'author' => 'LamGame Team',
            'stats' => [
                'views' => rand(100, 300),
                'shares' => rand(20, 60),
                'reading_time' => rand(5, 15) . ' phút'
            ],
            'updated_at' => now()->toISOString()
        ];
    }

    private function getFallbackSourcesData(): array
    {
        return [
            'project' => 'Roguelike Unity Kit',
            'idea' => 'VR adventure Việt Nam folklore',
            'stats' => [
                'downloads' => rand(500, 1500),
                'stars' => rand(50, 150),
                'contributors' => rand(3, 12)
            ],
            'updated_at' => now()->toISOString()
        ];
    }

    private function calculateReadingTime(string $content): string
    {
        $wordCount = str_word_count(strip_tags($content));
        $readingTimeMinutes = max(1, ceil($wordCount / 200)); // Average 200 words per minute
        
        return $readingTimeMinutes . ' phút đọc';
    }
}