# Phân tích Seller Dashboard - https://lamgame.localhost/seller/dashboard

## Tổng quan

Seller Dashboard là trang quản lý dành cho người bán (seller) trên nền tảng Làm Game, cho phép họ:
- Xem thống kê tổng quan (sản phẩm, đơn hàng, doanh thu, đánh giá)
- Quản lý sản phẩm
- Theo dõi đơn hàng
- Rút tiền
- Cài đặt shop

## Routing

### File: `routes/web.php`

```php
Route::prefix('seller')->name('seller.')->middleware('theme')->group(function () {
    // Public routes
    Route::get('register', [SellerController::class, 'showRegisterForm'])->name('register');
    Route::post('register', [SellerController::class, 'register'])->name('register.submit');
    Route::get('pending', [SellerController::class, 'pending'])->name('pending');
    
    // Protected routes (require seller middleware)
    Route::middleware('seller')->group(function () {
        Route::get('dashboard', [SellerController::class, 'dashboard'])->name('dashboard');
        Route::resource('products', SellerProductController::class);
        Route::resource('earnings', SellerEarningController::class);
        Route::resource('withdrawals', SellerWithdrawalController::class);
    });
});
```

**Route structure:**
- `/seller/register` - Đăng ký seller
- `/seller/pending` - Chờ duyệt
- `/seller/dashboard` - Dashboard (require active seller)
- `/seller/products` - Quản lý sản phẩm
- `/seller/earnings` - Thu nhập
- `/seller/withdrawals` - Rút tiền

## Controller Logic

### File: `app/Http/Controllers/SellerController.php`

#### Method: `dashboard()`

**Flow:**
```
1. Check authentication
   ↓
2. Get seller from customer
   ↓
3. Check seller status (must be active)
   ↓
4. Calculate stats
   ↓
5. Get recent orders
   ↓
6. Get top products
   ↓
7. Get monthly revenue
   ↓
8. Return view with data
```

**Code breakdown:**

```php
public function dashboard()
{
    // 1. Get authenticated customer
    $customer = Auth::guard('customer')->user();
    $seller = $customer->seller;

    // 2. Check seller status
    if (!$seller || !$seller->isActive()) {
        return redirect()->route('seller.pending')
            ->with('error', 'Tài khoản seller chưa được kích hoạt.');
    }

    // 3. Calculate stats
    $stats = [
        'total_products' => $seller->total_products,
        'total_sales' => $seller->total_sales,
        'total_revenue' => $seller->total_revenue,
        'rating_avg' => $seller->rating_avg,
        'available_balance' => $this->getAvailableBalance($seller),
    ];

    // 4. Get recent orders (last 10)
    $recentOrders = DB::table('orders')
        ->join('order_items', 'orders.id', '=', 'order_items.order_id')
        ->join('products', 'order_items.product_id', '=', 'products.id')
        ->where('products.company_id', $seller->id)
        ->select('orders.*', 'order_items.product_id', 'order_items.name as product_name', 'order_items.total')
        ->orderBy('orders.created_at', 'desc')
        ->limit(10)
        ->get();

    // 5. Get top products (top 5 by sales)
    $topProducts = DB::table('products')
        ->leftJoin('product_flat', 'products.id', '=', 'product_flat.product_id')
        ->leftJoin('order_items', 'products.id', '=', 'order_items.product_id')
        ->where('products.company_id', $seller->id)
        ->select('products.id', 'product_flat.name', 
                 DB::raw('COUNT(order_items.id) as sales_count'), 
                 DB::raw('SUM(order_items.total) as revenue'))
        ->groupBy('products.id', 'product_flat.name')
        ->orderBy('sales_count', 'desc')
        ->limit(5)
        ->get();

    // 6. Get monthly revenue (last 6 months)
    $monthlyRevenue = DB::table('orders')
        ->join('order_items', 'orders.id', '=', 'order_items.order_id')
        ->join('products', 'order_items.product_id', '=', 'products.id')
        ->where('products.company_id', $seller->id)
        ->where('orders.created_at', '>=', now()->subMonths(6))
        ->select(DB::raw('DATE_FORMAT(orders.created_at, "%Y-%m") as month'), 
                 DB::raw('SUM(order_items.total) as revenue'))
        ->groupBy('month')
        ->orderBy('month')
        ->get();

    // 7. Return view
    return view('seller.dashboard', [
        'seller' => $seller,
        'stats' => $stats,
        'recentOrders' => $recentOrders,
        'topProducts' => $topProducts,
        'monthlyRevenue' => $monthlyRevenue,
        'page_title' => 'Dashboard - Seller - Làm Game',
    ]);
}
```

