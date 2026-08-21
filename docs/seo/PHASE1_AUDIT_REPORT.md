# LamGame.vn Phase 1 Audit Report
## Làm sạch định vị, E-E-A-T và Brand Positioning

**Ngày audit:** 14/08/2026  
**Phiên bản:** 1.0

---

## Executive Summary

### Phát hiện chính

| Vấn đề | Mức độ | Trạng thái |
|--------|--------|------------|
| Xổ số (`/xo-so/*`) trong sitemap và navigation | 🔴 Critical | Cần xử lý ngay |
| Thể thao (`/the-thao/*`) trong sitemap và navigation | 🔴 Critical | Cần xử lý ngay |
| World Cup 2026 landing page | 🟠 High | Cần rewrite angle |
| LottoLive landing pages trong sitemap | 🔴 Critical | Cần migrate/remove |
| Trang About có claim không verify được | 🔴 Critical | Cần sửa ngay |
| Footer chứa link tới xổ số/thể thao | 🔴 Critical | Cần cleanup |
| Blog có nội dung review game generic | 🟠 High | Cần phân loại |
| Thiếu Author system | 🟠 High | Cần triển khai |
| Thiếu Editorial/Correction policy | 🟠 High | Cần tạo mới |

### Tình trạng hiện tại theo North Star

```
Learn   → ✅ Blog với nội dung game dev (một phần)
Build   → ✅ Source Game marketplace
Connect → ✅ Forum
Work    → ✅ Việc làm game
Ship    → ⚠️ Chưa có showcase/publish flow rõ ràng
```

### Vấn đề định vị

Website hiện tại đang pha trộn:
- ✅ **Game Development** (phù hợp)
- ❌ **Xổ số** (không phù hợp - 7 services, routes, models)
- ❌ **Thể thao/Betting** (không phù hợp - sport routes, crawlers)
- ❌ **World Cup generic** (cần rewrite)
- ⚠️ **Game reviews** (cần kiểm tra angle)

---

## Phần A: URL Audit Chi Tiết

### 1. Sitemaps hiện có

| Sitemap | URL Count | Nội dung | Action |
|---------|-----------|----------|--------|
| `sitemap-blogs.xml` | ~500+ | Mixed (game dev + game reviews) | AUDIT |
| `sitemap-forum.xml` | ~300+ | Game dev discussions | KEEP |
| `sitemap-jobs.xml` | ~200+ | Game developer jobs | KEEP |
| `sitemap-source-game.xml` | ~150+ | Source code marketplace | KEEP |
| `sitemap-landing.xml` | 2 | LottoLive pages | MIGRATE/REMOVE |
| `sitemap-pages.xml` | ~20 | CMS pages | AUDIT |
| `sitemap-sellers.xml` | ~10 | Seller profiles | KEEP |

### 2. Routes cần xử lý

#### 🔴 REMOVE/MIGRATE — Xổ số Routes (`routes/web.php` lines 68-83)

```php
// Lottery / Xổ số Web Views — KHÔNG LIÊN QUAN GAME DEV
Route::prefix('xo-so')->group(function () {
    Route::get('/', ...);           // /xo-so
    Route::get('mien-bac', ...);    // /xo-so/mien-bac
    Route::get('mien-trung', ...);  // /xo-so/mien-trung
    Route::get('mien-nam', ...);    // /xo-so/mien-nam
    Route::get('vietlott', ...);    // /xo-so/vietlott
    Route::get('vietlott/keno', ...);
    Route::get('vietlott/power-655', ...);
    Route::get('vietlott/mega-645', ...);
    Route::get('thong-ke', ...);
    Route::get('do-so', ...);
    Route::get('lich-quay', ...);
    Route::get('dai/{code}', ...);  // 63 tỉnh
});
```

**Action:** 
- Check GSC traffic/backlinks trước
- Nếu traffic > 100/tháng → Migrate sang domain khác
- Nếu traffic < 100/tháng → 410 Gone

#### 🔴 REMOVE/MIGRATE — Thể thao Routes (`routes/web.php` lines 56-66)

```php
// Sport Web Views — KHÔNG LIÊN QUAN GAME DEV
Route::prefix('the-thao')->group(function () {
    Route::get('/', ...);                  // /the-thao
    Route::get('lich-thi-dau', ...);       // /the-thao/lich-thi-dau
    Route::get('bang-xep-hang/{league}', ...);
    Route::get('tran-dau/{id}', ...);
    Route::get('doi-bong/{slug}', ...);
    Route::get('tin-tuc', ...);
    Route::get('tin-tuc/{id}', ...);
});
```

**Action:** Tương tự xổ số

#### 🟠 REWRITE — World Cup 2026 (`routes/web.php` line 92)

```php
Route::get('world-cup-2026', ...);
```

**Hiện tại:** Generic World Cup content  
**Cần rewrite:** Game bóng đá, AI trong game thể thao, EA Sports FC analysis

