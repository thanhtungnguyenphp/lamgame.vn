# LAMGAME Blog API — Tài liệu tích hợp

**Base URL:** `https://lamgame.vn/api/blog`
**Version:** 2.0
**Cập nhật:** 2026-03-27

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

## Rate Limiting

| Endpoint          | Giới hạn     |
|-------------------|-------------|
| publish, update, delete | 10 request / phút |
| status            | 60 request / phút |

Khi vượt giới hạn → HTTP `429 Too Many Requests`.

---

## Mã lỗi chung

| HTTP Code | Ý nghĩa                  | Response mẫu                                                |
|-----------|---------------------------|--------------------------------------------------------------|
| 401       | API key sai hoặc thiếu    | `{"status":"error","message":"Invalid API key"}`             |
| 404       | Blog không tồn tại        | `{"status":"error","message":"Blog not found"}`              |
| 409       | Slug đã tồn tại           | `{"status":"skipped","message":"Blog '...' already exists"}` |
| 422       | Dữ liệu không hợp lệ     | `{"message":"...","errors":{...}}`                           |
| 429       | Vượt rate limit            | Throttle response mặc định của Laravel                       |
| 500       | Lỗi server                | `{"message":"Server Error"}`                                 |

---

## 1. Publish — Tạo bài viết mới

```
POST /api/blog/publish
Content-Type: multipart/form-data
```

### Request Body

| Field             | Type     | Bắt buộc | Mô tả                                                       |
|-------------------|----------|----------|---------------------------------------------------------------|
| title             | string   | ✅       | Tiêu đề bài viết (max 500 ký tự)                             |
| slug              | string   | ✅       | URL slug, phải unique (max 500 ký tự)                         |
| description       | string   | ✅       | Nội dung HTML đầy đủ                                          |
| short_description | string   | ❌       | Mô tả ngắn (mặc định = meta_description)                     |
| category          | string   | ✅       | Tên category — tự tạo mới nếu chưa tồn tại                  |
| tags              | array    | ❌       | Danh sách tên tag — tự tạo mới nếu chưa tồn tại             |
| meta_title        | string   | ❌       | SEO title (mặc định = title)                                 |
| meta_description  | string   | ❌       | SEO description                                               |
| meta_keywords     | string   | ❌       | SEO keywords                                                  |
| published_at      | date     | ❌       | Ngày xuất bản. Nếu > ngày hiện tại → bài ở trạng thái chờ (scheduled). Mặc định = now |
| thumbnail         | file     | ❌       | Ảnh đại diện (jpg, jpeg, png, webp, svg — max 5MB)           |
| images[]          | file[]   | ❌       | Ảnh nội dung (jpg, jpeg, png, webp, gif, svg — max 10MB/ảnh) |

### Xử lý đặc biệt

- **Category & Tags**: gửi tên text, hệ thống tự tìm (case-insensitive) hoặc tạo mới
- **Scheduled publish**: nếu `published_at` là ngày tương lai → `status=0` (ẩn), hệ thống tự publish khi đến giờ (kiểm tra mỗi 5 phút)
- **Image rewrite**: URL ảnh trong HTML tự động được rewrite. Hỗ trợ các dạng path:
  - `images/file.png`
  - `./images/file.png`
  - `../images/file.png`
  - `/images/file.png`
  - `assets/images/file.png`

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
  -F "images[]=@/path/to/screenshot1.png" \
  -F "images[]=@/path/to/screenshot2.png"
