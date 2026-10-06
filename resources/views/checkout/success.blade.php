@extends('layouts.master')

@section('page_title')
    Đặt hàng thành công
@endsection

@push('styles')
<style>
    :root {
        --co-surface-2: rgba(17,24,39,.6); --co-border: rgba(124,92,255,.15);
        --co-text: #F5F7FA; --co-text-2: #B7C0D1; --co-muted: #7A8599;
        --co-accent: #7C5CFF; --co-accent-2: #00D1FF; --co-success: #34D399;
    }
    .success-wrap { max-width: 640px; margin: 0 auto; padding: 3rem 1rem; text-align: center; color: var(--co-text); }
    .success-icon { margin-bottom: 1.5rem; }
    .success-wrap h1 { font-size: 1.8rem; font-weight: 800; margin-bottom: 0.75rem; }
    .success-order-id { font-size: 1.1rem; color: var(--co-text-2); margin-bottom: 0.5rem; }
    .success-order-id strong { color: var(--co-accent-2); }
    .success-info { color: var(--co-muted); margin-bottom: 1.5rem; }
    .success-dl { background: var(--co-surface-2); border: 1px solid var(--co-border); border-radius: 14px; padding: 1.5rem; margin: 0 auto 1.5rem; max-width: 520px; }
    .success-dl h3 { font-weight: 700; color: var(--co-text); display: flex; align-items: center; justify-content: center; gap: 0.5rem; margin-bottom: 0.75rem; }
    .success-dl p { color: var(--co-text-2); font-size: 0.9rem; margin-bottom: 1rem; }
    .co-btn { display: inline-flex; align-items: center; gap: 0.5rem; font-weight: 700; padding: 0.75rem 1.5rem; border-radius: 10px; text-decoration: none; transition: opacity .2s, border-color .2s; cursor: pointer; }
    .co-btn--primary { background: linear-gradient(135deg,var(--co-accent),var(--co-accent-2)); color: #fff; border: none; }
    .co-btn--primary:hover { opacity: .9; }
    .co-btn--ghost { background: transparent; color: var(--co-text-2); border: 1px solid var(--co-border); }
    .co-btn--ghost:hover { border-color: var(--co-accent); color: var(--co-text); }
    .success-actions { display: flex; gap: 1rem; flex-wrap: wrap; justify-content: center; }
</style>
@endpush

@section('content')
<div class="success-wrap">
    <div class="success-icon">
        <svg width="80" height="80" viewBox="0 0 80 80" fill="none">
            <circle cx="40" cy="40" r="40" fill="rgba(52,211,153,.15)"/>
            <path d="M25 40l10 10 20-20" stroke="#34D399" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
    </div>

    <h1>Cảm ơn bạn đã đặt hàng!</h1>

    <p class="success-order-id">
        Mã đơn hàng: <strong>#{{ $order->increment_id }}</strong>
    </p>

    <p class="success-info">
        @if (! empty($order->checkout_message))
            {!! nl2br(e($order->checkout_message)) !!}
        @else
            Chúng tôi sẽ gửi email xác nhận đơn hàng và thông tin chi tiết cho bạn.
        @endif
    </p>

    @php
        $hasDownloadable = false;
        foreach($order->items as $item) {
            if ($item->type == 'downloadable' ||
                ($item->product && $item->product->type == 'downloadable') ||
                (($item->additional['product_type'] ?? null) == 'downloadable')) {
                $hasDownloadable = true;
                break;
            }
        }
        if (!$hasDownloadable) {
            $hasDownloadable = \DB::table('downloadable_link_purchased')
                ->where('order_id', $order->id)
                ->exists();
        }
    @endphp

    @if($hasDownloadable)
        <div class="success-dl">
            <h3>
                <svg width="22" height="22" fill="#7C5CFF" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/>
                </svg>
                Sản phẩm tải về
            </h3>

            @auth('customer')
                <p>Đơn hàng của bạn chứa source code có thể tải về ngay. Bạn cũng có thể xem license trong mục "License của tôi".</p>
                <a href="{{ route('shop.customers.account.downloadable_products.index') }}" class="co-btn co-btn--primary">
                    ⬇ Tải Source Code
                </a>
            @else
                <p>Link tải về đã được gửi qua email: <strong style="color:var(--co-text)">{{ $order->customer_email }}</strong>. Vui lòng kiểm tra hộp thư (bao gồm spam).</p>
                <div class="success-actions">
                    <a href="{{ route('shop.customer.session.create') }}" class="co-btn co-btn--primary">Đăng nhập để tải</a>
                    <a href="{{ route('shop.customers.register.index') }}" class="co-btn co-btn--ghost">Tạo tài khoản</a>
                </div>
            @endauth
        </div>
    @endif

    <div class="success-actions">
        @auth('customer')
            <a href="{{ route('shop.customers.account.orders.index') }}" class="co-btn co-btn--ghost">Xem đơn hàng</a>
        @endauth
        <a href="{{ route('lamgame.source-game') }}" class="co-btn co-btn--primary">Tiếp tục mua sắm</a>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const orderEvent = @json(in_array($order->status, ['processing', 'completed'], true) ? 'purchase' : 'order_submitted');
    window.trackRevenueEvent?.(orderEvent, {
        transaction_id: @json((string) $order->increment_id),
        currency: @json($order->order_currency_code ?? 'USD'),
        value: {{ (float) $order->grand_total }},
        order_status: @json($order->status),
        payment_method: @json(optional($order->payment)->method),
        items: @json($order->items->map(fn ($item) => [
            'item_id' => (string) $item->product_id,
            'item_name' => $item->name,
            'price' => (float) $item->price,
            'quantity' => (int) $item->qty_ordered,
        ])->values())
    }, 'order-' + @json((string) $order->increment_id) + '-' + orderEvent);
});
</script>
@endpush