#### 🟠 REMOVE — LottoLive Landing Pages

```
/p/lottolive
/p/ung-dung-lotto-live
/lottolive
```

**Action:** Migrate sang project riêng hoặc 410

### 3. Database Models cần xử lý

| Model | File | Action |
|-------|------|--------|
| `LotteryDraw` | `app/Models/LotteryDraw.php` | Archive |
| `LotteryProvince` | `app/Models/LotteryProvince.php` | Archive |
| `LotteryResult` | `app/Models/LotteryResult.php` | Archive |
| `LotterySchedule` | `app/Models/LotterySchedule.php` | Archive |
| `LotteryScrapeLog` | `app/Models/LotteryScrapeLog.php` | Archive |
| `M7Match` | `app/Models/M7Match.php` | Archive |
| `M7Prediction` | `app/Models/M7Prediction.php` | Archive |
| Sport Models | `app/Models/Sport/*` | Archive |

### 4. Services cần xử lý

| Service | Path | Action |
|---------|------|--------|
| `LotteryService` | `app/Services/Lottery/` | Archive/Remove |
| `VietlotScraper` | `app/Services/Lottery/` | Archive/Remove |
| `TraditionalScraper` | `app/Services/Lottery/` | Archive/Remove |
| `XosoMeScraper` | `app/Services/Lottery/` | Archive/Remove |
| `SportCrawl/*` | `app/Services/SportCrawl/` | Archive/Remove |

### 5. Scheduler Jobs cần xử lý

Kiểm tra `app/Console/Kernel.php`:
- `ScrapeVietlotLottery`
- `ScrapeTraditionalLottery`
- `CheckUserTickets`
- Sport crawlers

---

## Phần B: E-E-A-T Audit

### 1. Trang About — Vấn đề phát hiện

**File:** `resources/views/lamgame/pages/gioi-thieu.blade.php`

#### Claims KHÔNG verify được (lines 125-142):

```html
<div class="stat-number">5000+</div>
<div class="stat-label">Học viên đã tốt nghiệp</div>

<div class="stat-number">200+</div>
<div class="stat-label">Game đã phát hành</div>

<div class="stat-number">95%</div>
<div class="stat-label">Tỷ lệ có việc làm</div>

<div class="stat-number">50+</div>
<div class="stat-label">Đối tác công ty</div>
```

**Vấn đề:**
- Không có evidence
- Không link tới source
- Không match với database metrics
- "Khóa học" không còn là core offering

#### Brand Message KHÔNG phù hợp:

```html
<p><strong>Làm Game</strong> là một nền tảng giáo dục trực tuyến 
chuyên cung cấp các khóa học lập trình game...</p>
```

**Vấn đề:** LamGame không phải chỉ là nền tảng khóa học, mà là ecosystem

### 2. Footer — Links không phù hợp

**File:** `resources/views/partials/footer-redesign.blade.php` (lines 57-62)

```html
<li><a href="{{ route('sport.index') }}">Thể thao</a></li>
<li><a href="{{ route('lottery.index') }}">Xổ số</a></li>
<li><a href="{{ route('world-cup-2026') }}">World Cup 2026</a></li>
```

**Action:** Remove khỏi "Khám phá" section

### 3. Author System — THIẾU

**Hiện trạng:**
- Không có Author model riêng
- Blog posts không hiển thị author detail
- Không có author profile pages
- Technical articles không có credential

**Cần triển khai:**
- `Author` model với bio, expertise, social links
- Author box component cho blog posts
- `/author/{slug}` routes
- Schema `Person` cho authors

### 4. Editorial Policy — THIẾU

**Cần tạo:**
- `/chinh-sach-bien-tap` (Editorial Policy)
- `/chinh-sach-sua-loi` (Correction Policy)
- `/cong-bo-affiliate` (Affiliate Disclosure)

### 5. Citation System — THIẾU

Blog posts hiện tại không có:
- Source citations
- Last Updated field visible
- Fact-check badges
- Reference links

---

## Phần C: Navigation Audit

### Main Menu (from MenuSeeder)

**Hiện tại:**
1. Trang chủ
2. Source Game ✅
3. Forum ✅
4. Blog ✅
5. Việc làm ✅
6. Giới thiệu ✅
7. Liên hệ ✅

**Vấn đề:** Menu chính OK, nhưng homepage và footer có links không phù hợp

### Footer Navigation

**Section "Khám phá" cần sửa:**

| Hiện tại | Action |
|----------|--------|
| Blog | ✅ Keep |
| Thể thao | ❌ Remove |
| Xổ số | ❌ Remove |
| World Cup 2026 | ❌ Remove hoặc Rewrite |
| Giới thiệu | ✅ Keep |
| Liên hệ | ✅ Keep |

---

## Phần D: Blog Categories Audit

