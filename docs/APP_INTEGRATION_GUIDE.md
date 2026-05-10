# LamGame App Integration Guide

> Cập nhật: 2026-05-06 | Hướng dẫn tích hợp API cho mobile app
> Base URL Production: `https://lamgame.vn/api`

---

## Cấu hình chung

### Authentication

```
Header: X-Api-Key: {admin_api_token}
```

Token lấy từ bảng `admins.api_token` (đã hash SHA-256 phía server).

### Base Headers

```http
X-Api-Key: your-api-key-here
Accept: application/json
Content-Type: application/json
```

Với upload file:
```http
Content-Type: multipart/form-data
```

### Rate Limits

| Loại | Limit | Header trả về |
|------|-------|---------------|
| Read (GET) | 60 req/min | X-RateLimit-Remaining |
| Write (POST/PUT/DELETE) | 10 req/min | X-RateLimit-Remaining |
| File upload | 5 req/min | X-RateLimit-Remaining |

### Response Format

**Success:**
```json
{
  "status": "success",
  "message": "Optional message",
  "data": { ... },
  "meta": { "current_page": 1, "last_page": 5, "per_page": 15, "total": 72 }
}
```

**Error:**
```json
{ "status": "error", "message": "Mô tả lỗi tiếng Việt" }
```

**HTTP Status Codes:**
- 200: Success
- 201: Created
- 401: Invalid API key
- 404: Not found
- 422: Validation error / Business logic error
- 429: Rate limit exceeded

---

## PHẦN 1: DASHBOARD

### GET /api/manage/dashboard

**Mục đích:** Hiển thị tổng quan trên trang chủ app.

**Request:**
```http
GET /api/manage/dashboard
X-Api-Key: your-key
```

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

**Gợi ý UI:** Card grid hiển thị từng nhóm metric. Revenue format VND.

---

## PHẦN 2: QUẢN LÝ SẢN PHẨM

### 2.1 Danh sách sản phẩm

**GET /api/manage/products**

```http
GET /api/manage/products?search=unity&status=1&per_page=20&page=1&sort_by=created_at&sort_dir=desc
```

**Params:**
| Param | Type | Mô tả |
|-------|------|--------|
| search | string | Tìm theo tên/SKU |
| status | 0\|1 | 0=draft, 1=published |
| pending_review | 1 | Chỉ lấy sản phẩm chờ duyệt |
| seller_id | int | Lọc theo seller |
| category_id | int | Lọc theo danh mục |
| sort_by | string | created_at, price, name |
| sort_dir | string | asc, desc |
| per_page | int | 1-100, default 15 |
| page | int | Trang |

**Response data item:**
```json
{
  "id": 5,
  "sku": "SG-005",
  "name": "Unity 2D Platformer",
  "price": 500000,
  "special_price": 350000,
  "status": 1,
  "pending_review": false,
  "seller": { "id": 2, "shop_name": "GameDev Studio" },
  "thumbnail": "/storage/product/5/thumb.webp",
  "created_at": "2026-04-15T10:00:00Z"
}
```

---

### 2.2 Chi tiết sản phẩm

**GET /api/manage/products/{id}**

```http
GET /api/manage/products/5
```

**Response:** Full product data bao gồm images, categories, downloadable_links, custom attributes, seller info, stats, meta SEO.

---

### 2.3 Tạo sản phẩm

**POST /api/manage/products**

```http
POST /api/manage/products
Content-Type: application/json

{
  "sku": "SG-NEW-001",
  "name": "Game Source Unity RPG",
  "description": "<p>Full source code game RPG...</p>",
  "price": 1500000,
  "short_description": "Source code game RPG hoàn chỉnh",
  "url_key": "game-source-unity-rpg",
  "special_price": 1200000,
  "special_price_from": "2026-05-01",
  "special_price_to": "2026-05-31",
  "seller_id": 2,
  "category_ids": [3, 5],
  "status": 1,
  "attributes": {
    "game_engine": "Unity",
    "programming_language": "C#",
    "file_size": "250MB",
    "version": "1.0.0",
    "demo_url": "https://demo.lamgame.vn/rpg",
    "video_demo_url": "https://youtube.com/watch?v=xxx"
  },
  "meta_title": "Source Game Unity RPG",
  "meta_description": "Mua source code game RPG Unity"
}
```

