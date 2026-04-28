# E-Commerce Management API — Hướng dẫn tích hợp Ohha Studio

> Cập nhật: 28/04/2026
> Base URL: `https://lamgame.vn/api`
> Dự án: LAMGAME.VN — Bagisto-based Game Source Code Marketplace
> Route file: `routes/api-ecommerce-manage.php` — 33 endpoints
> Postman: `docs/ecommerce/ECOMMERCE_API_POSTMAN.json`
> Task tracking: `docs/ecommerce/ECOMMERCE_API_TASKS.md`

---

## Tổng quan hệ thống

LAMGAME.VN là marketplace bán source code game, xây trên nền Bagisto (Webkul). Hệ thống gồm:

| Module | Mô tả | Trạng thái API |
|--------|--------|:--------------:|
| **Products** (Source Game) | Sản phẩm downloadable, EAV model | ✅ Đã xây API |
| **Orders** | Đơn hàng digital, auto-deliver | ✅ Đã xây API |
| **Sellers** | Multi-seller marketplace | ✅ Đã xây API |
| **Earnings** | Revenue split 70/30 | ✅ Đã xây API |
| **Withdrawals** | Seller rút tiền | ✅ Đã xây API |
| **Customers** | Người mua | ✅ Đã xây API |
| **Jobs** | Việc làm game | ✅ Đã có (`api-job-manage.php`) |
| **Blog** | Bài viết | ✅ Đã có (`/api/blog/`) |

---

## DATABASE SCHEMA

### 1. Products (Bagisto EAV)

```
products
├── id, sku (unique), type ('downloadable')
├── parent_id → products.id
├── attribute_family_id → attribute_families.id
├── additional (JSON)
├── seller_id → source_game_sellers.id (nullable)
├── pending_review (boolean, default false)
├── rejection_reason (text, nullable)
├── company_id, created_by_admin_id
└── timestamps

product_flat (denormalized, 1 per locale/channel)
├── product_id → products.id
├── locale, channel
├── name, description, short_description
├── url_key (slug), sku
├── price, special_price, special_price_from/to
├── status (0=draft, 1=published)
├── thumbnail, meta_title, meta_description, meta_keywords
└── new, featured, visible_individually

product_attribute_values (EAV)
├── product_id, attribute_id
├── text_value, boolean_value, integer_value
├── float_value, datetime_value, json_value
└── locale, channel

product_images
├── product_id, type, path, position

product_downloadable_links
├── product_id, url, file, file_name
├── type ('file'|'url'), price, sort_order, downloads

product_categories
├── product_id, category_id (M:N pivot)
```

**Custom attributes (EAV):** `game_engine`, `programming_language`, `file_size`, `version`, `video_demo_url`, `demo_url`, `author_name`

---

### 2. Orders (Bagisto Sales)

```
orders
├── id, increment_id (unique, display ID)
├── status: pending | pending_payment | processing | completed | canceled | closed | fraud
├── channel_name, channel_id, channel_type
├── is_guest (boolean)
├── customer_id → customers, customer_email, customer_first_name, customer_last_name
├── total_item_count, total_qty_ordered
├── base_currency_code, order_currency_code
├── grand_total, base_grand_total (decimal 12,4)
├── sub_total, discount_amount, tax_amount, shipping_amount
├── grand_total_invoiced, grand_total_refunded
├── coupon_code, applied_cart_rule_ids
├── cart_id
└── timestamps

order_items
├── order_id → orders.id
├── product_id → products.id, product_type
├── sku, type, name
├── qty_ordered, qty_shipped, qty_invoiced, qty_canceled, qty_refunded
├── price, base_price, total, base_total
├── tax_percent, tax_amount
├── discount_percent, discount_amount
├── additional (JSON)
└── parent_id (for configurable children)

order_payment
├── order_id → orders.id
├── method (e.g. 'paypal_smart_button', 'lemonsqueezy')
├── method_title
├── additional (JSON — PayPal transaction details)

order_addresses
├── order_id, address_type ('billing'|'shipping')
├── first_name, last_name, email, phone
├── address, city, state, country, postcode

order_comments
├── order_id, comment, customer_notified

order_transactions
├── order_id, transaction_id, payment_method
├── status, amount

invoices
├── order_id, state ('pending'|'paid')
├── total_qty, sub_total, grand_total
├── transaction_id

downloadable_link_purchased
├── order_item_id, customer_id
├── product_id, name, url, file, type
├── download_bought, download_used, status
```

