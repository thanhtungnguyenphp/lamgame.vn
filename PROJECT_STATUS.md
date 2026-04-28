# LAMGAME.VN — TRẠNG THÁI DỰ ÁN
> Cập nhật: 28/04/2026 13:56 (GMT+7)
> Production deployed ✅ — Job System V2 ✅ — Job Crawler ✅ — 48 Mini Games ✅ — Redis cache ✅ — E-Commerce API ✅

---

## 🔵 PAYMENT

| Kênh | Trạng thái | Ghi chú |
|------|:----------:|---------|
| PayPal | ✅ LIVE | Production go-live 23/04 — AI Subscription + Source Game checkout hoạt động |
| Lemon Squeezy | ⏳ Đang chờ duyệt | Store #334725 — Chờ Stripe identity verification |

---

## 🟢 ĐÃ HOÀN THÀNH

- [x] E-Commerce Core (Bagisto)
- [x] Source Game Marketplace (listing, detail, search, sort, SEO)
- [x] Seller System (đăng ký, duyệt, dashboard, CRUD, versioning, earnings, withdrawals)
- [x] Forum / Cộng đồng
- [x] Blog (CRUD, scheduled publish, API publish)
- [x] Mini Games (48 game HTML5) — synced + ZIP 24/04
- [x] Xổ số (KQXS, Vietlot, dò vé, thống kê)
- [x] Landing Pages (admin CRUD)
- [x] Việc làm Game — **Refactored 24/04** → **Deployed + Crawled 28/04** (job_postings table riêng, crawler TopDev)
- [x] Job Crawler (crawl TopDev, normalize, dedup, auto-import)
- [x] Auth & User (đăng ký, đăng nhập, quên MK, verify email)
- [x] Subscription + PayPal
- [x] AI Tools (concept, codegen, debug, test, review — proxy qua II-Agent)
- [x] Sport / Bóng đá (API: live scores, BXH, highlights, articles)
- [x] **E-Commerce Management API** (28/04) — 33 endpoints, 7 modules: Dashboard, Products, Orders, Sellers, Earnings, Withdrawals, Customers
- [x] Banner System (package LamGame/Banner)
- [x] SEO (sitemap, Google Index push, Adsense)
- [x] Collections (bookmark sản phẩm)
- [x] Docker (8 services)
- [x] Cache Redis (24/04)
- [x] **Deploy production** (24/04 — 2 lần deploy thành công)

---

## 🟡 ĐANG LÀM / CHỜ

| Việc | Trạng thái | Chi tiết |
|------|:----------:|----------|
| Lemon Squeezy | ⏳ Chờ Stripe duyệt | Chờ verify → kích hoạt live mode |

---

## 🔴 CHƯA LÀM

### Kỹ thuật (ưu tiên cao)
- [ ] Chuyển queue sang Redis (QUEUE_CONNECTION=redis)
- [ ] Log rotation (laravel.log đang 23MB)
- [ ] Error monitoring (Sentry)

### Sản phẩm
- [ ] Trang "Thuê Team Dev" (service page + form báo giá)
- [ ] Review/rating system cho source game
- [ ] Demo/preview trực tiếp cho source game
- [ ] License types (single, multi, extended)
- [ ] Thêm AI tools: Asset Generator, GDD Generator
- [ ] Streaming response cho AI tools
- [ ] Sport frontend (web views)
- [ ] SportPulse Phase 9 — Crawl Data (11 tasks)

### Kỹ thuật (backlog)
- [ ] Dọn file macOS metadata (._*)
- [ ] CI/CD pipeline
- [ ] Unit/Feature tests

---

## 📊 SỐ LIỆU

| Metric | Số lượng |
|--------|:--------:|
| Controllers | 21 |
| Models | 40 |
| Views (Blade) | 123 |
| Services | 11 |
| Migrations | 76 |
| Database tables | 192 |
| Mini Games | 48 |
| Source Game Products | 68 |
| Job Postings | 10 |
| Docker services | 8 |
| Tests | 0 ❌ |

---

## 📋 VIỆC TIẾP THEO (khi quay lại)

1. **Tích hợp Job Management vào Ohha Studio** — tương tự chức năng Blog (đang làm)
2. **Queue → Redis** — `QUEUE_CONNECTION=redis` trong .env production (10 phút)
3. **Log rotation** — cấu hình daily log channel + logrotate (15 phút)
4. **Sentry** — setup error monitoring (1 giờ)
5. **SportPulse Phase 9** — Crawl data (11 tasks, ~6 ngày)

---

## 🔄 CHANGELOG 28/04/2026

### Job System Production Deploy + Crawler
- **Deploy migration production** — 3 migrations job_postings chạy thành công
- **Job Crawler hoạt động** — crawl dữ liệu từ TopDev, normalize, dedup, import tự động
- **Tài liệu API** — tạo `docs/job/JOB_API_GUIDE.md` cho tích hợp Ohha Studio
- **Tài liệu E-Commerce API** — tạo `docs/ecommerce/ECOMMERCE_API_GUIDE.md` (DB schema, 6 modules, route design)
- **Fix 4 bugs:**
  - `SourceGameSeller::products()` — FK `company_id` → `seller_id`
  - `Order::customer()` — bỏ `where('id', -999)` hack
  - `SellerProductController::store()` — thêm `pending_review=true`
  - `AdminProductController` — implement email notifications (ProductApproved/ProductRejected)
