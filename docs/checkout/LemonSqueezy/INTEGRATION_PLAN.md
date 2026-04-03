# Kế hoạch tích hợp Lemon Squeezy — LamGame.vn

> **Trạng thái:** 🔧 ĐANG TRIỂN KHAI — Phase 2+3 hoàn thành (03/04/2026). Chờ đăng ký LS → Testing.
> **Ngày phân tích:** 2026-02-20
> **Bắt đầu code:** 2026-04-03
> **Ưu tiên:** P1
> **Ước tính:** 10-14 ngày (bao gồm chờ xác minh LS)
> **Task tracking:** `docs/checkout/LemonSqueezy/TASKS.md`

---

## Hiện trạng checkout

- ✅ 18/18 tasks checkout hoàn thành
- ✅ PayPal Smart Button (sandbox tested 2026-02-03)
- ✅ COD (sản phẩm vật lý), Money Transfer
- ✅ Download sau thanh toán, email xác nhận

## Tại sao cần Lemon Squeezy?

| Vấn đề hiện tại | LS giải quyết |
|------------------|---------------|
| PayPal phí cao cho VN buyer | 16+ phương thức: Visa, MC, Apple Pay, Google Pay |
| Không xử lý thuế quốc tế | MoR — tự động tính/thu/nộp VAT 100+ quốc gia |
| Không có license key | Tự động cấp license key cho source code |
| Tự xử lý refund/chargeback | LS xử lý toàn bộ |
| Không có affiliate | Affiliate program tích hợp sẵn |

## Phí: 5% + $0.50 + 1.5% (quốc tế)

| Giá SP | Phí | Thực nhận |
|--------|-----|-----------|
| $10 | $1.15 (11.5%) | $8.85 |
| $25 | $2.13 (8.5%) | $22.87 |
| $50 | $3.75 (7.5%) | $46.25 |
| $100 | $7.00 (7.0%) | $93.00 |

---

## Task List

### Phase 1: Setup & Đăng ký (1-3 ngày, không cần code)

| Task | Mô tả | Trạng thái |
|------|--------|------------|
| LS-001 | Tạo tài khoản + store "LamGame" | ⬜ |
| LS-002 | Xác minh danh tính (CCCD) — duyệt 1-3 ngày | ⬜ |
| LS-003 | Thiết lập bank payout (ngân hàng VN) | ⬜ |
| LS-004 | Tạo API key (production + test) | ⬜ |
| LS-005 | Thiết lập webhook → `https://lamgame.vn/api/webhooks/lemonsqueezy` | ⬜ |
| LS-006 | Tạo 1 "generic" variant trên LS (dùng cho custom_price) | ⬜ |

### Phase 2: Backend — Bagisto Payment Package (3-5 ngày)

| Task | Mô tả | Phức tạp | Rủi ro | Trạng thái |
|------|--------|----------|--------|------------|
| LS-101 | Tạo package `packages/LemonSqueezy/` | Thấp | Thấp | ⬜ |
| LS-102 | Payment class extend `Webkul\Payment\Payment` | TB | TB | ⬜ |
| LS-103 | `createCheckout()` — gọi LS API + **currency VND→USD** | TB | 🔴 Cao | ⬜ |
| LS-104 | `handleWebhook()` — verify signature + tạo order + **idempotency** | Cao | 🔴 Cao | ⬜ |
| LS-105 | Cấp `downloadable_link_purchased` sau webhook order_created | TB | TB | ⬜ |
| LS-106 | Config `paymentmethods.php` + `system.php` | Thấp | Thấp | ⬜ |
| LS-107 | ServiceProvider + routes + webhook CSRF exclusion | Thấp | Thấp | ⬜ |
| LS-108 | Queue job cho webhook (tránh 15s timeout) | TB | Thấp | ⬜ |

### Phase 3: Frontend — Checkout UI (2-3 ngày)

