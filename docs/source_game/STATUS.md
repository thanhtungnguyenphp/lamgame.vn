# Source Game Marketplace & Seller System — Trạng thái & Roadmap

**Cập nhật:** 2026-03-29
**Tài liệu trước:** 2026-03-28 (Sprint D — SEO Technical & Indexing)

## Changelog 29/03/2026
- Fix FCM: credentials typo `ttoken_uri`, cache null token, data object cast
- Fix Vietlot Scraper: URL mới, browser UA, implement Keno + Lotto parser
- AI Subscription Plans: 3 gói (Free/Pro/Business), migration seed, tài liệu

---

## 1. Trạng thái hiện tại

### ✅ Đã hoàn thành

| Module | Chi tiết | Routes |
|--------|----------|--------|
| **Source Game Listing** | Browse, search, sort, phân trang, filter category | `/source-game` |
| **Source Game Detail** | Images, downloadable links, attributes, related products | `/source-game/{slug}` |
| **Checkout/Download** | Cart → Payment → Download (Bagisto core) | `/checkout/*` |
| **Seller Registration** | Form đăng ký (cá nhân/doanh nghiệp), upload logo/banner | `seller/register` |
| **Admin Approval** | Duyệt/từ chối/suspend seller, email notification | `admin/sellers/*` (8 routes) |
| **Seller Dashboard** | Stats cards, revenue chart (Chart.js), recent orders, quick actions | `seller/dashboard` |
| **Product Upload** | CRUD sản phẩm downloadable, multi-file upload, validation | `seller/products/*` (7 routes) |
| **Revenue Sharing** | Commission 30%, earnings tracking | `seller/earnings` |
| **Withdrawal System** | Request rút tiền (min 100k VND), bank info | `seller/withdrawals/*` (3 routes) |
| **Middleware** | `CheckSeller` — bảo vệ routes, kiểm tra status | Registered |
| **Admin Menu** | Menu Sellers trong admin panel | Config-based |
| **Layout** | Custom account layout tích hợp seller navigation | Component |
| **Seller Profile Page** | `/seller/{slug}` — shop info, stats, product grid | Sprint B |
| **Reviews/Rating** | Reviews list + form đánh giá + star rating UI | Sprint B |
| **Wishlist** | Nút yêu thích toggle trên source game detail | Sprint B |
| **SEO JSON-LD** | Schema.org cho source game + blog, OG image | Sprint C |
| **Related Content** | Source games trên blog detail, seller link trên source game | Sprint C |
| **Collections** | User collections CRUD + add/remove items, 2 bảng DB, 6 routes | Sprint C |
| **Version Control** | Upload version mới, changelog, version history, 1 bảng DB | Sprint C |

**Database:** 6 bảng custom + `seller_id` column trên `products`
**Thống kê:** 26+ routes, 1 seller, 4 source game products

---

## 2. Sprint D — SEO Technical & Indexing (hoàn thành 2026-03-28)

### Vấn đề phát hiện

| # | Vấn đề | Mức độ |
|---|--------|--------|
| 1 | Sitemap cũ (13/01/2026), thiếu source game + seller URLs | 🔴 Critical |
| 2 | `GenerateSitemap` command có sẵn nhưng thiếu source game products + seller profiles | 🔴 Critical |
| 3 | Google Indexing API dùng sai — push blogs + source games (chỉ hỗ trợ JobPosting) | 🔴 Critical |
| 4 | Google ping sitemap deprecated từ 2023 (trả 404) | 🔴 Critical |
| 5 | Source game listing thiếu `rel=prev/next` cho pagination | 🟡 Medium |
| 6 | Laravel scheduler trong `Kernel.php` không được load (Laravel 11 dùng `bootstrap/app.php`) | 🔴 Critical |
| 7 | Cron jobs trên host trùng với Laravel scheduler | 🟡 Medium |

### Đã fix

| # | Task | Giải pháp | Commit |
|---|------|-----------|--------|
| D1 | **Sitemap source game + sellers** | Thêm `generateSourceGameSitemap()` + `generateSellerSitemap()` vào `GenerateSitemap.php`, tạo 7 sub-sitemaps + sitemap index | `885de05` |
| D2 | **Pagination SEO** | Thêm `rel=prev/next` vào `source-game.blade.php` qua `@push('pagination_links')` | `885de05` |
| D3 | **Fix Indexing API** | Restrict Google Indexing API chỉ cho jobs (JobPosting schema). Bỏ push blogs/source games | `78fe453` |
| D4 | **Replace ping sitemap** | Bỏ deprecated Google/Bing ping. Thay bằng IndexNow protocol (Bing/Yandex) — batch submit URLs | `78fe453` |
| D5 | **Fix Laravel scheduler** | Chuyển tất cả schedules từ `Kernel.php` sang `bootstrap/app.php` (Laravel 11) | `e2db10e`, `a4f6ee1` |
| D6 | **Lottery schedules** | Thêm 5 lottery scrape schedules vào `bootstrap/app.php` | `a4f6ee1` |
| D7 | **Cleanup cron trùng** | Xóa 7 cron jobs trùng trên host (sitemap + lottery) | Manual on server |

