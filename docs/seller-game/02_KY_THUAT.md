# KIẾN TRÚC KỸ THUẬT SELLER GAME

## 1. Cấu trúc thư mục

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── SellerController.php          # Đăng ký, dashboard seller
│   │   ├── SellerProductController.php   # CRUD sản phẩm (root)
│   │   ├── SellerEarningController.php   # Quản lý doanh thu
│   │   ├── Seller/
│   │   │   └── SellerProductController.php  # CRUD sản phẩm (namespace)
│   │   └── Admin/
│   │       ├── AdminSellerController.php    # Admin quản lý seller
│   │       └── AdminProductController.php   # Admin duyệt sản phẩm
│   └── Middleware/
│       └── CheckSeller.php               # Middleware kiểm tra seller
├── Models/
│   ├── SourceGameSeller.php              # Model seller
│   ├── SourceGameEarning.php             # Model doanh thu
│   └── SourceGameWithdrawal.php          # Model rút tiền
├── Mail/
│   ├── NewSellerRegistration.php         # Email thông báo admin
│   ├── SellerApproved.php                # Email duyệt seller
│   └── SellerRejected.php                # Email từ chối seller
└── DataGrids/
    └── Admin/
        └── SellerDataGrid.php            # DataGrid admin

resources/
├── views/
│   ├── seller/                           # Views seller portal
│   │   ├── dashboard.blade.php
│   │   ├── products/
│   │   ├── earnings/
│   │   └── withdrawals/
│   └── admin/
│       └── sellers/                      # Views admin seller
└── themes/
    └── emsaigon/
        └── views/
            └── seller/                   # Theme views

database/
└── migrations/
    ├── 2025_12_16_174321_create_source_game_sellers_table.php
    └── 2025_12_23_104400_create_earnings_withdrawals_tables.php
```

## 2. Models

### 2.1 SourceGameSeller

```php
// app/Models/SourceGameSeller.php

protected $fillable = [
    'customer_id', 'shop_name', 'shop_slug', 'shop_description',
    'shop_logo', 'shop_banner', 'contact_email', 'contact_phone',
    'website', 'business_type', 'tax_id', 'bank_name', 'bank_account',
    'bank_holder', 'status', 'verified', 'verified_at'
];

// Relationships
public function customer()     // belongsTo Customer
public function products()     // hasMany Product (via company_id)

// Status checks
public function isActive()     // status === 'active'
public function isPending()    // status === 'pending'
public function isSuspended()  // status === 'suspended'
public function canUploadProduct() // isActive() && verified
```

### 2.2 SourceGameEarning

```php
// app/Models/SourceGameEarning.php

protected $fillable = [
    'seller_id', 'order_id', 'order_item_id', 'product_id',
    'order_amount', 'platform_fee_percent', 'platform_fee_amount',
    'seller_amount', 'status', 'completed_at'
];

// Relationships
public function seller()   // belongsTo SourceGameSeller
public function order()    // belongsTo Order
public function product()  // belongsTo Product

// Static method
public static function createFromOrder($order)  // Tạo earning từ order
```

### 2.3 SourceGameWithdrawal

```php
// app/Models/SourceGameWithdrawal.php

protected $fillable = [
    'seller_id', 'amount', 'status', 'bank_name', 'bank_account',
    'bank_holder', 'note', 'admin_note', 'transaction_id',
    'processed_at', 'processed_by'
];

// Status: pending, processing, completed, rejected
```

## 3. Controllers

### 3.1 SellerController

| Method | Chức năng |
|--------|-----------|
| `showRegisterForm()` | Hiển thị form đăng ký/cập nhật seller |
| `register()` | Xử lý đăng ký/cập nhật seller |
| `pending()` | Trang chờ duyệt |
| `dashboard()` | Dashboard seller với stats |
| `orders()` | Danh sách đơn hàng |
| `orderShow($id)` | Chi tiết đơn hàng |
| `analytics()` | Phân tích doanh thu |

### 3.2 SellerProductController

| Method | Chức năng |
|--------|-----------|
| `index()` | Danh sách sản phẩm của seller |
| `create()` | Form tạo sản phẩm |
| `store()` | Lưu sản phẩm mới |
| `edit($id)` | Form sửa sản phẩm |
| `update($id)` | Cập nhật sản phẩm |
| `destroy($id)` | Xóa sản phẩm |
| `submitForReview($id)` | Gửi sản phẩm để duyệt |

### 3.3 AdminSellerController

| Method | Chức năng |
|--------|-----------|
| `index()` | Danh sách tất cả seller |
| `pending()` | Danh sách seller chờ duyệt |
| `show($id)` | Chi tiết seller |
| `approve($id)` | Duyệt seller |
| `reject($id)` | Từ chối seller |
| `suspend($id)` | Tạm ngưng seller |
| `activate($id)` | Kích hoạt lại seller |

## 4. Middleware

### CheckSeller

```php
// app/Http/Middleware/CheckSeller.php

// Kiểm tra:
// 1. Customer đã đăng nhập
// 2. Customer có seller profile
// 3. Seller status = active

// Redirect:
// - Chưa đăng nhập → /auth/login
// - Chưa có seller → /seller/register
// - Pending/Inactive → /seller/pending
```

## 5. Tính toán doanh thu

```php
// SourceGameEarning::createFromOrder($order)

$orderAmount = $item->total;
$platformFeePercent = 30.00;  // 30%
$platformFeeAmount = $orderAmount * 0.30;
$sellerAmount = $orderAmount - $platformFeeAmount;

// Cập nhật stats seller
$seller->increment('total_sales');
$seller->increment('total_revenue', $sellerAmount);
```

## 6. Tính số dư khả dụng

```php
$availableBalance = 
    total_earnings (completed) 
    - total_withdrawn (completed) 
    - pending_withdrawals
```
