# AI Subscription — Hướng dẫn Deploy Production

**Ngày tạo:** 2026-04-13
**Sandbox test:** PASSED ✅ (subscribe, quota, cancel — tất cả OK)

---

## Tổng quan

Hệ thống AI Subscription cho phép user mua gói (Free/Pro $9/Business $29) qua PayPal Billing.
Sandbox đã test thành công. Tài liệu này hướng dẫn deploy lên production server.

---

## Điều kiện tiên quyết

- [x] Code đã merge vào main
- [x] Migration files có sẵn: `2026_03_22_000001`, `2026_03_22_000002`
- [x] Sandbox test passed (13/04/2026)
- [ ] PayPal Business account đã verified (live)
- [ ] PayPal Live API credentials

---

## Bước 1: Tạo PayPal Live App & Plans (~15 phút)

### 1.1 Tạo Live App

1. Vào https://developer.paypal.com/dashboard/applications/live
2. Click **"Create App"**
3. Tên: `LamGame Production`
4. Chọn **Merchant** type
5. Sau khi tạo, copy:
   - **Client ID**: `<LIVE_CLIENT_ID>`
   - **Secret**: `<LIVE_SECRET>`

### 1.2 Tạo Billing Plans trên Live

Dùng API (thay `<LIVE_TOKEN>` bằng token lấy từ bước 1.1):

```bash
# Lấy token
LIVE_TOKEN=$(curl -s -X POST https://api-m.paypal.com/v1/oauth2/token \
  -u "<LIVE_CLIENT_ID>:<LIVE_SECRET>" \
  -d "grant_type=client_credentials" | python3 -c "import sys,json; print(json.load(sys.stdin)['access_token'])")

# Tạo Product
PRODUCT_ID=$(curl -s -X POST https://api-m.paypal.com/v1/catalogs/products \
  -H "Authorization: Bearer $LIVE_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "LamGame AI Tools",
    "type": "SERVICE",
    "description": "AI tools subscription for game developers",
    "category": "SOFTWARE"
  }' | python3 -c "import sys,json; print(json.load(sys.stdin)['id'])")

echo "Product ID: $PRODUCT_ID"

# Tạo Plan Pro ($9/tháng)
PRO_PLAN=$(curl -s -X POST https://api-m.paypal.com/v1/billing/plans \
  -H "Authorization: Bearer $LIVE_TOKEN" \
  -H "Content-Type: application/json" \
  -d "{
    \"product_id\": \"$PRODUCT_ID\",
    \"name\": \"LamGame Pro\",
    \"billing_cycles\": [{
      \"frequency\": {\"interval_unit\": \"MONTH\", \"interval_count\": 1},
      \"tenure_type\": \"REGULAR\",
      \"sequence\": 1,
      \"total_cycles\": 0,
      \"pricing_scheme\": {\"fixed_price\": {\"value\": \"9\", \"currency_code\": \"USD\"}}
    }],
    \"payment_preferences\": {
      \"auto_bill_outstanding\": true,
      \"payment_failure_threshold\": 3
    }
  }" | python3 -c "import sys,json; print(json.load(sys.stdin)['id'])")

echo "Pro Plan ID: $PRO_PLAN"

# Tạo Plan Business ($29/tháng)
BIZ_PLAN=$(curl -s -X POST https://api-m.paypal.com/v1/billing/plans \
  -H "Authorization: Bearer $LIVE_TOKEN" \
  -H "Content-Type: application/json" \
  -d "{
    \"product_id\": \"$PRODUCT_ID\",
    \"name\": \"LamGame Business\",
    \"billing_cycles\": [{
      \"frequency\": {\"interval_unit\": \"MONTH\", \"interval_count\": 1},
      \"tenure_type\": \"REGULAR\",
      \"sequence\": 1,
      \"total_cycles\": 0,
      \"pricing_scheme\": {\"fixed_price\": {\"value\": \"29\", \"currency_code\": \"USD\"}}
    }],
    \"payment_preferences\": {
      \"auto_bill_outstanding\": true,
      \"payment_failure_threshold\": 3
    }
  }" | python3 -c "import sys,json; print(json.load(sys.stdin)['id'])")

echo "Business Plan ID: $BIZ_PLAN"
```

**Ghi lại 2 Plan IDs** (dạng `P-xxxxxxx`).

### 1.3 Tạo Webhook trên Live

