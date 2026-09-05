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
     * Show Gioi thieu page với dynamic metrics
     */
    public function gioiThieu()
    {
        $metricsService = new \App\Services\SiteMetricsService();
        $metrics = $metricsService->getMetrics();
        
        return view('lamgame.pages.gioi-thieu', [
            'page_title' => 'Giới thiệu LamGame.vn — Hệ sinh thái Game Developer Việt Nam',
            'page_description' => 'LamGame.vn là hệ sinh thái dành cho cộng đồng Game Developer Việt Nam với source code, tutorial, việc làm và cộng đồng.',
            'metrics' => $metrics,
        ]);
    }

    /**
     * Show AI Tools subscription page
     */
    public function aiTools()
    {
        $plans = \App\Models\SubscriptionPlan::active()->get();

        return view('lamgame.pages.ai-tools-landing', [
            'customer' => auth()->guard('customer')->user(),
            'plans'    => $plans,
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
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'phone'   => 'nullable|string|max:20',
            'email'   => 'required|email|max:255',
            'subject' => 'required|string|max:100',
            'message' => 'required|string|max:2000',
        ], [
            'name.required' => 'Vui lòng nhập họ tên.',
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Email không hợp lệ.',
            'subject.required' => 'Vui lòng chọn chủ đề.',
            'message.required' => 'Vui lòng nhập nội dung tin nhắn.',
        ]);

        // Map subject values to readable labels
        $subjectLabels = [
            'source-game' => 'Hỏi về Source Game',
            'ai-tools' => 'Hỏi về AI Tools',
            'hop-tac' => 'Hợp tác kinh doanh',
            'ho-tro' => 'Hỗ trợ kỹ thuật',
            'khac' => 'Khác',
        ];
        $subjectLabel = $subjectLabels[$validated['subject']] ?? $validated['subject'];

        try {
            // Send email to admin
            \Illuminate\Support\Facades\Mail::send([], [], function ($mail) use ($validated, $subjectLabel) {
                $mail->to('salegamevui@gmail.com')
                    ->subject('[LamGame Contact] ' . $subjectLabel)
                    ->html($this->buildContactEmailHtml($validated, $subjectLabel));
            });

            \Illuminate\Support\Facades\Log::info('Contact form submitted', [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'subject' => $validated['subject'],
            ]);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Contact form email failed', [
                'error' => $e->getMessage(),
                'email' => $validated['email'] ?? 'unknown',
            ]);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Tin nhắn của bạn đã được gửi thành công!',
            ]);
        }

        return back()->with('success', 'Tin nhắn của bạn đã được gửi thành công!');
    }

    /**
     * Build HTML email content for contact form
     */
    private function buildContactEmailHtml(array $data, string $subjectLabel): string
    {
        $phone = $data['phone'] ?? 'Không cung cấp';
        $message = nl2br(htmlspecialchars($data['message']));
        
        return <<<HTML
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #8B5CF6, #6366F1); color: white; padding: 20px; border-radius: 8px 8px 0 0; }
        .header h1 { margin: 0; font-size: 20px; }
        .content { background: #f9fafb; padding: 20px; border: 1px solid #e5e7eb; border-top: none; border-radius: 0 0 8px 8px; }
        .field { margin-bottom: 15px; }
        .field-label { font-weight: 600; color: #6B7280; font-size: 12px; text-transform: uppercase; margin-bottom: 4px; }
        .field-value { color: #111827; }
        .message-box { background: white; padding: 15px; border-radius: 6px; border: 1px solid #e5e7eb; margin-top: 15px; }
        .footer { margin-top: 20px; font-size: 12px; color: #9CA3AF; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📬 Tin nhắn mới từ LamGame.vn</h1>
        </div>
        <div class="content">
            <div class="field">
                <div class="field-label">Chủ đề</div>
                <div class="field-value"><strong>{$subjectLabel}</strong></div>
            </div>
            <div class="field">
                <div class="field-label">Họ tên</div>
                <div class="field-value">{$data['name']}</div>
            </div>
            <div class="field">
                <div class="field-label">Email</div>
                <div class="field-value"><a href="mailto:{$data['email']}">{$data['email']}</a></div>
            </div>
            <div class="field">
                <div class="field-label">Số điện thoại</div>
                <div class="field-value">{$phone}</div>
            </div>
            <div class="message-box">
                <div class="field-label">Nội dung tin nhắn</div>
                <div class="field-value">{$message}</div>
            </div>
        </div>
        <div class="footer">
            Email này được gửi tự động từ form liên hệ tại lamgame.vn
        </div>
    </div>
</body>
</html>
HTML;
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
            $category = BlogCategory::where('slug', $categorySlug)->first();
            if (!$category) {
                // Category doesn't exist → 404
                abort(404);
            }
            if (!$category->status) {
                // Category was deactivated (legacy/cleanup) → 410 Gone
                abort(410, 'This category has been removed.');
            }
            // Use exact matching for category IDs
            // default_category is the primary category (integer)
            // categorys is comma-separated IDs like "1,5,10" - use FIND_IN_SET for exact match
            $blogsQuery->where(function($query) use ($category) {
                $query->where('default_category', $category->id)
                      ->orWhereRaw('FIND_IN_SET(?, categorys) > 0', [$category->id]);
            });
        }

        // Filter by tag if specified
        if ($tagSlug) {
            $tag = BlogTag::where('slug', $tagSlug)->first();
            if (!$tag) {
                // Tag doesn't exist → 404
                abort(404);
            }
            if (!$tag->status) {
                // Tag was deactivated (legacy/cleanup) → 410 Gone
                abort(410, 'This tag has been removed.');
            }
            // Use FIND_IN_SET for exact tag ID matching (tags is comma-separated like "1,5,10")
            $blogsQuery->whereRaw('FIND_IN_SET(?, tags) > 0', [$tag->id]);
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
            'page_title' => 'Blog Lập Trình Game | Hướng Dẫn Unity, Unreal, Godot — LamGame.vn',
            'page_description' => 'Blog chia sẻ kiến thức lập trình game từ cộng đồng developer Việt Nam. Hướng dẫn Unity, Unreal Engine, Godot, tips tối ưu hiệu năng, và xu hướng game dev mới nhất.',
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
                    ->with(['category', 'authorModel'])
                    ->firstOrFail();

        // Get related posts from the same category
        $relatedPosts = Blog::published()
                           ->where('id', '!=', $blog->id)
                           ->where(function($query) use ($blog) {
                               $query->where('default_category', $blog->default_category)
                                     ->orWhereRaw('FIND_IN_SET(?, categorys) > 0', [$blog->default_category]);
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

        $descriptionSource = $blog->meta_description ?: $blog->short_description ?: $blog->description;
        $pageDescription = \Str::limit(
            preg_replace('/\s+/u', ' ', strip_tags((string) $descriptionSource)),
            160,
            ''
        );

        return view('lamgame.pages.blog-detail', [
            'page_title' => $blog->meta_title ?: $blog->name . ' - Làm Game',
            'page_description' => $pageDescription,
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
        $companyType = $request->get('company_type');
        $sort = $request->get('sort', 'newest');

        $query = \App\Models\JobPosting::with('skills', 'benefits')
            ->where('status', 'active')
            ->where('is_game_related', true); // Only show game-related jobs

        if ($keyword) {
            $query->search($keyword);
        }
        if ($location) {
            $query->byLocation($location);
        }
        if ($level) {
            $query->where('experience_level', $level);
        }
        if ($companyType) {
            $query->where('company_type', $companyType);
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

        return view('lamgame.pages.viec-lam-game-v2', [
            'page_title' => 'Việc Làm Game Developer Mới Nhất ' . date('Y') . ' | Unity, Unreal, Game Design — LamGame.vn',
            'page_description' => 'Khám phá ' . $totalJobs . '+ cơ hội việc làm game development tại Việt Nam. Tuyển dụng Unity Developer, Game Designer, 3D Artist và nhiều vị trí khác.',
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
     * My Applications page — show logged-in developer's job applications
     */
    public function myApplications(Request $request)
    {
        $customer = auth('customer')->user();
        if (!$customer) {
            return redirect()->route('auth.login')->with('info', 'Vui lòng đăng nhập để xem đơn ứng tuyển.');
        }

        $statusFilter = $request->get('status');
        $query = \App\Models\JobApplication::with('jobPosting')
            ->where('applicant_user_id', $customer->id)
            ->orderByDesc('applied_at');

        if ($statusFilter && in_array($statusFilter, ['pending', 'reviewed', 'shortlisted', 'rejected', 'accepted'])) {
            $query->where('status', $statusFilter);
        }

        $applications = $query->paginate(10);

        return view('lamgame.pages.my-applications', [
            'applications'  => $applications,
            'currentStatus' => $statusFilter,
            'page_title'    => 'Đơn ứng tuyển của tôi - Làm Game',
            'page_description' => 'Theo dõi trạng thái đơn ứng tuyển việc làm game của bạn.',
        ]);
    }

    /**
     * Company Profile page — public view of a company + their active jobs
     */
    public function companyProfile($slug)
    {
        // Support both slug and numeric ID
        $company = is_numeric($slug)
            ? \App\Models\Company::findOrFail($slug)
            : \App\Models\Company::where('slug', $slug)->firstOrFail();

        $jobs = \App\Models\JobPosting::where('company_id', $company->id)
            ->where('status', 'active')
            ->orderByDesc('created_at')
            ->paginate(10);

        // Also try matching by company_name for jobs without company_id
        $jobsByName = \App\Models\JobPosting::where('company_name', $company->name)
            ->where('status', 'active')
            ->whereNull('company_id')
            ->orderByDesc('created_at')
            ->get();

        // Merge (avoid duplicates)
        $allJobs = $jobs->getCollection()->merge($jobsByName)->unique('id');

        $totalApplications = \App\Models\JobPosting::where('company_id', $company->id)
            ->orWhere('company_name', $company->name)
            ->sum('application_count');

        return view('lamgame.pages.company-profile', [
            'company'           => $company,
            'jobs'              => $jobs,
            'totalJobs'         => $allJobs->count(),
            'totalApplications' => $totalApplications,
            'page_title'        => $company->name . ' — Tuyển dụng Game | Làm Game',
            'page_description'  => \Str::limit($company->description, 160),
        ]);
    }

    /**
     * Saved Jobs page — show user's bookmarked jobs
     */
    public function savedJobs()
    {
        $customer = auth('customer')->user();

        $savedJobs = \App\Models\SavedJob::with('jobPosting.skills')
            ->where('user_id', $customer->id)
            ->orderByDesc('saved_at')
            ->paginate(12);

        return view('lamgame.pages.saved-jobs', [
            'savedJobs'        => $savedJobs,
            'page_title'       => 'Việc làm đã lưu - Làm Game',
            'page_description' => 'Danh sách việc làm game bạn đã lưu để xem lại.',
        ]);
    }

    /**
     * Toggle save/unsave a job (AJAX)
     */
    public function toggleSaveJob(int $id)
    {
        $customer = auth('customer')->user();
        $existing = \App\Models\SavedJob::where('user_id', $customer->id)
            ->where('job_posting_id', $id)
            ->first();

        if ($existing) {
            $existing->delete();
            return response()->json(['saved' => false, 'message' => 'Đã bỏ lưu việc làm']);
        }

        \App\Models\SavedJob::create([
            'user_id'        => $customer->id,
            'job_posting_id' => $id,
        ]);

        return response()->json(['saved' => true, 'message' => 'Đã lưu việc làm']);
    }

    /**
     * Store a new job alert
     */
    public function storeJobAlert(Request $request)
    {
        $customer = auth('customer')->user();

        $request->validate([
            'keywords'  => 'nullable|string|max:255',
            'skills'    => 'nullable|array|max:10',
            'location'  => 'nullable|string|max:100',
            'frequency' => 'required|in:daily,weekly',
        ]);

        // Max 5 alerts per user
        $alertCount = \App\Models\JobAlert::where('user_id', $customer->id)->count();
        if ($alertCount >= 5) {
            return response()->json(['message' => 'Tối đa 5 thông báo việc làm'], 422);
        }

        $alert = \App\Models\JobAlert::create([
            'user_id'   => $customer->id,
            'keywords'  => $request->keywords,
            'skills'    => $request->skills,
            'location'  => $request->location,
            'frequency' => $request->frequency,
        ]);

        return response()->json(['message' => 'Đã tạo thông báo việc làm', 'alert' => $alert], 201);
    }

    /**
     * Delete a job alert
     */
    public function deleteJobAlert(int $id)
    {
        $customer = auth('customer')->user();
        $alert = \App\Models\JobAlert::where('user_id', $customer->id)->findOrFail($id);
        $alert->delete();

        return response()->json(['message' => 'Đã xóa thông báo']);
    }

    /**
     * Show Course detail page
     */
    public function courseDetail($slug)
    {
        $courses = [
            'unity' => [
                'title' => 'Khóa Học Lập Trình Game Unity',
                'description' => 'Học lập trình game với Unity từ cơ bản đến nâng cao. Thực hành tạo game 2D/3D, C# scripting, physics, animation. Phù hợp cho người mới bắt đầu.',
                'duration' => '3 tháng',
                'level' => 'Từ cơ bản đến nâng cao',
                'price' => '5.000.000đ'
            ],
            'unreal' => [
                'title' => 'Khóa Học Unreal Engine',
                'description' => 'Phát triển game 3D chất lượng AAA với Unreal Engine 5. Blueprint visual scripting, C++, materials, lighting. Cho mid-senior developer.',
                'duration' => '4 tháng',
                'level' => 'Trung cấp - Nâng cao',
                'price' => '7.000.000đ'
            ],
            'game-design' => [
                'title' => 'Khóa Học Game Design',
                'description' => 'Thiết kế game chuyên nghiệp từ ý tưởng đến GDD hoàn chỉnh. Game mechanics, level design, UX, monetization, balance.',
                'duration' => '2 tháng',
                'level' => 'Cơ bản',
                'price' => '3.500.000đ'
            ],
            'csharp' => [
                'title' => 'Khóa Học Lập Trình C# Cho Game',
                'description' => 'Nền tảng lập trình C# dành cho game developer. OOP, data structures, design patterns áp dụng vào Unity game development.',
                'duration' => '2 tháng',
                'level' => 'Cơ bản - Trung cấp',
                'price' => '4.000.000đ'
            ],
            'mobile' => [
                'title' => 'Khóa Học Lập Trình Game Mobile',
                'description' => 'Phát triển game mobile cho Android và iOS. Unity mobile optimization, touch controls, AdMob, publish lên Google Play/App Store.',
                'duration' => '3 tháng',
                'level' => 'Trung cấp',
                'price' => '6.000.000đ'
            ],
            '2d-game' => [
                'title' => 'Khóa Học Làm Game 2D',
                'description' => 'Tạo game 2D hoàn chỉnh với Unity và Phaser. Sprite animation, tilemap, physics 2D, platformer, top-down RPG.',
                'duration' => '2.5 tháng',
                'level' => 'Cơ bản - Trung cấp',
                'price' => '4.500.000đ'
            ],
            '3d-game' => [
                'title' => 'Khóa Học Làm Game 3D',
                'description' => 'Phát triển game 3D chuyên nghiệp với Unity/Unreal. 3D modeling integration, shaders, AI navigation, multiplayer basics.',
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
            'page_title' => $course['title'] . ' | Học Online — LamGame.vn',
            'page_description' => $course['description'],
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
        // Input params: search, sort, page, perPage, genre
        $search  = $request->get('search');
        $sort    = $request->get('sort', 'newest'); // newest|price-asc|price-desc|name
        $genre   = $request->get('genre'); // genre filter from homepage categories
        $perPage = (int) $request->get('perPage', 12);
        if ($perPage <= 0 || $perPage > 60) {
            $perPage = 12;
        }

        $catalogService = app(\App\Services\SourceGameCatalogService::class);

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
            ->where('type', 'downloadable')
            ->whereIn('id', $catalogService->publishedProductIds());

        if (! empty($allCategoryIds)) {
            $productQuery->whereHas('categories', function ($query) use ($allCategoryIds) {
                $query->whereIn('category_id', $allCategoryIds);
            });
        }

        // Filter by genre if specified (from homepage category cards)
        if ($genre && $genre !== 'all') {
            $productQuery->whereIn('id', function ($sub) use ($genre) {
                $sub->from('product_flat')
                    ->select('product_id')
                    ->where('locale', 'vi')
                    ->where(function ($q) use ($genre) {
                        // Match genre field or genre_tags JSON field
                        $q->whereRaw('LOWER(genre) = ?', [strtolower($genre)])
                          ->orWhereRaw('LOWER(genre_tags) LIKE ?', ['%' . strtolower($genre) . '%']);
                    });
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

        // Revenue/trust metrics must come from completed orders and published reviews.
        $productIds = collect($products->items())->pluck('id')->all();
        $purchaseCounts = \DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->whereIn('order_items.product_id', $productIds)
            ->whereIn('orders.status', ['processing', 'completed'])
            ->selectRaw('order_items.product_id, COUNT(*) as total')
            ->groupBy('order_items.product_id')
            ->pluck('total', 'product_id');
        $reviewStats = SourceGameReview::published()
            ->whereIn('product_id', $productIds)
            ->selectRaw('product_id, COUNT(*) as review_count, AVG(rating) as rating')
            ->groupBy('product_id')
            ->get()
            ->keyBy('product_id');

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

            // Technical fields are derived from the real product content only.
            $engine = 'Unity';
            $language = 'C#';
            $fileSize = '25 MB';

            // Detect engine from name/description/short_description
            $nameLower = strtolower($name . ' ' . $shortDescription . ' ' . $description);
            if (str_contains($nameLower, 'godot')) { $engine = 'Godot'; $language = 'GDScript'; }
            elseif (str_contains($nameLower, 'phaser') || str_contains($nameLower, 'html5') || str_contains($nameLower, 'javascript') || str_contains($nameLower, 'js game') || str_contains($nameLower, 'canvas')) { $engine = 'HTML5/Phaser'; $language = 'JavaScript'; }
            elseif (str_contains($nameLower, 'unreal') || str_contains($nameLower, 'ue4') || str_contains($nameLower, 'ue5') || str_contains($nameLower, 'blueprint')) { $engine = 'Unreal'; $language = 'C++/Blueprint'; }
            elseif (str_contains($nameLower, 'construct') || str_contains($nameLower, 'construct 3') || str_contains($nameLower, 'construct 2')) { $engine = 'Construct'; $language = 'Event Sheet'; }
            elseif (str_contains($nameLower, 'cocos') || str_contains($nameLower, 'cocos2d')) { $engine = 'Cocos'; $language = 'TypeScript'; }
            elseif (str_contains($nameLower, 'rpg maker') || str_contains($nameLower, 'rpgmaker')) { $engine = 'RPG Maker'; $language = 'JavaScript'; }
            // Default: Unity (most common)

            $downloadsCount = (int) ($purchaseCounts[$product->id] ?? 0);
            $productReviewStats = $reviewStats->get($product->id);
            $reviewCount = (int) ($productReviewStats->review_count ?? 0);
            $rating = $reviewCount > 0 ? round((float) $productReviewStats->rating, 1) : 0.0;

            $verifiedAssets = config('source-game-revenue.verified_assets.'.$product->sku, []);
            $verifiedDemoPath = $verifiedAssets['demo_path'] ?? null;
            $hasVerifiedDemo = $verifiedDemoPath
                && file_exists(public_path(trim($verifiedDemoPath, '/').'/index.html'));
            $isVerifiedCatalog = $catalogService->isVerifiedSku($product->sku);
            $isAvailable = $catalogService->isAvailable($product->sku, $price, $product->downloadable_links);

            // Hot badges are earned only by products that can be delivered now.
            $isHot = $isAvailable && $downloadsCount >= 10;

            // Image
            $previewImage = asset('images/placeholder-game.svg');
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
                'review_count' => $reviewCount,
                'is_hot' => $isHot,
                'is_available' => $isAvailable,
                'is_verified_catalog' => $isVerifiedCatalog,
                'preview_image' => $previewImage,
                'size' => $fileSize,
                'price' => $price,
                'updated' => optional($product->updated_at)->format('Y-m-d'),
                'sku' => $product->sku,
                'downloadable_links' => $product->downloadable_links,
                'url_key' => $urlKey,
                'href' => $urlKey ? route('lamgame.source-game.detail', $urlKey) : null,
                'has_demo' => (bool) $product->has_demo || $hasVerifiedDemo,
                'demo_href' => $hasVerifiedDemo
                    ? url($verifiedDemoPath)
                    : ($product->has_demo && $urlKey ? route('source-game.demo', $urlKey) : null),
            ];
        }

        // Pagination meta for the view (optional)
        $pagination = [
            'current_page' => method_exists($products, 'currentPage') ? $products->currentPage() : 1,
            'last_page'    => method_exists($products, 'lastPage') ? $products->lastPage() : 1,
            'per_page'     => $perPage,
            'has_more'     => method_exists($products, 'hasMorePages') ? $products->hasMorePages() : false,
        ];

        // Never merchandise unavailable products. Trending requires real purchases;
        // curated sources require the audited catalog baseline.
        $allSorted = collect($featuredSources);
        $trendingSources = $allSorted
            ->where('is_available', true)
            ->filter(fn ($source) => ($source['downloads'] ?? 0) > 0)
            ->sortByDesc('downloads')
            ->take(4)
            ->values()
            ->all();
        $bestSellingSources = $allSorted
            ->where('is_available', true)
            ->where('is_verified_catalog', true)
            ->sortByDesc('updated')
            ->take(4)
            ->values()
            ->all();

        return view('lamgame.pages.source-game', [
            'featuredSources'    => $featuredSources,
            'trendingSources'    => $trendingSources,
            'bestSellingSources' => $bestSellingSources,
            'pagination'         => $pagination,
            'currentGenre'       => $genre ?? 'all',
            'currentSearch'      => $search ?? '',
            'currentSort'        => $sort ?? 'newest',
            'page_title'         => 'Mua Bán Source Game Unity, Unreal | Mã Nguồn Game Giá Rẻ — LamGame.vn',
            'page_description'   => 'Kho source game Unity, Unreal Engine, Godot và HTML5 với giá, demo, thông số kỹ thuật và điều khoản sử dụng minh bạch.',
        ]);
    }

    /**
     * Show Source Game Detail page
     */
    public function sourceGameDetail($slug)
    {
        $catalogService = app(\App\Services\SourceGameCatalogService::class);

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

        // Unknown products must return a real 404 instead of fabricated sample content.
        abort_unless($product, 404);

        // Get product flat data
        $flat = \DB::table('product_flat')
            ->where('product_id', $product->id)
            ->where('locale', 'vi')
            ->first();

        // Get attribute values (resolve select/multiselect labels)
        $attributeValues = [];
        if ($product->attribute_values) {
            foreach ($product->attribute_values as $attrValue) {
                $attr = $attrValue->attribute;
                $rawValue = $attrValue->text_value ?: $attrValue->integer_value ?: $attrValue->value;

                if ($attr && in_array($attr->type, ['select', 'multiselect']) && $rawValue) {
                    // Resolve option IDs to labels
                    $optionIds = array_filter(explode(',', (string) $rawValue));
                    $labels = \DB::table('attribute_option_translations')
                        ->whereIn('attribute_option_id', $optionIds)
                        ->where('locale', 'vi')
                        ->pluck('label')
                        ->implode(', ');
                    $attributeValues[$attr->code] = $labels ?: $rawValue;
                } else {
                    $attributeValues[$attr->code ?? ''] = $rawValue;
                }
            }
        }

        // Get seller info
        $seller = \App\Models\SourceGameSeller::find($product->seller_id);

        // Only completed/processing orders count as purchases.
        $purchaseCount = \DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->where('order_items.product_id', $product->id)
            ->whereIn('orders.status', ['processing', 'completed'])
            ->count();

        $reviewStats = SourceGameReview::byProduct($product->id)
            ->published()
            ->selectRaw('COUNT(*) as review_count, AVG(rating) as rating')
            ->first();
        $reviewCount = (int) ($reviewStats->review_count ?? 0);
        $rating = $reviewCount > 0 ? round((float) $reviewStats->rating, 1) : 0.0;

        $verifiedAssets = config('source-game-revenue.verified_assets.'.$product->sku, []);
        $verifiedDemoPath = $verifiedAssets['demo_path'] ?? null;
        $hasVerifiedDemo = $verifiedDemoPath
            && file_exists(public_path(ltrim($verifiedDemoPath, '/')));

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
            'rating' => $rating,
            'version' => $attributeValues['version'] ?? '1.0',
            'last_updated' => optional($product->updated_at)->format('Y-m-d'),
            'created_at' => optional($product->created_at)->format('Y-m-d'),
            'images' => [],
            'downloadable_links' => [],
            'video_demo_url' => $attributeValues['video_demo_url'] ?? null,
            'demo_url' => $hasVerifiedDemo
                ? url($verifiedDemoPath)
                : (($product->has_demo && $flat->url_key) ? route('source-game.demo', $flat->url_key) : ($attributeValues['demo_url'] ?? null)),
            'author_name' => $seller->shop_name ?? ($attributeValues['author_name'] ?? 'Làm Game Team'),
            'author_slug' => $seller->shop_slug ?? null,
            'author_logo' => $seller ? $seller->logo_url : null,
            'author_verified' => $seller->verified ?? false,
            'author_email' => $seller->contact_email ?? ($attributeValues['author_email'] ?? null),
            'author_bio' => $seller->shop_description ?? ($attributeValues['author_bio'] ?? ''),
            'requirements' => $attributeValues['requirements'] ?? null,
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

        foreach ($verifiedAssets['screenshots'] ?? [] as $index => $screenshotPath) {
            if (file_exists(public_path(ltrim($screenshotPath, '/')))) {
                $sourceGameDetail['images'][] = [
                    'url' => asset(ltrim($screenshotPath, '/')),
                    'alt' => $sourceGameDetail['title'].' — gameplay '.($index + 1),
                ];
            }
        }

        // Fallback: 1 placeholder if no valid images
        if (empty($sourceGameDetail['images'])) {
            $sourceGameDetail['images'] = [
                ['url' => asset('images/placeholder-game.svg'), 'alt' => $sourceGameDetail['title']]
            ];
        }
        // Set primary image for OG/schema
        $sourceGameDetail['image'] = $sourceGameDetail['images'][0]['url'] ?? asset('assets/logos/png/logo-square-512.png');

        // Expose only links that can actually be delivered to the buyer.
        $privateDisk = \Storage::disk('private');
        $hasDocumentation = false;
        $hasLicenseFile = false;

        if ($product->downloadable_links && $product->downloadable_links->isNotEmpty()) {
            foreach ($product->downloadable_links as $link) {
                $isAvailable = $link->type === 'url'
                    ? filter_var($link->url, FILTER_VALIDATE_URL) !== false
                    : (! empty($link->file) && $privateDisk->exists($link->file));

                if (! $isAvailable) {
                    continue;
                }

                $sourceGameDetail['downloadable_links'][] = [
                    'id' => $link->id,
                    'title' => $link->title,
                    'file_name' => $link->file_name,
                    'downloads' => $link->downloads ?? 0,
                    'type' => $link->type,
                    'url' => $link->url,
                ];

                $linkName = strtolower(($link->title ?? '').' '.($link->file_name ?? ''));
                $hasDocumentation = $hasDocumentation || preg_match('/doc|guide|hướng dẫn|readme/i', $linkName) === 1;
                $hasLicenseFile = $hasLicenseFile || str_contains($linkName, 'license');

                if ($link->type === 'file' && strtolower(pathinfo($link->file, PATHINFO_EXTENSION)) === 'zip') {
                    $zip = new \ZipArchive();
                    if ($zip->open($privateDisk->path($link->file)) === true) {
                        for ($index = 0; $index < $zip->numFiles; $index++) {
                            $entry = strtolower(basename((string) $zip->getNameIndex($index)));
                            $hasDocumentation = $hasDocumentation || preg_match('/^readme(?:\.[a-z0-9]+)?$|guide|hướng dẫn/', $entry) === 1;
                            $hasLicenseFile = $hasLicenseFile || preg_match('/^licen[cs]e(?:\.[a-z0-9]+)?$/', $entry) === 1;
                        }
                        $zip->close();
                    }
                }
            }
        }

        $sourceGameDetail['has_downloadable_file'] = ! empty($sourceGameDetail['downloadable_links']);
        $sourceGameDetail['has_license_file'] = $hasLicenseFile;
        $sourceGameDetail['is_revenue_featured'] = $sourceGameDetail['has_downloadable_file']
            && $catalogService->isVerifiedSku($product->sku);
        $sourceGameDetail['is_available'] = $catalogService->isAvailable(
            $product->sku,
            (float) $sourceGameDetail['price'],
            $product->downloadable_links
        );
        $sourceGameDetail['buyer_benefits'] = array_values(array_filter([
            !empty($sourceGameDetail['downloadable_links']) ? 'File tải được bảo vệ trong tài khoản người mua' : null,
            !empty($sourceGameDetail['demo_url']) ? 'Có demo để kiểm tra trước khi mua' : null,
            $hasDocumentation ? 'Có tài liệu hoặc hướng dẫn đi kèm trong gói tải' : null,
            $sourceGameDetail['last_updated'] ? 'Ngày cập nhật được công khai' : null,
            'Hỗ trợ qua diễn đàn và email của LamGame',
            'Chính sách hoàn tiền và điều khoản Marketplace được công khai',
        ]));
        $sourceGameDetail['has_documentation'] = $hasDocumentation;

        // Parse features — prefer dedicated attribute, fallback to description bullets only.
        $featuresSource = $attributeValues['features'] ?? $sourceGameDetail['description'] ?? '';
        if ($featuresSource) {
            $lines = explode("\n", strip_tags($featuresSource));
            $lines = array_filter(array_map('trim', $lines));
            // ONLY keep lines with explicit bullet markers (✅, ✓, -, •)
            $sourceGameDetail['features'] = array_values(array_filter($lines, function ($line) {
                return preg_match('/^[✅✓•\-\*]/', $line);
            }));
            $sourceGameDetail['features'] = array_map(function ($f) {
                return preg_replace('/^[✅✓•\-\*]\s*/', '', $f);
            }, $sourceGameDetail['features']);
            $sourceGameDetail['features'] = array_slice($sourceGameDetail['features'], 0, 8);
        }

        // FAQ answers only make claims supported by the current product data.
        $sourceGameDetail['faq'] = [
            ['q' => 'Source code này dùng engine gì?', 'a' => "Thông tin hiện tại: {$sourceGameDetail['engine']} và ngôn ngữ {$sourceGameDetail['language']}."],
            ['q' => 'Tôi có thể dùng cho dự án thương mại không?', 'a' => $sourceGameDetail['is_free']
                ? 'Vui lòng kiểm tra license được cung cấp cùng sản phẩm trước khi phân phối hoặc sử dụng thương mại.'
                : 'Quyền sử dụng phụ thuộc loại license hiển thị tại thời điểm mua và Điều khoản Marketplace.'],
            ['q' => 'Sản phẩm có tài liệu hướng dẫn không?', 'a' => $hasDocumentation
                ? 'Có tài liệu hoặc hướng dẫn được liệt kê trong gói tải.'
                : 'Chưa có tài liệu riêng được liệt kê. Vui lòng liên hệ trước khi mua nếu bạn cần hướng dẫn cài đặt.'],
            ['q' => 'Source có hỗ trợ nền tảng của tôi không?', 'a' => 'Hãy đối chiếu engine, phiên bản, yêu cầu kỹ thuật và demo trên trang. Liên hệ LamGame trước khi mua nếu nền tảng của bạn chưa được nêu rõ.'],
            ['q' => 'Tôi nhận hỗ trợ bằng cách nào?', 'a' => 'Bạn có thể gửi câu hỏi qua Forum LamGame hoặc email hỗ trợ được công bố trên website.'],
        ];

        // Add github_url if available
        $sourceGameDetail['github_url'] = '';
        if (str_contains($sourceGameDetail['full_description'] ?? '', 'github.com')) {
            preg_match('/https?:\/\/github\.com\/[^\s<"]+/', $sourceGameDetail['full_description'] ?? '', $ghMatch);
            $sourceGameDetail['github_url'] = $ghMatch[0] ?? '';
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
            $relatedProducts = \Webkul\Product\Models\Product::with(['categories', 'images', 'downloadable_links'])
                ->where('type', 'downloadable')
                ->where('id', '!=', $product->id)
                ->whereIn('id', $catalogService->publishedProductIds())
                ->whereHas('categories', function ($query) use ($categoryId) {
                    $query->where('category_id', $categoryId);
                })
                ->take(12)
                ->get();

            foreach ($relatedProducts as $relatedProduct) {
                $relatedFlat = \DB::table('product_flat')
                    ->where('product_id', $relatedProduct->id)
                    ->where('locale', 'vi')
                    ->first();

                $relatedPrice = (float) ($relatedFlat->price ?? 0);
                if (! $relatedFlat || ! $catalogService->isAvailable(
                    $relatedProduct->sku,
                    $relatedPrice,
                    $relatedProduct->downloadable_links
                )) {
                    continue;
                }

                $relatedSources[] = [
                    'title' => $relatedFlat->name ?? $relatedProduct->sku,
                    'url' => route('lamgame.source-game.detail', $relatedFlat->url_key ?? $relatedProduct->id),
                    'image' => $relatedProduct->images && $relatedProduct->images->isNotEmpty()
                             ? asset('storage/' . $relatedProduct->images->first()->path)
                             : asset('images/placeholder-game.svg'),
                    'price' => $relatedPrice,
                ];

                if (count($relatedSources) >= 3) {
                    break;
                }
            }
        }

        return view('lamgame.pages.source-game-detail', [
            'sourceGame' => $sourceGameDetail,
            'relatedSources' => $relatedSources,
            'page_title' => $sourceGameDetail['title'] . ' - Source Game - Làm Game',
            'page_description' => $sourceGameDetail['description'] ?: (
                'Xem thông tin, demo, giá và trạng thái gói tải source code '.$sourceGameDetail['title'].' tại LamGame.vn.'
            )
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

    /**
     * Show English Hire page for international clients
     */
    public function hire()
    {
        return view('lamgame.pages.hire');
    }

    /**
     * Show Portfolio page
     */
    public function portfolio()
    {
        $projects = \DB::table('products as p')
            ->join('product_flat as pf', function ($join) {
                $join->on('pf.product_id', '=', 'p.id')->where('pf.locale', 'vi');
            })
            ->join('product_images as pi', 'pi.product_id', '=', 'p.id')
            ->where('p.type', 'downloadable')
            ->where('pf.status', 1)
            ->select(
                'p.id', 'p.has_demo', 'pf.name', 'pf.url_key', 'pf.short_description',
                'pf.engine', 'pf.platform', \DB::raw('MIN(pi.path) as image_path')
            )
            ->groupBy('p.id', 'p.has_demo', 'p.updated_at', 'pf.name', 'pf.url_key', 'pf.short_description', 'pf.engine', 'pf.platform')
            ->orderByDesc('p.has_demo')
            ->orderByDesc('p.updated_at')
            ->limit(8)
            ->get()
            ->map(fn ($project) => [
                'id' => $project->id,
                'name' => $project->name,
                'description' => strip_tags($project->short_description ?? ''),
                'engine' => $project->engine ?: 'Game Development',
                'platform' => $project->platform,
                'image' => asset('storage/' . $project->image_path),
                'url' => route('lamgame.source-game.detail', $project->url_key),
                'demo_url' => $project->has_demo ? route('source-game.demo', $project->url_key) : null,
            ]);

        return view('lamgame.pages.portfolio', ['portfolioProjects' => $projects]);
    }

    /**
     * Show Team page
     */
    public function team()
    {
        return view('lamgame.pages.team');
    }
}
