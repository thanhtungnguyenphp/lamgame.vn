@component('emails.layout')
<div style="margin-bottom: 30px;">
    <h1 style="font-size: 24px; font-weight: 700; color: #2c5f41; margin: 0 0 10px;">
        🎉 Đơn hàng đã được đặt thành công!
    </h1>
    <p style="font-size: 16px; color: #495057; margin: 0; line-height: 1.6;">
        Xin chào <strong>{{ $order->customer_full_name }}</strong>,
    </p>
    <p style="font-size: 16px; color: #495057; margin: 10px 0 0; line-height: 1.6;">
        Cảm ơn bạn đã đặt hàng tại <strong>LamGame.vn</strong>! Đơn hàng của bạn đã được tiếp nhận và đang được xử lý.
    </p>
</div>

<!-- Order Info Box -->
<div style="background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%); border-radius: 12px; padding: 20px; margin-bottom: 30px;">
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td width="50%">
                <p style="margin: 0; font-size: 14px; color: #666;">Mã đơn hàng</p>
                <p style="margin: 5px 0 0; font-size: 20px; font-weight: 700; color: #2c5f41;">#{{ $order->increment_id }}</p>
            </td>
            <td width="50%" style="text-align: right;">
                <p style="margin: 0; font-size: 14px; color: #666;">Ngày đặt</p>
                <p style="margin: 5px 0 0; font-size: 16px; font-weight: 600; color: #333;">{{ $order->created_at->format('d/m/Y H:i') }}</p>
            </td>
        </tr>
    </table>
</div>

<!-- Products -->
<div style="margin-bottom: 30px;">
    <h2 style="font-size: 18px; font-weight: 600; color: #333; margin: 0 0 15px; padding-bottom: 10px; border-bottom: 2px solid #e9ecef;">
        📦 Chi tiết đơn hàng
    </h2>
    <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse: collapse;">
        <thead>
            <tr style="background-color: #f8f9fa;">
                <th style="padding: 12px; text-align: left; font-size: 14px; color: #666; border-bottom: 1px solid #e9ecef;">Sản phẩm</th>
                <th style="padding: 12px; text-align: center; font-size: 14px; color: #666; border-bottom: 1px solid #e9ecef;">SL</th>
                <th style="padding: 12px; text-align: right; font-size: 14px; color: #666; border-bottom: 1px solid #e9ecef;">Giá</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->items as $item)
            <tr>
                <td style="padding: 15px 12px; border-bottom: 1px solid #f1f3f4;">
                    <p style="margin: 0; font-size: 15px; font-weight: 600; color: #333;">{{ $item->name }}</p>
                    <p style="margin: 5px 0 0; font-size: 13px; color: #888;">SKU: {{ $item->sku }}</p>
                </td>
                <td style="padding: 15px 12px; text-align: center; border-bottom: 1px solid #f1f3f4; font-size: 15px; color: #333;">
                    {{ $item->qty_ordered }}
                </td>
                <td style="padding: 15px 12px; text-align: right; border-bottom: 1px solid #f1f3f4; font-size: 15px; font-weight: 600; color: #333;">
                    {{ core()->formatPrice($item->total, $order->order_currency_code) }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Order Summary -->
<div style="background-color: #f8f9fa; border-radius: 12px; padding: 20px; margin-bottom: 30px;">
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td style="padding: 8px 0; font-size: 15px; color: #666;">Tạm tính</td>
            <td style="padding: 8px 0; font-size: 15px; color: #333; text-align: right;">{{ core()->formatPrice($order->sub_total, $order->order_currency_code) }}</td>
        </tr>
        @if ($order->shipping_amount > 0)
        <tr>
            <td style="padding: 8px 0; font-size: 15px; color: #666;">Phí vận chuyển</td>
            <td style="padding: 8px 0; font-size: 15px; color: #333; text-align: right;">{{ core()->formatPrice($order->shipping_amount, $order->order_currency_code) }}</td>
        </tr>
        @endif
        @if ($order->discount_amount > 0)
        <tr>
            <td style="padding: 8px 0; font-size: 15px; color: #666;">Giảm giá</td>
            <td style="padding: 8px 0; font-size: 15px; color: #28a745; text-align: right;">-{{ core()->formatPrice($order->discount_amount, $order->order_currency_code) }}</td>
        </tr>
        @endif
        <tr>
            <td colspan="2" style="padding: 15px 0 0; border-top: 2px solid #e9ecef;"></td>
        </tr>
        <tr>
            <td style="padding: 8px 0; font-size: 18px; font-weight: 700; color: #2c5f41;">Tổng cộng</td>
            <td style="padding: 8px 0; font-size: 20px; font-weight: 700; color: #2c5f41; text-align: right;">{{ core()->formatPrice($order->grand_total, $order->order_currency_code) }}</td>
        </tr>
    </table>
</div>

<!-- Payment Info -->
<div style="margin-bottom: 30px;">
    <h2 style="font-size: 18px; font-weight: 600; color: #333; margin: 0 0 15px;">💳 Thông tin thanh toán</h2>
    <p style="margin: 0; font-size: 15px; color: #495057;">
        <strong>Phương thức:</strong> {{ core()->getConfigData('sales.payment_methods.' . $order->payment->method . '.title') ?? $order->payment->method }}
    </p>
</div>

@if ($order->billing_address)
<!-- Billing Address -->
<div style="margin-bottom: 30px;">
    <h2 style="font-size: 18px; font-weight: 600; color: #333; margin: 0 0 15px;">📍 Thông tin khách hàng</h2>
    <div style="background-color: #f8f9fa; border-radius: 8px; padding: 15px;">
        <p style="margin: 0 0 5px; font-size: 15px; color: #333;"><strong>{{ $order->billing_address->name }}</strong></p>
        <p style="margin: 0 0 5px; font-size: 14px; color: #666;">{{ $order->billing_address->email }}</p>
        <p style="margin: 0; font-size: 14px; color: #666;">{{ $order->billing_address->phone }}</p>
    </div>
</div>
@endif

<!-- CTA Button -->
<div style="text-align: center; margin-top: 30px;">
    <a href="{{ route('shop.customers.account.orders.view', $order->id) }}" 
       style="display: inline-block; background: linear-gradient(135deg, #2c5f41 0%, #1e4530 100%); color: #ffffff; text-decoration: none; padding: 15px 40px; border-radius: 8px; font-size: 16px; font-weight: 600;">
        Xem chi tiết đơn hàng
    </a>
</div>

<p style="margin: 30px 0 0; font-size: 14px; color: #888; text-align: center; line-height: 1.6;">
    Nếu bạn có bất kỳ câu hỏi nào về đơn hàng, vui lòng liên hệ với chúng tôi.
</p>
@endcomponent