**Lưu ý:** Đây là marketplace digital — `canShip()` luôn trả `false`, không có shipment thật.

---

### 3. Sellers

```
source_game_sellers
├── id, customer_id → customers (unique)
├── shop_name, shop_slug (unique), shop_description
├── shop_logo, shop_banner
├── contact_email, contact_phone, website
├── business_type: 'individual' | 'company'
├── tax_id, bank_name, bank_account, bank_holder
├── status: 'pending' | 'active' | 'rejected' | 'suspended' | 'banned'
├── verified (boolean), verified_at
├── total_products, total_sales, total_revenue (decimal 12,2)
├── rating_avg (decimal 3,2), rating_count
└── timestamps

source_game_earnings
├── id, seller_id → source_game_sellers
├── order_id → orders, order_item_id, product_id → products
├── order_amount (decimal 12,2)
├── platform_fee_percent (decimal 5,2, default 30.00)
├── platform_fee_amount, seller_amount (decimal 12,2)
├── status: 'pending' | 'completed' | 'refunded'
├── completed_at
└── timestamps

source_game_withdrawals
├── id, seller_id → source_game_sellers
├── amount (decimal 12,2)
├── status: 'pending' | 'processing' | 'completed' | 'rejected'
├── bank_name, bank_account, bank_holder
├── note (seller), admin_note
├── transaction_id, processed_at, processed_by
└── timestamps

source_game_versions
├── product_id → products
├── version, changelog, file_path, file_size
├── status: 'pending' | 'approved' | 'rejected'
└── timestamps
```

---

### 4. Customers (Bagisto)

```
customers
├── id, first_name, last_name, email (unique)
├── gender, date_of_birth, phone
├── password, api_token, token
├── customer_group_id, channel_id
├── is_verified, is_suspended
├── notes (text)
└── timestamps
```

---

### 5. Subscriptions

```
subscription_plans
├── id, slug, name, description
├── price (decimal 10,2), currency
├── interval: 'monthly' | 'yearly'
├── features (JSON), is_active
└── timestamps

subscriptions
├── id, user_id, user_type (morph)
├── plan_id → subscription_plans
├── paypal_subscription_id
├── status: 'active' | 'canceled' | 'expired' | 'suspended'
├── current_period_start, current_period_end
├── canceled_at
└── timestamps

subscription_usages
├── subscription_id, feature
├── used (int), limit (int)
├── period_start, period_end
```

---

## ENTITY RELATIONSHIPS

```
customers ──1:1──▶ source_game_sellers ──1:N──▶ products (seller_id)
                                        ──1:N──▶ source_game_earnings
                                        ──1:N──▶ source_game_withdrawals

products ──N:M──▶ categories (product_categories)
         ──1:N──▶ product_flat (denormalized per locale)
         ──1:N──▶ product_attribute_values (EAV)
         ──1:N──▶ product_images
         ──1:N──▶ product_downloadable_links
         ──1:N──▶ source_game_versions

orders ──1:N──▶ order_items ──M:1──▶ products
       ──1:1──▶ order_payment
       ──1:N──▶ order_addresses
       ──1:N──▶ order_comments
       ──1:N──▶ order_transactions
       ──1:N──▶ invoices ──1:N──▶ invoice_items
       ──1:N──▶ downloadable_link_purchased
       ──M:1──▶ customers

source_game_earnings ──M:1──▶ orders
                     ──M:1──▶ products
                     ──M:1──▶ source_game_sellers
```

