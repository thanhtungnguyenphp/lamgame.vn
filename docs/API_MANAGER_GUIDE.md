# LamGame - API Manager Documentation

> Cập nhật: 2026-05-06 | Dựa trên code thực tế tại `source-web/`

## Tổng quan

Hệ thống API Manager của LamGame bao gồm 4 nhóm chính:

| Module | Prefix | Route File | Endpoints |
|--------|--------|-----------|-----------|
| E-Commerce | `/api/manage/` | `api-ecommerce-manage.php` | 42 |
| Forum | `/api/manage/forum/` | `api-forum-manage.php` | 28 |
| Job | `/api/manage/` | `api-job-manage.php` | 15 |
| Reviews & Hire | `/api/v1/` | `api-reviews-hire.php` | 5 |

**Tổng: 90 endpoints**

---

## Authentication

### API Key (Management APIs)

```
Header: X-Api-Key: {admin_api_token}
```

Middleware `api.key` (class `ApiKeyAuth`) hash SHA-256 key và lookup trong `admins.api_token`.

```php
$admin = Admin::where('api_token', hash('sha256', $request->header('X-Api-Key', '')))->first();
```

### Sanctum (User APIs)

Reviews API sử dụng `auth:sanctum` cho các endpoint write.

### Rate Limiting

- Read: `60 req/min`
- Write: `10 req/min`
- Hire Request: `3 req/60min`
- Review Store: `5 req/min`
- Review Helpful: `30 req/min`

---

## Response Format

```json
{
  "status": "success|error",
  "message": "Optional message",
  "data": {},
  "meta": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 72
  }
}
```

---

## Pagination

Tất cả list endpoints hỗ trợ:
- `per_page` (default: 15, max: 100)
- `page` (default: 1)

---

# MODULE 1: E-COMMERCE MANAGEMENT API

**Prefix:** `/api/manage/`  
**Auth:** `X-Api-Key`  
**Controller files:** `DashboardManageController`, `ProductManageController`, `OrderManageController`, `SellerManageController`, `EarningManageController`, `WithdrawalManageController`, `CustomerManageController`

---

## 1.1 Dashboard

### GET /api/manage/dashboard

Trả về thống kê tổng hợp toàn platform.

**Response:**
```json
{
  "status": "success",
  "data": {
    "products": { "total": 150, "published": 120, "pending_review": 10, "draft": 20 },
    "orders": { "total": 500, "completed": 400, "pending": 50, "processing": 30, "canceled": 20 },
    "revenue": { "total": 50000000, "this_month": 5000000, "today": 200000, "platform_earnings": 10000000, "seller_earnings": 40000000 },
    "sellers": { "total": 30, "active": 25, "pending": 3, "suspended": 2 },
    "customers": { "total": 1000, "this_month": 50, "with_orders": 400 },
    "withdrawals": { "pending_count": 5, "pending_amount": 2000000, "completed_this_month": 3000000 },
    "jobs": { "total": 100, "active": 60 },
    "subscriptions": { "active": 20, "mrr": 1000000 }
  }
}
```

---

## 1.2 Products

### GET /api/manage/products

Danh sách sản phẩm (paginated).

**Query params:**
| Param | Type | Description |
|-------|------|-------------|
| search | string | Tìm theo name/sku |
| status | 0\|1 | 0=draft, 1=published |
| pending_review | bool | Lọc sản phẩm chờ duyệt |
| seller_id | int | Lọc theo seller |
| category_id | int | Lọc theo category |
| sort_by | string | `created_at`, `price`, `name` |
| sort_dir | string | `asc`, `desc` |
| per_page | int | Default: 15, max: 100 |

---

### GET /api/manage/products/statistics

**Response data:**
- `totals`: by status breakdown
- `by_category`: top 10 categories
- `by_seller`: top 10 sellers
- `top_selling`: top 10 products

---

### GET /api/manage/products/{id}

Chi tiết sản phẩm bao gồm: basic info, flat data (name, description, prices, status), images, categories, downloadable_links, custom attributes (game_engine, programming_language, file_size, version, video_demo_url, demo_url, author_name), seller info, stats, meta SEO.

---

### POST /api/manage/products

Tạo sản phẩm mới (type: downloadable).

