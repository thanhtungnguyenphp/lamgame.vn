# Tài liệu Blog Publish API

## Tổng quan

Blog Publish API cho phép đăng bài viết lên LAMGAME thông qua REST API, phục vụ cho việc tự động hóa quy trình xuất bản nội dung từ các nguồn bên ngoài (CLI tool, CMS bên thứ ba, CI/CD pipeline). Xác thực bằng API key của admin.

---

## Kiến trúc Hệ thống

```
┌─────────────────────────────────────────────────────────────┐
│                    CLIENT / CALLER                          │
│  - CLI script (ImportBlogContent command)                   │
│  - Postman / cURL                                          │
│  - CI/CD pipeline                                          │
└────────────────────┬────────────────────────────────────────┘
                     │ POST /api/blog/publish
                     │ POST /api/blog/status
                     ▼
┌─────────────────────────────────────────────────────────────┐
│                  MIDDLEWARE LAYER                           │
│  - throttle:30,1 (30 requests/phút)                       │
└────────────────────┬────────────────────────────────────────┘
                     │
                     ▼
┌─────────────────────────────────────────────────────────────┐
│                 CONTROLLER LAYER                           │
│  BlogPublishController                                     │
│  - publish()   → tạo blog mới                             │
│  - status()    → kiểm tra trạng thái slug                 │
└────────────────────┬────────────────────────────────────────┘
                     │
          ┌──────────┼──────────┐
          ▼          ▼          ▼
┌──────────────┐ ┌────────┐ ┌──────────────┐
│ Blog Model   │ │Storage │ │ Category/Tag │
│ (MySQL)      │ │(disk)  │ │ auto-resolve │
└──────────────┘ └────────┘ └──────────────┘
```

---

## API Endpoints

### 1. Publish Blog

```
POST /api/blog/publish
```

**Headers:**

| Header       | Bắt buộc | Mô tả                          |
|-------------|----------|----------------------------------|
| X-Api-Key   | ✅       | API token của admin user         |
| Content-Type| ✅       | `multipart/form-data`            |

**Body (form-data):**

| Field             | Type     | Bắt buộc | Mô tả                                      |
|-------------------|----------|----------|----------------------------------------------|
| title             | string   | ✅       | Tiêu đề bài viết (max 500 ký tự)            |
| slug              | string   | ✅       | URL slug (max 500, phải unique)              |
| description       | string   | ✅       | Nội dung HTML đầy đủ                         |
| short_description | string   | ❌       | Mô tả ngắn                                  |
| category          | string   | ✅       | Tên category (tự tạo nếu chưa có)           |
| tags              | array    | ❌       | Danh sách tag (tự tạo nếu chưa có)          |
| meta_title        | string   | ❌       | SEO title (mặc định = title)                |
| meta_description  | string   | ❌       | SEO description                              |
| meta_keywords     | string   | ❌       | SEO keywords                                 |
| published_at      | date     | ❌       | Ngày xuất bản (mặc định = now)              |
| thumbnail         | file     | ❌       | Ảnh đại diện (jpg,png,webp,svg — max 5MB)   |
| images[]          | file[]   | ❌       | Ảnh nội dung (jpg,png,webp,gif,svg — max 10MB/ảnh) |

**Response thành công (201):**

```json
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

**Response lỗi:**

| HTTP Code | Trường hợp                | Response                                              |
|-----------|---------------------------|-------------------------------------------------------|
| 401       | API key sai hoặc thiếu    | `{"status":"error","message":"Invalid API key"}`      |
| 409       | Slug đã tồn tại           | `{"status":"skipped","message":"Blog '...' already exists"}` |
| 422       | Validation fail            | `{"message":"...","errors":{...}}`                    |
| 429       | Rate limit exceeded        | Throttle response mặc định của Laravel                |

---

### 2. Check Status

```
POST /api/blog/status
```

**Headers:**

| Header     | Bắt buộc | Mô tả                  |
|-----------|----------|--------------------------|
| X-Api-Key | ✅       | API token của admin user |

**Body (JSON):**

```json
{
  "slugs": ["bai-viet-1", "bai-viet-2", "bai-viet-3"]
}
```

**Response (200):**

```json
{
  "status": "ok",
  "published": ["bai-viet-1", "bai-viet-3"],
  "pending": ["bai-viet-2"]
}
```

---

## Luồng xử lý Publish

```
Request đến
    │
    ▼
Xác thực API key (X-Api-Key → admins.api_token)
    │ Fail → 401
    ▼
Validate input
    │ Fail → 422
    ▼
Kiểm tra slug trùng (Blog::where slug)
    │ Trùng → 409
    ▼
Resolve category (tìm hoặc tạo mới)
    │
    ▼
Resolve tags (tìm hoặc tạo mới cho từng tag)
    │
    ▼
Upload content images → rewrite URL trong HTML
    │
    ▼
Blog::create (lưu DB)
    │
    ▼
Upload thumbnail (nếu có)
    │
    ▼
Response 201 + blog info
```

---

## Cấu trúc File

```
app/Http/Controllers/Api/
└── BlogPublishController.php    # Controller chính

routes/
└── api.php                      # Route definition (line 64-67)

app/Models/
├── Blog.php                     # Blog model
├── BlogCategory.php             # Category model
└── (Tag model)                  # Tag model

