# Tối ưu UI/UX Trang Addresses

## Vấn đề hiện tại
- Empty state đơn giản, không hấp dẫn
- Card layout cơ bản, thiếu visual hierarchy
- Form dài dòng, khó điền
- Dropdown actions ẩn, khó phát hiện
- Không responsive tốt trên mobile

## Giải pháp đã triển khai

### 1. Trang danh sách địa chỉ (index.blade.php)
**File:** `resources/themes/emsaigon/views/customers/account/addresses/index.blade.php`

**Cải tiến:**
- ✅ Header rõ ràng với title + subtitle
- ✅ Button "Thêm địa chỉ mới" nổi bật với icon
- ✅ Card layout hiện đại với border và shadow
- ✅ Badge "Mặc định" dễ nhận biết
- ✅ Actions buttons inline (Sửa, Đặt mặc định, Xóa) thay vì dropdown
- ✅ Empty state đẹp với icon SVG lớn và CTA rõ ràng
- ✅ Responsive tốt trên mobile
- ✅ Hover effects mượt mà

**Features:**
- Card có border xanh khi là địa chỉ mặc định
- Hiển thị đầy đủ: tên, công ty, phone, địa chỉ chi tiết
- Actions có icon và màu sắc phân biệt (xanh-sửa, vàng-mặc định, đỏ-xóa)
- Confirm dialog khi xóa

### 2. Form thêm/sửa địa chỉ (create-optimized.blade.php, edit-optimized.blade.php)
**Files:** 
- `resources/themes/emsaigon/views/customers/account/addresses/create-optimized.blade.php`
- `resources/themes/emsaigon/views/customers/account/addresses/edit-optimized.blade.php`

**Cải tiến:**
- ✅ Layout 2 cột trên desktop, 1 cột trên mobile
- ✅ Nhóm fields theo logic: Thông tin liên hệ, Địa chỉ chi tiết
- ✅ Label có dấu * cho required fields
- ✅ Placeholder hướng dẫn rõ ràng
- ✅ Error messages inline dưới mỗi field
- ✅ Section headers với border bottom
- ✅ Button actions có icon
- ✅ Form HTML thuần, không phụ thuộc Vue component

**Layout:**
```
┌─────────────────────────────────────┐
│ ← Quay lại                          │
│ Thêm địa chỉ mới                    │
│ Điền thông tin địa chỉ...           │
├─────────────────────────────────────┤
│ Thông tin liên hệ                   │
│ ┌──────────┬──────────┐             │
│ │ Họ       │ Tên      │             │
│ ├──────────┼──────────┤             │
│ │ Phone    │ Email    │             │
│ ├──────────┴──────────┤             │
│ │ Công ty (full width)│             │
│ └─────────────────────┘             │
├─────────────────────────────────────┤
│ Địa chỉ chi tiết                    │
│ ┌─────────────────────┐             │
│ │ Địa chỉ (full width)│             │
│ ├──────────┬──────────┤             │
│ │ Quốc gia │ Tỉnh/TP  │             │
│ ├──────────┼──────────┤             │
│ │ Quận/Huyện│ Mã BĐ   │             │
│ └──────────┴──────────┘             │
├─────────────────────────────────────┤
│ ☑ Đặt làm địa chỉ mặc định          │
├─────────────────────────────────────┤
│              [Hủy] [💾 Lưu địa chỉ] │
└─────────────────────────────────────┘
```

## Cách sử dụng

### Option 1: Thay thế file hiện tại (Recommended)
```bash
# Backup files cũ
cp resources/themes/emsaigon/views/customers/account/addresses/index.blade.php resources/themes/emsaigon/views/customers/account/addresses/index.blade.php.bak
cp resources/themes/emsaigon/views/customers/account/addresses/create.blade.php resources/themes/emsaigon/views/customers/account/addresses/create.blade.php.bak
cp resources/themes/emsaigon/views/customers/account/addresses/edit.blade.php resources/themes/emsaigon/views/customers/account/addresses/edit.blade.php.bak

# Sử dụng version mới
cp resources/themes/emsaigon/views/customers/account/addresses/create-optimized.blade.php resources/themes/emsaigon/views/customers/account/addresses/create.blade.php
cp resources/themes/emsaigon/views/customers/account/addresses/edit-optimized.blade.php resources/themes/emsaigon/views/customers/account/addresses/edit.blade.php
```

### Option 2: Test trước khi thay thế
Tạm thời sửa route để test:
```php
// packages/Shop/src/Routes/customer-routes.php
Route::get('addresses/create', function() {
    return view('shop::customers.account.addresses.create-optimized');
})->name('shop.customers.account.addresses.create.test');
```

## Design System

### Colors
- Primary: `#2c5f41` (xanh lá)
- Success: `#10b981` (xanh lục)
- Warning: `#f59e0b` (vàng)
- Danger: `#dc2626` (đỏ)
- Gray: `#6b7280` (xám)
- Border: `#e5e7eb` (xám nhạt)

### Typography
- Title: `1.75rem` (28px), font-weight: 700
- Subtitle: `0.875rem` (14px), color: gray
- Section title: `1.125rem` (18px), font-weight: 600
- Body: `1rem` (16px)
- Label: `0.875rem` (14px), font-weight: 500

### Spacing
- Section margin: `1.5rem` (24px)
- Card padding: `2rem` (32px) desktop, `1.5rem` (24px) mobile
- Form gap: `1.5rem` (24px)
- Button gap: `1rem` (16px)

### Border Radius
- Cards: `12px`
- Buttons: `8px`
- Inputs: `8px`
- Badges: `6px`

## Browser Support
- Chrome/Edge: ✅
- Firefox: ✅
- Safari: ✅
- Mobile browsers: ✅

## Responsive Breakpoints
- Desktop: > 768px (2 columns)
- Mobile: ≤ 768px (1 column, stacked buttons)

## Notes
- Form không dùng Vue component để tránh dependency issues
- Sử dụng HTML form thuần với CSRF token
- Confirm dialog dùng JavaScript native `confirm()`
- Icons sử dụng Heroicons (SVG inline)