**Body:**
```json
{
  "sku": "required|string|unique",
  "name": "required|string|max:255",
  "description": "required|string",
  "price": "required|numeric|min:0",
  "short_description": "nullable|string",
  "url_key": "nullable|string|unique",
  "special_price": "nullable|numeric",
  "special_price_from": "nullable|date",
  "special_price_to": "nullable|date",
  "seller_id": "nullable|int|exists:source_game_sellers,id",
  "category_ids": "nullable|array",
  "status": "nullable|0|1",
  "attributes": "nullable|object",
  "meta_title": "nullable|string",
  "meta_description": "nullable|string",
  "meta_keywords": "nullable|string"
}
```

**Logic:** Nếu có `seller_id` → tự động set `pending_review=true`.

---

### PUT /api/manage/products/{id}

Cập nhật sản phẩm. Tất cả fields optional (validation `sometimes`).

---

### DELETE /api/manage/products/{id}

Xóa sản phẩm. **Blocked** nếu product có orders (HTTP 422).

---

### POST /api/manage/products/{id}/status

Đổi trạng thái sản phẩm.

**Body:**
```json
{ "status": 0 }  // 0=draft, 1=published
```

**Logic:** Không thể publish nếu `pending_review=true`.

---

### POST /api/manage/products/{id}/review

Duyệt/từ chối sản phẩm pending.

**Body:**
```json
{
  "action": "approve|reject",
  "rejection_reason": "nullable|string (required if reject)"
}
```

**Logic:**
- Approve: set `status=1`, gửi email `ProductApproved`
- Reject: set `status=0`, lưu `rejection_reason`, gửi email `ProductRejected`

---

### POST /api/manage/products/{id}/images

Upload hình ảnh sản phẩm. **Content-Type: multipart/form-data**

**Body:**
```json
{
  "images": "required|array|min:1|max:10",
  "images.*": "image|mimes:jpeg,jpg,png,gif,webp|max:5120 (5MB)"
}
```

**Logic:** Auto-convert sang WebP, lưu vào `product/{id}/`, gán position tự động.

**Response:**
```json
{
  "status": "success",
  "message": "Đã upload 3 hình.",
  "data": [
    { "id": 1, "path": "product/5/abc.webp", "url": "/storage/product/5/abc.webp", "position": 1 }
  ]
}
```

---

### DELETE /api/manage/products/{id}/images/{imageId}

Xóa 1 hình ảnh. Xóa cả file trên storage.

---

### POST /api/manage/products/{id}/images/reorder

Sắp xếp lại thứ tự hình ảnh.

**Body:**
```json
{ "image_ids": [3, 1, 2] }
```

---

### GET /api/manage/products/{id}/downloadable-links

Danh sách downloadable links của sản phẩm. Trả về: id, title, type (file/url), file_name, price, downloads, sort_order.

---

### POST /api/manage/products/{id}/downloadable-links

Upload source file hoặc external URL. **Content-Type: multipart/form-data** (nếu upload file).

**Body:**
```json
{
  "title": "required|string|max:255",
  "file": "required_without:url|file|max:102400 (100MB)",
  "url": "required_without:file|url",
  "price": "nullable|numeric|min:0 (default 0)",
  "downloads": "nullable|int|min:0 (0=unlimited)",
  "sort_order": "nullable|int"
}
```

**Logic:** Chỉ cho product type `downloadable`. File lưu private storage.

---

### PUT /api/manage/products/{id}/downloadable-links/{linkId}

Cập nhật link. Upload file mới sẽ xóa file cũ.

---

### DELETE /api/manage/products/{id}/downloadable-links/{linkId}

Xóa link + file + translations.

---

### POST /api/manage/products/{id}/downloadable-samples

Upload sample/preview file. Max 20MB.

**Body:**
```json
{
  "title": "required|string|max:255",
  "file": "required_without:url|file|max:20480",
  "url": "required_without:file|url",
  "sort_order": "nullable|int"
}
```

---

### DELETE /api/manage/products/{id}/downloadable-samples/{sampleId}

Xóa sample + file.

---

## 1.3 Orders

### GET /api/manage/orders

**Query params:**
| Param | Type | Description |
|-------|------|-------------|
| search | string | Tìm theo increment_id/email/name |
| status | string | pending, processing, completed, canceled, closed |
| payment_method | string | Lọc theo phương thức thanh toán |
| date_from | date | Từ ngày |
| date_to | date | Đến ngày |
| customer_id | int | Lọc theo customer |
| sort_by | string | `created_at`, `grand_total`, `increment_id` |

---

### GET /api/manage/orders/statistics

**Response:** total, by_status, revenue (total/this_month/last_month/today), avg_order_value, by_payment_method, recent_7_days.

---

### GET /api/manage/orders/{id}