**Lưu ý:**
- Nếu có `seller_id` → sản phẩm tự động vào trạng thái `pending_review`
- `url_key` phải unique, nếu không truyền sẽ auto-generate từ name
- Product type luôn là `downloadable`

**Response:** 201 + product data

---

### 2.4 Cập nhật sản phẩm

**PUT /api/manage/products/{id}**

Chỉ gửi fields cần update:
```json
{ "price": 1800000, "status": 1 }
```

---

### 2.5 Xóa sản phẩm

**DELETE /api/manage/products/{id}**

⚠️ Trả 422 nếu sản phẩm đã có đơn hàng.

---

### 2.6 Đổi trạng thái

**POST /api/manage/products/{id}/status**

```json
{ "status": 1 }
```
- `0` = draft (ẩn)
- `1` = published (hiển thị)
- Không thể publish nếu `pending_review=true`

---

### 2.7 Duyệt sản phẩm (Review)

**POST /api/manage/products/{id}/review**

Approve:
```json
{ "action": "approve" }
```

Reject:
```json
{ "action": "reject", "rejection_reason": "Hình ảnh không rõ ràng" }
```

**Side effects:** Gửi email cho seller.

---

### 2.8 Upload hình ảnh

**POST /api/manage/products/{id}/images**

```http
POST /api/manage/products/5/images
Content-Type: multipart/form-data

images[0]: (file) screenshot1.png
images[1]: (file) screenshot2.jpg
```

**Validation:**
- Max 10 files/request
- Mỗi file max 5MB
- Formats: jpeg, jpg, png, gif, webp
- Auto-convert sang WebP

**Response:**
```json
{
  "status": "success",
  "message": "Đã upload 2 hình.",
  "data": [
    { "id": 10, "path": "product/5/abc123.webp", "url": "/storage/product/5/abc123.webp", "position": 1 },
    { "id": 11, "path": "product/5/def456.webp", "url": "/storage/product/5/def456.webp", "position": 2 }
  ]
}
```

---

### 2.9 Xóa hình ảnh

**DELETE /api/manage/products/{id}/images/{imageId}**

```http
DELETE /api/manage/products/5/images/10
```

---

### 2.10 Sắp xếp hình ảnh

**POST /api/manage/products/{id}/images/reorder**

```json
{ "image_ids": [11, 10, 12] }
```
Thứ tự trong array = position mới (1, 2, 3...).

---

### 2.11 Danh sách Downloadable Links

**GET /api/manage/products/{id}/downloadable-links**

```http
GET /api/manage/products/5/downloadable-links
```

**Response:**
```json
{
  "status": "success",
  "data": [
    {
      "id": 1,
      "title": "Source Code v1.0",
      "type": "file",
      "file_name": "unity-rpg-v1.0.zip",
      "price": 0,
      "downloads": 0,
      "sort_order": 0,
      "created_at": "2026-05-01T10:00:00Z"
    }
  ]
}
```

---

### 2.12 Upload Downloadable Link (Source File)

**POST /api/manage/products/{id}/downloadable-links**

**Cách 1: Upload file**
```http
POST /api/manage/products/5/downloadable-links
Content-Type: multipart/form-data

title: Source Code v1.0
file: (binary) unity-rpg-v1.0.zip
price: 0
downloads: 0
sort_order: 0
```

**Cách 2: External URL**
```http
POST /api/manage/products/5/downloadable-links
Content-Type: application/json

{
  "title": "Source Code v1.0",
  "url": "https://drive.google.com/file/xxx",
  "price": 0,
  "downloads": 0
}
```

**Validation:**
- `title`: required, max 255
- `file`: max 100MB (required nếu không có url)
- `url`: required nếu không có file
- `price`: optional, default 0 (phụ phí cho link này)
- `downloads`: optional, 0 = unlimited
- `sort_order`: optional

**Lưu ý:** Chỉ hoạt động với product type `downloadable`. File lưu vào private storage (không public access).

**Response:** 201
```json
{
  "status": "success",
  "message": "Đã upload file download.",
  "data": {
    "id": 1,
    "title": "Source Code v1.0",
    "type": "file",
    "file_name": "unity-rpg-v1.0.zip",
    "price": 0,
    "downloads": 0
  }
}
```

---

### 2.13 Update Downloadable Link

**PUT /api/manage/products/{id}/downloadable-links/{linkId}**

