# Tối ưu Form Địa Chỉ cho Việt Nam

## Thay đổi chính

### 1. Cấu trúc địa chỉ Việt Nam
Thay đổi từ cấu trúc quốc tế sang cấu trúc Việt Nam:

**Trước:**
- Quốc gia (dropdown tất cả quốc gia)
- Tỉnh/Thành phố (text input)
- Quận/Huyện (text input)
- Mã số thuế (optional)

**Sau:**
- Địa chỉ cụ thể (số nhà, tên đường)
- Tỉnh/Thành phố (dropdown 63 tỉnh/thành VN)
- Quận/Huyện (text input)
- Phường/Xã (optional)
- Mã bưu điện
- Quốc gia = "VN" (hidden field)

### 2. Dropdown 63 Tỉnh/Thành phố Việt Nam

**5 Thành phố trực thuộc TW (đầu tiên):**
1. TP. Hồ Chí Minh
2. Hà Nội
3. Đà Nẵng
4. Cần Thơ
5. Hải Phòng

**58 Tỉnh (theo alphabet):**
An Giang, Bà Rịa - Vũng Tàu, Bắc Giang, Bắc Kạn, Bạc Liêu, Bắc Ninh, Bến Tre, Bình Định, Bình Dương, Bình Phước, Bình Thuận, Cà Mau, Cao Bằng, Đắk Lắk, Đắk Nông, Điện Biên, Đồng Nai, Đồng Tháp, Gia Lai, Hà Giang, Hà Nam, Hà Tĩnh, Hải Dương, Hậu Giang, Hòa Bình, Hưng Yên, Khánh Hòa, Kiên Giang, Kon Tum, Lai Châu, Lâm Đồng, Lạng Sơn, Lào Cai, Long An, Nam Định, Nghệ An, Ninh Bình, Ninh Thuận, Phú Thọ, Phú Yên, Quảng Bình, Quảng Nam, Quảng Ngãi, Quảng Ninh, Quảng Trị, Sóc Trăng, Sơn La, Tây Ninh, Thái Bình, Thái Nguyên, Thanh Hóa, Thừa Thiên Huế, Tiền Giang, Trà Vinh, Tuyên Quang, Vĩnh Long, Vĩnh Phúc, Yên Bái

### 3. Cấu trúc form mới

```
┌─────────────────────────────────────┐
│ Thông tin liên hệ                   │
│ ┌──────────┬──────────┐             │
│ │ Họ       │ Tên      │             │
│ ├──────────┼──────────┤             │
│ │ Phone    │ Email    │             │
│ ├──────────┴──────────┤             │
│ │ Công ty (optional)  │             │
│ └─────────────────────┘             │
├─────────────────────────────────────┤
│ Địa chỉ chi tiết                    │
│ ┌─────────────────────┐             │
│ │ Địa chỉ cụ thể      │ (full)      │
│ │ (số nhà, tên đường) │             │
│ ├──────────┬──────────┤             │
│ │ Tỉnh/TP  │ Quận/Huyện│             │
│ │(dropdown)│ (input)  │             │
│ ├──────────┼──────────┤             │
│ │ Phường/Xã│ Mã BĐ    │             │
│ │(optional)│ (required)│             │
│ └──────────┴──────────┘             │
│ [country=VN hidden]                 │
└─────────────────────────────────────┘
```

### 4. Placeholder examples

- Địa chỉ cụ thể: "Số nhà, tên đường (VD: 123 Nguyễn Huệ)"
- Quận/Huyện: "VD: Quận 1, Huyện Củ Chi"
- Phường/Xã: "VD: Phường Bến Nghé"
- Mã bưu điện: "VD: 700000"

### 5. Xử lý address array

**Backend logic:**
```php
// Controller store/update
$data['address'] = implode(PHP_EOL, array_filter($request->input('address')));

// Kết quả lưu vào DB:
// Line 1: Địa chỉ cụ thể (số nhà, tên đường)
// Line 2: Phường/Xã (nếu có)
```

**Form edit - parse address:**
```php
// Line 1
explode(PHP_EOL, $address->address)[0] ?? ''

// Line 2 (Phường/Xã)
explode(PHP_EOL, $address->address)[1] ?? ''
```

## Files đã cập nhật

1. **create.blade.php**
   - Dropdown 63 tỉnh/thành
   - Hidden field country="VN"
   - Thêm field Phường/Xã
   - Xóa field Mã số thuế

2. **edit.blade.php**
   - Tương tự create
   - Parse address thành 2 lines
   - Sử dụng PHP array trong Blade để tạo dropdown

## Lợi ích

✅ **UX tốt hơn:**
- Dropdown tỉnh/thành → không typo
- Cấu trúc địa chỉ chuẩn VN
- Placeholder hướng dẫn rõ ràng

✅ **Data quality:**
- Tên tỉnh/thành chuẩn hóa
- Dễ tích hợp API giao hàng (GHN, GHTK, Viettel Post)
- Dễ tính phí ship theo vùng

✅ **Đơn giản hóa:**
- Không cần chọn quốc gia
- Xóa field không cần thiết (VAT ID)
- Focus vào thông tin quan trọng

## Tích hợp API giao hàng (Future)

Với cấu trúc này, dễ dàng tích hợp:

### GHN (Giao Hàng Nhanh)
```javascript
{
  "province": "Hồ Chí Minh",  // từ dropdown
  "district": "Quận 1",        // từ input
  "ward": "Phường Bến Nghé"    // từ input optional
}
```

### GHTK (Giao Hàng Tiết Kiệm)
```javascript
{
  "province": "TP. Hồ Chí Minh",
  "district": "Quận 1",
  "address": "123 Nguyễn Huệ, Phường Bến Nghé"
}
```

## Mã bưu điện Việt Nam

**Thành phố lớn:**
- Hà Nội: 100000
- TP. Hồ Chí Minh: 700000
- Đà Nẵng: 550000
- Cần Thơ: 900000
- Hải Phòng: 180000

**Validation:**
- Format: 6 chữ số
- Có thể để trống hoặc validate theo tỉnh/thành

## Testing

### Test cases:
1. ✅ Chọn tỉnh/thành từ dropdown
2. ✅ Nhập quận/huyện
3. ✅ Phường/xã optional (có thể bỏ trống)
4. ✅ Mã bưu điện required
5. ✅ Country tự động = VN
6. ✅ Edit form hiển thị đúng dữ liệu
7. ✅ Address được parse thành 2 lines

### Browser test:
- [ ] Desktop: dropdown dễ chọn
- [ ] Mobile: dropdown native picker
- [ ] Autocomplete hoạt động
- [ ] Validation messages rõ ràng

## Migration data cũ

Nếu có data địa chỉ cũ với country khác VN:

```sql
-- Check addresses không phải VN
SELECT * FROM customer_addresses WHERE country != 'VN';

-- Update tất cả về VN (nếu chắc chắn)
UPDATE customer_addresses SET country = 'VN' WHERE country IS NULL OR country != 'VN';

-- Hoặc giữ nguyên và chỉ áp dụng cho địa chỉ mới
```

## Next steps

1. **Tích hợp API tỉnh/quận/phường:**
   - Sử dụng API provinces.open-api.vn
   - Dropdown cascade: Tỉnh → Quận → Phường
   - Cache data để tăng tốc

2. **Validation nâng cao:**
   - Validate mã bưu điện theo tỉnh
   - Suggest địa chỉ từ Google Maps API
   - Validate số nhà/tên đường

3. **Tính phí ship:**
   - Tích hợp GHN/GHTK API
   - Tính phí theo tỉnh/quận
   - Hiển thị thời gian giao hàng dự kiến
