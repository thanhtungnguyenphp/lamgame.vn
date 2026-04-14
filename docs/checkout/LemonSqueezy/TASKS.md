# Lemon Squeezy Integration — Task Tracking

**Bắt đầu:** 2026-04-03
**Ước tính:** 10-14 ngày (bao gồm chờ xác minh LS 1-3 ngày)

---

## Tiến độ tổng quan

| Phase | Trạng thái | Ngày |
|-------|-----------|------|
| Phase 1 — Setup & Đăng ký LS | ✅ DONE | 2026-04-03 |
| Phase 2 — Backend Package | ✅ DONE | 2026-04-03 |
| Phase 3 — Frontend Checkout UI | ✅ DONE | 2026-04-03 |
| Phase 4 — Testing & Go-live | 🔧 IN PROGRESS | 2026-04-03 |

---

## Phase 1: Setup & Đăng ký (1-3 ngày, không cần code)

| Task | Mô tả | Trạng thái |
|------|--------|------------|
| LS-001 | Tạo tài khoản + store "LamGame" trên lemonsqueezy.com | ✅ Done |
| LS-002 | Xác minh danh tính (CCCD) — duyệt 1-3 ngày | ✅ Done |
| LS-003 | Thiết lập bank payout (ngân hàng VN) | ✅ Done |
| LS-004 | Tạo API key (production + test) | ✅ Done |
| LS-005 | Thiết lập webhook → `https://lamgame.vn/api/webhooks/lemonsqueezy` | ✅ Done |
| LS-006 | Tạo 1 "generic" variant trên LS (dùng cho custom_price) | ✅ Done (ID: 1483177) |

---

## Phase 2: Backend Package ✅ DONE (2026-04-03)

| Task | Mô tả | Trạng thái |
|------|--------|------------|
| LS-101 | Tạo package `packages/LemonSqueezy/` | ✅ |
| LS-102 | Payment class extend `Webkul\Payment\Payment` | ✅ |
| LS-103 | `createCheckout()` — gọi LS API + currency VND→USD | ✅ |
| LS-104 | `handleWebhook()` — verify signature + tạo order + idempotency | ✅ |
| LS-105 | Auto-generate invoice sau webhook order_created | ✅ |
| LS-106 | Config `paymentmethods.php` + `system.php` | ✅ |
| LS-107 | ServiceProvider + routes + webhook CSRF exclusion | ✅ |
| LS-108 | Migration `lemon_squeezy_transactions` (idempotency tracking) | ✅ |
| LS-109 | Model `LemonSqueezyTransaction` | ✅ |
| LS-110 | Đăng ký provider + autoload trong composer.json | ✅ |
| LS-111 | Env variables (.env + .env.example) | ✅ |
| LS-112 | Refund handling (order_refunded webhook) | ✅ |

---

## Phase 3: Frontend Checkout UI ✅ DONE (2026-04-03)

| Task | Mô tả | Trạng thái |
|------|--------|------------|
| LS-201 | Lemon.js `<script>` inject qua EventServiceProvider | ✅ |
| LS-202 | Vue component `v-lemon-squeezy-button` (giống v-paypal-smart-button) | ✅ |
| LS-203 | JS: gọi API → mở overlay (LemonSqueezy.Url.Open) | ✅ |
| LS-204 | Fallback redirect nếu overlay bị block | ✅ |
| LS-205 | Listen `Checkout.Success` message → redirect success page | ✅ |
| LS-206 | Tích hợp vào checkout index.blade.php (`v-else-if="cart.payment_method == 'lemonsqueezy'"`) | ✅ |
| LS-207 | EventServiceProvider inject blade qua `bagisto.shop.layout.body.after` | ✅ |

### Files Phase 3

| File | Loại | Mô tả |
|------|------|-------|
| `packages/LemonSqueezy/src/Resources/views/checkout/onepage/lemon-squeezy-button.blade.php` | New | Vue component + Lemon.js loader |
| `packages/LemonSqueezy/src/Providers/EventServiceProvider.php` | New | Inject blade template vào layout |
| `packages/LemonSqueezy/src/Providers/LemonSqueezyServiceProvider.php` | Modified | Register EventServiceProvider |
| `packages/Webkul/Shop/src/Resources/views/checkout/onepage/index.blade.php` | Modified | Thêm `v-else-if` cho lemonsqueezy |

---

## Phase 4: Testing & Go-live (2-3 ngày) — 🔧 IN PROGRESS

### Bugs phát hiện & fix (03/04/2026 tối)

| # | Bug | Fix |
|---|-----|-----|
| 1 | Store currency = VND, `custom_price` phải là VND cents (không phải USD) | Bỏ convert VND→USD, dùng `$cart->grand_total * 100` |
| 2 | `Cart::prepareDataForOrder()` không tồn tại trong Bagisto | Đổi thành `(new OrderResource($cart))->jsonSerialize()` |
| 3 | `invoiceRepository->create()` truyền thừa params | Bỏ `'paid', 'completed'` |

### Test results (03/04/2026 tối)

