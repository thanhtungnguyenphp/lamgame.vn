# LamGame.vn Phase 1 — Implementation Roadmap
## Làm sạch định vị, E-E-A-T và Brand Positioning

**Version:** 1.0  
**Start Date:** 14/08/2026  
**Target Completion:** 11/09/2026 (4 Sprints)

---

## Timeline Overview

```
Week 1-2 (Sprint 1): Cleanup & Backup
    ↓
Week 3 (Sprint 2): Brand & Trust
    ↓
Week 4 (Sprint 3): SEO Architecture
    ↓
Week 5+ (Sprint 4): Topical Authority
```

---

## Sprint 1: Cleanup (14/08 - 27/08)

### Goal
Loại bỏ brand/topic conflict mà không làm mất traffic hiện có.

### Tasks

#### [marketing][seo] — URL Audit
- [ ] Export toàn bộ URLs từ sitemap
- [ ] Export GSC data (clicks, impressions, position) 3 tháng
- [ ] Export GSC data 12 tháng cho URLs có traffic
- [ ] Cross-reference với GA4 revenue data (nếu có)
- [ ] Check backlinks cho top URLs (Ahrefs/Semrush)

**Deliverable:** `docs/seo/URL_AUDIT_SPREADSHEET.csv`

```csv
URL,Title,Category,Topic,Clicks_3M,Clicks_12M,Impressions,Avg_Position,Backlinks,Action,Redirect_URL,Priority,Note
```

#### [marketing][seo] — URL Classification
- [ ] Classify mỗi URL vào KEEP/REWRITE/MIGRATE/REMOVE
- [ ] Tạo redirect map cho URLs cần migrate
- [ ] Review với team trước khi execute

**Classification Rules:**

| Condition | Action |
|-----------|--------|
| Game Dev content + traffic | KEEP |
| Game Dev content + no traffic | KEEP (improve) |
| Non-Game Dev + high traffic + backlinks | MIGRATE |
| Non-Game Dev + low traffic | REMOVE (410) |
| Betting/Xổ số any | MIGRATE or REMOVE |

#### [back_end][lamgame] — Backup
- [ ] Backup database tables: `lottery_*`, `m7_*`, `sport_*`
- [ ] Archive lottery/sport code to separate branch
- [ ] Document current sitemap generation logic

```bash
# Database backup
mysqldump lamgame lottery_draws lottery_provinces lottery_results \
  lottery_schedules lottery_scrape_logs user_tickets \
  m7_matches m7_predictions > backup_lottery_sport_20260814.sql

# Git archive
git checkout -b archive/lottery-sport-features
git add app/Services/Lottery app/Models/Lottery* app/Models/M7*
git commit -m "Archive lottery and sport features before Phase 1 cleanup"
```

#### [front_end][lamgame] — Navigation Cleanup
- [ ] Remove Xổ số từ footer
- [ ] Remove Thể thao từ footer  
- [ ] Remove World Cup 2026 từ footer (or rewrite link)
- [ ] Verify no other nav elements link to these sections

**File:** `resources/views/partials/footer-redesign.blade.php`

```blade
{{-- BEFORE --}}
<li><a href="{{ route('sport.index') }}">Thể thao</a></li>
<li><a href="{{ route('lottery.index') }}">Xổ số</a></li>
<li><a href="{{ route('world-cup-2026') }}">World Cup 2026</a></li>

{{-- AFTER --}}
{{-- Removed: Sport, Lottery, World Cup generic --}}
<li><a href="{{ route('lamgame.blog') }}?category=game-industry">Game Industry</a></li>
```

#### [back_end][lamgame] — Sitemap Update
- [ ] Remove lottery URLs từ sitemap generation
- [ ] Remove sport URLs từ sitemap generation
- [ ] Remove LottoLive landing pages từ sitemap
- [ ] Regenerate sitemaps

**File:** Tìm và sửa sitemap generation logic

```php
// Exclude patterns
$excludePatterns = [
    '/xo-so/*',
    '/the-thao/*', 
    '/p/lottolive',
    '/p/ung-dung-lotto-live',
    '/world-cup-2026', // until rewritten
];
```

#### [back_end][lamgame] — Scheduler Cleanup
- [ ] Disable lottery scraping jobs
- [ ] Disable sport crawling jobs
- [ ] Keep jobs in code (commented) for potential migration

**File:** `app/Console/Kernel.php`

```php
// DISABLED — Phase 1 cleanup
// $schedule->job(new ScrapeVietlotLottery)->...;
// $schedule->job(new ScrapeTraditionalLottery)->...;
// $schedule->job(new CheckUserTickets)->...;
```

