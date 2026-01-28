# Tài liệu mô tả chức năng Giỏ hàng và Thanh toán

## 1. Tổng quan

Tài liệu này mô tả chi tiết các chức năng liên quan đến giỏ hàng và quy trình thanh toán của website. Mục tiêu là cung cấp một trải nghiệm mua sắm trực tuyến liền mạch, an toàn và hiệu quả cho người dùng.

## 2. Các chức năng chính

### 2.1. Giỏ hàng (Shopping Cart)

- **Thêm sản phẩm vào giỏ hàng:**
    - Người dùng có thể thêm sản phẩm vào giỏ hàng từ trang danh sách sản phẩm hoặc trang chi tiết sản phẩm.
    - Khi thêm thành công, một thông báo sẽ xuất hiện và icon giỏ hàng sẽ được cập nhật số lượng.
- **Xem giỏ hàng:**
    - Người dùng có thể truy cập trang giỏ hàng để xem danh sách các sản phẩm đã thêm.
    - Thông tin hiển thị bao gồm: hình ảnh, tên sản phẩm, giá, số lượng, và tổng tiền cho từng sản phẩm.
- **Cập nhật số lượng:**
    - Người dùng có thể thay đổi số lượng của từng sản phẩm trong giỏ hàng.
    - Tổng tiền của sản phẩm và tổng tiền của đơn hàng sẽ được tự động cập nhật.
- **Xóa sản phẩm khỏi giỏ hàng:**
    - Người dùng có thể xóa một hoặc nhiều sản phẩm khỏi giỏ hàng.
- **Áp dụng mã giảm giá:**
    - Người dùng có thể nhập mã giảm giá để được hưởng ưu đãi.
    - Hệ thống sẽ kiểm tra tính hợp lệ của mã và tự động áp dụng vào tổng tiền đơn hàng.

### 2.2. Thanh toán (Checkout)

Quy trình thanh toán sẽ được chia thành hai luồng chính: dành cho người dùng đã đăng nhập và dành cho khách (chưa đăng nhập).

#### **2.2.1. Luồng dành cho Khách (Chưa đăng nhập)**

- **Thông tin giao hàng:**
    - Người dùng cần điền đầy đủ thông tin giao hàng, bao gồm: họ tên, số điện thoại, địa chỉ email, và địa chỉ nhận hàng.
    - Hệ thống có thể đề xuất tùy chọn "Tạo tài khoản" để lưu thông tin cho lần mua hàng sau.
- **Phương thức vận chuyển và thanh toán:**
    - Tương tự như người dùng đã đăng nhập.
- **Hoàn tất đơn hàng:**
    - Sau khi hoàn tất, đơn hàng được tạo với thông tin khách hàng đã cung cấp.

#### **2.2.2. Luồng dành cho Người dùng đã đăng nhập**

- **Thông tin giao hàng:**
    - Hệ thống sẽ tự động điền thông tin giao hàng mặc định đã được lưu trong tài khoản của người dùng.
    - Người dùng có thể chọn sử dụng địa chỉ mặc định hoặc nhập một địa chỉ giao hàng mới.
- **Phương thức vận chuyển:**
    - Người dùng có thể lựa chọn các phương thức vận chuyển có sẵn (ví dụ: giao hàng tiêu chuẩn, giao hàng nhanh).
    - Phí vận chuyển sẽ được tính toán và hiển thị dựa trên địa chỉ và phương thức vận chuyển được chọn.
- **Phương thức thanh toán:**
    - Người dùng có thể lựa chọn các phương thức thanh toán được hỗ trợ, bao gồm:
        - Thanh toán khi nhận hàng (COD)
        - Chuyển khoản ngân hàng
        - Thanh toán qua cổng thanh toán trực tuyến (ví dụ: VNPay, MoMo)
- **Xem lại đơn hàng:**
    - Trước khi hoàn tất, người dùng có thể xem lại toàn bộ thông tin đơn hàng, bao gồm: sản phẩm, tổng tiền, thông tin giao hàng, và phương thức thanh toán.
- **Hoàn tất đơn hàng:**
    - Sau khi xác nhận, đơn hàng sẽ được tạo và một trang xác nhận đơn hàng thành công sẽ được hiển thị.
    - Email xác nhận đơn hàng sẽ được gửi đến địa chỉ email của người dùng.
    - Lịch sử đơn hàng sẽ được lưu vào tài khoản của người dùng.

## 3. Yêu cầu phi chức năng

- **Bảo mật:**
    - Tất cả các giao dịch thanh toán trực tuyến phải được thực hiện qua kết nối an toàn (HTTPS).
    - Thông tin nhạy cảm của khách hàng (ví dụ: thông tin thẻ) không được lưu trữ trên hệ thống.
- **Hiệu suất:**
    - Thời gian tải trang giỏ hàng và thanh toán phải nhanh, ngay cả khi có nhiều sản phẩm trong giỏ hàng.
- **Tương thích:**
    - Chức năng giỏ hàng và thanh toán phải hoạt động tốt trên các trình duyệt và thiết bị phổ biến (desktop, mobile, tablet).
