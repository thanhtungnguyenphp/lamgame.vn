# SEO Deployment Checklist - LAMGAME.VN

## ✅ Checklist Triển Khai

### Phase 1: Immediate (Hôm nay - 1 giờ)

#### 1.1. Generate Sitemap
- [ ] Chạy: `php artisan sitemap:generate`
- [ ] Verify file: `ls -lh public/sitemap.xml`
- [ ] Check URL count: `grep -c '<url>' public/sitemap.xml`
- [ ] Test URL: `curl https://lamgame.vn/sitemap.xml`

#### 1.2. Submit to Google Search Console
- [ ] Login: https://search.google.com/search-console
- [ ] Verify domain ownership
- [ ] Sitemaps → Add new sitemap
- [ ] Enter: `https://lamgame.vn/sitemap.xml`
- [ ] Click Submit

#### 1.3. Setup Cron Job
```bash
# Edit crontab
crontab -e

# Add this line:
* * * * * cd /path/to/lamgame.vn && php artisan schedule:run >> /dev/null 2>&1
```

- [ ] Add cron job
- [ ] Test: `php artisan schedule:list`
- [ ] Verify scheduled tasks appear

---

### Phase 2: Google Indexing API (1-2 ngày)

#### 2.1. Create Google Service Account
- [ ] Go to: https://console.cloud.google.com/
- [ ] Create new project: "lamgame-indexing"
- [ ] Enable "Indexing API"
- [ ] Create Service Account
- [ ] Download JSON key file

#### 2.2. Configure Permissions
- [ ] Go to: https://search.google.com/search-console
- [ ] Settings → Users and permissions
- [ ] Add user: `service-account@project.iam.gserviceaccount.com`
- [ ] Set role: Owner

#### 2.3. Install Service Account
```bash
# Copy file
cp ~/Downloads/service-account.json storage/app/google-service-account.json

# Set permissions
chmod 600 storage/app/google-service-account.json
```

- [ ] Copy service account file
- [ ] Set correct permissions
- [ ] Test: `php artisan google:push-index --type=jobs --limit=1`

---

### Phase 3: Structured Data (3-5 ngày)

#### 3.1. Update Job Detail Controller
File: `app/Http/Controllers/LamGamePageController.php`

```php
use App\Helpers\StructuredDataHelper;

public function jobDetail($slug)
{
    // ... existing code ...
    
    // Add schemas
    $schemas = StructuredDataHelper::generateAll('job', $job);
    
    return view('lamgame.job-detail', [
        'job' => $job,
        'schemas' => $schemas, // Add this
        // ... other data ...
    ]);
}
```

- [ ] Add use statement
- [ ] Generate schemas
- [ ] Pass to view

#### 3.2. Update Job Detail View
File: `resources/views/lamgame/job-detail.blade.php`

```blade
@extends('layouts.app')

@section('head')
    {{-- Add structured data --}}
    @if(isset($schemas))
        @foreach($schemas as $schema)
            <script type="application/ld+json">
                {!! $schema !!}
            </script>
        @endforeach
    @endif
@endsection

@section('content')
    {{-- Existing content --}}
@endsection
```

- [ ] Add @section('head')
- [ ] Loop through schemas
- [ ] Add JSON-LD scripts

#### 3.3. Update Blog Controller
File: `app/Http/Controllers/LamGamePageController.php`

```php
public function blogShow($slug)
{
    // ... existing code ...
    
    $schemas = StructuredDataHelper::generateAll('blog', $blog);
    
    return view('lamgame.blog-show', [
        'blog' => $blog,
        'schemas' => $schemas,
        // ... other data ...
    ]);
}
```

- [ ] Add schemas to blog controller
- [ ] Pass to view

#### 3.4. Update Blog View
File: `resources/views/lamgame/blog-show.blade.php`

- [ ] Add structured data section
- [ ] Same pattern as job detail

#### 3.5. Test Structured Data
- [ ] Test job page: https://search.google.com/test/rich-results
- [ ] Test blog page: https://search.google.com/test/rich-results
- [ ] Validate: https://validator.schema.org/
- [ ] Fix any errors

---

### Phase 4: Meta Tags Optimization (1 tuần)

#### 4.1. Add Open Graph Tags
File: `resources/views/layouts/app.blade.php`

```blade
<head>
    {{-- Existing meta tags --}}
    
    {{-- Open Graph --}}
    <meta property="og:title" content="{{ $page_title ?? 'Làm Game' }}">
    <meta property="og:description" content="{{ $page_description ?? '' }}">
    <meta property="og:image" content="{{ $og_image ?? asset('logo/lamgame-logo.png') }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="{{ $og_type ?? 'website' }}">
    
    {{-- Twitter Card --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $page_title ?? 'Làm Game' }}">
    <meta name="twitter:description" content="{{ $page_description ?? '' }}">
    <meta name="twitter:image" content="{{ $og_image ?? asset('logo/lamgame-logo.png') }}">
    
    {{-- Canonical --}}
    <link rel="canonical" href="{{ url()->current() }}">
</head>
```