| Task | Mô tả | Rủi ro | Trạng thái |
|------|--------|--------|------------|
| LS-201 | Thêm Lemon.js vào layout master | Thấp | ⬜ |
| LS-202 | Nút "Thanh toán" trong checkout page cạnh PayPal | Thấp | ⬜ |
| LS-203 | JS: gọi API → mở overlay → listen success event | TB (popup blocker) | ⬜ |
| LS-204 | Fallback redirect sang hosted checkout nếu overlay bị block | TB | ⬜ |
| LS-205 | Success page redirect | Thấp | ⬜ |

### Phase 4: Testing & Go-live (2-3 ngày)

| Task | Mô tả | Rủi ro | Trạng thái |
|------|--------|--------|------------|
| LS-301 | Test mode card `4242 4242 4242 4242` | Thấp | ⬜ |
| LS-302 | Test webhook → tạo order Bagisto (cần ngrok cho local) | Cao | ⬜ |
| LS-303 | Test refund flow | TB | ⬜ |
| LS-304 | Test guest + logged-in checkout | Thấp | ⬜ |
| LS-305 | Test downloadable product — cấp download sau payment | Cao | ⬜ |
| LS-306 | Test mobile (overlay vs redirect) | TB | ⬜ |
| LS-307 | Switch production API key | Thấp | ⬜ |
| LS-308 | Monitor first real transaction | Thấp | ⬜ |

---

## Vấn đề cần giải quyết khi triển khai

### 🔴 Cao — Phải fix trước khi code

1. **Currency conversion VND→USD**: Code mẫu trong `tich-hop.md` dùng `$cart->grand_total * 100` nhưng grand_total là VND, LS cần USD cents. Cần service convert realtime hoặc config tỷ giá cố định.

2. **Webhook idempotency**: `handleOrderCreated()` không check order đã tồn tại. LS có thể gửi webhook trùng → duplicate order. Cần check `ls_order_id` unique.

### 🟠 Trung bình

3. **Queue cho webhook**: Xử lý sync trong 15s timeout có thể fail. Dispatch job thay vì xử lý trực tiếp.

4. **Download permission**: Sau tạo order, chưa gọi `DownloadableLinkPurchasedRepository` để cấp quyền download.

### 🟡 Thấp

5. **Generic variant**: API LS yêu cầu variant ID dù dùng custom_price. Tạo 1 variant trên dashboard.

6. **Error fallback**: Khi LS API down, hiển thị payment method khác thay vì chỉ log error.

---

## Package structure dự kiến

```
packages/LemonSqueezy/
├── composer.json
└── src/
    ├── Config/
    │   ├── paymentmethods.php
    │   └── system.php
    ├── Http/
    │   ├── Controllers/
    │   │   └── LemonSqueezyController.php
    │   └── routes.php
    ├── Jobs/
    │   └── ProcessLemonSqueezyWebhook.php
    ├── Payment/
    │   └── LemonSqueezy.php
    └── Providers/
        └── LemonSqueezyServiceProvider.php
```

## Env variables cần thêm

```env
LEMON_SQUEEZY_API_KEY=
LEMON_SQUEEZY_STORE=
LEMON_SQUEEZY_SIGNING_SECRET=
LEMON_SQUEEZY_DEFAULT_VARIANT_ID=
LEMON_SQUEEZY_USD_RATE=25000
```

---

## Tài liệu tham khảo

- `docs/checkout/LemonSqueezy/README.md` — Tổng quan + phí + so sánh
- `docs/checkout/LemonSqueezy/tich-hop.md` — Code mẫu tích hợp chi tiết
- `docs/checkout/LemonSqueezy/api-reference.md` — API endpoints + webhook payload
- `docs/checkout/LemonSqueezy/dang-ky.md` — Checklist đăng ký
- [LS API Docs](https://docs.lemonsqueezy.com/api)
- [LS Laravel SDK](https://github.com/lmsqueezy/laravel)