Chi tiết đơn hàng: customer info, items, payment, billing_address, totals, comments, invoices, earnings breakdown.

---

### POST /api/manage/orders/{id}/status

**Body:**
```json
{ "status": "processing|completed|canceled|closed" }
```

**Transition rules:**
- `pending` → `processing`, `canceled`
- `processing` → `completed`, `canceled`
- `completed` → `closed`

**Side effects:**
- `completed`: auto-create earnings via `SourceGameEarning::createFromOrder()`
- `canceled`: mark related earnings as `refunded`

---

### POST /api/manage/orders/{id}/comment

**Body:**
```json
{
  "comment": "required|string",
  "customer_notified": "nullable|boolean"
}
```

---

## 1.4 Sellers

### GET /api/manage/sellers

**Query params:** search (shop_name/email), status, business_type, verified (bool), sort_by (created_at, total_revenue, total_products, rating_avg).

---

### GET /api/manage/sellers/statistics

**Response:** total, by_status, by_business_type, verified_count, new_this_month, top_sellers (top 10).

---

### GET /api/manage/sellers/{id}

Chi tiết seller: customer info, shop details, contact, business_type, verification, financial stats (total_earnings, total_withdrawn, pending_withdrawals, available_balance), bank_info, recent_products.

---

### PUT /api/manage/sellers/{id}

**Body:** shop_name, shop_description, contact_email, contact_phone, website, business_type, tax_id, bank_name, bank_account, bank_holder.

---

### POST /api/manage/sellers/{id}/approve

Approve pending seller → `status=active`, `verified=true`, `verified_at=now`.

---

### POST /api/manage/sellers/{id}/reject

**Body:** `{ "reason": "required|string" }`  
Set `status=rejected`.

---

### POST /api/manage/sellers/{id}/suspend

**Body:** `{ "reason": "required|string" }`  
Set `status=suspended` + ẩn tất cả products (status=0).

---

### POST /api/manage/sellers/{id}/activate

Reactivate seller → `status=active`, restore products (trừ pending_review).

---

## 1.5 Earnings

### GET /api/manage/earnings

**Query params:** seller_id, status, date_from, date_to, sort_by (created_at, order_amount, seller_amount).

**Response:** earning details with seller info, order info, product name, amounts, fees, status.

---

### GET /api/manage/earnings/statistics

**Response:** total_revenue, platform_earnings, seller_earnings, by_status, this_month, last_month, top_products (10), top_sellers (10), monthly_trend (12 months).

---

## 1.6 Withdrawals

### GET /api/manage/withdrawals

**Query params:** seller_id, status, date_from, date_to, sort_by (created_at, amount).

---

### GET /api/manage/withdrawals/{id}

Chi tiết withdrawal + seller's available_balance, bank_info.

---

### POST /api/manage/withdrawals/{id}/approve

Approve pending → `processing`. Validates seller balance.

---

### POST /api/manage/withdrawals/{id}/complete

**Body:**
```json
{
  "transaction_id": "required|string",
  "admin_note": "nullable|string"
}
```
Processing → `completed`. Records `processed_at`, `processed_by`.

---

### POST /api/manage/withdrawals/{id}/reject

**Body:** `{ "admin_note": "required|string" }`  
Pending/processing → `rejected`.

**Status flow:** `pending → processing → completed` hoặc `pending/processing → rejected`

---

## 1.7 Customers

### GET /api/manage/customers

**Query params:** search (name/email), is_verified, is_suspended, has_orders, has_seller, sort_by (created_at, first_name, email).

---

### GET /api/manage/customers/statistics

**Response:** total, verified, suspended, with_orders, sellers, new_this_month, new_last_month, top_spenders (10).

---

### GET /api/manage/customers/{id}

Chi tiết: seller info, orders_summary, active subscription, recent_orders.

---

### PUT /api/manage/customers/{id}

**Body:** first_name, last_name, email (unique), phone, gender (male/female/other), date_of_birth, notes.

---

### POST /api/manage/customers/{id}/suspend

**Body:** `{ "reason": "required|string" }`  
Cascade suspend associated seller.

---

### POST /api/manage/customers/{id}/activate

Reactivate customer + cascade reactivate seller.

---

# MODULE 2: FORUM MANAGEMENT API

**Prefix:** `/api/manage/forum/`  
**Auth:** `X-Api-Key`  
**Controller:** `ForumManageController`  
**Services:** ForumPostService, ForumCommentService, ForumReportService, ForumReputationService

