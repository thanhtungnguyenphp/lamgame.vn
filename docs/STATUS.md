# LAMGAME.VN — Trạng thái dự án & Task List

> Cập nhật: 2026-04-14 14:25
> Tiếp tục: LS switch production key → monitor first real transaction

---

## 1. Tổng quan trạng thái

| Module | Trạng thái | Hoàn thành |
|--------|-----------|------------|
| Source Game Marketplace (listing, detail, cart, download) | ✅ Done | 100% |
| Seller System (registration, dashboard, upload, revenue, withdrawal) | ✅ Done | 100% |
| Seller Profile, Reviews, Wishlist, Collections, Version Control | ✅ Done | Sprint A-C |
| Checkout & Giỏ hàng (PayPal + COD) | ✅ Done | 18/18 tasks |
| SEO Technical (sitemap, IndexNow, scheduler, pagination) | ✅ Done | Sprint D |
| SEO On-Page (canonical, JSON-LD, OG, H1, CSS) | ✅ Done | 09/04 |
| Blog Publish API (hardening) | ✅ Done | 8/8 cải tiến |
| Auth (registration, email verify, reset password) | ✅ Done | v1.1.0 |
| Banner Module | ✅ Done | CRUD + tracking |
| Job & Job Application | ✅ Done | API + admin |
| GA4 Tracking | ✅ Done | 7 event types |
| FCM Push Notification | ✅ Fixed | 29/03 |
| Vietlot Scraper | ✅ Code fixed | Server cần proxy (Cloudflare block) |
| AI Subscription Plans | ✅ Sandbox PASSED + UI OK | 13/04 — subscribe, quota, cancel, pricing page OK |
| SportPulse API — Phase 1-8 | ✅ Done | 13 tables, 26 routes |
| **Lemon Squeezy — Phase 4 Testing** | ✅ e2e + refund PASSED | Checkout, order, invoice, refund OK 14/04 |
| **Docker local config** | ✅ Fixed | DB_HOST, traefik network, mysql service |
| **Currency USD** | ✅ Fixed | Sửa USA → USD trong DB |
| **SportPulse — Phase 9 Crawl** | 🔜 Tiếp theo | 11 tasks, ~6 ngày |
| SportPulse — Phase 10 Push | ⬜ Chưa bắt đầu | 5 notification jobs |

---

## 2. Việc đang làm & cần làm

> Ưu tiên: BÁN ĐƯỢC GÓI trước → revenue → rồi mới mở rộng

### 🔴 SPRINT NOW — Go-live bán hàng

#### Bước 1: AI Subscription Go-live (ít việc nhất, ~2h)

| # | Task | Ước tính | Trạng thái |
|---|------|---------|-----------|
| 1 | Chạy migration production (4 subscription tables) | 5 phút | ✅ Done (local) |
| 2 | Test e2e sandbox (subscribe → webhook → activate → use quota) | 1h | ✅ Done 13/04 |
| 3 | Switch PayPal sang production mode (.env) | 15 phút | ⬜ Cần deploy |
| 4 | Thêm route web `/ai-tools` trỏ tới trang subscription | 10 phút | ✅ Done |
| 5 | Verify trang pricing hiển thị đúng 3 gói + nút Subscribe | 15 phút | ✅ Done 13/04 |

> PayPal Plans sandbox ✅, webhook verify ✅, use-quota ✅, cancel ✅
> Trang `/ai-tools` render OK: 3 pricing cards, features table, FAQ, nút Subscribe ✅
> Bugs fixed 13/04: SubscriptionUsage::increment conflict, auth:sanctum guard, $customer undefined, duplicate aiSubscribe method
> **Hướng dẫn deploy production:** [subscription/DEPLOY_PRODUCTION.md](subscription/DEPLOY_PRODUCTION.md)
> **Chỉ còn:** tạo PayPal Live Plans + sửa .env production + migrate

#### Bước 2: Lemon Squeezy Go-live — bán Source Game (~1 ngày)

| # | Task | Ước tính | Trạng thái |
|---|------|---------|-----------|
| 6 | Setup ngrok/tunnel cho local webhook test | 15 phút | ✅ Done 14/04 |
| 7 | Test checkout flow browser — card 4242 (LS-301) | 30 phút | ✅ Done 14/04 |
| 8 | Test webhook → tạo order Bagisto (LS-302) | 30 phút | ✅ Done 14/04 — Order #9 created |
| 9 | Test guest + logged-in checkout (LS-304) | 30 phút | ✅ Guest overlay OK |
| 10 | Test downloadable product — cấp download (LS-305) | 30 phút | ⬜ |
| 11 | Chạy migration production (LS-307) | 5 phút | ✅ Done (deployed) |
| 12 | Switch production API key (LS-308) | 10 phút | ⬜ |
| 13 | Monitor first real transaction (LS-309) | — | ⬜ |

> e2e test PASSED 14/04: checkout → card 4242 → webhook → order #9 + invoice + cart deactivated ✅
> Bug fixed: Cart thiếu billing/shipping address → auto-tạo trong webhook handler

#### Bước 3: Test bổ sung (sau khi đã live)

