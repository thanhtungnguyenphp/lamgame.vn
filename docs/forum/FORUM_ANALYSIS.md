# LAMGAME.VN — Phân Tích Hệ Thống Forum

> Tài liệu phân tích toàn diện hệ thống Forum của dự án LAMGAME.VN (Laravel Bagisto)
> Ngày tạo: 28/04/2026

---

## Mục lục

1. [Tổng quan hệ thống](#1-tổng-quan-hệ-thống)
2. [Database Schema](#2-database-schema)
3. [Models & Relationships](#3-models--relationships)
4. [Controllers & Endpoints](#4-controllers--endpoints)
5. [Services Layer](#5-services-layer)
6. [Middleware & Security](#6-middleware--security)
7. [API Resources](#7-api-resources)
8. [Artisan Commands](#8-artisan-commands)
9. [Views (Blade Templates)](#9-views-blade-templates)
10. [Business Logic & Flows](#10-business-logic--flows)
11. [Cấu trúc code hiện tại](#11-cấu-trúc-code-hiện-tại)
12. [Những gì đã có vs chưa có](#12-những-gì-đã-có-vs-chưa-có)
13. [Đề xuất API Management cho Ohha Studio](#13-đề-xuất-api-management-cho-ohha-studio)

---

## 1. Tổng quan hệ thống

### Kiến trúc tổng thể

Forum LAMGAME.VN được xây dựng trên nền tảng Laravel Bagisto với kiến trúc:

- **MVC + Service Layer**: Controller → Service → Model
- **Dual interface**: Web (Blade views) + REST API (Sanctum auth)
- **Polymorphic relationships**: Votes và Reports dùng morphMany/morphTo
- **Customer-based auth**: Sử dụng Bagisto Customer model (không phải User), guard `customer`
- **Admin API**: Pattern riêng với X-Api-Key auth (file `api-ecommerce-manage.php`)

### Tính năng chính

| Tính năng | Trạng thái |
|-----------|-----------|
| CRUD bài viết (6 loại: discussion, idea, question, showcase, job, review) | ✅ Hoàn thành |
| Bình luận lồng nhau (nested comments) | ✅ Hoàn thành |
| Vote (like/dislike) cho post & comment | ✅ Hoàn thành |
| Danh mục & Tags | ✅ Hoàn thành |
| Tìm kiếm FULLTEXT | ✅ Hoàn thành |
| Bookmark bài viết | ✅ Hoàn thành |
| Thông báo (notifications) | ✅ Hoàn thành |
| Hệ thống Reputation & Badges | ✅ Hoàn thành |
| Best Answer (cho bài dạng question) | ✅ Hoàn thành |
| Report (báo cáo vi phạm) | ✅ Hoàn thành |
| Hot Score & Trending | ✅ Hoàn thành |
| Leaderboard | ✅ Hoàn thành |
| Honeypot anti-spam | ✅ Hoàn thành |
| Rate limiting | ✅ Hoàn thành |
| Edit history tracking | ✅ Hoàn thành |
| @Mention trong comment | ✅ Hoàn thành |
| User Profile (posts, comments) | ✅ Hoàn thành |

### Config (`config/forum.php`)

```php
'posts_per_page'        => 15,
'comments_per_page'     => 20,
'max_tags_per_post'     => 10,
'max_comment_depth'     => 5,
'auto_lock_after_days'  => 90,
'rate_limits' => [
    'posts'    => 5,   // per hour
    'comments' => 30,  // per hour
    'votes'    => 60,  // per hour
    'reports'  => 10,  // per hour
],
'cooldown_seconds'      => 120,
'honeypot_field'        => 'website_url',
```

---

## 2. Database Schema

### 2.1. `forum_categories`

| Cột | Kiểu | Mô tả |
|-----|------|-------|
| id | bigint PK | |
| name | varchar | Tên danh mục |
| slug | varchar UNIQUE | URL slug |
| description | varchar NULL | Mô tả |
| icon | varchar NULL | Emoji hoặc icon class |
| color | varchar(7) | Hex color, default `#667eea` |
| sort_order | int | Thứ tự sắp xếp |
| is_active | boolean | Trạng thái hoạt động |
| is_featured | boolean | Nổi bật |
| posts_count | int | Cache đếm bài viết |
| comments_count | int | Cache đếm bình luận |
| created_at, updated_at | timestamp | |

**Indexes**: `(is_active, sort_order)`, `(slug)`

### 2.2. `forum_posts`

| Cột | Kiểu | Mô tả |
|-----|------|-------|
| id | bigint PK | |
| customer_id | uint NULL FK→customers | Tác giả (added 28/04/2026) |
| title | varchar | Tiêu đề |
| slug | varchar UNIQUE | URL slug (auto-generated) |
| content | text | Nội dung HTML |
| excerpt | text NULL | Tóm tắt (auto 160 chars) |
| views | int | Views count (migration 2025_09_26) |
| hot_score | int | Điểm trending (cron tính) |
| type | enum | `discussion, idea, question, showcase, job, review` |
| author_name | varchar | Tên tác giả (legacy) |
| author_email | varchar NULL | Email tác giả (legacy) |
| author_avatar | varchar NULL | Avatar URL |
| category_id | bigint FK→forum_categories | Danh mục |
| status | enum | `draft, published, hidden, locked` |
| is_featured | boolean | Bài nổi bật |
| is_sticky | boolean | Bài ghim |
| views_count | int | Lượt xem |
| comments_count | int | Cache đếm comment |
| likes_count | int | Cache đếm like |
| dislikes_count | int | Cache đếm dislike |
| meta_title | varchar NULL | SEO title |
| meta_description | varchar NULL | SEO description |
| meta_keywords | varchar NULL | SEO keywords |
| edit_history | json NULL | Lịch sử chỉnh sửa |
| ip_address | varchar NULL | IP tạo bài |
| user_agent | text NULL | User agent |
| last_comment_at | timestamp NULL | Thời gian comment cuối |
| last_comment_author | varchar NULL | Tên người comment cuối |
| created_at, updated_at | timestamp | |

**Indexes**: `(status, is_featured, created_at)`, `(category_id, status)`, `(slug)`, `(author_name, created_at)`, `(last_comment_at)`, `(hot_score, created_at)`, `(views)`, `(customer_id)`, **FULLTEXT** `(title, content)`

> **Lưu ý**: Có 2 cột views (`views` từ migration 2025_09_26 và `views_count` từ migration gốc). Code sử dụng `views_count`.

### 2.3. `forum_comments`

| Cột | Kiểu | Mô tả |
|-----|------|-------|
| id | bigint PK | |
| post_id | bigint FK→forum_posts | Bài viết |
| customer_id | uint NULL FK→customers | Tác giả (added 28/04/2026) |
| parent_id | bigint NULL FK→forum_comments | Comment cha (nested) |
| content | text | Nội dung |
| author_name | varchar | Tên (legacy) |
| author_email | varchar NULL | Email (legacy) |
| author_avatar | varchar NULL | Avatar |
| author_website | varchar NULL | Website |
| status | enum | `published, pending, hidden, spam` |
| is_best_answer | boolean | Câu trả lời tốt nhất (added 28/04/2026) |
| likes_count | int | Cache like |
| dislikes_count | int | Cache dislike |
| replies_count | int | Cache replies |
| metadata | json NULL | Dữ liệu bổ sung |
| ip_address | varchar NULL | IP |
| user_agent | varchar NULL | User agent |
| created_at, updated_at | timestamp | |

**Indexes**: `(post_id, status, created_at)`, `(parent_id, created_at)`, `(author_name, created_at)`, `(status)`, `(customer_id)`

### 2.4. `forum_tags`

| Cột | Kiểu | Mô tả |
|-----|------|-------|
| id | bigint PK | |
| name | varchar | Tên tag |
| slug | varchar UNIQUE | URL slug |
| description | varchar NULL | Mô tả |
| color | varchar(7) | Hex color |
| posts_count | int | Cache đếm bài |
| is_featured | boolean | Nổi bật |
| created_at, updated_at | timestamp | |

### 2.5. `forum_post_tags` (Pivot)

| Cột | Kiểu | Mô tả |
|-----|------|-------|
| id | bigint PK | |
| post_id | bigint FK→forum_posts | |
| tag_id | bigint FK→forum_tags | |
| created_at, updated_at | timestamp | |

**Unique**: `(post_id, tag_id)`

### 2.6. `forum_votes` (Polymorphic)

| Cột | Kiểu | Mô tả |
|-----|------|-------|
| id | bigint PK | |
| customer_id | uint NULL FK→customers | Người vote (added 28/04/2026) |
| voteable_type | varchar | `App\Models\ForumPost` hoặc `App\Models\ForumComment` |
| voteable_id | bigint | ID của post/comment |
| voter_identifier | varchar | IP, email, hoặc customer ID |
| vote_type | enum | `like, dislike` |
| ip_address | varchar NULL | |
| user_agent | varchar NULL | |
| created_at, updated_at | timestamp | |

**Unique**: `(voteable_type, voteable_id, voter_identifier)`

### 2.7. `forum_reports` (Polymorphic)

| Cột | Kiểu | Mô tả |
|-----|------|-------|
| id | bigint PK | |
| reporter_id | uint FK→customers | Người báo cáo |
| reportable_type | varchar | `post` hoặc `comment` |
| reportable_id | bigint | ID |
| reason | varchar | `spam, inappropriate, harassment, copyright, other` |
| description | text NULL | Mô tả chi tiết |
| status | enum | `pending, reviewed, resolved, dismissed` |
| reviewed_by | uint NULL FK→admins | Admin xử lý |
| admin_notes | text NULL | Ghi chú admin |
| reviewed_at | timestamp NULL | Thời gian xử lý |
| ip_address | varchar NULL | |
| created_at, updated_at | timestamp | |

### 2.8. `forum_bookmarks` (Added 28/04/2026)

| Cột | Kiểu | Mô tả |
|-----|------|-------|
| id | bigint PK | |
| customer_id | uint FK→customers | |
| post_id | bigint FK→forum_posts | |
| created_at, updated_at | timestamp | |

**Unique**: `(customer_id, post_id)`

### 2.9. `forum_notifications` (Added 28/04/2026)

| Cột | Kiểu | Mô tả |
|-----|------|-------|
| id | bigint PK | |
| customer_id | uint FK→customers | Người nhận |
| type | varchar(50) | `reply_post, reply_comment, vote, mention, best_answer` |
| title | varchar | Tiêu đề thông báo |
| body | text NULL | Nội dung |
| url | varchar NULL | Link đến nội dung |
| data | json NULL | Dữ liệu bổ sung |
| read_at | timestamp NULL | Thời gian đọc |
| created_at, updated_at | timestamp | |

**Index**: `(customer_id, read_at, created_at)`

### 2.10. `forum_reputation_logs` (Added 28/04/2026)

| Cột | Kiểu | Mô tả |
|-----|------|-------|
| id | bigint PK | |
| customer_id | uint FK→customers | |
| points | smallint | Điểm (+/-) |
| action | varchar(50) | `post_created, comment_created, vote_like, vote_dislike, best_answer, post_removed` |
| reference_type | varchar NULL | Morphable type |
| reference_id | bigint NULL | Morphable ID |
| created_at, updated_at | timestamp | |

### 2.11. Cột bổ sung trên `customers` table

| Cột | Kiểu | Mô tả |
|-----|------|-------|
| forum_role | enum(`user`, `moderator`) | Vai trò forum |
| bio | text NULL | Tiểu sử |
| avatar_url | varchar NULL | Avatar |
| reputation | int default 0 | Tổng điểm reputation |
| banned_until | timestamp NULL | Thời gian ban |
| ban_reason | text NULL | Lý do ban |
| banned_by | uint NULL FK→admins | Admin ban |

### Sơ đồ quan hệ

```
customers ──1:N──> forum_posts ──1:N──> forum_comments (self-referencing parent_id)
    │                   │                      │
    │                   ├──N:M──> forum_tags (via forum_post_tags)
    │                   │
    │                   ├──1:N──> forum_votes (morphMany)
    │                   │         forum_comments ──1:N──> forum_votes (morphMany)
    │                   │
    │                   ├──1:N──> forum_bookmarks
    │                   │
    │                   └──belongs_to──> forum_categories
    │
    ├──1:N──> forum_bookmarks
    ├──1:N──> forum_notifications
    ├──1:N──> forum_reputation_logs
    └──1:N──> forum_reports (as reporter)

admins ──1:N──> forum_reports (as reviewer)
```

---

## 3. Models & Relationships

### 3.1. `ForumCategory`

- **Relationships**: `posts()` → hasMany ForumPost, `publishedPosts()`, `featuredPosts()`
- **Scopes**: `active()`, `featured()`, `ordered()`
- **Methods**: `updateCounts()` — cập nhật posts_count và comments_count
- **Route key**: `slug`

### 3.2. `ForumPost`

- **Relationships**:
  - `customer()` → belongsTo Customer (Bagisto CustomerProxy)
  - `category()` → belongsTo ForumCategory
  - `comments()` → hasMany ForumComment
  - `publishedComments()`, `rootComments()` — filtered hasMany
  - `tags()` → belongsToMany ForumTag (pivot: forum_post_tags)
  - `bookmarks()` → hasMany ForumBookmark
  - `votes()` → morphMany ForumVote
  - `likes()`, `dislikes()` — filtered morphMany
- **Scopes**: `published()`, `featured()`, `sticky()`, `ofType($type)`, `byActivity()`, `popular()`, `search($search)`
- **Methods**:
  - `isBookmarkedBy(?int $customerId)` — kiểm tra bookmark
  - `bestAnswer()` — lấy comment best answer
  - `incrementViews()` — tăng views_count
  - `updateCommentStats()` — cập nhật comments_count, last_comment_at, last_comment_author + category counts
  - `updateVoteStats()` — cập nhật likes_count, dislikes_count
- **Accessors**: `url`, `time_ago`, `last_activity`
- **Boot events**: Auto-generate slug và excerpt khi creating/updating
- **Route key**: `slug`

### 3.3. `ForumComment`

- **Relationships**:
  - `customer()` → belongsTo Customer
  - `post()` → belongsTo ForumPost
  - `parent()` → belongsTo ForumComment (self)
  - `replies()` → hasMany ForumComment (self)
  - `publishedReplies()`, `descendants()` — filtered/recursive
  - `votes()` → morphMany ForumVote
  - `likes()`, `dislikes()`
- **Scopes**: `published()`, `root()`, `latest()`, `oldest()`
- **Methods**: `isReply()`, `hasReplies()`, `updateRepliesCount()`, `updateVoteStats()`
- **Accessors**: `time_ago`, `depth` (tính từ parent chain), `avatar_url` (Gravatar fallback)
- **Boot events**: Auto-update post comment stats và parent replies count khi created/deleted

### 3.4. `ForumTag`

- **Relationships**: `posts()` → belongsToMany ForumPost, `publishedPosts()`
- **Scopes**: `featured()`, `popular()`
- **Methods**: `updatePostsCount()`
- **Route key**: `slug`

### 3.5. `ForumVote`

- **Relationships**: `voteable()` → morphTo (ForumPost hoặc ForumComment)
- **Scopes**: `likes()`, `dislikes()`, `byVoter($identifier)`
- **Methods**: `isLike()`, `isDislike()`
- **Boot events**: Auto-update voteable's vote stats khi created/deleted

### 3.6. `ForumReport`

- **Relationships**: `reporter()` → belongsTo Customer, `reviewer()` → belongsTo Admin, `reportable()` → morphTo
- **Scopes**: `pending()`, `reviewed()`
- **Methods**: `markAsReviewed($admin, $notes)`

### 3.7. `ForumBookmark`

- **Relationships**: `customer()` → belongsTo Customer, `post()` → belongsTo ForumPost

### 3.8. `ForumNotification`

- **Relationships**: `customer()` → belongsTo Customer
- **Scopes**: `unread()`, `forCustomer($customerId)`
- **Methods**: `markAsRead()`

### 3.9. `ForumReputationLog`

- **Relationships**: `customer()` → belongsTo Customer, `reference()` → morphTo

---

## 4. Controllers & Endpoints

### 4.1. `ForumController` (Web — `app/Http/Controllers/ForumController.php`)

Inject 7 services qua constructor: PostService, CommentService, VoteService, ReportService, BookmarkService, NotificationService, ReputationService.

#### Routes Web (`routes/web.php`, prefix `/forum`)

| Method | URI | Action | Auth | Middleware | Name |
|--------|-----|--------|------|------------|------|
| GET | `/forum` | `index` | Public | — | `forum.index` |
| GET | `/forum/search` | `search` | Public | — | `forum.search` |
| GET | `/forum/trending` | `trending` | Public | — | `forum.trending` |
| GET | `/forum/leaderboard` | `leaderboard` | Public | — | `forum.leaderboard` |
| GET | `/forum/posts/{post}` | `show` | Public | — | `forum.posts.show` |
| GET | `/forum/category/{category}` | `category` | Public | — | `forum.category` |
| GET | `/forum/tag/{tag}` | `tag` | Public | — | `forum.tag` |
| GET | `/forum/create` | `create` | Customer | honeypot | `forum.posts.create` |
| POST | `/forum/posts` | `store` | Customer | honeypot, rate:posts | `forum.posts.store` |
| GET | `/forum/posts/{post}/edit` | `edit` | Customer | honeypot | `forum.posts.edit` |
| PUT | `/forum/posts/{post}` | `update` | Customer | honeypot, rate:posts | `forum.posts.update` |
| DELETE | `/forum/posts/{post}` | `destroy` | Customer | honeypot | `forum.posts.destroy` |
| POST | `/forum/posts/{post}/comments` | `storeComment` | Customer | honeypot, rate:comments | `forum.comments.store` |
| POST | `/forum/posts/{post}/bookmark` | `bookmark` | Customer | honeypot | `forum.posts.bookmark` |
| GET | `/forum/bookmarks` | `bookmarks` | Customer | honeypot | `forum.bookmarks` |
| POST | `/forum/posts/{post}/pin-answer` | `pinBestAnswer` | Customer | honeypot | `forum.posts.pin_answer` |
| GET | `/forum/notifications` | `notifications` | Customer | honeypot | `forum.notifications` |
| GET | `/forum/notifications/count` | `notificationCount` | Customer | honeypot | `forum.notifications.count` |
| POST | `/forum/notifications/read` | `markNotificationRead` | Customer | honeypot | `forum.notifications.read` |
| POST | `/forum/vote` | `vote` | Customer | honeypot, rate:votes | `forum.vote` |
| POST | `/forum/report` | `report` | Customer | honeypot, rate:reports | `forum.report` |

### 4.2. `ForumApiController` (API — `app/Http/Controllers/Api/ForumApiController.php`)

#### Routes API (`routes/api/forum.php`)

| Method | URI | Action | Auth | Name |
|--------|-----|--------|------|------|
| GET | `/api/forum/posts` | `index` | Public | — |
| GET | `/api/forum/posts/{slug}` | `show` | Public | — |
| GET | `/api/forum/categories` | `categories` | Public | — |
| GET | `/api/forum/tags` | `tags` | Public | — |
| GET | `/api/forum/trending` | `trending` | Public | — |
| GET | `/api/forum/leaderboard` | `leaderboard` | Public | — |
| POST | `/api/forum/posts` | `store` | Sanctum | — |
| PUT | `/api/forum/posts/{id}` | `update` | Sanctum | — |
| DELETE | `/api/forum/posts/{id}` | `destroy` | Sanctum | — |
| POST | `/api/forum/posts/{id}/comments` | `storeComment` | Sanctum | — |
| POST | `/api/forum/posts/{id}/bookmark` | `bookmark` | Sanctum | — |
| POST | `/api/forum/vote` | `vote` | Sanctum | — |
| GET | `/api/forum/notifications` | `notifications` | Sanctum | — |
| POST | `/api/forum/notifications/read` | `markNotificationRead` | Sanctum | — |

### 4.3. User Profile (`UserProfileController`)

| Method | URI | Auth | Name |
|--------|-----|------|------|
| GET | `/profile/{user}` | Public | `forum.profile.show` |
| GET | `/profile/{user}/posts` | Public | `forum.profile.posts` |
| GET | `/profile/{user}/comments` | Public | `forum.profile.comments` |
| GET | `/profile/edit` | Customer | `forum.profile.edit` |

### 4.4. Validation (`ForumPostRequest`)

```php
'title'       => 'required|string|max:255',
'content'     => 'required|string|min:10',
'category_id' => 'required|exists:forum_categories,id',
'type'        => 'nullable|in:discussion,idea,question,showcase,job,review',
'tags'        => 'nullable|string|max:500',
'edit_reason' => 'nullable|string|max:255',
```

- Auto-set type = `discussion` nếu không có
- HTML sanitization: chỉ giữ `<p><br><strong><em><u><ul><ol><li><a><code><pre><blockquote><h1-h6>`
- Tags: trim, unique, max 10 tags
- Messages bằng tiếng Việt

---

## 5. Services Layer

Tất cả services nằm trong `app/Services/Forum/`.

### 5.1. `ForumPostService`

| Method | Mô tả |
|--------|-------|
| `getIndexData(filters)` | Lấy dữ liệu trang chủ: posts (paginated), sticky, categories, tags, stats. Sort: latest/popular/activity |
| `getPublicStats()` | Thống kê public: total posts, comments, members, categories |
| `getAdminStats()` | Thống kê admin: bao gồm pending posts/comments/reports |
| `create(data, customer)` | Tạo post + sync tags + award reputation (+10 điểm) |
| `update(post, data, customer)` | Cập nhật post + ghi edit_history + sync tags |
| `delete(post)` | Xóa post |
| `getDetail(post, ip)` | Chi tiết post + increment views (deduplicate by session) + related + author posts |
| `getByCategory(category)` | Posts theo danh mục (paginated) |
| `getByTag(tag)` | Posts theo tag (paginated) |
| `search(query, filters)` | FULLTEXT search với relevance scoring. Sort: relevance/newest/votes/comments |
| `getTrending(limit)` | Posts có hot_score > 0, order by hot_score |
| `updateStatus(post, status, featured, sticky)` | Cập nhật trạng thái (admin) |
| `massUpdateStatus(ids, status)` | Mass update status (admin) |
| `massDelete(ids)` | Mass delete (admin) |
| `syncTags(post, tagsString)` | Parse comma-separated tags, firstOrCreate, sync pivot |

### 5.2. `ForumCommentService`

| Method | Mô tả |
|--------|-------|
| `create(post, data, customer)` | Tạo comment + notify post author + notify parent author + process @mentions + award reputation (+5) |
| `pinBestAnswer(comment, post)` | Unpin existing → pin comment + notify + award reputation (+15) |
| `unpinBestAnswer(post)` | Unpin all best answers |
| `updateStatus(comment, status)` | Cập nhật status + update post stats |
| `delete(comment)` | Xóa + update post stats |
| `massUpdateStatus(ids, status)` | Mass update (admin) |
| `massDelete(ids)` | Mass delete (admin) |
| `processMentions(comment, post)` | Parse `@name` → tìm customer → gửi notification |

### 5.3. `ForumVoteService`

| Method | Mô tả |
|--------|-------|
| `toggle(voteable, voterIdentifier, voteType)` | Toggle vote: same type → remove, different → switch, new → create + award reputation (+2 like / -1 dislike) |
| `resolveVoteable(type, id)` | Resolve `post`/`comment` string → Model instance |

### 5.4. `ForumBookmarkService`

| Method | Mô tả |
|--------|-------|
| `toggle(customerId, post)` | Toggle bookmark, return bool |
| `getByCustomer(customerId, perPage)` | Lấy bookmarked posts (paginated) |

### 5.5. `ForumNotificationService`

| Method | Mô tả |
|--------|-------|
| `notifyReplyToPost(post, comment)` | Thông báo khi có comment mới trên bài viết |
| `notifyReplyToComment(parent, reply)` | Thông báo khi có reply trên comment |
| `notifyBestAnswer(comment)` | Thông báo khi comment được chọn best answer |
| `notifyMention(customerId, post, comment, name)` | Thông báo khi bị @mention |
| `getForCustomer(customerId, perPage)` | Lấy notifications (paginated) |
| `getUnreadCount(customerId)` | Đếm unread |
| `markAsRead(notificationId, customerId)` | Đánh dấu đã đọc |
| `markAllAsRead(customerId)` | Đánh dấu tất cả đã đọc |

> **Logic**: Không gửi notification cho chính mình (self-action check).

### 5.6. `ForumReputationService`

**Bảng điểm**:

| Action | Điểm |
|--------|------|
| `post_created` | +10 |
| `comment_created` | +5 |
| `vote_like` | +2 |
| `vote_dislike` | -1 |
| `best_answer` | +15 |
| `post_removed` | -10 |

**Badges** (dựa trên tổng reputation):

| Min Points | Badge | Icon |
|-----------|-------|------|
| 1000 | Legend | 👑 |
| 500 | Expert | ⭐ |
| 200 | Contributor | 🔥 |
| 50 | Active | ⚡ |
| 0 | Newcomer | 🌱 |

| Method | Mô tả |
|--------|-------|
| `award(customerId, action, reference)` | Ghi log + increment customer.reputation |
| `getBadge(reputation)` | Trả về badge tương ứng |
| `getLeaderboard(period, limit)` | All-time (từ customers.reputation) hoặc monthly (từ logs SUM) |

### 5.7. `ForumReportService`

| Method | Mô tả |
|--------|-------|
| `create(reportable, reporterId, data)` | Tạo report |
| `hasDuplicate(reportable, reporterId)` | Kiểm tra đã report chưa |
| `resolveReportable(type, id)` | Resolve `post`/`comment` → Model |
| `updateStatus(report, status, notes, adminId)` | Admin xử lý report |
| `massUpdateStatus(ids, status, adminId)` | Mass update (admin) |
| `massDelete(ids)` | Mass delete (admin) |

---

## 6. Middleware & Security

### 6.1. `ForumHoneypot` (`app/Http/Middleware/ForumHoneypot.php`)

- Alias: `forum.honeypot`
- Kiểm tra hidden field `website_url` (configurable) trên POST requests
- Nếu field có giá trị → bot detected → trả về fake success (200 OK hoặc redirect with success message)
- Không block, không log — silent rejection

### 6.2. `ForumRateLimiter` (`app/Http/Middleware/ForumRateLimiter.php`)

- Alias: `forum.rate:{action}`
- Sử dụng Laravel RateLimiter, key: `forum:{action}:{customer_id}`
- Decay: 1 giờ (3600s)
- Limits từ `config/forum.rate_limits`: posts=5/h, comments=30/h, votes=60/h, reports=10/h
- Trả về 429 JSON hoặc redirect with error message bằng tiếng Việt
- Chỉ áp dụng cho authenticated customers

### 6.3. Bảo mật khác

- **IP tracking**: Lưu ip_address trên posts, comments, votes, reports
- **User agent tracking**: Lưu user_agent trên posts, comments, votes
- **HTML sanitization**: Strip dangerous tags trong ForumPostRequest
- **View deduplication**: Session-based (`forum_viewed_{id}`)
- **Duplicate report prevention**: Kiểm tra trước khi tạo report
- **Owner-only actions**: Best answer chỉ post author mới pin được

---

## 7. API Resources

### 7.1. `ForumPostResource`

```json
{
  "id": 1,
  "title": "...",
  "slug": "...",
  "excerpt": "...",
  "content": "(chỉ khi route *.show)",
  "type": "discussion",
  "status": "published",
  "is_featured": false,
  "is_sticky": false,
  "author": { "name": "...", "avatar": "..." },
  "category": { "id": 1, "name": "...", "slug": "..." },
  "tags": [{ "id": 1, "name": "...", "slug": "...", "color": "#..." }],
  "stats": { "views": 0, "comments": 0, "likes": 0, "dislikes": 0 },
  "hot_score": 0,
  "url": "https://lamgame.vn/forum/posts/...",
  "created_at": "2026-04-28T...",
  "last_activity": "2026-04-28T..."
}
```

### 7.2. `ForumCommentResource`

```json
{
  "id": 1,
  "content": "...",
  "is_best_answer": false,
  "author": { "name": "...", "avatar": "..." },
  "stats": { "likes": 0, "dislikes": 0, "replies": 0 },
  "parent_id": null,
  "replies": [ /* recursive ForumCommentResource */ ],
  "created_at": "2026-04-28T..."
}
```

---

## 8. Artisan Commands

### `forum:calculate-hot-scores`

- **File**: `app/Console/Commands/ForumCalculateHotScores.php`
- **Chức năng**: Tính `hot_score` cho forum posts dựa trên hoạt động gần đây
- **Công thức**: `(views_count + likes_count * 3 + comments_count * 5) / age_hours^1.5 * 1000`
- **Phạm vi**: Chỉ posts published trong 30 ngày gần nhất
- **Cleanup**: Zero out hot_score cho posts > 30 ngày
- **Nên chạy**: Cron mỗi 15-30 phút

---

## 9. Views (Blade Templates)

### Frontend (`resources/views/lamgame/pages/forum/`)

| File | Mô tả |
|------|-------|
| `index.blade.php` | Trang chủ forum |
| `show.blade.php` | Chi tiết bài viết |
| `create.blade.php` | Form tạo bài mới |
| `edit.blade.php` | Form chỉnh sửa bài |
| `bookmarks.blade.php` | Danh sách bài đã bookmark |
| `trending.blade.php` | Bài viết trending |
| `tag.blade.php` | Bài viết theo tag |
| `leaderboard.blade.php` | Bảng xếp hạng reputation |
| `notifications.blade.php` | Trang thông báo |
| `partials/post-card.blade.php` | Component card bài viết |
| `partials/comment.blade.php` | Component comment (recursive) |
| `components/rich-editor.blade.php` | Rich text editor component |

### Admin (`resources/views/admin/forum/`)

| File | Mô tả |
|------|-------|
| `dashboard.blade.php` | Dashboard quản lý forum |
| `posts.blade.php` | Quản lý bài viết |

### Profile (`resources/views/forum/profile/`)

| File | Mô tả |
|------|-------|
| `show.blade.php` | Trang profile user |

### Views thiếu (Controller có nhưng chưa có view)

| View cần | Controller method |
|----------|------------------|
| `search.blade.php` | `ForumController@search` |
| `category.blade.php` | `ForumController@category` |

---

## 10. Business Logic & Flows

### 10.1. Flow tạo bài viết

```
User submit form → ForumPostRequest (validate + sanitize HTML + clean tags)
  → ForumHoneypot middleware (check bot)
  → ForumRateLimiter middleware (max 5/hour)
  → ForumController@store
    → ForumPostService@create
      → Tạo ForumPost (auto slug, auto excerpt)
      → syncTags (firstOrCreate tags, sync pivot)
      → ReputationService@award (+10 points)
  → Redirect to post show page
```

### 10.2. Flow bình luận

```
User submit comment → Validate (content 3-2000 chars, parent_id optional)
  → ForumHoneypot + ForumRateLimiter (max 30/hour)
  → ForumCommentService@create
    → Tạo ForumComment
    → [Boot event] Auto-update post.comments_count, last_comment_at
    → [Boot event] Auto-update parent.replies_count (nếu reply)
    → NotificationService@notifyReplyToPost (notify post author)
    → NotificationService@notifyReplyToComment (notify parent author, nếu reply)
    → processMentions → parse @name → tìm customer → notifyMention
    → ReputationService@award (+5 points)
```

### 10.3. Flow vote (like/dislike)

```
User click vote → AJAX request
  → ForumRateLimiter (max 60/hour)
  → ForumVoteService@toggle
    → Tìm existing vote by voter_identifier
    → Nếu same type → DELETE (undo vote)
    → Nếu different type → UPDATE (switch vote)
    → Nếu không có → CREATE + award reputation to content author (+2 like / -1 dislike)
    → [Boot event] Auto-update voteable.likes_count, dislikes_count
  → Return JSON { likes_count, dislikes_count }
```

### 10.4. Flow bookmark

```
User click bookmark → AJAX request
  → ForumBookmarkService@toggle
    → Nếu đã bookmark → DELETE → return false
    → Nếu chưa → CREATE → return true
  → Return JSON { bookmarked: true/false }
```

### 10.5. Flow notification

```
Trigger events:
  1. Comment trên post → notifyReplyToPost (post author)
  2. Reply trên comment → notifyReplyToComment (parent author)
  3. @mention trong comment → notifyMention (mentioned user)
  4. Best answer được chọn → notifyBestAnswer (comment author)

Tất cả đều check: không notify chính mình.

User xem notifications → paginated list
User mark as read → single hoặc mark all
AJAX poll unread count → GET /notifications/count
```

### 10.6. Flow reputation

```
Actions tự động award:
  - Tạo post: +10
  - Tạo comment: +5
  - Nhận like: +2
  - Nhận dislike: -1
  - Best answer: +15
  - Post bị xóa: -10 (chưa implement trong code hiện tại)

Mỗi award:
  → Tạo ForumReputationLog (customer_id, points, action, reference)
  → INCREMENT customers.reputation

Badge tự động theo tổng điểm (không lưu DB, tính runtime).
```

### 10.7. Flow best answer

```
Post author click "Chọn câu trả lời tốt nhất" → AJAX
  → Kiểm tra: chỉ post.customer_id mới được pin
  → Nếu comment đã là best_answer → unpin
  → Nếu chưa → unpin existing → pin comment
    → Notify comment author
    → Award +15 reputation to comment author
```

### 10.8. Flow report

```
User click report → AJAX
  → Validate: type (post/comment), reason (5 options), description
  → ForumRateLimiter (max 10/hour)
  → Check duplicate (same reporter + same reportable)
  → ForumReportService@create
  → Return success message
```

### 10.9. Flow moderation (Admin)

Services có sẵn methods cho admin:
- `PostService@updateStatus`, `massUpdateStatus`, `massDelete`
- `CommentService@updateStatus`, `massUpdateStatus`, `massDelete`
- `ReportService@updateStatus`, `massUpdateStatus`, `massDelete`

> **Lưu ý**: Chưa có ForumModerationController riêng. Admin routes được handle bởi Bagisto admin package.

### 10.10. Flow search (FULLTEXT)

```
User nhập query → GET /forum/search?q=...
  → ForumPostService@search
    → FULLTEXT MATCH...AGAINST (BOOLEAN MODE) với relevance_score
    → Filter: category, type, tag
    → Sort: relevance (default), newest, votes, comments
  → Paginated results
```

### 10.11. Flow trending / hot score

```
Cron job (mỗi 15-30 phút):
  → forum:calculate-hot-scores
    → UPDATE posts SET hot_score = (views + likes*3 + comments*5) / age^1.5 * 1000
    → Chỉ posts trong 30 ngày
    → Zero out posts > 30 ngày

User xem trending:
  → ForumPostService@getTrending
    → WHERE hot_score > 0 ORDER BY hot_score DESC
```

---

## 11. Cấu trúc code hiện tại

```
app/
├── Console/Commands/
│   └── ForumCalculateHotScores.php          # Artisan command tính hot_score
├── Http/
│   ├── Controllers/
│   │   ├── ForumController.php              # Web controller (22 methods)
│   │   └── Api/
│   │       └── ForumApiController.php       # REST API controller (Sanctum auth)
│   ├── Middleware/
│   │   ├── ForumHoneypot.php                # Anti-spam honeypot
│   │   └── ForumRateLimiter.php             # Rate limiting per action
│   ├── Requests/
│   │   └── ForumPostRequest.php             # Form request validation
│   └── Resources/
│       ├── ForumPostResource.php            # API resource cho post
│       └── ForumCommentResource.php         # API resource cho comment
├── Models/
│   ├── ForumCategory.php
│   ├── ForumPost.php
│   ├── ForumComment.php
│   ├── ForumTag.php
│   ├── ForumVote.php
│   ├── ForumReport.php
│   ├── ForumBookmark.php
│   ├── ForumNotification.php
│   └── ForumReputationLog.php
└── Services/Forum/
    ├── ForumPostService.php
    ├── ForumCommentService.php
    ├── ForumVoteService.php
    ├── ForumBookmarkService.php
    ├── ForumNotificationService.php
    ├── ForumReputationService.php
    └── ForumReportService.php

config/
└── forum.php                                # Forum configuration

database/migrations/
├── 2024_12_09_000001_create_forum_categories_table.php
├── 2024_12_09_000002_create_forum_posts_table.php
├── 2024_12_09_000003_create_forum_comments_table.php
├── 2024_12_09_000004_create_forum_tags_table.php
├── 2024_12_09_000005_create_forum_post_tags_table.php
├── 2024_12_09_000006_create_forum_votes_table.php
├── 2025_09_09_142418_add_edit_history_to_forum_posts_table.php
├── 2025_09_09_170743_create_forum_reports_table.php
├── 2025_09_09_171028_add_user_status_fields_to_users_table.php
├── 2025_09_26_144022_add_stats_to_forum_posts_table.php
├── 2026_04_28_000001_add_customer_id_to_forum_tables.php
├── 2026_04_28_000002_create_forum_bookmarks_notifications_and_best_answer.php
└── 2026_04_28_000003_add_fulltext_search_and_reputation_logs.php

routes/
├── web.php                                  # Forum web routes (prefix /forum)
└── api/
    └── forum.php                            # Forum API routes (prefix /api/forum)

resources/views/
├── lamgame/pages/forum/
│   ├── index.blade.php
│   ├── show.blade.php
│   ├── create.blade.php
│   ├── edit.blade.php
│   ├── bookmarks.blade.php
│   ├── trending.blade.php
│   ├── tag.blade.php
│   ├── leaderboard.blade.php
│   ├── notifications.blade.php
│   ├── partials/post-card.blade.php
│   ├── partials/comment.blade.php
│   └── components/rich-editor.blade.php
├── admin/forum/
│   ├── dashboard.blade.php
│   └── posts.blade.php
└── forum/profile/
    └── show.blade.php
```

---

## 12. Những gì đã có vs chưa có

### ✅ Đã có

| Feature | Chi tiết |
|---------|---------|
| CRUD Posts | Tạo, sửa, xóa bài viết với 6 loại |
| Nested Comments | Comment lồng nhau với parent_id |
| Vote System | Like/dislike cho cả post và comment (polymorphic) |
| Categories & Tags | Danh mục + tags many-to-many |
| FULLTEXT Search | MySQL FULLTEXT với relevance scoring |
| Bookmarks | Toggle bookmark cho customer |
| Notifications | 4 loại: reply_post, reply_comment, mention, best_answer |
| Reputation System | Points + badges + leaderboard (all-time + monthly) |
| Best Answer | Pin/unpin cho bài dạng question |
| Reports | Báo cáo vi phạm với 5 reasons |
| Hot Score / Trending | Cron-based hot_score calculation |
| Anti-spam | Honeypot + rate limiting |
| Edit History | JSON tracking changes |
| IP/UA Tracking | Trên posts, comments, votes, reports |
| API Resources | ForumPostResource, ForumCommentResource |
| Dual Interface | Web (Blade) + REST API (Sanctum) |
| Admin Views | Dashboard + posts management |
| User Profile | Public profile với posts/comments |
| Customer Integration | customer_id FK + data migration từ email |

### ❌ Chưa có / Cần bổ sung

| Feature | Ghi chú |
|---------|---------|
| **ForumManageController** (Admin API) | Chưa có API management theo pattern `api-ecommerce-manage.php` |
| **View: search.blade.php** | Controller `search()` render view nhưng file chưa tồn tại |
| **View: category.blade.php** | Controller `category()` render view nhưng file chưa tồn tại |
| **Cron schedule** | `forum:calculate-hot-scores` chưa được đăng ký trong Kernel |
| **`post_removed` reputation** | Constant định nghĩa -10 nhưng chưa gọi khi delete post |
| **Cột `views` trùng lặp** | Có cả `views` (migration 2025_09_26) và `views_count` (migration gốc) |
| **Comment edit** | Chưa có chức năng sửa comment |
| **Comment delete by author** | Chưa có endpoint cho user tự xóa comment |
| **Post ownership check** | `update`/`destroy` chưa kiểm tra customer_id ownership (web controller) |
| **Image/file upload** | Chưa có upload ảnh trong bài viết |
| **Email notifications** | Chỉ có in-app notifications, chưa có email |
| **Notification cho vote** | Type `vote` được define nhưng chưa implement |
| **Admin moderation API** | Services có mass methods nhưng chưa có controller expose |
| **Soft delete** | Posts/comments dùng hard delete |
| **Pagination cho API notifications** | `ForumApiController@notifications` trả paginated nhưng không wrap resource |
| **API rate limiting** | API routes (`routes/api/forum.php`) chưa có rate limiting middleware |

---

## 13. Đề xuất API Management cho Ohha Studio

### Pattern hiện tại (`api-ecommerce-manage.php`)

- **Auth**: Header `X-Api-Key` → middleware `api.key` → verify admin `api_token`
- **Prefix**: `/api/manage/...`
- **Throttle**: `throttle:60,1` (global), `throttle:10,1` (write operations)
- **Response format**: `{ status: 'success'|'error', data: ..., meta: { pagination }, message: '...' }`

### Đề xuất routes cho Forum Management API

Thêm vào `routes/api-ecommerce-manage.php`:

```php
// === Forum Management ===
Route::prefix('forum')->name('forum.')->group(function () {

    // Dashboard & Statistics
    Route::get('dashboard', [ForumManageController::class, 'dashboard'])->name('dashboard');

    // Posts
    Route::prefix('posts')->name('posts.')->group(function () {
        Route::get('/', [ForumManageController::class, 'listPosts'])->name('list');
        Route::get('/statistics', [ForumManageController::class, 'postStatistics'])->name('statistics');
        Route::get('/{id}', [ForumManageController::class, 'postDetail'])->name('detail')->where('id', '[0-9]+');
        Route::post('/{id}/status', [ForumManageController::class, 'changePostStatus'])->name('change-status')->where('id', '[0-9]+')->middleware('throttle:10,1');
        Route::post('/{id}/feature', [ForumManageController::class, 'toggleFeature'])->name('feature')->where('id', '[0-9]+')->middleware('throttle:10,1');
        Route::post('/{id}/sticky', [ForumManageController::class, 'toggleSticky'])->name('sticky')->where('id', '[0-9]+')->middleware('throttle:10,1');
        Route::delete('/{id}', [ForumManageController::class, 'deletePost'])->name('delete')->where('id', '[0-9]+')->middleware('throttle:10,1');
        Route::post('/mass-status', [ForumManageController::class, 'massPostStatus'])->name('mass-status')->middleware('throttle:10,1');
        Route::post('/mass-delete', [ForumManageController::class, 'massDeletePosts'])->name('mass-delete')->middleware('throttle:10,1');
    });

    // Comments
    Route::prefix('comments')->name('comments.')->group(function () {
        Route::get('/', [ForumManageController::class, 'listComments'])->name('list');
        Route::post('/{id}/status', [ForumManageController::class, 'changeCommentStatus'])->name('change-status')->where('id', '[0-9]+')->middleware('throttle:10,1');
        Route::delete('/{id}', [ForumManageController::class, 'deleteComment'])->name('delete')->where('id', '[0-9]+')->middleware('throttle:10,1');
        Route::post('/mass-status', [ForumManageController::class, 'massCommentStatus'])->name('mass-status')->middleware('throttle:10,1');
        Route::post('/mass-delete', [ForumManageController::class, 'massDeleteComments'])->name('mass-delete')->middleware('throttle:10,1');
    });

    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ForumManageController::class, 'listReports'])->name('list');
        Route::post('/{id}/resolve', [ForumManageController::class, 'resolveReport'])->name('resolve')->where('id', '[0-9]+')->middleware('throttle:10,1');
        Route::post('/{id}/dismiss', [ForumManageController::class, 'dismissReport'])->name('dismiss')->where('id', '[0-9]+')->middleware('throttle:10,1');
        Route::post('/mass-resolve', [ForumManageController::class, 'massResolveReports'])->name('mass-resolve')->middleware('throttle:10,1');
    });

    // Categories
    Route::prefix('categories')->name('categories.')->group(function () {
        Route::get('/', [ForumManageController::class, 'listCategories'])->name('list');
        Route::post('/', [ForumManageController::class, 'storeCategory'])->name('store')->middleware('throttle:10,1');
        Route::put('/{id}', [ForumManageController::class, 'updateCategory'])->name('update')->where('id', '[0-9]+')->middleware('throttle:10,1');
        Route::delete('/{id}', [ForumManageController::class, 'deleteCategory'])->name('delete')->where('id', '[0-9]+')->middleware('throttle:10,1');
    });

    // Tags
    Route::prefix('tags')->name('tags.')->group(function () {
        Route::get('/', [ForumManageController::class, 'listTags'])->name('list');
        Route::put('/{id}', [ForumManageController::class, 'updateTag'])->name('update')->where('id', '[0-9]+')->middleware('throttle:10,1');
        Route::delete('/{id}', [ForumManageController::class, 'deleteTag'])->name('delete')->where('id', '[0-9]+')->middleware('throttle:10,1');
    });
});
```

### Endpoints chi tiết

| Method | Endpoint | Mô tả | Response |
|--------|----------|-------|----------|
| GET | `/api/manage/forum/dashboard` | Thống kê tổng quan forum | `{ status, data: { total_posts, published, pending, total_comments, pending_reports, ... } }` |
| GET | `/api/manage/forum/posts` | Danh sách posts (filter: status, category, type, search, sort) | `{ status, data: [...], meta: { pagination } }` |
| GET | `/api/manage/forum/posts/statistics` | Thống kê posts theo thời gian | `{ status, data: { by_status, by_type, by_category, daily_trend } }` |
| GET | `/api/manage/forum/posts/{id}` | Chi tiết post + comments + votes | `{ status, data: { post, comments, vote_stats } }` |
| POST | `/api/manage/forum/posts/{id}/status` | Đổi status (published/hidden/locked) | `{ status, message }` |
| POST | `/api/manage/forum/posts/{id}/feature` | Toggle featured | `{ status, data: { is_featured } }` |
| POST | `/api/manage/forum/posts/{id}/sticky` | Toggle sticky | `{ status, data: { is_sticky } }` |
| DELETE | `/api/manage/forum/posts/{id}` | Xóa post | `{ status, message }` |
| POST | `/api/manage/forum/posts/mass-status` | Mass update status | `{ status, data: { affected } }` |
| POST | `/api/manage/forum/posts/mass-delete` | Mass delete | `{ status, data: { deleted } }` |
| GET | `/api/manage/forum/comments` | Danh sách comments (filter: status, post_id, search) | `{ status, data: [...], meta: { pagination } }` |
| POST | `/api/manage/forum/comments/{id}/status` | Đổi status comment | `{ status, message }` |
| DELETE | `/api/manage/forum/comments/{id}` | Xóa comment | `{ status, message }` |
| GET | `/api/manage/forum/reports` | Danh sách reports (filter: status, type) | `{ status, data: [...], meta: { pagination } }` |
| POST | `/api/manage/forum/reports/{id}/resolve` | Resolve report + admin notes | `{ status, message }` |
| POST | `/api/manage/forum/reports/{id}/dismiss` | Dismiss report | `{ status, message }` |
| GET | `/api/manage/forum/categories` | Danh sách categories | `{ status, data: [...] }` |
| POST | `/api/manage/forum/categories` | Tạo category | `{ status, data: { category } }` |
| PUT | `/api/manage/forum/categories/{id}` | Sửa category | `{ status, data: { category } }` |
| DELETE | `/api/manage/forum/categories/{id}` | Xóa category | `{ status, message }` |
| GET | `/api/manage/forum/tags` | Danh sách tags | `{ status, data: [...] }` |
| PUT | `/api/manage/forum/tags/{id}` | Sửa tag | `{ status, data: { tag } }` |
| DELETE | `/api/manage/forum/tags/{id}` | Xóa tag | `{ status, message }` |

### Ưu tiên triển khai

1. **P0 — Dashboard + Posts management**: dashboard, listPosts, postDetail, changePostStatus, deletePost
2. **P0 — Reports**: listReports, resolveReport, dismissReport (cần xử lý vi phạm)
3. **P1 — Comments management**: listComments, changeCommentStatus, deleteComment
4. **P1 — Mass operations**: massPostStatus, massDeletePosts, massCommentStatus
5. **P2 — Categories & Tags CRUD**: Quản lý danh mục và tags
6. **P2 — Statistics**: postStatistics với charts data

### File cần tạo

```
app/Http/Controllers/Api/ForumManageController.php   # Controller mới
routes/api-ecommerce-manage.php                       # Thêm forum routes
```

> **Lưu ý**: Tất cả business logic đã có sẵn trong Services layer. Controller chỉ cần gọi service methods và format response theo pattern `{ status, data, meta, message }`.
