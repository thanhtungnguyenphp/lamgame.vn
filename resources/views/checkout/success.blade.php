@extends('layouts.master')

@section('page_title')
    Đặt hàng thành công
@endsection

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="flex flex-col items-center justify-center text-center">
        <!-- Icon -->
        <div class="mb-6">
            <svg width="80" height="80" viewBox="0 0 80 80" fill="none">
                <circle cx="40" cy="40" r="40" fill="#d1fae5"/>
                <path d="M25 40l10 10 20-20" stroke="#059669" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>

        <!-- Thank you message -->
        <h1 class="text-2xl font-semibold text-gray-900 mb-3">
            Cảm ơn bạn đã đặt hàng!
        </h1>

        <!-- Order ID -->
        <p class="text-lg text-gray-700 mb-2">
            Mã đơn hàng: <span class="font-semibold text-purple-600">#{{ $order->increment_id }}</span>
        </p>

        <!-- Info -->
        <p class="text-gray-500 mb-6 max-w-md">
            @if (! empty($order->checkout_message))
                {!! nl2br($order->checkout_message) !!}
            @else
                Chúng tôi sẽ gửi email xác nhận đơn hàng và thông tin chi tiết cho bạn.
            @endif
        </p>

        @php
            // Check all possible ways to detect downloadable
            $hasDownloadable = false;
            foreach($order->items as $item) {
                if ($item->type == 'downloadable' || 
                    ($item->product && $item->product->type == 'downloadable') ||
                    $item->additional['product_type'] ?? null == 'downloadable') {
                    $hasDownloadable = true;
                    break;
                }
            }
            
            // Also check downloadable_link_purchased table
            if (!$hasDownloadable) {
                $hasDownloadable = \DB::table('downloadable_link_purchased')
                    ->where('order_id', $order->id)
                    ->exists();
            }
        @endphp

        @if($hasDownloadable)
            <div class="bg-purple-50 border border-purple-200 rounded-xl p-6 mb-6 max-w-lg w-full">
                <div class="flex items-center justify-center gap-3 mb-3">
                    <svg width="24" height="24" fill="#6a4c93" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                    <h3 class="font-semibold text-purple-900">Sản phẩm tải về</h3>
                </div>
                
                @auth('customer')
                    <p class="text-sm text-purple-700 mb-4">
                        Đơn hàng của bạn chứa source code có thể tải về ngay.
                    </p>
                    <a href="{{ route('shop.customers.account.downloadable_products.index') }}" 
                       class="inline-flex items-center gap-2 bg-purple-600 hover:bg-purple-700 text-white font-medium py-3 px-6 rounded-lg transition-colors">
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                        Tải Source Code
                    </a>
                @else
                    <p class="text-sm text-purple-700 mb-4">
                        Link tải về đã được gửi qua email: <strong>{{ $order->customer_email }}</strong>
                    </p>
                    <p class="text-xs text-purple-600 mb-4">
                        Vui lòng kiểm tra hộp thư (bao gồm cả spam) để nhận link tải.
                    </p>
                    <div class="flex gap-3 justify-center">
                        <a href="{{ route('shop.customer.session.create') }}" 
                           class="inline-flex items-center gap-2 bg-purple-600 hover:bg-purple-700 text-white font-medium py-2 px-4 rounded-lg text-sm transition-colors">
                            Đăng nhập để tải
                        </a>
                        <a href="{{ route('shop.customers.register.index') }}" 
                           class="inline-flex items-center gap-2 bg-white border border-purple-300 hover:bg-purple-50 text-purple-700 font-medium py-2 px-4 rounded-lg text-sm transition-colors">
                            Tạo tài khoản
                        </a>
                    </div>
                @endauth
            </div>
        @endif

        <!-- Buttons -->
        <div class="flex gap-4 flex-wrap justify-center">
            @auth('customer')
                <a href="{{ route('shop.customers.account.orders.index') }}" 
                   class="inline-block bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-3 px-6 rounded-lg transition-colors">
                    Xem đơn hàng
                </a>
            @endauth
            <a href="{{ route('lamgame.source-game') }}" 
               class="inline-block bg-purple-600 hover:bg-purple-700 text-white font-medium py-3 px-6 rounded-lg transition-colors">
                Tiếp tục mua sắm
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const orderEvent = @json(in_array($order->status, ['processing', 'completed'], true) ? 'purchase' : 'order_submitted');
    window.trackRevenueEvent?.(orderEvent, {
        transaction_id: @json((string) $order->increment_id),
        currency: @json($order->order_currency_code ?? 'VND'),
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