### Sprint 1 Acceptance Criteria
- [ ] URL audit spreadsheet complete với GSC data
- [ ] Main navigation không còn xổ số/betting/thể thao
- [ ] Footer không còn link tới vertical không liên quan
- [ ] Sitemap regenerated without non-core URLs
- [ ] Scheduler không chạy lottery/sport jobs
- [ ] Backup complete

---

## Sprint 2: Brand & Trust (28/08 - 03/09)

### Goal
Xây dựng E-E-A-T và thống nhất brand messaging.

### Tasks

#### [marketing][content] — About Page Rewrite
- [ ] Remove unverifiable statistics
- [ ] Update brand positioning
- [ ] Add dynamic metrics from database
- [ ] Update schema markup

**New About Page Content:**

```blade
{{-- Hero --}}
<h1>LamGame.vn — Hệ sinh thái Game Developer Việt Nam</h1>
<p class="lead">Learn. Build. Connect. Ship.</p>

{{-- Mission --}}
<h2>Sứ mệnh</h2>
<p>
LamGame.vn là hệ sinh thái dành cho cộng đồng Game Developer Việt Nam, 
kết nối kiến thức, công cụ, source code, cơ hội việc làm và cộng đồng 
để giúp developer học, xây dựng và phát hành game tốt hơn.
</p>

{{-- Dynamic Metrics --}}
<h2>Cộng đồng</h2>
<div class="stats-grid">
    <div class="stat">{{ $registeredUsers }} registered developers</div>
    <div class="stat">{{ $publishedSources }} source codes</div>
    <div class="stat">{{ $forumPosts }} forum discussions</div>
    <div class="stat">{{ $jobListings }} job listings</div>
</div>
<p class="stats-note">* Số liệu cập nhật realtime từ hệ thống LamGame.vn</p>
```

#### [back_end][lamgame] — Dynamic Metrics Service
- [ ] Create `SiteMetricsService`
- [ ] Query actual counts from database
- [ ] Cache results (1 hour TTL)
- [ ] Expose to About page controller

```php
// app/Services/SiteMetricsService.php
class SiteMetricsService
{
    public function getMetrics(): array
    {
        return Cache::remember('site_metrics', 3600, function () {
            return [
                'registered_users' => Customer::count(),
                'published_sources' => Product::where('type', 'source-game')
                    ->where('status', 1)->count(),
                'forum_posts' => ForumPost::where('is_active', true)->count(),
                'job_listings' => JobPosting::where('status', 'active')->count(),
                'total_downloads' => OrderItem::whereHas('product', function($q) {
                    $q->where('type', 'source-game');
                })->count(),
            ];
        });
    }
}
```

#### [back_end][lamgame] — Author System
- [ ] Create `Author` model
- [ ] Create migration
- [ ] Create author profile routes
- [ ] Create author box component

**Migration:**

```php
Schema::create('authors', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('slug')->unique();
    $table->string('title')->nullable(); // e.g., "Unity Developer"
    $table->text('bio')->nullable();
    $table->integer('experience_years')->nullable();
    $table->json('expertise')->nullable(); // ["Unity", "C#", "Mobile"]
    $table->json('social_links')->nullable();
    $table->string('avatar')->nullable();
    $table->foreignId('customer_id')->nullable()->constrained();
    $table->boolean('is_active')->default(true);
    $table->timestamps();
});

// Add author_id to blogs
Schema::table('blogs', function (Blueprint $table) {
    $table->foreignId('author_id')->nullable()->constrained();
});
```

**Author Model:**

```php
// app/Models/Author.php
class Author extends Model
{
    protected $fillable = [
        'name', 'slug', 'title', 'bio', 
        'experience_years', 'expertise', 'social_links',
        'avatar', 'customer_id', 'is_active'
    ];

    protected $casts = [
        'expertise' => 'array',
        'social_links' => 'array',
    ];

    public function blogs()
    {
        return $this->hasMany(Blog::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
```

#### [front_end][lamgame] — Author Box Component
- [ ] Create Blade component
- [ ] Add to blog detail page
- [ ] Style with existing design system

```blade
{{-- resources/views/components/author-box.blade.php --}}
@props(['author'])

<div class="author-box">
    <div class="author-box__avatar">
        <img src="{{ $author->avatar ?? asset('images/default-avatar.png') }}" 
             alt="{{ $author->name }}">
    </div>
    <div class="author-box__info">
        <h4 class="author-box__name">
            <a href="{{ route('author.show', $author->slug) }}">
                {{ $author->name }}
            </a>
        </h4>
        @if($author->title)
            <p class="author-box__title">{{ $author->title }}</p>
        @endif
        @if($author->expertise)
            <div class="author-box__expertise">
                @foreach($author->expertise as $skill)
                    <span class="tag">{{ $skill }}</span>
                @endforeach
            </div>
        @endif
        @if($author->bio)
            <p class="author-box__bio">{{ Str::limit($author->bio, 150) }}</p>
        @endif
    </div>
</div>
```