---

## 2.1 Dashboard

### GET /api/manage/forum/dashboard

**Response:**
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

## 2.2 Posts

### GET /api/manage/forum/posts

**Query params:**
| Param | Type | Description |
|-------|------|-------------|
| search | string | Tìm theo title/author_name |
| status | string | draft, published, hidden, locked |
| type | string | discussion, idea, question, showcase, job, review |
| category_id | int | Lọc theo category |
| is_featured | bool | Bài viết nổi bật |
| is_sticky | bool | Bài ghim |
| customer_id | int | Lọc theo tác giả |
| sort_by | string | created_at, views_count, comments_count, likes_count, hot_score, title |

---

### GET /api/manage/forum/posts/{id}

Chi tiết post: category, tags, customer, comments_count, bookmarks_count.

---

### POST /api/manage/forum/posts

**Body:**
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

---

### PUT /api/manage/forum/posts/{id}

Cập nhật post + tags. Tất cả fields optional.

---

### DELETE /api/manage/forum/posts/{id}

Xóa post (via service).

---

### POST /api/manage/forum/posts/{id}/status

**Body:**
```json
{
  "status": "required|in:draft,published,hidden,locked",
  "is_featured": "nullable|boolean",
  "is_sticky": "nullable|boolean"
}
```

---

### PATCH /api/manage/forum/posts/bulk/status

**Body:**
```json
{
  "ids": "required|array",
  "status": "required|in:draft,published,hidden,locked"
}
```

---

### DELETE /api/manage/forum/posts/bulk

**Body:**
```json
{ "ids": "required|array" }
```

---

## 2.3 Comments

### GET /api/manage/forum/comments

**Query params:** search (content/author_name), status (published/pending/hidden/spam), post_id, is_best_answer, is_root, sort_by (created_at, likes_count, replies_count).

---

### GET /api/manage/forum/comments/{id}

Chi tiết comment: post, customer, parent.

---

### POST /api/manage/forum/comments/{id}/status

**Body:**
```json
{ "status": "required|in:published,pending,hidden,spam" }
```

---

### DELETE /api/manage/forum/comments/{id}

Xóa comment (via service).

---

### PATCH /api/manage/forum/comments/bulk/status

**Body:**
```json
{
  "ids": "required|array",
  "status": "required|in:published,pending,hidden,spam"
}
```

---

### DELETE /api/manage/forum/comments/bulk

**Body:**
```json
{ "ids": "required|array" }
```

---

## 2.4 Categories

### GET /api/manage/forum/categories

**Query params:** is_active (bool). Trả về ordered list (không paginate).

---

### POST /api/manage/forum/categories

**Body:**
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
Auto-generates slug từ name.

---

### PUT /api/manage/forum/categories/{id}

Cập nhật category fields.

---

### DELETE /api/manage/forum/categories/{id}

Xóa category. **Blocked** nếu có posts (HTTP 422).

---

## 2.5 Tags

### GET /api/manage/forum/tags

**Query params:** search (name), sort_by (name, posts_count). Paginated (default: 50, max: 200).

---

### POST /api/manage/forum/tags

**Body:**
```json
{
  "name": "required|string|max:100",
  "color": "nullable|string"
}
```
Checks slug uniqueness.

---

### PUT /api/manage/forum/tags/{id}

**Body:** name, color.

---

### DELETE /api/manage/forum/tags/{id}

Xóa tag (detach from posts first).

---

### DELETE /api/manage/forum/tags/bulk

**Body:**
```json
{ "ids": "required|array" }
```

---

## 2.6 Reports

### GET /api/manage/forum/reports

**Query params:** status, reason, type (post/comment via morph map).

---

### POST /api/manage/forum/reports/{id}/resolve

**Body:**
```json
{
  "status": "required|in:reviewed,resolved,dismissed",
  "notes": "nullable|string"
}
```

---

### PATCH /api/manage/forum/reports/bulk/resolve

**Body:**
```json
{
  "ids": "required|array",
  "status": "required|in:reviewed,resolved,dismissed",
  "notes": "nullable|string"
}
```

---

## 2.7 Leaderboard

### GET /api/manage/forum/leaderboard

**Query params:**
- `period`: `all` | `month` (default: all)
- `limit`: int (default: 20, max: 100)

---

# MODULE 3: JOB MANAGEMENT API

**Prefix:** `/api/manage/`  
**Auth:** `X-Api-Key`  
**Controllers:** `JobManageController`, `CandidateManageController`, `CompanyManageController`

