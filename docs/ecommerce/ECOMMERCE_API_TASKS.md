# Tài liệu Phân tích Chức năng — Bộ API E-Commerce Manage cho Ohha Studio

> **Dự án:** LAMGAME.VN — Bagisto-based Game Source Code Marketplace
> **Ngày tạo:** 28/04/2026
> **Cập nhật lần cuối:** 28/04/2026
> **Base URL:** `https://lamgame.vn/api`
> **Auth:** `X-Api-Key` header (giống Blog/Job API)
> **Route file:** `routes/api-ecommerce-manage.php`

---

## Mục lục

1. [Task List & Trạng thái](#task-list--trạng-thái)
2. [Phase 1: Foundation](#phase-1-foundation)
3. [Phase 2: Product Management](#phase-2-product-management)
4. [Phase 3: Order Management](#phase-3-order-management)
5. [Phase 4: Seller Management](#phase-4-seller-management)
6. [Phase 5: Earnings & Withdrawals](#phase-5-earnings--withdrawals)
7. [Phase 6: Customer Management](#phase-6-customer-management)
8. [Phase 7: Testing & Docs](#phase-7-testing--docs)

---

## Task List & Trạng thái

| # | Task | Phase | Trạng thái | Ngày hoàn thành |
|---|------|-------|:----------:|:---------------:|
| T1 | Tạo `routes/api-ecommerce-manage.php` + register trong `bootstrap/app.php` | 1 | ✅ DONE | 28/04/2026 |
| T2 | Tạo `DashboardManageController` — endpoint tổng quan hệ thống | 1 | ✅ DONE | 28/04/2026 |
| T3 | Tạo `ProductManageController` — list, detail, statistics | 2 | ✅ DONE | 28/04/2026 |
| T4 | `ProductManageController` — store, update, destroy (CRUD) | 2 | ✅ DONE | 28/04/2026 |
| T5 | `ProductManageController` — changeStatus, review (approve/reject) | 2 | ✅ DONE | 28/04/2026 |
| T6 | Tạo `ProductManageResource` — response format chuẩn | 2 | ✅ DONE | 28/04/2026 |
| T7 | Tạo `OrderManageController` — list, detail, statistics | 3 | ✅ DONE | 28/04/2026 |
| T8 | `OrderManageController` — changeStatus, comment | 3 | ✅ DONE | 28/04/2026 |
| T9 | Tạo `OrderManageResource` — response format | 3 | ✅ DONE | 28/04/2026 |
| T10 | Tạo `SellerManageController` — list, detail, statistics | 4 | ✅ DONE | 28/04/2026 |
| T11 | `SellerManageController` — approve, reject, suspend, activate | 4 | ✅ DONE | 28/04/2026 |
| T12 | Tạo `EarningManageController` — list, statistics | 5 | ✅ DONE | 28/04/2026 |
| T13 | Tạo `WithdrawalManageController` — list, detail, approve, complete, reject | 5 | ✅ DONE | 28/04/2026 |
| T14 | Tạo `CustomerManageController` — list, detail, statistics, suspend, activate | 6 | ✅ DONE | 28/04/2026 |
| T15 | Tạo Postman collection cho toàn bộ API | 7 | ✅ DONE | 28/04/2026 |
| T16 | Cập nhật tài liệu cuối cùng | 7 | ✅ DONE | 28/04/2026 |

**Ký hiệu:** ⬜ TODO · 🔄 DOING · ✅ DONE · ❌ BLOCKED

---

## Quy ước chung

### Response format chuẩn

```json
{
  "status": "success|error",
  "message": "Mô tả kết quả",
  "data": {},
  "meta": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 68
  }
}
```

### Auth & Middleware

- Header: `X-Api-Key: {admin_api_token}`
- Middleware: `api.key`, `throttle:60,1` (read), `throttle:10,1` (write)
- Prefix: `/api/manage/`

### Pagination mặc định

- `per_page`: 15 (min 1, max 100)
- `page`: 1
- `sort_by`: `created_at`
- `sort_dir`: `desc`

### Models tham chiếu

| Model | Namespace | Bảng DB |
|-------|-----------|---------|
| Product | `App\Models\Product` (extends Webkul) | `products` + `product_flat` (EAV) |
| Order | `Webkul\Sales\Models\Order` | `orders` |
| OrderItem | `Webkul\Sales\Models\OrderItem` | `order_items` |
| SourceGameSeller | `App\Models\SourceGameSeller` | `source_game_sellers` |
| SourceGameEarning | `App\Models\SourceGameEarning` | `source_game_earnings` |
| SourceGameWithdrawal | `App\Models\SourceGameWithdrawal` | `source_game_withdrawals` |
| Customer | `Webkul\Customer\Models\Customer` | `customers` |

---

## Phase 1: Foundation

### T1. Tạo `routes/api-ecommerce-manage.php` + register trong `bootstrap/app.php`

**Mục tiêu:** Tạo route file mới và đăng ký vào application, theo đúng pattern của `api-job-manage.php`.

**File tạo mới:**
- `routes/api-ecommerce-manage.php`

**File sửa:**
- `bootstrap/app.php` — thêm dòng register route trong `then` callback

**Chi tiết route file:**

```
routes/api-ecommerce-manage.php
├── Prefix: manage (→ /api/manage/)
├── Middleware: api.key, throttle:60,1
│
├── GET  /dashboard                          → DashboardManageController@index
│
├── /products
│   ├── GET    /                             → ProductManageController@list
│   ├── GET    /statistics                   → ProductManageController@statistics
│   ├── GET    /{id}                         → ProductManageController@detail
│   ├── POST   /                             → ProductManageController@store          [throttle:10,1]
│   ├── PUT    /{id}                         → ProductManageController@update         [throttle:10,1]
│   ├── DELETE /{id}                         → ProductManageController@destroy        [throttle:10,1]
│   ├── POST   /{id}/status                  → ProductManageController@changeStatus   [throttle:10,1]
│   └── POST   /{id}/review                  → ProductManageController@review         [throttle:10,1]
│
├── /orders
│   ├── GET    /                             → OrderManageController@list
│   ├── GET    /statistics                   → OrderManageController@statistics
│   ├── GET    /{id}                         → OrderManageController@detail
│   ├── POST   /{id}/status                  → OrderManageController@changeStatus     [throttle:10,1]
│   └── POST   /{id}/comment                 → OrderManageController@comment          [throttle:10,1]
│
├── /sellers
│   ├── GET    /                             → SellerManageController@list
│   ├── GET    /statistics                   → SellerManageController@statistics
│   ├── GET    /{id}                         → SellerManageController@detail
│   ├── POST   /{id}/approve                 → SellerManageController@approve         [throttle:10,1]
│   ├── POST   /{id}/reject                  → SellerManageController@reject          [throttle:10,1]
│   ├── POST   /{id}/suspend                 → SellerManageController@suspend         [throttle:10,1]
│   └── POST   /{id}/activate                → SellerManageController@activate        [throttle:10,1]
│
├── /earnings
│   ├── GET    /                             → EarningManageController@list
│   └── GET    /statistics                   → EarningManageController@statistics
│
├── /withdrawals
│   ├── GET    /                             → WithdrawalManageController@list
│   ├── GET    /{id}                         → WithdrawalManageController@detail
│   ├── POST   /{id}/approve                 → WithdrawalManageController@approve     [throttle:10,1]
│   ├── POST   /{id}/complete                → WithdrawalManageController@complete    [throttle:10,1]
│   └── POST   /{id}/reject                  → WithdrawalManageController@reject      [throttle:10,1]
│
└── /customers
    ├── GET    /                             → CustomerManageController@list
    ├── GET    /statistics                   → CustomerManageController@statistics
    ├── GET    /{id}                         → CustomerManageController@detail
    ├── POST   /{id}/suspend                 → CustomerManageController@suspend       [throttle:10,1]
    └── POST   /{id}/activate                → CustomerManageController@activate      [throttle:10,1]
```

**Lưu ý conflict route:** Route prefix `/api/manage/` đã được dùng bởi `api-job-manage.php` cho `/api/manage/jobs/`, `/api/manage/candidates/`, `/api/manage/companies/`. Các route mới (products, orders, sellers, earnings, withdrawals, customers, dashboard) KHÔNG trùng prefix nên không conflict.

**Register trong `bootstrap/app.php`:**

```php
// Thêm vào block then:
Route::middleware('api')->prefix('api')->group(base_path('routes/api-ecommerce-manage.php'));
```

---

### T2. Tạo `DashboardManageController` — endpoint tổng quan hệ thống

**Mục tiêu:** Cung cấp endpoint tổng quan toàn bộ hệ thống cho Ohha Studio dashboard.

**File tạo mới:**
- `app/Http/Controllers/Api/DashboardManageController.php`

**Endpoint:**

```
GET /api/manage/dashboard
```

**Response:**

```json
{
  "status": "success",
  "data": {
    "products": {
      "total": 68,
      "published": 60,
      "pending_review": 3,
      "draft": 5
    },
    "orders": {
      "total": 250,
      "completed": 200,
      "pending": 15,
      "processing": 20,
      "canceled": 15
    },
    "revenue": {
      "total": 125000000,
      "this_month": 15000000,
      "today": 1500000,
      "platform_earnings": 37500000,
      "seller_earnings": 87500000
    },
    "sellers": {
      "total": 12,
      "active": 10,
      "pending": 2,
      "suspended": 0
    },
    "customers": {
      "total": 500,
      "this_month": 45,
      "with_orders": 200
    },
    "withdrawals": {
      "pending_count": 3,
      "pending_amount": 5000000,
      "completed_this_month": 10000000
    },
    "jobs": {
      "total": 42,
      "active": 30
    },
    "subscriptions": {
      "active": 25,
      "mrr": 12500000
    }
  }
}
```

**Logic query:**

| Metric | Query |
|--------|-------|
| `products.total` | `Product::count()` |
| `products.published` | `product_flat` where `status=1` |
| `products.pending_review` | `Product::where('pending_review', true)->count()` |
| `products.draft` | `product_flat` where `status=0` AND `pending_review=false` |
| `orders.total` | `Order::count()` |
| `orders.{status}` | `Order::where('status', $s)->count()` |
| `revenue.total` | `SourceGameEarning::sum('order_amount')` |
| `revenue.this_month` | `SourceGameEarning::whereMonth('created_at', now()->month)->sum('order_amount')` |
| `revenue.today` | `SourceGameEarning::whereDate('created_at', today())->sum('order_amount')` |
| `sellers.*` | `SourceGameSeller::where('status', $s)->count()` |
| `customers.total` | `Customer::count()` |
| `customers.this_month` | `Customer::whereMonth('created_at', now()->month)->count()` |
| `withdrawals.pending_count` | `SourceGameWithdrawal::where('status', 'pending')->count()` |
| `withdrawals.pending_amount` | `SourceGameWithdrawal::where('status', 'pending')->sum('amount')` |

---

## Phase 2: Product Management

### T3. Tạo `ProductManageController` — list, detail, statistics

**File tạo mới:**
- `app/Http/Controllers/Api/ProductManageController.php`

**Endpoints:**

#### GET /api/manage/products — Danh sách sản phẩm

**Query params:**

| Param | Type | Default | Mô tả |
|-------|------|---------|-------|
| `search` | string | — | Tìm theo name, sku |
| `status` | int (0/1) | — | 0=draft, 1=published |
| `pending_review` | bool | — | Lọc sản phẩm chờ duyệt |
| `seller_id` | int | — | Lọc theo seller |
| `category_id` | int | — | Lọc theo category |
| `sort_by` | string | `created_at` | Cột sắp xếp: `created_at`, `price`, `name` |
| `sort_dir` | string | `desc` | `asc` / `desc` |
| `per_page` | int | 15 | 1–100 |

**Logic query:**
- Join `product_flat` (locale=vi) để lấy name, price, status, thumbnail
- Eager load: `seller:id,shop_name,shop_slug`, `categories:id,name`
- Search: `product_flat.name LIKE %search%` OR `products.sku LIKE %search%`
- Filter status qua `product_flat.status`
- Filter pending_review qua `products.pending_review`

**Response:** Danh sách `ProductManageResource` + meta pagination

#### GET /api/manage/products/{id} — Chi tiết sản phẩm

**Logic:**
- Find product by ID
- Eager load: `seller`, `categories`, `images`, `downloadable_links`, `product_flats`
- Load EAV attributes: `game_engine`, `programming_language`, `file_size`, `version`, `video_demo_url`, `demo_url`, `author_name`
- Tính stats: `order_items.count()` (purchase_count), `product_reviews.avg(rating)`

**Response:** `ProductManageResource` đầy đủ

#### GET /api/manage/products/statistics — Thống kê sản phẩm

**Response:**

```json
{
  "status": "success",
  "data": {
    "total": 68,
    "published": 60,
    "draft": 5,
    "pending_review": 3,
    "by_category": [
      {"category_id": 1, "name": "Source Game", "count": 45},
      {"category_id": 2, "name": "Game Template", "count": 23}
    ],
    "by_seller": [
      {"seller_id": 1, "shop_name": "GameDev Studio", "count": 12}
    ],
    "top_selling": [
      {"id": 10, "name": "Unity 2D Platformer", "purchase_count": 25}
    ]
  }
}
```

---

### T4. `ProductManageController` — store, update, destroy (CRUD)

#### POST /api/manage/products — Tạo sản phẩm mới

**Request body:**

```json
{
  "sku": "SG-NEW-001",
  "name": "Unity 2D Platformer",
  "description": "Full source code...",
  "short_description": "Game platformer 2D",
  "url_key": "unity-2d-platformer",
  "price": 499000,
  "special_price": null,
  "special_price_from": null,
  "special_price_to": null,
  "seller_id": 1,
  "category_ids": [1, 3],
  "status": 0,
  "attributes": {
    "game_engine": "Unity",
    "programming_language": "C#",
    "file_size": "150MB",
    "version": "1.0",
    "video_demo_url": "https://youtube.com/...",
    "demo_url": "https://demo.lamgame.vn/..."
  },
  "meta_title": "Unity 2D Platformer Source Code",
  "meta_description": "Mua source code game...",
  "meta_keywords": "unity, platformer, source code"
}
```

**Validation rules:**

| Field | Rule |
|-------|------|
| `sku` | required, string, unique:products,sku |
| `name` | required, string, max:255 |
| `description` | required, string |
| `short_description` | nullable, string, max:500 |
| `url_key` | nullable, string, unique:product_flat,url_key |
| `price` | required, numeric, min:0 |
| `special_price` | nullable, numeric, min:0 |
| `seller_id` | nullable, integer, exists:source_game_sellers,id |
| `category_ids` | nullable, array |
| `category_ids.*` | integer, exists:categories,id |
| `status` | nullable, in:0,1 |
| `attributes` | nullable, array |
| `attributes.game_engine` | nullable, string |
| `attributes.programming_language` | nullable, string |

**Logic:**
1. Tạo `Product` với `type=downloadable`, `attribute_family_id=1`
2. Tạo `product_flat` record (locale=vi, channel=default)
3. Sync categories qua `product_categories` pivot
4. Lưu EAV attributes qua `product_attribute_values`
5. Set `pending_review=true` nếu `seller_id` có giá trị (seller submit)
6. Return `ProductManageResource`

#### PUT /api/manage/products/{id} — Cập nhật sản phẩm

**Validation:** Giống store nhưng tất cả `sometimes` thay vì `required`. SKU unique ignore current ID.

**Logic:**
1. Find product by ID, 404 nếu không tìm thấy
2. Update `products` table
3. Update `product_flat` record
4. Sync categories nếu `category_ids` có trong request
5. Update EAV attributes nếu `attributes` có trong request
6. Return `ProductManageResource`

#### DELETE /api/manage/products/{id} — Xóa sản phẩm

**Logic:**
1. Find product by ID, 404 nếu không tìm thấy
2. Kiểm tra: không cho xóa nếu có `order_items` liên quan (đã bán)
3. Xóa: `product_flat`, `product_attribute_values`, `product_images`, `product_categories`, `product_downloadable_links`, rồi xóa `products`
4. Return success message

**Response lỗi nếu đã bán:**

```json
{
  "status": "error",
  "message": "Không thể xóa sản phẩm đã có đơn hàng. Hãy chuyển sang draft thay vì xóa."
}
```

---

### T5. `ProductManageController` — changeStatus, review (approve/reject)

#### POST /api/manage/products/{id}/status — Đổi trạng thái

**Request body:**

```json
{
  "status": 1
}
```

| Field | Rule |
|-------|------|
| `status` | required, in:0,1 |

**Logic:**
1. Find product, 404 nếu không tìm thấy
2. Update `product_flat.status` = request status
3. Nếu status=1 (publish): kiểm tra `pending_review` phải = false
4. Return success

#### POST /api/manage/products/{id}/review — Duyệt/từ chối sản phẩm

**Request body:**

```json
{
  "action": "approve",
  "rejection_reason": null
}
```

| Field | Rule |
|-------|------|
| `action` | required, in:approve,reject |
| `rejection_reason` | required_if:action,reject, string, max:1000 |

**Logic approve:**
1. Set `pending_review=false`, `status=1` (published), `rejection_reason=null`
2. Update `product_flat.status=1`
3. Gửi mail `ProductApproved` cho seller (nếu có seller_id)

**Logic reject:**
1. Set `pending_review=false`, `status=0` (draft), `rejection_reason=reason`
2. Update `product_flat.status=0`
3. Gửi mail `ProductRejected` cho seller (nếu có seller_id)

---

### T6. Tạo `ProductManageResource` — response format chuẩn

**File tạo mới:**
- `app/Http/Resources/ProductManageResource.php`

**Cấu trúc resource:**

```json
{
  "id": 1,
  "sku": "SG-001",
  "type": "downloadable",
  "name": "Unity 2D Platformer",
  "description": "...",
  "short_description": "...",
  "url_key": "unity-2d-platformer",
  "price": 499000,
  "special_price": null,
  "special_price_from": null,
  "special_price_to": null,
  "status": 1,
  "pending_review": false,
  "rejection_reason": null,
  "thumbnail": "/storage/product/1/thumb.jpg",
  "images": [
    {"id": 1, "path": "/storage/product/1/img1.jpg", "position": 1}
  ],
  "categories": [
    {"id": 1, "name": "Source Game"}
  ],
  "downloadable_links": [
    {"id": 1, "title": "Source Code", "price": 0, "type": "file"}
  ],
  "attributes": {
    "game_engine": "Unity",
    "programming_language": "C#",
    "file_size": "150MB",
    "version": "1.0",
    "video_demo_url": "https://youtube.com/...",
    "demo_url": "https://demo.lamgame.vn/...",
    "author_name": "GameDev Studio"
  },
  "seller": {
    "id": 1,
    "shop_name": "GameDev Studio",
    "shop_slug": "gamedev-studio"
  },
  "stats": {
    "purchase_count": 25,
    "rating_avg": 4.5,
    "rating_count": 8
  },
  "meta": {
    "meta_title": "...",
    "meta_description": "...",
    "meta_keywords": "..."
  },
  "created_at": "2026-04-20T10:00:00+07:00",
  "updated_at": "2026-04-25T14:30:00+07:00"
}
```

**Logic lấy data:**
- `name`, `description`, `price`, `status`, `thumbnail`, `url_key`, `meta_*` → từ `product_flat` (locale=vi)
- `sku`, `type`, `pending_review`, `rejection_reason`, `seller_id` → từ `products`
- `images` → từ `product_images`
- `categories` → từ `product_categories` join `category_translations`
- `downloadable_links` → từ `product_downloadable_links`
- `attributes` → từ `product_attribute_values` (EAV query)
- `seller` → từ `source_game_sellers`
- `stats.purchase_count` → `order_items` where `product_id` count
- `stats.rating_avg/count` → `product_reviews` aggregate

---

## Phase 3: Order Management

### T7. Tạo `OrderManageController` — list, detail, statistics

**File tạo mới:**
- `app/Http/Controllers/Api/OrderManageController.php`

**Endpoints:**

#### GET /api/manage/orders — Danh sách đơn hàng

**Query params:**

| Param | Type | Default | Mô tả |
|-------|------|---------|-------|
| `search` | string | — | Tìm theo increment_id, customer_email, customer_first_name |
| `status` | string | — | pending, processing, completed, canceled, closed, fraud |
| `payment_method` | string | — | paypal_smart_button, lemonsqueezy |
| `date_from` | date | — | Từ ngày (Y-m-d) |
| `date_to` | date | — | Đến ngày (Y-m-d) |
| `customer_id` | int | — | Lọc theo customer |
| `sort_by` | string | `created_at` | `created_at`, `grand_total`, `increment_id` |
| `sort_dir` | string | `desc` | `asc` / `desc` |
| `per_page` | int | 15 | 1–100 |

**Logic query:**
- Query `orders` table
- Eager load: `items.product`, `payment`, `addresses`, `comments`
- Search: `increment_id LIKE` OR `customer_email LIKE` OR `customer_first_name LIKE`
- Filter date range: `created_at BETWEEN date_from AND date_to`

#### GET /api/manage/orders/{id} — Chi tiết đơn hàng

**Logic:**
- Find order by ID
- Eager load: `items.product`, `payment`, `addresses`, `comments`, `invoices`, `transactions`
- Load `downloadable_link_purchased` cho mỗi item
- Load earning info từ `source_game_earnings` where `order_id`

#### GET /api/manage/orders/statistics — Thống kê đơn hàng

**Response:**

```json
{
  "status": "success",
  "data": {
    "total": 250,
    "by_status": {
      "pending": 15,
      "processing": 20,
      "completed": 200,
      "canceled": 10,
      "closed": 5,
      "fraud": 0
    },
    "revenue": {
      "total": 125000000,
      "this_month": 15000000,
      "last_month": 12000000,
      "today": 1500000
    },
    "avg_order_value": 500000,
    "by_payment_method": [
      {"method": "paypal_smart_button", "count": 180, "total": 90000000},
      {"method": "lemonsqueezy", "count": 70, "total": 35000000}
    ],
    "recent_7_days": [
      {"date": "2026-04-28", "orders": 5, "revenue": 2500000},
      {"date": "2026-04-27", "orders": 3, "revenue": 1500000}
    ]
  }
}
```

---

### T8. `OrderManageController` — changeStatus, comment

#### POST /api/manage/orders/{id}/status — Đổi trạng thái đơn hàng

**Request body:**

```json
{
  "status": "completed"
}
```

| Field | Rule |
|-------|------|
| `status` | required, in:pending,processing,completed,canceled,closed |

**Logic:**
1. Find order, 404 nếu không tìm thấy
2. Validate transition hợp lệ:
   - `pending` → `processing`, `canceled`
   - `processing` → `completed`, `canceled`
   - `completed` → `closed`
   - Không cho chuyển ngược
3. Update `orders.status`
4. Nếu `completed`: tạo `SourceGameEarning` cho mỗi item (nếu chưa có)
5. Nếu `canceled`: refund earnings nếu đã tạo
6. Return success

**Transition matrix:**

| From → To | pending | processing | completed | canceled | closed |
|-----------|:-------:|:----------:|:---------:|:--------:|:------:|
| pending | — | ✅ | ❌ | ✅ | ❌ |
| processing | ❌ | — | ✅ | ✅ | ❌ |
| completed | ❌ | ❌ | — | ❌ | ✅ |
| canceled | ❌ | ❌ | ❌ | — | ❌ |
| closed | ❌ | ❌ | ❌ | ❌ | — |

#### POST /api/manage/orders/{id}/comment — Thêm ghi chú

**Request body:**

```json
{
  "comment": "Đã liên hệ khách hàng xác nhận",
  "customer_notified": false
}
```

| Field | Rule |
|-------|------|
| `comment` | required, string, max:1000 |
| `customer_notified` | nullable, boolean |

**Logic:**
1. Find order, 404 nếu không tìm thấy
2. Tạo `order_comments` record
3. Nếu `customer_notified=true`: gửi email thông báo cho customer
4. Return success + comment data

---

### T9. Tạo `OrderManageResource` — response format

**File tạo mới:**
- `app/Http/Resources/OrderManageResource.php`

**Cấu trúc resource:**

```json
{
  "id": 1,
  "increment_id": "000000001",
  "status": "completed",
  "is_guest": false,
  "customer": {
    "id": 5,
    "name": "Nguyễn Văn A",
    "email": "a@email.com"
  },
  "items": [
    {
      "id": 1,
      "product_id": 10,
      "name": "Unity 2D Platformer",
      "sku": "SG-001",
      "type": "downloadable",
      "qty_ordered": 1,
      "price": 499000,
      "total": 499000,
      "download_status": "available"
    }
  ],
  "payment": {
    "method": "paypal_smart_button",
    "method_title": "PayPal",
    "transaction_id": "PAY-xxx"
  },
  "billing_address": {
    "name": "Nguyễn Văn A",
    "email": "a@email.com",
    "phone": "0901234567",
    "city": "Hồ Chí Minh",
    "country": "VN"
  },
  "sub_total": 499000,
  "discount_amount": 0,
  "tax_amount": 0,
  "grand_total": 499000,
  "currency": "VND",
  "coupon_code": null,
  "comments": [
    {
      "id": 1,
      "comment": "Đã xác nhận thanh toán",
      "customer_notified": true,
      "created_at": "2026-04-25T15:00:00+07:00"
    }
  ],
  "invoices": [
    {"id": 1, "state": "paid", "grand_total": 499000}
  ],
  "earnings": [
    {
      "seller": "GameDev Studio",
      "order_amount": 499000,
      "platform_fee": 149700,
      "seller_amount": 349300,
      "status": "completed"
    }
  ],
  "created_at": "2026-04-25T14:30:00+07:00",
  "updated_at": "2026-04-25T15:00:00+07:00"
}
```

---

## Phase 4: Seller Management

### T10. Tạo `SellerManageController` — list, detail, statistics

**File tạo mới:**
- `app/Http/Controllers/Api/SellerManageController.php`

**Endpoints:**

#### GET /api/manage/sellers — Danh sách sellers

**Query params:**

| Param | Type | Default | Mô tả |
|-------|------|---------|-------|
| `search` | string | — | Tìm theo shop_name, contact_email |
| `status` | string | — | pending, active, suspended, rejected, banned |
| `business_type` | string | — | individual, company |
| `verified` | bool | — | Lọc đã xác minh |
| `sort_by` | string | `created_at` | `created_at`, `total_revenue`, `total_products`, `rating_avg` |
| `sort_dir` | string | `desc` | `asc` / `desc` |
| `per_page` | int | 15 | 1–100 |

**Logic query:**
- Query `source_game_sellers`
- Eager load: `customer:id,first_name,last_name,email`
- Search: `shop_name LIKE` OR `contact_email LIKE`
- Tính `available_balance` = `SUM(earnings.seller_amount WHERE status=completed)` - `SUM(withdrawals.amount WHERE status IN (completed, processing))`

#### GET /api/manage/sellers/{id} — Chi tiết seller

**Logic:**
- Find seller by ID
- Eager load: `customer`, `products` (count + recent 5), `earnings` (aggregate)
- Tính stats chi tiết:
  - `total_products`: products count
  - `total_sales`: earnings count where status=completed
  - `total_revenue`: SUM earnings.seller_amount
  - `total_withdrawn`: SUM withdrawals.amount where status=completed
  - `available_balance`: total_revenue - total_withdrawn - pending_withdrawals
  - `pending_withdrawals`: SUM withdrawals.amount where status IN (pending, processing)

#### GET /api/manage/sellers/statistics — Thống kê sellers

**Response:**

```json
{
  "status": "success",
  "data": {
    "total": 12,
    "by_status": {
      "active": 10,
      "pending": 2,
      "suspended": 0,
      "rejected": 0,
      "banned": 0
    },
    "by_business_type": {
      "individual": 8,
      "company": 4
    },
    "verified_count": 10,
    "top_sellers": [
      {
        "id": 1,
        "shop_name": "GameDev Studio",
        "total_products": 12,
        "total_revenue": 42500000,
        "rating_avg": 4.5
      }
    ],
    "new_this_month": 2
  }
}
```

---

### T11. `SellerManageController` — approve, reject, suspend, activate

#### POST /api/manage/sellers/{id}/approve — Duyệt seller

**Logic:**
1. Find seller, 404 nếu không tìm thấy
2. Validate: status phải là `pending`
3. Update: `status=active`, `verified=true`, `verified_at=now()`
4. Gửi email thông báo cho seller (qua customer.email)
5. Return success

#### POST /api/manage/sellers/{id}/reject — Từ chối seller

**Request body:**

```json
{
  "reason": "Thông tin không đầy đủ, vui lòng bổ sung CMND/CCCD"
}
```

| Field | Rule |
|-------|------|
| `reason` | required, string, max:1000 |

**Logic:**
1. Find seller, validate status = `pending`
2. Update: `status=rejected`
3. Gửi email thông báo kèm lý do
4. Return success

#### POST /api/manage/sellers/{id}/suspend — Tạm khóa seller

**Request body:**

```json
{
  "reason": "Vi phạm chính sách bán hàng"
}
```

| Field | Rule |
|-------|------|
| `reason` | required, string, max:1000 |

**Logic:**
1. Find seller, validate status = `active`
2. Update: `status=suspended`
3. Ẩn tất cả products của seller: `product_flat.status=0`
4. Gửi email thông báo
5. Return success

#### POST /api/manage/sellers/{id}/activate — Kích hoạt lại seller

**Logic:**
1. Find seller, validate status IN (`suspended`, `rejected`)
2. Update: `status=active`
3. Khôi phục products: `product_flat.status=1` cho các product đã published trước khi suspend
4. Gửi email thông báo
5. Return success

---

## Phase 5: Earnings & Withdrawals

### T12. Tạo `EarningManageController` — list, statistics

**File tạo mới:**
- `app/Http/Controllers/Api/EarningManageController.php`

**Endpoints:**

#### GET /api/manage/earnings — Danh sách earnings

**Query params:**

| Param | Type | Default | Mô tả |
|-------|------|---------|-------|
| `seller_id` | int | — | Lọc theo seller |
| `status` | string | — | pending, completed, refunded |
| `date_from` | date | — | Từ ngày |
| `date_to` | date | — | Đến ngày |
| `sort_by` | string | `created_at` | `created_at`, `order_amount`, `seller_amount` |
| `sort_dir` | string | `desc` | `asc` / `desc` |
| `per_page` | int | 15 | 1–100 |

**Logic query:**
- Query `source_game_earnings`
- Eager load: `seller:id,shop_name`, `order:id,increment_id`, `product:id,sku` + product_flat name
- Filter theo params

**Response item:**

```json
{
  "id": 1,
  "seller": {"id": 1, "shop_name": "GameDev Studio"},
  "order_id": 15,
  "order_increment_id": "000000015",
  "product": {"id": 10, "name": "Unity 2D Platformer", "sku": "SG-001"},
  "order_amount": 499000,
  "platform_fee_percent": 30.00,
  "platform_fee_amount": 149700,
  "seller_amount": 349300,
  "status": "completed",
  "completed_at": "2026-04-26T10:00:00+07:00",
  "created_at": "2026-04-25T14:30:00+07:00"
}
```

#### GET /api/manage/earnings/statistics — Thống kê doanh thu

**Response:**

```json
{
  "status": "success",
  "data": {
    "total_revenue": 125000000,
    "platform_earnings": 37500000,
    "seller_earnings": 87500000,
    "by_status": {
      "pending": 5000000,
      "completed": 120000000,
      "refunded": 0
    },
    "this_month": {
      "revenue": 15000000,
      "platform_earnings": 4500000,
      "seller_earnings": 10500000,
      "orders": 30
    },
    "last_month": {
      "revenue": 12000000,
      "orders": 24
    },
    "top_products": [
      {"id": 10, "name": "Unity 2D Platformer", "total_revenue": 12475000, "sales_count": 25}
    ],
    "top_sellers": [
      {"id": 1, "shop_name": "GameDev Studio", "total_earnings": 29750000, "sales_count": 85}
    ],
    "monthly_trend": [
      {"month": "2026-04", "revenue": 15000000, "orders": 30},
      {"month": "2026-03", "revenue": 12000000, "orders": 24}
    ]
  }
}
```

---

### T13. Tạo `WithdrawalManageController` — list, detail, approve, complete, reject

**File tạo mới:**
- `app/Http/Controllers/Api/WithdrawalManageController.php`

**Endpoints:**

#### GET /api/manage/withdrawals — Danh sách yêu cầu rút tiền

**Query params:**

| Param | Type | Default | Mô tả |
|-------|------|---------|-------|
| `seller_id` | int | — | Lọc theo seller |
| `status` | string | — | pending, processing, completed, rejected |
| `date_from` | date | — | Từ ngày |
| `date_to` | date | — | Đến ngày |
| `sort_by` | string | `created_at` | `created_at`, `amount` |
| `sort_dir` | string | `desc` | `asc` / `desc` |
| `per_page` | int | 15 | 1–100 |

**Logic query:**
- Query `source_game_withdrawals`
- Eager load: `seller:id,shop_name,contact_email`

#### GET /api/manage/withdrawals/{id} — Chi tiết withdrawal

**Response:**

```json
{
  "id": 1,
  "seller": {
    "id": 1,
    "shop_name": "GameDev Studio",
    "contact_email": "seller@email.com",
    "available_balance": 9750000
  },
  "amount": 5000000,
  "status": "pending",
  "bank_info": {
    "bank_name": "Vietcombank",
    "bank_account": "1234567890",
    "bank_holder": "NGUYEN VAN A"
  },
  "note": "Rút tiền tháng 4",
  "admin_note": null,
  "transaction_id": null,
  "processed_at": null,
  "created_at": "2026-04-28T10:00:00+07:00"
}
```

#### POST /api/manage/withdrawals/{id}/approve — Duyệt (pending → processing)

**Logic:**
1. Find withdrawal, 404 nếu không tìm thấy
2. Validate: status phải là `pending`
3. Validate: seller `available_balance` >= withdrawal amount
4. Update: `status=processing`
5. Gửi email thông báo cho seller
6. Return success

#### POST /api/manage/withdrawals/{id}/complete — Hoàn thành (processing → completed)

**Request body:**

```json
{
  "transaction_id": "VCB-20260428-001",
  "admin_note": "Đã chuyển khoản Vietcombank"
}
```

| Field | Rule |
|-------|------|
| `transaction_id` | required, string, max:255 |
| `admin_note` | nullable, string, max:1000 |

**Logic:**
1. Find withdrawal, validate status = `processing`
2. Update: `status=completed`, `transaction_id`, `admin_note`, `processed_at=now()`, `processed_by=admin.id`
3. Gửi email thông báo cho seller kèm transaction_id
4. Return success

#### POST /api/manage/withdrawals/{id}/reject — Từ chối

**Request body:**

```json
{
  "admin_note": "Thông tin ngân hàng không chính xác"
}
```

| Field | Rule |
|-------|------|
| `admin_note` | required, string, max:1000 |

**Logic:**
1. Find withdrawal, validate status IN (`pending`, `processing`)
2. Update: `status=rejected`, `admin_note`, `processed_at=now()`, `processed_by=admin.id`
3. Hoàn lại balance cho seller (không cần thao tác DB vì balance tính dynamic)
4. Gửi email thông báo cho seller kèm lý do
5. Return success

---

## Phase 6: Customer Management

### T14. Tạo `CustomerManageController` — list, detail, statistics, suspend, activate

**File tạo mới:**
- `app/Http/Controllers/Api/CustomerManageController.php`

**Endpoints:**

#### GET /api/manage/customers — Danh sách khách hàng

**Query params:**

| Param | Type | Default | Mô tả |
|-------|------|---------|-------|
| `search` | string | — | Tìm theo first_name, last_name, email |
| `is_verified` | bool | — | Lọc đã xác minh email |
| `is_suspended` | bool | — | Lọc đã bị khóa |
| `has_orders` | bool | — | Lọc có đơn hàng |
| `has_seller` | bool | — | Lọc là seller |
| `sort_by` | string | `created_at` | `created_at`, `first_name`, `email` |
| `sort_dir` | string | `desc` | `asc` / `desc` |
| `per_page` | int | 15 | 1–100 |

**Logic query:**
- Query `customers` (Webkul\Customer\Models\Customer)
- Eager load: `orders` (count), `seller` (nếu có)
- Search: `first_name LIKE` OR `last_name LIKE` OR `email LIKE`
- `has_orders`: whereHas('orders')
- `has_seller`: whereHas('seller') — cần check relationship

#### GET /api/manage/customers/{id} — Chi tiết khách hàng

**Response:**

```json
{
  "id": 5,
  "first_name": "Nguyễn",
  "last_name": "Văn A",
  "email": "a@email.com",
  "phone": "0901234567",
  "gender": "male",
  "is_verified": true,
  "is_suspended": false,
  "seller": {
    "id": 1,
    "shop_name": "GameDev Studio",
    "status": "active"
  },
  "orders_summary": {
    "total_orders": 15,
    "total_spent": 7485000,
    "last_order_at": "2026-04-25T14:30:00+07:00"
  },
  "subscription": {
    "plan": "Pro",
    "status": "active",
    "expires_at": "2026-05-15T00:00:00+07:00"
  },
  "recent_orders": [
    {
      "id": 250,
      "increment_id": "000000250",
      "status": "completed",
      "grand_total": 499000,
      "created_at": "2026-04-25T14:30:00+07:00"
    }
  ],
  "created_at": "2026-01-15T10:00:00+07:00"
}
```

#### GET /api/manage/customers/statistics — Thống kê khách hàng

**Response:**

```json
{
  "status": "success",
  "data": {
    "total": 500,
    "verified": 450,
    "suspended": 5,
    "with_orders": 200,
    "sellers": 12,
    "new_this_month": 45,
    "new_last_month": 38,
    "top_spenders": [
      {"id": 5, "name": "Nguyễn Văn A", "total_spent": 7485000, "order_count": 15}
    ]
  }
}
```

#### POST /api/manage/customers/{id}/suspend — Tạm khóa khách hàng

**Request body:**

```json
{
  "reason": "Vi phạm điều khoản sử dụng"
}
```

| Field | Rule |
|-------|------|
| `reason` | required, string, max:1000 |

**Logic:**
1. Find customer, 404 nếu không tìm thấy
2. Validate: `is_suspended` phải = false
3. Update: `is_suspended=true`, lưu reason vào `notes`
4. Nếu customer là seller: suspend seller luôn (`source_game_sellers.status=suspended`)
5. Return success

#### POST /api/manage/customers/{id}/activate — Kích hoạt lại

**Logic:**
1. Find customer, validate `is_suspended=true`
2. Update: `is_suspended=false`
3. Nếu customer là seller VÀ seller.status=suspended: activate seller (`status=active`)
4. Return success

---

## Phase 7: Testing & Docs

### T15. Tạo Postman collection cho toàn bộ API

**File tạo mới:**
- `docs/ecommerce/ECOMMERCE_API_POSTMAN.json`

**Nội dung collection:**
- Environment variables: `base_url`, `api_key`
- Folders theo module: Dashboard, Products, Orders, Sellers, Earnings, Withdrawals, Customers
- Mỗi endpoint có: request mẫu, test script kiểm tra status code, response schema

**Danh sách requests:**

| # | Method | Endpoint | Folder |
|---|--------|----------|--------|
| 1 | GET | /api/manage/dashboard | Dashboard |
| 2 | GET | /api/manage/products | Products |
| 3 | GET | /api/manage/products/statistics | Products |
| 4 | GET | /api/manage/products/{id} | Products |
| 5 | POST | /api/manage/products | Products |
| 6 | PUT | /api/manage/products/{id} | Products |
| 7 | DELETE | /api/manage/products/{id} | Products |
| 8 | POST | /api/manage/products/{id}/status | Products |
| 9 | POST | /api/manage/products/{id}/review | Products |
| 10 | GET | /api/manage/orders | Orders |
| 11 | GET | /api/manage/orders/statistics | Orders |
| 12 | GET | /api/manage/orders/{id} | Orders |
| 13 | POST | /api/manage/orders/{id}/status | Orders |
| 14 | POST | /api/manage/orders/{id}/comment | Orders |
| 15 | GET | /api/manage/sellers | Sellers |
| 16 | GET | /api/manage/sellers/statistics | Sellers |
| 17 | GET | /api/manage/sellers/{id} | Sellers |
| 18 | POST | /api/manage/sellers/{id}/approve | Sellers |
| 19 | POST | /api/manage/sellers/{id}/reject | Sellers |
| 20 | POST | /api/manage/sellers/{id}/suspend | Sellers |
| 21 | POST | /api/manage/sellers/{id}/activate | Sellers |
| 22 | GET | /api/manage/earnings | Earnings |
| 23 | GET | /api/manage/earnings/statistics | Earnings |
| 24 | GET | /api/manage/withdrawals | Withdrawals |
| 25 | GET | /api/manage/withdrawals/{id} | Withdrawals |
| 26 | POST | /api/manage/withdrawals/{id}/approve | Withdrawals |
| 27 | POST | /api/manage/withdrawals/{id}/complete | Withdrawals |
| 28 | POST | /api/manage/withdrawals/{id}/reject | Withdrawals |
| 29 | GET | /api/manage/customers | Customers |
| 30 | GET | /api/manage/customers/statistics | Customers |
| 31 | GET | /api/manage/customers/{id} | Customers |
| 32 | POST | /api/manage/customers/{id}/suspend | Customers |
| 33 | POST | /api/manage/customers/{id}/activate | Customers |

---

### T16. Cập nhật tài liệu cuối cùng

**Nội dung:**
- Cập nhật `ECOMMERCE_API_GUIDE.md` với trạng thái ✅ cho tất cả modules
- Cập nhật `PROJECT_STATUS.md` với tiến độ E-Commerce API
- Review lại toàn bộ response format, đảm bảo consistency
- Ghi chú các edge cases đã xử lý
- Cập nhật task list trong file này với trạng thái ✅ DONE

---

## Tổng hợp Files cần tạo/sửa

### Files tạo mới:

| # | File | Task |
|---|------|------|
| 1 | `routes/api-ecommerce-manage.php` | T1 |
| 2 | `app/Http/Controllers/Api/DashboardManageController.php` | T2 |
| 3 | `app/Http/Controllers/Api/ProductManageController.php` | T3, T4, T5 |
| 4 | `app/Http/Resources/ProductManageResource.php` | T6 |
| 5 | `app/Http/Controllers/Api/OrderManageController.php` | T7, T8 |
| 6 | `app/Http/Resources/OrderManageResource.php` | T9 |
| 7 | `app/Http/Controllers/Api/SellerManageController.php` | T10, T11 |
| 8 | `app/Http/Controllers/Api/EarningManageController.php` | T12 |
| 9 | `app/Http/Controllers/Api/WithdrawalManageController.php` | T13 |
| 10 | `app/Http/Controllers/Api/CustomerManageController.php` | T14 |
| 11 | `docs/ecommerce/ECOMMERCE_API_POSTMAN.json` | T15 |

### Files sửa:

| # | File | Task | Thay đổi |
|---|------|------|----------|
| 1 | `bootstrap/app.php` | T1 | Thêm route register |
| 2 | `docs/ecommerce/ECOMMERCE_API_GUIDE.md` | T16 | Cập nhật trạng thái |
| 3 | `PROJECT_STATUS.md` | T16 | Cập nhật tiến độ |

---

## Ghi chú kỹ thuật

### Bagisto EAV Product — Cách query

Product trong Bagisto dùng EAV pattern. Để lấy thông tin product cần:

1. **product_flat** (denormalized): Dùng cho list/search — đã có name, price, status, thumbnail
2. **product_attribute_values**: Dùng cho custom attributes (game_engine, programming_language...)
3. **products**: Dùng cho core fields (sku, type, seller_id, pending_review)

```php
// Pattern query product list
$query = DB::table('products')
    ->join('product_flat', 'products.id', '=', 'product_flat.product_id')
    ->where('product_flat.locale', 'vi')
    ->where('product_flat.channel', 'default');
```

### Available Balance tính toán

```php
// Seller available balance = total earnings - total withdrawn - pending withdrawals
$totalEarnings = SourceGameEarning::where('seller_id', $id)
    ->where('status', 'completed')
    ->sum('seller_amount');

$totalWithdrawn = SourceGameWithdrawal::where('seller_id', $id)
    ->whereIn('status', ['completed', 'processing'])
    ->sum('amount');

$availableBalance = $totalEarnings - $totalWithdrawn;
```

### Naming convention

Theo pattern hiện tại của dự án:
- Controller: `{Module}ManageController` (trong `App\Http\Controllers\Api`)
- Resource: `{Module}ManageResource` (trong `App\Http\Resources`)
- Route name prefix: `api.manage.{module}.`
- Response format: `{status, message, data, meta}`
