<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\ForumPost;
use App\Models\ForumCategory;

class HomeController extends Controller
{
    /**
     * Show homepage with dynamic content from database
     * 70% community content, 30% curated content
     */
    public function index()
    {
        // Cache homepage data for 5 minutes
        $homeData = Cache::remember('homepage_data', 300, function () {
            return $this->getHomepageData();
        });

        return view('home.index', array_merge($homeData, [
            'page_title' => 'LamGame.vn — Cộng đồng Game Developer Việt Nam | Việc làm Game Dev',
            'page_description' => 'Cộng đồng Game Developer Việt Nam hàng đầu. Tìm việc làm game dev, thảo luận Unity/Unreal Engine, chia sẻ source code và ý tưởng game sáng tạo. 50+ jobs mới mỗi tuần từ VNG, Gameloft.'
        ]));
    }

    /**
     * Get all dynamic data for homepage
     */
    private function getHomepageData()
    {
        return [
            // Jobs section - from product database
            'jobs' => $this->getJobsData(),
            
            // Forum hot topics - 40% of content (community)
            'hotForumTopics' => $this->getHotForumTopics(),
            
            // Latest blogs from developers - 30% of content (community)
            'latestBlogs' => $this->getLatestBlogs(),
            
            // News & reviews - 20% of content (curated)
            'news' => $this->getNewsData(),
            
            // Events & team building - 10% of content (curated)
            'events' => $this->getEventsData(),
            
            // Statistics for dynamic banner
            'stats' => $this->getStatistics(),
            
            // Source games & prototypes
            'sourceGames' => $this->getSourceGames(),
        ];
    }

    /**
     * Get jobs data from products table (30% curated)
     */
    private function getJobsData()
    {
        $jobs = DB::table('products as p')
            ->leftJoin('product_flat as pf', function($join) {
                $join->on('p.id', '=', 'pf.product_id')
                     ->where('pf.locale', '=', 'vi');
            })
            ->leftJoin('product_categories as pc', 'p.id', '=', 'pc.product_id')
            ->leftJoin('category_translations as ct', function($join) {
                $join->on('pc.category_id', '=', 'ct.category_id')
                     ->where('ct.locale', '=', 'vi');
            })
            ->where('p.sku', 'LIKE', 'JOB_%')
            ->where('pf.status', 1)
            ->where('pf.visible_individually', 1)
            ->select(
                'p.id',
                'pf.name',
                'pf.short_description',
                'pf.price',
                'ct.name as company',
                'p.created_at'
            )
            ->orderBy('p.created_at', 'desc')
            ->limit(6)
            ->get();

        // Transform jobs data
        $jobsData = [];
        foreach ($jobs as $job) {
            $parts = explode(' - ', $job->name);
            $jobsData[] = [
                'id' => $job->id,
                'title' => $parts[0] ?? $job->name,
                'company' => trim($parts[1] ?? $job->company ?? 'Studio Game VN'),
                'salary' => number_format($job->price / 1000000, 1) . ' triệu VND',
                'location' => $this->extractLocation($job->short_description),
                'posted_ago' => \Carbon\Carbon::parse($job->created_at)->diffForHumans(),
                'url' => route('lamgame.viec-lam-game') . '/' . $job->id,
            ];
        }

        return [
            'featured' => $jobsData,
            'total_count' => $this->getJobsCount(),
            'weekly_new' => $this->getWeeklyJobsCount(),
        ];
    }

