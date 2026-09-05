@extends('layouts.master')

@section('page_title', 'Đặt hàng thành công')

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="flex flex-col items-center justify-center text-center">
        <div class="mb-6">
            <svg class="w-32 h-32 text-green-500 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>

        <p class="text-lg text-gray-700 mb-2">
            Mã đơn hàng của bạn là <span class="font-semibold">#{{ $order->increment_id }}</span>
        </p>

        <h1 class="text-2xl font-semibold text-gray-900 mb-3">
            Cảm ơn bạn đã đặt hàng!
        </h1>

        <p class="text-gray-500 mb-6 max-w-md">
            @if (! empty($order->checkout_message))
                {!! nl2br($order->checkout_message) !!}
            @else
                Chúng tôi sẽ gửi email xác nhận đơn hàng và thông tin chi tiết cho bạn.
            @endif
        </p>

        <a href="{{ url('/') }}" 
           class="inline-block bg-[#2c5f41] hover:bg-[#1e4530] text-white font-medium py-3 px-8 rounded-full transition-colors">
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
