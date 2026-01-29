# Task List - Chức năng Giỏ hàng và Thanh toán

## Tổng quan tiến độ

| Module | Hoàn thành | Đang làm | Chưa làm | Tổng |
|--------|------------|----------|----------|------|
| Giỏ hàng | 6 | 0 | 1 | 7 |
| Thanh toán | 9 | 0 | 2 | 11 |
| **Tổng** | **15** | **0** | **3** | **18** |

---

## Module 1: Giỏ hàng (Shopping Cart)

### CART-001: Thêm sản phẩm vào giỏ hàng
- **Trạng thái:** ✅ Hoàn thành
- **Mô tả:** Cho phép thêm sản phẩm từ trang danh sách và trang chi tiết
- **Files liên quan:**
  - `packages/Shop/src/Resources/views/products/view.blade.php`
  - `packages/Webkul/Shop/src/Http/Controllers/API/CartController.php`
- **Test cases:** TC_CART_001, TC_CART_002

### CART-002: Xem giỏ hàng
- **Trạng thái:** ✅ Hoàn thành
- **Mô tả:** Hiển thị danh sách sản phẩm với hình ảnh, tên, giá, số lượng, tổng tiền
- **Files liên quan:**
  - `resources/views/checkout/cart.blade.php`
- **Ghi chú:** Đã custom view với layout mới, sử dụng Vue.js

### CART-003: Cập nhật số lượng sản phẩm
- **Trạng thái:** ✅ Hoàn thành
- **Mô tả:** Thay đổi số lượng và tự động cập nhật tổng tiền
- **Files liên quan:**
  - `resources/views/checkout/cart.blade.php` (Vue component)
  - `packages/Webkul/Shop/src/Http/Controllers/API/CartController.php`
- **Test cases:** TC_CART_003

### CART-004: Xóa sản phẩm khỏi giỏ hàng
- **Trạng thái:** ✅ Hoàn thành
- **Mô tả:** Xóa một hoặc nhiều sản phẩm
- **Files liên quan:**
  - `resources/views/checkout/cart.blade.php`
- **Test cases:** TC_CART_004

### CART-005: Áp dụng mã giảm giá
- **Trạng thái:** ✅ Hoàn thành
- **Mô tả:** Nhập và áp dụng coupon code
- **Files liên quan:**
  - `resources/views/checkout/cart.blade.php`
- **Đã hoàn thành:**
  - [x] Thêm UI nhập mã giảm giá vào trang cart
  - [x] Kết nối API coupon (apply/remove)
  - [x] Hiển thị mã đã áp dụng và nút xóa
- **Test cases:** TC_CART_005, TC_CART_006

### CART-006: Mini Cart (Header)
- **Trạng thái:** ✅ Hoàn thành
- **Mô tả:** Hiển thị mini cart ở header với số lượng sản phẩm
- **Files liên quan:**
  - `packages/Shop/src/Resources/views/checkout/cart/mini-cart.blade.php`

### CART-007: Trang giỏ hàng trống
- **Trạng thái:** ❌ Chưa làm
- **Mô tả:** Hiển thị thông báo và link khi giỏ hàng trống
- **Công việc:**
  - [ ] Cải thiện UI trang giỏ hàng trống
  - [ ] Thêm gợi ý sản phẩm

---

## Module 2: Thanh toán (Checkout)

### CHECKOUT-001: Trang thanh toán - Layout cơ bản
- **Trạng thái:** ✅ Hoàn thành
- **Mô tả:** Layout 2 cột với form bên trái, summary bên phải
- **Files liên quan:**
  - `resources/views/checkout/onepage.blade.php`
- **Ghi chú:** Đã custom với master page đúng (LAMGAME.VN header)

### CHECKOUT-002: Guest Checkout - Form nhập thông tin
- **Trạng thái:** ✅ Hoàn thành
- **Mô tả:** Form nhập họ tên, email, phone, địa chỉ cho khách chưa đăng nhập
- **Files liên quan:**
  - `resources/views/checkout/onepage.blade.php`
- **Test cases:** TC_CHECKOUT_001, TC_CHECKOUT_002

### CHECKOUT-003: Logged In - Chọn địa chỉ đã lưu
- **Trạng thái:** ✅ Hoàn thành
- **Mô tả:** Hiển thị danh sách địa chỉ đã lưu, cho phép chọn hoặc nhập mới
- **Files liên quan:**
  - `resources/views/checkout/onepage.blade.php`
- **Test cases:** TC_CHECKOUT_004, TC_CHECKOUT_005

### CHECKOUT-004: Phương thức vận chuyển
- **Trạng thái:** ✅ Hoàn thành
- **Mô tả:** Hiển thị và chọn phương thức vận chuyển
- **Files liên quan:**
  - `resources/views/checkout/onepage.blade.php`
  - `packages/Webkul/Shop/src/Http/Controllers/API/OnepageController.php`