### Hiện tại (từ BlogCategoriesTagsSeeder)

| Category | Status | Action |
|----------|--------|--------|
| Unity Development | ✅ | Keep |
| Unreal Engine | ✅ | Keep |
| Game Design | ✅ | Keep |
| Programming | ✅ | Keep |
| Mobile Game | ✅ | Keep |
| 2D Game | ✅ | Keep |
| 3D Game | ✅ | Keep |

**Vấn đề:** Cần audit content trong mỗi category để tìm bài viết không phù hợp (game reviews generic, betting, etc.)

### Đề xuất thêm categories

- AI Game Dev
- Game Industry / Career
- Indie Game
- Game Marketing

---

## Phần E: Structured Data Audit

### Hiện có

| Schema | Page | Status |
|--------|------|--------|
| Organization | `/gioi-thieu` | ✅ Có nhưng cần update |
| JobPosting | `/viec-lam-game/*` | ⚠️ Cần kiểm tra |
| Product | `/source-game/*` | ⚠️ Cần kiểm tra |
| Article | `/blog/*` | ⚠️ Cần kiểm tra |

### Cần thêm

| Schema | Page | Priority |
|--------|------|----------|
| Person (Author) | `/author/*` | P1 |
| HowTo | Tutorial posts | P2 |
| DiscussionForumPosting | `/forum/*` | P2 |
| BreadcrumbList | All pages | P1 |
| WebSite + SearchAction | Homepage | P1 |

---

## Action Items — Theo Priority

### P0 — Critical (Sprint 1)

- [ ] Export GSC data cho tất cả URLs
- [ ] Classify URLs: KEEP / REWRITE / MIGRATE / REMOVE
- [ ] Remove `/xo-so/*` từ navigation/footer
- [ ] Remove `/the-thao/*` từ navigation/footer
- [ ] Remove LottoLive từ sitemap
- [ ] Fix About page claims
- [ ] Update brand messaging

### P1 — High (Sprint 2)

- [ ] Tạo Author system
- [ ] Tạo Editorial Policy page
- [ ] Tạo Correction Policy page
- [ ] Update Blog posts với author info
- [ ] Add Last Updated to posts
- [ ] Audit blog content cho relevancy

### P2 — Medium (Sprint 3)

- [ ] Implement redirect map
- [ ] Execute 410/301 redirects
- [ ] Update sitemap generation
- [ ] Add schema markup
- [ ] Build topical clusters
- [ ] Internal linking optimization

---

## Recommendations

### 1. Migration Strategy cho Xổ số/Thể thao

**Option A:** Tạo subdomain/domain riêng
- `xoso.lamgame.vn` hoặc `xoso.vn`
- Di chuyển toàn bộ lottery functionality
- 301 redirect từ lamgame.vn

**Option B:** Archive và 410
- Nếu traffic không đáng kể
- Archive code sang branch riêng
- Return 410 Gone

**Khuyến nghị:** Kiểm tra GSC traffic 3 tháng gần nhất trước khi quyết định.

### 2. World Cup 2026 Rewrite

Thay vì content generic, rewrite thành:
- "Top 10 Game bóng đá hay nhất 2026"
- "Cách game developer xây dựng AI cầu thủ thực tế"
- "Phân tích kỹ thuật đồ họa trong EA Sports FC"
- "Cơ hội việc làm game thể thao mùa World Cup"

### 3. About Page Rewrite

```
LamGame.vn là hệ sinh thái dành cho Game Developer Việt Nam.

Chúng tôi cung cấp:
- Source code chất lượng cao
- Tutorial và kiến thức chuyên sâu  
- Cộng đồng và forum hỗ trợ
- Việc làm game developer
- AI tools cho game development

Metrics (từ database):
- X registered developers
- X published source codes
- X active forum posts
- X job listings
```

---

## Appendix

### A. Files cần sửa

```
# Navigation/Layout
resources/views/partials/footer-redesign.blade.php
resources/views/layouts/master.blade.php (if nav there)

# About
resources/views/lamgame/pages/gioi-thieu.blade.php

# Routes
routes/web.php (lines 56-92)
routes/api/lottery.php
routes/api/sport.php

# Sitemap
app/Console/Commands/GenerateSitemap.php (or similar)
config/sitemap.php

# Scheduler
app/Console/Kernel.php
```

### B. Database tables liên quan Lottery/Sport

```
lottery_draws
lottery_provinces
lottery_results
lottery_schedules
lottery_scrape_logs
user_tickets
m7_matches
m7_predictions
sport_* (multiple tables)
```

### C. GSC Query để check

```
site:lamgame.vn/xo-so
site:lamgame.vn/the-thao
site:lamgame.vn/p/lottolive
```

---

**Prepared by:** Kiro AI  
**Review required by:** Marketing/SEO Team, Backend Team  
**Next review date:** 21/08/2026
