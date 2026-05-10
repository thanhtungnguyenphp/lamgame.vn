# Forum Management API Guide

> Cập nhật: 2026-05-06 | Dựa trên code thực tế

## Tổng quan

API quản lý forum cho platform LamGame.

- **Base URL:** `/api/manage/forum/`
- **Auth:** Header `X-Api-Key: {admin_api_token}`
- **Rate limit:** Read 60/min, Write 10/min
- **Route file:** `routes/api-forum-manage.php`
- **Controller:** `ForumManageController`
- **Services:** ForumPostService, ForumCommentService, ForumReportService, ForumReputationService

## Endpoints Summary (28 total)

| # | Method | Endpoint | Description |
|---|--------|----------|-------------|
| 1 | GET | /dashboard | Thống kê forum |
| 2 | GET | /posts | Danh sách bài viết |
| 3 | GET | /posts/{id} | Chi tiết bài viết |
| 4 | POST | /posts | Tạo bài viết |
| 5 | PUT | /posts/{id} | Cập nhật bài viết |
| 6 | DELETE | /posts/{id} | Xóa bài viết |
| 7 | POST | /posts/{id}/status | Đổi trạng thái |
| 8 | PATCH | /posts/bulk/status | Bulk đổi trạng thái |
| 9 | DELETE | /posts/bulk | Bulk xóa |
| 10 | GET | /comments | Danh sách comments |
| 11 | GET | /comments/{id} | Chi tiết comment |
| 12 | POST | /comments/{id}/status | Đổi trạng thái comment |
| 13 | DELETE | /comments/{id} | Xóa comment |
| 14 | PATCH | /comments/bulk/status | Bulk đổi trạng thái |
| 15 | DELETE | /comments/bulk | Bulk xóa |
| 16 | GET | /categories | Danh sách categories |
| 17 | POST | /categories | Tạo category |
| 18 | PUT | /categories/{id} | Cập nhật category |
| 19 | DELETE | /categories/{id} | Xóa category |
| 20 | GET | /tags | Danh sách tags |
| 21 | POST | /tags | Tạo tag |
| 22 | PUT | /tags/{id} | Cập nhật tag |
| 23 | DELETE | /tags/{id} | Xóa tag |
| 24 | DELETE | /tags/bulk | Bulk xóa tags |
| 25 | GET | /reports | Danh sách reports |
| 26 | POST | /reports/{id}/resolve | Resolve report |
| 27 | PATCH | /reports/bulk/resolve | Bulk resolve |
| 28 | GET | /leaderboard | Bảng xếp hạng |

---

## 1. Dashboard

### GET /api/manage/forum/dashboard

```json
{
  "status": "success",
  "data": {
    "total_categories": 10,
    "total_tags": 50,
    "total_bookmarks": 200,
    "total_notifications": 500,
    "posts_last_7_days": 25,
    "comments_last_7_days": 80,
    "top_categories": [
      { "id": 1, "name": "Hỏi đáp", "posts_count": 150 }
    ]
  }
}
```

---

## 2. Posts

### GET /api/manage/forum/posts

**Query params:**
| Param | Type | Values |
|-------|------|--------|
| search | string | Tìm theo title/author_name |
| status | string | draft, published, hidden, locked |
| type | string | discussion, idea, question, showcase, job, review |
| category_id | int | ID category |
| is_featured | bool | Bài nổi bật |
| is_sticky | bool | Bài ghim |
| customer_id | int | ID tác giả |
| sort_by | string | created_at, views_count, comments_count, likes_count, hot_score, title |
| sort_dir | string | asc, desc |
| per_page | int | Default: 15 |

### GET /api/manage/forum/posts/{id}

Trả về: post data + category + tags + customer + comments_count + bookmarks_count.

### POST /api/manage/forum/posts

```json
{
  "title": "required|string|max:255",
  "content": "required|string",
  "category_id": "required|int|exists:forum_categories,id",
  "type": "nullable|in:discussion,idea,question,showcase,job,review",
  "status": "nullable|in:draft,published,hidden,locked",
  "tags": "nullable|array",
  "is_featured": "nullable|boolean",
  "is_sticky": "nullable|boolean",
  "meta_title": "nullable|string|max:255",
  "meta_description": "nullable|string|max:500",
  "meta_keywords": "nullable|string|max:255"
}
```

### PUT /api/manage/forum/posts/{id}

Tất cả fields optional. Cập nhật tags nếu có.

### DELETE /api/manage/forum/posts/{id}

Xóa post via ForumPostService.