#### [marketing][content] — Editorial Policy Page
- [ ] Create CMS page `/chinh-sach-bien-tap`
- [ ] Document AI usage policy
- [ ] Document fact-checking process
- [ ] Document correction process

**Content Template:**

```markdown
# Chính sách biên tập

## Quy trình tạo nội dung
- Tất cả bài viết technical được review bởi developer có kinh nghiệm
- Fact-check với source chính thức trước khi publish
- Code examples được test trước khi đăng

## Sử dụng AI
- LamGame sử dụng AI tools để hỗ trợ draft và research
- Tất cả nội dung AI-generated được human review
- Không publish nội dung AI chưa qua kiểm duyệt

## Cập nhật và sửa lỗi
- Bài viết được review định kỳ để đảm bảo accuracy
- Nếu phát hiện sai sót, báo qua: [email]
- Corrections được ghi chú rõ ràng

## Affiliate Disclosure
- Một số link có thể là affiliate links
- Điều này không ảnh hưởng editorial independence
```

#### [marketing][content] — Contact Page Update
- [ ] Add clear organization info
- [ ] Add response time expectations
- [ ] Add editorial contact

### Sprint 2 Acceptance Criteria
- [ ] About page reflects ecosystem positioning
- [ ] No unverifiable statistics on About page
- [ ] Metrics sync from database
- [ ] Author model và migration created
- [ ] Author box component available
- [ ] Editorial policy page live
- [ ] Contact page updated

---

## Sprint 3: SEO Architecture (04/09 - 10/09)

### Goal
Chuẩn hóa taxonomy, internal linking và schema.

### Tasks

#### [marketing][seo] — Blog Category Cleanup
- [ ] Audit content trong mỗi category
- [ ] Remove/redirect betting-related posts
- [ ] Consolidate thin categories
- [ ] Add missing categories (AI Game Dev, Indie Game)

#### [back_end][lamgame] — Execute Redirects
- [ ] Implement redirect map từ Sprint 1
- [ ] Configure 410 Gone cho removed URLs
- [ ] Test redirect chains (max 1 hop)
- [ ] Monitor 404s

**Update `routes/redirects.php`:**

```php
// PHASE 1 CLEANUP REDIRECTS

// Lottery → 410 Gone (no equivalent content)
Route::get('xo-so/{any?}', function () {
    abort(410, 'Content permanently removed');
})->where('any', '.*');

// Sport → 410 Gone
Route::get('the-thao/{any?}', function () {
    abort(410, 'Content permanently removed');
})->where('any', '.*');

// LottoLive → 410
Route::get('lottolive', function () { abort(410); });
Route::get('p/lottolive', function () { abort(410); });
Route::get('p/ung-dung-lotto-live', function () { abort(410); });
```

#### [back_end][lamgame] — Tag Noindex
- [ ] Identify tags with < 3 posts
- [ ] Add noindex to thin tags
- [ ] Consider consolidating or removing

```php
// In tag route/controller
public function show($slug)
{
    $tag = ForumTag::where('slug', $slug)->firstOrFail();
    
    $shouldIndex = $tag->posts()->count() >= 3;
    
    return view('forum.tag', [
        'tag' => $tag,
        'shouldIndex' => $shouldIndex,
    ]);
}

// In view
@if(!$shouldIndex)
    <meta name="robots" content="noindex, follow">
@endif
```

#### [front_end][lamgame] — Breadcrumb Implementation
- [ ] Create breadcrumb component
- [ ] Add to all main page types
- [ ] Add BreadcrumbList schema

```blade
{{-- resources/views/components/breadcrumb.blade.php --}}
@props(['items'])

<nav aria-label="breadcrumb" class="breadcrumb-nav">
    <ol class="breadcrumb" itemscope itemtype="https://schema.org/BreadcrumbList">
        @foreach($items as $index => $item)
            <li class="breadcrumb-item {{ $loop->last ? 'active' : '' }}"
                itemprop="itemListElement" 
                itemscope 
                itemtype="https://schema.org/ListItem">
                @if($loop->last)
                    <span itemprop="name">{{ $item['label'] }}</span>
                @else
                    <a itemprop="item" href="{{ $item['url'] }}">
                        <span itemprop="name">{{ $item['label'] }}</span>
                    </a>
                @endif
                <meta itemprop="position" content="{{ $index + 1 }}" />
            </li>
        @endforeach
    </ol>
</nav>
```

#### [back_end][lamgame] — Schema Enhancement
- [ ] Add WebSite schema to homepage
- [ ] Add SearchAction schema
- [ ] Verify Article schema on blog posts
- [ ] Add Person schema for authors

