# Forum Management API — Ohha Studio Integration

> Base URL: `https://lamgame.vn/api/manage/forum`
> Auth: Header `X-Api-Key: {admin_api_token}`
> Rate Limit: 60 req/min (read), 10 req/min (write)

---

## Endpoints tổng quan (27 endpoints)

| # | Method | Endpoint | Mô tả |
|---|--------|----------|--------|
| | **Dashboard** | | |
| 1 | GET | `/dashboard` | Thống kê tổng quan forum |
| | **Posts** | | |
| 2 | GET | `/posts` | Danh sách bài viết (filter, sort, paginate) |
| 3 | GET | `/posts/{id}` | Chi tiết bài viết |
| 4 | POST | `/posts` | Tạo bài viết |
| 5 | PUT | `/posts/{id}` | Cập nhật bài viết |
| 6 | DELETE | `/posts/{id}` | Xóa bài viết |
| 7 | POST | `/posts/{id}/status` | Đổi trạng thái bài viết |
| 8 | PATCH | `/posts/bulk/status` | Đổi trạng thái hàng loạt |
| 9 | DELETE | `/posts/bulk` | Xóa hàng loạt |
| | **Comments** | | |
| 10 | GET | `/comments` | Danh sách bình luận |
| 11 | GET | `/comments/{id}` | Chi tiết bình luận |
| 12 | POST | `/comments/{id}/status` | Đổi trạng thái bình luận |
| 13 | DELETE | `/comments/{id}` | Xóa bình luận |
| 14 | PATCH | `/comments/bulk/status` | Đổi trạng thái hàng loạt |
| 15 | DELETE | `/comments/bulk` | Xóa hàng loạt |
| | **Categories** | | |
| 16 | GET | `/categories` | Danh sách danh mục |
| 17 | POST | `/categories` | Tạo danh mục |
| 18 | PUT | `/categories/{id}` | Cập nhật danh mục |
| 19 | DELETE | `/categories/{id}` | Xóa danh mục |
| | **Tags** | | |
| 20 | GET | `/tags` | Danh sách tags |
| 21 | POST | `/tags` | Tạo tag |
| 22 | PUT | `/tags/{id}` | Cập nhật tag |
| 23 | DELETE | `/tags/{id}` | Xóa tag |
| 24 | DELETE | `/tags/bulk` | Xóa hàng loạt |
| | **Reports** | | |
| 25 | GET | `/reports` | Danh sách báo cáo vi phạm |
| 26 | POST | `/reports/{id}/resolve` | Xử lý báo cáo |
| 27 | PATCH | `/reports/bulk/resolve` | Xử lý hàng loạt |
| | **Leaderboard** | | |
| 28 | GET | `/leaderboard` | Bảng xếp hạng reputation |

---

## Chi tiết API

### 1. Dashboard

```
GET /api/manage/forum/dashboard
```

**Response:**
```json
{
  "status": "success",
  "data": {
    "total_posts": 150,
    "published_posts": 120,
    "pending_posts": 5,
    "total_comments": 890,
    "published_comments": 850,
    "pending_comments": 10,
    "pending_reports": 3,
    "total_reports": 15,
    "total_categories": 8,
    "total_tags": 45,
    "total_bookmarks": 230,
    "total_notifications": 1200,
    "posts_last_7_days": 12,
    "comments_last_7_days": 45,
    "top_categories": [
      {"id": 1, "name": "Thảo luận", "slug": "thao-luan", "posts_count": 50, "comments_count": 200}
    ]
  }
}
```

---

### 2. Posts — List

```
GET /api/manage/forum/posts
```

| Param | Type | Mô tả |
|-------|------|--------|
| search | string | Tìm theo title, author_name |
| status | string | `draft`, `published`, `hidden`, `locked` |
| type | string | `discussion`, `idea`, `question`, `showcase`, `job`, `review` |
| category_id | int | Filter theo danh mục |
| customer_id | int | Filter theo tác giả |
| is_featured | bool | Bài nổi bật |
| is_sticky | bool | Bài ghim |
| sort_by | string | `created_at`, `views_count`, `comments_count`, `likes_count`, `hot_score`, `title` |
| sort_dir | string | `asc` / `desc` (default: desc) |
| per_page | int | 1-100 (default: 15) |
| page | int | Trang |

**Response:**
```json
{
  "status": "success",
  "data": [
    {
      "id": 1,
      "title": "Hướng dẫn Unity cho người mới",
      "slug": "huong-dan-unity-cho-nguoi-moi",
      "type": "discussion",
      "status": "published",
      "is_featured": false,
      "is_sticky": false,
      "views_count": 150,
      "comments_count": 12,
      "likes_count": 8,
      "author_name": "Nguyen Van A",
      "customer_id": 5,
      "category": {"id": 1, "name": "Thảo luận", "slug": "thao-luan"},
      "tags": [{"id": 1, "name": "Unity", "slug": "unity"}],
      "created_at": "2026-04-20T10:00:00.000000Z"
    }
  ],
  "meta": {
    "current_page": 1,
    "last_page": 10,
    "per_page": 15,
    "total": 150
  }
}
```

---

### 3. Posts — Detail

```
GET /api/manage/forum/posts/{id}
```

Trả về đầy đủ thông tin bài viết + category + tags + customer + counts (comments, bookmarks).

---

### 4. Posts — Create

```
POST /api/manage/forum/posts
Content-Type: application/json
```