```http
PUT /api/manage/products/5/downloadable-links/1
Content-Type: multipart/form-data

title: Source Code v1.1 (Updated)
file: (binary) unity-rpg-v1.1.zip
```

Hoặc chỉ update metadata:
```json
{ "title": "New Title", "price": 50000, "downloads": 5 }
```

**Lưu ý:** Upload file mới sẽ tự động xóa file cũ.

---

### 2.14 Xóa Downloadable Link

**DELETE /api/manage/products/{id}/downloadable-links/{linkId}**

Xóa link + file trên storage + translations.

---

### 2.15 Upload Sample (Preview File)

**POST /api/manage/products/{id}/downloadable-samples**

```http
POST /api/manage/products/5/downloadable-samples
Content-Type: multipart/form-data

title: Demo Preview
file: (binary) demo-preview.zip
sort_order: 0
```

Hoặc URL:
```json
{ "title": "Demo Preview", "url": "https://example.com/demo.zip" }
```

**Validation:**
- `title`: required, max 255
- `file`: max 20MB
- `url`: required nếu không có file

**Response:** 201

---

### 2.16 Xóa Sample

**DELETE /api/manage/products/{id}/downloadable-samples/{sampleId}**

---

### 2.17 Thống kê sản phẩm

**GET /api/manage/products/statistics**

**Response:**
```json
{
  "status": "success",
  "data": {
    "totals": { "total": 150, "published": 120, "draft": 20, "pending_review": 10 },
    "by_category": [{ "category_id": 3, "name": "Unity", "count": 45 }],
    "by_seller": [{ "seller_id": 2, "shop_name": "GameDev", "count": 30 }],
    "top_selling": [{ "id": 5, "name": "RPG Source", "purchase_count": 50 }]
  }
}
```

---

## PHẦN 3: QUẢN LÝ ĐƠN HÀNG

### 3.1 Danh sách đơn hàng

**GET /api/manage/orders**

```http
GET /api/manage/orders?status=pending&date_from=2026-05-01&per_page=20
```

**Params:**
| Param | Type | Mô tả |
|-------|------|--------|
| search | string | Tìm theo mã đơn/email/tên |
| status | string | pending, processing, completed, canceled, closed |
| payment_method | string | paypal, bank_transfer... |
| date_from | date | YYYY-MM-DD |
| date_to | date | YYYY-MM-DD |
| customer_id | int | Lọc theo khách |
| sort_by | string | created_at, grand_total, increment_id |

---

### 3.2 Chi tiết đơn hàng

**GET /api/manage/orders/{id}**

**Response bao gồm:**
- Customer info (name, email)
- Items array (product_id, name, sku, qty, price, total)
- Payment method + billing address
- Financial: sub_total, discount, tax, grand_total, currency
- Comments history
- Invoices
- Earnings breakdown by seller

---

### 3.3 Đổi trạng thái đơn hàng

**POST /api/manage/orders/{id}/status**

```json
{ "status": "completed" }
```

**Transition rules (quan trọng):**
```
pending    → processing, canceled
processing → completed, canceled
completed  → closed
```

Gửi status không hợp lệ → 422.

**Side effects:**
- `completed`: Tự động tạo earnings cho seller
- `canceled`: Đánh dấu earnings liên quan là `refunded`

---

### 3.4 Thêm comment

**POST /api/manage/orders/{id}/comment**

```json
{
  "comment": "Đã xác nhận thanh toán",
  "customer_notified": true
}
```

---

### 3.5 Thống kê đơn hàng

**GET /api/manage/orders/statistics**

**Response:**
```json
{
  "data": {
    "total": 500,
    "by_status": { "pending": 50, "processing": 30, "completed": 400, "canceled": 20 },
    "revenue": { "total": 50000000, "this_month": 5000000, "last_month": 4500000, "today": 200000 },
    "avg_order_value": 100000,
    "by_payment_method": { "paypal": 300, "bank_transfer": 200 },
    "recent_7_days": [
      { "date": "2026-05-06", "orders": 5, "revenue": 500000 }
    ]
  }
}
```

---

## PHẦN 4: QUẢN LÝ SELLER

### 4.1 Danh sách seller

**GET /api/manage/sellers**

```http
GET /api/manage/sellers?status=active&sort_by=total_revenue&sort_dir=desc
```

**Params:** search, status (pending/active/suspended/rejected), business_type, verified (bool), sort_by (created_at, total_revenue, total_products, rating_avg).

---