- **Files mới:** `ProductApproved.php`, `ProductRejected.php`, `product-approved.blade.php`, `product-rejected.blade.php`

---

## 🔄 CHANGELOG 25/04/2026

### Job System V2 Optimization (deployed production 25/04)
- **Fix MySQL NULLS LAST** — `orderByRaw('salary_max DESC NULLS LAST')` → `salary_max IS NULL, salary_max DESC`
- **View count dedup** — session-based, cùng session chỉ đếm 1 lần (chống F5 inflate)
- **Fix update null issue** — `array_filter` chặn set null → tách skills/benefits, pass data trực tiếp
- **Statistics subquery** — `pluck('id')` (2 queries) → `select('id')` subquery (1 query)
- **Cache filter options** — `Cache::remember` 1 giờ cho 8 DISTINCT queries + auto invalidate khi CRUD
- **Sanitize LIKE wildcards** — escape `%` và `_` trong scopeSearch/scopeByLocation
- **Extract Form Requests** — `StoreJobPostingRequest` + `UpdateJobPostingRequest` (loại bỏ validation trùng 3 lần)
- **Validation cải thiện** — thêm `salary_max gte:salary_min`, `skills/benefits max:20 items`

**Files mới:**
```
app/Http/Requests/StoreJobPostingRequest.php
app/Http/Requests/UpdateJobPostingRequest.php
```

**Files cập nhật:**
```
app/Models/JobPosting.php (incrementViews dedup, sanitize scopes)
app/Services/JobPostingService.php (update null fix, cache, subquery)
app/Http/Controllers/Api/JobPostingController.php (Form Requests)
app/Http/Controllers/Admin/JobPostingController.php (Form Requests)
app/Http/Controllers/LamGamePageController.php (MySQL NULLS LAST fix)
```

---

## 🔄 CHANGELOG 24/04/2026

### Mini Game Products Sync
- Import database live từ `database-backup/lamgame_20260424_1000.sql.gz`
- Chuyển `CACHE_STORE=file` → `CACHE_STORE=redis`
- Tạo `MiniGameProductsSyncSeeder`: sync 48 game từ `kho_game_free/output/`
  - 14 products cũ: update tên tiếng Việt + file path
  - 34 products mới: tạo mới (free, HTML5/JavaScript)
- Tạo 48 file ZIP (140MB) tại `storage/app/private/product_downloadable_links/`

### Job System Refactor (4 commits, −8,000 lines)
**Vấn đề:** Job dùng chung bảng `products` (Bagisto EAV) → query chậm (7-table JOIN), code phức tạp (~8,900 lines), không scale được.

**Giải pháp:** Tách hoàn toàn sang bảng `job_postings` riêng.

| Layer | Cũ | Mới |
|-------|-----|------|
| Database | products + product_flat + product_attribute_values (EAV) | job_postings (30+ columns tường minh) |
| Pivot | job_skills → products.id, job_benefits → products.id | job_posting_skills, job_posting_benefits (text-based) |
| Model | Product (Webkul) | JobPosting, JobPostingSkill, JobPostingBenefit |
| Service | JobService + JobSearchService + JobFilterService (~1,400 lines) | JobPostingService (~180 lines) |
| API Controller | JobController + UserJobController + JobBulkController (~1,200 lines) | JobPostingController (~180 lines) |
| Admin Controller | Admin/JobController (~350 lines, raw DB + hardcoded attr IDs) | Admin/JobPostingController (~120 lines) |
| Resource | JobResource (~170 lines, EAV resolve) | JobPostingResource (~60 lines) |
| Routes | ~50 routes phân tán trong api.php | 16 routes gọn (api-job-v2.php) |
| Frontend | LamGamePageController: 320 lines raw SQL | 110 lines Eloquent |

**Kết quả:**
- Code: 8,900 → 900 lines (−89%)
- Query: 7-table JOIN → single table Eloquent
- Products table: 78 → 68 records (chỉ còn source game)
- Data: 10 jobs + skills + benefits migrated thành công
- Backward compatible: views giữ nguyên, data transform qua `$job->attributes`

**Files mới:**
```
app/Models/JobPosting.php
app/Models/JobPostingSkill.php
app/Models/JobPostingBenefit.php
app/Services/JobPostingService.php
app/Services/JobPostingApplicationService.php
app/Http/Controllers/Api/JobPostingController.php
app/Http/Controllers/Api/JobPostingApplicationController.php
app/Http/Controllers/Admin/JobPostingController.php
app/Http/Resources/JobPostingResource.php
routes/api-job-v2.php
database/migrations/2026_04_24_000001_create_job_postings_table.php
database/migrations/2026_04_24_000002_migrate_jobs_from_products_to_job_postings.php
database/migrations/2026_04_24_000003_cleanup_job_products_from_products_table.php
```

**Files đã xóa (19 files):**
```
app/Services/JobService.php, JobSearchService.php, JobFilterService.php,
  JobApplicationService.php, JobImportExportService.php
app/Http/Controllers/Api/JobController.php, UserJobController.php,
  JobBulkController.php, JobAnalyticsController.php, JobApplicationController.php,
  JobOptionsController.php, JobImportExportController.php
app/Http/Controllers/Admin/JobController.php
app/Http/Resources/JobResource.php
app/Http/Requests/Api/CreateJobRequest.php, UpdateJobRequest.php,
  CreateUserJobRequest.php, JobApplicationRequest.php, JobImportRequest.php
```