### POST /api/manage/forum/posts/{id}/status

```json
{
  "status": "required|in:draft,published,hidden,locked",
  "is_featured": "nullable|boolean",
  "is_sticky": "nullable|boolean"
}
```

### PATCH /api/manage/forum/posts/bulk/status

```json
{
  "ids": [1, 2, 3],
  "status": "published"
}
```

### DELETE /api/manage/forum/posts/bulk

```json
{ "ids": [1, 2, 3] }
```

---

## 3. Comments

### GET /api/manage/forum/comments

**Query params:**
| Param | Type | Values |
|-------|------|--------|
| search | string | Tìm theo content/author_name |
| status | string | published, pending, hidden, spam |
| post_id | int | Lọc theo bài viết |
| is_best_answer | bool | Câu trả lời tốt nhất |
| is_root | bool | Comment gốc (không phải reply) |
| sort_by | string | created_at, likes_count, replies_count |

### GET /api/manage/forum/comments/{id}

Trả về: comment + post + customer + parent comment.

### POST /api/manage/forum/comments/{id}/status

```json
{ "status": "published|pending|hidden|spam" }
```

### DELETE /api/manage/forum/comments/{id}

### PATCH /api/manage/forum/comments/bulk/status

```json
{ "ids": [1, 2, 3], "status": "hidden" }
```

### DELETE /api/manage/forum/comments/bulk

```json
{ "ids": [1, 2, 3] }
```

---

## 4. Categories

### GET /api/manage/forum/categories

**Query params:** `is_active` (bool). Trả về ordered list (KHÔNG paginate).

### POST /api/manage/forum/categories

```json
{
  "name": "required|string|max:255",
  "description": "nullable|string",
  "icon": "nullable|string",
  "color": "nullable|string",
  "sort_order": "nullable|integer",
  "is_active": "nullable|boolean",
  "is_featured": "nullable|boolean"
}
```
Slug auto-generated từ name.

### PUT /api/manage/forum/categories/{id}

### DELETE /api/manage/forum/categories/{id}

**Blocked** nếu category có posts → HTTP 422.

---

## 5. Tags

### GET /api/manage/forum/tags

**Query params:** search (name), sort_by (name | posts_count).  
**Pagination:** default 50, max 200.

### POST /api/manage/forum/tags

```json
{ "name": "required|string|max:100", "color": "nullable|string" }
```
Checks slug uniqueness.

### PUT /api/manage/forum/tags/{id}

### DELETE /api/manage/forum/tags/{id}

Detach from posts trước khi xóa.

### DELETE /api/manage/forum/tags/bulk

```json
{ "ids": [1, 2, 3] }
```

---

## 6. Reports

### GET /api/manage/forum/reports

**Query params:** status, reason, type (post | comment).

### POST /api/manage/forum/reports/{id}/resolve

```json
{
  "status": "required|in:reviewed,resolved,dismissed",
  "notes": "nullable|string"
}
```

### PATCH /api/manage/forum/reports/bulk/resolve

```json
{
  "ids": [1, 2, 3],
  "status": "resolved",
  "notes": "nullable"
}
```

---

## 7. Leaderboard

### GET /api/manage/forum/leaderboard

**Query params:**
- `period`: all | month (default: all)
- `limit`: int (default: 20, max: 100)

---

## Enums

### Post Types
- `discussion` - Thảo luận chung
- `idea` - Ý tưởng
- `question` - Câu hỏi
- `showcase` - Showcase dự án
- `job` - Tuyển dụng
- `review` - Đánh giá

### Post Statuses
- `draft` - Nháp
- `published` - Đã xuất bản
- `hidden` - Ẩn
- `locked` - Khóa (không cho comment)

### Comment Statuses
- `published` - Hiển thị
- `pending` - Chờ duyệt
- `hidden` - Ẩn
- `spam` - Spam

### Report Statuses
- `pending` - Chờ xử lý
- `reviewed` - Đã xem
- `resolved` - Đã giải quyết
- `dismissed` - Bỏ qua

---

## Database Tables

- `forum_posts` - Bài viết
- `forum_comments` - Bình luận (nested via parent_id)
- `forum_categories` - Danh mục
- `forum_tags` + `forum_post_tags` - Tags (many-to-many)
- `forum_votes` - Upvote/downvote
- `forum_reports` - Báo cáo vi phạm (polymorphic)
- `forum_bookmarks` - Bookmark bài viết
- `forum_notifications` - Thông báo
- `forum_reputation_logs` - Lịch sử reputation
