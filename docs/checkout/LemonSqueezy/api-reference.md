# Lemon Squeezy API Reference — Tóm tắt cho LamGame.vn

## Authentication

Tất cả API calls cần header:
```
Authorization: Bearer {LEMON_SQUEEZY_API_KEY}
Accept: application/vnd.api+json
Content-Type: application/vnd.api+json
```

## Base URL
```
https://api.lemonsqueezy.com/v1
```

## Endpoints chính cần dùng

### 1. Checkouts — Tạo phiên thanh toán

```
POST /v1/checkouts
```

Request body:
```json
{
  "data": {
    "type": "checkouts",
    "attributes": {
      "custom_price": 1000,
      "product_options": {
        "name": "Source Game Ô Quan",
        "description": "Source code game ô ăn quan Unity",
        "redirect_url": "https://lamgame.vn/checkout/success"
      },
      "checkout_options": {
        "embed": true,
        "logo": true
      },
      "checkout_data": {
        "email": "buyer@example.com",
        "custom": {
          "cart_id": "123",
          "customer_id": "456"
        }
      }
    },
    "relationships": {
      "store": {
        "data": { "type": "stores", "id": "STORE_ID" }
      },
      "variant": {
        "data": { "type": "variants", "id": "VARIANT_ID" }
      }
    }
  }
}
```

Response: Trả về checkout URL trong `data.attributes.url`

### 2. Orders — Xem đơn hàng

```
GET /v1/orders                    # Danh sách
GET /v1/orders/{id}               # Chi tiết
POST /v1/orders/{id}/refund       # Hoàn tiền
```

### 3. Products — Quản lý sản phẩm

```
GET /v1/products                  # Danh sách
GET /v1/products/{id}             # Chi tiết
```

### 4. Customers — Quản lý khách hàng

```
GET /v1/customers                 # Danh sách
POST /v1/customers                # Tạo mới
GET /v1/customers/{id}            # Chi tiết
```

### 5. License Keys — Quản lý license

```
GET /v1/license-keys              # Danh sách
GET /v1/license-keys/{id}         # Chi tiết
PATCH /v1/license-keys/{id}       # Cập nhật (activate/deactivate)
```

### License Validation API (Public)
```
POST https://api.lemonsqueezy.com/v1/licenses/activate
POST https://api.lemonsqueezy.com/v1/licenses/deactivate
POST https://api.lemonsqueezy.com/v1/licenses/validate
```

## Webhook Payload mẫu

### order_created
```json
{
  "meta": {
    "event_name": "order_created",
    "custom_data": {
      "cart_id": "123",
      "customer_id": "456"
    }
  },
  "data": {
    "type": "orders",
    "id": "1",
    "attributes": {
      "store_id": 1,
      "customer_id": 1,
      "identifier": "abc-123",
      "order_number": 1001,
      "currency": "USD",
      "currency_rate": "1.00",
      "subtotal": 1000,
      "total": 1000,
      "tax": 0,
      "status": "paid",
      "receipt_url": "https://app.lemonsqueezy.com/my-orders/...",
      "created_at": "2026-02-11T00:00:00.000000Z",
      "updated_at": "2026-02-11T00:00:00.000000Z"
    }
  }
}
```

## Verify Webhook Signature

```php
$secret = config('lemon-squeezy.signing_secret');
$signature = $request->header('X-Signature');
$payload = $request->getContent();

$computed = hash_hmac('sha256', $payload, $secret);
$valid = hash_equals($computed, $signature);
```

## Rate Limits

- 300 requests/phút
- Headers: `X-Ratelimit-Limit`, `X-Ratelimit-Remaining`
- Vượt limit → HTTP 429

## Test Cards

| Card | Kết quả |
|------|---------|
| `4242 4242 4242 4242` | Thành công |
| `4000 0000 0000 0002` | Bị từ chối |
| `4000 0000 0000 3220` | Yêu cầu 3D Secure |

Expiry: bất kỳ ngày tương lai. CVC: bất kỳ 3 số.