| # | Task | Ước tính | Trạng thái |
|---|------|---------|-----------|
| 14 | Test refund flow (LS-303) | 30 phút | ✅ Done 14/04 — order→closed, txn→refunded |
| 15 | Test mobile overlay vs redirect (LS-306) | 30 phút | ⬜ |

### 🟡 P1 — Sau khi bán được

| # | Task | Module | Trạng thái |
|---|------|--------|-----------|
| 16 | Admin withdrawal processing UI | Seller System | ⬜ |
| 17 | www → non-www redirect | SEO | ⬜ |
| 18 | SportPulse Phase 9 — Crawl Data (11 tasks) | SportPulse | 🔜 |
| 19 | SportPulse Phase 10 — Push Notifications | SportPulse | ⬜ |

### 🟢 P2 — Q3-Q4

| # | Task | Module | Ước tính |
|---|------|--------|---------|
| 20 | License Management (Personal/Commercial/OSS) | Source Game | 2 tuần |
| 21 | Enhanced Preview (WebGL, video, code viewer) | Source Game | 2 tuần |
| 22 | Analytics & Insights (product + seller) | Source Game | 2 tuần |
| 23 | Virus scanning (ClamAV) | Security | 3-5 ngày |
| 24 | Performance Optimization (Redis, CDN, Lighthouse 95+) | Infra | 2 tuần |
| 25 | Email marketing / Abandoned cart | Marketing | 1-2 tuần |
| 26 | Mobile App (React Native) | Mobile | 3 tháng |

---

## 3. Modules & Chức năng chi tiết

### 3.1 Source Game Marketplace

| Chức năng | Routes | Trạng thái |
|-----------|--------|-----------|
| Browse, search, sort, filter category | `/source-game` | ✅ |
| Detail page (images, downloads, attributes) | `/source-game/{slug}` | ✅ |
| Cart → Payment → Download | `/checkout/*` | ✅ |
| Seller Registration (cá nhân/doanh nghiệp) | `seller/register` | ✅ |
| Admin Approval (duyệt/từ chối/suspend) | `admin/sellers/*` (8 routes) | ✅ |
| Seller Dashboard (stats, chart, orders) | `seller/dashboard` | ✅ |
| Product Upload (CRUD, multi-file) | `seller/products/*` (7 routes) | ✅ |
| Revenue Sharing (commission 30%) | `seller/earnings` | ✅ |
| Withdrawal System (min 100k VND) | `seller/withdrawals/*` (3 routes) | ✅ |
| Seller Profile Page | `/seller/{slug}` | ✅ |
| Reviews/Rating | Source game detail | ✅ |
| Wishlist | Source game detail | ✅ |
| User Collections (CRUD) | 6 routes | ✅ |
| Version Control (changelog, history) | Seller products | ✅ |
| SEO JSON-LD (SoftwareSourceCode) | Auto | ✅ |

**Database:** 6 bảng custom + `seller_id` trên products
**Stats:** 26+ routes, 1 seller, 4 source games

### 3.2 Checkout & Payment

| Phương thức | Trạng thái |
|-------------|-----------|
| PayPal Smart Button | ✅ Done (sandbox tested) |
| COD | ✅ Done |
| Money Transfer | ✅ Done |
| Lemon Squeezy (quốc tế) | ✅ e2e PASSED 14/04 |

### 3.3 Lemon Squeezy Integration

| Phase | Trạng thái |
|-------|-----------|
| Phase 1 — Setup & Đăng ký LS | ✅ Done |
| Phase 2 — Backend Package | ✅ Done |
| Phase 3 — Frontend (JS overlay) | ✅ Done |
| Phase 4 — Testing & Go-live | ✅ e2e + refund PASSED 14/04 — còn switch production |

Package: `packages/LemonSqueezy/` — Chi tiết: [checkout/LemonSqueezy/TASKS.md](checkout/LemonSqueezy/TASKS.md)

### 3.4 SportPulse API

| Phase | Trạng thái |
|-------|-----------|
| Phase 1-8 — Foundation + Endpoints | ✅ Done (13 tables, 12 models, 7 controllers, 26 routes) |
| Phase 9 — Crawl Data | 🔜 Tiếp theo (11 tasks, ~6 ngày) |
| Phase 10 — Push Notifications | ⬜ Chưa bắt đầu |

Base URL: `https://lamgame.vn/api/v1/sport` — Chi tiết: [API-SportPulse/TASKS.md](API-SportPulse/TASKS.md)

### 3.5 Blog Publish API

| Endpoint | Chức năng | Rate Limit |
|----------|-----------|-----------|
| `POST /api/blog/publish` | Tạo bài viết | 10 req/phút |
| `POST /api/blog/status` | Check slug | 60 req/phút |

Xác thực: `X-Api-Key` header (SHA-256 hash). 8/8 cải tiến done. Chi tiết: [blog-publish-api/REPORT.md](blog-publish-api/REPORT.md)

### 3.6 Lottery API (Xổ số)

| Endpoint | Chức năng |
|----------|-----------|
| Xổ số truyền thống (3 miền) | Scrape + API |
| Vietlot (Mega, Power, Max3D, Keno) | Scrape + API |
| Dò vé tự động | FCM push notification |