```php
// app/Helpers/StructuredDataHelper.php — additions

public static function getWebsiteSchema(): array
{
    return [
        '@context' => 'https://schema.org',
        '@type' => 'WebSite',
        'name' => 'LamGame.vn',
        'url' => config('app.url'),
        'description' => 'Hệ sinh thái dành cho Game Developer Việt Nam',
        'potentialAction' => [
            '@type' => 'SearchAction',
            'target' => config('app.url') . '/search?q={search_term_string}',
            'query-input' => 'required name=search_term_string',
        ],
    ];
}

public static function getAuthorSchema(Author $author): array
{
    return [
        '@context' => 'https://schema.org',
        '@type' => 'Person',
        'name' => $author->name,
        'url' => route('author.show', $author->slug),
        'jobTitle' => $author->title,
        'description' => $author->bio,
        'sameAs' => array_values($author->social_links ?? []),
    ];
}
```

#### [marketing][seo] — Internal Linking Audit
- [ ] Identify orphan pages
- [ ] Map content clusters
- [ ] Add contextual links to posts
- [ ] Link related articles

### Sprint 3 Acceptance Criteria
- [ ] Redirect map executed
- [ ] 410 returns for removed URLs
- [ ] No redirect chains
- [ ] Thin tags noindexed
- [ ] Breadcrumbs on all pages
- [ ] Schema enhanced
- [ ] Internal linking improved

---

## Sprint 4: Topical Authority (11/09+)

### Goal
Build pillar content và establish topical authority.

### Tasks

#### [marketing][content] — Pillar Pages
Create comprehensive pillar pages:

1. **Unity Hub** `/unity`
   - Overview of Unity
   - Learning path
   - Best tutorials (internal links)
   - Related source codes
   - Related jobs

2. **Godot Hub** `/godot`
   - Similar structure

3. **Game Design Hub** `/game-design`
   - Principles
   - Tools
   - Career path

4. **Career Hub** `/game-developer-career`
   - Salary guide
   - Skills roadmap
   - Interview tips
   - Job board link

5. **AI for Game Dev** `/ai-game-dev`
   - AI tools
   - Use cases
   - Tutorials

#### [marketing][content] — Content Clusters
Link supporting articles to pillar pages:

```
Unity Hub
├── Unity Performance Optimization
├── Unity Mobile Development  
├── Unity 2D vs 3D
├── Unity C# Basics
└── Unity Best Practices

(Each links back to Unity Hub)
```

#### [marketing][seo] — Monitor & Iterate
- [ ] Track GSC indexing status weekly
- [ ] Monitor traffic changes
- [ ] Fix any crawl errors
- [ ] Adjust based on data

### Sprint 4 Acceptance Criteria
- [ ] 5 pillar pages created
- [ ] Content clusters established
- [ ] Internal linking complete
- [ ] No GSC errors
- [ ] Traffic stable or improving

---

## KPIs to Track

### SEO Metrics (GSC)
| Metric | Baseline | Target (30 days) | Target (90 days) |
|--------|----------|------------------|------------------|
| Indexed Pages | TBD | -20% (cleanup) | Stable |
| Organic Clicks | TBD | ±10% | +10% |
| Game Dev Keywords | TBD | +20% | +50% |
| Avg Position (Game Dev) | TBD | +2 positions | +5 positions |

### Product Metrics
| Metric | Baseline | Target (30 days) |
|--------|----------|------------------|
| Source Game Views | TBD | +5% |
| Job Applications | TBD | +5% |
| Forum Posts | TBD | +10% |
| Registrations | TBD | +5% |

---

## Risk Mitigation

| Risk | Mitigation |
|------|------------|
| Traffic drop from removals | Check GSC before removing anything |
| Broken links | Full crawl before/after |
| User confusion | Gradual rollout, monitor feedback |
| Technical issues | Staging test first |

---

## Team Responsibilities

| Role | Sprint 1 | Sprint 2 | Sprint 3 | Sprint 4 |
|------|----------|----------|----------|----------|
| SEO/Marketing | URL Audit, Classification | Content review, Policies | Linking, Monitor | Pillars |
| Backend | Backup, Sitemap, Scheduler | Metrics service, Author model | Redirects, Schema | Support |
| Frontend | Nav cleanup | Author box, About page | Breadcrumbs | Pillar pages |
| Content | Review | About rewrite, Policies | Post updates | Pillar content |

---

## Checklist Summary

### Phase 1 Complete When:
- [x] Audit complete
- [ ] Navigation cleaned
- [ ] Footer cleaned  
- [ ] Sitemap cleaned
- [ ] About page accurate
- [ ] Author system live
- [ ] Editorial policy live
- [ ] Redirects executed
- [ ] Schema enhanced
- [ ] Breadcrumbs added
- [ ] Pillars drafted
- [ ] GSC monitored
- [ ] No critical errors

---

**Document Owner:** Marketing/SEO Team  
**Technical Lead:** Backend Team  
**Last Updated:** 14/08/2026