#### Method: `getAvailableBalance()`

**Purpose:** Tính số dư khả dụng để rút tiền

```php
private function getAvailableBalance($seller)
{
    // Total earnings from completed orders
    $totalEarnings = DB::table('source_game_earnings')
        ->where('seller_id', $seller->id)
        ->where('status', 'completed')
        ->sum('seller_amount');

    // Total withdrawn amount
    $totalWithdrawn = DB::table('source_game_withdrawals')
        ->where('seller_id', $seller->id)
        ->where('status', 'completed')
        ->sum('amount');

    // Available = Earnings - Withdrawn
    return $totalEarnings - $totalWithdrawn;
}
```

## Model: SourceGameSeller

### File: `app/Models/SourceGameSeller.php`

**Table:** `source_game_sellers`

**Fillable fields:**
```php
[
    'customer_id',      // FK to customers table
    'shop_name',        // Tên shop
    'shop_slug',        // URL-friendly slug
    'shop_description', // Mô tả shop
    'shop_logo',        // Logo path
    'shop_banner',      // Banner path
    'contact_email',    // Email liên hệ
    'contact_phone',    // SĐT liên hệ
    'website',          // Website
    'business_type',    // individual/company
    'tax_id',           // Mã số thuế
    'bank_name',        // Tên ngân hàng
    'bank_account',     // Số tài khoản
    'bank_holder',      // Chủ tài khoản
    'status',           // pending/active/suspended/banned
    'verified',         // boolean
    'verified_at',      // datetime
]
```

**Computed columns (from DB):**
- `total_products` - Tổng số sản phẩm
- `total_sales` - Tổng số đơn hàng
- `total_revenue` - Tổng doanh thu
- `rating_avg` - Điểm đánh giá trung bình

**Relationships:**
```php
customer()  // belongsTo Customer
products()  // hasMany Product (via company_id)
```

**Status methods:**
```php
isActive()      // status === 'active'
isPending()     // status === 'pending'
isSuspended()   // status === 'suspended'
isBanned()      // status === 'banned'
canUploadProduct() // isActive() && verified
```

**Accessors:**
```php
getLogoUrlAttribute()   // Return full URL for logo
getBannerUrlAttribute() // Return full URL for banner
```

## View: seller/dashboard.blade.php

### File: `resources/themes/emsaigon/views/seller/dashboard.blade.php`

**Layout structure:**

```
┌─────────────────────────────────────┐
│ Header (Gradient green)             │
│ 👋 Xin chào, {shop_name}!           │
│ Quản lý shop và sản phẩm của bạn    │
├─────────────────────────────────────┤
│ Stats Cards (4 columns)             │
│ ┌────┬────┬────┬────┐               │
│ │📦  │🛒  │💰  │⭐  │               │
│ │Sản │Đơn │Doanh│Đánh│               │
│ │phẩm│hàng│thu  │giá │               │
│ └────┴────┴────┴────┘               │
├─────────────────────────────────────┤
│ Quick Actions (4 buttons)           │
│ ┌────┬────┬────┬────┐               │
│ │➕  │📊  │💳  │⚙️  │               │
│ │Thêm│Báo │Rút │Cài │               │
│ │SP  │cáo │tiền│đặt │               │
│ └────┴────┴────┴────┘               │
├─────────────────────────────────────┤
│ Coming Soon Section                 │
│ 🚧 Tính năng đang phát triển        │
└─────────────────────────────────────┘
```