```

---

## 2. Update — Cập nhật bài viết

```
POST /api/blog/update/{slug}
Content-Type: multipart/form-data
```

### URL Parameters

| Param | Type   | Mô tả                    |
|-------|--------|---------------------------|
| slug  | string | Slug của bài viết cần sửa |

### Request Body

Tất cả field đều **optional** — chỉ gửi field cần cập nhật (partial update).

| Field             | Type     | Mô tả                                                       |
|-------------------|----------|---------------------------------------------------------------|
| title             | string   | Tiêu đề mới (max 500 ký tự)                                  |
| description       | string   | Nội dung HTML mới                                             |
| short_description | string   | Mô tả ngắn mới                                               |
| category          | string   | Tên category mới                                              |
| tags              | array    | Danh sách tag mới (thay thế toàn bộ tag cũ)                  |
| meta_title        | string   | SEO title mới                                                 |
| meta_description  | string   | SEO description mới                                           |
| meta_keywords     | string   | SEO keywords mới                                              |
| published_at      | date     | Ngày xuất bản mới                                             |
| status            | boolean  | `true` = hiển thị, `false` = ẩn                               |
| thumbnail         | file     | Ảnh đại diện mới (thay thế ảnh cũ, jpg/jpeg/png/webp/svg, max 5MB) |
| images[]          | file[]   | Ảnh nội dung mới (jpg/jpeg/png/webp/gif/svg, max 10MB/ảnh)   |

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
# Chỉ cập nhật title và description
curl -X POST https://lamgame.vn/api/blog/update/huong-dan-unity-co-ban \
  -H "X-Api-Key: YOUR_API_KEY" \
  -F "title=Hướng dẫn Unity cơ bản (Cập nhật 2026)" \
  -F "description=<p>Nội dung đã cập nhật...</p>"

# Ẩn bài viết
curl -X POST https://lamgame.vn/api/blog/update/huong-dan-unity-co-ban \
  -H "X-Api-Key: YOUR_API_KEY" \
  -F "status=0"

# Thay thumbnail
curl -X POST https://lamgame.vn/api/blog/update/huong-dan-unity-co-ban \
  -H "X-Api-Key: YOUR_API_KEY" \
  -F "thumbnail=@/path/to/new-thumbnail.jpg"
```

---

## 3. Delete — Xóa bài viết

```
DELETE /api/blog/delete/{slug}
```

### URL Parameters

| Param | Type   | Mô tả                    |
|-------|--------|---------------------------|
| slug  | string | Slug của bài viết cần xóa |

Xóa bài viết + toàn bộ file liên quan (thumbnail + content images).

### Response thành công

```json
// HTTP 200
{
  "status": "ok",
  "message": "Blog deleted"
}
```

### Ví dụ cURL

```bash
curl -X DELETE https://lamgame.vn/api/blog/delete/huong-dan-unity-co-ban \
  -H "X-Api-Key: YOUR_API_KEY"
```

---

## 4. Status — Kiểm tra trạng thái bài viết

```
POST /api/blog/status
Content-Type: application/json
```

Kiểm tra danh sách slug đã được publish hay chưa. Hữu ích khi import batch để biết bài nào đã tồn tại.

### Request Body (JSON)

| Field  | Type     | Bắt buộc | Mô tả                                    |
|--------|----------|----------|-------------------------------------------|
| slugs  | string[] | ✅       | Danh sách slug cần kiểm tra (max 100 items, mỗi item max 500 ký tự) |

### Response thành công

```json
// HTTP 200
{
  "status": "ok",
  "published": ["bai-viet-1", "bai-viet-3"],
  "pending": ["bai-viet-2"]
}
```

- `published`: các slug đã tồn tại trong hệ thống
- `pending`: các slug chưa tồn tại

### Ví dụ cURL

```bash
curl -X POST https://lamgame.vn/api/blog/status \
  -H "X-Api-Key: YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"slugs": ["bai-viet-1", "bai-viet-2", "bai-viet-3"]}'
```

---

## Audit Log

Mọi thao tác publish/update/delete đều được ghi log vào bảng `blog_api_logs`:

| Field      | Mô tả                                    |
|------------|-------------------------------------------|
| admin_id   | ID admin thực hiện                        |
| action     | `publish`, `update`, `delete`             |
| slug       | Slug bài viết                             |
| blog_id    | ID bài viết                               |
| ip         | IP address của request                    |
| changes    | Danh sách field đã thay đổi (cho update)  |
| created_at | Thời gian thực hiện                       |

---

## Scheduled Publish

Khi tạo bài với `published_at` là ngày tương lai:
- Bài được lưu với `status=0` (ẩn, không hiển thị trên web)
- Hệ thống kiểm tra mỗi 5 phút và tự động chuyển `status=1` khi đến giờ
- Có thể override bằng cách gửi `status=true` trong update endpoint

---

## Tổng hợp Endpoints

| Method | Endpoint                  | Chức năng          | Rate Limit   | Content-Type         |
|--------|---------------------------|--------------------|-------------|----------------------|
| POST   | `/api/blog/publish`       | Tạo bài mới        | 10 req/phút | `multipart/form-data` |
| POST   | `/api/blog/update/{slug}` | Cập nhật bài        | 10 req/phút | `multipart/form-data` |
| DELETE | `/api/blog/delete/{slug}` | Xóa bài             | 10 req/phút | —                    |
| POST   | `/api/blog/status`        | Kiểm tra slug       | 60 req/phút | `application/json`   |
