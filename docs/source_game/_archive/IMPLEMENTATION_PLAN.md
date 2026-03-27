# KẾ HOẠCH TRIỂN KHAI SELLER SYSTEM

## 📋 PHÂN TÍCH HIỆN TRẠNG

### ✅ Đã có sẵn
- Bảng `customers` với các fields:
  - first_name, last_name, email, phone
  - status, is_verified, is_suspended
  - customer_group_id (có thể dùng để phân biệt seller)
  - image, notes
- Authentication system (Laravel Sanctum)
- Customer registration & login
- Email verification

### 🎯 Cần phát triển
- Bảng `source_game_sellers` (thông tin seller bổ sung)
- Seller registration form
- Admin approval workflow
- Seller dashboard

---

## 🚀 STEP 1: DATABASE MIGRATION

### Migration 1: Create source_game_sellers table
```php
php artisan make:migration create_source_game_sellers_table
```

**Columns:**
- id, customer_id (FK to customers)
- shop_name, shop_slug, shop_description
- shop_logo, shop_banner
- contact_email, contact_phone, website
- business_type (individual/company)
- tax_id, bank_name, bank_account, bank_holder
- status (pending/active/suspended/banned)
- verified, verified_at
- total_products, total_sales, total_revenue
- rating_avg, rating_count
- timestamps

### Migration 2: Add seller_id to products table
```php
php artisan make:migration add_seller_id_to_products_table
```

**Note:** Có thể tận dụng `company_id` đã có trong products table

---

## 🚀 STEP 2: MODELS & RELATIONSHIPS

### Model: SourceGameSeller
```php
app/Models/SourceGameSeller.php
```

**Relationships:**
- belongsTo(Customer)
- hasMany(Product)
- hasMany(SourceGameEarning)
- hasMany(SourceGameWithdrawal)

**Methods:**
- isActive()
- isPending()
- canUploadProduct()
- getTotalEarnings()
- getAvailableBalance()

---

## 🚀 STEP 3: SELLER REGISTRATION

### Route
```php
Route::get('/seller/register', [SellerController::class, 'showRegisterForm'])
    ->name('seller.register');
Route::post('/seller/register', [SellerController::class, 'register'])
    ->name('seller.register.submit');
```

### Controller: SellerController
```php
app/Http/Controllers/SellerController.php
```

**Methods:**
- showRegisterForm() - Hiển thị form
- register() - Xử lý đăng ký
- dashboard() - Seller dashboard

### View: Seller Registration Form
```php
resources/views/seller/register.blade.php
```

**Fields:**
- Shop Name (required)
- Shop Description
- Contact Email (required)
- Contact Phone
- Website
- Business Type (individual/company)
- Tax ID (if company)
- Bank Details (for withdrawal)
- Terms & Conditions acceptance

---

## 🚀 STEP 4: ADMIN APPROVAL

### Admin Routes
```php
Route::prefix('admin')->middleware('admin')->group(function () {
    Route::get('/sellers/pending', [AdminSellerController::class, 'pending']);
    Route::post('/sellers/{id}/approve', [AdminSellerController::class, 'approve']);
    Route::post('/sellers/{id}/reject', [AdminSellerController::class, 'reject']);
});
```

### Admin Controller
```php
app/Http/Controllers/Admin/AdminSellerController.php
```

**Methods:**
- pending() - List pending sellers
- approve() - Approve seller
- reject() - Reject seller with reason

### Admin View
```php
resources/views/admin/sellers/pending.blade.php
```

---

## 🚀 STEP 5: SELLER DASHBOARD

### Dashboard Route
```php
Route::prefix('seller')->middleware('auth:customer', 'seller')->group(function () {
    Route::get('/dashboard', [SellerController::class, 'dashboard']);
    Route::get('/products', [SellerProductController::class, 'index']);
    Route::get('/earnings', [SellerEarningController::class, 'index']);
});
```

### Dashboard View
```php
resources/views/seller/dashboard.blade.php
```

**Sections:**
- Stats cards (products, sales, revenue, balance)
- Recent sales chart
- Top products
- Recent orders
- Quick actions

---

## 🚀 STEP 6: MIDDLEWARE

### Middleware: CheckSeller
```php
app/Http/Middleware/CheckSeller.php
```

**Logic:**
- Check if customer is seller
- Check if seller is active
- Redirect if not seller or pending