**Stats Cards:**

1. **Total Products** (📦)
   - Value: `$stats['total_products']`
   - Color: Blue (#1976d2)

2. **Total Sales** (🛒)
   - Value: `$stats['total_sales']`
   - Color: Purple (#7b1fa2)

3. **Total Revenue** (💰)
   - Value: `number_format($stats['total_revenue'])`
   - Color: Green (#388e3c)

4. **Rating Average** (⭐)
   - Value: `number_format($stats['rating_avg'], 1)`
   - Color: Orange (#f57c00)

**Quick Actions:**

1. **Thêm sản phẩm** (➕)
   - Link: `#` (chưa implement)
   - Description: Upload source game mới

2. **Xem báo cáo** (📊)
   - Link: `#` (chưa implement)
   - Description: Thống kê chi tiết

3. **Rút tiền** (💳)
   - Link: `#` (chưa implement)
   - Description: Yêu cầu thanh toán

4. **Cài đặt** (⚙️)
   - Link: `#` (chưa implement)
   - Description: Quản lý shop

## Middleware: CheckSeller

### File: `app/Http/Middleware/CheckSeller.php`

**Purpose:** Kiểm tra quyền truy cập seller routes

**Logic:**
```php
public function handle($request, Closure $next)
{
    // 1. Check authentication
    if (!Auth::guard('customer')->check()) {
        return redirect()->route('shop.customer.session.index');
    }

    $customer = Auth::guard('customer')->user();
    $seller = $customer->seller;

    // 2. Check seller exists
    if (!$seller) {
        return redirect()->route('seller.register')
            ->with('error', 'Vui lòng đăng ký seller trước.');
    }

    // 3. Check seller status
    if ($seller->isPending()) {
        return redirect()->route('seller.pending')
            ->with('info', 'Tài khoản đang chờ duyệt.');
    }

    if ($seller->isSuspended()) {
        return redirect()->route('seller.pending')
            ->with('error', 'Tài khoản đã bị tạm khóa.');
    }

    if ($seller->isBanned()) {
        return redirect()->route('seller.pending')
            ->with('error', 'Tài khoản đã bị cấm.');
    }

    // 4. Allow access
    return $next($request);
}
```

## Database Schema

### Table: `source_game_sellers`

```sql
CREATE TABLE source_game_sellers (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    customer_id BIGINT NOT NULL,
    shop_name VARCHAR(255) NOT NULL,
    shop_slug VARCHAR(255) UNIQUE NOT NULL,
    shop_description TEXT,
    shop_logo VARCHAR(255),
    shop_banner VARCHAR(255),
    contact_email VARCHAR(255) NOT NULL,
    contact_phone VARCHAR(20),
    website VARCHAR(255),
    business_type ENUM('individual', 'company') NOT NULL,
    tax_id VARCHAR(50),
    bank_name VARCHAR(255) NOT NULL,
    bank_account VARCHAR(100) NOT NULL,
    bank_holder VARCHAR(255) NOT NULL,
    status ENUM('pending', 'active', 'suspended', 'banned') DEFAULT 'pending',
    verified BOOLEAN DEFAULT FALSE,
    verified_at TIMESTAMP NULL,
    total_products INT DEFAULT 0,
    total_sales INT DEFAULT 0,
    total_revenue DECIMAL(15,2) DEFAULT 0,
    rating_avg DECIMAL(3,2) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE CASCADE,
    INDEX idx_customer_id (customer_id),
    INDEX idx_status (status),
    INDEX idx_shop_slug (shop_slug)
);
```

### Related Tables:

**source_game_earnings:**
```sql
CREATE TABLE source_game_earnings (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    seller_id BIGINT NOT NULL,
    order_id BIGINT NOT NULL,
    product_id BIGINT NOT NULL,
    order_amount DECIMAL(15,2) NOT NULL,
    commission_rate DECIMAL(5,2) NOT NULL,
    commission_amount DECIMAL(15,2) NOT NULL,
    seller_amount DECIMAL(15,2) NOT NULL,
    status ENUM('pending', 'completed', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (seller_id) REFERENCES source_game_sellers(id),
    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);
```

**source_game_withdrawals:**
```sql
CREATE TABLE source_game_withdrawals (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    seller_id BIGINT NOT NULL,
    amount DECIMAL(15,2) NOT NULL,
    bank_name VARCHAR(255) NOT NULL,
    bank_account VARCHAR(100) NOT NULL,
    bank_holder VARCHAR(255) NOT NULL,
    status ENUM('pending', 'processing', 'completed', 'rejected') DEFAULT 'pending',
    note TEXT,
    processed_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (seller_id) REFERENCES source_game_sellers(id)
);
```

## Data Flow

### 1. Seller Registration Flow

```
Customer → Register Form → Submit
  ↓
Validation
  ↓
Create SourceGameSeller (status: pending)
  ↓
Send email to admin
  ↓
Redirect to /seller/pending
```

### 2. Admin Approval Flow

```
Admin → View pending sellers
  ↓
Review seller info
  ↓
Approve/Reject
  ↓
Update status to 'active'
  ↓
Send email to seller
  ↓
Seller can access dashboard
```

### 3. Dashboard Access Flow

```
Customer login
  ↓
Has seller account?
  ├─ No → Redirect to /seller/register
  └─ Yes → Check status
      ├─ Pending → Redirect to /seller/pending
      ├─ Suspended/Banned → Redirect to /seller/pending with error
      └─ Active → Show dashboard
```

### 4. Stats Calculation

**Total Products:**
```sql
SELECT COUNT(*) FROM products WHERE company_id = {seller_id}
```

**Total Sales:**
```sql
SELECT COUNT(DISTINCT orders.id) 
FROM orders
JOIN order_items ON orders.id = order_items.order_id
JOIN products ON order_items.product_id = products.id
WHERE products.company_id = {seller_id}
```

**Total Revenue:**
```sql
SELECT SUM(order_items.total)
FROM order_items
JOIN products ON order_items.product_id = products.id
WHERE products.company_id = {seller_id}
```

**Rating Average:**
```sql
SELECT AVG(product_reviews.rating)
FROM product_reviews
JOIN products ON product_reviews.product_id = products.id
WHERE products.company_id = {seller_id}
```

## Current Status

### ✅ Implemented:
- Seller registration
- Admin approval system
- Basic dashboard with stats
- Middleware protection
- Status checks

### 🚧 Coming Soon (shown in UI):
- Detailed charts/graphs
- Product management UI
- Order management
- Withdrawal system
- Shop settings
- Reports/Analytics

### 📝 TODO:
1. Implement product CRUD
2. Build order management
3. Create withdrawal flow
4. Add charts (Chart.js/ApexCharts)
5. Shop customization
6. Performance analytics
7. Customer reviews management
8. Notification system

## Security Considerations

1. **Authentication:** Require customer login
2. **Authorization:** Check seller ownership
3. **Status validation:** Only active sellers can access
4. **Data isolation:** Sellers only see their own data
5. **File uploads:** Validate logo/banner uploads
6. **SQL injection:** Use query builder/Eloquent
7. **XSS protection:** Blade escaping

## Performance Optimization

1. **Eager loading:** Load relationships efficiently
2. **Caching:** Cache stats for X minutes
3. **Pagination:** Limit recent orders/products
4. **Indexing:** Add DB indexes on foreign keys
5. **Query optimization:** Use joins instead of N+1

## Future Enhancements

1. **Real-time notifications** (Pusher/WebSockets)
2. **Advanced analytics** (Google Analytics integration)
3. **Multi-language support**
4. **Mobile app** (React Native/Flutter)
5. **API for third-party integrations**
6. **Automated reports** (daily/weekly/monthly emails)
7. **Seller tiers** (bronze/silver/gold with benefits)
8. **Referral program**
