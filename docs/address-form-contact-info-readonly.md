# Tối ưu Form Địa Chỉ - Tách biệt Contact Info và Address

## Thay đổi logic

### Trước đây:
Form địa chỉ cho phép nhập/sửa tất cả thông tin:
- Họ, Tên
- Số điện thoại
- Email
- Công ty
- Địa chỉ chi tiết

**Vấn đề:**
- Thông tin liên hệ bị duplicate giữa profile và addresses
- User có thể tạo nhiều địa chỉ với thông tin liên hệ khác nhau
- Khó đồng bộ khi user update profile
- Gây nhầm lẫn về thông tin nào là chính xác

### Sau khi tối ưu:
Form địa chỉ chỉ cho phép thêm/sửa **địa chỉ**, thông tin liên hệ lấy từ profile:

**Thông tin liên hệ (Read-only):**
- Hiển thị từ `auth()->guard('customer')->user()`
- Không cho phép chỉnh sửa
- Link đến trang profile để update

**Địa chỉ (Editable):**
- Địa chỉ cụ thể
- Tỉnh/Thành phố
- Quận/Huyện
- Phường/Xã
- Mã bưu điện

## UI Changes

### Contact Information Section

**Trước:**
```html
<input type="text" name="first_name" required>
<input type="text" name="last_name" required>
<input type="tel" name="phone" required>
<input type="email" name="email" required>
<input type="text" name="company_name">
```

**Sau:**
```html
<div class="info-display">
  <div class="info-row">
    <span class="info-label">Họ tên:</span>
    <span class="info-value">Nguyễn Văn A</span>
  </div>
  <div class="info-row">
    <span class="info-label">Số điện thoại:</span>
    <span class="info-value">0912345678</span>
  </div>
  <div class="info-row">
    <span class="info-label">Email:</span>
    <span class="info-value">email@example.com</span>
  </div>
  <p class="info-note">
    Để thay đổi thông tin liên hệ, vui lòng cập nhật trong 
    <a href="/customer/account/profile/edit">Thông tin cá nhân</a>
  </p>
</div>

<!-- Hidden fields for form submission -->
<input type="hidden" name="first_name" value="...">
<input type="hidden" name="last_name" value="...">
<input type="hidden" name="phone" value="...">
<input type="hidden" name="email" value="...">
<input type="hidden" name="company_name" value="">
```

### Visual Design