| Test | Kết quả |
|------|---------|
| API key → LS Store | ✅ 200 OK |
| Tạo checkout URL (VND cents) | ✅ 201 Created |
| Migration lemon_squeezy_transactions | ✅ |
| Webhook signature verify | ✅ |
| Webhook routing + CSRF exclusion | ✅ |
| Idempotency (duplicate skip) | ✅ |
| Cart not found → log warning | ✅ |
| Order creation (full cart) | ⬜ Cần test browser |

### Tasks

| Task | Mô tả | Trạng thái |
|------|--------|------------|
| LS-301 | Test mode card `4242 4242 4242 4242` | ✅ Done 14/04 |
| LS-302 | Test webhook → tạo order Bagisto (ngrok cho local) | ✅ Done 14/04 — Order #9 |
| LS-303 | Test refund flow | ⬜ |
| LS-304 | Test guest + logged-in checkout | ✅ Guest overlay OK |
| LS-305 | Test downloadable product — cấp download sau payment | ⬜ |
| LS-306 | Test mobile (overlay vs redirect) | ⬜ |
| LS-307 | Chạy migration production | ✅ Done (deployed) |
| LS-308 | Switch production API key | ⬜ |
| LS-309 | Monitor first real transaction | ⬜ |

### Bugs fixed Phase 4

| # | Bug | Fix | Ngày |
|---|-----|-----|------|
| 1 | Store currency = VND, `custom_price` phải là VND cents | Bỏ convert VND→USD, dùng `$cart->grand_total * 100` | 03/04 |
| 2 | `Cart::prepareDataForOrder()` không tồn tại | Đổi thành `(new OrderResource($cart))->jsonSerialize()` | 03/04 |
| 3 | `invoiceRepository->create()` truyền thừa params | Bỏ `'paid', 'completed'` | 03/04 |
| 4 | Cart thiếu billing/shipping address → `address_type on null` | Auto-tạo `CartAddress` trong webhook handler | 14/04 |
| 5 | `Cart::addresses()` không tồn tại | Dùng `CartAddress::create()` trực tiếp | 14/04 |

---

## Tổng hợp files đã tạo/sửa

### Package mới: `packages/LemonSqueezy/`

```
packages/LemonSqueezy/
├── composer.json
└── src/
    ├── Config/
    │   ├── paymentmethods.php
    │   └── system.php
    ├── Database/Migrations/
    │   └── 2026_04_03_000001_create_lemon_squeezy_transactions_table.php
    ├── Http/
    │   ├── Controllers/
    │   │   └── LemonSqueezyController.php
    │   └── routes.php
    ├── Models/
    │   └── LemonSqueezyTransaction.php
    ├── Payment/
    │   └── LemonSqueezy.php
    ├── Providers/
    │   ├── EventServiceProvider.php
    │   └── LemonSqueezyServiceProvider.php
    └── Resources/
        ├── lang/{vi,en}/app.php
        ├── manifest.php
        └── views/checkout/onepage/lemon-squeezy-button.blade.php
```

### Files sửa trong project chính

| File | Mô tả |
|------|-------|
| `composer.json` | Thêm autoload `LemonSqueezy\\` |
| `bootstrap/providers.php` | Đăng ký `LemonSqueezyServiceProvider` |
| `app/Http/Middleware/VerifyCsrfToken.php` | CSRF exclusion webhook |
| `.env` + `.env.example` | 5 env vars |
| `packages/Webkul/Shop/.../checkout/onepage/index.blade.php` | Thêm LS button template |

---

## Kiến trúc flow

```
[Checkout Page]
    │
    ├── User chọn payment method "Lemon Squeezy"
    │   └── v-payment-methods component → store('lemonsqueezy')
    │
    ├── canPlaceOrder = true
    │   └── cart.payment_method == 'lemonsqueezy'
    │       └── Render <v-lemon-squeezy-button>
    │
    ├── User click "Thanh toán quốc tế"
    │   └── POST /lemonsqueezy/checkout
    │       ├── Convert VND→USD (÷ LEMON_SQUEEZY_USD_RATE)
    │       ├── Call LS API /v1/checkouts
    │       └── Return checkout_url
    │
    ├── Frontend mở overlay
    │   └── LemonSqueezy.Url.Open(checkout_url)
    │   └── Fallback: window.location.href = checkout_url
    │
    ├── User thanh toán trên LS overlay
    │   └── LS gửi webhook POST /api/webhooks/lemonsqueezy
    │       ├── Verify HMAC signature
    │       ├── Check idempotency (lemon_squeezy_transactions)
    │       ├── Create Bagisto order
    │       ├── Auto-create invoice (paid/completed)
    │       └── Deactivate cart
    │
    └── Frontend listen Checkout.Success message
        └── Redirect → /checkout/onepage/success
```

## Env Variables

```env
LEMON_SQUEEZY_API_KEY=          # API key từ LS dashboard
LEMON_SQUEEZY_STORE=            # Store ID
LEMON_SQUEEZY_SIGNING_SECRET=   # Webhook signing secret
LEMON_SQUEEZY_DEFAULT_VARIANT_ID= # Generic variant ID
LEMON_SQUEEZY_USD_RATE=25000    # Tỷ giá VND/USD
```