- [ ] Add Open Graph tags
- [ ] Add Twitter Card tags
- [ ] Add canonical URL
- [ ] Test with Facebook Debugger
- [ ] Test with Twitter Card Validator

#### 4.2. Optimize Title & Description
- [ ] Job listing: "Việc làm Game | 100+ cơ hội tại Việt Nam"
- [ ] Job detail: "{Job Title} - {Company} | Làm Game"
- [ ] Blog listing: "Blog Game Dev | Tin tức & Hướng dẫn"
- [ ] Blog post: "{Title} | Blog Làm Game"

---

### Phase 5: Performance Optimization (1-2 tuần)

#### 5.1. Enable Redis Cache
File: `.env`

```env
CACHE_STORE=redis
REDIS_HOST=shared-redis
REDIS_PORT=6379
```

- [ ] Update .env
- [ ] Clear config: `php artisan config:clear`
- [ ] Test cache: `php artisan cache:clear`

#### 5.2. Cache Jobs Listing
File: `app/Http/Controllers/LamGamePageController.php`

```php
use Illuminate\Support\Facades\Cache;

public function jobs(Request $request)
{
    $cacheKey = 'jobs_listing_' . md5(json_encode($request->all()));
    
    $jobs = Cache::remember($cacheKey, 300, function() use ($request) {
        // Existing query logic
        return $jobsQuery->paginate($perPage);
    });
    
    // ... rest of code ...
}
```

- [ ] Add cache to jobs listing
- [ ] TTL: 5 minutes
- [ ] Cache key includes filters

#### 5.3. Cache Blog Listing
- [ ] Similar pattern for blogs
- [ ] TTL: 1 hour

#### 5.4. Optimize Images
- [ ] Add lazy loading: `loading="lazy"`
- [ ] Use WebP format
- [ ] Compress images
- [ ] Add width/height attributes

---

### Phase 6: Monitoring & Analytics (Ongoing)

#### 6.1. Google Search Console
- [ ] Monitor Coverage report
- [ ] Check for errors
- [ ] Review Performance report
- [ ] Track indexed pages

#### 6.2. Google Analytics
- [ ] Setup GA4
- [ ] Track page views
- [ ] Track events (job apply, blog read)
- [ ] Setup conversion goals

#### 6.3. Core Web Vitals
- [ ] Monitor LCP (< 2.5s)
- [ ] Monitor FID (< 100ms)
- [ ] Monitor CLS (< 0.1)
- [ ] Use PageSpeed Insights

#### 6.4. Weekly Checks
- [ ] Check sitemap generation log
- [ ] Check Google index push log
- [ ] Review GSC errors
- [ ] Monitor page load times

---

## 📊 Success Metrics

### Week 1:
- [ ] Sitemap submitted
- [ ] 0 errors in GSC
- [ ] Cron job running

### Week 2:
- [ ] Google Indexing API working
- [ ] 50+ URLs pushed
- [ ] Structured data validated

### Week 4:
- [ ] 80%+ pages indexed
- [ ] Rich snippets appearing
- [ ] CTR improved by 20%

### Month 3:
- [ ] 95%+ pages indexed
- [ ] Top 10 for target keywords
- [ ] Organic traffic +50%

---

## 🐛 Common Issues

### Sitemap not generating
```bash
# Check permissions
chmod 755 public/
chmod 644 public/sitemap.xml

# Check database
php artisan tinker
>>> DB::table('products')->where('type', 'job')->count();
```

### Google Indexing API errors
```bash
# 401 Unauthorized
# → Check service account file
# → Verify credentials

# 403 Forbidden
# → Add service account to GSC
# → Set role to Owner

# 429 Rate Limit
# → Reduce --limit parameter
# → Add delay between requests
```

### Structured data not validating
```bash
# Test individual schemas
php artisan tinker
>>> use App\Helpers\StructuredDataHelper;
>>> $job = DB::table('products')->first();
>>> echo StructuredDataHelper::jobPosting($job);
```

---

## 📞 Support Resources

### Documentation:
- [SEO Analysis Report](./SEO_ANALYSIS_REPORT.md)
- [SEO Tools Guide](./SEO_TOOLS_GUIDE.md)
- [Implementation Summary](./SEO_IMPLEMENTATION_SUMMARY.md)

### External:
- [Google Search Central](https://developers.google.com/search)
- [Schema.org](https://schema.org/)
- [Spatie Sitemap Docs](https://github.com/spatie/laravel-sitemap)

### Tools:
- [Rich Results Test](https://search.google.com/test/rich-results)
- [PageSpeed Insights](https://pagespeed.web.dev/)
- [Schema Validator](https://validator.schema.org/)

---

## ✅ Final Checklist

Before going live:
- [ ] Sitemap generated and submitted
- [ ] Cron job configured
- [ ] Structured data added to all pages
- [ ] Meta tags optimized
- [ ] Open Graph tags added
- [ ] Canonical URLs set
- [ ] Google Indexing API configured
- [ ] Cache enabled
- [ ] Images optimized
- [ ] GSC monitoring setup
- [ ] Analytics configured
- [ ] All tests passing

---

**Last Updated:** 16/12/2025  
**Version:** 1.0
