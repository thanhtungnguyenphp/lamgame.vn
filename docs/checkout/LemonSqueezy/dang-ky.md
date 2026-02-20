# Hướng dẫn đăng ký Lemon Squeezy cho LamGame.vn

## Bước 1: Tạo tài khoản

1. Truy cập https://app.lemonsqueezy.com/register
2. Đăng ký bằng email
3. Xác nhận email

## Bước 2: Tạo Store

1. Vào **Settings → Store**
2. Điền thông tin:
   - **Store name**: LamGame
   - **Store URL**: lamgame (→ lamgame.lemonsqueezy.com)
   - **Store description**: Source game marketplace - Mua bán source code game
   - **Currency**: USD (bắt buộc, nhưng hiển thị VND cho khách VN)

## Bước 3: Xác minh danh tính

1. Vào **Settings → Identity Verification**
2. Upload giấy tờ:
   - CMND/CCCD hoặc Passport
   - Ảnh selfie xác minh
3. Thời gian duyệt: 1-3 ngày làm việc

## Bước 4: Thiết lập Payout

1. Vào **Settings → Payouts**
2. Chọn **Bank Transfer**
3. Điền thông tin ngân hàng VN:
   - Bank name (ví dụ: Vietcombank)
   - Account number
   - Account holder name
   - SWIFT code
4. Hoặc chọn **PayPal** nếu có tài khoản PayPal

## Bước 5: Kích hoạt Store

1. Vào **Settings → General → Activate Store**
2. Đồng ý Terms of Service
3. Store sẽ active sau khi xác minh danh tính hoàn tất

## Bước 6: Tạo API Key

1. Vào **Settings → API**
2. Click **Create API Key**
3. Đặt tên: `lamgame-production`
4. Lưu API key (chỉ hiển thị 1 lần)
5. Tạo thêm 1 key cho test mode: `lamgame-test`

## Bước 7: Thiết lập Webhook

1. Vào **Settings → Webhooks**
2. Click **Create Webhook**
3. Điền:
   - **URL**: `https://lamgame.vn/api/webhooks/lemonsqueezy`
   - **Signing secret**: Tự động tạo (lưu lại)
   - **Events**: Chọn tất cả hoặc chọn:
     - `order_created`
     - `order_refunded`
     - `subscription_created`
     - `subscription_updated`
     - `subscription_cancelled`
     - `license_key_created`

## Bước 8: Tạo Products trên Lemon Squeezy

> **Lưu ý quan trọng:** Có 2 cách tiếp cận:
> - **Cách 1 (Đơn giản):** Tạo sản phẩm trực tiếp trên dashboard Lemon Squeezy, dùng checkout overlay/hosted checkout
> - **Cách 2 (Tích hợp sâu):** Dùng API tạo checkout session từ Bagisto, sync đơn hàng qua webhook

Với LamGame.vn, nên dùng **Cách 2** để giữ trải nghiệm mua hàng trên website.

## Checklist đăng ký

- [ ] Tạo tài khoản Lemon Squeezy
- [ ] Tạo store "LamGame"
- [ ] Xác minh danh tính (CMND/CCCD)
- [ ] Thiết lập bank payout (ngân hàng VN)
- [ ] Kích hoạt store
- [ ] Tạo API key (production + test)
- [ ] Thiết lập webhook endpoint
- [ ] Test giao dịch trong test mode
- [ ] Go live
