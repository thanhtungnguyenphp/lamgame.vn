# Fix: Form Thêm/Sửa Địa Chỉ Không Hiển Thị

## Vấn đề phát hiện

### Từ ảnh `docs/error_form_add_address.png`:
- Trang chỉ hiển thị title "Add Address"
- Form không xuất hiện (màn hình trắng)
- Chỉ có sidebar menu hiển thị

### Nguyên nhân:
Vue component `v-create-customer-address` không được render đúng cách, có thể do:
1. Vue app chưa được khởi tạo
2. Component template bị lỗi
3. JavaScript dependencies chưa load

## Giải pháp đã triển khai

### 1. Thay thế Vue component bằng HTML form thuần

**Files đã thay thế:**
- `resources/themes/emsaigon/views/customers/account/addresses/create.blade.php`
- `resources/themes/emsaigon/views/customers/account/addresses/edit.blade.php`

**Backup files:**
- `create.blade.php.bak`
- `edit.blade.php.bak`

### 2. Form mới có các tính năng:

#### **Layout & Structure:**
- ✅ Header với nút "Quay lại"
- ✅ Title + subtitle rõ ràng
- ✅ Form chia thành 2 sections:
  - Thông tin liên hệ (Họ, Tên, Phone, Email, Công ty)
  - Địa chỉ chi tiết (Địa chỉ, Quốc gia, Tỉnh/TP, Quận/Huyện, Mã BĐ, Mã số thuế)
- ✅ Checkbox "Đặt làm địa chỉ mặc định"
- ✅ Actions: Hủy + Lưu địa chỉ

#### **UX Improvements:**
- ✅ Grid layout 2 cột trên desktop, 1 cột trên mobile
- ✅ Required fields có dấu * đỏ
- ✅ Placeholder text hướng dẫn
- ✅ Error messages inline dưới mỗi field
- ✅ Alert box hiển thị tất cả lỗi validation ở đầu form
- ✅ Section headers với border bottom
- ✅ Button có icon SVG

#### **Technical:**
- ✅ HTML form thuần với `method="POST"` và `@csrf`
- ✅ Không phụ thuộc Vue.js
- ✅ Sử dụng Blade directives: `@error`, `old()`, `@foreach`
- ✅ Responsive CSS với media queries

## Logic Backend

### Controller: `AddressController.php`

**Route create:**
```php
GET  /customer/account/addresses/create  -> create()
POST /customer/account/addresses/create  -> store()
```

**Route edit:**
```php
GET  /customer/account/addresses/{id}/edit  -> edit($id)
PUT  /customer/account/addresses/{id}       -> update($id)
```

### Validation: `AddressRequest.php`

**Required fields:**
- first_name
- last_name
- address (array, min 1)
- city
- phone
- email

**Conditional required:**
- country (nếu `core()->isCountryRequired()`)
- state (nếu `core()->isStateRequired()`)
- postcode (nếu `core()->isPostCodeRequired()`)

**Optional:**
- company_name
- vat_id

### Store Logic:

```php
1. Validate request data
2. Dispatch event: 'customer.addresses.create.before'
3. Merge data với customer_id
4. Convert address array thành string (implode với PHP_EOL)
5. Create address qua CustomerAddressRepository
6. Dispatch event: 'customer.addresses.create.after'
7. Flash success message
8. Redirect về addresses index
```

## Testing Checklist

### Form Create:
- [ ] Truy cập `/customer/account/addresses/create`
- [ ] Form hiển thị đầy đủ fields
- [ ] Submit form trống → hiển thị lỗi validation
- [ ] Điền đầy đủ thông tin → tạo địa chỉ thành công
- [ ] Check "Đặt làm địa chỉ mặc định" → địa chỉ được set default
- [ ] Click "Hủy" → quay về trang index

### Form Edit:
- [ ] Truy cập `/customer/account/addresses/{id}/edit`
- [ ] Form hiển thị dữ liệu hiện tại
- [ ] Sửa thông tin → cập nhật thành công
- [ ] Validation hoạt động đúng

### Responsive:
- [ ] Desktop (>768px): 2 cột
- [ ] Mobile (≤768px): 1 cột, buttons full width

### Browser:
- [ ] Chrome/Edge
- [ ] Firefox
- [ ] Safari
- [ ] Mobile browsers

## Files Changed

```
resources/themes/emsaigon/views/customers/account/addresses/
├── index.blade.php (đã update trước đó)
├── create.blade.php (replaced)
├── create.blade.php.bak (backup)
├── create-optimized.blade.php (source)
├── edit.blade.php (replaced)
├── edit.blade.php.bak (backup)
└── edit-optimized.blade.php (source)
```

## Rollback Instructions

Nếu cần quay lại version cũ:

```bash
cd resources/themes/emsaigon/views/customers/account/addresses/

# Restore create
cp create.blade.php.bak create.blade.php

# Restore edit
cp edit.blade.php.bak edit.blade.php
```

## Next Steps

1. Test form trên browser
2. Kiểm tra validation messages
3. Test responsive trên mobile
4. Xác nhận địa chỉ được lưu vào database
5. Kiểm tra default address logic
6. Test edit form với dữ liệu có sẵn

## Notes

- Form không cần JavaScript để hoạt động
- Tất cả validation được xử lý server-side
- CSS được inline trong `@push('styles')`
- Icons sử dụng Heroicons SVG
- Form tương thích với validation rules hiện tại