9 scheduled tasks. Chi tiết: [api/](api/)

### 3.7 AI Subscription

| Gói | Giá | Features chính |
|-----|-----|----------------|
| Free | $0 | ai_concept: 3/tháng, apply_job: 3, ticket_register: 3 |
| Pro | $9/tháng | ai_concept: 50, ai_generate: unlimited, ai_debug: unlimited, post_job: 2 |
| Business | $29/tháng | Tất cả unlimited, ai_asset: 100, featured_job: 2, freelancer_contact |

PayPal Billing. Sandbox test PASSED 13/04. Chờ deploy production.
Chi tiết: [subscription/AI_SUBSCRIPTION_PLANS.md](subscription/AI_SUBSCRIPTION_PLANS.md)
Hướng dẫn deploy: [subscription/DEPLOY_PRODUCTION.md](subscription/DEPLOY_PRODUCTION.md)

### 3.8 Job & Recruitment

| Chức năng | URL |
|-----------|-----|
| Job listings | `/viec-lam-game` |
| Job detail | `/viec-lam/{slug}` |
| Job application (API) | `POST /api/jobs/{id}/apply` |
| Admin CRUD | `/admin/jobs` |

Rate limit: 3/hr per email, 5/hr per IP. Chi tiết: [job/](job/), [job-application/](job-application/)

### 3.9 Banner System

Module: `LamGame/Banner` — CRUD + scheduling + device targeting + impression/click tracking.
Chi tiết: [banner/BANNER_SPEC.md](banner/BANNER_SPEC.md)

### 3.10 Auth

Registration → Email verification (SMTP2GO) → Login → Reset password.
Guard: `customer`. Chi tiết: [auth/README.md](auth/README.md)

---

## 4. Infrastructure

| Component | Chi tiết |
|-----------|---------|
| Docker | nginx + php + mysql + redis + meilisearch + traefik |
| Scheduler | 14 scheduled tasks (SEO + lottery + blog) |
| Sitemap | 7 sub-sitemaps, auto-generate daily 02:00 |
| IndexNow | Bing/Yandex batch URL submission daily 02:15 |
| Google Indexing API | Jobs (JobPosting) only, mỗi 6h |
| GA4 | G-WPXBBHC7XJ, 7 event types |
| SEO | Canonical, JSON-LD, OG, robots.txt, gzip, HTTPS, 301 redirects |

---

## 5. Số liệu

| Metric | Giá trị |
|--------|---------|
| Routes | 62+ (26 seller/source game + 26 SportPulse + 9 subscription + API) |
| Database tables (custom) | 23 (6 source game + 13 sport + 4 subscription) |
| Source games | 4 |
| Sellers | 1 |
| Scheduled tasks | 14 |
| Packages (custom) | LemonSqueezy, LamGame/Banner |

---

## 6. Changelog gần đây

| Ngày | Thay đổi |
|------|---------|
| 14/04 trưa | **Lemon Squeezy e2e + refund test PASSED**: ngrok tunnel → checkout card 4242 → webhook → Order #9 + invoice + cart deactivated. Refund webhook → order closed + txn refunded. Fix 2 bugs: auto-tạo CartAddress, dùng `CartAddress::create()` thay `Cart::addresses()` |
| 14/04 sáng | Update docs + STATUS.md. Deploy production AI Subscription OK |
| 13/04 tối | Fix trang `/ai-tools`: truyền `$customer` vào view, xóa duplicate `aiSubscribe()`, thêm route POST `ai-tools/subscribe`. Trang pricing render OK (3 cards + features table + FAQ) |
| 13/04 chiều | **AI Subscription sandbox test PASSED**: subscribe Free/Pro OK, PayPal approve OK, quota check/use/exceed OK, cancel OK (cả DB + PayPal). Fix 2 bugs: `SubscriptionUsage::increment` conflict → `incrementUsage`, thêm `sanctum-customer` guard cho Customer auth. Tạo hướng dẫn deploy production |
| 12/04 đêm | Fix LS checkout: thêm button vào custom checkout page, fix guest checkout (customer_id null), block direct order creation, fix Lemon.js event handler. Fix Docker: DB_HOST, traefik network, thêm mysql service. Fix currency USA→USD. LS overlay mở OK || 09/04 chiều | Dọn dẹp docs (75→46 files), tạo STATUS.md master. Sắp xếp ưu tiên bán gói trước. Thêm route `/ai-tools` cho trang subscription |
| 09/04 sáng | Merge feat/seller-payment → main. Fix LS checkout JS overlay. Fix SEO H1/CSS. Regenerate sitemaps |
| 03/04 | Lemon Squeezy Phase 1-3 done. Phase 4 bắt đầu, fix 3 bugs (VND currency, OrderResource, invoice) |
| 31/03 | SportPulse API Phase 1 done. Deploy production OK |
| 29/03 | Fix FCM credentials. Fix Vietlot scraper. AI Subscription Plans done |
| 28/03 | Sprint D SEO Technical done. Sitemap, IndexNow, scheduler fix |
| 27/03 | Blog Publish API hardening 8/8 done |
