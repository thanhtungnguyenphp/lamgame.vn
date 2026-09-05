# LAMGAME.VN — TRẠNG THÁI DỰ ÁN
> Cập nhật: 14/05/2026 10:54 (GMT+7)
> Production deployed ✅ — Forum Phase 1-3 ✅ — Forum Management API ✅ — Job Crawler ✅ — 48 Mini Games ✅ — Redis cache ✅ — Queue Redis ✅ — Sentry ✅ — E-Commerce API ✅ — Reviews & Hire API ✅ — Blog Management API ✅ — SportPulse Phase 9 Foundation ✅ — Smoke Tests ✅

---

## 🔵 PAYMENT

| Kênh | Trạng thái | Ghi chú |
|------|:----------:|---------|
| PayPal | ✅ LIVE | Production go-live 23/04 — AI Subscription + Source Game checkout hoạt động |
| Lemon Squeezy | ⏳ Chờ support | Store #334725 — KYC bị reject, đã gửi email hello@lemonsqueezy.com (01/07/2026) chờ phản hồi |

---

## 🟢 ĐÃ HOÀN THÀNH

- [x] E-Commerce Core (Bagisto)
- [x] Source Game Marketplace (listing, detail, search, sort, SEO)
- [x] Seller System (đăng ký, duyệt, dashboard, CRUD, versioning, earnings, withdrawals)
- [x] **Forum / Cộng đồng — Phase 1-3 hoàn thành 28/04** (service layer, customer binding, rate limiting, bookmark, pin best answer, notifications, mentions, reputation, FULLTEXT search, trending, REST API, leaderboard)
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
- [x] **E-Commerce Management API** (28/04) — 33 endpoints, 7 modules
- [x] **Forum Management API** (29/04) — 28 endpoints, 6 modules (posts, comments, categories, tags, reports, leaderboard) — Ohha Studio integration
- [x] **Source Game Reviews API** (05/05) — Rating, review, helpful, verified purchase
- [x] **Hire Request API** (05/05) — Yêu cầu báo giá, email notification
- [x] Banner System (package LamGame/Banner)
- [x] SEO (sitemap, Google Index push, AdSense consent-gated trên blog)
- [x] Collections (bookmark sản phẩm)
- [x] Docker (8 services)
- [x] Cache Redis (24/04)
- [x] **Deploy production** (24/04 — 2 lần deploy thành công)

---

## 🟡 ĐANG LÀM / CHỜ

| Việc | Trạng thái | Chi tiết |
|------|:----------:|----------|
| Lemon Squeezy | ⏳ Chờ support | Đã gửi email 01/07 → chờ phản hồi re-verify KYC |

---

## 🔴 CHƯA LÀM

### Kỹ thuật (ưu tiên cao)
- [x] Chuyển queue sang Redis (QUEUE_CONNECTION=redis) — ✅ Done 04/05
- [x] Log rotation (laravel.log đang 23MB) — ✅ Done 04/05
- [x] Error monitoring (Sentry) — ✅ Done 04/05

### Sản phẩm
- [x] Trang "Thuê Team Dev" (service page + form báo giá) — ✅ Done 05/05
- [x] Review/rating system cho source game — ✅ Done 07/05
- [ ] Demo/preview trực tiếp cho source game
- [ ] License types (single, multi, extended)
- [ ] Thêm AI tools: Asset Generator, GDD Generator
- [ ] Streaming response cho AI tools
- [ ] Sport frontend (web views)
- [x] SportPulse Phase 9 — Crawl Data Foundation — ✅ Done 14/05 (migration, 6 commands, 5 services, scheduler)
- [ ] SportPulse Phase 9 — Chạy thực tế (cần API keys)
- [ ] Forum Phase 4 (tuỳ chọn): Polls, Private Messages, Follow User/Tag, WebSocket real-time

### Kỹ thuật (backlog)
- [x] Dọn file macOS metadata (._*) — ✅ Done 01/07 (đã xóa hết)
- [ ] CI/CD pipeline
- [x] Unit/Feature tests — ✅ Smoke tests 14/05 (30+ test cases)

---

## 📊 SỐ LIỆU

| Metric | Số lượng |
|--------|:--------:|
| Controllers | 25 (+1 BlogManageController) |
| Models | 44 (+1 SportCrawlLog) |
| Views (Blade) | 129 |
| Services | 23 (+5 SportCrawl services, +1 SourceGameReviewService) |
| Migrations | 80 (+1 sport crawl tracking) |
| Database tables | 196 (+1 sport_crawl_logs) |
| Forum API endpoints | 14 (REST API /api/v1/forum/) |
| Forum Management API | 28 (Admin API /api/manage/forum/) |
| Blog Management API | 17 (Admin API /api/manage/blogs/) |
| E-Commerce Management API | 42 |
| Job Management API | 15 |
| Reviews & Hire API | 5 |
| Sport Crawl Commands | 6 |
| Forum web routes | 43 (frontend + admin) |
| Mini Games | 48 |
| Source Game Products | 68 |
| Job Postings | 10 |
| Docker services | 8 |
| Scheduled tasks | 20 (+6 sport crawl) |
| Tests | 30+ (smoke tests) ✅ |