### 4.2 Chi tiết seller

**GET /api/manage/sellers/{id}**

**Response bao gồm:**
- Customer info
- Shop: name, slug, description, logo, banner
- Contact: email, phone, website
- Business type, verification status
- Financial: total_earnings, total_withdrawn, pending_withdrawals, available_balance
- Bank info
- Recent products (5)

---

### 4.3 Cập nhật seller

**PUT /api/manage/sellers/{id}**

```json
{
  "shop_name": "New Shop Name",
  "contact_email": "new@email.com",
  "bank_name": "Vietcombank",
  "bank_account": "1234567890",
  "bank_holder": "NGUYEN VAN A"
}
```

---

### 4.4 Duyệt seller

**POST /api/manage/sellers/{id}/approve**

Không cần body. Set `status=active`, `verified=true`.

---

### 4.5 Từ chối seller

**POST /api/manage/sellers/{id}/reject**

```json
{ "reason": "Thông tin không đầy đủ" }
```

---

### 4.6 Tạm ngưng seller

**POST /api/manage/sellers/{id}/suspend**

```json
{ "reason": "Vi phạm chính sách" }
```

⚠️ Tự động ẩn TẤT CẢ sản phẩm của seller.

---

### 4.7 Kích hoạt lại

**POST /api/manage/sellers/{id}/activate**

Restore products (trừ những cái đang pending_review).

---

### 4.8 Thống kê seller

**GET /api/manage/sellers/statistics**

---

## PHẦN 5: EARNINGS & WITHDRAWALS

### 5.1 Danh sách earnings

**GET /api/manage/earnings**

```http
GET /api/manage/earnings?seller_id=2&status=completed&date_from=2026-05-01
```

---

### 5.2 Thống kê earnings

**GET /api/manage/earnings/statistics**

**Response:** total_revenue, platform_earnings, seller_earnings, by_status, monthly_trend (12 tháng), top_products, top_sellers.

---

### 5.3 Danh sách withdrawals

**GET /api/manage/withdrawals**

**Params:** seller_id, status (pending/processing/completed/rejected), date_from, date_to.

---

### 5.4 Chi tiết withdrawal

**GET /api/manage/withdrawals/{id}**

Bao gồm: seller info, amount, bank_info, available_balance.

---

### 5.5 Duyệt withdrawal

**POST /api/manage/withdrawals/{id}/approve**

Pending → Processing. Server validate seller có đủ balance.

---

### 5.6 Hoàn tất withdrawal

**POST /api/manage/withdrawals/{id}/complete**

```json
{
  "transaction_id": "VCB-20260506-001",
  "admin_note": "Đã chuyển khoản"
}
```

Processing → Completed.

---

### 5.7 Từ chối withdrawal

**POST /api/manage/withdrawals/{id}/reject**

```json
{ "admin_note": "Thông tin bank không chính xác" }
```

---

## PHẦN 6: QUẢN LÝ KHÁCH HÀNG

### 6.1 Danh sách

**GET /api/manage/customers**

**Params:** search, is_verified, is_suspended, has_orders, has_seller, sort_by.

---

### 6.2 Chi tiết

**GET /api/manage/customers/{id}**

Bao gồm: seller info, orders_summary (total_orders, total_spent, last_order_at), subscription, recent_orders.

---

### 6.3 Cập nhật

**PUT /api/manage/customers/{id}**

```json
{
  "first_name": "Văn",
  "last_name": "Nguyễn",
  "email": "van@email.com",
  "phone": "0901234567",
  "gender": "male",
  "date_of_birth": "1990-01-15",
  "notes": "VIP customer"
}
```

---

### 6.4 Tạm ngưng

**POST /api/manage/customers/{id}/suspend**

```json
{ "reason": "Spam reviews" }
```

⚠️ Cascade: tạm ngưng luôn seller (nếu có).

---

### 6.5 Kích hoạt

**POST /api/manage/customers/{id}/activate**

Cascade: kích hoạt lại seller.

---

### 6.6 Thống kê

**GET /api/manage/customers/statistics**

---

## PHẦN 7: FORUM MANAGEMENT

**Prefix:** `/api/manage/forum/`

### 7.1 Dashboard

**GET /api/manage/forum/dashboard**

---

### 7.2 Posts

