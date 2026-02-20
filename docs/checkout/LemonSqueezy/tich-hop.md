# Tích hợp Lemon Squeezy với LamGame.vn (Bagisto)

## Kiến trúc tích hợp

```
┌─────────────────┐     ┌──────────────────┐     ┌─────────────────┐
│   LamGame.vn    │     │  Lemon Squeezy   │     │   Ngân hàng VN  │
│   (Bagisto)     │     │  (MoR + Payment) │     │                 │
│                 │     │                  │     │                 │
│  1. User click  │────▶│  2. Checkout     │     │                 │
│     "Mua ngay"  │     │     overlay/page │     │                 │
│                 │     │                  │     │                 │
│  4. Webhook     │◀────│  3. Payment OK   │     │                 │
│     order_created│     │                  │     │                 │
│                 │     │  Payout (2x/mo)  │────▶│  5. Nhận tiền   │
│  5. Cấp quyền  │     │                  │     │                 │
│     download    │     │                  │     │                 │
└─────────────────┘     └──────────────────┘     └─────────────────┘
```

## Flow thanh toán chi tiết

### Flow 1: Checkout Overlay (Đề xuất)
1. User click "Thêm vào giỏ hàng" / "Mua ngay" trên LamGame.vn
2. Backend tạo Checkout session qua Lemon Squeezy API
3. Frontend mở checkout overlay (popup) bằng Lemon.js
4. User thanh toán trên overlay (không rời website)
5. Lemon Squeezy gửi webhook `order_created` về LamGame.vn
6. Backend xử lý webhook → tạo order trong Bagisto → cấp quyền download

### Flow 2: Hosted Checkout (Backup)
1. User click "Mua ngay"
2. Redirect sang `https://lamgame.lemonsqueezy.com/checkout/...`
3. User thanh toán
4. Redirect về `https://lamgame.vn/checkout/success?order_id=...`
5. Webhook xử lý tương tự Flow 1

## Cài đặt

### 1. Cài Laravel SDK

```bash
composer require lemonsqueezy/laravel
```

### 2. Publish config

```bash
php artisan vendor:publish --tag="lemon-squeezy-config"
php artisan vendor:publish --tag="lemon-squeezy-migrations"
php artisan migrate
```

### 3. Environment variables

```env
# .env
LEMON_SQUEEZY_API_KEY=your_api_key_here
LEMON_SQUEEZY_STORE=your_store_id
LEMON_SQUEEZY_SIGNING_SECRET=your_webhook_signing_secret

# Test mode
# LEMON_SQUEEZY_API_KEY=your_test_api_key_here
```

### 4. Config file

```php
// config/lemon-squeezy.php
return [
    'api_key'        => env('LEMON_SQUEEZY_API_KEY'),
    'store'          => env('LEMON_SQUEEZY_STORE'),
    'signing_secret' => env('LEMON_SQUEEZY_SIGNING_SECRET'),
    'path'           => 'lemon-squeezy',
    'currency_locale' => 'vi_VN',
];
```

## Tích hợp với Bagisto Payment System

### 5. Tạo Payment Method Package

```
packages/
  LemonSqueezy/
    src/
      Config/
        paymentmethods.php
        system.php
      Http/
        Controllers/
          LemonSqueezyController.php
      Payment/
        LemonSqueezy.php
      Providers/
        LemonSqueezyServiceProvider.php
    composer.json
```

### 6. Payment class

```php
// packages/LemonSqueezy/src/Payment/LemonSqueezy.php
<?php

namespace LemonSqueezy\Payment;

use Webkul\Payment\Payment\Payment;

class LemonSqueezy extends Payment
{
    protected $code = 'lemonsqueezy';

    public function getRedirectUrl()
    {
        return route('lemonsqueezy.checkout.create');
    }
}
```

### 7. Payment config

```php
// packages/LemonSqueezy/src/Config/paymentmethods.php
<?php

return [
    'lemonsqueezy' => [
        'code'        => 'lemonsqueezy',
        'title'       => 'Lemon Squeezy',
        'description' => 'Thanh toán bằng Visa, Mastercard, PayPal, Apple Pay',
        'class'       => 'LemonSqueezy\Payment\LemonSqueezy',
        'active'      => true,
        'sort'        => 3,
    ],
];
```