---

## BUSINESS FLOWS

### Flow 1: Seller Onboarding
```
Customer đăng ký seller → status=pending
  → Admin duyệt → status=active, verified=true
  → Admin từ chối → status=rejected (có lý do)
  → Admin suspend → status=suspended
```

### Flow 2: Product Publishing
```
Seller tạo product → type=downloadable, status=0 (draft)
  → Upload images + downloadable files
  → pending_review=true (chờ duyệt)
  → Admin approve → status=1 (published), pending_review=false
  → Admin reject → status=0, pending_review=false, rejection_reason
```

### Flow 3: Purchase & Earnings
```
Customer thêm vào cart → checkout (PayPal/LemonSqueezy)
  → Order created (status=pending)
  → Payment confirmed → status=processing → completed
  → SourceGameEarning auto-created:
      order_amount = item price
      platform_fee = 30%
      seller_amount = 70%
  → Customer nhận download link
```

### Flow 4: Withdrawal
```
Seller yêu cầu rút tiền (min 100,000đ)
  → status=pending
  → Admin approve → status=processing
  → Admin complete → status=completed (ghi transaction_id)
  → Admin reject → status=rejected (ghi lý do)
```

---

## ĐỀ XUẤT API CHO OHHA STUDIO

Dựa trên pattern Blog API + Job Management API đã có, đề xuất bộ API mới:

### Auth: `X-Api-Key` header (giống Blog/Job API)
### Prefix: `/api/manage/`

---

### Module 1: Product Management

```
GET    /api/manage/products                    # Danh sách sản phẩm
GET    /api/manage/products/statistics          # Thống kê tổng quan
GET    /api/manage/products/{id}                # Chi tiết sản phẩm
POST   /api/manage/products                     # Tạo sản phẩm mới
PUT    /api/manage/products/{id}                # Cập nhật sản phẩm
DELETE /api/manage/products/{id}                # Xóa sản phẩm
POST   /api/manage/products/{id}/status         # Đổi trạng thái (publish/draft)
POST   /api/manage/products/{id}/review         # Duyệt/từ chối (admin review)
```

**Filters:** `search`, `status` (0/1), `pending_review`, `seller_id`, `category_id`, `sort_by`, `per_page`

**Response fields:**
```json
{
  "id": 1,
  "sku": "SG-001",
  "name": "Unity 2D Platformer",
  "description": "...",
  "url_key": "unity-2d-platformer",
  "price": 499000,
  "special_price": null,
  "status": 1,
  "pending_review": false,
  "type": "downloadable",
  "thumbnail": "/storage/product/1/thumb.jpg",
  "images": ["..."],
  "categories": [{"id": 1, "name": "Source Game"}],
  "downloadable_links": [{"id": 1, "title": "Source Code", "price": 0}],
  "attributes": {
    "game_engine": "Unity",
    "programming_language": "C#",
    "file_size": "150MB",
    "version": "1.0"
  },
  "seller": {
    "id": 1,
    "shop_name": "GameDev Studio",
    "shop_slug": "gamedev-studio"
  },
  "stats": {
    "view_count": 500,
    "purchase_count": 25,
    "review_count": 8,
    "rating_avg": 4.5
  },
  "created_at": "2026-04-20T10:00:00+07:00"
}
```

---

### Module 2: Order Management

```
GET    /api/manage/orders                       # Danh sách đơn hàng
GET    /api/manage/orders/statistics             # Thống kê đơn hàng
GET    /api/manage/orders/{id}                   # Chi tiết đơn hàng
POST   /api/manage/orders/{id}/status            # Đổi trạng thái
POST   /api/manage/orders/{id}/comment           # Thêm ghi chú
```