| Field | Type | Required | Mô tả |
|-------|------|----------|--------|
| title | string | ✅ | Tiêu đề (max 255) |
| content | string | ✅ | Nội dung HTML |
| category_id | int | ✅ | ID danh mục |
| type | string | | `discussion` (default), `idea`, `question`, `showcase`, `job`, `review` |
| status | string | | `published` (default), `draft`, `hidden`, `locked` |
| tags | string | | Comma-separated: `"Unity, C#, 2D"` |
| is_featured | bool | | Default: false |
| is_sticky | bool | | Default: false |
| meta_title | string | | SEO title |
| meta_description | string | | SEO description |
| meta_keywords | string | | SEO keywords |

---

### 5. Posts — Update

```
PUT /api/manage/forum/posts/{id}
```

Cùng fields như Create, tất cả optional. Chỉ gửi fields cần cập nhật.

---

### 6. Posts — Delete

```
DELETE /api/manage/forum/posts/{id}
```

---

### 7. Posts — Change Status

```
POST /api/manage/forum/posts/{id}/status
```

| Field | Type | Required | Mô tả |
|-------|------|----------|--------|
| status | string | ✅ | `draft`, `published`, `hidden`, `locked` |
| is_featured | bool | | Bài nổi bật |
| is_sticky | bool | | Bài ghim |

---

### 8-9. Posts — Bulk Operations

```
PATCH /api/manage/forum/posts/bulk/status
DELETE /api/manage/forum/posts/bulk
```

| Field | Type | Required |
|-------|------|----------|
| ids | int[] | ✅ |
| status | string | ✅ (chỉ bulk status) |

---

### 10-15. Comments

**List:** `GET /comments`

| Param | Type | Mô tả |
|-------|------|--------|
| search | string | Tìm theo content, author_name |
| status | string | `published`, `pending`, `hidden`, `spam` |
| post_id | int | Filter theo bài viết |
| is_best_answer | bool | Câu trả lời tốt nhất |
| is_root | bool | true = root comments, false = replies |
| sort_by | string | `created_at`, `likes_count`, `replies_count` |

**Change Status:** `POST /comments/{id}/status`
```json
{"status": "published"}
```

**Bulk:** Tương tự posts.

---

### 16-19. Categories

**List:** `GET /categories` — Param: `is_active` (bool)

**Create:** `POST /categories`

| Field | Type | Required |
|-------|------|----------|
| name | string | ✅ |
| description | string | |
| icon | string | Emoji hoặc icon class |
| color | string | Hex color (default: #667eea) |
| sort_order | int | |
| is_active | bool | Default: true |
| is_featured | bool | Default: false |

**Update:** `PUT /categories/{id}` — Cùng fields, tất cả optional.

**Delete:** `DELETE /categories/{id}` — Lỗi 422 nếu danh mục còn bài viết.

---

### 20-24. Tags

**List:** `GET /tags`

| Param | Type | Mô tả |
|-------|------|--------|
| search | string | Tìm theo tên |
| sort_by | string | `name` (default) / `posts_count` |
| per_page | int | 1-200 (default: 50) |

**Create:** `POST /tags` — `name` (required), `color` (optional)

**Update:** `PUT /tags/{id}` — `name`, `color`

**Delete:** `DELETE /tags/{id}` — Tự detach khỏi posts.

**Bulk Delete:** `DELETE /tags/bulk` — `{"ids": [1, 2, 3]}`

---

### 25-27. Reports

**List:** `GET /reports`

| Param | Type | Mô tả |
|-------|------|--------|
| status | string | `pending`, `reviewed`, `resolved`, `dismissed` |
| reason | string | `spam`, `inappropriate`, `harassment`, `copyright`, `other` |
| type | string | `post` / `comment` |

**Resolve:** `POST /reports/{id}/resolve`

| Field | Type | Required |
|-------|------|----------|
| status | string | ✅ `reviewed`, `resolved`, `dismissed` |
| notes | string | | Ghi chú admin |

**Bulk Resolve:** `PATCH /reports/bulk/resolve` — `{"ids": [...], "status": "resolved"}`

---

### 28. Leaderboard

```
GET /api/manage/forum/leaderboard?period=all&limit=20
```

| Param | Type | Mô tả |
|-------|------|--------|
| period | string | `all` (default) / `month` |
| limit | int | 1-100 (default: 20) |

---

## Response Format

**Success:**
```json
{"status": "success", "data": {...}, "meta": {...}}
```

**Error:**
```json
{"status": "error", "message": "Mô tả lỗi bằng tiếng Việt"}
```

**HTTP Status Codes:**
- `200` — OK
- `201` — Created
- `401` — Invalid API key
- `404` — Not found
- `422` — Validation error
- `429` — Rate limit exceeded
- `500` — Server error

---

## Ví dụ cURL

```bash
# Dashboard
curl -H "X-Api-Key: YOUR_KEY" https://lamgame.vn/api/manage/forum/dashboard

# List posts (published, sorted by views)
curl -H "X-Api-Key: YOUR_KEY" "https://lamgame.vn/api/manage/forum/posts?status=published&sort_by=views_count&per_page=20"

# Create post
curl -X POST -H "X-Api-Key: YOUR_KEY" -H "Content-Type: application/json" \
  -d '{"title":"Test post","content":"<p>Hello</p>","category_id":1,"tags":"Unity,C#"}' \
  https://lamgame.vn/api/manage/forum/posts

# Bulk hide posts
curl -X PATCH -H "X-Api-Key: YOUR_KEY" -H "Content-Type: application/json" \
  -d '{"ids":[1,2,3],"status":"hidden"}' \
  https://lamgame.vn/api/manage/forum/posts/bulk/status

# Resolve report
curl -X POST -H "X-Api-Key: YOUR_KEY" -H "Content-Type: application/json" \
  -d '{"status":"resolved","notes":"Đã xử lý spam"}' \
  https://lamgame.vn/api/manage/forum/reports/5/resolve
```