---

## 3.1 Jobs

### GET /api/manage/jobs

**Query params:**
| Param | Type | Description |
|-------|------|-------------|
| search | string | Tìm kiếm |
| job_type | string | Loại công việc |
| location | string | Địa điểm |
| experience_level | string | Cấp độ kinh nghiệm |
| is_featured | bool | Tin nổi bật |
| is_remote | bool | Remote |
| status | string | draft, active, paused, archived |
| sort_by | string | Sắp xếp |
| sort_dir | string | asc, desc |

**Note:** Tự động filter theo `created_by = auth_admin.id`.

---

### GET /api/manage/jobs/statistics

Thống kê jobs của admin hiện tại.

---

### GET /api/manage/jobs/{slug}

Chi tiết job posting (lookup by slug).

---

### POST /api/manage/jobs

Tạo tin tuyển dụng.

**Body:**
```json
{
  "title": "required|string|max:255",
  "description": "required|string",
  "short_description": "nullable|string|max:500",
  "job_type": "nullable|string|max:50",
  "experience_level": "nullable|string|max:50",
  "salary_range": "nullable|string",
  "salary_min": "nullable|numeric|min:0",
  "salary_max": "nullable|numeric|min:0|gte:salary_min",
  "location": "nullable|string",
  "is_remote": "nullable|boolean",
  "education_level": "nullable|string|max:50",
  "english_level": "nullable|string|max:50",
  "company_name": "nullable|string|max:255",
  "company_id": "nullable|int|exists:companies,id",
  "company_size": "nullable|string|max:50",
  "contact_email": "nullable|email",
  "contact_phone": "nullable|string|max:20",
  "application_method": "nullable|string",
  "application_url": "nullable|url",
  "application_deadline": "nullable|date|after:today",
  "is_featured": "nullable|boolean",
  "is_urgent": "nullable|boolean",
  "status": "nullable|in:draft,active",
  "skills": "nullable|array|max:20",
  "benefits": "nullable|array|max:20",
  "meta_title": "nullable|string|max:255",
  "meta_description": "nullable|string|max:500"
}
```

---

### PUT /api/manage/jobs/{slug}

Cập nhật job. Tất cả fields optional. Status cho phép: `draft, active, paused, archived`.

---

### DELETE /api/manage/jobs/{slug}

Xóa job posting.

---

### POST /api/manage/jobs/{slug}/status

**Body:**
```json
{ "status": "required|in:draft,active,paused,archived" }
```

**Logic:**
- `active`: gọi `service->publish()`
- `paused`: gọi `service->unpublish()`
- Khác: update trực tiếp

---

## 3.2 Candidates

### GET /api/manage/candidates

**Query params:** job_posting_id, status, search (applicant_name/email).

**Note:** Chỉ trả về candidates của jobs thuộc admin hiện tại.

---

### GET /api/manage/candidates/statistics

**Query params:** job_posting_id (optional, nếu không có → tổng hợp tất cả).

**Response:**
```json
{
  "total": 50,
  "pending": 20,
  "reviewed": 10,
  "shortlisted": 8,
  "accepted": 7,
  "rejected": 5
}
```

---

### GET /api/manage/candidates/{id}

Chi tiết đơn ứng tuyển + job posting info.

---

### PATCH /api/manage/candidates/{id}/status

**Body:**
```json
{
  "status": "required|in:pending,reviewed,shortlisted,accepted,rejected",
  "notes": "nullable|string|max:2000"
}
```

---

### DELETE /api/manage/candidates/{id}

Xóa đơn ứng tuyển.

---

## 3.3 Companies

### GET /api/manage/companies

**Query params:** search (name/industry).

**Note:** Chỉ trả về companies của admin hiện tại (`created_by_admin_id`).

---

### GET /api/manage/companies/{id}

Chi tiết công ty.

---

### POST /api/manage/companies

Tạo công ty mới.

**Body (multipart/form-data):**
```json
{
  "name": "required|string|max:255",
  "description": "nullable|string",
  "website": "nullable|url",
  "email": "nullable|email",
  "phone": "nullable|string|max:20",
  "address": "nullable|string|max:500",
  "employee_count": "nullable|int|min:1",
  "founded_year": "nullable|int|min:1900|max:current_year",
  "industry": "nullable|string|max:100",
  "logo": "nullable|file|mimes:jpg,jpeg,png,webp,svg|max:2048"
}
```

---

### POST /api/manage/companies/{id}

Cập nhật công ty (POST vì hỗ trợ file upload).