---

## 📋 VIỆC TIẾP THEO (khi quay lại)

1. **Đăng ký API-Football key** → thêm vào `.env` → chạy `migrate` + `seed SportExternalIdsSeeder`
2. **Map team external_ids** → chạy `sport:sync-fixtures` lần đầu
3. **Chạy xóa file `._*`** → `bash scripts/cleanup-macos-metadata.sh` (1098 files)
4. **Lemon Squeezy** — chờ Stripe duyệt
5. **SportPulse Phase 9** — test e2e sau khi có API keys
6. **Forum Phase 4** (tuỳ chọn) — Polls, Private Messages, Follow, WebSocket
7. **Demo/preview** cho source game (~5 ngày)
8. **CI/CD pipeline** — GitHub Actions

---

## 🔄 CHANGELOG 14/05/2026

### SportPulse Phase 9 — Crawl Foundation
- ✅ Migration: `external_id`, `source`, `synced_at` cho sport_matches; `external_ids` JSON cho teams + leagues; `source_url` cho highlights + articles; bảng `sport_crawl_logs`
- ✅ `SportDataService` base class (HTTP client, retry 3x, logging, team mapping)
- ✅ 5 crawl services: SyncFixtures, SyncLive, SyncStandings, SyncHighlights, SyncArticles
- ✅ 6 artisan commands: `sport:sync-fixtures`, `sport:sync-live`, `sport:sync-standings`, `sport:sync-highlights`, `sport:sync-articles`, `sport:cleanup`
- ✅ Scheduler config (6 commands đăng ký trong bootstrap/app.php)
- ✅ Config `sport-crawl.php` (15 leagues, API keys, retry settings)
- ✅ Seeder `SportExternalIdsSeeder` (map 15 leagues → API-Football IDs)
- ✅ `.env.example` thêm `API_FOOTBALL_KEY`, `BALLDONTLIE_KEY`

### Smoke Tests — API Quality
- ✅ `tests/Feature/ApiSmokeTest.php` — 30+ test cases
- ✅ Public endpoints: verify không trả 500
- ✅ Auth endpoints: verify trả 401 khi không có token
- ✅ Management endpoints: verify trả 401 khi không có X-Api-Key
- ✅ `phpunit.xml` thêm testsuite "API Smoke"

### Cleanup & Fixes
- ✅ Script `scripts/cleanup-macos-metadata.sh` — phát hiện 1098 file `._*` rác
- ✅ Đăng ký route `api-blog-manage.php` trong `bootstrap/app.php` (trước đó bị thiếu)

---

## 🔄 CHANGELOG 04/05/2026

### Infra Production — 4 tasks hoàn thành
- ✅ Queue chuyển sang Redis (`QUEUE_CONNECTION=redis`)
- ✅ Log rotation cấu hình (daily log channel + logrotate)
- ✅ Sentry error monitoring setup
- ✅ Deploy Forum Management API lên production (28 endpoints live)

---

## 🔄 CHANGELOG 29/04/2026

### Forum Management API — Ohha Studio Integration
**28 endpoints** tại `/api/manage/forum/` — Auth: `X-Api-Key` (admin token)

| Module | Endpoints | Chức năng |
|--------|:---------:|-----------|
| Dashboard | 1 | Thống kê tổng quan (posts, comments, reports, trends 7 ngày, top categories) |
| Posts | 8 | CRUD + change status + bulk status + bulk delete |
| Comments | 6 | List, detail, change status, delete, bulk status, bulk delete |
| Categories | 4 | CRUD (chặn xóa nếu còn bài viết) |
| Tags | 5 | CRUD + bulk delete |
| Reports | 3 | List, resolve, bulk resolve |
| Leaderboard | 1 | Reputation ranking (all-time / monthly) |

**Files:**
- `app/Http/Controllers/Api/ForumManageController.php` — Controller (reuse 5 forum services)
- `routes/api-forum-manage.php` — 28 routes
- `bootstrap/app.php` — Đăng ký route file
- `docs/forum/FORUM_MANAGE_API_GUIDE.md` — Tài liệu API

**Test local:** Tất cả 28 endpoints PASSED (dashboard, CRUD posts/categories/tags, status change, bulk ops, auth fail 401)

---

