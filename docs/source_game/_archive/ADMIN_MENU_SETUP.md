# HƯỚNG DẪN THÊM MENU SELLER VÀO ADMIN

## ✅ ĐÃ TẠO

### 1. Config File
**File:** `config/seller-menu.php`

Định nghĩa menu items cho Seller:
```php
[
    [
        'key'   => 'sellers',
        'name'  => 'Sellers',
        'route' => 'admin.sellers.index',
        'sort'  => 5,
        'icon'  => 'icon-users',
    ],
    [
        'key'   => 'sellers.all',
        'name'  => 'Tất cả Sellers',
        'route' => 'admin.sellers.index',
        'sort'  => 1,
    ],
    [
        'key'   => 'sellers.pending',
        'name'  => 'Chờ duyệt',
        'route' => 'admin.sellers.pending',
        'sort'  => 2,
    ],
]
```

### 2. Service Provider
**File:** `app/Providers/MenuServiceProvider.php`

Load menu config vào Bagisto admin menu.

### 3. Provider đã đăng ký
**File:** `bootstrap/providers.php`

MenuServiceProvider đã có trong danh sách providers.

---

## 🔧 CÁCH HOẠT ĐỘNG

### Menu Structure
```
Sellers (icon-users)
├── Tất cả Sellers → /admin/sellers
└── Chờ duyệt → /admin/sellers/pending
```

### Config Merge
MenuServiceProvider sẽ merge `seller-menu.php` vào `menu.admin` của Bagisto.

---

## 🧪 KIỂM TRA

### 1. Clear cache
```bash
php artisan config:clear
php artisan cache:clear
```

### 2. Truy cập admin panel
```
URL: /admin
Login với admin account
```

### 3. Kiểm tra sidebar
- Tìm menu "Sellers" với icon users
- Click vào sẽ thấy 2 submenu:
  - Tất cả Sellers
  - Chờ duyệt

---

## 🐛 TROUBLESHOOTING

### Menu không hiện
1. **Clear cache:**
   ```bash
   php artisan config:clear
   php artisan cache:clear
   php artisan view:clear
   ```

2. **Kiểm tra routes:**
   ```bash
   php artisan route:list | grep sellers
   ```
   Phải có:
   - admin.sellers.index
   - admin.sellers.pending

3. **Kiểm tra provider:**
   ```bash
   php artisan about
   ```
   MenuServiceProvider phải có trong danh sách.

### Icon không hiện
Bagisto sử dụng icon classes:
- `icon-users` - Users icon
- `icon-dashboard` - Dashboard icon
- `icon-sales` - Sales icon
- `icon-product` - Product icon

Nếu icon không có, để trống `'icon' => ''`

### Submenu không hiện
Đảm bảo `key` có format đúng:
- Parent: `sellers`
- Child: `sellers.all`, `sellers.pending`

---

## 🎨 CUSTOM ICON

### Sử dụng icon có sẵn
```php
'icon' => 'icon-users',      // Users
'icon' => 'icon-settings',   // Settings
'icon' => 'icon-customer',   // Customer
'icon' => 'icon-cms',        // CMS
```

### Thêm custom icon
1. Thêm CSS vào admin:
```css
.icon-seller::before {
    content: "\f007"; /* FontAwesome user icon */
}
```

2. Sử dụng trong menu:
```php
'icon' => 'icon-seller',
```

---

## 📝 THÊM MENU ITEMS MỚI

### Thêm vào config/seller-menu.php
```php
[
    'key'   => 'sellers.statistics',
    'name'  => 'Thống kê',
    'route' => 'admin.sellers.statistics',
    'sort'  => 3,
    'icon'  => '',
],
```

### Clear cache
```bash
php artisan config:clear
```

---

## 🔐 PERMISSIONS (ACL)

Nếu cần thêm permissions cho menu:

### 1. Tạo file config/seller-acl.php
```php
<?php

return [
    [
        'key'   => 'sellers',
        'name'  => 'Sellers',
        'route' => 'admin.sellers.index',
        'sort'  => 5,
    ],
    [
        'key'   => 'sellers.view',
        'name'  => 'View Sellers',
        'route' => 'admin.sellers.index',
        'sort'  => 1,
    ],
    [
        'key'   => 'sellers.approve',
        'name'  => 'Approve Sellers',
        'route' => 'admin.sellers.approve',
        'sort'  => 2,
    ],
];
```

### 2. Load trong MenuServiceProvider
```php
$this->mergeConfigFrom(__DIR__ . '/../../config/seller-acl.php', 'acl');
```

---

## ✅ CHECKLIST

- [x] Config file created: `config/seller-menu.php`
- [x] MenuServiceProvider updated
- [x] Provider registered in `bootstrap/providers.php`
- [x] Routes exist: `admin.sellers.index`, `admin.sellers.pending`
- [ ] Cache cleared
- [ ] Menu visible in admin sidebar
- [ ] Submenu items clickable
- [ ] Routes working correctly

---

## 📚 REFERENCES

- Bagisto Menu Docs: https://devdocs.bagisto.com/
- Menu Config: `packages/Webkul/Admin/src/Config/menu.php`
- ACL Config: `packages/Webkul/Admin/src/Config/acl.php`

---

**Created:** 2025-12-17  
**Status:** ✅ Completed