**Info Display Box:**
- Background: Light gray (#f9fafb)
- Border: 1px solid #e5e7eb
- Border radius: 8px
- Padding: 1.5rem

**Info Rows:**
- Label: Gray (#6b7280), min-width 140px
- Value: Dark (#1f2937), font-weight 500
- Border bottom between rows

**Info Note:**
- Background: Light blue (#eff6ff)
- Border: 1px solid #bfdbfe
- Icon + text + link
- Link underlined, blue color

## Data Flow

### Create Address

```
User clicks "Thêm địa chỉ mới"
  ↓
Form loads with:
  - Contact info from auth()->user() (read-only)
  - Empty address fields (editable)
  ↓
User fills address fields
  ↓
Submit form with:
  - Hidden fields: first_name, last_name, phone, email (from profile)
  - Visible fields: address, state, city, postcode (user input)
  ↓
AddressController::store()
  - Saves address with contact info from profile
```

### Edit Address

```
User clicks "Sửa" on existing address
  ↓
Form loads with:
  - Contact info from auth()->user() (read-only, NOT from $address)
  - Address fields from $address (editable)
  ↓
User updates address fields
  ↓
Submit form with:
  - Hidden fields: first_name, last_name, phone, email (from profile)
  - Visible fields: updated address data
  ↓
AddressController::update()
  - Updates address with latest contact info from profile
```

## Benefits

### 1. Single Source of Truth
- Contact info chỉ lưu ở profile
- Mọi địa chỉ đều dùng contact info hiện tại
- Update profile → tất cả địa chỉ tự động cập nhật

### 2. Better UX
- User không phải nhập lại thông tin liên hệ
- Rõ ràng: địa chỉ là "nơi giao hàng", không phải "người nhận"
- Giảm confusion về thông tin nào là chính xác

### 3. Data Consistency
- Không có duplicate contact info
- Không có conflict giữa profile và addresses
- Dễ maintain và sync data

### 4. Simplified Form
- Form ngắn hơn, focus vào địa chỉ
- Ít fields → ít lỗi validation
- Faster checkout experience

## Implementation Details

### Hidden Fields
```php
<input type="hidden" name="first_name" value="{{ auth()->guard('customer')->user()->first_name }}">
<input type="hidden" name="last_name" value="{{ auth()->guard('customer')->user()->last_name }}">
<input type="hidden" name="phone" value="{{ auth()->guard('customer')->user()->phone }}">
<input type="hidden" name="email" value="{{ auth()->guard('customer')->user()->email }}">
<input type="hidden" name="company_name" value="">
```

**Why hidden fields?**
- Backend validation vẫn require các fields này
- Không cần thay đổi controller logic
- Form vẫn submit đầy đủ data như cũ

### Controller Logic (Unchanged)
```php
// AddressController::store()
$data = array_merge(request()->only([
    'company_name',
    'first_name',    // từ hidden field
    'last_name',     // từ hidden field
    'phone',         // từ hidden field
    'email',         // từ hidden field
    'address',
    'country',
    'state',
    'city',
    'postcode',
]), [
    'customer_id' => $customer->id,
    'address' => implode(PHP_EOL, array_filter($request->input('address'))),
]);
```

## CSS Styles

```css
.info-display {
  background: #f9fafb;
  border: 1px solid #e5e7eb;
  border-radius: 8px;
  padding: 1.5rem;
}

.info-row {
  display: flex;
  padding: 0.75rem 0;
  border-bottom: 1px solid #e5e7eb;
}

.info-label {
  font-weight: 500;
  color: #6b7280;
  min-width: 140px;
}

.info-value {
  color: #1f2937;
  font-weight: 500;
}

.info-note {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  margin: 1rem 0 0 0;
  padding: 0.75rem;
  background: #eff6ff;
  border: 1px solid #bfdbfe;
  border-radius: 6px;
  font-size: 0.875rem;
  color: #1e40af;
}

/* Mobile responsive */
@media (max-width: 768px) {
  .info-row {
    flex-direction: column;
    gap: 0.25rem;
  }
  
  .info-label {
    min-width: auto;
    font-size: 0.75rem;
  }
  
  .info-note {
    font-size: 0.75rem;
  }
}
```

## Migration Notes

### Existing Addresses
Địa chỉ cũ vẫn giữ nguyên contact info đã lưu. Khi user edit:
- Contact info sẽ được update thành info từ profile hiện tại
- Address data giữ nguyên

### Database Schema
Không cần thay đổi schema. Table `customer_addresses` vẫn có:
- first_name
- last_name
- phone
- email
- company_name
- address
- city
- state
- country
- postcode

## Testing Checklist

### Create Address
- [ ] Contact info hiển thị đúng từ profile
- [ ] Link "Thông tin cá nhân" hoạt động
- [ ] Chỉ cần điền địa chỉ, không cần điền contact info
- [ ] Submit thành công với contact info từ profile
- [ ] Address được lưu với đầy đủ thông tin

### Edit Address
- [ ] Contact info hiển thị từ profile (không phải từ $address)
- [ ] Address fields hiển thị data từ $address
- [ ] Update address thành công
- [ ] Contact info được sync từ profile mới nhất

### Profile Update Flow
- [ ] Update profile (name, phone, email)
- [ ] Tạo địa chỉ mới → dùng info mới
- [ ] Edit địa chỉ cũ → dùng info mới
- [ ] Checkout → hiển thị info mới

### Responsive
- [ ] Desktop: info rows horizontal
- [ ] Mobile: info rows vertical
- [ ] Link vẫn clickable trên mobile

## Future Enhancements

### 1. Multiple Recipients (Optional)
Nếu cần giao hàng cho người khác:
- Thêm checkbox "Giao cho người khác"
- Show fields: recipient_name, recipient_phone
- Lưu vào address record

### 2. Address Labels
- Thêm field "label" (Nhà riêng, Văn phòng, Khác)
- Hiển thị icon tương ứng
- Dễ phân biệt nhiều địa chỉ

### 3. Address Validation
- Tích hợp Google Maps API
- Validate địa chỉ có tồn tại
- Suggest địa chỉ gần đúng

## Files Changed

```
resources/themes/emsaigon/views/customers/account/addresses/
├── create.blade.php (updated)
│   - Contact info → read-only display
│   - Hidden fields for form submission
│   - CSS for info-display
│
└── edit.blade.php (updated)
    - Contact info → read-only display
    - Hidden fields for form submission
    - CSS for info-display
```

## Rollback

Nếu cần rollback:
```bash
cd resources/themes/emsaigon/views/customers/account/addresses/
cp create.blade.php.bak create.blade.php
cp edit.blade.php.bak edit.blade.php
```
