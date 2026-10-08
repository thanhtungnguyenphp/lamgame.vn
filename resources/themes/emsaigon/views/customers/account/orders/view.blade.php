<x-layouts.account>
    <x-slot:title>
        Đơn hàng #{{ $order->increment_id }}
    </x-slot>

    @php
        $statusLabels = [
            'completed' => ['Hoàn thành', '#dcfce7', '#166534'],
            'processing' => ['Đang xử lý', '#dbeafe', '#1e40af'],
            'pending' => ['Chờ xử lý', '#fef9c3', '#854d0e'],
            'canceled' => ['Đã hủy', '#fee2e2', '#991b1b'],
            'closed' => ['Đã đóng', '#f3f4f6', '#374151'],
        ];
        $st = $statusLabels[$order->status] ?? [ucfirst($order->status), '#f3f4f6', '#374151'];
        $billing = $order->billing_address;
        $payMethodLabels = [
            'paypal_smart_button' => 'PayPal',
            'paypal_standard' => 'PayPal',
            'lemonsqueezy' => 'Thẻ quốc tế (Lemon Squeezy)',
            'cashondelivery' => 'Thanh toán khi nhận',
            'moneytransfer' => 'Chuyển khoản',
        ];
        $payMethod = $payMethodLabels[optional($order->payment)->method] ?? (optional($order->payment)->method ?? '—');
        $cur = fn($v) => '$' . number_format((float) $v, 2);
    @endphp

    <style>
        .od-head { display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:1rem; margin-bottom:1.5rem; }
        .od-head__id { font-size:1.5rem; font-weight:800; color:#F5F7FA; }
        .od-head__date { color:#7A8599; font-size:0.9rem; margin-top:0.25rem; }
        .od-badge { padding:5px 14px; border-radius:12px; font-size:0.85rem; font-weight:600; }
        .od-grid { display:grid; grid-template-columns:1fr 340px; gap:1.5rem; align-items:start; }
        .od-card { background:rgba(255,255,255,.03); border:1px solid rgba(124,92,255,.15); border-radius:14px; padding:1.5rem; margin-bottom:1.25rem; }
        .od-card__title { font-size:1.05rem; font-weight:700; color:#F5F7FA; margin:0 0 1rem 0; }
        .od-item { display:flex; gap:1rem; padding:0.9rem 0; border-bottom:1px solid rgba(124,92,255,.1); }
        .od-item:last-child { border-bottom:none; }
        .od-item__img { width:60px; height:60px; border-radius:10px; object-fit:cover; background:#111827; flex-shrink:0; }
        .od-item__name { font-weight:600; color:#F5F7FA; }
        .od-item__meta { color:#7A8599; font-size:0.85rem; margin-top:0.2rem; }
        .od-item__total { font-weight:700; color:#00D1FF; white-space:nowrap; }
        .od-sum-row { display:flex; justify-content:space-between; padding:0.5rem 0; color:#B7C0D1; font-size:0.95rem; }
        .od-sum-total { border-top:1px solid rgba(124,92,255,.15); margin-top:0.5rem; padding-top:0.75rem; font-size:1.15rem; font-weight:800; color:#F5F7FA; }
        .od-sum-total span:last-child { color:#00D1FF; }
        .od-info-row { display:flex; justify-content:space-between; gap:1rem; padding:0.4rem 0; font-size:0.92rem; }
        .od-info-row span:first-child { color:#7A8599; }
        .od-info-row span:last-child { color:#F5F7FA; font-weight:500; text-align:right; }
        .od-back { display:inline-flex; align-items:center; gap:0.4rem; color:#00D1FF; text-decoration:none; font-weight:600; margin-bottom:1rem; }
        @media (max-width:768px){ .od-grid { grid-template-columns:1fr; } }
    </style>

    <a href="{{ route('shop.customers.account.orders.index') }}" class="od-back">{{ __('lamgame.order_detail.back') }}</a>

    <div class="od-head">
        <div>
            <div class="od-head__id">{{ __('lamgame.order_detail.order') }} #{{ $order->increment_id }}</div>
            <div class="od-head__date">{{ __('lamgame.order_detail.placed_on') }} {{ $order->created_at?->format('d/m/Y H:i') }}</div>
        </div>
        <span class="od-badge" style="background:{{ $st[1] }};color:{{ $st[2] }}">{{ $st[0] }}</span>
    </div>

    <div class="od-grid">
        <!-- Cột trái: sản phẩm -->
        <div>
            <div class="od-card">
                <h3 class="od-card__title">{{ __('lamgame.order_detail.products') }} ({{ $order->items->count() }})</h3>
                @foreach($order->items as $item)
                    <div class="od-item">
                        <div style="flex:1">
                            <div class="od-item__name">{{ $item->name }}</div>
                            <div class="od-item__meta">SKU: {{ $item->sku }} · SL: {{ (int) $item->qty_ordered }} · {{ $cur($item->price) }}</div>
                        </div>
                        <div class="od-item__total">{{ $cur($item->total) }}</div>
                    </div>
                @endforeach
            </div>

            <!-- Thông tin khách hàng -->
            <div class="od-card">
                <h3 class="od-card__title">{{ __('lamgame.order_detail.customer') }}</h3>
                @if($billing)
                    <div class="od-info-row"><span>{{ __('lamgame.order_detail.full_name') }}</span><span>{{ trim($billing->first_name . ' ' . $billing->last_name) }}</span></div>
                    <div class="od-info-row"><span>{{ __('lamgame.order_detail.email') ?? 'Email' }}</span><span>{{ $billing->email }}</span></div>
                    @if($billing->phone)<div class="od-info-row"><span>{{ __('lamgame.order_detail.phone') }}</span><span>{{ $billing->phone }}</span></div>@endif
                @else
                    <div class="od-info-row"><span>{{ __('lamgame.order_detail.email') ?? 'Email' }}</span><span>{{ $order->customer_email }}</span></div>
                @endif
            </div>
        </div>

        <!-- Cột phải: tóm tắt thanh toán -->
        <div>
            <div class="od-card">
                <h3 class="od-card__title">{{ __('lamgame.order_detail.pay_summary') }}</h3>
                <div class="od-sum-row"><span>{{ __('lamgame.order_detail.subtotal') }}</span><span>{{ $cur($order->sub_total) }}</span></div>
                @if($order->discount_amount > 0)
                    <div class="od-sum-row"><span>{{ __('lamgame.order_detail.discount') }}</span><span style="color:#34D399">-{{ $cur($order->discount_amount) }}</span></div>
                @endif
                @if($order->tax_amount > 0)
                    <div class="od-sum-row"><span>{{ __('lamgame.order_detail.tax') }}</span><span>{{ $cur($order->tax_amount) }}</span></div>
                @endif
                @if($order->shipping_amount > 0)
                    <div class="od-sum-row"><span>{{ __('lamgame.order_detail.shipping') }}</span><span>{{ $cur($order->shipping_amount) }}</span></div>
                @endif
                <div class="od-sum-row od-sum-total"><span>{{ __('lamgame.order_detail.total') }}</span><span>{{ $cur($order->grand_total) }}</span></div>
            </div>

            <div class="od-card">
                <h3 class="od-card__title">{{ __('lamgame.order_detail.payment') }}</h3>
                <div class="od-info-row"><span>{{ __('lamgame.order_detail.method') }}</span><span>{{ $payMethod }}</span></div>
                <div class="od-info-row"><span>{{ __('lamgame.order_detail.order_status') }}</span><span>{{ $st[0] }}</span></div>
            </div>

            @if($order->status === 'completed' || $order->status === 'processing')
                <a href="{{ route('shop.customers.account.downloadable_products.index') }}"
                   style="display:block;text-align:center;padding:0.9rem;background:linear-gradient(135deg,#7C5CFF,#00D1FF);color:#fff;border-radius:10px;font-weight:700;text-decoration:none">
                    {{ __('lamgame.order_detail.download_src') }}
                </a>
            @endif
        </div>
    </div>
</x-layouts.account>
