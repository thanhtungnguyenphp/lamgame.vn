<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\BlogTag;
use App\Models\ForumCategory;
use App\Models\ForumPost;
use App\Models\SourceGameReview;

class LamGamePageController extends Controller
{
    /**
     * Show Gioi thieu page
     */
    public function gioiThieu()
    {
        return view('lamgame.pages.gioi-thieu', [
            'page_title' => 'Giới thiệu - Làm Game',
            'page_description' => 'Tìm hiểu về Làm Game - nền tảng học lập trình game hàng đầu Việt Nam với các khóa học chất lượng cao.'
        ]);
    }

    /**
     * Show AI Tools subscription page
     */
    public function aiTools()
    {
        return view('lamgame.pages.ai-tools-landing', [
            'customer' => auth()->guard('customer')->user(),
        ]);
    }

    /**
     * Show AI Tools dashboard (requires login)
     */
    public function aiToolsDashboard()
    {
        $customer = auth()->guard('customer')->user();
        $token = $customer->createToken('ai-tools')->plainTextToken ?? '';

        return view('lamgame.pages.ai-tools-dashboard', [
            'customer' => $customer,
            'token'    => $token,
        ]);
    }

    /**
     * Show AI Chat page (OHHA Core WebSocket)
     */
    public function aiChat()
    {
        return view('lamgame.pages.ai-chat');
    }

    /**
     * Show Lien he page
     */
    public function lienHe()
    {
        return view('lamgame.pages.lien-he', [
            'page_title' => 'Liên hệ - Làm Game',
            'page_description' => 'Liên hệ với Làm Game để được tư vấn về các khóa học lập trình game phù hợp nhất.'
        ]);
    }

