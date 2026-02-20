# Lemon Squeezy — Tài liệu cổng thanh toán cho LamGame.vn

## 1. Tổng quan

Lemon Squeezy là nền tảng thanh toán all-in-one chuyên cho sản phẩm số (digital products), được thành lập năm 2020 và **được Stripe mua lại tháng 4/2024**. Hoạt động như Merchant of Record (MoR) — tức Lemon Squeezy là bên bán hàng chính thức, chịu trách nhiệm thu thuế, xử lý gian lận, và tuân thủ pháp lý thay cho bạn.

### Tại sao phù hợp LamGame.vn?

| Yếu tố | Đánh giá |
|---------|----------|
| Sản phẩm số (source game) | ✅ Chuyên biệt cho digital products |
| Hỗ trợ VN merchant | ✅ Vietnam có trong danh sách bank payout |
| Mô hình nhỏ | ✅ $0/tháng, chỉ trả phí khi có giao dịch |
| Đăng ký | ✅ Email + xác minh danh tính, không cần DN |
| License key management | ✅ Tự động cấp license key cho source code |
| Thuế VAT | ✅ Tự động xử lý toàn cầu |

## 2. Phí & Chi phí

### Phí giao dịch
- **5% + $0.50** mỗi giao dịch
- **+1.5%** cho giao dịch quốc tế (ngoài US)
- Không phí hàng tháng, không phí setup
- Phí đã bao gồm: xử lý thanh toán, thu thuế, chống gian lận

### Ví dụ tính phí cho LamGame.vn

| Giá sản phẩm | Phí LS (5% + $0.50 + 1.5% intl) | Thực nhận |
|---------------|----------------------------------|-----------|
| $5 (source miễn phí tier) | $0.83 (16.5%) | $4.17 |
| $10 | $1.15 (11.5%) | $8.85 |
| $25 | $2.13 (8.5%) | $22.87 |
| $50 | $3.75 (7.5%) | $46.25 |
| $100 | $7.00 (7.0%) | $93.00 |

> **Lưu ý:** Phí % giảm dần khi giá sản phẩm tăng. Với sản phẩm giá thấp (<$5), phí cố định $0.50 chiếm tỷ lệ lớn. Liên hệ sales@lemonsqueezy.com để thương lượng phí volume-based nếu doanh số lớn.

### Phí payout (rút tiền)
- Bank wire: phí nhỏ tùy ngân hàng nhận
- PayPal: theo phí PayPal
- Payout tự động 2 lần/tháng (ngày 1 và 15)
- Tiền về tài khoản vào ngày 14 và 28

### So sánh phí với các cổng khác

| Cổng | Phí giao dịch | Phí tháng | MoR | Thuế tự động |
|------|---------------|-----------|-----|-------------|
| **Lemon Squeezy** | 5% + $0.50 | $0 | ✅ | ✅ |
| Stripe | 2.9% + $0.30 | $0 | ❌ | ❌ (tự xử lý) |
| Paddle | 5% + $0.50 | $0 | ✅ | ✅ |
| PayPal | 2.9% + $0.30 | $0 | ❌ | ❌ |
| Gumroad | 10% | $0 | ✅ | ✅ |

## 3. Tính năng chi tiết

### 3.1 Checkout
- **Hosted Checkout**: Trang thanh toán riêng trên domain Lemon Squeezy
- **Checkout Overlay**: Popup checkout nhúng trực tiếp vào website (dùng Lemon.js)
- **16+ phương thức thanh toán**: Visa, Mastercard, PayPal, Apple Pay, Google Pay, iDEAL, Bancontact, v.v.
- **130+ loại tiền tệ** hiển thị (xử lý nội bộ bằng USD)
- **Pre-filled fields**: Truyền sẵn email, tên khách hàng qua URL params

### 3.2 Merchant of Record (MoR)
- Lemon Squeezy là bên bán hàng chính thức trên hóa đơn
- Tự động tính và thu thuế VAT/sales tax cho 100+ quốc gia
- Tự động nộp thuế thay merchant
- Xử lý refund, chargeback, dispute
- Chống gian lận bằng AI

### 3.3 Digital Products
- Upload file trực tiếp lên Lemon Squeezy
- Download link bảo mật (signed URL, throttled)
- Quản lý phiên bản file
- License key tự động cấp sau mỗi giao dịch