### Files thay đổi

| File | Loại | Mô tả |
|------|------|-------|
| `app/Console/Commands/GenerateSitemap.php` | Modified | +2 methods: source game + seller sitemaps |
| `app/Console/Commands/PushToGoogleIndex.php` | Rewritten | Jobs-only Indexing API + IndexNow cho content khác |
| `config/services.php` | Modified | Thêm `indexnow.key` config |
| `bootstrap/app.php` | Modified | Thêm tất cả schedules (SEO + lottery) |
| `app/Console/Kernel.php` | Unchanged | Giữ lại nhưng không được load bởi Laravel 11 |
| `resources/views/lamgame/pages/source-game.blade.php` | Modified | Thêm `rel=prev/next` pagination |

### Kết quả deploy (production)

| Item | Trạng thái |
|------|-----------|
| Sitemap regenerated (7 sub-sitemaps) | ✅ 4 source games, 1 seller |
| IndexNow submit | ✅ 34 URLs (HTTP 202) |
| Google Indexing API (jobs) | ✅ 3/3 success |
| Google Service Account verified | ✅ Owner in Search Console |
| IndexNow key + verification file | ✅ Deployed |
| Laravel scheduler (14 schedules) | ✅ All registered |
| Host cron cleaned | ✅ 7 duplicates removed |

### Scheduler tổng hợp (production)

| Schedule | Thời gian | Mô tả |
|----------|-----------|-------|
| `blog:publish-scheduled` | */5 min | Auto-publish bài scheduled |
| `sitemap:generate` | 02:00 daily | Tạo lại sitemap index + 7 sub-sitemaps |
| `google:push-index --type=jobs` | */6h | Push jobs lên Google Indexing API |
| `google:push-index --type=indexnow` | 02:15 daily | Submit URLs mới lên Bing/Yandex |
| `lottery:scrape --region=mien-nam` | */5 min, 16:35-17:15 | Scrape KQXS Miền Nam |
| `lottery:scrape --region=mien-trung` | */5 min, 17:35-18:15 | Scrape KQXS Miền Trung |
| `lottery:scrape --region=mien-bac` | */5 min, 18:35-19:15 | Scrape KQXS Miền Bắc |
| `ScrapeVietlotLottery` | */5 min, 18:05-18:45 | Scrape Vietlot (Mega, Power, Max3D) |
| `ScrapeVietlotLottery(keno)` | */10 min, 06:00-22:00 | Scrape Keno |

---

## 3. SEO On-Page — Trạng thái

### ✅ Đã có

| Item | Chi tiết |
|------|----------|
| Canonical URL | Tự động strip query params |
| `noindex` paginated | `page > 1` → `noindex, follow` |
| `rel=prev/next` | Source game listing pagination |
| JSON-LD | `SoftwareSourceCode` (source game), `Article` (blog) |
| Open Graph + Twitter Card | Đầy đủ trên tất cả pages |
| robots.txt | Block admin, seller, checkout, query params |
| Trailing slash redirect 301 | `.htaccess` |
| `index.php` redirect 301 | `.htaccess` |
| HTTPS force | `URL::forceScheme('https')` |
| Gzip compression | `.htaccess` mod_deflate |
| Static file caching | 7 ngày cho images, CSS, JS |
| Sitemap index | 7 sub-sitemaps, auto-generate daily |
| IndexNow | Bing/Yandex batch URL submission |
| Google Indexing API | Jobs (JobPosting) only |

### 📋 Chưa làm

| Item | Mức độ | Mô tả |
|------|--------|-------|
| www → non-www redirect | 🟡 Medium | `.htaccess` hoặc nginx config |
| Virus scanning (ClamAV) | 🟢 Low | Upload file scanning |
| License Management | 📋 Phase 3 | Personal/Commercial/Open Source |
| Enhanced Preview | 📋 Phase 3 | WebGL embed, video, code viewer |
| Analytics & Insights | 📋 Phase 3 | Product + seller analytics |
| Performance Optimization | 📋 Phase 4 | Caching, DB tuning, Lighthouse 95+ |

