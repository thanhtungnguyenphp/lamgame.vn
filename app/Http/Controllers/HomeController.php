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
     * Get latest blogs from developers (30% community) - Load top 6 blogs with high engagement
     */
    private function getLatestBlogs()
    {
        try {
            // Lấy top 6 blogs dựa trên views và shares
            $blogs = Blog::published()
                ->with(['category'])
                ->where(function($query) {
                    // Ưu tiên blogs có nhiều views hoặc shares
                    $query->where('views', '>', 100)
                          ->orWhere('shares', '>', 10);
                })
                ->orderByRaw('(COALESCE(views, 0) + COALESCE(shares, 0) * 5) DESC')
                ->limit(6)
                ->get();

            // Nếu không đủ 6 blogs, lấy thêm blogs mới nhất
            if ($blogs->count() < 6) {
                $excludeIds = $blogs->pluck('id')->toArray();
                $additionalBlogs = Blog::published()
                    ->with(['category'])
                    ->whereNotIn('id', $excludeIds)
                    ->orderBy('published_at', 'desc')
                    ->limit(6 - $blogs->count())
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
     * Get YouTube videos from lamgame_vn channel
     */
    private function getYouTubeVideos()
    {
        // Featured videos from @lamgame_vn channel
        return [
            'featured' => [
                [
                    'id' => 'dQw4w9WgXcQ',
                    'title' => 'Unity 2024 - Hướng dẫn tạo game RPG từ A-Z',
                    'description' => 'Học cách tạo game RPG hoàn chỉnh với Unity 2024, từ thiết kế character đến combat system và inventory management.',
                    'thumbnail' => 'https://img.youtube.com/vi/dQw4w9WgXcQ/maxresdefault.jpg',
                    'duration' => '15:30',
                    'views' => '125K',
                    'published_at' => '3 ngày trước',
                    'url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                    'category' => 'Unity Tutorial'
                ],
                [
                    'id' => 'J2X5mJ3HDYE',
                    'title' => 'C# Programming cho Game Developer - OOP Basics',
                    'description' => 'Nắm vững lập trình hướng đối tượng C# cho Unity game development. Inheritance, Polymorphism và Design Patterns.',
                    'thumbnail' => 'https://img.youtube.com/vi/J2X5mJ3HDYE/maxresdefault.jpg',
                    'duration' => '22:15',
                    'views' => '89K',
                    'published_at' => '1 tuần trước',
                    'url' => 'https://www.youtube.com/watch?v=J2X5mJ3HDYE',
                    'category' => 'C# Programming'
                ],
                [
                    'id' => 'kJQP7kiw5Fk',
                    'title' => 'Mobile Game Optimization - Tối ưu hiệu suất Unity',
                    'description' => 'Các kỹ thuật tối ưu hóa performance cho mobile games: Object Pooling, LOD, Texture Compression và Memory Management.',
                    'thumbnail' => 'https://img.youtube.com/vi/kJQP7kiw5Fk/maxresdefault.jpg',
                    'duration' => '18:45',
                    'views' => '67K',
                    'published_at' => '2 tuần trước',
                    'url' => 'https://www.youtube.com/watch?v=kJQP7kiw5Fk',
                    'category' => 'Mobile Development'
                ]
            ],
            'channel_info' => [
                'name' => 'Làm Game',
                'handle' => '@lamgame_vn',
                'subscribers' => '45.2K',
                'total_views' => '2.1M',
                'channel_url' => 'https://www.youtube.com/@lamgame_vn'
            ]
        ];
    }
}