    /**
     * Get hot forum topics (40% community) - Top 6 "Chủ Đề Nổi Bật"
     */
    private function getHotForumTopics()
    {
        try {
            // Lấy top 6 topic nổi bật dựa trên comments và likes
            $hotTopics = ForumPost::published()
                ->with(['category', 'comments' => function($query) {
                    $query->published()
                        ->orderBy('created_at', 'desc')
                        ->take(1); // Lấy comment mới nhất để làm snippet
                }])
                ->where(function($query) {
                    // Ưu tiên posts có nhiều comments hoặc likes hoặc featured
                    $query->where('comments_count', '>', 3)
                          ->orWhere('likes_count', '>', 5)
                          ->orWhere('is_featured', true);
                })
                // Tính toán hot score: comments * 2 + likes + views/10
                ->orderByRaw('(comments_count * 2 + likes_count + views_count/10) DESC')
                ->limit(6)
                ->get();

            // Nếu không đủ 6 posts, lấy thêm posts mới nhất
            if ($hotTopics->count() < 6) {
                $excludeIds = $hotTopics->pluck('id')->toArray();
                $additionalTopics = ForumPost::published()
                    ->with(['category', 'comments' => function($query) {
                        $query->published()->orderBy('created_at', 'desc')->take(1);
                    }])
                    ->whereNotIn('id', $excludeIds)
                    ->orderBy('created_at', 'desc')
                    ->limit(6 - $hotTopics->count())
                    ->get();
                
                $hotTopics = $hotTopics->merge($additionalTopics);
            }

            $topicsData = [];
            foreach ($hotTopics as $topic) {
                // Lấy comment snippet cho teaser
                $latestComment = $topic->comments->first();
                $commentSnippet = $latestComment ? 
                    \Illuminate\Support\Str::limit(strip_tags($latestComment->content), 60) : 
                    '';
                
                $topicsData[] = [
                    'id' => $topic->id,
                    'title' => $topic->title,
                    'author' => $topic->author_name,
                    'category' => $topic->category->name ?? 'General',
                    'category_icon' => $topic->category->icon ?? '💬',
                    'category_color' => $topic->category->color ?? '#667eea',
                    'replies' => $topic->comments_count,
                    'views' => $topic->views_count,
                    'likes' => $topic->likes_count,
                    'created_at' => $topic->created_at->format('Y-m-d H:i:s'),
                    'time_ago' => $topic->created_at->diffForHumans(),
                    'excerpt' => $topic->excerpt,
                    'comment_snippet' => $commentSnippet,
                    'latest_comment_author' => $latestComment ? $latestComment->author_name : '',
                    'url' => route('forum.posts.show', $topic->slug),
                    'forum_url' => route('forum.index'),
                ];
            }

            return [
                'featured' => $topicsData,
                'total_posts' => ForumPost::published()->count(),
                'active_discussions' => ForumPost::published()->whereDate('last_comment_at', '>=', now()->subDays(1))->count(),
            ];
        } catch (\Exception $e) {
            // Fallback data nếu có lỗi
            return [
                'featured' => [
                    [
                        'id' => 1,
                        'title' => 'Share source code AR game',
                        'author' => 'GameDev_VN',
                        'category' => 'Chia sẻ ý tưởng',
                        'category_icon' => '💡',
                        'category_color' => '#ffd700',
                        'replies' => 300,
                        'views' => 1250,
                        'likes' => 85,
                        'time_ago' => '2 giờ trước',
                        'excerpt' => 'Mình đang phát triển AR game với Unity, muốn chia sẻ source code để cộng đồng cùng học hỏi.',
                        'comment_snippet' => 'Cảm ơn bạn! Source này rất hữu ích cho Unity developer...',
                        'latest_comment_author' => 'UnityExpert',
                        'url' => '#',
                        'forum_url' => route('forum.index'),
                    ],
                    [
                        'id' => 2,
                        'title' => 'Ý tưởng game dựa trên lịch sử VN',
                        'author' => 'HistoryGamer',
                        'category' => 'Thảo luận',
                        'category_icon' => '💭',
                        'category_color' => '#667eea',
                        'replies' => 150,
                        'views' => 800,
                        'likes' => 65,
                        'time_ago' => '5 giờ trước',
                        'excerpt' => 'Làm game RPG lấy bối cảnh lịch sử Việt Nam, từ thời Hùng Vương đến các triều đại phong kiến.',
                        'comment_snippet' => 'Ý tưởng hay quá! Mình có thể hỗ trợ research lịch sử...',
                        'latest_comment_author' => 'VietHistorian',
                        'url' => '#',
                        'forum_url' => route('forum.index'),
                    ],
                ],
                'total_posts' => 50,
                'active_discussions' => 12,
            ];
        }
    }

    /**
     * Get latest blogs from developers (30% community)
     */
    private function getLatestBlogs()
    {
        try {
            $blogs = Blog::published()
                ->with(['category'])
                ->orderBy('published_at', 'desc')
                ->limit(6)
                ->get();

            $blogsData = [];
            foreach ($blogs as $blog) {
                $blogsData[] = [
                    'id' => $blog->id,
                    'title' => $blog->name,
                    'excerpt' => $blog->short_description,
                    'author' => $blog->author ?? 'LamGame Team',
                    'category' => $blog->category->name ?? 'Development',
                    'published_at' => $blog->published_at->format('Y-m-d'),
                    'time_ago' => $blog->published_at->diffForHumans(),
                    'reading_time' => $blog->reading_time ?? 5,
                    'featured_image' => $blog->featured_image ?? 'https://images.unsplash.com/photo-1556438064-2d7646166914?w=400&h=250&fit=crop',
                    'url' => route('blog.show', $blog->slug),
                    'views' => $blog->views ?? rand(100, 1000),
                    'shares' => rand(10, 100),
                ];
            }

            return [
                'featured' => $blogsData,
                'categories' => $this->getBlogCategories(),
                'total_posts' => Blog::published()->count(),
            ];
        } catch (\Exception $e) {
            return [
                'featured' => [],
                'categories' => [],
                'total_posts' => 0,
            ];
        }
    }