### 3.4 Tính năng bổ sung
- **Discount codes**: Tạo mã giảm giá
- **Affiliate program**: Hệ thống affiliate tích hợp sẵn
- **Abandoned cart recovery**: Email tự động nhắc giỏ hàng bỏ dở
- **Email marketing**: Gửi newsletter cho subscribers (miễn phí 500 subscribers)
- **Analytics & Reporting**: Dashboard doanh thu, đơn hàng
- **Customer Portal**: Khách hàng tự quản lý đơn hàng, download

### 3.5 API & Developer
- REST API chuẩn JSON:API
- Rate limit: 300 requests/phút
- Webhook events cho mọi sự kiện (order, subscription, refund...)
- Test mode với sandbox đầy đủ
- SDK chính thức: **JavaScript** (`@lmsqueezy/lemonsqueezy.js`) và **Laravel** (`@lmsqueezy/laravel`)

## 4. Hỗ trợ Vietnam

### Merchant (bán hàng)
- ✅ Vietnam có trong danh sách **bank payout** chính thức
- Nhận tiền qua tài khoản ngân hàng VN hoặc PayPal
- Cần xác minh danh tính (CMND/CCCD/Passport)

### Customer (mua hàng)
- ✅ Vietnam không nằm trong danh sách quốc gia bị chặn
- Khách VN có thể mua bằng Visa/Mastercard/PayPal

### Tiền tệ
- Hiển thị giá bằng VND cho khách VN (tự động detect)
- Xử lý nội bộ bằng USD
- Payout về VN bằng USD → ngân hàng VN tự convert sang VND

## 5. Đánh giá ưu/nhược điểm cho LamGame.vn

### Ưu điểm
1. **Zero setup cost** — $0/tháng, chỉ trả khi có giao dịch
2. **MoR xử lý thuế** — không cần lo VAT/sales tax quốc tế
3. **Chuyên digital products** — download bảo mật, license key, file versioning
4. **Laravel SDK chính thức** — tích hợp nhanh với Bagisto (Laravel)
5. **Backed by Stripe** — ổn định, bảo mật, không lo shutdown
6. **Affiliate program** — seller có thể tạo affiliate cho sản phẩm
7. **Checkout overlay** — không redirect khỏi LamGame.vn
8. **Test mode** — sandbox đầy đủ để phát triển

### Nhược điểm
1. **Phí cao hơn Stripe** — 5% + $0.50 vs 2.9% + $0.30 (nhưng bao gồm thuế + MoR)
2. **Xử lý bằng USD** — giá VND hiển thị nhưng charge USD, có chênh lệch tỷ giá
3. **Payout 2 lần/tháng** — không rút tiền tức thì
4. **Phí cao cho sản phẩm giá thấp** — $0.50 cố định chiếm tỷ lệ lớn với sản phẩm <$5
5. **Ít tùy chỉnh checkout UI** — checkout overlay có giới hạn branding
6. **Support 24-48h** — không có live chat/phone

### Đánh giá tổng thể

| Tiêu chí | Điểm (1-5) | Ghi chú |
|----------|------------|---------|
| Phù hợp sản phẩm số | ⭐⭐⭐⭐⭐ | Thiết kế riêng cho digital products |
| Dễ đăng ký | ⭐⭐⭐⭐⭐ | Email + xác minh, không cần DN |
| Phí hợp lý | ⭐⭐⭐ | Cao hơn Stripe nhưng bao gồm MoR |
| Tích hợp Laravel | ⭐⭐⭐⭐⭐ | SDK chính thức, docs tốt |
| Hỗ trợ VN | ⭐⭐⭐⭐ | Bank payout OK, nhưng charge USD |
| Tính năng bổ sung | ⭐⭐⭐⭐⭐ | Affiliate, email, license key, analytics |
| Độ tin cậy | ⭐⭐⭐⭐⭐ | Backed by Stripe |

**Điểm tổng: 4.4/5** — Rất phù hợp cho LamGame.vn với mô hình bán source game số.

## 6. Tài liệu tham khảo

- [Lemon Squeezy Pricing](https://www.lemonsqueezy.com/pricing)
- [Supported Countries](https://docs.lemonsqueezy.com/help/getting-started/supported-countries)
- [API Reference](https://docs.lemonsqueezy.com/api)
- [Laravel SDK](https://github.com/lmsqueezy/laravel)
- [Fees Documentation](https://docs.lemonsqueezy.com/help/getting-started/fees)
- [Webhook Events](https://docs.lemonsqueezy.com/help/webhooks/event-types)

*Content was rephrased for compliance with licensing restrictions.*