**Filters:** `search` (increment_id, email), `status`, `payment_method`, `date_from`, `date_to`, `customer_id`, `sort_by`, `per_page`

**Response fields:**
```json
{
  "id": 1,
  "increment_id": "000000001",
  "status": "completed",
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
      "qty_ordered": 1,
      "price": 499000,
      "total": 499000
    }
  ],
  "payment": {
    "method": "paypal_smart_button",
    "method_title": "PayPal"
  },
  "grand_total": 499000,
  "sub_total": 499000,
  "discount_amount": 0,
  "tax_amount": 0,
  "currency": "VND",
  "is_guest": false,
  "comments": [],
  "invoices": [{"id": 1, "state": "paid", "grand_total": 499000}],
  "created_at": "2026-04-25T14:30:00+07:00"
}
```

---

### Module 3: Seller Management

```
GET    /api/manage/sellers                      # Danh sách sellers
GET    /api/manage/sellers/statistics            # Thống kê sellers
GET    /api/manage/sellers/{id}                  # Chi tiết seller
POST   /api/manage/sellers/{id}/approve          # Duyệt seller
POST   /api/manage/sellers/{id}/reject           # Từ chối seller
POST   /api/manage/sellers/{id}/suspend          # Tạm khóa
POST   /api/manage/sellers/{id}/activate         # Kích hoạt lại
```

**Filters:** `search` (shop_name, email), `status` (pending/active/suspended/rejected/banned), `per_page`

**Response fields:**
```json
{
  "id": 1,
  "customer_id": 5,
  "shop_name": "GameDev Studio",
  "shop_slug": "gamedev-studio",
  "shop_description": "...",
  "shop_logo": "...",
  "contact_email": "seller@email.com",
  "business_type": "individual",
  "status": "active",
  "verified": true,
  "stats": {
    "total_products": 12,
    "total_sales": 85,
    "total_revenue": 42500000,
    "total_earnings": 29750000,
    "total_withdrawn": 20000000,
    "available_balance": 9750000,
    "rating_avg": 4.5,
    "rating_count": 32
  },
  "bank_info": {
    "bank_name": "Vietcombank",
    "bank_account": "****5678",
    "bank_holder": "NGUYEN VAN A"
  },
  "created_at": "2026-03-15T10:00:00+07:00"
}
```

---

### Module 4: Earnings & Withdrawals

```
GET    /api/manage/earnings                     # Danh sách earnings
GET    /api/manage/earnings/statistics           # Thống kê doanh thu

GET    /api/manage/withdrawals                  # Danh sách yêu cầu rút tiền
GET    /api/manage/withdrawals/{id}             # Chi tiết
POST   /api/manage/withdrawals/{id}/approve     # Duyệt (→ processing)
POST   /api/manage/withdrawals/{id}/complete    # Hoàn thành (→ completed)
POST   /api/manage/withdrawals/{id}/reject      # Từ chối (→ rejected)
```

**Earnings filters:** `seller_id`, `status` (pending/completed/refunded), `date_from`, `date_to`

**Earnings response:**
```json
{
  "id": 1,
  "seller": {"id": 1, "shop_name": "GameDev Studio"},
  "order_id": 15,
  "order_increment_id": "000000015",
  "product": {"id": 10, "name": "Unity 2D Platformer"},
  "order_amount": 499000,
  "platform_fee_percent": 30,
  "platform_fee_amount": 149700,
  "seller_amount": 349300,
  "status": "completed",
  "completed_at": "2026-04-26T10:00:00+07:00"
}
```

**Earnings statistics:**
```json
{
  "total_revenue": 125000000,
  "platform_earnings": 37500000,
  "seller_earnings": 87500000,
  "pending_earnings": 5000000,
  "completed_earnings": 82500000,
  "refunded": 0,
  "this_month": {
    "revenue": 15000000,
    "orders": 30
  }
}
```