1. Vào https://developer.paypal.com/dashboard/webhooks (chọn Live app)
2. Click **"Add Webhook"**
3. URL: `https://lamgame.vn/api/v1/subscription/webhook`
4. Chọn events:
   - `BILLING.SUBSCRIPTION.ACTIVATED`
   - `BILLING.SUBSCRIPTION.CANCELLED`
   - `BILLING.SUBSCRIPTION.SUSPENDED`
   - `BILLING.SUBSCRIPTION.EXPIRED`
   - `PAYMENT.SALE.COMPLETED`
5. Save → copy **Webhook ID** (dạng `WH-xxxxxxx`)

---

## Bước 2: Deploy code lên server (~10 phút)

```bash
# SSH vào server
ssh user@lamgame.vn

# Pull code mới nhất
cd /data/www/lamgame.vn
git pull origin main
```

---

## Bước 3: Sửa .env production (~5 phút)

```bash
nano .env
```

Thêm/sửa các dòng sau:

```env
# === AI Subscription — PayPal Live ===
PAYPAL_SUBSCRIPTION_CLIENT_ID=<LIVE_CLIENT_ID>
PAYPAL_SUBSCRIPTION_CLIENT_SECRET=<LIVE_SECRET>
PAYPAL_SUBSCRIPTION_SANDBOX=false
PAYPAL_WEBHOOK_ID=<LIVE_WEBHOOK_ID>
```

> ⚠️ `PAYPAL_SUBSCRIPTION_SANDBOX=false` → chuyển từ sandbox sang live API (`api-m.paypal.com`)

---

## Bước 4: Chạy migration (~1 phút)

```bash
docker exec lg-php php artisan migrate --force
```

Kết quả mong đợi:
```
Migrating: 2026_03_22_000001_create_subscription_tables
Migrated:  2026_03_22_000001_create_subscription_tables
Migrating: 2026_03_22_000002_seed_subscription_plans
Migrated:  2026_03_22_000002_seed_subscription_plans
```

Tạo 4 bảng: `subscription_plans`, `user_subscriptions`, `subscription_transactions`, `subscription_usages`
Seed 3 gói: Free, Pro ($9), Business ($29)

---

## Bước 5: Cập nhật PayPal Plan IDs trong DB (~2 phút)

```bash
docker exec -it lg-mysql mysql -uroot -proot lamgame
```

```sql
UPDATE subscription_plans
SET paypal_plan_id = '<LIVE_PRO_PLAN_ID>'
WHERE slug = 'pro';

UPDATE subscription_plans
SET paypal_plan_id = '<LIVE_BIZ_PLAN_ID>'
WHERE slug = 'business';

-- Verify
SELECT id, slug, name, price, paypal_plan_id FROM subscription_plans;
```

Kết quả mong đợi:
```
+----+----------+----------+-------+----------------------------+
| id | slug     | name     | price | paypal_plan_id             |
+----+----------+----------+-------+----------------------------+
|  1 | free     | Free     |  0.00 | NULL                       |
|  2 | pro      | Pro      |  9.00 | P-xxxxxxxxxxxxxxxxxxxxx    |
|  3 | business | Business | 29.00 | P-xxxxxxxxxxxxxxxxxxxxx    |
+----+----------+----------+-------+----------------------------+
```

---

## Bước 6: Clear cache (~1 phút)

```bash
docker exec lg-php php artisan config:clear
docker exec lg-php php artisan route:clear
docker exec lg-php php artisan cache:clear
```

---

## Bước 7: Verify (~5 phút)

### 7.1 API Plans

```bash
curl https://lamgame.vn/api/v1/subscription/plans | python3 -m json.tool
```

Phải trả về 3 gói với đúng giá và features.

### 7.2 PayPal OAuth

```bash
docker exec lg-php php artisan tinker --execute="
\$clientId = config('subscription.paypal.client_id');
\$secret = config('subscription.paypal.client_secret');
\$baseUrl = config('subscription.paypal.base_url');
echo 'Base URL: ' . \$baseUrl . PHP_EOL;
\$r = \Illuminate\Support\Facades\Http::asForm()
    ->withBasicAuth(\$clientId, \$secret)
    ->post(\$baseUrl . '/v1/oauth2/token', ['grant_type' => 'client_credentials']);
echo 'OAuth: ' . (\$r->successful() ? 'OK' : 'FAILED: ' . \$r->body()) . PHP_EOL;
"
```

Mong đợi:
```
Base URL: https://api-m.paypal.com     ← (KHÔNG phải sandbox)
OAuth: OK
```

### 7.3 Trang /ai-tools

Mở browser: `https://lamgame.vn/ai-tools` → kiểm tra hiển thị 3 gói + nút Subscribe.

