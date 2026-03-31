# LAMGAME Blog API — Tài liệu tích hợp

**Base URL:** `https://lamgame.vn/api/blog`
**Version:** 3.0
**Cập nhật:** 2026-03-31

---

## Xác thực (Authentication)

Tất cả endpoint yêu cầu header `X-Api-Key` chứa plain API key của admin.

| Header     | Bắt buộc | Mô tả                                |
|-----------|----------|----------------------------------------|
| X-Api-Key | ✅       | API key của admin (80 ký tự, plain text) |

Server tự hash key bằng SHA-256 trước khi so khớp với database. Client **không cần** hash key.

**Lỗi xác thực:**

```json
// HTTP 401
{"status": "error", "message": "Invalid API key"}
```

---

## Article Status

Hệ thống sử dụng 4 trạng thái cho bài viết:

| Status | Mô tả |
|--------|-------|
| `draft` | Bản nháp, chưa hiển thị |
| `scheduled` | Đã lên lịch, tự động publish khi đến `published_at` |
| `published` | Đã xuất bản, hiển thị trên web |
| `archived` | Đã lưu trữ, không hiển thị |

---

## Rate Limiting

| Endpoint | Giới hạn |
|----------|----------|
| publish, update, delete, change-status | 10 req / phút |
| status, list, detail | 60 req / phút |

Khi vượt giới hạn → HTTP `429 Too Many Requests`.

---

## Mã lỗi chung

| HTTP Code | Ý nghĩa | Response mẫu |
|-----------|----------|---------------|
| 401 | API key sai hoặc thiếu | `{"status":"error","message":"Invalid API key"}` |
| 404 | Blog không tồn tại | `{"status":"error","message":"Article not found"}` |
| 409 | Slug đã tồn tại | `{"status":"skipped","message":"Blog '...' already exists"}` |
| 422 | Dữ liệu không hợp lệ | `{"message":"...","errors":{...}}` |
| 429 | Vượt rate limit | Throttle response mặc định của Laravel |
| 500 | Lỗi server | `{"message":"Server Error"}` |

---

## 1. Publish — Tạo bài viết mới

```
POST /api/blog/publish
Content-Type: multipart/form-data
```

### Request Body

| Field | Type | Bắt buộc | Mô tả |
|-------|------|----------|-------|
| title | string | ✅ | Tiêu đề bài viết (max 500 ký tự) |
| slug | string | ✅ | URL slug, phải unique (max 500 ký tự) |
| description | string | ✅ | Nội dung HTML đầy đủ |
| short_description | string | ❌ | Mô tả ngắn (mặc định = meta_description) |
| category | string | ✅ | Tên category — tự tạo mới nếu chưa tồn tại |
| tags | array | ❌ | Danh sách tên tag — tự tạo mới nếu chưa tồn tại |
| meta_title | string | ❌ | SEO title (mặc định = title) |
| meta_description | string | ❌ | SEO description |
| meta_keywords | string | ❌ | SEO keywords |
| published_at | date | ❌ | Ngày xuất bản. Nếu > now → status=`scheduled`. Mặc định = now |
| thumbnail | file | ❌ | Ảnh đại diện (jpg, jpeg, png, webp, svg — max 5MB) |
| images[] | file[] | ❌ | Ảnh nội dung (jpg, jpeg, png, webp, gif, svg — max 10MB/ảnh) |

### Xử lý đặc biệt

- **Category & Tags**: gửi tên text, hệ thống tự tìm (case-insensitive) hoặc tạo mới
- **Scheduled publish**: nếu `published_at` là ngày tương lai → `status=scheduled`, hệ thống tự chuyển sang `published` khi đến giờ (kiểm tra mỗi 5 phút)
- **Image rewrite**: URL ảnh trong HTML tự động được rewrite. Hỗ trợ: `images/`, `./images/`, `../images/`, `/images/`, `assets/images/`

### Response thành công

```json
// HTTP 201
{
  "status": "ok",
  "message": "Blog published",
  "data": {
    "id": 42,
    "slug": "huong-dan-unity-co-ban",
    "url": "https://lamgame.vn/blog/huong-dan-unity-co-ban",
    "images_uploaded": 3
  }
}
```

### Ví dụ cURL

