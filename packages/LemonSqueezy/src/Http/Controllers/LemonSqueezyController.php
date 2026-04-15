<?php

namespace LemonSqueezy\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use LemonSqueezy\Models\LemonSqueezyTransaction;
use Webkul\Checkout\Facades\Cart;
use Webkul\Sales\Transformers\OrderResource;
use Webkul\Sales\Repositories\InvoiceRepository;
use Webkul\Sales\Repositories\OrderRepository;

class LemonSqueezyController
{
    public function __construct(
        protected OrderRepository $orderRepository,
        protected InvoiceRepository $invoiceRepository,
    ) {}

    /**
     * Tạo checkout session trên Lemon Squeezy.
     */
    public function createCheckout(Request $request)
    {
        $cart = Cart::getCart();

        if (! $cart) {
            return response()->json(['error' => 'Cart is empty'], 400);
        }

        $customer = auth()->guard('customer')->user();

        // Store currency = VND → custom_price tính bằng VND cents (đơn vị nhỏ nhất)
        // VND không có phần thập phân nên 1 VND = 100 cents trong LS
        $priceVndCents = max(1317200, (int) ($cart->grand_total * 100));

        $apiKey = core()->getConfigData('sales.payment_methods.lemonsqueezy.api_key') ?: env('LEMON_SQUEEZY_API_KEY');
        $storeId = core()->getConfigData('sales.payment_methods.lemonsqueezy.store_id') ?: env('LEMON_SQUEEZY_STORE');
        $variantId = core()->getConfigData('sales.payment_methods.lemonsqueezy.default_variant_id') ?: env('LEMON_SQUEEZY_DEFAULT_VARIANT_ID');

        if (! $apiKey || ! $storeId || ! $variantId) {
            Log::error('LemonSqueezy: missing config');
            return response()->json(['error' => 'Payment gateway not configured'], 500);
        }

        $productName = mb_substr($cart->items->pluck('name')->implode(', '), 0, 200);

        // LS-306: Detect mobile → disable embed for redirect-based checkout
        $agent = $request->header('User-Agent', '');
        $isMobile = (bool) preg_match('/Android|iPhone|iPad|iPod|Opera Mini|IEMobile/i', $agent);

        $response = Http::withToken($apiKey)
            ->accept('application/vnd.api+json')
            ->contentType('application/vnd.api+json')
            ->timeout(15)
            ->post('https://api.lemonsqueezy.com/v1/checkouts', [
                'data' => [
                    'type'       => 'checkouts',
                    'attributes' => [
                        'custom_price'    => $priceVndCents,
                        'product_options' => [
                            'name'         => 'LamGame Order #' . $cart->id,
                            'description'  => $productName,
                            'redirect_url' => route('lemonsqueezy.checkout.success'),
                        ],
                        'checkout_options' => [
                            'embed' => ! $isMobile,
                            'logo'  => true,
                        ],
                        'checkout_data' => [
                            'email'  => $customer?->email ?? $cart->billing_address?->email,
                            'name'   => $customer?->name ?? $cart->billing_address?->first_name,
                            'custom' => array_filter([
                                'cart_id'     => (string) $cart->id,
                                'customer_id' => $customer ? (string) $customer->id : null,
                            ]),
                        ],
                    ],
                    'relationships' => [
                        'store'   => ['data' => ['type' => 'stores', 'id' => (string) $storeId]],
                        'variant' => ['data' => ['type' => 'variants', 'id' => (string) $variantId]],
                    ],
                ],
            ]);

        if ($response->successful()) {
            return response()->json([
                'checkout_url' => $response->json('data.attributes.url'),
            ]);
        }

        Log::error('LemonSqueezy checkout error', [
            'status' => $response->status(),
            'body'   => $response->json(),
        ]);

        return response()->json(['error' => 'Không thể tạo phiên thanh toán'], 500);
    }

    /**
     * Xử lý webhook từ Lemon Squeezy.
     */
    public function handleWebhook(Request $request)
    {
        $secret = core()->getConfigData('sales.payment_methods.lemonsqueezy.signing_secret')
            ?: env('LEMON_SQUEEZY_SIGNING_SECRET');

        $signature = $request->header('X-Signature');
        $payload = $request->getContent();

        if (! $secret || ! $signature || ! hash_equals(hash_hmac('sha256', $payload, $secret), $signature)) {
            Log::warning('LemonSqueezy webhook: invalid signature');
            return response('Unauthorized', 403);
        }

        $event = $request->input('meta.event_name');
        $data = $request->input('data', []);
        $customData = $request->input('meta.custom_data', []);

        Log::info("LemonSqueezy webhook: {$event}", ['ls_order_id' => $data['id'] ?? null]);

        match ($event) {
            'order_created'  => $this->handleOrderCreated($data, $customData, $payload),
            'order_refunded' => $this->handleOrderRefunded($data, $customData),
            default          => null,
        };

        return response('OK', 200);
    }

    public function success(Request $request)
    {
        $cart = Cart::getCart();

        // If webhook already processed and cart deactivated, order exists → success
        if (! $cart) {
            return redirect()->route('shop.checkout.onepage.success');
        }

        // Cart still active = webhook hasn't processed yet
        // Wait briefly for webhook, then show pending message
        $cartId = $cart->id;
        $maxWait = 10; // seconds

        for ($i = 0; $i < $maxWait; $i++) {
            if (LemonSqueezyTransaction::where('cart_id', $cartId)->where('status', 'paid')->exists()) {
                return redirect()->route('shop.checkout.onepage.success');
            }
            sleep(1);
        }

        // Webhook still pending — redirect to success anyway (webhook will process async)
        // But log for monitoring
        Log::warning('LemonSqueezy: success redirect before webhook confirmed', ['cart_id' => $cartId]);

        return redirect()->route('shop.checkout.onepage.success');
    }

