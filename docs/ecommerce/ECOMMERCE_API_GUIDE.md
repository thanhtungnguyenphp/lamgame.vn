# E-Commerce Management API Guide

> Cập nhật: 2026-05-06 | Dựa trên code thực tế

## Tổng quan

API quản lý e-commerce cho platform LamGame (marketplace source game).

- **Base URL:** `/api/manage/`
- **Auth:** Header `X-Api-Key: {admin_api_token}`
- **Rate limit:** Read 60/min, Write 10/min
- **Route file:** `routes/api-ecommerce-manage.php`

## Controllers

| Controller | Responsibility |
|-----------|---------------|
| DashboardManageController | Thống kê tổng hợp |
| ProductManageController | CRUD sản phẩm + review flow |
| OrderManageController | Quản lý đơn hàng + status transitions |
| SellerManageController | Quản lý seller lifecycle |
| EarningManageController | Xem earnings + thống kê doanh thu |
| WithdrawalManageController | Duyệt/hoàn tất rút tiền |
| CustomerManageController | Quản lý khách hàng |

## Response Format

```json
{
  "status": "success",
  "data": { ... },
  "meta": { "current_page": 1, "last_page": 5, "per_page": 15, "total": 72 }
}
```

Error:
```json
{ "status": "error", "message": "Mô tả lỗi" }
```

---

## 1. Dashboard

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | /api/manage/dashboard | Thống kê tổng hợp platform |

### GET /api/manage/dashboard

**Response fields:**
- `products`: total, published, pending_review, draft
- `orders`: total, completed, pending, processing, canceled
- `revenue`: total, this_month, today, platform_earnings, seller_earnings
- `sellers`: total, active, pending, suspended
- `customers`: total, this_month, with_orders
- `withdrawals`: pending_count, pending_amount, completed_this_month
- `jobs`: total, active
- `subscriptions`: active, mrr

---

## 2. Products (8 endpoints)

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | /products | Danh sách sản phẩm |
| GET | /products/statistics | Thống kê sản phẩm |
| GET | /products/{id} | Chi tiết sản phẩm |
| POST | /products | Tạo sản phẩm |
| PUT | /products/{id} | Cập nhật sản phẩm |
| DELETE | /products/{id} | Xóa sản phẩm |
| POST | /products/{id}/status | Đổi trạng thái |
| POST | /products/{id}/review | Duyệt/từ chối |
| POST | /products/{id}/images | Upload hình ảnh |
| DELETE | /products/{id}/images/{imageId} | Xóa hình |
| POST | /products/{id}/images/reorder | Sắp xếp hình |

### Filters (GET /products)
- `search`: tìm theo name/sku
- `status`: 0 (draft) | 1 (published)
- `pending_review`: boolean
- `seller_id`: int
- `category_id`: int
- `sort_by`: created_at | price | name
- `sort_dir`: asc | desc

### Create Product (POST /products)
```json
{
  "sku": "required|unique",
  "name": "required|max:255",
  "description": "required",
  "price": "required|numeric|min:0",
  "short_description": "nullable",
  "url_key": "nullable|unique",
  "special_price": "nullable|numeric",
  "special_price_from": "nullable|date",
  "special_price_to": "nullable|date",
  "seller_id": "nullable|exists:source_game_sellers,id",
  "category_ids": "nullable|array",
  "status": "nullable|0|1",
  "attributes": {
    "game_engine": "nullable",
    "programming_language": "nullable",
    "file_size": "nullable",
    "version": "nullable",
    "video_demo_url": "nullable",
    "demo_url": "nullable",
    "author_name": "nullable"
  },
  "meta_title": "nullable",
  "meta_description": "nullable",
  "meta_keywords": "nullable"
}
```

**Logic:** Nếu `seller_id` → auto set `pending_review=true`.

### Review Product (POST /products/{id}/review)
```json
{ "action": "approve|reject", "rejection_reason": "required if reject" }
```
- Approve: status=1, email ProductApproved
- Reject: status=0, save reason, email ProductRejected