```bash
curl -X POST https://lamgame.vn/api/blog/publish \
  -H "X-Api-Key: YOUR_API_KEY" \
  -F "title=Hướng dẫn Unity cơ bản" \
  -F "slug=huong-dan-unity-co-ban" \
  -F "description=<p>Nội dung bài viết...</p>" \
  -F "category=Game Development" \
  -F "tags[]=unity" \
  -F "tags[]=tutorial" \
  -F "meta_title=Hướng dẫn Unity cho người mới" \
  -F "meta_description=Bài viết hướng dẫn Unity từ A-Z" \
  -F "published_at=2026-04-01" \
  -F "thumbnail=@/path/to/thumbnail.jpg" \
  -F "images[]=@/path/to/screenshot1.png"
```

---

## 2. Update — Cập nhật bài viết

```
POST /api/blog/update/{slug}
Content-Type: multipart/form-data
```

Tất cả field đều **optional** — chỉ gửi field cần cập nhật (partial update).

| Field | Type | Mô tả |
|-------|------|-------|
| title | string | Tiêu đề mới (max 500 ký tự) |
| description | string | Nội dung HTML mới |
| short_description | string | Mô tả ngắn mới |
| category | string | Tên category mới |
| tags | array | Danh sách tag mới (thay thế toàn bộ tag cũ) |
| meta_title | string | SEO title mới |
| meta_description | string | SEO description mới |
| meta_keywords | string | SEO keywords mới |
| published_at | date | Ngày xuất bản mới |
| status | string | `draft` \| `scheduled` \| `published` \| `archived` |
| thumbnail | file | Ảnh đại diện mới (thay thế ảnh cũ) |
| images[] | file[] | Ảnh nội dung mới |

### Response thành công

```json
// HTTP 200
{
  "status": "ok",
  "message": "Blog updated",
  "data": {
    "id": 42,
    "slug": "huong-dan-unity-co-ban",
    "url": "https://lamgame.vn/blog/huong-dan-unity-co-ban"
  }
}
```

### Ví dụ cURL

```bash
# Cập nhật title
curl -X POST https://lamgame.vn/api/blog/update/huong-dan-unity-co-ban \
  -H "X-Api-Key: YOUR_API_KEY" \
  -F "title=Hướng dẫn Unity cơ bản (Cập nhật 2026)"

# Chuyển sang archived
curl -X POST https://lamgame.vn/api/blog/update/huong-dan-unity-co-ban \
  -H "X-Api-Key: YOUR_API_KEY" \
  -F "status=archived"
```

---

## 3. Delete — Xóa bài viết

```
DELETE /api/blog/delete/{slug}
```

Xóa bài viết + toàn bộ file liên quan (thumbnail + content images).

```json
// HTTP 200
{"status": "ok", "message": "Blog deleted"}
```

```bash
curl -X DELETE https://lamgame.vn/api/blog/delete/huong-dan-unity-co-ban \
  -H "X-Api-Key: YOUR_API_KEY"
```

---

## 4. Check Status — Kiểm tra trạng thái nhiều slug

```
POST /api/blog/status
Content-Type: application/json
```

| Field | Type | Bắt buộc | Mô tả |
|-------|------|----------|-------|
| slugs | string[] | ✅ | Danh sách slug cần kiểm tra (max 100 items) |

### Response

```json
// HTTP 200
{
  "status": "success",
  "published": ["bai-viet-1"],
  "pending": ["slug-khong-ton-tai"],
  "draft": ["bai-viet-2"],
  "scheduled": [],
  "archived": ["bai-viet-3"]
}
```

- Mỗi slug chỉ xuất hiện trong đúng 1 mảng
- Slug không tồn tại trong DB → `pending`

```bash
curl -X POST https://lamgame.vn/api/blog/status \
  -H "X-Api-Key: YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"slugs": ["bai-viet-1", "bai-viet-2", "bai-viet-3"]}'
```

---

## 5. Change Status — Đổi trạng thái 1 bài viết

```
POST /api/blog/status/{slug}
Content-Type: application/json
```

| Field | Type | Bắt buộc | Mô tả |
|-------|------|----------|-------|
| status | string | ✅ | `draft` \| `scheduled` \| `published` \| `archived` |

### Business rules

| Chuyển sang | Hành vi |
|-------------|---------|
| `scheduled` | Yêu cầu `published_at` phải là ngày tương lai (422 nếu không) |
| `published` | Tự set `published_at = now()` nếu chưa có |
| `draft` | Xóa `published_at` |
| `archived` | Giữ nguyên `published_at` |

### Response

```json
// HTTP 200
{"status": "success", "message": "Article status updated to published"}

// HTTP 404
{"status": "error", "message": "Article not found"}

// HTTP 422
{"status": "error", "message": "Scheduled status requires published_at to be a future date"}
```

