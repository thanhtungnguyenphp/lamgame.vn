# API & ROUTES SELLER GAME

## 1. Routes tổng quan

### File: `routes/web.php`

## 2. Seller Routes

```php
Route::prefix('seller')->name('seller.')->middleware('theme')->group(function () {
    // Public routes (chỉ cần đăng nhập customer)
    Route::get('register', [SellerController::class, 'showRegisterForm'])->name('register');
    Route::post('register', [SellerController::class, 'register'])->name('register.submit');
    Route::get('pending', [SellerController::class, 'pending'])->name('pending');
    
    // Protected routes (cần seller active)
    Route::middleware('seller')->group(function () {
        // Dashboard
        Route::get('dashboard', [SellerController::class, 'dashboard'])->name('dashboard');
        Route::get('analytics', [SellerController::class, 'analytics'])->name('analytics');
        
        // Products
        Route::resource('products', SellerProductController::class);
        
        // Orders
        Route::get('orders', [SellerController::class, 'orders'])->name('orders.index');
        Route::get('orders/{id}', [SellerController::class, 'orderShow'])->name('orders.show');
        
        // Earnings
        Route::get('earnings', [SellerEarningController::class, 'index'])->name('earnings.index');
        
        // Withdrawals
        Route::get('withdrawals', [SellerWithdrawalController::class, 'index'])->name('withdrawals.index');
        Route::get('withdrawals/create', [SellerWithdrawalController::class, 'create'])->name('withdrawals.create');
        Route::post('withdrawals', [SellerWithdrawalController::class, 'store'])->name('withdrawals.store');
    });
});
```

## 3. Admin Routes

```php
Route::prefix('admin')->middleware('admin')->name('admin.')->group(function () {
    // Seller Management
    Route::prefix('sellers')->name('sellers.')->group(function () {
        Route::get('/', [AdminSellerController::class, 'index'])->name('index');
        Route::get('pending', [AdminSellerController::class, 'pending'])->name('pending');
        Route::get('{id}', [AdminSellerController::class, 'show'])->name('show');
        Route::post('{id}/approve', [AdminSellerController::class, 'approve'])->name('approve');
        Route::post('{id}/reject', [AdminSellerController::class, 'reject'])->name('reject');
        Route::post('{id}/suspend', [AdminSellerController::class, 'suspend'])->name('suspend');
        Route::post('{id}/activate', [AdminSellerController::class, 'activate'])->name('activate');
    });

    // Product Management
    Route::prefix('products')->name('products.')->group(function () {
        Route::get('sellers', [AdminProductController::class, 'sellers'])->name('sellers');
        Route::get('pending', [AdminProductController::class, 'pending'])->name('pending');
        Route::get('{id}/review', [AdminProductController::class, 'review'])->name('review');
        Route::post('{id}/approve', [AdminProductController::class, 'approve'])->name('approve');
        Route::post('{id}/reject', [AdminProductController::class, 'reject'])->name('reject');
    });
});
```

## 4. Chi tiết Routes

### 4.1 Seller Portal

| Method | URI | Name | Controller | Mô tả |
|--------|-----|------|------------|-------|
| GET | `/seller/register` | seller.register | SellerController@showRegisterForm | Form đăng ký |
| POST | `/seller/register` | seller.register.submit | SellerController@register | Xử lý đăng ký |
| GET | `/seller/pending` | seller.pending | SellerController@pending | Trang chờ duyệt |
| GET | `/seller/dashboard` | seller.dashboard | SellerController@dashboard | Dashboard |
| GET | `/seller/analytics` | seller.analytics | SellerController@analytics | Phân tích |
| GET | `/seller/products` | seller.products.index | SellerProductController@index | DS sản phẩm |
| GET | `/seller/products/create` | seller.products.create | SellerProductController@create | Form tạo |
| POST | `/seller/products` | seller.products.store | SellerProductController@store | Lưu sản phẩm |
| GET | `/seller/products/{id}/edit` | seller.products.edit | SellerProductController@edit | Form sửa |
| PUT | `/seller/products/{id}` | seller.products.update | SellerProductController@update | Cập nhật |
| DELETE | `/seller/products/{id}` | seller.products.destroy | SellerProductController@destroy | Xóa |
| GET | `/seller/orders` | seller.orders.index | SellerController@orders | DS đơn hàng |
| GET | `/seller/orders/{id}` | seller.orders.show | SellerController@orderShow | Chi tiết đơn |
| GET | `/seller/earnings` | seller.earnings.index | SellerEarningController@index | DS doanh thu |
| GET | `/seller/withdrawals` | seller.withdrawals.index | SellerWithdrawalController@index | DS rút tiền |
| GET | `/seller/withdrawals/create` | seller.withdrawals.create | SellerWithdrawalController@create | Form rút tiền |
| POST | `/seller/withdrawals` | seller.withdrawals.store | SellerWithdrawalController@store | Tạo yêu cầu |