    protected function handleOrderCreated(array $data, array $customData, string $rawPayload): void
    {
        $lsOrderId = (string) ($data['id'] ?? '');
        $cartId = $customData['cart_id'] ?? null;

        if (! $cartId || ! $lsOrderId) {
            Log::warning('LemonSqueezy webhook: missing cart_id or ls_order_id');
            return;
        }

        // Idempotency check
        if (LemonSqueezyTransaction::where('ls_order_id', $lsOrderId)->exists()) {
            Log::info('LemonSqueezy webhook: duplicate, skipping', ['ls_order_id' => $lsOrderId]);
            return;
        }

        $cart = \Webkul\Checkout\Models\Cart::find($cartId);

        if (! $cart) {
            Log::warning('LemonSqueezy webhook: cart not found', ['cart_id' => $cartId]);
            return;
        }

        $attrs = $data['attributes'] ?? [];

        // Record transaction first (idempotency lock)
        $transaction = LemonSqueezyTransaction::create([
            'ls_order_id'     => $lsOrderId,
            'cart_id'         => $cartId,
            'customer_id'     => $customData['customer_id'] ?? null,
            'status'          => 'pending',
            'amount_usd_cents' => $attrs['total'] ?? 0,
            'amount_vnd'      => $cart->grand_total,
            'currency'        => $attrs['currency'] ?? 'USD',
            'receipt_url'     => $attrs['urls']['receipt'] ?? $attrs['receipt_url'] ?? null,
            'webhook_payload' => json_decode($rawPayload, true),
        ]);

        try {
            // Ensure cart has billing address (required by OrderResource)
            if (! $cart->billing_address) {
                $customer = $cart->customer_id ? \Webkul\Customer\Models\Customer::find($cart->customer_id) : null;
                \Webkul\Checkout\Models\CartAddress::create([
                    'cart_id'       => $cart->id,
                    'address_type'  => 'cart_billing',
                    'first_name'    => $customer->first_name ?? ($customData['name'] ?? 'Guest'),
                    'last_name'     => $customer->last_name ?? '',
                    'email'         => $customer->email ?? ($customData['email'] ?? 'guest@lamgame.vn'),
                    'country'       => 'VN',
                    'state'         => 'HCM',
                    'city'          => 'Ho Chi Minh',
                    'postcode'      => '700000',
                    'address'       => json_encode(['Digital Delivery']),
                    'phone'         => '0000000000',
                ]);
                $cart->load('billing_address');
            }

            if (! $cart->shipping_address) {
                $b = $cart->billing_address;
                \Webkul\Checkout\Models\CartAddress::create([
                    'cart_id'       => $cart->id,
                    'address_type'  => 'cart_shipping',
                    'first_name'    => $b->first_name,
                    'last_name'     => $b->last_name,
                    'email'         => $b->email,
                    'country'       => $b->country,
                    'state'         => $b->state,
                    'city'          => $b->city,
                    'postcode'      => $b->postcode,
                    'address'       => $b->address,
                    'phone'         => $b->phone,
                ]);
                $cart->load('shipping_address');
            }

            Cart::setCart($cart);
            $data = (new OrderResource($cart))->jsonSerialize();
            $order = $this->orderRepository->create($data);

            if ($order) {
                // Auto invoice
                $invoiceData = ['order_id' => $order->id];
                foreach ($order->items as $item) {
                    $invoiceData['invoice']['items'][$item->id] = $item->qty_to_invoice;
                }
                $this->invoiceRepository->create($invoiceData);

                Cart::deActivateCart();

                // LS-305: Verify downloadable links were granted
                $downloadLinks = \Webkul\Sales\Models\DownloadableLinkPurchased::where('order_id', $order->id)->get();
                if ($downloadLinks->isNotEmpty()) {
                    Log::info('LemonSqueezy: downloadable links granted', [
                        'order_id' => $order->id,
                        'links'    => $downloadLinks->pluck('status', 'name')->toArray(),
                    ]);
                }

                $transaction->update([
                    'order_id' => $order->id,
                    'status'   => 'paid',
                ]);

                Log::info('LemonSqueezy order created', [
                    'order_id'    => $order->id,
                    'ls_order_id' => $lsOrderId,
                ]);
            }
        } catch (\Exception $e) {
            $transaction->update(['status' => 'failed']);

            Log::error('LemonSqueezy order creation failed', [
                'ls_order_id' => $lsOrderId,
                'error'       => $e->getMessage(),
            ]);
        }
    }

    protected function handleOrderRefunded(array $data, array $customData): void
    {
        $lsOrderId = (string) ($data['id'] ?? '');

        $transaction = LemonSqueezyTransaction::where('ls_order_id', $lsOrderId)->first();

        if (! $transaction || ! $transaction->order_id) {
            return;
        }

        $order = $this->orderRepository->find($transaction->order_id);

        if ($order) {
            $order->update(['status' => 'closed']);
            $transaction->update(['status' => 'refunded']);

            // LS-305: Revoke downloadable links on refund
            \Webkul\Sales\Models\DownloadableLinkPurchased::where('order_id', $order->id)
                ->update(['status' => 'expired']);

            Log::info('LemonSqueezy order refunded', [
                'order_id'    => $order->id,
                'ls_order_id' => $lsOrderId,
            ]);
        }
    }
}