---

## 4. Cấu trúc code hiện tại

```
app/Http/Controllers/
├── SellerController.php           # Registration, dashboard, orders, analytics
├── SellerProductController.php    # Product CRUD (downloadable)
├── SellerEarningController.php    # Earnings + Withdrawals
├── Admin/
│   ├── AdminSellerController.php  # Admin approval system
│   ├── AdminProductController.php # Admin product management
│   └── AdminWithdrawalController.php # Admin withdrawal processing

app/Console/Commands/
├── GenerateSitemap.php            # 7 sub-sitemaps + sitemap index
├── PushToGoogleIndex.php          # Google Indexing API (jobs) + IndexNow
├── LotteryScrapeCommand.php       # Scrape KQXS truyền thống
└── PublishScheduledBlogs.php      # Auto-publish scheduled blogs

app/Jobs/
├── ScrapeTraditionalLottery.php   # Queue job scrape XS truyền thống
├── ScrapeVietlotLottery.php       # Queue job scrape Vietlot
└── CheckUserTickets.php           # Tự động dò vé

app/Services/Lottery/
├── TraditionalScraper.php         # Scrape xoso.com.vn
├── VietlotScraper.php             # Scrape vietlott.vn
├── LotteryNotificationService.php # FCM push notifications
├── LotteryCheckService.php        # Dò vé logic
├── LotteryService.php             # Core lottery service
└── LotteryStatisticsService.php   # Thống kê xổ số
```

---

## 5. Database Schema tóm tắt

```
source_game_sellers          # Seller info + status
source_game_earnings         # Revenue sharing 70/30
source_game_withdrawals      # Withdrawal requests
source_game_versions         # Version control + changelog
user_collections             # User collections
collection_items             # Collection ↔ Product mapping
```

---

## 6. ROADMAP — Đánh giá & Định hướng phát triển (29/03/2026)

### 📊 Tổng kết tiến độ

| Phase / Module | Trạng thái | Hoàn thành |
|----------------|-----------|------------|
| Checkout & Giỏ hàng | ✅ Done | 18/18 tasks (100%) |
| Phase 1 — Foundation (listing, detail, cart, download) | ✅ Done | — |
| Phase 2 — Seller System (4 sprints) | ✅ Done | Registration, upload, dashboard, revenue/withdrawal |
| Sprint A/B/C — Seller profile, reviews, wishlist, SEO JSON-LD, collections, version control | ✅ Done | — |
| Sprint D — SEO Technical & Indexing | ✅ Done (28/03) | Sitemap, IndexNow, scheduler fix, pagination SEO |
| Blog Publish API — Hardening | ✅ Done (27/03) | 8/8 cải tiến, 12/12 tests passed |
| SEO On-Page | ✅ Done | Canonical, JSON-LD, OG, robots.txt, gzip, caching |
| FCM Push Notification | ✅ Fixed (29/03) | Fix credentials typo, cache null token, data object cast |
| Vietlot Scraper | ✅ Code fixed (29/03) | URL mới + browser UA + Keno/Lotto parser. Server cần proxy (Cloudflare block) |
| AI Subscription Plans | ✅ Done (29/03) | 3 gói (Free/Pro $9/Business $29), migration + tài liệu. Chờ PayPal Billing Plans + migrate production |
| Lemon Squeezy Integration | ⏸️ Tạm hoãn | Chờ multi-language |

### 🗓️ Roadmap sắp tới

#### Ngay bây giờ — AI Subscription Go-live

| # | Task | Ước tính | Ưu tiên | Trạng thái |
|---|------|----------|---------|-----------|
| 1 | **Chạy migration production** (subscription tables) | 5 phút | 🔴 P0 | ⬜ |
| 2 | **Tạo PayPal Billing Plans** (Pro + Business) | 1 giờ | 🔴 P0 | ⬜ |
| 3 | **Cập nhật paypal_plan_id vào DB** | 10 phút | 🔴 P0 | ⬜ |
| 4 | **Config .env production** (PayPal subscription credentials) | 30 phút | 🔴 P0 | ⬜ |
| 5 | **Thêm endpoint use-quota** cho AI service | 1 giờ | 🔴 P0 | ⬜ |
| 6 | **Verify PayPal webhook signature** | 2 giờ | 🟡 P1 | ⬜ |
| 7 | **Test end-to-end sandbox** | 2 giờ | 🟡 P1 | ⬜ |

#### Q2/2026 (Tháng 4-6) — Sau khi subscription live