    /**
     * Handle contact form submission
     */
    public function submitContact(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'phone'   => 'required|string|max:20',
            'email'   => 'nullable|email|max:255',
            'message' => 'nullable|string|max:2000',
        ]);

        // TODO: Store in database or send email notification

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Cảm ơn bạn đã liên hệ! Chúng tôi sẽ phản hồi trong 24h.',
            ]);
        }

        return back()->with('success', 'Cảm ơn bạn đã liên hệ! Chúng tôi sẽ phản hồi trong 24h.');
    }

    /**
     * Show Blog page with dynamic data
     */
    public function blog(Request $request)
    {
        $perPage = 6; // Number of blog posts per page
        $categorySlug = $request->get('category');
        $tagSlug = $request->get('tag');
        $search = $request->get('search');

        // Build the query for blogs
        $blogsQuery = Blog::published()
            ->with('category')
            ->orderBy('published_at', 'desc');

        // Filter by category if specified
        if ($categorySlug) {
            $category = BlogCategory::where('slug', $categorySlug)->active()->first();
            if (!$category) {
                // Category doesn't exist → 404
                abort(404);
            }
            $blogsQuery->where(function($query) use ($category) {
                $query->where('default_category', $category->id)
                      ->orWhere('categorys', 'LIKE', '%' . $category->id . '%');
            });
        }

        // Filter by tag if specified
        if ($tagSlug) {
            $tag = BlogTag::where('slug', $tagSlug)->active()->first();
            if (!$tag) {
                // Tag doesn't exist → 404
                abort(404);
            }
            $blogsQuery->where('tags', 'LIKE', '%' . $tag->id . '%');
        }

        // Search functionality
        if ($search) {
            $blogsQuery->where(function($query) use ($search) {
                $query->where('name', 'LIKE', '%' . $search . '%')
                      ->orWhere('short_description', 'LIKE', '%' . $search . '%')
                      ->orWhere('description', 'LIKE', '%' . $search . '%')
                      ->orWhere('meta_keywords', 'LIKE', '%' . $search . '%');
            });
        }

        // Get paginated blogs
        $blogs = $blogsQuery->paginate($perPage);

        // Get featured blog (latest published blog)
        $featuredBlog = Blog::published()
            ->with('category')
            ->orderBy('published_at', 'desc')
            ->first();

        // Get categories with blog count for sidebar
        $categories = BlogCategory::active()
            ->withCount(['blogs' => function($query) {
                $query->published();
            }])
            ->orderBy('name')
            ->get();

        // Get popular tags for sidebar - precompute counts to avoid N+1 queries
        $allTags = BlogTag::active()->orderBy('name')->get();
        $publishedBlogs = Blog::published()->whereNotNull('tags')->pluck('tags');
        $tagCounts = [];
        foreach ($publishedBlogs as $tagString) {
            foreach (explode(',', $tagString) as $tagId) {
                $tagId = trim($tagId);
                if ($tagId !== '') {
                    $tagCounts[$tagId] = ($tagCounts[$tagId] ?? 0) + 1;
                }
            }
        }
        $popularTags = $allTags->filter(function($tag) use ($tagCounts) {
            return ($tagCounts[$tag->id] ?? 0) > 0;
        })->take(20);

        // Get popular posts (most recent ones for now)
        $popularPosts = Blog::published()
            ->orderBy('published_at', 'desc')
            ->take(3)
            ->get();

        return view('lamgame.pages.blog', [
            'page_title' => 'Blog - Làm Game',
            'page_description' => 'Khám phá các bài viết hay về lập trình game, tips & tricks, và xu hướng công nghệ game mới nhất.',
            'blogs' => $blogs,
            'featuredBlog' => $featuredBlog,
            'categories' => $categories,
            'popularTags' => $popularTags,
            'popularPosts' => $popularPosts,
            'currentCategory' => $categorySlug,
            'currentTag' => $tagSlug,
            'searchQuery' => $search,
            'shouldNoindex' => ($categorySlug || $tagSlug) && $blogs->total() < 5,
        ]);
    }

    /**
     * Show individual blog post
     */
    public function blogShow($slug)
    {
        // Find the blog post by slug
        $blog = Blog::where('slug', $slug)
                    ->published()
                    ->with('category')
                    ->firstOrFail();

        // Get related posts from the same category
        $relatedPosts = Blog::published()
                           ->where('id', '!=', $blog->id)
                           ->where(function($query) use ($blog) {
                               $query->where('default_category', $blog->default_category)
                                     ->orWhere('categorys', 'LIKE', '%' . $blog->default_category . '%');
                           })
                           ->orderBy('published_at', 'desc')
                           ->take(3)
                           ->get();

        // Get blog categories and tags for the post
        $postCategories = $blog->getCategories();
        $postTags = $blog->getTags();

        // Get categories for sidebar
        $categories = BlogCategory::active()
            ->withCount(['blogs' => function($query) {
                $query->published();
            }])
            ->orderBy('name')
            ->take(10)
            ->get();

        // Get recent posts for sidebar
        $recentPosts = Blog::published()
            ->where('id', '!=', $blog->id)
            ->orderBy('published_at', 'desc')
            ->take(5)
            ->get();

        // Get popular tags for sidebar - precompute to avoid N+1
        $allTags = BlogTag::active()->orderBy('name')->get();
        $publishedBlogTags = Blog::published()->whereNotNull('tags')->pluck('tags');
        $tagCounts = [];
        foreach ($publishedBlogTags as $tagString) {
            foreach (explode(',', $tagString) as $tagId) {
                $tagId = trim($tagId);
                if ($tagId !== '') {
                    $tagCounts[$tagId] = ($tagCounts[$tagId] ?? 0) + 1;
                }
            }
        }
        $popularTags = $allTags->filter(function($tag) use ($tagCounts) {
            return ($tagCounts[$tag->id] ?? 0) > 0;
        })->take(15);

        return view('lamgame.pages.blog-detail', [
            'page_title' => $blog->meta_title ?: $blog->name . ' - Làm Game',
            'page_description' => $blog->meta_description ?: $blog->short_description,
            'page_keywords' => $blog->meta_keywords,
            'blog' => $blog,
            'postCategories' => $postCategories,
            'postTags' => $postTags,
            'relatedPosts' => $relatedPosts,
            'categories' => $categories,
            'recentPosts' => $recentPosts,
            'popularTags' => $popularTags,
        ]);
    }

    /**
     * Show Jobs page (alias for viecLamGame)
     */
    public function jobs(Request $request)
    {
        return $this->viecLamGame($request);
    }

    /**
     * Show Viec lam Game page
     */
    public function viecLamGame(Request $request)
    {
        $keyword = $request->get('keyword');
        $location = $request->get('location');
        $level = $request->get('level');
        $sort = $request->get('sort', 'newest');

        $query = \App\Models\JobPosting::with('skills', 'benefits')
            ->where('status', 'active');

        if ($keyword) {
            $query->search($keyword);
        }
        if ($location) {
            $query->byLocation($location);
        }
        if ($level) {
            $query->where('experience_level', 'like', "%{$level}%");
        }

        switch ($sort) {
            case 'salary-high':
                $query->orderByRaw('salary_max IS NULL, salary_max DESC');
                break;
            case 'company':
                $query->orderBy('company_name');
                break;
            default:
                $query->orderByDesc('created_at');
        }

        $jobs = $query->paginate(10);

        // Transform for view compatibility
        foreach ($jobs as $job) {
            $job->job_title = $job->title;
            $job->url_key = $job->slug;
            $job->company_logo_url = $job->company_logo ? asset('storage/' . $job->company_logo) : null;
            $job->processed_description = $this->processJobDescription($job->short_description, 150);
            $job->category_name = null;
            $job->attributes = [
                'salary_range'     => $job->salary_range ?? 'Thỏa thuận',
                'job_location'     => $job->location ?? 'Việt Nam',
                'job_type'         => $job->job_type ?? 'Full-time',
                'experience_level' => $job->experience_level,
                'required_skills'  => $job->skills->pluck('skill_name')->implode(','),
                'job_benefits'     => $job->benefits->pluck('benefit_name')->implode(','),
            ];
        }

        $totalJobs = \App\Models\JobPosting::where('status', 'active')->count();

        $topCompanies = \App\Models\JobPosting::where('status', 'active')
            ->whereNotNull('company_name')
            ->select('company_name', 'company_logo', \DB::raw('COUNT(*) as job_count'))
            ->groupBy('company_name', 'company_logo')
            ->orderByDesc('job_count')
            ->take(5)
            ->get();

        return view('lamgame.pages.viec-lam-game', [
            'page_title' => 'Tuyển dụng Game Developer — 51+ việc làm mới | LamGame.vn',
            'page_description' => 'Tìm kiếm cơ hội việc làm trong ngành game development tại Việt Nam và quốc tế.',
            'jobs' => $jobs,
            'totalJobs' => $totalJobs,
            'topCompanies' => $topCompanies,
            'searchParams' => [
                'keyword' => $keyword,
                'location' => $location,
                'level' => $level,
                'sort' => $sort,
            ],
        ]);
    }

    /**
     * Get job attributes for a specific job
     */
    private function getJobAttributes($productId)
    {
        $attributes = \DB::table('product_attribute_values as pav')
            ->join('attributes as a', 'pav.attribute_id', '=', 'a.id')
            ->leftJoin('attribute_options as ao', 'pav.text_value', '=', 'ao.id')
            ->leftJoin('attribute_option_translations as aot', function($join) {
                $join->on('ao.id', '=', 'aot.attribute_option_id')
                     ->where(function($query) {
                         $query->where('aot.locale', '=', 'vi')
                               ->orWhereNull('aot.locale')
                               ->orWhere('aot.locale', '=', '');
                     });
            })
            ->where('pav.product_id', $productId)
            ->whereIn('a.code', ['job_type', 'experience_level', 'salary_range', 'job_location', 'required_skills', 'job_benefits'])
            ->select(
                'a.code',
                'pav.text_value',
                'pav.integer_value',
                'pav.date_value',
                'aot.label as option_label'
            )
            ->get();

        $jobAttributes = [];
        foreach ($attributes as $attr) {
            if (in_array($attr->code, ['job_benefits', 'required_skills']) && $attr->text_value) {
                // Handle multiple values (comma-separated IDs)
                $valueIds = explode(',', $attr->text_value);
                $valueLabels = [];
                
                foreach ($valueIds as $valueId) {
                    $valueId = trim($valueId);
                    if ($valueId) {
                        $valueLabel = \DB::table('attribute_options as ao')
                            ->join('attribute_option_translations as aot', 'ao.id', '=', 'aot.attribute_option_id')
                            ->where('ao.id', $valueId)
                            ->where(function($query) {
                                $query->where('aot.locale', 'vi')
                                      ->orWhereNull('aot.locale')
                                      ->orWhere('aot.locale', '');
                            })
                            ->value('aot.label');
                        
                        if ($valueLabel) {
                            $valueLabels[] = $valueLabel;
                        }
                    }
                }
                
                $jobAttributes[$attr->code] = implode(',', $valueLabels);
            } else {
                $value = $attr->option_label ?: $attr->text_value ?: $attr->integer_value;
                $jobAttributes[$attr->code] = $value;
            }
        }

        return $jobAttributes;
    }

    /**
     * Get job thumbnail URL with fallback to default recruitment image
     */
    private function getJobThumbnailUrl($thumbnailPath)
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
     * Process job description for safe HTML display with text truncation
     */
    private function processJobDescription($description, $limit = null)
    {
        if (empty($description)) {
            return 'Thông tin mô tả công việc sẽ được cập nhật sớm.';
        }

        // Clean up the HTML - remove dangerous tags, keep safe formatting
        $allowedTags = '<strong><b><em><i><u><br><p><ul><ol><li>';
        $cleanText = strip_tags($description, $allowedTags);
        
        // Convert plain line breaks to <br> tags if needed
        $cleanText = preg_replace('/\n(?![^<]*>)/', '<br>', $cleanText);
        
        // Clean up multiple consecutive <br> tags
        $cleanText = preg_replace('/(<br\s*\/?>\s*){3,}/', '<br><br>', $cleanText);
        
        // If we need to truncate for listing view
        if ($limit !== null) {
            // Get plain text for length calculation
            $plainText = strip_tags($cleanText);
            
            if (strlen($plainText) > $limit) {
                // Truncate plain text at word boundary
                $truncated = substr($plainText, 0, $limit);
                $truncated = substr($truncated, 0, strrpos($truncated, ' '));
                
                // Try to preserve some HTML formatting in truncated version
                $words = explode(' ', $truncated);
                $wordCount = count($words);
                
                // Create a truncated version with HTML
                $htmlWords = explode(' ', strip_tags($cleanText));
                $truncatedWithHtml = implode(' ', array_slice($htmlWords, 0, $wordCount));
                
                return '<p>' . $truncatedWithHtml . '...</p>';
            }
        }
        
        // Wrap in paragraph if not already wrapped
        if (!str_starts_with(trim($cleanText), '<p>')) {
            $cleanText = '<p>' . $cleanText . '</p>';
        }
        
        return $cleanText;
    }

    /**
     * Show Job detail page
     */
    public function jobDetail($slug)
    {
        $job = \App\Models\JobPosting::with('skills', 'benefits')
            ->where('slug', $slug)
            ->where('status', 'active')
            ->first();

        if (!$job) {
            abort(404, 'Job not found');
        }

        $job->incrementViews();

        // View compatibility
        $job->name = $job->title;
        $job->url_key = $job->slug;
        $job->attributes = [
            'salary_range'     => $job->salary_range ?? 'Thỏa thuận',
            'job_location'     => $job->location ?? 'Việt Nam',
            'job_type'         => $job->job_type ?? 'Full-time',
            'experience_level' => $job->experience_level,
            'required_skills'  => $job->skills->pluck('skill_name')->implode(','),
            'job_benefits'     => $job->benefits->pluck('benefit_name')->implode(','),
        ];

        $jobTitle = $job->title;
        $companyName = $job->company_name ?: 'Công ty chưa xác định';
        $salaryFormatted = $job->salary_range ?? 'Thỏa thuận';
        $postedAgo = $job->created_at->diffForHumans();

        $logoUrl = $job->company_logo ? asset('storage/' . $job->company_logo) : null;
        $companyInfo = [
            'name'           => $companyName,
            'description'    => 'Công ty hoạt động trong lĩnh vực phát triển game.',
            'logo'           => $job->company_logo,
            'logo_url'       => $logoUrl,
            'website'        => null,
            'email'          => $job->contact_email,
            'phone'          => $job->contact_phone,
            'employee_count' => 50,
            'founded_year'   => 2020,
            'industry'       => 'Game Development',
        ];

        $similarJobs = \App\Models\JobPosting::where('status', 'active')
            ->where('slug', '!=', $slug)
            ->orderByDesc('created_at')
            ->limit(3)
            ->get()
            ->map(function ($j) {
                $j->name = $j->title;
                $j->url_key = $j->slug;
                $j->price = 0;
                return $j;
            });

        $customer = Auth::guard('customer')->user();
        $customerData = null;
        if ($customer) {
            $customerData = [
                'id'          => $customer->id,
                'full_name'   => trim($customer->first_name . ' ' . $customer->last_name),
                'first_name'  => $customer->first_name,
                'last_name'   => $customer->last_name,
                'email'       => $customer->email,
                'phone'       => $customer->phone ?? '',
                'is_verified' => $customer->is_verified ?? false,
                'status'      => $customer->status ?? 1,
            ];
        }

        return view('lamgame.pages.job-detail', [
            'job'              => $job,
            'jobTitle'         => $jobTitle,
            'companyName'      => $companyName,
            'companyInfo'      => $companyInfo,
            'salaryFormatted'  => $salaryFormatted,
            'postedAgo'        => $postedAgo,
            'similarJobs'      => $similarJobs,
            'customer'         => $customerData,
            'isLoggedIn'       => !is_null($customer),
            'page_title'       => $jobTitle . ' - ' . $companyName . ' - Làm Game',
            'page_description' => \Str::limit($job->short_description, 160),
        ]);
    }

    /**
     * Show Course detail page
     */
    public function courseDetail($slug)
    {
        $courses = [
            'unity' => [
                'title' => 'Unity Game Development',
                'description' => 'Học lập trình game với Unity từ cơ bản đến nâng cao',
                'duration' => '3 tháng',
                'level' => 'Từ cơ bản đến nâng cao',
                'price' => '5.000.000đ'
            ],
            'unreal' => [
                'title' => 'Unreal Engine',
                'description' => 'Phát triển game 3D chất lượng cao với Unreal Engine',
                'duration' => '4 tháng',
                'level' => 'Trung cấp - Nâng cao',
                'price' => '7.000.000đ'
            ],
            'game-design' => [
                'title' => 'Game Design',
                'description' => 'Thiết kế game từ ý tưởng đến sản phẩm hoàn chỉnh',
                'duration' => '2 tháng',
                'level' => 'Cơ bản',
                'price' => '3.500.000đ'
            ],
            'csharp' => [
                'title' => 'C# Programming',
                'description' => 'Nền tảng lập trình C# cho game development',
                'duration' => '2 tháng',
                'level' => 'Cơ bản - Trung cấp',
                'price' => '4.000.000đ'
            ],
            'mobile' => [
                'title' => 'Mobile Game Development',
                'description' => 'Phát triển game mobile cho Android và iOS',
                'duration' => '3 tháng',
                'level' => 'Trung cấp',
                'price' => '6.000.000đ'
            ],
            '2d-game' => [
                'title' => '2D Game Development',
                'description' => 'Tạo game 2D với các công cụ hiện đại',
                'duration' => '2.5 tháng',
                'level' => 'Cơ bản - Trung cấp',
                'price' => '4.500.000đ'
            ],
            '3d-game' => [
                'title' => '3D Game Development',
                'description' => 'Phát triển game 3D chuyên nghiệp',
                'duration' => '4 tháng',
                'level' => 'Nâng cao',
                'price' => '8.000.000đ'
            ]
        ];

        $course = $courses[$slug] ?? null;

        if (!$course) {
            abort(404);
        }

        return view('lamgame.pages.course-detail', compact('course'), [
            'page_title' => $course['title'] . ' - Làm Game',
            'page_description' => $course['description']
        ]);
    }

    /**
     * Show AI Tools Subscription page
     */
    public function aiSubscription()
    {
        $customer = Auth::guard('customer')->user();

        return view('lamgame.pages.ai-subscription', [
            'page_title' => 'AI Tools cho Game Developer - Làm Game',
            'page_description' => 'Công cụ AI hỗ trợ lập trình game: Code Generate, Debug, Unit Test, Asset Generate. Gói Free, Pro $9/tháng, Business $29/tháng.',
            'customer' => $customer,
        ]);
    }

    /**
     * Subscribe to AI plan (server-side, session auth)
     */
    public function aiSubscribe(Request $request)
    {
        $request->validate(['plan' => 'required|in:free,pro,business']);

        $customer = Auth::guard('customer')->user();
        $service = app(\App\Services\SubscriptionService::class);
        $planSlug = $request->input('plan');

        if ($planSlug === 'free') {
            $service->subscribeFree($customer->id);
            return redirect()->route('lamgame.ai-subscription')->with('success', 'Đăng ký gói Free thành công!');
        }

        $result = $service->createPaypalSubscription($customer->id, $planSlug);

        if (!$result || !($result['approval_url'] ?? null)) {
            return redirect()->route('lamgame.ai-subscription')->with('error', 'Không thể tạo subscription. Vui lòng thử lại.');
        }

        return redirect($result['approval_url']);
    }

    /**
     * Show Source Game page
     */
    public function sourceGame(Request $request)
    {
        // Input params: search, sort, page, perPage
        $search  = $request->get('search');
        $sort    = $request->get('sort', 'newest'); // newest|price-asc|price-desc|name
        $perPage = (int) $request->get('perPage', 12);
        if ($perPage <= 0 || $perPage > 60) {
            $perPage = 12;
        }

        // Resolve base category by slug aliases
        $slugAliases = ['source-game', 'source-code-game'];
        $baseCategoryIds = \DB::table('category_translations')
            ->whereIn('slug', $slugAliases)
            ->pluck('category_id')
            ->filter()
            ->unique()
            ->values()
            ->all();

        $allCategoryIds = [];
        if (! empty($baseCategoryIds)) {
            // Include base categories and all their descendants using nested set model (_lft, _rgt)
            $allCategoryIds = collect($baseCategoryIds);

            foreach ($baseCategoryIds as $cid) {
                // Find all descendants of this category using nested set model
                $category = \DB::table('categories')->where('id', $cid)->first();
                if ($category) {
                    $descendants = \DB::table('categories')
                        ->where('_lft', '>', $category->_lft)
                        ->where('_rgt', '<', $category->_rgt)
                        ->pluck('id');
                    $allCategoryIds = $allCategoryIds->merge($descendants);
                }
            }

            $allCategoryIds = $allCategoryIds->unique()->values()->all();
        }

        // Build product query
        $productQuery = \Webkul\Product\Models\Product::with(['categories', 'images', 'downloadable_links'])
            ->where('type', 'downloadable');

        if (! empty($allCategoryIds)) {
            $productQuery->whereHas('categories', function ($query) use ($allCategoryIds) {
                $query->whereIn('category_id', $allCategoryIds);
            });
        }

        // Optional search on product_flat fields
        if ($search) {
            $productQuery->whereIn('id', function ($sub) use ($search) {
                $sub->from('product_flat')
                    ->select('product_id')
                    ->where('locale', 'vi')
                    ->where(function ($q) use ($search) {
                        $q->where('name', 'LIKE', '%' . $search . '%')
                          ->orWhere('short_description', 'LIKE', '%' . $search . '%')
                          ->orWhere('description', 'LIKE', '%' . $search . '%');
                    });
            });
        }

        // Sorting based on product_flat
        switch ($sort) {
            case 'price-asc':
                $productQuery->orderByRaw('(SELECT COALESCE(pf.price, 0) FROM product_flat pf WHERE pf.product_id = products.id AND pf.locale = ?) asc', ['vi']);
                break;
            case 'price-desc':
                $productQuery->orderByRaw('(SELECT COALESCE(pf.price, 0) FROM product_flat pf WHERE pf.product_id = products.id AND pf.locale = ?) desc', ['vi']);
                break;
            case 'name':
                $productQuery->orderByRaw('(SELECT pf.name FROM product_flat pf WHERE pf.product_id = products.id AND pf.locale = ?) asc', ['vi']);
                break;
            case 'newest':
            default:
                $productQuery->orderBy('created_at', 'desc');
                break;
        }

        // Paginate products
        $products = $productQuery->simplePaginate($perPage);

        // Transform products for view
        $featuredSources = [];
        foreach ($products as $product) {
            // Get flat row for vi locale
            $flat = \DB::table('product_flat')
                ->where('product_id', $product->id)
                ->where('locale', 'vi')
                ->first();

            $name = $flat->name ?? $product->sku;
            $description = $flat->description ?? 'No description available';
            $shortDescription = $flat->short_description ?? ($description ?: '');
            $price = (float) ($flat->price ?? 0);
            $urlKey = $flat->url_key ?? null;

            // Derived fields — stable per product (seeded by product ID)
            $seed = crc32($product->sku ?? (string) $product->id);
            $engine = 'Unity';
            $language = 'C#';
            $fileSize = '25 MB';

            // Detect engine from name/description
            $nameLower = strtolower($name . ' ' . $shortDescription);
            if (str_contains($nameLower, 'godot')) { $engine = 'Godot'; $language = 'GDScript'; }
            elseif (str_contains($nameLower, 'phaser') || str_contains($nameLower, 'html5') || str_contains($nameLower, 'javascript')) { $engine = 'Phaser'; $language = 'JavaScript'; }
            elseif (str_contains($nameLower, 'unreal')) { $engine = 'Unreal'; $language = 'Blueprint'; }

            // Stable downloads & rating based on product ID
            srand($seed);
            $downloadsCount = rand(200, 2500);
            $rating = number_format(rand(38, 49) / 10, 1);
            srand(); // reset

            // Mark as hot if high downloads
            $isHot = $downloadsCount > 1500;

            // Image
            $previewImage = 'https://images.unsplash.com/photo-1550745165-9bc0b252726f?w=300&h=200&fit=crop';
            if ($product->images && $product->images->isNotEmpty()) {
                $previewImage = asset('storage/' . $product->images->first()->path);
            }

            // Category tag for client filtering
            $categoryTag = 'modern';
            if ($product->categories && $product->categories->contains('id', 3)) {
                $categoryTag = 'unity';
            } elseif ($product->categories && $product->categories->contains('id', 4)) {
                $categoryTag = 'mobile';
            } elseif ($product->categories && $product->categories->contains('id', 5)) {
                $categoryTag = 'web';
            }

            $featuredSources[] = [
                'id' => $product->id,
                'title' => $name,
                'description' => $shortDescription,
                'full_description' => $description,
                'category' => $categoryTag,
                'engine' => $engine,
                'language' => $language,
                'downloads' => $downloadsCount,
                'rating' => $rating,
                'is_hot' => $isHot,
                'preview_image' => $previewImage,
                'size' => $fileSize,
                'price' => $price,
                'updated' => optional($product->updated_at)->format('Y-m-d'),
                'sku' => $product->sku,
                'downloadable_links' => $product->downloadable_links,
                'url_key' => $urlKey,
                'href' => $urlKey ? route('lamgame.source-game.detail', $urlKey) : null,
            ];
        }

        // Fallback sample data if empty
        if (empty($featuredSources)) {
            $featuredSources = [
                [
                    'id' => 'sample-1',
                    'title' => 'Super Mario Clone',
                    'description' => 'Source code hoàn chỉnh của game Mario kinh điển',
                    'category' => 'classic',
                    'engine' => 'Unity',
                    'language' => 'C#',
                    'downloads' => 1250,
                    'rating' => 4.8,
                    'preview_image' => 'https://images.unsplash.com/photo-1550745165-9bc0b252726f?w=300&h=200&fit=crop',
                    'size' => '25 MB',
                    'price' => 0,
                    'updated' => '2024-01-15',
                    'url_key' => 'super-mario-clone-sample',
                    'href' => route('lamgame.source-game.detail', 'super-mario-clone-sample')
                ],
                [
                    'id' => 'sample-2',
                    'title' => 'Space Shooter 2D',
                    'description' => 'Game bắn phi thuyền 2D với AI và power-ups',
                    'category' => '2d',
                    'engine' => 'Unity',
                    'language' => 'C#',
                    'downloads' => 890,
                    'rating' => 4.6,
                    'preview_image' => 'https://images.unsplash.com/photo-1614294148960-9aa740632117?w=300&h=200&fit=crop',
                    'size' => '18 MB',
                    'price' => 0,
                    'updated' => '2024-01-10',
                    'url_key' => 'space-shooter-2d-sample',
                    'href' => route('lamgame.source-game.detail', 'space-shooter-2d-sample')
                ],
                [
                    'id' => 'sample-3',
                    'title' => 'RPG Inventory System',
                    'description' => 'Hệ thống inventory hoàn chỉnh cho game RPG',
                    'category' => 'modern',
                    'engine' => 'Unreal Engine',
                    'language' => 'Blueprint',
                    'downloads' => 567,
                    'rating' => 4.9,
                    'preview_image' => 'https://images.unsplash.com/photo-1542751371-adc38448a05e?w=300&h=200&fit=crop',
                    'size' => '45 MB',
                    'price' => 0,
                    'updated' => '2024-01-08',
                    'url_key' => 'rpg-inventory-system-sample',
                    'href' => route('lamgame.source-game.detail', 'rpg-inventory-system-sample')
                ]
            ];
        }

        // Pagination meta for the view (optional)
        $pagination = [
            'current_page' => method_exists($products, 'currentPage') ? $products->currentPage() : 1,
            'last_page'    => method_exists($products, 'lastPage') ? $products->lastPage() : 1,
            'per_page'     => $perPage,
            'has_more'     => method_exists($products, 'hasMorePages') ? $products->hasMorePages() : false,
        ];

        // Derive trending & best-selling from all sources (sorted copies)
        $allSorted = collect($featuredSources);
        $trendingSources = $allSorted->sortByDesc('downloads')->take(4)->values()->all();
        $bestSellingSources = $allSorted->sortByDesc('rating')->take(4)->values()->all();

        return view('lamgame.pages.source-game', [
            'featuredSources'    => $featuredSources,
            'trendingSources'    => $trendingSources,
            'bestSellingSources' => $bestSellingSources,
            'pagination'         => $pagination,
            'page_title'         => 'Mua Bán Source Game Unity, Unreal | Mã Nguồn Game Giá Rẻ — LamGame.vn',
            'page_description'   => 'Kho source game Unity, Unreal Engine đa dạng thể loại. Mua bán mã nguồn game 2D, 3D chất lượng cao, giá từ 99K. Code sạch, document đầy đủ, hỗ trợ cài đặt miễn phí.',
        ]);
    }

    /**
     * Show Source Game Detail page
     */
    public function sourceGameDetail($slug)
    {
        // Try to find product by URL key first
        $product = null;
        $productFlat = \DB::table('product_flat')
            ->where('url_key', $slug)
            ->where('locale', 'vi')
            ->first();

        if ($productFlat) {
            $product = \Webkul\Product\Models\Product::with(['categories', 'images', 'downloadable_links', 'attribute_values'])
                ->where('id', $productFlat->product_id)
                ->where('type', 'downloadable')
                ->first();
        }

        // If not found by url_key, try by product ID or SKU
        if (!$product) {
            $product = \Webkul\Product\Models\Product::with(['categories', 'images', 'downloadable_links', 'attribute_values'])
                ->where('type', 'downloadable')
                ->where(function ($query) use ($slug) {
                    $query->where('id', $slug)
                          ->orWhere('sku', $slug);
                })
                ->first();
        }

        // If still not found, create sample data
        if (!$product) {
            return $this->getSampleSourceGameDetail($slug);
        }

        // Get product flat data
        $flat = \DB::table('product_flat')
            ->where('product_id', $product->id)
            ->where('locale', 'vi')
            ->first();

        // Get attribute values
        $attributeValues = [];
        if ($product->attribute_values) {
            foreach ($product->attribute_values as $attrValue) {
                $attributeValues[$attrValue->attribute->code] = $attrValue->text_value ?: $attrValue->value;
            }
        }

        // Get seller info
        $seller = \App\Models\SourceGameSeller::find($product->seller_id);

        // Get real purchase count from order_items
        $purchaseCount = \DB::table('order_items')
            ->where('product_id', $product->id)
            ->count();

        // Get real review/comment count
        $reviewCount = \DB::table('product_reviews')
            ->where('product_id', $product->id)
            ->where('status', 'approved')
            ->count();

        // Build source game detail data
        $sourceGameDetail = [
            'id' => $product->id,
            'title' => $flat->name ?? $product->sku,
            'slug' => $slug,
            'description' => strip_tags($flat->short_description ?? ''),
            'full_description' => $flat->description ?? '',
            'price' => (float) ($flat->price ?? 0),
            'is_free' => ((float) ($flat->price ?? 0)) == 0,
            'sku' => $product->sku,
            'engine' => $attributeValues['game_engine'] ?? 'Unity',
            'language' => $attributeValues['programming_language'] ?? 'C#',
            'file_size' => $attributeValues['file_size'] ?? '25 MB',
            'downloads_count' => $purchaseCount,
            'review_count' => $reviewCount,
            'rating' => (float) ($attributeValues['rating'] ?? 0),
            'version' => $attributeValues['version'] ?? '1.0',
            'last_updated' => optional($product->updated_at)->format('Y-m-d'),
            'created_at' => optional($product->created_at)->format('Y-m-d'),
            'images' => [],
            'downloadable_links' => [],
            'video_demo_url' => $attributeValues['video_demo_url'] ?? null,
            'demo_url' => $attributeValues['demo_url'] ?? null,
            'author_name' => $seller->shop_name ?? ($attributeValues['author_name'] ?? 'Làm Game Team'),
            'author_slug' => $seller->shop_slug ?? null,
            'author_logo' => $seller ? $seller->logo_url : null,
            'author_verified' => $seller->verified ?? false,
            'author_email' => $seller->contact_email ?? ($attributeValues['author_email'] ?? null),
            'author_bio' => $seller->shop_description ?? ($attributeValues['author_bio'] ?? ''),
            'requirements' => $attributeValues['requirements'] ?? 'Unity 2022.3 LTS trở lên',
            'features' => [],
            'tags' => [],
            'category_name' => 'Source Game'
        ];

        // Process images — only include files that exist
        if ($product->images && $product->images->isNotEmpty()) {
            foreach ($product->images as $image) {
                $filePath = storage_path('app/public/' . $image->path);
                if (file_exists($filePath)) {
                    $sourceGameDetail['images'][] = [
                        'url' => asset('storage/' . $image->path),
                        'alt' => $sourceGameDetail['title']
                    ];
                }
            }
        }
        // Fallback: 1 placeholder if no valid images
        if (empty($sourceGameDetail['images'])) {
            $sourceGameDetail['images'] = [
                ['url' => asset('images/placeholder-game.svg'), 'alt' => $sourceGameDetail['title']]
            ];
        }

        // Process downloadable links
        if ($product->downloadable_links && $product->downloadable_links->isNotEmpty()) {
            foreach ($product->downloadable_links as $link) {
                $sourceGameDetail['downloadable_links'][] = [
                    'title' => $link->title,
                    'file_name' => $link->file_name,
                    'downloads' => $link->downloads ?? 0,
                    'type' => $link->type,
                    'url' => $link->url
                ];
            }
        }

        // Parse features from description or attributes
        $featuresText = $attributeValues['features'] ?? $sourceGameDetail['full_description'];
        if ($featuresText) {
            $sourceGameDetail['features'] = explode('\n', strip_tags($featuresText));
            $sourceGameDetail['features'] = array_filter(array_map('trim', $sourceGameDetail['features']));
        }

        // Get category name
        if ($product->categories && $product->categories->isNotEmpty()) {
            $category = $product->categories->first();
            $categoryTranslation = \DB::table('category_translations')
                ->where('category_id', $category->id)
                ->where('locale', 'vi')
                ->first();
            $sourceGameDetail['category_name'] = $categoryTranslation->name ?? 'Source Game';
        }

        // Get related source games (same category)
        $relatedSources = [];
        if ($product->categories && $product->categories->isNotEmpty()) {
            $categoryId = $product->categories->first()->id;
            $relatedProducts = \Webkul\Product\Models\Product::with(['categories', 'images'])
                ->where('type', 'downloadable')
                ->where('id', '!=', $product->id)
                ->whereHas('categories', function ($query) use ($categoryId) {
                    $query->where('category_id', $categoryId);
                })
                ->take(3)
                ->get();

            foreach ($relatedProducts as $relatedProduct) {
                $relatedFlat = \DB::table('product_flat')
                    ->where('product_id', $relatedProduct->id)
                    ->where('locale', 'vi')
                    ->first();

                $relatedSources[] = [
                    'title' => $relatedFlat->name ?? $relatedProduct->sku,
                    'url' => route('lamgame.source-game.detail', $relatedFlat->url_key ?? $relatedProduct->id),
                    'image' => $relatedProduct->images && $relatedProduct->images->isNotEmpty()
                             ? asset('storage/' . $relatedProduct->images->first()->path)
                             : asset('images/placeholder-game.svg'),
                    'price' => (float) ($relatedFlat->price ?? 0),
                ];
            }
        }

        return view('lamgame.pages.source-game-detail', [
            'sourceGame' => $sourceGameDetail,
            'relatedSources' => $relatedSources,
            'page_title' => $sourceGameDetail['title'] . ' - Source Game - Làm Game',
            'page_description' => $sourceGameDetail['description'] ?: ('Tải về source code ' . $sourceGameDetail['title'] . ' hoàn toàn miễn phí tại Làm Game.')
        ]);
    }

    /**
     * Get sample source game detail for demo
     */
    private function getSampleSourceGameDetail($slug)
    {
        $sampleGames = [
            'space-shooter-2d' => [
                'title' => 'Space Shooter 2D',
                'description' => 'Game bắn phi thuyền không gian 2D hoàn chỉnh với AI thông minh và nhiều power-ups',
                'full_description' => 'Space Shooter 2D là một game bắn phi thuyền không gian được phát triển bằng Unity. Game có đầy đủ tính năng từ cơ bản đến nâng cao, phù hợp cho việc học tập và phát triển thêm.',
                'engine' => 'Unity 2022.3 LTS',
                'language' => 'C#',
                'file_size' => '45 MB',
                'author_name' => 'Nguyễn Văn A',
                'author_email' => 'developer@lamgame.localhost'
            ],
            'super-mario-clone' => [
                'title' => 'Super Mario Clone',
                'description' => 'Phiên bản clone hoàn chỉnh của game Mario kinh điển với đầy đủ mechanics',
                'full_description' => 'Clone hoàn chỉnh của Super Mario Bros với physics, animations, và gameplay giống như bản gốc. Đầy đủ source code và assets.',
                'engine' => 'Unity 2022.3 LTS',
                'language' => 'C#',
                'file_size' => '38 MB',
                'author_name' => 'Trần Thị B',
                'author_email' => 'mario@lamgame.localhost'
            ]
        ];

        $gameData = $sampleGames[$slug] ?? $sampleGames['space-shooter-2d'];

        $sourceGameDetail = array_merge($gameData, [
            'id' => 'sample-' . $slug,
            'slug' => $slug,
            'price' => 0,
            'is_free' => true,
            'sku' => 'SAMPLE-' . strtoupper($slug),
            'downloads_count' => rand(500, 2000),
            'review_count' => 0,
            'rating' => number_format(rand(40, 50) / 10, 1),
            'version' => '1.0',
            'last_updated' => '2024-01-15',
            'created_at' => '2024-01-01',
            'images' => [
                ['url' => 'https://images.unsplash.com/photo-1550745165-9bc0b252726f?w=800&h=600&fit=crop', 'alt' => $gameData['title']],
                ['url' => 'https://images.unsplash.com/photo-1614294148960-9aa740632117?w=800&h=600&fit=crop', 'alt' => $gameData['title']],
                ['url' => 'https://images.unsplash.com/photo-1542751371-adc38448a05e?w=800&h=600&fit=crop', 'alt' => $gameData['title']]
            ],
            'downloadable_links' => [
                ['title' => 'Source Code', 'file_name' => $slug . '-source.zip', 'downloads' => rand(100, 500), 'type' => 'file'],
                ['title' => 'Documentation', 'file_name' => $slug . '-docs.pdf', 'downloads' => rand(50, 200), 'type' => 'file']
            ],
            'video_demo_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
            'demo_url' => null,
            'author_bio' => 'Lập trình viên game với 5+ năm kinh nghiệm phát triển game Unity',
            'requirements' => 'Unity 2022.3 LTS hoặc mới hơn, Visual Studio hoặc VS Code',
            'features' => [
                'Đầy đủ source code và comments chi tiết',
                'Hệ thống AI cho enemies',
                'Nhiều loại vũ khí và power-ups',
                'Hệ thống điểm số và leaderboard',
                'Sound effects và background music',
                'Mobile-ready controls',
                'Dễ dàng customize và mở rộng'
            ],
            'tags' => ['Unity', '2D', 'Shooter', 'Mobile', 'Beginner Friendly'],
            'category_name' => 'Game 2D'
        ]);

        $relatedSources = [
            [
                'title' => 'Flappy Bird Clone',
                'url' => route('lamgame.source-game.detail', 'flappy-bird-clone'),
                'image' => 'https://images.unsplash.com/photo-1550745165-9bc0b252726f?w=300&h=200&fit=crop',
                'price' => 0,
                'rating' => 4.3
            ],
            [
                'title' => 'Puzzle Match 3',
                'url' => route('lamgame.source-game.detail', 'puzzle-match-3'),
                'image' => 'https://images.unsplash.com/photo-1614294148960-9aa740632117?w=300&h=200&fit=crop',
                'price' => 0,
                'rating' => 4.7
            ],
            [
                'title' => 'RPG Character System',
                'url' => route('lamgame.source-game.detail', 'rpg-character-system'),
                'image' => 'https://images.unsplash.com/photo-1542751371-adc38448a05e?w=300&h=200&fit=crop',
                'price' => 150000,
                'rating' => 4.9
            ]
        ];

        return view('lamgame.pages.source-game-detail', [
            'sourceGame' => $sourceGameDetail,
            'relatedSources' => $relatedSources,
            'page_title' => $sourceGameDetail['title'] . ' - Source Game - Làm Game',
            'page_description' => $sourceGameDetail['description']
        ]);
    }

    /**
     * Show Cộng đồng page
     */
    public function congDong()
    {
        // Get forum categories
        $forumCategories = ForumCategory::active()
            ->ordered()
            ->withCount(['posts' => function ($query) {
                $query->where('status', 'published');
            }])
            ->get();

        // Convert to old format for compatibility with existing view
        $categories = [];
        foreach ($forumCategories as $category) {
            $categories[$category->slug] = $category->name;
        }

        // Get latest forum posts
        $forumPosts = ForumPost::published()
            ->with(['category'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Convert to old format for compatibility with existing view
        $posts = [];
        foreach ($forumPosts as $post) {
            $posts[] = [
                'id' => $post->id,
                'title' => $post->title,
                'author' => $post->author_name,
                'category' => $post->category->name,
                'replies' => $post->comments_count,
                'views' => $post->views_count,
                'created_at' => $post->created_at->format('Y-m-d H:i:s'),
                'excerpt' => $post->excerpt,
            ];
        }

        return view('lamgame.pages.cong-dong', compact('posts', 'categories'), [
            'page_title' => 'Cộng đồng Game Developer - Làm Game',
            'page_description' => 'Tham gia cộng đồng game developer Việt Nam. Chia sẻ kinh nghiệm, tìm kiếm đồng đội và học hỏi từ những chuyên gia.'
        ]);
    }

    /**
     * Show Chia sẻ ý tưởng page
     */
    public function chiaSeyTuong()
    {
        $ideaPosts = [
            [
                'id' => 1,
                'title' => 'Game mobile về nông nghiệp Việt Nam',
                'author' => 'FarmGameVN',
                'description' => 'Ý tưởng game mô phỏng việc trồng trọt các loại cây đặc sản Việt Nam như lúa, cà phê, cao su...',
                'genre' => 'Simulation',
                'platform' => 'Mobile',
                'team_needed' => ['Programmer', '2D Artist', 'Game Designer'],
                'created_at' => '2024-01-15',
                'likes' => 23,
                'comments' => 7
            ],
            [
                'id' => 2,
                'title' => 'RPG Tam Quốc với gameplay mới',
                'author' => 'HistoryGamer',
                'description' => 'Game nhập vai lấy bối cảnh Tam Quốc nhưng với hệ thống combat theo thời gian thực và AI thông minh...',
                'genre' => 'RPG',
                'platform' => 'PC',
                'team_needed' => ['Unity Developer', '3D Artist', 'Sound Designer'],
                'created_at' => '2024-01-14',
                'likes' => 18,
                'comments' => 5
            ]
        ];

        return view('lamgame.pages.chia-se-y-tuong', compact('ideaPosts'), [
            'page_title' => 'Chia sẻ ý tưởng Game - Cộng đồng Làm Game',
            'page_description' => 'Nơi gamer chia sẻ ý tưởng game độc đáo và tìm kiếm đội ngũ phát triển. Biến ý tưởng thành hiện thực.'
        ]);
    }

    /**
     * Handle creating new idea post
     */
    public function taoYTuong(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:2000',
            'genre' => 'required|string|max:100',
            'platform' => 'required|string|max:100',
            'team_needed' => 'nullable|array',
            'team_needed.*' => 'string|max:100'
        ]);

        // Here you would save to database
        // For now, just return success response
        return response()->json([
            'success' => true,
            'message' => 'Ý tưởng của bạn đã được đăng thành công!'
        ]);
    }

    /**
     * View specific community post
     */
    public function xemBaiViet($id)
    {
        // Sample post data - in real app, fetch from database
        $post = [
            'id' => $id,
            'title' => 'Làm thế nào để tối ưu performance game Unity?',
            'content' => 'Chi tiết nội dung bài viết...',
            'author' => 'GameDev_VN',
            'created_at' => '2024-01-15 09:30:00',
            'views' => 234,
            'category' => 'Thảo luận'
        ];

        $comments = [
            [
                'author' => 'UnityExpert',
                'content' => 'Bạn có thể thử sử dụng Object Pooling để tối ưu...',
                'created_at' => '2024-01-15 10:15:00'
            ],
            [
                'author' => 'GameOptimizer',
                'content' => 'Ngoài ra, hãy chú ý đến texture compression...',
                'created_at' => '2024-01-15 11:20:00'
            ]
        ];

        return view('lamgame.pages.bai-viet', compact('post', 'comments'), [
            'page_title' => $post['title'] . ' - Cộng đồng Làm Game',
            'page_description' => 'Thảo luận về ' . $post['title']
        ]);
    }

    /**
     * Handle comment submission
     */
    public function binhLuan(Request $request)
    {
        $request->validate([
            'post_id' => 'required|integer',
            'content' => 'required|string|max:1000',
            'author' => 'required|string|max:100'
        ]);

        // Here you would save comment to database
        // For now, just return success response
        return response()->json([
            'success' => true,
            'message' => 'Bình luận của bạn đã được đăng!'
        ]);
    }

    /**
     * Show "Thuê Team Dev" landing page
     */
    public function hireTeam()
    {
        return view('lamgame.pages.thue-team-dev', [
            'page_title'       => 'Thuê Team Dev - Phát triển Game, Web, App | LamGame.vn',
            'page_description' => 'Thuê đội ngũ lập trình viên chuyên nghiệp để phát triển game, website, ứng dụng mobile và giải pháp AI.',
        ]);
    }
}
