@component('shop::emails.layout')
    <div style="margin-bottom: 34px;">
        <span style="font-size: 22px;font-weight: 600;color: #121A26">
            Xác nhận đơn hàng
        </span> <br>

        <p style="font-size: 16px;color: #5E5E5E;line-height: 24px;">
            Xin chào {{ $order->customer_full_name }}, 👋
        </p>

        <p style="font-size: 16px;color: #5E5E5E;line-height: 24px;">
            Cảm ơn bạn đã đặt hàng! Đơn hàng <strong style="color: #6a4c93;">#{{ $order->increment_id }}</strong> 
            đã được tạo lúc {{ $order->created_at->format('d/m/Y H:i') }}.
        </p>
    </div>

    {{-- Download Section for Downloadable Products --}}
    @php
        $hasDownloadable = $order->items->contains(fn($item) => $item->type == 'downloadable');
    @endphp

    @if($hasDownloadable)
        <div style="background: #f3e8ff; border: 2px solid #6a4c93; border-radius: 12px; padding: 24px; margin-bottom: 30px;">
            <div style="font-size: 18px; font-weight: 600; color: #6a4c93; margin-bottom: 12px;">
                📥 Sản phẩm tải về
            </div>
            <p style="font-size: 14px; color: #5E5E5E; margin-bottom: 16px;">
                Đơn hàng của bạn chứa source code có thể tải về. Sau khi thanh toán được xác nhận, bạn có thể tải về tại:
            </p>
            <a href="{{ route('shop.customers.account.downloadable_products.index') }}" 
               style="display: inline-block; background: #6a4c93; color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600;">
                Tải Source Code
            </a>
            <p style="font-size: 12px; color: #888; margin-top: 12px;">
                Hoặc truy cập: Tài khoản → Sản phẩm tải về
            </p>
        </div>
    @endif

    <div style="font-size: 20px;font-weight: 600;color: #121A26">
        Chi tiết đơn hàng
    </div>

    {{-- Order Items --}}
    <div style="padding-bottom: 30px; border-bottom: 1px solid #CBD5E1; margin-top: 20px;">
        <table style="border-collapse: collapse; width: 100%;">
            <thead>
                <tr style="color: #121A26; border-bottom: 2px solid #CBD5E1;">
                    <th style="text-align: left; padding: 12px 0;">Sản phẩm</th>
                    <th style="text-align: center; padding: 12px 0;">SL</th>
                    <th style="text-align: right; padding: 12px 0;">Giá</th>
                </tr>
            </thead>
            <tbody style="font-size: 14px; color: #384860;">
                @foreach ($order->items as $item)
                    <tr style="border-bottom: 1px solid #e5e7eb;">
                        <td style="padding: 12px 0;">
                            {{ $item->name }}
                            @if($item->type == 'downloadable')
                                <span style="background: #d1fae5; color: #065f46; padding: 2px 8px; border-radius: 4px; font-size: 11px; margin-left: 8px;">Tải về</span>
                            @endif
                        </td>
                        <td style="text-align: center; padding: 12px 0;">{{ $item->qty_ordered }}</td>
                        <td style="text-align: right; padding: 12px 0;">{{ core()->formatPrice($item->total, $order->order_currency_code) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Order Summary --}}
    <div style="padding: 20px 0; font-size: 14px; color: #384860;">
        <div style="display: flex; justify-content: space-between; padding: 8px 0;">
            <span>Tạm tính:</span>
            <span>{{ core()->formatPrice($order->sub_total, $order->order_currency_code) }}</span>
        </div>
        
        @if($order->shipping_amount > 0)
            <div style="display: flex; justify-content: space-between; padding: 8px 0;">
                <span>Phí vận chuyển:</span>
                <span>{{ core()->formatPrice($order->shipping_amount, $order->order_currency_code) }}</span>
            </div>
        @endif
        
        @if($order->tax_amount > 0)
            <div style="display: flex; justify-content: space-between; padding: 8px 0;">
                <span>Thuế:</span>
                <span>{{ core()->formatPrice($order->tax_amount, $order->order_currency_code) }}</span>
            </div>
        @endif
        
        @if($order->discount_amount > 0)
            <div style="display: flex; justify-content: space-between; padding: 8px 0; color: #059669;">
                <span>Giảm giá:</span>
                <span>-{{ core()->formatPrice($order->discount_amount, $order->order_currency_code) }}</span>
            </div>
        @endif
        
        <div style="display: flex; justify-content: space-between; padding: 12px 0; font-size: 18px; font-weight: 600; color: #121A26; border-top: 2px solid #CBD5E1; margin-top: 8px;">
            <span>Tổng cộng:</span>
            <span style="color: #6a4c93;">{{ core()->formatPrice($order->grand_total, $order->order_currency_code) }}</span>
        </div>
    </div>

    {{-- Payment Method --}}
    <div style="background: #f9fafb; border-radius: 8px; padding: 16px; margin-top: 20px;">
        <div style="font-size: 14px; font-weight: 600; color: #121A26; margin-bottom: 8px;">
            Phương thức thanh toán
        </div>
        <div style="font-size: 14px; color: #384860;">
            {{ core()->getConfigData('sales.payment_methods.' . $order->payment->method . '.title') }}
        </div>
    </div>

    {{-- Footer --}}
    <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e7eb; text-align: center;">
        <p style="font-size: 14px; color: #6b7280;">
            Nếu bạn có bất kỳ câu hỏi nào, vui lòng liên hệ: <a href="mailto:salegamevui@gmail.com" style="color: #6a4c93;">salegamevui@gmail.com</a>
        </p>
        <p style="font-size: 12px; color: #9ca3af; margin-top: 8px;">
            © {{ date('Y') }} LAMGAME.VN - Làm Game
        </p>
    </div>
@endcomponent
