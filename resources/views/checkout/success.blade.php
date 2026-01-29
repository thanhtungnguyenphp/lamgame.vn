@extends('layouts.master')

@section('page_title')
    Đặt hàng thành công
@endsection

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="flex flex-col items-center justify-center text-center">
        <!-- Icon -->
        <div class="mb-6">
            <img 
                src="{{ asset('images/thank-you.png') }}" 
                alt="Đặt hàng thành công" 
                class="w-32 h-32"
            >
        </div>

        <!-- Order ID -->
        <p class="text-lg text-gray-700 mb-2">
            Mã đơn hàng của bạn là <span class="font-semibold">#{{ $order->increment_id }}</span>
        </p>

        <!-- Thank you message -->
        <h1 class="text-2xl font-semibold text-gray-900 mb-3">
            Cảm ơn bạn đã đặt hàng!
        </h1>

        <!-- Info -->
        <p class="text-gray-500 mb-6 max-w-md">
            @if (! empty($order->checkout_message))
                {!! nl2br($order->checkout_message) !!}
            @else
                Chúng tôi sẽ gửi email xác nhận đơn hàng và thông tin chi tiết cho bạn.
            @endif
        </p>

        <!-- Continue Shopping Button -->
        <a href="{{ url('/') }}" 
           class="inline-block bg-navyBlue hover:bg-blue-800 text-white font-medium py-3 px-8 rounded-full transition-colors">
            Tiếp tục mua sắm
        </a>
    </div>
</div>
@endsection