| # | Task | Ước tính | Ưu tiên | Ghi chú |
|---|------|----------|---------|---------|
| 8 | **Multi-language** (đa ngôn ngữ) | 1-2 tuần | 🟡 P1 | Prerequisite cho Lemon Squeezy |
| 9 | **Lemon Squeezy integration** | 10-14 ngày | 🟡 P1 | Cổng thanh toán quốc tế, license key, MoR thuế |
| 10 | **www → non-www redirect** | 1 ngày | 🟡 P1 | .htaccess hoặc nginx config |
| 11 | **License Management** | 2 tuần | 🟡 P1 | Personal/Commercial/Open Source, license key generation, verification API |
| 12 | **Enhanced Preview** | 2 tuần | 🟡 P1 | WebGL embed, video player, code viewer, 3D model viewer |

#### Q3/2026 (Tháng 7-9) — Phase 3 Advanced Features

| # | Task | Ước tính | Ưu tiên | Ghi chú |
|---|------|----------|---------|---------|
| 6 | **Analytics & Insights** | 2 tuần | 🟡 P1 | Product analytics, seller analytics, admin dashboard |
| 7 | **Virus scanning (ClamAV)** | 3-5 ngày | 🟢 P2 | Upload file scanning |
| 8 | **Email marketing / Abandoned cart** | 1-2 tuần | 🟢 P2 | Nếu dùng LS thì có sẵn |
| 9 | **Affiliate program** | 2 tuần | 🟢 P2 | Nếu dùng LS thì có sẵn |

#### Q4/2026 (Tháng 10-12) — Phase 4 Optimization

| # | Task | Ước tính | Ưu tiên | Ghi chú |
|---|------|----------|---------|---------|
| 10 | **Performance Optimization** | 2 tuần | 🟡 P1 | Redis caching, DB tuning, CDN, Lighthouse 95+ |
| 11 | **Marketing tools** | 2 tuần | 🟢 P2 | Discount codes, promotional banners, social sharing |
| 12 | **Mobile App** (optional) | 3 tháng | 🟢 P2 | React Native, iOS + Android |

### 🎯 Mục tiêu theo giai đoạn

| Giai đoạn | Sellers | Source Games | Transactions/tháng | Revenue/tháng |
|-----------|---------|-------------|-------------------|---------------|
| Hiện tại (03/2026) | 1 | 4 | — | — |
| Cuối Q2/2026 | 20+ | 100+ | 50+ | — |
| Cuối Q3/2026 | 50+ | 300+ | 200+ | $5,000+ |
| Cuối Q4/2026 | 100+ | 500+ | 500+ | $20,000+ |

### 📈 Số liệu hiện tại

- **Routes:** 26+ (seller/source game)
- **Database:** 6 bảng custom + `seller_id` trên products
- **Products:** 4 source games, 1 seller
- **Scheduled tasks:** 14 (SEO + lottery + blog)
- **Infrastructure:** Docker (lamgame-php + lamgame-web + nginx)
- **Sitemap:** 7 sub-sitemaps, auto-generate daily
- **IndexNow:** 34 URLs submitted (HTTP 202)

### ⚠️ Rủi ro & Lưu ý

| Rủi ro | Mức độ | Giải pháp |
|--------|--------|-----------|
| Lemon Squeezy xác minh chậm (1-3 ngày) | 🟡 Medium | Đăng ký sớm, song song với dev |
| Tỷ giá USD/VND chênh lệch qua LS | 🟡 Medium | Hiển thị giá USD rõ ràng |
| Low seller adoption | 🔴 High | Marketing, incentives, onboarding hướng dẫn |
| File upload security (chưa có virus scan) | 🟡 Medium | Ưu tiên ClamAV trong Q3 |
| Admin withdrawal processing chưa có UI | 🟡 Medium | Cần build trước khi có withdrawal thực |

### 📝 Quyết định cần đưa ra

1. **Multi-language scope:** Hỗ trợ bao nhiêu ngôn ngữ? (EN + VI? Thêm ngôn ngữ khác?)
2. **Lemon Squeezy vs Stripe direct:** LS phí cao hơn (5%+$0.50) nhưng bao gồm MoR/thuế. Stripe rẻ hơn (2.9%+$0.30) nhưng tự xử lý thuế. Quyết định dựa trên volume dự kiến.
3. **Mobile app:** Có cần trong 2026 không? Hay focus web trước?
4. **Pricing strategy:** Giá tối thiểu source game bao nhiêu? (LS phí cố định $0.50 không phù hợp sản phẩm <$5)