## 🔄 CHANGELOG 28/04/2026

### Forum Phase 1 — Service Layer, Customer Binding, Rate Limiting
**Commit:** `907c5c9` | 16 files | +1,179 / -1,057 lines

**Service Layer (tách business logic khỏi controller):**
- `ForumPostService` — CRUD posts, filter/search/sort, sync tags, stats, mass operations
- `ForumCommentService` — CRUD comments, mass operations, auto-update post stats
- `ForumVoteService` — Toggle like/dislike, resolve voteable
- `ForumReportService` — CRUD reports, duplicate check, mass operations

**Customer Binding:**
- Thêm `customer_id` (FK → customers) vào `forum_posts`, `forum_comments`, `forum_votes`
- Data migration tự động link records cũ qua email matching
- Fix `UserProfileController`: `author_id` (không tồn tại) → `customer_id`

**Rate Limiting & Anti-spam:**
- `ForumRateLimiter` middleware: 5 posts/h, 30 comments/h, 60 votes/h, 10 reports/h
- `ForumHoneypot` middleware: hidden field trap chống bot
- Protected routes qua middleware `customer` thay vì check thủ công trong controller

**Config:** `config/forum.php` — centralized settings (pagination, rate limits, cooldown, honeypot)

---

### Forum Phase 2 — Bookmark, Pin Best Answer, Notifications, Mentions
**Commit:** `6d83f6c` | 15 files | +708 lines

**Bookmark/Save Post:**
- Toggle bookmark qua AJAX, trang "Bài đã lưu" (`/forum/bookmarks`)
- `ForumBookmarkService`, `ForumBookmark` model, `forum_bookmarks` table

**Pin Best Answer:**
- Tác giả bài viết chọn/bỏ chọn "Câu trả lời tốt nhất" trên root comments
- Badge xanh "✓ Câu trả lời tốt nhất" + highlight nền xanh
- Thêm `is_best_answer` column vào `forum_comments`

**Notification System:**
- Tự động thông báo: reply bài viết, reply comment, best answer, @mention
- Trang thông báo (`/forum/notifications`) với icon theo loại, trạng thái đọc/chưa đọc
- Notification bell trên header forum, polling 30s, đánh dấu đã đọc
- `ForumNotificationService`, `ForumNotification` model, `forum_notifications` table

**Mention (@user):**
- Parse `@username` trong comment → gửi notification cho user được mention

---

### Forum Phase 3 — Search, Trending, Reputation, REST API
**Commit:** `054b3b1` | 18 files | +702 lines

**FULLTEXT Search:**
- MySQL `MATCH...AGAINST` thay thế `LIKE %...%`
- Sort: relevance (mặc định), newest, votes, comments
- Filter: category + tag + type

**Trending & Hot Posts:**
- Thuật toán: `(views + likes*3 + comments*5) / age_hours^1.5 * 1000`
- Command `forum:calculate-hot-scores` chạy cron mỗi giờ
- Trang `/forum/trending`

**Reputation System:**
- Điểm: post +10, comment +5, like +2, dislike -1, best answer +15, post removed -10
- 5 badges: 🌱 Newcomer → ⚡ Active → 🔥 Contributor → ⭐ Expert → 👑 Legend
- `ForumReputationService`, `ForumReputationLog` model, `forum_reputation_logs` table
- Leaderboard tổng + tháng (`/forum/leaderboard`)
- Badge hiển thị bên cạnh tên trong comments

**REST API (14 endpoints tại `/api/v1/forum/`):**
- Public: GET posts, posts/{slug}, categories, tags, trending, leaderboard
- Auth (Sanctum): POST/PUT/DELETE posts, comments, bookmark, vote, notifications
- `ForumApiController`, `ForumPostResource`, `ForumCommentResource`

---

### Job System Production Deploy + Crawler + E-Commerce API
- Deploy migration production — 3 migrations job_postings chạy thành công
- Job Crawler hoạt động — crawl TopDev, normalize, dedup, import tự động
- E-Commerce Management API — 33 endpoints, 7 modules
- Fix 4 bugs (SourceGameSeller FK, Order customer, SellerProduct pending_review, Admin email notifications)

---

## 🔄 CHANGELOG 25/04/2026

### Job System V2 Optimization (deployed production 25/04)
- Fix MySQL NULLS LAST, View count dedup, Cache filter options
- Form Requests, Validation improvements, Sanitize LIKE wildcards

---

## 🔄 CHANGELOG 24/04/2026

### Mini Game Products Sync
- Import database live, Redis cache, 48 game sync + ZIP (140MB)

### Job System Refactor (−8,000 lines, −89% code)
- Tách từ products EAV → job_postings flat table
- 19 files xóa, 13 files mới
