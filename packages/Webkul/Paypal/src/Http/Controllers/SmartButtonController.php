<?php

namespace Webkul\Paypal\Http\Controllers;

use Webkul\Checkout\Facades\Cart;
use Webkul\Paypal\Payment\SmartButton;
use Webkul\Sales\Repositories\InvoiceRepository;
use Webkul\Sales\Repositories\OrderRepository;
use Webkul\Sales\Transformers\OrderResource;

class SmartButtonController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected SmartButton $smartButton,
        protected OrderRepository $orderRepository,
        protected InvoiceRepository $invoiceRepository
    ) {}

    /**
     * Paypal order creation for approval of client.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function createOrder()
    {
        try {
            return response()->json($this->smartButton->createOrder($this->buildRequestBody()));
        } catch (\Exception $e) {
            \Log::error('PayPal createOrder error', [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);

            $decoded = json_decode($e->getMessage());

            return response()->json([
                'message' => $decoded ?: $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Capturing paypal order after approval.
     *
     * @return \Illuminate\Http\Response
     */
    public function captureOrder()
    {
        try {
            $this->smartButton->captureOrder(request()->input('orderData.orderID'));

            return $this->saveOrder();
        } catch (\Exception $e) {
            \Log::error('PayPal captureOrder error', [
                'message'  => $e->getMessage(),
                'order_id' => request()->input('orderData.orderID'),
                'trace'    => substr($e->getTraceAsString(), 0, 2000),
            ]);

            $decoded = json_decode($e->getMessage());

            return response()->json([
                'message' => $decoded ?: $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Build request body.
     *
     * @return array
     */
    protected function buildRequestBody()
    {
        $cart = Cart::getCart();

        // Ép dùng USD cho PayPal: toàn site đã chuyển sang USD và SDK PayPal cũng
        // Ép dùng USD cho PayPal: toàn site đã chuyển sang USD và SDK PayPal cũng
        // load currency=USD. Không dựa vào cart currency code vì các cart cũ
        // có thể còn giữ mã không hợp lệ/0-decimal (VND/USA) gây DECIMALS_NOT_SUPPORTED.
        $currencyCode = 'USD';

        $billingAddressLines = $this->getAddressLines($cart->billing_address->address);

        $data = [
            'intent' => 'CAPTURE',

            'payer'  => [
                'name' => [
                    'given_name' => $cart->billing_address->first_name,
                    'surname'    => $cart->billing_address->last_name,
                ],

                'address' => [
                    'address_line_1' => current($billingAddressLines),
                    'address_line_2' => last($billingAddressLines),
                    'admin_area_2'   => $cart->billing_address->city,
                    'admin_area_1'   => $cart->billing_address->state,
                    'postal_code'    => $cart->billing_address->postcode,
                    'country_code'   => $cart->billing_address->country,
                ],

                'email_address' => $cart->billing_address->email,
            ],

            'application_context' => [
                'shipping_preference' => 'NO_SHIPPING',
            ],

            'purchase_units' => [
                [
                    'amount'   => [
                        'value'         => $this->smartButton->formatCurrencyValue((float) $cart->sub_total + $cart->tax_total + ($cart->selected_shipping_rate ? $cart->selected_shipping_rate->price : 0) - $cart->discount_amount),
                        'currency_code' => $currencyCode,

                        'breakdown'     => [
                            'item_total' => [
                                'currency_code' => $currencyCode,
                                'value'         => $this->smartButton->formatCurrencyValue((float) $cart->sub_total),
                            ],

                            'shipping'   => [
                                'currency_code' => $currencyCode,
                                'value'         => $this->smartButton->formatCurrencyValue((float) ($cart->selected_shipping_rate ? $cart->selected_shipping_rate->price : 0)),
                            ],

                            'tax_total'  => [
                                'currency_code' => $currencyCode,
                                'value'         => $this->smartButton->formatCurrencyValue((float) $cart->tax_total),
                            ],

                            'discount'   => [
                                'currency_code' => $currencyCode,
                                'value'         => $this->smartButton->formatCurrencyValue((float) $cart->discount_amount),
                            ],
                        ],
                    ],

                    'items'    => $this->getLineItems($cart),
                ],
            ],
        ];

        if (! empty($cart->billing_address->phone)) {
            $data['payer']['phone'] = [
                'phone_type'   => 'MOBILE',

                'phone_number' => [
                    'national_number' => $this->smartButton->formatPhone($cart->billing_address->phone),
                ],
            ];
        }

        if (
            $cart->haveStockableItems()
            && $cart->shipping_address
        ) {
            $data['application_context']['shipping_preference'] = 'SET_PROVIDED_ADDRESS';

            $data['purchase_units'][0] = array_merge($data['purchase_units'][0], [
                'shipping' => [
                    'address' => [
                        'address_line_1' => current($billingAddressLines),
                        'address_line_2' => last($billingAddressLines),
                        'admin_area_2'   => $cart->shipping_address->city,
                        'admin_area_1'   => $cart->shipping_address->state,
                        'postal_code'    => $cart->shipping_address->postcode,
                        'country_code'   => $cart->shipping_address->country,
                    ],
                ],
            ]);
        }

        return $data;
    }

    /**
     * Return cart items.
     *
     * @param  string  $cart
     * @return array
     */
    protected function getLineItems($cart)
    {
        $lineItems = [];

        foreach ($cart->items as $item) {
            $lineItems[] = [
                'unit_amount' => [
                    'currency_code' => 'USD',
                    'value'         => $this->smartButton->formatCurrencyValue((float) $item->price),
                ],
                'quantity'    => $item->quantity,
                'name'        => $item->name,
                'sku'         => $item->sku,
                'category'    => $item->getTypeInstance()->isStockable() ? 'PHYSICAL_GOODS' : 'DIGITAL_GOODS',
            ];
        }

        return $lineItems;
    }

    /**
     * Return convert multiple address lines into 2 address lines.
     *
     * @param  string  $address
     * @return array
     */
    protected function getAddressLines($address)
    {
        $address = explode(PHP_EOL, $address, 2);

        $addressLines = [current($address)];

        if (isset($address[1])) {
            $addressLines[] = str_replace(["\r\n", "\r", "\n"], ' ', last($address));
        } else {
            $addressLines[] = '';
        }

        return $addressLines;
    }

    /**
     * Saving order once captured and all formalities done.
     *
     * @return \Illuminate\Http\Response
     */
    protected function saveOrder()
    {
        if (Cart::hasError()) {
            return response()->json(['redirect_url' => route('shop.checkout.cart.index')], 403);
        }

        try {
            Cart::collectTotals();

            $this->validateOrder();

            $cart = Cart::getCart();

            // Nếu khách đã đăng nhập nhưng cart đang là guest (customer_id null),
            // gắn customer vào cart để order thuộc về tài khoản (hiện trong My Orders,
            // sinh license, tải được). Fix cart tạo như guest dù đã login.
            if (auth()->guard('customer')->check() && empty($cart->customer_id)) {
                $customer = auth()->guard('customer')->user();

                \Webkul\Checkout\Models\Cart::where('id', $cart->id)->update([
                    'customer_id'        => $customer->id,
                    'customer_email'     => $customer->email,
                    'customer_first_name'=> $customer->first_name,
                    'customer_last_name' => $customer->last_name,
                    'is_guest'           => 0,
                ]);

                $cart = Cart::getCart();
            }

            $data = (new OrderResource($cart))->jsonSerialize();

            $order = $this->orderRepository->create($data);

            $this->orderRepository->update(['status' => 'processing'], $order->id);

            if ($order->canInvoice()) {
                $this->invoiceRepository->create($this->prepareInvoiceData($order));
            }

            Cart::deActivateCart();

            session()->flash('order_id', $order->id);

            return response()->json([
                'success' => true,
            ]);
        } catch (\Exception $e) {
            session()->flash('error', trans('shop::app.common.error'));

            throw $e;
        }
    }

    /**
     * Prepares order's invoice data for creation.
     *
     * @param  \Webkul\Sales\Models\Order  $order
     * @return array
     */
    protected function prepareInvoiceData($order)
    {
        $invoiceData = ['order_id' => $order->id];

        foreach ($order->items as $item) {
            $invoiceData['invoice']['items'][$item->id] = $item->qty_to_invoice;
        }

        return $invoiceData;
    }

    /**
     * Validate order before creation.
     *
     * @return void|\Exception
     */
    protected function validateOrder()
    {
        $cart = Cart::getCart();

        $minimumOrderAmount = (float) core()->getConfigData('sales.order_settings.minimum_order.minimum_order_amount') ?: 0;

        if (! Cart::haveMinimumOrderAmount()) {
            throw new \Exception(trans('shop::app.checkout.cart.minimum-order-message', ['amount' => core()->currency($minimumOrderAmount)]));
        }

        if (
            $cart->haveStockableItems()
            && ! $cart->shipping_address
        ) {
            throw new \Exception(trans('shop::app.checkout.cart.check-shipping-address'));
        }

        if (! $cart->billing_address) {
            throw new \Exception(trans('shop::app.checkout.cart.check-billing-address'));
        }

        if (
            $cart->haveStockableItems()
            && ! $cart->selected_shipping_rate
        ) {
            throw new \Exception(trans('shop::app.checkout.cart.specify-shipping-method'));
        }

        if (! $cart->payment) {
            throw new \Exception(trans('shop::app.checkout.cart.specify-payment-method'));
        }
    }
}