### Upload Images (POST /products/{id}/images) — NEW
**Content-Type: multipart/form-data**
```json
{
  "images": "required|array|min:1|max:10",
  "images.*": "image|mimes:jpeg,jpg,png,gif,webp|max:5120"
}
```
Auto-convert sang WebP. Response trả về array `{id, path, url, position}`.

### Delete Image (DELETE /products/{id}/images/{imageId}) — NEW
Xóa 1 hình khỏi product. Xóa cả file trên storage.

### Reorder Images (POST /products/{id}/images/reorder) — NEW
```json
{ "image_ids": [3, 1, 2] }
```
Sắp xếp lại thứ tự hình theo array position.

### Delete Product
Blocked nếu có orders → HTTP 422.

---

## 3. Orders (5 endpoints)

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | /orders | Danh sách đơn hàng |
| GET | /orders/statistics | Thống kê |
| GET | /orders/{id} | Chi tiết |
| POST | /orders/{id}/status | Đổi trạng thái |
| POST | /orders/{id}/comment | Thêm comment |

### Status Transitions
```
pending → processing | canceled
processing → completed | canceled
completed → closed
```

**Side effects:**
- `completed`: auto-create SourceGameEarning
- `canceled`: mark earnings as refunded

---

## 4. Sellers (8 endpoints)

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | /sellers | Danh sách |
| GET | /sellers/statistics | Thống kê |
| GET | /sellers/{id} | Chi tiết |
| PUT | /sellers/{id} | Cập nhật profile |
| POST | /sellers/{id}/approve | Duyệt seller |
| POST | /sellers/{id}/reject | Từ chối |
| POST | /sellers/{id}/suspend | Tạm ngưng |
| POST | /sellers/{id}/activate | Kích hoạt lại |

### Seller Lifecycle
```
pending → approve → active
pending → reject → rejected
active → suspend → suspended (ẩn tất cả products)
suspended/rejected → activate → active (restore products)
```

---

## 5. Earnings (2 endpoints)

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | /earnings | Danh sách earnings |
| GET | /earnings/statistics | Thống kê doanh thu |

### Filters
- seller_id, status, date_from, date_to
- sort_by: created_at | order_amount | seller_amount

### Statistics Response
- total_revenue, platform_earnings, seller_earnings
- by_status (pending/completed/refunded)
- this_month, last_month comparisons
- top_products (10), top_sellers (10)
- monthly_trend (12 months)

---

## 6. Withdrawals (5 endpoints)

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | /withdrawals | Danh sách |
| GET | /withdrawals/{id} | Chi tiết |
| POST | /withdrawals/{id}/approve | Duyệt |
| POST | /withdrawals/{id}/complete | Hoàn tất |
| POST | /withdrawals/{id}/reject | Từ chối |

### Withdrawal Flow
```
pending → approve → processing → complete → completed
pending/processing → reject → rejected
```

### Complete Request
```json
{ "transaction_id": "required", "admin_note": "nullable" }
```

---

## 7. Customers (6 endpoints)

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | /customers | Danh sách |
| GET | /customers/statistics | Thống kê |
| GET | /customers/{id} | Chi tiết |
| PUT | /customers/{id} | Cập nhật |
| POST | /customers/{id}/suspend | Tạm ngưng |
| POST | /customers/{id}/activate | Kích hoạt |

### Cascade Operations
- Suspend customer → cascade suspend seller
- Activate customer → cascade activate seller

---

## Database Schema (Key Tables)

- `products` + `product_flat` (EAV architecture, Bagisto)
- `product_attribute_values` (custom attributes)
- `orders` + `order_items`
- `source_game_sellers` (seller profiles)
- `source_game_earnings` (revenue split)
- `source_game_withdrawals` (payout requests)
- `customers` (Webkul\Customer)
- `user_subscriptions` + `subscription_plans`

## Business Flows

### Purchase & Earnings
1. Customer mua product → Order created (pending)
2. Admin change status → completed
3. System auto-create SourceGameEarning (split platform_fee / seller_amount)
4. Seller request withdrawal
5. Admin approve → processing → complete (with transaction_id)

### Seller Onboarding
1. Customer register as seller → pending
2. Admin review → approve/reject
3. Approved seller can submit products
4. Products go through review → approve/reject
5. Approved products are published