### 8. Controller — Tạo checkout & xử lý webhook

```php
// packages/LemonSqueezy/src/Http/Controllers/LemonSqueezyController.php
<?php

namespace LemonSqueezy\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class LemonSqueezyController
{
    /**
     * Tạo checkout session và trả về URL
     */
    public function createCheckout(Request $request)
    {
        $cart = cart()->getCart();
        if (!$cart) {
            return response()->json(['error' => 'Cart is empty'], 400);
        }

        $customer = auth()->guard('customer')->user();

        // Tạo checkout qua Lemon Squeezy API
        $response = Http::withToken(config('lemon-squeezy.api_key'))
            ->post('https://api.lemonsqueezy.com/v1/checkouts', [
                'data' => [
                    'type' => 'checkouts',
                    'attributes' => [
                        'custom_price' => (int) ($cart->grand_total * 100), // cents
                        'product_options' => [
                            'name' => 'LamGame Order #' . $cart->id,
                            'description' => $cart->items->pluck('name')->implode(', '),
                            'redirect_url' => route('lemonsqueezy.checkout.success'),
                        ],
                        'checkout_options' => [
                            'embed' => true,
                            'logo' => true,
                        ],
                        'checkout_data' => [
                            'email' => $customer?->email ?? $cart->billing_address?->email,
                            'name' => $customer?->name ?? $cart->billing_address?->first_name,
                            'custom' => [
                                'cart_id' => (string) $cart->id,
                                'customer_id' => (string) ($customer?->id ?? ''),
                            ],
                        ],
                    ],
                    'relationships' => [
                        'store' => [
                            'data' => [
                                'type' => 'stores',
                                'id' => config('lemon-squeezy.store'),
                            ],
                        ],
                        'variant' => [
                            'data' => [
                                'type' => 'variants',
                                'id' => config('lemon-squeezy.default_variant_id'),
                            ],
                        ],
                    ],
                ],
            ]);

        if ($response->successful()) {
            $checkoutUrl = $response->json('data.attributes.url');
            return response()->json(['checkout_url' => $checkoutUrl]);
        }

        Log::error('LemonSqueezy checkout error', [
            'status' => $response->status(),
            'body' => $response->json(),
        ]);

        return response()->json(['error' => 'Không thể tạo checkout'], 500);
    }

    /**
     * Xử lý webhook từ Lemon Squeezy
     */
    public function handleWebhook(Request $request)
    {
        // Verify webhook signature
        $secret = config('lemon-squeezy.signing_secret');
        $signature = $request->header('X-Signature');
        $payload = $request->getContent();

        $computedSignature = hash_hmac('sha256', $payload, $secret);

        if (!hash_equals($computedSignature, $signature ?? '')) {
            Log::warning('LemonSqueezy webhook: invalid signature');
            return response('Invalid signature', 403);
        }

        $event = $request->input('meta.event_name');
        $data = $request->input('data');
        $customData = $request->input('meta.custom_data', []);

        Log::info("LemonSqueezy webhook: {$event}", ['order_id' => $data['id'] ?? null]);

        match ($event) {
            'order_created' => $this->handleOrderCreated($data, $customData),
            'order_refunded' => $this->handleOrderRefunded($data, $customData),
            default => null,
        };

        return response('OK', 200);
    }

    private function handleOrderCreated(array $data, array $customData)
    {
        $cartId = $customData['cart_id'] ?? null;
        if (!$cartId) return;

        $cart = \Webkul\Checkout\Models\Cart::find($cartId);
        if (!$cart) return;

        // Tạo order trong Bagisto
        $order = app(\Webkul\Sales\Repositories\OrderRepository::class)->create([
            'cart_id' => $cart->id,
            'is_guest' => $cart->is_guest,
            'customer_id' => $cart->customer_id,
            'channel_id' => $cart->channel_id,
            'payment_method' => 'lemonsqueezy',
            'status' => 'completed',
        ]);

        // Tạo invoice tự động
        if ($order) {
            app(\Webkul\Sales\Repositories\InvoiceRepository::class)->create([
                'order_id' => $order->id,
            ]);
        }

        Log::info("LemonSqueezy order created in Bagisto", [
            'order_id' => $order?->id,
            'ls_order_id' => $data['id'],
        ]);
    }

    private function handleOrderRefunded(array $data, array $customData)
    {
        // TODO: Xử lý refund — cập nhật order status trong Bagisto
        Log::info("LemonSqueezy order refunded", ['ls_order_id' => $data['id']]);
    }

    /**
     * Trang success sau thanh toán
     */
    public function success(Request $request)
    {
        return redirect()->route('shop.checkout.onepage.success');
    }
}
```