    /**
     * Get news & reviews (20% curated)
     */
    private function getNewsData()
    {
        return [
            'featured' => [
                [
                    'title' => 'Xu hướng Game AI 2025: Cơ hội và thách thức cho Developer VN',
                    'excerpt' => 'Phân tích những công nghệ AI mới nhất trong game development và tác động đến thị trường Việt Nam.',
                    'author' => 'LamGame Editorial',
                    'published_at' => now()->format('d/m/Y'),
                    'time_ago' => 'Hôm nay',
                    'category' => 'AI & Technology',
                    'featured_image' => 'https://images.unsplash.com/photo-1677442136019-21780ecad995?w=400&h=250&fit=crop',
                    'url' => '#',
                ]
            ],
            'trending_keywords' => ['Unity 2024', 'AI in Games', 'VR Development', 'Mobile Gaming', 'Esports VN'],
        ];
    }

    /**
     * Get events & team building (10% curated)
     */
    private function getEventsData()
    {
        return [
            'upcoming' => [
                [
                    'title' => 'Vietnam Game Developers Meetup #15',
                    'type' => 'Networking',
                    'date' => now()->addDays(7)->format('d/m/Y'),
                    'location' => 'TP.HCM',
                    'participants' => 150,
                    'description' => 'Gặp gỡ và chia sẻ kinh nghiệm với các developer từ VNG, Gameloft, và indie studios.',
                    'url' => '#',
                ],
            ],
            'team_requests' => [
                [
                    'title' => 'Tìm Unity Developer cho game RPG mobile',
                    'author' => 'IndieStudio_VN',
                    'skills_needed' => ['Unity', 'C#', 'Mobile Optimization'],
                    'posted_ago' => '2 ngày trước',
                    'responses' => 12,
                    'url' => '#',
                ],
            ],
        ];
    }

    /**
     * Get source games & prototypes
     */
    private function getSourceGames()
    {
        return [
            'featured' => [
                [
                    'title' => 'Roguelike Unity Kit',
                    'description' => 'Complete roguelike game template with procedural generation',
                    'category' => '2D Game',
                    'engine' => 'Unity',
                    'language' => 'C#',
                    'downloads' => 1250,
                    'rating' => 4.8,
                    'price' => 0,
                    'is_free' => true,
                    'updated' => now()->format('Y-m-d'),
                    'url' => '#',
                ]
            ],
            'total_sources' => 25,
            'github_links' => [
                'Unity Templates' => 'https://github.com/lamgame-vn/unity-templates',
            ]
        ];
    }

    /**
     * Get statistics for banner
     */
    private function getStatistics()
    {
        return [
            'jobs_this_week' => $this->getWeeklyJobsCount(),
            'total_jobs' => $this->getJobsCount(),
            'forum_posts' => $this->getForumPostsCount(),
            'active_discussions' => 0,
            'blog_posts' => $this->getBlogPostsCount(),
            'blog_views_today' => rand(200, 800),
            'source_downloads' => rand(500, 2000),
            'community_members' => $this->getCommunityMembersCount(),
        ];
    }

    /**
     * Helper methods
     */
    private function getJobsCount()
    {
        return DB::table('products as p')
            ->leftJoin('product_flat as pf', function($join) {
                $join->on('p.id', '=', 'pf.product_id')
                     ->where('pf.locale', '=', 'vi');
            })
            ->where('p.sku', 'LIKE', 'JOB_%')
            ->where('pf.status', 1)
            ->where('pf.visible_individually', 1)
            ->count();
    }

    private function getWeeklyJobsCount()
    {
        return DB::table('products as p')
            ->leftJoin('product_flat as pf', function($join) {
                $join->on('p.id', '=', 'pf.product_id')
                     ->where('pf.locale', '=', 'vi');
            })
            ->where('p.sku', 'LIKE', 'JOB_%')
            ->where('pf.status', 1)
            ->where('pf.visible_individually', 1)
            ->whereDate('p.created_at', '>=', now()->subWeek())
            ->count();
    }

    private function getBlogCategories()
    {
        try {
            return BlogCategory::active()
                ->limit(5)
                ->get();
        } catch (\Exception $e) {
            return collect();
        }
    }

    private function getForumPostsCount()
    {
        try {
            return ForumPost::published()->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    private function getBlogPostsCount()
    {
        try {
            return Blog::published()->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    private function getCommunityMembersCount()
    {
        return DB::table('customers')->count();
    }

    private function extractLocation($description)
    {
        $locations = ['TP.HCM', 'Hà Nội', 'Đà Nẵng', 'Remote', 'Hybrid'];
        
        foreach ($locations as $location) {
            if (stripos($description, $location) !== false) {
                return $location;
            }
        }
        
        return 'TP.HCM'; // Default location
    }
}