```bash
curl -X POST https://lamgame.vn/api/blog/status/huong-dan-unity-co-ban \
  -H "X-Api-Key: YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"status": "published"}'
```

---

## 6. List — Danh sách bài viết

```
GET /api/blog/list
```

### Query params

| Param | Type | Default | Mô tả |
|-------|------|---------|-------|
| page | int | 1 | Trang hiện tại |
| per_page | int | 20 | Số bài/trang (max 100) |
| status | string | — | Filter: `draft` \| `scheduled` \| `published` \| `archived` |
| category | string | — | Filter theo tên category (exact match, case-insensitive) |
| search | string | — | Tìm trong title và slug (LIKE %search%) |

Sắp xếp: `updated_at DESC`

### Response

```json
// HTTP 200
{
  "status": "success",
  "data": [
    {
      "slug": "bai-viet-1",
      "title": "Tiêu đề bài viết 1",
      "status": "published",
      "category": "Game Development",
      "published_at": "2026-03-28",
      "updated_at": "2026-03-28T10:30:00+07:00"
    }
  ],
  "meta": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 20,
    "total": 95
  }
}
```

```bash
# Lấy trang 1, chỉ bài published
curl -s -H "X-Api-Key: YOUR_API_KEY" \
  "https://lamgame.vn/api/blog/list?status=published&page=1&per_page=10"
```

---

## 7. Detail — Chi tiết bài viết

```
GET /api/blog/detail/{slug}
```

### Response

```json
// HTTP 200
{
  "status": "success",
  "data": {
    "slug": "bai-viet-1",
    "title": "Tiêu đề bài viết",
    "description": "<p>Nội dung HTML đầy đủ...</p>",
    "short_description": "Mô tả ngắn",
    "category": "Game Development",
    "tags": ["unity", "tutorial"],
    "meta_title": "SEO Title",
    "meta_description": "SEO Description",
    "meta_keywords": "keyword1, keyword2",
    "status": "published",
    "published_at": "2026-03-28",
    "thumbnail_url": "https://lamgame.vn/storage/blogs/42/abc123.webp",
    "created_at": "2026-03-27T08:00:00+07:00",
    "updated_at": "2026-03-28T10:30:00+07:00"
  }
}
```

```bash
curl -s -H "X-Api-Key: YOUR_API_KEY" \
  https://lamgame.vn/api/blog/detail/huong-dan-unity-co-ban
```

---

## Audit Log

Mọi thao tác publish/update/delete/change_status đều được ghi log vào bảng `blog_api_logs`:

| Field | Mô tả |
|-------|-------|
| admin_id | ID admin thực hiện |
| action | `publish`, `update`, `delete`, `change_status` |
| slug | Slug bài viết |
| blog_id | ID bài viết |
| ip | IP address của request |
| changes | Danh sách field đã thay đổi |
| created_at | Thời gian thực hiện |

---

## Scheduled Publish

Khi tạo bài với `published_at` là ngày tương lai:
- Bài được lưu với `status=scheduled`
- Hệ thống kiểm tra mỗi 5 phút và tự động chuyển `status=published` khi đến giờ
- Có thể override bằng cách dùng endpoint Change Status hoặc Update

---

## Tổng hợp Endpoints

| Method | Endpoint | Chức năng | Rate Limit | Content-Type |
|--------|----------|-----------|------------|--------------|
| POST | `/api/blog/publish` | Tạo bài mới | 10 req/phút | `multipart/form-data` |
| POST | `/api/blog/update/{slug}` | Cập nhật bài | 10 req/phút | `multipart/form-data` |
| DELETE | `/api/blog/delete/{slug}` | Xóa bài | 10 req/phút | — |
| POST | `/api/blog/status` | Kiểm tra nhiều slug | 60 req/phút | `application/json` |
| POST | `/api/blog/status/{slug}` | Đổi status 1 bài | 10 req/phút | `application/json` |
| GET | `/api/blog/list` | Danh sách + phân trang | 60 req/phút | — |
| GET | `/api/blog/detail/{slug}` | Chi tiết bài viết | 60 req/phút | — |

**Postman Collection:** `docs/blog-publish-api/Blog_Publish_API.postman_collection.json`

---

## Changelog

| Ngày | Thay đổi |
|------|----------|
| 2026-03-26 | v1.0 — publish + status endpoints |
| 2026-03-27 | v2.0 — hardening (#1-#8): transaction, hash key, middleware, scheduled publish |
| 2026-03-31 | v3.0 — article status system: status string (draft/scheduled/published/archived), change status endpoint, list endpoint, detail endpoint, Postman collection |