**Withdrawal complete body:**
```json
{
  "transaction_id": "VCB-20260428-001",
  "admin_note": "Đã chuyển khoản"
}
```

---

### Module 5: Customer Management

```
GET    /api/manage/customers                    # Danh sách khách hàng
GET    /api/manage/customers/statistics          # Thống kê
GET    /api/manage/customers/{id}                # Chi tiết (kèm orders, subscriptions)
POST   /api/manage/customers/{id}/suspend        # Tạm khóa
POST   /api/manage/customers/{id}/activate       # Kích hoạt
```

**Filters:** `search` (name, email), `is_verified`, `is_suspended`, `has_orders`, `per_page`

---

### Module 6: Dashboard / Overview

```
GET    /api/manage/dashboard                    # Tổng quan toàn bộ hệ thống
```

**Response:**
```json
{
  "products": {"total": 68, "published": 60, "pending_review": 3, "draft": 5},
  "orders": {"total": 250, "completed": 200, "pending": 15, "canceled": 35},
  "revenue": {"total": 125000000, "this_month": 15000000, "today": 1500000},
  "sellers": {"total": 12, "active": 10, "pending": 2},
  "customers": {"total": 500, "this_month": 45},
  "withdrawals": {"pending": 3, "pending_amount": 5000000},
  "jobs": {"total": 42, "active": 30},
  "subscriptions": {"active": 25, "mrr": 12500000}
}
```

---

## SO SÁNH PATTERN VỚI CÁC API ĐÃ CÓ

| Aspect | Blog API | Job API | E-Commerce API (mới) |
|--------|----------|---------|---------------------|
| Auth | `X-Api-Key` | `X-Api-Key` | `X-Api-Key` |
| Prefix | `/api/blog/` | `/api/manage/jobs/` | `/api/manage/` |
| Throttle read | 60/min | 60/min | 60/min |
| Throttle write | 10/min | 10/min | 10/min |
| Response format | `{status, message, data}` | `{status, message, data, meta}` | `{status, message, data, meta}` |
| Pagination | ✅ | ✅ | ✅ |
| Search/Filter | ✅ | ✅ | ✅ |

---

## KIẾN TRÚC CODE HIỆN TẠI

```
app/
├── Models/
│   ├── Product.php                    # Extends Webkul Product + seller
│   ├── SourceGameSeller.php           # Seller profile
│   ├── SourceGameEarning.php          # Per-item earnings (70/30 split)
│   └── SourceGameWithdrawal.php       # Withdrawal requests
├── Http/Controllers/
│   ├── SellerProductController.php    # Seller CRUD (web only)
│   ├── SellerController.php           # Seller dashboard + orders (web only)
│   ├── SellerEarningController.php    # Earnings + Withdrawals (web only)
│   └── Admin/
│       ├── AdminProductController.php # Product review (web only)
│       ├── AdminSellerController.php  # Seller management (web only)
│       └── AdminWithdrawalController.php # Withdrawal approval (web only)

packages/Webkul/
├── Product/src/Models/Product.php     # Core EAV product model
├── Sales/src/Models/Order.php         # Core order model
├── Shop/src/Http/Controllers/API/
│   └── ProductController.php          # Public read-only API
├── Admin/src/Http/Controllers/
│   ├── Catalog/ProductController.php  # Admin product CRUD
│   └── Sales/OrderController.php      # Admin order management
```

**Cần xây mới:** API controllers trong `app/Http/Controllers/Api/` cho từng module, tái sử dụng logic từ các controller web hiện có.

---

## ROUTE FILE ĐỀ XUẤT

File: `routes/api-ecommerce-manage.php`