storage/app/public/
├── blogs/{id}/                  # Thumbnail storage
└── blogs/content/{slug}/        # Content images storage
```

---

## Xác thực (Authentication)

- Sử dụng API key truyền qua header `X-Api-Key`
- Key được so khớp với field `api_token` trong bảng `admins`
- Không sử dụng Sanctum/Passport — đây là stateless key-based auth

---

## Auto-resolve Category & Tags

Khi publish bài, category và tags được xử lý tự động:

- **Category**: tìm theo tên (case-insensitive). Nếu chưa có → tạo mới với `slug = Str::slug(name)`, `status = 1`, `locale = vi`
- **Tags**: tương tự category, mỗi tag được tìm hoặc tạo mới

Điều này cho phép client gửi tên category/tag dạng text mà không cần biết ID.

---

## Upload & Xử lý ảnh

### Thumbnail
- Lưu tại: `storage/app/public/blogs/{blog_id}/{random_40_chars}.{ext}`
- Cập nhật field `src` trong bảng `blogs`

### Content Images
- Lưu tại: `storage/app/public/blogs/content/{slug}/{original_filename}`
- Sau khi upload, tự động rewrite URL trong HTML:
  - `images/filename.png` → `/storage/blogs/content/{slug}/filename.png`
  - `./images/filename.png` → `/storage/blogs/content/{slug}/filename.png`

---

## Rate Limiting

```php
Route::post('/publish', ...)->middleware('throttle:10,1');  // 10 req/phút
Route::post('/status', ...)->middleware('throttle:60,1');   // 60 req/phút
```

- `publish`: 10 requests / 1 phút (write operation, nặng hơn)
- `status`: 60 requests / 1 phút (read-only, nhẹ)

---

## Ví dụ sử dụng

### cURL — Publish bài mới

```bash
curl -X POST https://lamgame.vn/api/blog/publish \
  -H "X-Api-Key: YOUR_API_TOKEN" \
  -F "title=Hướng dẫn Unity cơ bản" \
  -F "slug=huong-dan-unity-co-ban" \
  -F "description=<p>Nội dung bài viết...</p>" \
  -F "category=Game Development" \
  -F "tags[]=unity" \
  -F "tags[]=tutorial" \
  -F "meta_title=Hướng dẫn Unity cho người mới" \
  -F "thumbnail=@/path/to/thumbnail.jpg" \
  -F "images[]=@/path/to/screenshot1.png" \
  -F "images[]=@/path/to/screenshot2.png"
```

### cURL — Kiểm tra status

```bash
curl -X POST https://lamgame.vn/api/blog/status \
  -H "X-Api-Key: YOUR_API_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"slugs": ["bai-viet-1", "bai-viet-2"]}'
```

---

## Kết quả Test hiện tại

| Test                  | Kết quả | HTTP Code |
|-----------------------|---------|-----------|
| Publish bài mới       | ✅ OK   | 201       |
| Xem bài trên web      | ✅ OK   | 200       |
| Publish trùng slug    | ✅ Chặn | 409       |
| Sai API key           | ✅ Chặn | 401       |
| Check status           | ✅ OK   | 200       |

---

## Vấn đề đã biết & Đề xuất tối ưu

| #  | Vấn đề                              | Mức độ   | Trạng thái | Đề xuất                                                    |
|----|--------------------------------------|----------|------------|--------------------------------------------------------------|
| 1  | Không có DB transaction              | 🔴 Cao   | ✅ Done    | Wrap `Blog::create` + upload trong `DB::transaction`         |
| 2  | API key lưu plain text               | 🔴 Cao   | ✅ Done   | Hash bằng `hash('sha256', ...)` khi lưu và so sánh          |
| 3  | Extract auth middleware               | 🔴 Cao   | ✅ Done   | Tạo `ApiKeyAuth` middleware thay vì duplicate auth logic     |
| 4  | `status` endpoint không validate     | 🟡 Trung | ✅ Done   | Thêm validate `slugs` là `required|array`, items là `string`|
| 5  | Không hỗ trợ scheduled publish       | 🟡 Trung | ✅ Done   | Set `status=0` nếu `published_at` > now(), command auto-publish |
| 6  | Image rewrite chỉ match 2 pattern    | 🟡 Trung | ✅ Done   | Regex match: `images/`, `./images/`, `../images/`, `/images/`, `assets/images/` |
| 7  | Rate limit chung cho publish & status | 🟢 Thấp  | ✅ Done   | Tách riêng: publish `10/phút`, status `60/phút`             |
| 8  | Orphan files khi create fail         | 🟢 Thấp  | ✅ Done    | Cleanup uploaded files trong catch block                     |

---

## Changelog

| Ngày       | Thay đổi                                    |
|------------|----------------------------------------------|
| 2026-03-26 | Triển khai v1 — publish + status endpoints   |
| 2026-03-27 | Tạo tài liệu chức năng                      |
| 2026-03-27 | #1 DB transaction + #8 orphan file cleanup   |
| 2026-03-27 | #2 Hash API key (SHA-256) + migration        |
| 2026-03-27 | #3 Extract ApiKeyAuth middleware              |
| 2026-03-27 | #4 Validate status endpoint input             |
| 2026-03-27 | #5 Scheduled publish + blog:publish-scheduled |
| 2026-03-27 | #6 Image rewrite regex (7 patterns)           |
| 2026-03-27 | #7 Tách rate limit publish/status              |
