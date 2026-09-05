@extends('layouts.master')

@section('page_title', 'Đặt hàng thành công')

@section('content')
<div style="min-height: 60vh; display: flex; align-items: center; justify-content: center; padding: 3rem 1rem;">
    <div style="text-align: center; max-width: 500px;">
        <!-- Icon nhỏ gọn -->
        <div style="margin-bottom: 1.5rem;">
            <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="#22c55e" stroke-width="2" style="margin: 0 auto;">
                <circle cx="12" cy="12" r="10"></circle>
                <path d="M9 12l2 2 4-4"></path>
            </svg>
        </div>

        <p style="font-size: 1rem; color: #666; margin-bottom: 0.5rem;">
            Mã đơn hàng của bạn là <strong>#{{ $order->increment_id }}</strong>
        </p>

        <h1 style="font-size: 1.75rem; font-weight: 600; color: #333; margin-bottom: 1rem;">
            Cảm ơn bạn đã đặt hàng!
        </h1>

        <p style="color: #666; margin-bottom: 2rem;">
            @if (! empty($order->checkout_message))
                {!! nl2br($order->checkout_message) !!}
            @else
                Chúng tôi sẽ gửi email xác nhận đơn hàng và thông tin chi tiết cho bạn.
            @endif
        </p>

        <a href="{{ url('/') }}" 
           style="display: inline-block; background: #2c5f41; color: white; padding: 0.875rem 2rem; border-radius: 8px; text-decoration: none; font-weight: 500;">
            Tiếp tục mua sắm
        </a>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const orderEvent = @json(in_array($order->status, ['processing', 'completed'], true) ? 'purchase' : 'order_submitted');
    window.trackRevenueEvent?.(orderEvent, {
        transaction_id: @json((string) $order->increment_id), currency: @json($order->order_currency_code ?? 'VND'),
        value: {{ (float) $order->grand_total }}, order_status: @json($order->status),
        payment_method: @json(optional($order->payment)->method),
        items: @json($order->items->map(fn ($item) => ['item_id' => (string) $item->product_id, 'item_name' => $item->name, 'price' => (float) $item->price, 'quantity' => (int) $item->qty_ordered])->values())
    }, 'order-' + @json((string) $order->increment_id) + '-' + orderEvent);
});
</script>
@endpush
