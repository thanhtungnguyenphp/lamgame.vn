@component('emails.layout')
<div style="margin-bottom: 30px;">
    <h1 style="font-size: 24px; font-weight: 700; color: #2c5f41; margin: 0 0 10px;">
        🎉 Bạn có đơn hàng mới!
    </h1>
    <p style="font-size: 16px; color: #495057; margin: 0; line-height: 1.6;">
        Xin chào <strong>{{ $seller->shop_name }}</strong>,
    </p>
    <p style="font-size: 16px; color: #495057; margin: 10px 0 0; line-height: 1.6;">
        Có khách hàng vừa đặt mua sản phẩm của bạn trên <strong>LamGame.vn</strong>!
    </p>
</div>

<!-- Order Info Box -->
<div style="background: linear-gradient(135deg, #fff3cd 0%, #ffeeba 100%); border-radius: 12px; padding: 20px; margin-bottom: 30px;">
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td width="50%">
                <p style="margin: 0; font-size: 14px; color: #856404;">Mã đơn hàng</p>
                <p style="margin: 5px 0 0; font-size: 20px; font-weight: 700; color: #856404;">#{{ $order->increment_id }}</p>
            </td>
            <td width="50%" style="text-align: right;">
                <p style="margin: 0; font-size: 14px; color: #856404;">Ngày đặt</p>
                <p style="margin: 5px 0 0; font-size: 16px; font-weight: 600; color: #856404;">{{ $order->created_at->format('d/m/Y H:i') }}</p>
            </td>
        </tr>
    </table>
</div>

<!-- Customer Info -->
<div style="background-color: #e3f2fd; border-radius: 12px; padding: 20px; margin-bottom: 30px;">
    <h2 style="font-size: 16px; font-weight: 600; color: #1565c0; margin: 0 0 15px;">👤 Thông tin khách hàng</h2>
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td style="padding: 5px 0; font-size: 14px; color: #666;">Tên:</td>
            <td style="padding: 5px 0; font-size: 14px; color: #333; font-weight: 600;">{{ $order->customer_full_name }}</td>
        </tr>
        <tr>
            <td style="padding: 5px 0; font-size: 14px; color: #666;">Email:</td>
            <td style="padding: 5px 0; font-size: 14px; color: #333;">{{ $order->customer_email }}</td>
        </tr>
        @if ($order->billing_address && $order->billing_address->phone)
        <tr>
            <td style="padding: 5px 0; font-size: 14px; color: #666;">Điện thoại:</td>
            <td style="padding: 5px 0; font-size: 14px; color: #333;">{{ $order->billing_address->phone }}</td>
        </tr>
        @endif
    </table>
</div>

<!-- Products -->
<div style="margin-bottom: 30px;">
    <h2 style="font-size: 18px; font-weight: 600; color: #333; margin: 0 0 15px; padding-bottom: 10px; border-bottom: 2px solid #e9ecef;">
        📦 Sản phẩm của bạn trong đơn hàng
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
            @foreach ($sellerItems as $item)
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

<!-- Seller Total -->
<div style="background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%); border-radius: 12px; padding: 20px; margin-bottom: 30px;">
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td style="font-size: 18px; font-weight: 600; color: #2c5f41;">Doanh thu của bạn</td>
            <td style="font-size: 24px; font-weight: 700; color: #2c5f41; text-align: right;">
                {{ core()->formatPrice($sellerTotal, $order->order_currency_code) }}
            </td>
        </tr>
    </table>
    <p style="margin: 10px 0 0; font-size: 13px; color: #666;">
        * Số tiền thực nhận sau khi trừ phí hoa hồng sẽ được cập nhật trong dashboard của bạn.
    </p>
</div>

<!-- CTA Button -->
<div style="text-align: center; margin-top: 30px;">
    <a href="{{ route('seller.orders.index') }}" 
       style="display: inline-block; background: linear-gradient(135deg, #2c5f41 0%, #1e4530 100%); color: #ffffff; text-decoration: none; padding: 15px 40px; border-radius: 8px; font-size: 16px; font-weight: 600;">
        Xem đơn hàng trong Dashboard
    </a>
</div>

<p style="margin: 30px 0 0; font-size: 14px; color: #888; text-align: center; line-height: 1.6;">
    Hãy đảm bảo sản phẩm của bạn sẵn sàng để khách hàng tải về!
</p>
@endcomponent
