# Hướng Dẫn Sử Dụng SEO Tools

## 📋 Mục lục

1. [Sitemap Generator](#1-sitemap-generator)
2. [Google Indexing API](#2-google-indexing-api)
3. [Structured Data Helper](#3-structured-data-helper)
4. [Scheduled Tasks](#4-scheduled-tasks)
5. [Monitoring & Maintenance](#5-monitoring--maintenance)

---

## 1. Sitemap Generator

### Chức năng
Tự động tạo file `sitemap.xml` chứa tất cả URLs quan trọng của website.

### Sử dụng

#### Tạo sitemap thủ công:
```bash
php artisan sitemap:generate
```

#### Output:
```
🚀 Generating sitemap...
📋 Adding job posts...
✅ Added 45 job posts
📝 Adding blog posts...
✅ Added 23 blog posts
✅ Sitemap generated successfully!
📍 Location: /path/to/public/sitemap.xml
🔗 URL: https://lamgame.vn/sitemap.xml
📊 Total URLs: 72
```

### Cấu trúc Sitemap

```xml
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
  <url>
    <loc>https://lamgame.vn/</loc>
    <lastmod>2025-12-16</lastmod>
    <changefreq>daily</changefreq>
    <priority>1.0</priority>
  </url>
  <url>
    <loc>https://lamgame.vn/viec-lam-game</loc>
    <lastmod>2025-12-16</lastmod>
    <changefreq>hourly</changefreq>
    <priority>0.9</priority>
  </url>
  <!-- ... more URLs ... -->
</urlset>
```

### Priority Levels

| Page Type | Priority | Change Frequency |
|-----------|----------|------------------|
| Homepage | 1.0 | Daily |
| Jobs Listing | 0.9 | Hourly |
| Blog Listing | 0.9 | Daily |
| Job Detail | 0.8 | Weekly |
| Blog Post | 0.7 | Weekly |
| Forum | 0.8 | Hourly |
| Static Pages | 0.7 | Monthly |

### Tự động hóa

Sitemap sẽ tự động regenerate mỗi ngày lúc 2:00 AM (xem [Scheduled Tasks](#4-scheduled-tasks))

---

## 2. Google Indexing API

### Chức năng
Push URLs mới/cập nhật lên Google để index nhanh hơn (thay vì chờ Google crawl).

### Setup

#### Bước 1: Tạo Google Service Account

1. Truy cập [Google Cloud Console](https://console.cloud.google.com/)
2. Tạo project mới hoặc chọn project có sẵn
3. Enable **Indexing API**:
   - APIs & Services → Library
   - Tìm "Indexing API" → Enable

4. Tạo Service Account:
   - APIs & Services → Credentials
   - Create Credentials → Service Account
   - Tải file JSON key

5. Cấp quyền trong Google Search Console:
   - Vào [Search Console](https://search.google.com/search-console)
   - Settings → Users and permissions
   - Add user: `service-account-email@project.iam.gserviceaccount.com`
   - Role: Owner

#### Bước 2: Cài đặt Service Account File

```bash
# Copy file JSON vào storage
cp google-service-account.json storage/app/google-service-account.json

# Set permissions
chmod 600 storage/app/google-service-account.json
```

### Sử dụng

#### Push tất cả (jobs + blogs):
```bash
php artisan google:push-index --type=all --limit=10
```

#### Push chỉ jobs:
```bash
php artisan google:push-index --type=jobs --limit=20
```

#### Push chỉ blogs:
```bash
php artisan google:push-index --type=blogs --limit=15
```

### Output Example:
```
🚀 Starting Google Indexing API push...
✅ Access token obtained
📋 Pushing job posts...
✅ https://lamgame.vn/viec-lam/unity-developer-senior
✅ https://lamgame.vn/viec-lam/game-designer-mobile
❌ https://lamgame.vn/viec-lam/invalid-url
📊 Jobs: 19 success, 1 failed
📝 Pushing blog posts...
✅ https://lamgame.vn/blog/huong-dan-unity-co-ban
✅ https://lamgame.vn/blog/game-design-patterns
📊 Blogs: 10 success, 0 failed
✅ Push completed!
```

### Rate Limits

- **Google Indexing API**: 200 requests/minute
- **Tool tự động delay**: 0.3 giây/request (safe)
- **Daily quota**: 200 URLs/day (free tier)

### Lưu ý

⚠️ **Chỉ push URLs quan trọng:**
- Jobs mới đăng
- Blogs mới publish
- Pages có update lớn

❌ **Không nên push:**
- Pagination pages
- Filter/search results
- Duplicate content

---

## 3. Structured Data Helper

### Chức năng
Tạo JSON-LD structured data (Schema.org) cho SEO rich snippets.

### Sử dụng trong Controller

#### Job Detail Page:
```php
use App\Helpers\StructuredDataHelper;

public function jobDetail($slug)
{
    $job = $this->getJobBySlug($slug);
    
    // Generate all schemas
    $schemas = StructuredDataHelper::generateAll('job', $job);
    
    return view('lamgame.job-detail', [
        'job' => $job,
        'schemas' => $schemas
    ]);
}
```

#### Blog Post Page:
```php
public function blogShow($slug)
{
    $blog = Blog::where('slug', $slug)->firstOrFail();
    
    $schemas = StructuredDataHelper::generateAll('blog', $blog);
    
    return view('lamgame.blog-show', [
        'blog' => $blog,
        'schemas' => $schemas
    ]);
}
```

### Sử dụng trong Blade Template

```blade
@if(isset($schemas))
    @foreach($schemas as $schema)
        <script type="application/ld+json">
            {!! $schema !!}
        </script>
    @endforeach
@endif
```

### Schema Types

#### 1. JobPosting Schema
```json
{
  "@context": "https://schema.org",
  "@type": "JobPosting",
  "title": "Unity Developer Senior",
  "description": "...",
  "datePosted": "2025-12-16",
  "employmentType": "FULL_TIME",
  "hiringOrganization": {...},
  "jobLocation": {...},
  "baseSalary": {...}
}
```

**Rich Snippet Result:**
- Job title với icon
- Company name
- Location
- Salary range
- Posted date
- "Apply" button

#### 2. Article Schema
```json
{
  "@context": "https://schema.org",
  "@type": "Article",
  "headline": "Hướng dẫn Unity cơ bản",
  "image": "...",
  "datePublished": "2025-12-16",
  "author": {...},
  "publisher": {...}
}
```

**Rich Snippet Result:**
- Article title
- Featured image
- Published date
- Author
- Reading time estimate

#### 3. BreadcrumbList Schema
```json
{
  "@context": "https://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement": [
    {"@type": "ListItem", "position": 1, "name": "Home", "item": "..."},
    {"@type": "ListItem", "position": 2, "name": "Jobs", "item": "..."}
  ]
}
```

**Rich Snippet Result:**
- Breadcrumb navigation in search results

#### 4. Organization Schema
```json
{
  "@context": "https://schema.org",
  "@type": "Organization",
  "name": "Làm Game",
  "url": "https://lamgame.vn",
  "logo": "...",
  "sameAs": ["facebook", "youtube"]
}
```

**Rich Snippet Result:**
- Knowledge panel
- Social links
- Logo

#### 5. WebSite Schema with SearchAction
```json
{
  "@context": "https://schema.org",
  "@type": "WebSite",
  "name": "Làm Game",
  "potentialAction": {
    "@type": "SearchAction",
    "target": "https://lamgame.vn/viec-lam-game?keyword={search_term_string}"
  }
}
```

**Rich Snippet Result:**
- Sitelinks search box in Google

### Testing Structured Data

#### Google Rich Results Test:
```
https://search.google.com/test/rich-results
```

#### Schema.org Validator:
```
https://validator.schema.org/
```

---

## 4. Scheduled Tasks

### Cron Setup

#### Thêm vào crontab:
```bash
crontab -e
```

```cron
# Laravel Scheduler
* * * * * cd /path/to/lamgame.vn && php artisan schedule:run >> /dev/null 2>&1
```

### Scheduled Commands

File: `app/Console/Kernel.php`

```php
protected function schedule(Schedule $schedule): void
{
    // Generate sitemap daily at 2 AM
    $schedule->command('sitemap:generate')
        ->dailyAt('02:00')
        ->appendOutputTo(storage_path('logs/sitemap.log'));

    // Push to Google Index every 6 hours
    $schedule->command('google:push-index --type=all --limit=20')
        ->everySixHours()
        ->appendOutputTo(storage_path('logs/google-index.log'));
}
```

### Kiểm tra Schedule

```bash
# List all scheduled tasks
php artisan schedule:list

# Run scheduler manually (for testing)
php artisan schedule:run
```

### Logs

```bash
# View sitemap generation log
tail -f storage/logs/sitemap.log

# View Google indexing log
tail -f storage/logs/google-index.log
```

---

## 5. Monitoring & Maintenance

### Google Search Console

#### Submit Sitemap:
1. Vào [Search Console](https://search.google.com/search-console)
2. Sitemaps → Add new sitemap
3. Nhập: `https://lamgame.vn/sitemap.xml`
4. Submit

#### Monitor Coverage:
- Check "Coverage" report
- Fix any errors
- Monitor indexed pages

### Performance Metrics

#### Core Web Vitals:
- LCP (Largest Contentful Paint): < 2.5s
- FID (First Input Delay): < 100ms
- CLS (Cumulative Layout Shift): < 0.1

#### Tools:
- [PageSpeed Insights](https://pagespeed.web.dev/)
- [GTmetrix](https://gtmetrix.com/)
- [WebPageTest](https://www.webpagetest.org/)

### SEO Checklist

#### Weekly:
- [ ] Check sitemap generation logs
- [ ] Monitor Google Index push status
- [ ] Review Search Console errors
- [ ] Check page load times

#### Monthly:
- [ ] Audit structured data
- [ ] Review keyword rankings
- [ ] Analyze traffic trends
- [ ] Update meta descriptions

#### Quarterly:
- [ ] Full SEO audit
- [ ] Competitor analysis
- [ ] Content gap analysis
- [ ] Technical SEO review

---

## 6. Troubleshooting

### Sitemap không generate

```bash
# Check permissions
ls -la public/sitemap.xml

# Check logs
tail -f storage/logs/sitemap.log

# Regenerate manually
php artisan sitemap:generate
```

### Google Indexing API lỗi

```bash
# Check service account file
ls -la storage/app/google-service-account.json

# Test authentication
php artisan google:push-index --type=jobs --limit=1

# Common errors:
# - 401: Invalid credentials
# - 403: No permission in Search Console
# - 429: Rate limit exceeded
```

### Structured Data không hiển thị

1. Test với [Rich Results Test](https://search.google.com/test/rich-results)
2. Check JSON syntax
3. Verify required fields
4. Wait 1-2 weeks for Google to process

---

## 7. Best Practices

### DO ✅

- Generate sitemap after major content updates
- Push only new/updated URLs to Google
- Test structured data before deploy
- Monitor Search Console regularly
- Keep schemas up-to-date

### DON'T ❌

- Don't push all URLs at once (rate limits)
- Don't include noindex pages in sitemap
- Don't use invalid schema properties
- Don't ignore Search Console errors
- Don't forget to update sitemap after migrations

---

## 8. Resources

### Documentation:
- [Google Search Central](https://developers.google.com/search)
- [Schema.org](https://schema.org/)
- [Spatie Sitemap](https://github.com/spatie/laravel-sitemap)

### Tools:
- [Google Search Console](https://search.google.com/search-console)
- [Google Rich Results Test](https://search.google.com/test/rich-results)
- [Schema Markup Validator](https://validator.schema.org/)

---

**Cập nhật lần cuối:** 16/12/2025
