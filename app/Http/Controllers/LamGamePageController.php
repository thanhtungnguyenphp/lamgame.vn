<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Mail;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\BlogTag;
use App\Models\ForumCategory;
use App\Models\ForumPost;

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
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
        ]);

        // Store in database or send email
        // For now, just return success response
        return response()->json([
            'success' => true,
            'message' => 'Cảm ơn bạn đã liên hệ! Chúng tôi sẽ phản hồi trong thời gian sớm nhất.'
        ]);
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
            if ($category) {
                $blogsQuery->where(function($query) use ($category) {
                    $query->where('default_category', $category->id)
                          ->orWhere('categorys', 'LIKE', '%' . $category->id . '%');
                });
            }
        }

        // Filter by tag if specified
        if ($tagSlug) {
            $tag = BlogTag::where('slug', $tagSlug)->active()->first();
            if ($tag) {
                $blogsQuery->where('tags', 'LIKE', '%' . $tag->id . '%');
            }
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

        // Get popular tags for sidebar
        $popularTags = BlogTag::active()
            ->orderBy('name')
            ->get()
            ->filter(function($tag) {
                return $tag->published_blogs_count > 0;
            })
            ->take(20);

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

        // Get popular tags for sidebar
        $popularTags = BlogTag::active()
            ->orderBy('name')
            ->get()
            ->filter(function($tag) {
                return $tag->published_blogs_count > 0;
            })
            ->take(15);

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
     * Show Viec lam Game page
     */
    public function viecLamGame(Request $request)
    {
        // Get job search parameters
        $keyword = $request->get('keyword');
        $location = $request->get('location');
        $level = $request->get('level');
        $sort = $request->get('sort', 'newest');
        $perPage = 10;

        // Get job products from database
        $jobsQuery = \DB::table('products as p')
            ->leftJoin('product_flat as pf', function($join) {
                $join->on('p.id', '=', 'pf.product_id')
                     ->where('pf.locale', '=', 'vi');
            })
            ->leftJoin('product_categories as pc', 'p.id', '=', 'pc.product_id')
            ->leftJoin('category_translations as ct', function($join) {
                $join->on('pc.category_id', '=', 'ct.category_id')
                     ->where('ct.locale', '=', 'vi');
            })
            ->leftJoin('product_attribute_values as pav_deadline', function($join) {
                $join->on('p.id', '=', 'pav_deadline.product_id')
                     ->leftJoin('attributes as a_deadline', 'pav_deadline.attribute_id', '=', 'a_deadline.id')
                     ->where('a_deadline.code', '=', 'application_deadline');
            })
            ->leftJoin('product_attribute_values as pav_email', function($join) {
                $join->on('p.id', '=', 'pav_email.product_id')
                     ->leftJoin('attributes as a_email', 'pav_email.attribute_id', '=', 'a_email.id')
                     ->where('a_email.code', '=', 'contact_email');
            })
            ->where('p.sku', 'LIKE', 'JOB_%')
            ->where('pf.status', 1)
            ->where('pf.visible_individually', 1)
            ->select(
                'p.id',
                'p.sku', 
                'pf.name',
                'pf.short_description',
                'pf.description',
                'pf.price',
                'pf.url_key',
                'ct.name as category_name',
                'p.created_at',
                'pav_deadline.text_value as application_deadline',
                'pav_email.text_value as contact_email'
            );

        // Apply search filters
        if ($keyword) {
            $jobsQuery->where(function($query) use ($keyword) {
                $query->where('pf.name', 'LIKE', '%' . $keyword . '%')
                      ->orWhere('pf.short_description', 'LIKE', '%' . $keyword . '%')
                      ->orWhere('pf.description', 'LIKE', '%' . $keyword . '%');
            });
        }

        if ($location && $location !== '') {
            $jobsQuery->where('pf.short_description', 'LIKE', '%' . $location . '%');
        }

        // Apply sorting
        switch ($sort) {
            case 'salary-high':
                $jobsQuery->orderBy('pf.price', 'desc');
                break;
            case 'company':
                $jobsQuery->orderBy('pf.name', 'asc');
                break;
            case 'newest':
            default:
                $jobsQuery->orderBy('p.created_at', 'desc');
                break;
        }

        // Get paginated results
        $jobs = $jobsQuery->paginate($perPage);
        
        // Get additional job attributes for each job
        foreach ($jobs as $job) {
            $job->attributes = $this->getJobAttributes($job->id);
        }

        // Get job statistics for sidebar
        $totalJobs = \DB::table('products as p')
            ->leftJoin('product_flat as pf', function($join) {
                $join->on('p.id', '=', 'pf.product_id')
                     ->where('pf.locale', '=', 'vi');
            })
            ->where('p.sku', 'LIKE', 'JOB_%')
            ->where('pf.status', 1)
            ->where('pf.visible_individually', 1)
            ->count();

        // Get companies with job counts
        $topCompanies = \DB::table('products as p')
            ->leftJoin('product_flat as pf', function($join) {
                $join->on('p.id', '=', 'pf.product_id')
                     ->where('pf.locale', '=', 'vi');
            })
            ->where('p.sku', 'LIKE', 'JOB_%')
            ->where('pf.status', 1)
            ->select(
                \DB::raw('SUBSTRING_INDEX(SUBSTRING_INDEX(pf.name, " - ", -1), " ", 2) as company_name'),
                \DB::raw('COUNT(*) as job_count')
            )
            ->groupBy('company_name')
            ->orderBy('job_count', 'desc')
            ->take(5)
            ->get();

        return view('lamgame.pages.viec-lam-game', [
            'page_title' => 'Việc làm Game - Làm Game',
            'page_description' => 'Tìm kiếm cơ hội việc làm trong ngành game development tại Việt Nam và quốc tế.',
            'jobs' => $jobs,
            'totalJobs' => $totalJobs,
            'topCompanies' => $topCompanies,
            'searchParams' => [
                'keyword' => $keyword,
                'location' => $location,
                'level' => $level,
                'sort' => $sort
            ]
        ]);
    }

    /**
     * Get job attributes for a specific job
     */
    private function getJobAttributes($productId)
    {
        $attributes = \DB::table('product_attribute_values as pav')
            ->join('attributes as a', 'pav.attribute_id', '=', 'a.id')
            ->leftJoin('attribute_options as ao', 'pav.integer_value', '=', 'ao.id')
            ->leftJoin('attribute_option_translations as aot', function($join) {
                $join->on('ao.id', '=', 'aot.attribute_option_id')
                     ->where('aot.locale', '=', 'vi');
            })
            ->where('pav.product_id', $productId)
            ->whereIn('a.code', ['job_type', 'experience_level', 'salary_range', 'location', 'skills_required'])
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
            $value = $attr->text_value ?: $attr->option_label ?: $attr->integer_value;
            $jobAttributes[$attr->code] = $value;
        }

        return $jobAttributes;
    }

    /**
     * Show Job detail page
     */
    public function jobDetail($id, $slug = null)
    {
        // Get job product from database
        $job = \DB::table('products as p')
            ->leftJoin('product_flat as pf', function($join) {
                $join->on('p.id', '=', 'pf.product_id')
                     ->where('pf.locale', '=', 'vi');
            })
            ->leftJoin('product_categories as pc', 'p.id', '=', 'pc.product_id')
            ->leftJoin('category_translations as ct', function($join) {
                $join->on('pc.category_id', '=', 'ct.category_id')
                     ->where('ct.locale', '=', 'vi');
            })
            ->leftJoin('product_attribute_values as pav_deadline', function($join) {
                $join->on('p.id', '=', 'pav_deadline.product_id')
                     ->leftJoin('attributes as a_deadline', 'pav_deadline.attribute_id', '=', 'a_deadline.id')
                     ->where('a_deadline.code', '=', 'application_deadline');
            })
            ->leftJoin('product_attribute_values as pav_email', function($join) {
                $join->on('p.id', '=', 'pav_email.product_id')
                     ->leftJoin('attributes as a_email', 'pav_email.attribute_id', '=', 'a_email.id')
                     ->where('a_email.code', '=', 'contact_email');
            })
            ->where('p.id', $id)
            ->where('p.sku', 'LIKE', 'JOB_%')
            ->where('pf.status', 1)
            ->where('pf.visible_individually', 1)
            ->select(
                'p.id',
                'p.sku', 
                'pf.name',
                'pf.short_description',
                'pf.description',
                'pf.price',
                'pf.url_key',
                'ct.name as category_name',
                'p.created_at',
                'p.updated_at',
                'pav_deadline.text_value as application_deadline',
                'pav_email.text_value as contact_email'
            )
            ->first();

        if (!$job) {
            abort(404, 'Job not found');
        }

        // Get job attributes
        $job->attributes = $this->getJobAttributes($job->id);

        // Parse job data
        $companyName = trim(str_replace(' - ', ' ', explode(' - ', $job->name)[1] ?? $job->name));
        $jobTitle = explode(' - ', $job->name)[0] ?? $job->name;
        $salaryFormatted = number_format($job->price / 1000000, 1) . ' triệu VND';
        $postedAgo = \Carbon\Carbon::parse($job->created_at)->diffForHumans();
        
        // Get similar jobs
        $similarJobs = \DB::table('products as p')
            ->leftJoin('product_flat as pf', function($join) {
                $join->on('p.id', '=', 'pf.product_id')
                     ->where('pf.locale', '=', 'vi');
            })
            ->where('p.sku', 'LIKE', 'JOB_%')
            ->where('p.id', '!=', $id)
            ->where('pf.status', 1)
            ->where('pf.visible_individually', 1)
            ->select('p.id', 'p.sku', 'pf.name', 'pf.price', 'p.created_at')
            ->orderBy('p.created_at', 'desc')
            ->limit(3)
            ->get();

        return view('lamgame.pages.job-detail', [
            'job' => $job,
            'jobTitle' => $jobTitle,
            'companyName' => $companyName,
            'salaryFormatted' => $salaryFormatted,
            'postedAgo' => $postedAgo,
            'similarJobs' => $similarJobs,
            'page_title' => $jobTitle . ' - ' . $companyName . ' - Làm Game',
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

            // Derived fields
            $engine = 'Unity';
            $language = 'C#';
            $fileSize = '25 MB';
            $downloadsCount = rand(100, 2000);
            $rating = (float) number_format(rand(35, 50) / 10, 1);

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

        return view('lamgame.pages.source-game', [
            'featuredSources' => $featuredSources,
            'pagination'      => $pagination,
            'page_title'      => 'Source Game - Kho Mã Nguồn Game - Làm Game',
            'page_description'=> 'Tổng hợp các source code game từ cổ điển đến hiện đại. Tải miễn phí để học tập và nghiên cứu.'
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

        // Build source game detail data
        $sourceGameDetail = [
            'id' => $product->id,
            'title' => $flat->name ?? $product->sku,
            'slug' => $slug,
            'description' => $flat->short_description ?? '',
            'full_description' => $flat->description ?? '',
            'price' => (float) ($flat->price ?? 0),
            'is_free' => ((float) ($flat->price ?? 0)) == 0,
            'sku' => $product->sku,
            'engine' => $attributeValues['game_engine'] ?? 'Unity',
            'language' => $attributeValues['programming_language'] ?? 'C#',
            'file_size' => $attributeValues['file_size'] ?? '25 MB',
            'downloads_count' => (int) ($attributeValues['downloads_count'] ?? rand(100, 2000)),
            'rating' => (float) ($attributeValues['rating'] ?? number_format(rand(35, 50) / 10, 1)),
            'version' => $attributeValues['version'] ?? '1.0',
            'last_updated' => optional($product->updated_at)->format('Y-m-d'),
            'created_at' => optional($product->created_at)->format('Y-m-d'),
            'images' => [],
            'downloadable_links' => [],
            'video_demo_url' => $attributeValues['video_demo_url'] ?? null,
            'demo_url' => $attributeValues['demo_url'] ?? null,
            'author_name' => $attributeValues['author_name'] ?? 'Làm Game Team',
            'author_email' => $attributeValues['author_email'] ?? 'contact@lamgame.localhost',
            'author_bio' => $attributeValues['author_bio'] ?? 'Đội ngũ phát triển chuyên nghiệp tại Làm Game',
            'requirements' => $attributeValues['requirements'] ?? 'Unity 2022.3 LTS trở lên',
            'features' => [],
            'tags' => [],
            'category_name' => 'Source Game'
        ];

        // Process images
        if ($product->images && $product->images->isNotEmpty()) {
            foreach ($product->images as $image) {
                $sourceGameDetail['images'][] = [
                    'url' => asset('storage/' . $image->path),
                    'alt' => $sourceGameDetail['title']
                ];
            }
        } else {
            // Default images if none
            $sourceGameDetail['images'] = [
                ['url' => 'https://images.unsplash.com/photo-1550745165-9bc0b252726f?w=800&h=600&fit=crop', 'alt' => $sourceGameDetail['title']],
                ['url' => 'https://images.unsplash.com/photo-1614294148960-9aa740632117?w=800&h=600&fit=crop', 'alt' => $sourceGameDetail['title']]
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
                             : 'https://images.unsplash.com/photo-1550745165-9bc0b252726f?w=300&h=200&fit=crop',
                    'price' => (float) ($relatedFlat->price ?? 0),
                    'rating' => 4.5
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
}