### CHECKOUT-005: Phương thức thanh toán
- **Trạng thái:** ✅ Hoàn thành
- **Mô tả:** Hiển thị và chọn phương thức thanh toán (Money Transfer, PayPal)
- **Files liên quan:**
  - `resources/views/checkout/onepage.blade.php`

### CHECKOUT-006: Đặt hàng (Place Order)
- **Trạng thái:** ✅ Hoàn thành
- **Mô tả:** Xử lý đặt hàng và chuyển đến trang success
- **Files liên quan:**
  - `packages/Webkul/Shop/src/Http/Controllers/API/OnepageController.php`
  - `packages/Webkul/Sales/src/Repositories/OrderRepository.php`
  - `packages/Webkul/Sales/src/Repositories/DownloadableLinkPurchasedRepository.php`
- **Đã fix:**
  - [x] Fix lỗi `manage_stock` null cho downloadable products
  - [x] Fix lỗi `customer_id` cannot be null cho guest checkout downloadable
  - [x] Migration cho phép customer_id nullable trong downloadable_link_purchased
- **Test cases:** TC_CHECKOUT_001, TC_CHECKOUT_003

### CHECKOUT-007: Trang xác nhận đơn hàng (Success Page)
- **Trạng thái:** ✅ Hoàn thành
- **Mô tả:** Hiển thị thông tin đơn hàng sau khi đặt thành công
- **Files liên quan:**
  - `packages/Webkul/Shop/src/Resources/views/checkout/success.blade.php`
- **Đã hoàn thành:**
  - [x] Tạo custom success page với master layout
  - [x] Hiển thị mã đơn hàng
  - [x] Tiếng Việt hóa
  - [x] Fix layout UX - icon nhỏ gọn, căn giữa nội dung
- **Test cases:** TC_CHECKOUT_001, TC_CHECKOUT_003

### CHECKOUT-008: Email xác nhận đơn hàng
- **Trạng thái:** ✅ Hoàn thành
- **Mô tả:** Gửi email xác nhận sau khi đặt hàng thành công
- **Files liên quan:**
  - `packages/Webkul/Shop/src/Listeners/Order.php`
  - `packages/Webkul/Shop/src/Resources/views/emails/orders/created.blade.php`
- **Đã xác nhận:**
  - [x] Email notification enabled trong config
  - [x] Email gửi cho khách hàng (Subject: "New Order Confirmation")
  - [x] Email gửi cho Admin
  - [x] Mailpit nhận được email thành công

### CHECKOUT-009: Thanh toán COD
- **Trạng thái:** ❌ Chưa làm
- **Mô tả:** Thêm phương thức thanh toán khi nhận hàng
- **Công việc:**
  - [ ] Cấu hình COD payment method
  - [ ] Test flow COD

### CHECKOUT-010: Thanh toán trực tuyến (VNPay/MoMo)
- **Trạng thái:** ❌ Chưa làm
- **Mô tả:** Tích hợp cổng thanh toán VNPay hoặc MoMo
- **Công việc:**
  - [ ] Cài đặt package payment gateway
  - [ ] Cấu hình API keys
  - [ ] Test thanh toán sandbox
- **Test cases:** TC_CHECKOUT_006

### CHECKOUT-011: Quay lại giỏ hàng từ trang thanh toán
- **Trạng thái:** ✅ Hoàn thành
- **Mô tả:** Link quay lại giỏ hàng
- **Test cases:** TC_CHECKOUT_007

---

## Công việc kế tiếp (Priority Order)

### Ưu tiên trung bình
1. **CHECKOUT-009:** Cấu hình thanh toán COD

### Ưu tiên thấp (Có thể làm sau)
2. **CART-007:** Cải thiện UI giỏ hàng trống
3. **CHECKOUT-010:** Tích hợp VNPay/MoMo

---

## Lịch sử cập nhật

| Ngày | Thay đổi |
|------|----------|
| 2026-01-29 | Fix layout UX trang Success Page - icon nhỏ gọn, căn giữa nội dung |
| 2026-01-28 | Hoàn thành CHECKOUT-008 (Email xác nhận) - đã verify qua Mailpit |
| 2026-01-28 | Hoàn thành CART-005 (Mã giảm giá) |
| 2026-01-28 | Hoàn thành CHECKOUT-006 (Place Order) và CHECKOUT-007 (Success Page) |
| 2026-01-28 | Fix guest checkout cho downloadable products (customer_id nullable) |
| 2026-01-28 | Tạo task list ban đầu, phân tích code hiện tại |
| 2026-01-27 | Fix layout cart và checkout, thêm guest checkout |
