<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\ForumPost;
use App\Models\ForumCategory;
use LamGame\Banner\Repositories\BannerRepository;

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
            // Dynamic banners from Banner API
            'homepageBanners' => $this->getBannersForHomepage(),
            
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
            
            // YouTube videos from lamgame_vn channel
            'youtubeVideos' => $this->getYouTubeVideos(),
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
            // Join job attributes
            ->leftJoin('product_attribute_values as pav_salary', function($join) {
                $join->on('p.id', '=', 'pav_salary.product_id')
                     ->where('pav_salary.attribute_id', '=', 42); // salary_range
            })
            ->leftJoin('attribute_options as ao_salary', 'pav_salary.text_value', '=', 'ao_salary.id')
            ->leftJoin('attribute_option_translations as aot_salary', function($join) {
                $join->on('ao_salary.id', '=', 'aot_salary.attribute_option_id')
                     ->where('aot_salary.locale', '=', 'vi');
            })
            ->leftJoin('product_attribute_values as pav_location', function($join) {
                $join->on('p.id', '=', 'pav_location.product_id')
                     ->where('pav_location.attribute_id', '=', 43); // job_location
            })
            ->leftJoin('attribute_options as ao_location', 'pav_location.text_value', '=', 'ao_location.id')
            ->leftJoin('attribute_option_translations as aot_location', function($join) {
                $join->on('ao_location.id', '=', 'aot_location.attribute_option_id')
                     ->where('aot_location.locale', '=', 'vi');
            })
            ->leftJoin('product_attribute_values as pav_type', function($join) {
                $join->on('p.id', '=', 'pav_type.product_id')
                     ->where('pav_type.attribute_id', '=', 40); // job_type
            })
            ->leftJoin('attribute_options as ao_type', 'pav_type.text_value', '=', 'ao_type.id')
            ->leftJoin('attribute_option_translations as aot_type', function($join) {
                $join->on('ao_type.id', '=', 'aot_type.attribute_option_id')
                     ->where('aot_type.locale', '=', 'vi');
            })
            // Left join to get the first product image (thumbnail)
            ->leftJoin('product_images as pi', function($join) {
                $join->on('p.id', '=', 'pi.product_id')
                     ->where('pi.type', '=', 'images')
                     ->whereRaw('pi.id = (SELECT MIN(id) FROM product_images WHERE product_id = p.id AND type = "images")');
            })
            ->where('p.sku', 'LIKE', 'JOB_%')
            ->where('pf.status', 1)
            ->where('pf.visible_individually', 1)
            ->select(
                'p.id',
                'pf.name',
                'pf.short_description',
                'pf.url_key',
                'p.created_at',
                'pi.path as thumbnail',
                DB::raw('COALESCE(aot_salary.label, ao_salary.admin_name) as salary_range'),
                DB::raw('COALESCE(aot_location.label, ao_location.admin_name) as job_location'),
                DB::raw('COALESCE(aot_type.label, ao_type.admin_name) as job_type')
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
                'company' => trim($parts[1] ?? 'Studio Game VN'),
                'salary' => $job->salary_range ?: 'Thỏa thuận',
                'location' => $job->job_location ?: 'Remote',
                'type' => $job->job_type ?: 'Full-time',
                'posted_ago' => \Carbon\Carbon::parse($job->created_at)->diffForHumans(),
                'url' => route('lamgame.job.detail', $job->url_key),
                'thumbnail' => $this->getJobThumbnail($job->thumbnail),
            ];
        }

        return [
            'featured' => $jobsData,
            'total_count' => $this->getJobsCount(),
            'weekly_new' => $this->getWeeklyJobsCount(),
        ];
    }

    /**
     * Get hot forum topics (40% community) - Top 3 "Chủ Đề Nổi Bật"
     */
    private function getHotForumTopics()
    {
        try {
            // Lấy top 3 topic nổi bật dựa trên comments và likes
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
                ->limit(3)
                ->get();

            // Nếu không đủ 3 posts, lấy thêm posts mới nhất
            if ($hotTopics->count() < 3) {
                $excludeIds = $hotTopics->pluck('id')->toArray();
                $additionalTopics = ForumPost::published()
                    ->with(['category', 'comments' => function($query) {
                        $query->published()->orderBy('created_at', 'desc')->take(1);
                    }])
                    ->whereNotIn('id', $excludeIds)
                    ->orderBy('created_at', 'desc')
                    ->limit(3 - $hotTopics->count())
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
                    [
                        'id' => 3,
                        'title' => 'Học Unity từ Zero đến Hero - Roadmap 2024',
                        'author' => 'UnityMaster',
                        'category' => 'Hướng dẫn',
                        'category_icon' => '🎓',
                        'category_color' => '#10b981',
                        'replies' => 89,
                        'views' => 650,
                        'likes' => 45,
                        'time_ago' => '1 ngày trước',
                        'excerpt' => 'Lộ trình học Unity hoàn chỉnh từ người mới bắt đầu. Bao gồm kiến thức cơ bản, thực hành và dự án thực tế.',
                        'comment_snippet' => 'Roadmap rất chi tiết! Mình sẽ theo hình đường này...',
                        'latest_comment_author' => 'BeginnerDev',
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
     * Get latest blogs from developers (30% community) - Load top 3 blogs with high engagement
     */
    private function getLatestBlogs()
    {
        try {
            // Lấy top 3 blogs dựa trên views và shares
            $blogs = Blog::published()
                ->with(['category'])
                ->where(function($query) {
                    // Ưu tiên blogs có nhiều views hoặc shares
                    $query->where('views', '>', 100)
                          ->orWhere('shares', '>', 10);
                })
                ->orderByRaw('(COALESCE(views, 0) + COALESCE(shares, 0) * 5) DESC')
                ->limit(3)
                ->get();

            // Nếu không đủ 3 blogs, lấy thêm blogs mới nhất
            if ($blogs->count() < 3) {
                $excludeIds = $blogs->pluck('id')->toArray();
                $additionalBlogs = Blog::published()
                    ->with(['category'])
                    ->whereNotIn('id', $excludeIds)
                    ->orderBy('published_at', 'desc')
                    ->limit(3 - $blogs->count())
                    ->get();
                
                $blogs = $blogs->merge($additionalBlogs);
            }

            $blogsData = [];
            foreach ($blogs as $blog) {
                $blogsData[] = [
                    'id' => $blog->id,
                    'title' => $blog->name,
                    'excerpt' => $blog->short_description,
                    'author' => $blog->author ?? 'LamGame Team',
                    'category' => $blog->category->name ?? 'Development',
                    'category_color' => $this->getCategoryColor($blog->category->name ?? 'Development'),
                    'published_at' => $blog->published_at->format('Y-m-d'),
                    'time_ago' => $blog->published_at->diffForHumans(),
                    'reading_time' => $blog->reading_time ?? 5,
                    'featured_image' => $blog->featured_image ?? 'https://images.unsplash.com/photo-1556438064-2d7646166914?w=400&h=250&fit=crop',
                    'url' => route('blog.show', $blog->slug),
                    'views' => $blog->views ?? rand(100, 1000),
                    'shares' => $blog->shares ?? rand(10, 100),
                    // Giả lập comment snippet từ excerpt
                    'comment_snippet' => $this->generateCommentSnippet($blog->name),
                    'latest_comment_author' => 'GameDev_VN',
                ];
            }

            return [
                'featured' => $blogsData,
                'categories' => $this->getBlogCategories(),
                'total_posts' => Blog::published()->count(),
            ];
        } catch (\Exception $e) {
            // Fallback data nếu có lỗi
            return [
                'featured' => [
                    [
                        'id' => 1,
                        'title' => 'Hướng dẫn Unity 2024 - Tính năng mới',
                        'excerpt' => 'Unity 2024 mang đến nhiều cải tiến quan trọng giúp game developer tăng hiệu suất và chất lượng game.',
                        'author' => 'LamGame Team',
                        'category' => 'Unity',
                        'category_color' => '#ff6b35',
                        'published_at' => now()->format('Y-m-d'),
                        'time_ago' => '2 giờ trước',
                        'reading_time' => 8,
                        'featured_image' => 'https://images.unsplash.com/photo-1556438064-2d7646166914?w=400&h=250&fit=crop',
                        'url' => '#',
                        'views' => 1250,
                        'shares' => 85,
                        'comment_snippet' => 'Bài viết rất hữu ích! Netcode mới của Unity thực sự ấn tượng...',
                        'latest_comment_author' => 'UnityDev',
                    ],
                    [
                        'id' => 2,
                        'title' => 'C# Cơ bản cho Game Developer',
                        'excerpt' => 'Hướng dẫn C# từ cơ bản đến nâng cao dành cho Unity game development.',
                        'author' => 'LamGame Team',
                        'category' => 'Programming',
                        'category_color' => '#667eea',
                        'published_at' => now()->subDays(1)->format('Y-m-d'),
                        'time_ago' => '1 ngày trước',
                        'reading_time' => 12,
                        'featured_image' => 'https://images.unsplash.com/photo-1542751371-adc38448a05e?w=400&h=250&fit=crop',
                        'url' => '#',
                        'views' => 980,
                        'shares' => 65,
                        'comment_snippet' => 'Giải thích MonoBehaviour rất rõ ràng, cảm ơn tác giả!',
                        'latest_comment_author' => 'BeginnerCoder',
                    ],
                    [
                        'id' => 3,
                        'title' => 'Tối ưu hóa Performance Game Mobile',
                        'excerpt' => 'Các kỹ thuật tối ưu hóa performance cho mobile game để đạt hiệu suất tốt nhất.',
                        'author' => 'LamGame Team',
                        'category' => 'Mobile Development',
                        'category_color' => '#10b981',
                        'published_at' => now()->subDays(3)->format('Y-m-d'),
                        'time_ago' => '3 ngày trước',
                        'reading_time' => 15,
                        'featured_image' => 'https://images.unsplash.com/photo-1551650975-87deedd944c3?w=400&h=250&fit=crop',
                        'url' => '#',
                        'views' => 1580,
                        'shares' => 120,
                        'comment_snippet' => 'Object Pooling tip rất hay, đã áp dụng vào game của mình!',
                        'latest_comment_author' => 'MobileDev',
                    ],
                ],
                'categories' => collect(),
                'total_posts' => 50,
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
     * Get source games & prototypes for marketplace section
     * Expanded with more realistic data for homepage display
     */
    private function getSourceGames()
    {
        try {
            // In production, this would fetch from products table with sku LIKE 'SOURCE_%'
            // For now, using enhanced fallback data
            $sourceGames = [
                [
                    'id' => 1,
                    'title' => 'Roguelike Unity Kit',
                    'description' => 'Complete roguelike game template with procedural generation, inventory system, and combat mechanics',
                    'short_description' => 'Complete roguelike template with procedural generation',
                    'category' => '2D Game Kit',
                    'engine' => 'Unity',
                    'language' => 'C#',
                    'downloads' => 1250,
                    'rating' => 4.8,
                    'price' => 0,
                    'original_price' => 99000, // VND
                    'is_free' => true,
                    'is_featured' => true,
                    'thumbnail' => 'https://images.unsplash.com/photo-1551650975-87deedd944c3?w=400&h=250&fit=crop&q=80',
                    'updated' => now()->format('Y-m-d'),
                    'updated_ago' => now()->diffForHumans(),
                    'url' => route('lamgame.source-game'),
                    'tags' => ['Unity', '2D', 'Roguelike', 'C#']
                ],
                [
                    'id' => 2,
                    'title' => 'Mobile FPS Controller',
                    'description' => 'Professional mobile FPS controller with joystick, touch controls and weapon system',
                    'short_description' => 'Mobile FPS controller with touch controls',
                    'category' => 'Controller System',
                    'engine' => 'Unity',
                    'language' => 'C#',
                    'downloads' => 890,
                    'rating' => 4.6,
                    'price' => 149000, // VND
                    'original_price' => 199000,
                    'is_free' => false,
                    'is_featured' => true,
                    'thumbnail' => 'https://images.unsplash.com/photo-1542751371-adc38448a05e?w=400&h=250&fit=crop&q=80',
                    'updated' => now()->subDays(2)->format('Y-m-d'),
                    'updated_ago' => now()->subDays(2)->diffForHumans(),
                    'url' => route('lamgame.source-game'),
                    'tags' => ['Unity', 'Mobile', 'FPS', 'Controller']
                ],
                [
                    'id' => 3,
                    'title' => 'Inventory System Pro',
                    'description' => 'Advanced inventory and item management system for RPG games with drag & drop UI',
                    'short_description' => 'Advanced inventory system for RPG games',
                    'category' => 'UI System',
                    'engine' => 'Unity',
                    'language' => 'C#',
                    'downloads' => 2100,
                    'rating' => 4.9,
                    'price' => 99000, // VND
                    'original_price' => 149000,
                    'is_free' => false,
                    'is_featured' => true,
                    'thumbnail' => 'https://images.unsplash.com/photo-1556438064-2d7646166914?w=400&h=250&fit=crop&q=80',
                    'updated' => now()->subDays(1)->format('Y-m-d'),
                    'updated_ago' => now()->subDays(1)->diffForHumans(),
                    'url' => route('lamgame.source-game'),
                    'tags' => ['Unity', 'RPG', 'Inventory', 'UI']
                ],
                [
                    'id' => 4,
                    'title' => 'VR Interaction Framework',
                    'description' => 'Complete VR interaction system for Oculus and HTC Vive with hand tracking',
                    'short_description' => 'VR interaction system with hand tracking',
                    'category' => 'VR System',
                    'engine' => 'Unity',
                    'language' => 'C#',
                    'downloads' => 675,
                    'rating' => 4.7,
                    'price' => 199000, // VND
                    'original_price' => 299000,
                    'is_free' => false,
                    'is_featured' => false,
                    'thumbnail' => 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=400&h=250&fit=crop&q=80',
                    'updated' => now()->subDays(3)->format('Y-m-d'),
                    'updated_ago' => now()->subDays(3)->diffForHumans(),
                    'url' => route('lamgame.source-game'),
                    'tags' => ['Unity', 'VR', 'Interaction', 'Hand Tracking']
                ],
                [
                    'id' => 5,
                    'title' => 'AI Behavior Tree System',
                    'description' => 'Visual behavior tree editor for game AI with pathfinding and state management',
                    'short_description' => 'Visual behavior tree system for game AI',
                    'category' => 'AI System',
                    'engine' => 'Unity',
                    'language' => 'C#',
                    'downloads' => 1340,
                    'rating' => 4.5,
                    'price' => 179000, // VND
                    'original_price' => 249000,
                    'is_free' => false,
                    'is_featured' => false,
                    'thumbnail' => 'https://images.unsplash.com/photo-1677442136019-21780ecad995?w=400&h=250&fit=crop&q=80',
                    'updated' => now()->subDays(4)->format('Y-m-d'),
                    'updated_ago' => now()->subDays(4)->diffForHumans(),
                    'url' => route('lamgame.source-game'),
                    'tags' => ['Unity', 'AI', 'Behavior Tree', 'Pathfinding']
                ],
                [
                    'id' => 6,
                    'title' => 'Multiplayer Network Kit',
                    'description' => 'Complete multiplayer networking solution with authoritative server and client prediction',
                    'short_description' => 'Multiplayer networking with server authority',
                    'category' => 'Network System',
                    'engine' => 'Unity',
                    'language' => 'C#',
                    'downloads' => 580,
                    'rating' => 4.4,
                    'price' => 299000, // VND
                    'original_price' => 399000,
                    'is_free' => false,
                    'is_featured' => true,
                    'thumbnail' => 'https://images.unsplash.com/photo-1560472354-b33ff0c44a43?w=400&h=250&fit=crop&q=80',
                    'updated' => now()->subWeek()->format('Y-m-d'),
                    'updated_ago' => now()->subWeek()->diffForHumans(),
                    'url' => route('lamgame.source-game'),
                    'tags' => ['Unity', 'Multiplayer', 'Networking', 'Server']
                ]
            ];
            
            // Ensure all required keys are present
            $sourceGames = array_map(function($game) {
                return array_merge([
                    'id' => 0,
                    'title' => 'No Title',
                    'description' => 'No description',
                    'short_description' => 'No description available',
                    'category' => 'General',
                    'engine' => 'Unknown',
                    'language' => 'N/A',
                    'downloads' => 0,
                    'rating' => 0,
                    'price' => 0,
                    'original_price' => 0,
                    'is_free' => false,
                    'is_featured' => false,
                    'thumbnail' => '',
                    'updated' => now()->format('Y-m-d'),
                    'updated_ago' => 'Unknown',
                    'url' => '#',
                    'tags' => []
                ], $game);
            }, $sourceGames);

            // Prioritize featured items first, then by downloads
            $featuredGames = collect($sourceGames)
                ->sortByDesc(function($item) {
                    return ($item['is_featured'] ? 10000 : 0) + $item['downloads'];
                })
                ->take(6) // Display 6 items on homepage
                ->values()
                ->toArray();
            
            return [
                'featured' => $featuredGames,
                'total_sources' => count($sourceGames),
                'free_sources' => collect($sourceGames)->where('is_free', true)->count(),
                'paid_sources' => collect($sourceGames)->where('is_free', false)->count(),
                'most_downloaded' => collect($sourceGames)->sortByDesc('downloads')->first(),
                'newest_source' => collect($sourceGames)->sortBy('updated')->first(),
                'categories' => collect($sourceGames)->groupBy('category')->keys()->toArray(),
                'engines' => collect($sourceGames)->groupBy('engine')->keys()->toArray(),
                'github_links' => [
                    'Unity Templates' => 'https://github.com/lamgame-vn/unity-templates',
                    'Open Source Projects' => 'https://github.com/lamgame-vn'
                ]
            ];
            
        } catch (\Exception $e) {
            // Fallback data in case of any errors
            return [
                'featured' => [],
                'total_sources' => 0,
                'free_sources' => 0,
                'paid_sources' => 0,
                'most_downloaded' => null,
                'newest_source' => null,
                'categories' => [],
                'engines' => [],
                'github_links' => []
            ];
        }
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

    /**
     * Get category color for blog categories
     */
    private function getCategoryColor($categoryName)
    {
        $colors = [
            'Unity' => '#ff6b35',
            'Programming' => '#667eea',
            'C#' => '#667eea',
            'Game Design' => '#ffd700',
            'Mobile Development' => '#10b981',
            'Tutorial' => '#8b5cf6',
            'Review' => '#ec4899',
            'Development' => '#06b6d4',
            'News' => '#f59e0b',
            'Tips & Tricks' => '#764ba2',
        ];
        
        return $colors[$categoryName] ?? '#6b7280';
    }

    /**
     * Generate comment snippet from blog title
     */
    private function generateCommentSnippet($title)
    {
        $snippets = [
            'Bài viết rất hữu ích! Cảm ơn tác giả đã chia sẻ...',
            'Mình đã thử theo hướng dẫn và rất hiệu quả!',
            'Nội dung chi tiết và dễ hiểu, đánh giá 5 sao!',
            'Kinh nghiệm thực tế rất quý giá, cảm ơn!',
            'Đây là kiến thức mình cần tìm, save lại ngay!',
            'Giải thích rất rõ ràng, beginner cũng hiểu được!',
            'Code example rất hay, đã áp dụng vào project!',
            'Series này rất chất lượng, đợi phần tiếp theo!',
        ];
        
        return $snippets[array_rand($snippets)];
    }
    
    /**
     * Get job thumbnail with fallback to default recruitment image
     */
    private function getJobThumbnail($thumbnailPath)
    {
        // If job has thumbnail from database
        if (!empty($thumbnailPath)) {
            // Handle different path formats
            if (str_starts_with($thumbnailPath, 'http://') || str_starts_with($thumbnailPath, 'https://')) {
                return $thumbnailPath; // Already full URL
            }
            
            // For local storage paths
            if (str_starts_with($thumbnailPath, '/')) {
                return asset($thumbnailPath); // Path starts with /
            }
            
            return asset('storage/' . $thumbnailPath); // Relative path in storage
        }
        
        // Fallback: Use default recruitment thumbnails from constant
        $thumbnails = config('job.default_thumbnails');
        return $thumbnails[array_rand($thumbnails)];
    }
    
    /**
     * Get YouTube videos from lamgame_vn channel
     * Real videos from https://www.youtube.com/@lamgame_vn/videos
     */
    private function getYouTubeVideos()
    {
        // Featured videos from @lamgame_vn channel (real content)
        return [
            'featured' => [
                [
                    'id' => 'sOdX4Kss5sg',
                    'title' => 'Bàn phím cơ Gaming Rapoo V500 phiên bản hợp kim Alloy hiện đại | LamGame.vn',
                    'description' => 'Đánh giá chi tiết bàn phím cơ gaming Rapoo V500 với thiết kế hợp kim Alloy cao cấp. Phù hợp cho game thủ chuyên nghiệp và lập trình viên.',
                    'thumbnail' => 'https://img.youtube.com/vi/sOdX4Kss5sg/maxresdefault.jpg',
                    'duration' => '12:45',
                    'views' => '8.5K',
                    'published_at' => '3 ngày trước',
                    'url' => 'https://www.youtube.com/watch?v=sOdX4Kss5sg',
                    'category' => 'Gaming Gear Review'
                ],
                [
                    'id' => 'mnsTBAfeVdQ',
                    'title' => 'Gameplay Showcase | LamGame.vn',
                    'description' => 'Video gameplay và hướng dẫn chi tiết từ đội ngũ LamGame.vn. Khám phá các kỹ thuật chơi game và tips hữu ích cho game thủ.',
                    'thumbnail' => 'https://img.youtube.com/vi/mnsTBAfeVdQ/hqdefault.jpg',
                    'duration' => '16:24',
                    'views' => '9.8K',
                    'published_at' => '4 ngày trước',
                    'url' => 'https://www.youtube.com/watch?v=mnsTBAfeVdQ',
                    'category' => 'Gameplay'
                ],
                [
                    'id' => 'mQdNkT0SQFM',
                    'title' => 'Islet Online - Tựa game có lối chơi giống Minecraft | LamGame.vn',
                    'description' => 'Khám phá Islet Online, tựa game sandbox multiplayer với lối chơi tương tự Minecraft nhưng có những điểm khác biệt thú vị.',
                    'thumbnail' => 'https://img.youtube.com/vi/mQdNkT0SQFM/maxresdefault.jpg',
                    'duration' => '22:18',
                    'views' => '12.7K',
                    'published_at' => '5 ngày trước',
                    'url' => 'https://www.youtube.com/watch?v=mQdNkT0SQFM',
                    'category' => 'Game Review'
                ]
            ],
            'channel_info' => [
                'name' => 'Làm Game',
                'handle' => '@lamgame_vn',
                'subscribers' => '2.85K',
                'total_views' => '180K',
                'channel_url' => 'https://www.youtube.com/@lamgame_vn',
                'channel_id' => 'UCv2lripWdZDKtlrRy1J0dBw',
                'banner_url' => null, // Will be dynamically loaded via JavaScript
                'avatar_url' => 'https://yt3.googleusercontent.com/ytc/AIdro_lCL4LgHPQJU8-FMRZNjLgtIoJKwk7zJZ4xJQrYB4d3iw=s240-c-k-c0x00ffffff-no-rj'
            ]
        ];
    }
    
    /**
     * Get banners for homepage hero section from Banner API
     */
    private function getBannersForHomepage()
    {
        try {
            // Use Banner Repository directly instead of HTTP call
            $bannerRepository = app(BannerRepository::class);
            
            // Get banners for homepage_hero position
            $banners = $bannerRepository->getByPosition(
                'homepage_hero',
                'all', // device_type
                null,  // channel_id
                null,  // locale
                4      // limit
            );
            
            if ($banners->isNotEmpty()) {
                // Transform repository data for homepage usage
                $bannerData = [];
                foreach ($banners as $banner) {
                    $bannerData[] = [
                        'id' => $banner['id'],
                        'title' => $banner['title'] ?? 'LamGame.vn',
                        'content' => $banner['content'] ?? 'Cộng đồng Game Developer Việt Nam',
                        'image' => $banner['image'],
                        'image_alt' => $banner['image_alt'] ?? $banner['title'],
                        'link' => $banner['link'] ?? '#',
                        'target' => $banner['target'] ?? '_self',
                        'css_classes' => $banner['css_classes'],
                        'is_active' => $banner['is_active'],
                        'sort_order' => $banner['sort_order'] ?? 0,
                    ];
                }
                
                return [
                    'banners' => $bannerData,
                    'has_banners' => true,
                    'total_count' => count($bannerData)
                ];
            }
        } catch (\Exception $e) {
            // Log error for debugging
            \Log::warning('Failed to fetch banners for homepage', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
        
        // Return fallback data if API fails
        return [
            'banners' => $this->getFallbackBanners(),
            'has_banners' => false,
            'total_count' => 4
        ];
    }
    
    /**
     * Fallback banners if API is not available
     */
    private function getFallbackBanners()
    {
        return [
            [
                'id' => 'fallback-1',
                'title' => 'Khám Phá Việc Làm Game Dev Hot Nhất!',
                'content' => 'Hàng trăm vị trí từ VNG, Gameloft: Unity Developer lương 20-40tr VNĐ. 50+ jobs tuần này, apply ngay để kết nối với công ty hàng đầu!',
                'image' => null,
                'image_alt' => 'Việc làm game developer',
                'link' => route('lamgame.viec-lam-game'),
                'target' => '_self',
                'css_classes' => 'jobs',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'id' => 'fallback-2',
                'title' => 'Thảo Luận Sôi Động: Topic Forum Nóng Hổi!',
                'content' => 'Topic hot: "Unity vs Unreal cho game mobile?" – 150 comments, 500 views, 80 likes trong 24h. Tham gia ngay để chia sẻ kinh nghiệm với cộng đồng dev!',
                'image' => null,
                'image_alt' => 'Forum thảo luận',
                'link' => route('forum.index'),
                'target' => '_self',
                'css_classes' => 'forum',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'id' => 'fallback-3',
                'title' => 'Bài Viết Mới Nhất Từ Developer!',
                'content' => 'Bài mới: "Tối ưu hóa performance Unity cho game 3D" – Đăng bởi dev @UserX, 200 views, 50 shares. Đọc để cập nhật kiến thức hot nhất!',
                'image' => null,
                'image_alt' => 'Blog developer',
                'link' => route('lamgame.blog'),
                'target' => '_self',
                'css_classes' => 'blog',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'id' => 'fallback-4',
                'title' => 'Khám Phá Game Mới & Ý Tưởng Sáng Tạo!',
                'content' => 'Source mới: "Roguelike Unity kit" trên GitHub. Ý tưởng: "VR adventure Việt Nam folklore". Game demo từ dev cộng đồng – Download & phát triển ngay!',
                'image' => null,
                'image_alt' => 'Source code và ý tưởng game',
                'link' => route('lamgame.source-game'),
                'target' => '_self',
                'css_classes' => 'creative',
                'is_active' => true,
                'sort_order' => 4,
            ],
        ];
    }
}