---

## 🚀 STEP 7: NOTIFICATIONS

### Email Templates
1. **Seller Registration Received**
   - To: Seller
   - Subject: "Đăng ký seller thành công"
   - Content: Thông báo đã nhận đơn, đang chờ duyệt

2. **Seller Approved**
   - To: Seller
   - Subject: "Tài khoản seller đã được kích hoạt"
   - Content: Hướng dẫn bắt đầu bán hàng

3. **Seller Rejected**
   - To: Seller
   - Subject: "Đơn đăng ký seller chưa được chấp nhận"
   - Content: Lý do từ chối, hướng dẫn đăng ký lại

4. **New Seller Registration**
   - To: Admin
   - Subject: "Có seller mới đăng ký"
   - Content: Thông tin seller, link duyệt

---

## 📝 IMPLEMENTATION CHECKLIST

### Phase 1: Database (1 ngày)
- [ ] Create migration: source_game_sellers
- [ ] Create migration: add seller_id to products
- [ ] Run migrations
- [ ] Test database structure

### Phase 2: Models (1 ngày)
- [ ] Create SourceGameSeller model
- [ ] Define relationships
- [ ] Create helper methods
- [ ] Write unit tests

### Phase 3: Registration (2 ngày)
- [ ] Create SellerController
- [ ] Create registration form view
- [ ] Implement validation
- [ ] Handle file uploads (logo, banner)
- [ ] Send notification emails
- [ ] Test registration flow

### Phase 4: Admin Approval (2 ngày)
- [ ] Create AdminSellerController
- [ ] Create admin views (list, detail)
- [ ] Implement approve/reject logic
- [ ] Send approval/rejection emails
- [ ] Test approval workflow

### Phase 5: Middleware (1 ngày)
- [ ] Create CheckSeller middleware
- [ ] Register middleware
- [ ] Test access control

### Phase 6: Dashboard (2 ngày)
- [ ] Create dashboard layout
- [ ] Implement stats calculation
- [ ] Create charts (Chart.js)
- [ ] Add quick actions
- [ ] Test dashboard

### Phase 7: Testing & Polish (1 ngày)
- [ ] Write feature tests
- [ ] Test all flows end-to-end
- [ ] Fix bugs
- [ ] Polish UI/UX
- [ ] Update documentation

**Total: 10 ngày**

---

## 🔧 TECHNICAL DETAILS

### Customer Groups Strategy
Tận dụng `customer_group_id` để phân biệt:
- Group 1: Regular customers
- Group 2: Sellers
- Group 3: Premium sellers (future)

### Status Flow
```
Customer registers as seller
    ↓
status = 'pending'
    ↓
Admin reviews
    ↓
Approve → status = 'active', customer_group_id = 2
Reject → status = 'rejected', send email with reason
```

### File Storage
```
storage/
└── seller/
    ├── logos/
    │   └── {seller_id}/logo.jpg
    └── banners/
        └── {seller_id}/banner.jpg
```

### Validation Rules
```php
'shop_name' => 'required|string|max:255|unique:source_game_sellers',
'shop_slug' => 'required|string|max:255|unique:source_game_sellers',
'contact_email' => 'required|email|max:255',
'contact_phone' => 'required|string|max:20',
'business_type' => 'required|in:individual,company',
'bank_account' => 'required|string|max:100',
'bank_holder' => 'required|string|max:255',
'terms_accepted' => 'required|accepted',
```

---

## 🎯 SUCCESS CRITERIA

### Functional
- [ ] Customer có thể đăng ký seller
- [ ] Admin có thể duyệt/từ chối
- [ ] Seller được approved có thể access dashboard
- [ ] Email notifications hoạt động
- [ ] Middleware bảo vệ routes đúng

### Non-functional
- [ ] Form validation đầy đủ
- [ ] UI/UX thân thiện
- [ ] Responsive mobile
- [ ] Load time < 2s
- [ ] No security vulnerabilities

---

## 📊 METRICS TO TRACK

- Number of seller registrations
- Approval rate
- Average approval time
- Active sellers count
- Seller satisfaction score

---

## 🔄 NEXT STEPS (After Phase 1)

1. Product upload workflow
2. Earnings tracking
3. Withdrawal system
4. Seller analytics
5. Review & rating for sellers