### 7.4 Test transaction thật (tùy chọn)

Dùng tài khoản thật, subscribe gói Pro $9 → verify:
- PayPal redirect OK
- Subscription activated trong DB
- Quota hoạt động

---

## Troubleshooting

| Vấn đề | Giải pháp |
|--------|-----------|
| `PAYPAL_ERROR` khi subscribe | Kiểm tra `PAYPAL_SUBSCRIPTION_CLIENT_ID` và `SECRET` trong `.env` |
| OAuth failed | Verify credentials trên https://developer.paypal.com/dashboard/applications/live |
| Webhook không nhận | Kiểm tra URL webhook trên PayPal dashboard, verify SSL cert |
| `Base URL` vẫn là sandbox | Kiểm tra `PAYPAL_SUBSCRIPTION_SANDBOX=false` trong `.env`, chạy `config:clear` |
| Migration đã chạy rồi | Bình thường — Laravel skip migration đã chạy |
| `paypal_plan_id` NULL | Chạy lại SQL ở Bước 5 |

---

## Kiến trúc tham khảo

```
User click Subscribe Pro
    → POST /api/v1/subscription/subscribe {plan: "pro"}
    → SubscriptionService::createPaypalSubscription()
    → PayPal API tạo subscription → return approval_url
    → User approve trên PayPal
    → PayPal redirect → /api/v1/subscription/paypal/return?subscription_id=I-xxx
    → SubscriptionService::activateSubscription()
    → DB: user_subscriptions.status = active, ends_at = +1 month
    → PayPal webhook BILLING.SUBSCRIPTION.ACTIVATED (backup)

AI Service gọi quota:
    → POST /api/v1/subscription/check-quota {feature: "ai_concept"}
    → Return: {allowed: true, limit: 50, used: 3, remaining: 47}
    → POST /api/v1/subscription/use-quota {feature: "ai_concept"}
    → Return: {success: true, remaining: 46}
```

## API Endpoints

| Method | Endpoint | Auth | Mô tả |
|--------|----------|------|-------|
| GET | `/api/v1/subscription/plans` | Public | Danh sách gói |
| POST | `/api/v1/subscription/subscribe` | Customer | Đăng ký gói |
| GET | `/api/v1/subscription/status` | Customer | Trạng thái hiện tại |
| GET | `/api/v1/subscription/usage` | Customer | Quota đã dùng |
| POST | `/api/v1/subscription/check-quota` | Customer | Kiểm tra 1 feature |
| POST | `/api/v1/subscription/use-quota` | Customer | Trừ quota 1 feature |
| POST | `/api/v1/subscription/cancel` | Customer | Hủy subscription |
| GET | `/api/v1/subscription/paypal/return` | Public | PayPal callback |
| POST | `/api/v1/subscription/webhook` | Public | PayPal webhook |

## Database

| Bảng | Mô tả |
|------|-------|
| `subscription_plans` | 3 gói (free, pro, business) |
| `user_subscriptions` | user ↔ plan, PayPal sub ID, status |
| `subscription_transactions` | Lịch sử thanh toán |
| `subscription_usages` | Quota tracking per user/feature/month |

---

## Sandbox Test Results (13/04/2026)

| Test | Kết quả |
|------|---------|
| PayPal OAuth token | ✅ 200 OK |
| PayPal Plans (Pro + Business) | ✅ ACTIVE |
| API `/plans` | ✅ 3 gói đúng |
| Subscribe Free → active | ✅ |
| Quota Free (ai_concept limit=3) → chặn lần 4 | ✅ |
| Subscribe Pro → PayPal approval_url | ✅ |
| Buyer approve trên PayPal sandbox | ✅ |
| PayPal return → activate DB | ✅ |
| Quota Pro (ai_concept=50, ai_generate=unlimited) | ✅ |
| Boolean features (statistics=true, freelancer_contact=false) | ✅ |
| Cancel → PayPal CANCELLED + DB cancelled | ✅ |

### Bugs đã fix:
1. `SubscriptionUsage::increment()` conflict với `Model::increment()` → đổi thành `incrementUsage()`
2. `auth:sanctum` guard trỏ tới `admins` → thêm `sanctum-customer` guard trong `config/auth.php`
3. `$customer` undefined trong view `ai-subscription.blade.php` → truyền từ controller
4. Duplicate `aiSubscribe()` method trong `LamGamePageController` → xóa bản thừa
5. Route `lamgame.ai-subscribe` thiếu → thêm `POST ai-tools/subscribe` vào `web.php`