### 4.2 Admin Management

| Method | URI | Name | Controller | Mô tả |
|--------|-----|------|------------|-------|
| GET | `/admin/sellers` | admin.sellers.index | AdminSellerController@index | DS sellers |
| GET | `/admin/sellers/pending` | admin.sellers.pending | AdminSellerController@pending | DS chờ duyệt |
| GET | `/admin/sellers/{id}` | admin.sellers.show | AdminSellerController@show | Chi tiết |
| POST | `/admin/sellers/{id}/approve` | admin.sellers.approve | AdminSellerController@approve | Duyệt |
| POST | `/admin/sellers/{id}/reject` | admin.sellers.reject | AdminSellerController@reject | Từ chối |
| POST | `/admin/sellers/{id}/suspend` | admin.sellers.suspend | AdminSellerController@suspend | Tạm ngưng |
| POST | `/admin/sellers/{id}/activate` | admin.sellers.activate | AdminSellerController@activate | Kích hoạt |

## 5. Middleware

### 5.1 CheckSeller Middleware

```php
// Đăng ký trong app/Http/Kernel.php
protected $middlewareAliases = [
    'seller' => \App\Http\Middleware\CheckSeller::class,
];
```

### 5.2 Logic kiểm tra

```php
// 1. Kiểm tra đăng nhập customer
if (!Auth::guard('customer')->check()) {
    return redirect('/auth/login');
}

// 2. Kiểm tra có seller profile
if (!$customer->seller) {
    return redirect()->route('seller.register');
}

// 3. Kiểm tra status
if ($seller->isPending()) {
    return redirect()->route('seller.pending');
}

if (!$seller->isActive()) {
    return redirect()->route('seller.pending');
}
```

## 6. Validation Rules

### 6.1 Đăng ký Seller

```php
[
    'shop_name' => 'required|string|max:255|unique:source_game_sellers',
    'shop_description' => 'nullable|string|max:1000',
    'shop_logo' => 'nullable|image|max:2048',
    'shop_banner' => 'nullable|image|max:5120',
    'contact_email' => 'required|email|max:255',
    'contact_phone' => 'nullable|string|max:20',
    'website' => 'nullable|url|max:255',
    'business_type' => 'required|in:individual,company',
    'tax_id' => 'required_if:business_type,company|nullable|string|max:50',
    'bank_name' => 'required|string|max:255',
    'bank_account' => 'required|string|max:100',
    'bank_holder' => 'required|string|max:255',
    'terms_accepted' => 'required|accepted',
]
```

### 6.2 Tạo sản phẩm

```php
[
    'type' => 'required|in:simple,downloadable',
    'attribute_family_id' => 'required|exists:attribute_families,id',
    'sku' => 'required|unique:products,sku|regex:/^[a-zA-Z0-9]+(?:-[a-zA-Z0-9]+)*$/',
    'name' => 'required|string|max:255',
    'url_key' => 'required|string|unique:product_flat,url_key',
    'short_description' => 'nullable|string',
    'description' => 'nullable|string',
    'price' => 'required|numeric|min:0',
    'special_price' => 'nullable|numeric|min:0',
    'categories' => 'required|array',
    'images.*' => 'nullable|image|max:2048',
    'source_file' => 'nullable|file|max:524288', // 512MB
]
```

### 6.3 Rút tiền

```php
[
    'amount' => 'required|numeric|min:100000|max:' . $availableBalance,
    'note' => 'nullable|string|max:500',
]
```