### 9. Routes

```php
// packages/LemonSqueezy/src/Http/routes.php
<?php

use LemonSqueezy\Http\Controllers\LemonSqueezyController;

Route::group(['middleware' => ['web']], function () {
    Route::post('lemonsqueezy/checkout', [LemonSqueezyController::class, 'createCheckout'])
        ->name('lemonsqueezy.checkout.create');

    Route::get('lemonsqueezy/success', [LemonSqueezyController::class, 'success'])
        ->name('lemonsqueezy.checkout.success');
});

// Webhook (no CSRF)
Route::post('api/webhooks/lemonsqueezy', [LemonSqueezyController::class, 'handleWebhook'])
    ->name('lemonsqueezy.webhook');
```

### 10. Frontend — Checkout Overlay

```html
<!-- Thêm Lemon.js vào layout -->
<script src="https://app.lemonsqueezy.com/js/lemon.js" defer></script>

<!-- Nút thanh toán -->
<button id="btn-lemonsqueezy" onclick="startLemonCheckout()">
    💳 Thanh toán bằng Lemon Squeezy
</button>

<script>
async function startLemonCheckout() {
    const btn = document.getElementById('btn-lemonsqueezy');
    btn.disabled = true;
    btn.textContent = 'Đang xử lý...';

    try {
        const res = await fetch('/lemonsqueezy/checkout', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
        });
        const data = await res.json();

        if (data.checkout_url) {
            // Mở checkout overlay
            window.LemonSqueezy.Url.Open(data.checkout_url);
        } else {
            alert(data.error || 'Có lỗi xảy ra');
        }
    } catch (e) {
        alert('Có lỗi xảy ra. Vui lòng thử lại.');
    } finally {
        btn.disabled = false;
        btn.textContent = '💳 Thanh toán bằng Lemon Squeezy';
    }
}

// Lắng nghe sự kiện thanh toán thành công
window.addEventListener('message', (event) => {
    if (event.data?.event === 'Checkout.Success') {
        window.location.href = '/checkout/onepage/success';
    }
});
</script>
```

## Webhook Events quan trọng

| Event | Mô tả | Xử lý |
|-------|--------|-------|
| `order_created` | Đơn hàng mới | Tạo order trong Bagisto, cấp quyền download |
| `order_refunded` | Hoàn tiền | Cập nhật order status, thu hồi quyền download |
| `subscription_created` | Subscription mới | (Tương lai) Tạo subscription |
| `subscription_cancelled` | Hủy subscription | (Tương lai) Hủy quyền truy cập |
| `license_key_created` | License key mới | Gửi license key cho user |

## Test Mode

1. Tạo API key trong test mode tại https://app.lemonsqueezy.com/settings/api
2. Dùng test API key trong `.env`
3. Tạo test products trên dashboard
4. Dùng card test: `4242 4242 4242 4242` (Visa), expiry bất kỳ trong tương lai, CVC bất kỳ
5. Webhook vẫn hoạt động bình thường trong test mode

## Lưu ý triển khai

1. **HTTPS bắt buộc** — Webhook endpoint phải là HTTPS
2. **Idempotency** — Webhook có thể gửi lại, cần check trùng order
3. **Timeout** — Webhook timeout 15 giây, xử lý nhanh hoặc dùng queue
4. **Currency** — Lemon Squeezy charge USD, cần convert giá VND → USD khi tạo checkout
5. **Product mapping** — Cần map sản phẩm Bagisto ↔ Lemon Squeezy variant ID