| Action | Method | Endpoint | Body |
|--------|--------|----------|------|
| List | GET | /posts | ?search=&status=&type=&category_id=&sort_by= |
| Detail | GET | /posts/{id} | - |
| Create | POST | /posts | `{title, content, category_id, type?, status?, tags?, is_featured?, is_sticky?}` |
| Update | PUT | /posts/{id} | Partial update |
| Delete | DELETE | /posts/{id} | - |
| Status | POST | /posts/{id}/status | `{status, is_featured?, is_sticky?}` |
| Bulk Status | PATCH | /posts/bulk/status | `{ids: [1,2,3], status: "hidden"}` |
| Bulk Delete | DELETE | /posts/bulk | `{ids: [1,2,3]}` |

**Post types:** discussion, idea, question, showcase, job, review  
**Post statuses:** draft, published, hidden, locked

---

### 7.3 Comments

| Action | Method | Endpoint | Body |
|--------|--------|----------|------|
| List | GET | /comments | ?search=&status=&post_id=&is_best_answer= |
| Detail | GET | /comments/{id} | - |
| Status | POST | /comments/{id}/status | `{status}` |
| Delete | DELETE | /comments/{id} | - |
| Bulk Status | PATCH | /comments/bulk/status | `{ids, status}` |
| Bulk Delete | DELETE | /comments/bulk | `{ids}` |

**Comment statuses:** published, pending, hidden, spam

---

### 7.4 Categories

| Action | Method | Endpoint | Body |
|--------|--------|----------|------|
| List | GET | /categories | ?is_active=1 (không paginate) |
| Create | POST | /categories | `{name, description?, icon?, color?, sort_order?, is_active?}` |
| Update | PUT | /categories/{id} | Partial |
| Delete | DELETE | /categories/{id} | Blocked nếu có posts |

---

### 7.5 Tags

| Action | Method | Endpoint | Body |
|--------|--------|----------|------|
| List | GET | /tags | ?search=&sort_by= (paginate 50/page) |
| Create | POST | /tags | `{name, color?}` |
| Update | PUT | /tags/{id} | `{name?, color?}` |
| Delete | DELETE | /tags/{id} | - |
| Bulk Delete | DELETE | /tags/bulk | `{ids}` |

---

### 7.6 Reports

| Action | Method | Endpoint | Body |
|--------|--------|----------|------|
| List | GET | /reports | ?status=&reason=&type= |
| Resolve | POST | /reports/{id}/resolve | `{status: "resolved", notes?}` |
| Bulk Resolve | PATCH | /reports/bulk/resolve | `{ids, status, notes?}` |

**Report statuses:** pending, reviewed, resolved, dismissed

---

### 7.7 Leaderboard

**GET /api/manage/forum/leaderboard?period=month&limit=20**

---

## PHẦN 8: JOB MANAGEMENT

**Prefix:** `/api/manage/`

### 8.1 Jobs

| Action | Method | Endpoint | Body |
|--------|--------|----------|------|
| List | GET | /jobs | ?search=&job_type=&status=&is_remote= |
| Statistics | GET | /jobs/statistics | - |
| Detail | GET | /jobs/{slug} | - |
| Create | POST | /jobs | Full body (xem bên dưới) |
| Update | PUT | /jobs/{slug} | Partial |
| Delete | DELETE | /jobs/{slug} | - |
| Status | POST | /jobs/{slug}/status | `{status}` |

**Create Job body:**
```json
{
  "title": "Senior Unity Developer",
  "description": "<p>Mô tả chi tiết...</p>",
  "job_type": "full-time",
  "experience_level": "senior",
  "salary_min": 25000000,
  "salary_max": 40000000,
  "location": "Hồ Chí Minh",
  "is_remote": true,
  "company_id": 1,
  "contact_email": "hr@company.com",
  "application_deadline": "2026-06-30",
  "is_featured": true,
  "status": "active",
  "skills": ["Unity", "C#", "Multiplayer"],
  "benefits": ["Lương tháng 13", "Bảo hiểm"]
}
```

**Job statuses:** draft, active, paused, archived  
**Note:** Tất cả jobs scope theo admin hiện tại.

---

### 8.2 Candidates

| Action | Method | Endpoint | Body |
|--------|--------|----------|------|
| List | GET | /candidates | ?job_posting_id=&status=&search= |
| Statistics | GET | /candidates/statistics | ?job_posting_id= |
| Detail | GET | /candidates/{id} | - |
| Update Status | PATCH | /candidates/{id}/status | `{status, notes?}` |
| Delete | DELETE | /candidates/{id} | - |

