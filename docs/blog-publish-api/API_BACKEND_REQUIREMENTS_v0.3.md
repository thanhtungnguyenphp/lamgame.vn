# API Backend Requirements — Article Status System

> Yêu cầu bổ sung API endpoints cho OhHa Studio v0.3.0
> Ngày: 2026-03-29

## Tổng quan

Frontend đã implement hệ thống article status 4 trạng thái: `draft`, `scheduled`, `published`, `archived`. Cần backend hỗ trợ các endpoint mới và cập nhật endpoint hiện có.

---

## 1. Cập nhật endpoint hiện có

### POST `/api/blog/status`

**Thay đổi**: Response cần trả thêm các mảng `draft`, `scheduled`, `archived` ngoài `published` và `pending`.

**Request** (không đổi):
```json
{
  "slugs": ["slug-1", "slug-2", "slug-3"]
}
```

**Response MỚI**:
```json
{
  "status": "success",
  "published": ["slug-1"],
  "pending": ["slug-3"],
  "draft": ["slug-2"],
  "scheduled": [],
  "archived": []
}
```

**Lưu ý**:
- Mỗi slug chỉ xuất hiện trong đúng 1 mảng
- `pending` giữ lại cho backward compatibility (bài đang chờ duyệt hoặc chưa xác định status)
- Nếu slug không tồn tại, không đưa vào mảng nào

---

## 2. Endpoint mới: Change Article Status

### POST `/api/blog/status/{slug}`

Thay đổi trạng thái của một bài viết.

**Headers**:
```
X-Api-Key: {api_key}
Content-Type: application/json
```

**Request**:
```json
{
  "status": "published"
}
```

**Giá trị `status` hợp lệ**: `draft`, `scheduled`, `published`, `archived`

**Response thành công** (200):
```json
{
  "status": "success",
  "message": "Article status updated to published"
}
```

**Response lỗi** (404):
```json
{
  "status": "error",
  "message": "Article not found"
}
```

**Response lỗi** (422):
```json
{
  "status": "error",
  "message": "Invalid status value",
  "errors": {
    "status": ["Status must be one of: draft, scheduled, published, archived"]
  }
}
```

**Business rules**:
- Chuyển sang `scheduled` yêu cầu bài viết phải có `published_at` trong tương lai
- Chuyển sang `published` sẽ set `published_at = now()` nếu chưa có
- Chuyển sang `archived` giữ nguyên `published_at`
- Chuyển sang `draft` sẽ xóa `published_at`

---

## 3. Endpoint mới: List Articles

### GET `/api/blog/list`

Lấy danh sách bài viết có phân trang và filter.

**Headers**:
```
X-Api-Key: {api_key}
```

**Query params**:

| Param | Type | Default | Mô tả |
|-------|------|---------|-------|
| `page` | int | 1 | Trang hiện tại |
| `per_page` | int | 20 | Số bài/trang (max 100) |
| `status` | string | — | Filter theo status: `draft`, `scheduled`, `published`, `archived` |
| `category` | string | — | Filter theo category (exact match) |
| `search` | string | — | Tìm kiếm trong title và slug (LIKE %search%) |

**Response** (200):
```json
{
  "status": "success",
  "data": [
    {
      "slug": "bai-viet-1",
      "title": "Tiêu đề bài viết 1",
      "status": "published",
      "category": "tech",
      "published_at": "2026-03-28",
      "updated_at": "2026-03-28T10:30:00Z"
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

**Sắp xếp**: `updated_at DESC` (mới nhất trước)

---

## 4. Endpoint mới: Article Detail

### GET `/api/blog/detail/{slug}`

Lấy chi tiết đầy đủ một bài viết.

**Headers**:
```
X-Api-Key: {api_key}
```

**Response** (200):
```json
{
  "status": "success",
  "data": {
    "slug": "bai-viet-1",
    "title": "Tiêu đề bài viết",
    "description": "<p>Nội dung HTML đầy đủ...</p>",
    "short_description": "Mô tả ngắn",
    "category": "tech",
    "tags": ["tag1", "tag2"],
    "meta_title": "SEO Title",
    "meta_description": "SEO Description",
    "meta_keywords": "keyword1, keyword2",
    "status": "published",
    "published_at": "2026-03-28",
    "thumbnail_url": "https://example.com/images/thumb.webp",
    "created_at": "2026-03-27T08:00:00Z",
    "updated_at": "2026-03-28T10:30:00Z"
  }
}
```

**Response lỗi** (404):
```json
{
  "status": "error",
  "message": "Article not found"
}
```

---

## 5. Database Migration

Bảng `articles` cần cập nhật cột `status`:

```sql
-- Thay đổi cột status từ boolean sang enum/string
ALTER TABLE articles
  MODIFY COLUMN status ENUM('draft', 'scheduled', 'published', 'archived')
  DEFAULT 'draft' NOT NULL;

-- Migration data cũ (nếu status đang là boolean)
-- status = 1 (true)  → 'published'
-- status = 0 (false) → 'draft'
```

---

## 6. Tóm tắt endpoints

| Method | Endpoint | Mô tả | Trạng thái |
|--------|----------|-------|------------|
| POST | `/api/blog/publish` | Tạo bài mới | ✅ Có sẵn |
| POST | `/api/blog/update/{slug}` | Cập nhật bài | ✅ Có sẵn |
| DELETE | `/api/blog/delete/{slug}` | Xóa bài | ✅ Có sẵn |
| POST | `/api/blog/status` | Check status nhiều slug | ⚠️ Cần cập nhật response |
| POST | `/api/blog/status/{slug}` | Đổi status 1 bài | 🆕 Mới |
| GET | `/api/blog/list` | Danh sách + phân trang | 🆕 Mới |
| GET | `/api/blog/detail/{slug}` | Chi tiết bài viết | 🆕 Mới |
| GET | `/api/blog/status` (health) | Connection check | ✅ Có sẵn |
