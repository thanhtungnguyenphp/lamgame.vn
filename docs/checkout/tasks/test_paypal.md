# Test Cases - Thanh toán PayPal

## Cập nhật: 2026-02-02

---

## Cấu hình cần kiểm tra trước khi test

### Admin Panel: Configuration > Sales > Payment Methods

| Config | Giá trị cần có |
|--------|----------------|
| PayPal Smart Button - Active | Yes |
| PayPal Smart Button - Sandbox | Yes (test) / No (production) |
| PayPal Smart Button - Client ID | Đã nhập |
| PayPal Smart Button - Client Secret | Đã nhập |
| PayPal Smart Button - Accepted Currencies | USD (hoặc currency phù hợp) |

---

## Test Cases

### TC_PAYPAL_001: Hiển thị PayPal Smart Button trên trang checkout
- **Mục tiêu:** Kiểm tra nút PayPal hiển thị đúng
- **Điều kiện:**
  - Có sản phẩm trong giỏ hàng
  - PayPal Smart Button đã active trong admin
  - Client ID đã cấu hình
- **Bước thực hiện:**
  1. Thêm sản phẩm vào giỏ hàng
  2. Vào trang checkout `/checkout/onepage`
  3. Điền thông tin địa chỉ
  4. Chọn phương thức thanh toán
- **Kết quả mong đợi:**
  - [ ] Hiển thị option "PayPal Smart Button" trong danh sách payment methods
  - [ ] Khi chọn PayPal, hiển thị nút PayPal (màu vàng)
- **Trạng thái:** ⬜ Chưa test

---

### TC_PAYPAL_002: Thanh toán PayPal thành công (Sandbox)
- **Mục tiêu:** Kiểm tra flow thanh toán hoàn chỉnh
- **Điều kiện:**
  - PayPal Sandbox mode = Yes
  - Có tài khoản PayPal Sandbox buyer
- **Bước thực hiện:**
  1. Thêm sản phẩm vào giỏ hàng
  2. Vào checkout, điền thông tin
  3. Chọn PayPal Smart Button
  4. Click nút PayPal
  5. Đăng nhập tài khoản PayPal Sandbox buyer
  6. Xác nhận thanh toán
- **Kết quả mong đợi:**
  - [ ] Popup PayPal mở ra
  - [ ] Hiển thị đúng số tiền và sản phẩm
  - [ ] Sau khi thanh toán, chuyển về trang Success
  - [ ] Đơn hàng được tạo với status "Processing"
  - [ ] Invoice tự động tạo
  - [ ] Email xác nhận được gửi
- **Trạng thái:** ⬜ Chưa test

---

### TC_PAYPAL_003: Hủy thanh toán PayPal
- **Mục tiêu:** Kiểm tra xử lý khi user hủy thanh toán
- **Bước thực hiện:**
  1. Thực hiện bước 1-4 của TC_PAYPAL_002
  2. Trong popup PayPal, click "Cancel" hoặc đóng popup
- **Kết quả mong đợi:**
  - [ ] Quay lại trang checkout
  - [ ] Giỏ hàng vẫn còn nguyên
  - [ ] Không tạo đơn hàng
- **Trạng thái:** ⬜ Chưa test

---

### TC_PAYPAL_004: Thanh toán với sản phẩm downloadable
- **Mục tiêu:** Kiểm tra PayPal với digital goods
- **Điều kiện:**
  - Sản phẩm downloadable (source game)
- **Bước thực hiện:**
  1. Thêm sản phẩm downloadable vào giỏ
  2. Checkout với PayPal
- **Kết quả mong đợi:**
  - [ ] Không yêu cầu shipping address
  - [ ] PayPal hiển thị category = "DIGITAL_GOODS"
  - [ ] Thanh toán thành công
  - [ ] Download links được tạo sau khi thanh toán
- **Trạng thái:** ⬜ Chưa test

---

### TC_PAYPAL_005: Thanh toán với mã giảm giá
- **Mục tiêu:** Kiểm tra số tiền đúng khi có coupon
- **Bước thực hiện:**
  1. Thêm sản phẩm vào giỏ
  2. Áp dụng mã giảm giá
  3. Checkout với PayPal
- **Kết quả mong đợi:**
  - [ ] Số tiền trên PayPal = Tổng tiền - Giảm giá
  - [ ] Breakdown hiển thị đúng discount
- **Trạng thái:** ⬜ Chưa test

---

### TC_PAYPAL_006: Guest checkout với PayPal
- **Mục tiêu:** Kiểm tra khách chưa đăng nhập thanh toán PayPal
- **Bước thực hiện:**
  1. Không đăng nhập
  2. Thêm sản phẩm, checkout
  3. Điền thông tin guest
  4. Thanh toán PayPal
- **Kết quả mong đợi:**
  - [ ] Thanh toán thành công
  - [ ] Đơn hàng tạo với customer_id = null
  - [ ] Email gửi đến địa chỉ guest nhập
- **Trạng thái:** ⬜ Chưa test

---

### TC_PAYPAL_007: Kiểm tra currency
- **Mục tiêu:** Đảm bảo currency gửi đến PayPal đúng
- **Điều kiện:**
  - Accepted Currencies trong config = USD
  - Store currency = USD
- **Kết quả mong đợi:**
  - [ ] PayPal nhận đúng currency code
  - [ ] Không có lỗi currency mismatch
- **Trạng thái:** ⬜ Chưa test

---

## Tài khoản PayPal Sandbox Test

### Buyer Account (dùng để test thanh toán)
- Email: `<sandbox_buyer_email>`
- Password: `<sandbox_buyer_password>`

### Seller Account (nhận tiền)
- Email: `<sandbox_seller_email>`

> Tạo tài khoản sandbox tại: https://developer.paypal.com/dashboard/accounts

---

## API Endpoints liên quan

| Endpoint | Method | Mô tả |
|----------|--------|-------|
| `/paypal/smart-button/create-order` | GET | Tạo PayPal order |
| `/paypal/smart-button/capture-order` | POST | Capture sau khi approve |

---

## Checklist trước khi go-live

- [ ] Chuyển Sandbox = No
- [ ] Cập nhật Client ID production
- [ ] Cập nhật Client Secret production
- [ ] Test 1 giao dịch thật với số tiền nhỏ
- [ ] Kiểm tra webhook IPN (nếu dùng PayPal Standard)

---

## Lịch sử test

| Ngày | Tester | Test Case | Kết quả | Ghi chú |
|------|--------|-----------|---------|---------|
| 2026-02-02 | Dev | TC_PAYPAL_001 | ✅ Pass | Nút PayPal hiển thị đúng |
| 2026-02-02 | Dev | TC_PAYPAL_002 | ✅ Pass | Thanh toán Visa qua PayPal thành công |