**Candidate statuses:** pending → reviewed → shortlisted → accepted/rejected

---

### 8.3 Companies

| Action | Method | Endpoint | Body |
|--------|--------|----------|------|
| List | GET | /companies | ?search= |
| Detail | GET | /companies/{id} | - |
| Create | POST | /companies | multipart (có logo file) |
| Update | POST | /companies/{id} | multipart (POST vì file upload) |
| Delete | DELETE | /companies/{id} | - |

**Create Company (multipart/form-data):**
```
name: Công ty ABC
description: Mô tả...
website: https://abc.com
email: contact@abc.com
phone: 0901234567
employee_count: 50
founded_year: 2020
industry: Game Development
logo: (file) logo.png
```

---

## PHẦN 9: REVIEWS & HIRE REQUEST

**Prefix:** `/api/v1/`  
**Auth:** Mixed

### 9.1 Danh sách reviews (Public)

**GET /api/v1/source-game/{productId}/reviews?per_page=10&sort_by=created_at**

Sort options: created_at, rating, helpful_count

---

### 9.2 Thống kê reviews (Public)

**GET /api/v1/source-game/{productId}/review-stats**

```json
{
  "data": {
    "avg_rating": 4.2,
    "total": 25,
    "distribution": { "5": 10, "4": 8, "3": 4, "2": 2, "1": 1 }
  }
}
```

---

### 9.3 Tạo review (Auth: Sanctum)

**POST /api/v1/source-game/{productId}/reviews**

```http
Authorization: Bearer {sanctum_token}
```

```json
{
  "rating": 5,
  "title": "Tuyệt vời",
  "content": "Source code rất clean, dễ customize",
  "pros": "Clean code, tài liệu đầy đủ",
  "cons": "Chưa có multiplayer"
}
```

⚠️ Mỗi user chỉ review 1 lần/product. Auto-detect verified purchase.

---

### 9.4 Toggle Helpful (Auth: Sanctum)

**POST /api/v1/reviews/{id}/helpful**

```http
Authorization: Bearer {sanctum_token}
```

Response: `{ "data": { "helpful_count": 5 } }`

---

### 9.5 Gửi yêu cầu báo giá (Public)

**POST /api/v1/hire-request**

Rate limit: 3 req / 60 phút.

```json
{
  "name": "Nguyễn Văn A",
  "email": "a@email.com",
  "phone": "0901234567",
  "company": "Công ty XYZ",
  "project_type": "game",
  "budget_range": "50-100 triệu",
  "description": "Cần phát triển game mobile casual..."
}
```

**project_type options:** game, web, app, ai, other

---

## FLOW TÍCH HỢP GỢI Ý

### Flow 1: Quản lý sản phẩm hoàn chỉnh

```
1. POST /products              → Tạo product (lấy product_id)
2. POST /products/{id}/images  → Upload screenshots
3. POST /products/{id}/images/reorder → Sắp xếp
4. POST /products/{id}/downloadable-links → Upload source file
5. POST /products/{id}/downloadable-samples → Upload demo
6. POST /products/{id}/status  → Publish (status=1)
```

### Flow 2: Duyệt seller + sản phẩm

```
1. GET /sellers?status=pending  → Lấy danh sách chờ duyệt
2. GET /sellers/{id}            → Xem chi tiết
3. POST /sellers/{id}/approve   → Duyệt
4. GET /products?pending_review=1 → Sản phẩm chờ duyệt
5. POST /products/{id}/review   → Approve/Reject
```

### Flow 3: Xử lý đơn hàng

```
1. GET /orders?status=pending   → Đơn mới
2. POST /orders/{id}/status     → {status: "processing"}
3. POST /orders/{id}/status     → {status: "completed"}
   → Auto tạo earnings
4. GET /withdrawals?status=pending → Yêu cầu rút tiền
5. POST /withdrawals/{id}/approve → Duyệt
6. POST /withdrawals/{id}/complete → {transaction_id: "..."}
```

### Flow 4: Quản lý forum

```
1. GET /forum/dashboard         → Overview
2. GET /forum/posts?status=published → Bài viết
3. GET /forum/reports?status=pending → Reports cần xử lý
4. POST /forum/reports/{id}/resolve → Resolve
5. POST /forum/posts/{id}/status → Ẩn bài vi phạm
```