---

### DELETE /api/manage/companies/{id}

Xóa công ty.

---

# MODULE 4: REVIEWS & HIRE REQUEST API

**Prefix:** `/api/v1/`  
**Auth:** Mixed (public read, sanctum write)  
**Controllers:** `SourceGameReviewController`, `HireRequestController`

---

## 4.1 Source Game Reviews

### GET /api/v1/source-game/{productId}/reviews

**Public.** Danh sách reviews của sản phẩm.

**Query params:**
- `per_page`: int (default: 10)
- `sort_by`: `created_at` | `rating` | `helpful_count`

---

### GET /api/v1/source-game/{productId}/review-stats

**Public.** Thống kê đánh giá.

**Response:**
```json
{
  "status": "success",
  "data": {
    "avg_rating": 4.2,
    "total": 25,
    "distribution": { "5": 10, "4": 8, "3": 4, "2": 2, "1": 1 }
  }
}
```

---

### POST /api/v1/source-game/{productId}/reviews

**Auth: sanctum.** Tạo review.

**Body:**
```json
{
  "rating": "required|int|between:1,5",
  "title": "nullable|string|max:255",
  "content": "required|string|max:5000",
  "pros": "nullable|string|max:1000",
  "cons": "nullable|string|max:1000"
}
```

**Logic:**
- Mỗi customer chỉ review 1 lần/product
- Auto-detect `is_verified_purchase` (check completed orders)
- Error 422 nếu đã review

---

### POST /api/v1/reviews/{id}/helpful

**Auth: sanctum.** Toggle helpful count.

**Response:**
```json
{ "status": "success", "data": { "helpful_count": 5 } }
```

---

## 4.2 Hire Request

### POST /api/v1/hire-request

**Public.** Gửi yêu cầu báo giá/thuê dev.

**Rate limit:** 3 requests / 60 phút.

**Body:**
```json
{
  "name": "required|string|max:100",
  "email": "required|email|max:255",
  "phone": "nullable|string|max:20",
  "company": "nullable|string|max:255",
  "project_type": "required|in:game,web,app,ai,other",
  "budget_range": "nullable|string|max:50",
  "description": "required|string|max:5000"
}
```

**Side effects:** Gửi email thông báo cho admin (`NewHireRequestMail`).

**Response:**
```json
{
  "status": "success",
  "message": "Yêu cầu báo giá đã được gửi thành công. Chúng tôi sẽ liên hệ bạn sớm nhất!",
  "data": { ... }
}
```

---

# CODE REVIEW NOTES

## Điểm tốt ✅

1. **Consistent response format** - Tất cả API đều dùng `{status, data, meta}` format
2. **Rate limiting** - Phân biệt read (60/min) vs write (10/min)
3. **Input validation** - Đầy đủ validation rules cho mọi endpoint
4. **SQL injection prevention** - Escape `%` và `_` trong search queries
5. **Authorization scoping** - Job/Company APIs scope theo `created_by = admin.id`
6. **Status transition validation** - Order status có transition rules rõ ràng
7. **Cascade operations** - Suspend customer → cascade suspend seller
8. **Business logic separation** - Service layer cho complex operations

## Cần cải thiện ⚠️

1. **toggleHelpful không track per-user** - Hiện tại chỉ increment, user có thể spam helpful
2. **Hardcoded locale** - `locale=vi`, `channel=default` hardcoded trong ProductManageController
3. **N+1 queries potential** - Một số list endpoints load relations trong loop
4. **Missing pagination validation** - `per_page` không validate max ở một số endpoints
5. **Company update dùng POST thay PUT** - Route file dùng `POST /{id}` cho update (do file upload), nhưng không consistent với pattern khác
6. **Withdrawal balance check** - Chỉ check khi approve, không lock balance (race condition potential)
7. **Forum bulk operations** - Không giới hạn số lượng IDs trong bulk request

## Business Logic Summary

### Seller Lifecycle
```
pending → [approve] → active → [suspend] → suspended → [activate] → active
pending → [reject] → rejected → [activate] → active
```

### Order Lifecycle
```
pending → processing → completed → closed
pending → canceled
processing → canceled
```

### Withdrawal Lifecycle
```
pending → processing → completed
pending → rejected
processing → rejected
```

### Product Review Flow
```
Seller submit → pending_review=true → Admin approve → published
                                    → Admin reject → draft + email
```

### Candidate Status Flow
```
pending → reviewed → shortlisted → accepted
                                 → rejected
```
