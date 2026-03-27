# Báo cáo tổng thể — Blog Publish API

**Ngày:** 2026-03-27
**Branch:** `feat/Api.LotteApp`
**Commit:** `f8fdff7` — `feat(blog-api): harden Blog Publish API (#1-#8)`

---

## 1. Tổng quan chức năng

Blog Publish API cho phép đăng bài viết lên LAMGAME thông qua REST API, phục vụ tự động hóa xuất bản nội dung từ CLI tool, CMS bên thứ ba, hoặc CI/CD pipeline.

### Endpoints

| Method | Endpoint            | Chức năng                    | Rate Limit |
|--------|---------------------|------------------------------|------------|
| POST   | `/api/blog/publish` | Tạo bài viết mới             | 10 req/phút |
| POST   | `/api/blog/status`  | Kiểm tra trạng thái slug     | 60 req/phút |

### Xác thực
- Header `X-Api-Key` → hash SHA-256 → so khớp với `admins.api_token`
- Xử lý bởi middleware `ApiKeyAuth` (stateless, không session)

---

## 2. Luồng xử lý Publish (sau hardening)

```
Request → ApiKeyAuth middleware (hash + verify key)
    → Validate input (15 fields)
    → Check slug trùng (409 nếu trùng)
    → DB::transaction BEGIN
        → Resolve category (tìm hoặc tạo)
        → Resolve tags (tìm hoặc tạo)
        → Upload content images → regex rewrite URL trong HTML
        → Blog::create (status=0 nếu scheduled, =1 nếu publish ngay)
        → Upload thumbnail (nếu có)
    → DB::transaction COMMIT → Response 201
    → Nếu FAIL → ROLLBACK + cleanup orphan files
```

---

## 3. Các cải tiến đã thực hiện (8/8)

### 🔴 Ưu tiên cao (P0)

| # | Vấn đề | Giải pháp | File |
|---|--------|-----------|------|
| 1 | Không có DB transaction | `DB::transaction()` wrap toàn bộ logic publish | `BlogPublishController.php` |
| 2 | API key plain text | SHA-256 hash khi lưu + khi so sánh | `BlogPublishController.php`, migration |
| 3 | Auth logic duplicate | Extract `ApiKeyAuth` middleware | `ApiKeyAuth.php`, `bootstrap/app.php`, `routes/api.php` |

### 🟡 Ưu tiên trung bình (P1)

| # | Vấn đề | Giải pháp | File |
|---|--------|-----------|------|
| 4 | Status endpoint không validate | `required\|array\|max:100`, items `required\|string\|max:500` | `BlogPublishController.php` |
| 5 | Không hỗ trợ scheduled publish | `status=0` khi `published_at` > now(), command `blog:publish-scheduled` chạy mỗi 5 phút | `BlogPublishController.php`, `PublishScheduledBlogs.php`, `bootstrap/app.php` |
| 6 | Image rewrite chỉ match 2 pattern | Regex match 7 dạng path: `images/`, `./images/`, `../images/`, `/images/`, `assets/images/`, v.v. | `BlogPublishController.php` |

### 🟢 Ưu tiên thấp (P2)

| # | Vấn đề | Giải pháp | File |
|---|--------|-----------|------|
| 7 | Rate limit chung | Tách: publish `10/phút`, status `60/phút` | `routes/api.php` |
| 8 | Orphan files khi fail | Track `$uploadedPaths`, cleanup trong `catch` block | `BlogPublishController.php` |

---

## 4. Files thay đổi

| File | Loại | Mô tả |
|------|------|-------|
| `app/Http/Controllers/Api/BlogPublishController.php` | Modified | Transaction, scheduled publish, regex rewrite, cleanup auth |
| `app/Http/Middleware/ApiKeyAuth.php` | New | Middleware xác thực API key (SHA-256) |
| `app/Console/Commands/PublishScheduledBlogs.php` | New | Artisan command auto-publish bài scheduled |
| `app/Http/Controllers/Api/PublicThumbnailController.php` | New | Stub controller (unblock route:list) |
| `bootstrap/app.php` | Modified | Register `api.key` alias + scheduler |
| `routes/api.php` | Modified | Thêm middleware, tách rate limit |
| `database/migrations/2026_03_27_131842_hash_admin_api_tokens.php` | New | Hash token hiện tại bằng SHA-256 |
| `docs/blog-publish-api/README.md` | Modified | Cập nhật trạng thái task + changelog |

---

## 5. Kết quả test

| Test case                          | Expected | Actual | Status |
|------------------------------------|----------|--------|--------|
| Publish bài mới                    | 201      | 201    | ✅     |
| Publish trùng slug                 | 409      | 409    | ✅     |
| Sai API key                        | 401      | 401    | ✅     |
| Check status (valid slugs)         | 200      | 200    | ✅     |
| Check status (no slugs)            | 422      | 422    | ✅     |
| Check status (invalid items)       | 422      | 422    | ✅     |
| Scheduled publish (future date)    | status=0 | status=0 | ✅  |
| Scheduled publish (past date)      | status=1 | status=1 | ✅  |
| Scheduled publish (no date)        | status=1 | status=1 | ✅  |
| blog:publish-scheduled command     | 0 published | 0 published | ✅ |
| Image regex (7 patterns)           | All match | All match | ✅ |
| Hash key migration                 | 64 chars hex | 64 chars hex | ✅ |

---

## 6. Lưu ý triển khai

1. **Chạy migration trên production:**
   ```bash
   php artisan migrate
   ```
   → Hash tất cả API token hiện tại. Không thể rollback (one-way hash).

2. **Cron scheduler:** Đảm bảo cron đã chạy trên server:
   ```bash
   * * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
   ```
   → Command `blog:publish-scheduled` sẽ chạy mỗi 5 phút.

3. **Client không cần thay đổi:** Vẫn gửi plain API key qua `X-Api-Key` header.

4. **Tạo admin mới:** Vendor package (`Webkul/Admin/UserController`) vẫn lưu token plain text. Cần override hoặc thêm observer nếu tạo admin mới sau migration.