```php
Route::prefix('manage')->middleware(['api.key', 'throttle:60,1'])->group(function () {

    // Dashboard
    Route::get('dashboard', [DashboardManageController::class, 'index']);

    // Products
    Route::prefix('products')->group(function () {
        Route::get('/', [ProductManageController::class, 'list']);
        Route::get('/statistics', [ProductManageController::class, 'statistics']);
        Route::get('/{id}', [ProductManageController::class, 'detail']);
        Route::post('/', [ProductManageController::class, 'store'])->middleware('throttle:10,1');
        Route::put('/{id}', [ProductManageController::class, 'update'])->middleware('throttle:10,1');
        Route::delete('/{id}', [ProductManageController::class, 'destroy'])->middleware('throttle:10,1');
        Route::post('/{id}/status', [ProductManageController::class, 'changeStatus'])->middleware('throttle:10,1');
        Route::post('/{id}/review', [ProductManageController::class, 'review'])->middleware('throttle:10,1');
    });

    // Orders
    Route::prefix('orders')->group(function () {
        Route::get('/', [OrderManageController::class, 'list']);
        Route::get('/statistics', [OrderManageController::class, 'statistics']);
        Route::get('/{id}', [OrderManageController::class, 'detail']);
        Route::post('/{id}/status', [OrderManageController::class, 'changeStatus'])->middleware('throttle:10,1');
        Route::post('/{id}/comment', [OrderManageController::class, 'comment'])->middleware('throttle:10,1');
    });

    // Sellers
    Route::prefix('sellers')->group(function () {
        Route::get('/', [SellerManageController::class, 'list']);
        Route::get('/statistics', [SellerManageController::class, 'statistics']);
        Route::get('/{id}', [SellerManageController::class, 'detail']);
        Route::post('/{id}/approve', [SellerManageController::class, 'approve'])->middleware('throttle:10,1');
        Route::post('/{id}/reject', [SellerManageController::class, 'reject'])->middleware('throttle:10,1');
        Route::post('/{id}/suspend', [SellerManageController::class, 'suspend'])->middleware('throttle:10,1');
        Route::post('/{id}/activate', [SellerManageController::class, 'activate'])->middleware('throttle:10,1');
    });

    // Earnings
    Route::get('earnings', [EarningManageController::class, 'list']);
    Route::get('earnings/statistics', [EarningManageController::class, 'statistics']);

    // Withdrawals
    Route::prefix('withdrawals')->group(function () {
        Route::get('/', [WithdrawalManageController::class, 'list']);
        Route::get('/{id}', [WithdrawalManageController::class, 'detail']);
        Route::post('/{id}/approve', [WithdrawalManageController::class, 'approve'])->middleware('throttle:10,1');
        Route::post('/{id}/complete', [WithdrawalManageController::class, 'complete'])->middleware('throttle:10,1');
        Route::post('/{id}/reject', [WithdrawalManageController::class, 'reject'])->middleware('throttle:10,1');
    });

    // Customers
    Route::prefix('customers')->group(function () {
        Route::get('/', [CustomerManageController::class, 'list']);
        Route::get('/statistics', [CustomerManageController::class, 'statistics']);
        Route::get('/{id}', [CustomerManageController::class, 'detail']);
        Route::post('/{id}/suspend', [CustomerManageController::class, 'suspend'])->middleware('throttle:10,1');
        Route::post('/{id}/activate', [CustomerManageController::class, 'activate'])->middleware('throttle:10,1');
    });
});
```

---

## BUGS ĐÃ FIX (28/04/2026)

| # | File | Vấn đề | Trạng thái |
|---|------|--------|:----------:|
| 1 | `SourceGameSeller::products()` | FK `company_id` → đổi sang `seller_id` | ✅ Fixed |
| 2 | `SellerProductController::store()` | Thiếu `pending_review=true` → đã thêm | ✅ Fixed |
| 3 | `AdminProductController` | Email TODO stubs → implement `ProductApproved`/`ProductRejected` mail | ✅ Fixed |
| 4 | `Order::customer()` | `where('id', -999)` hack → bỏ, dùng morphTo chuẩn | ✅ Fixed |
