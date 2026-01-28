<x-shop::layouts
    :has-header="true"
    :has-feature="false"
    :has-footer="true"
>
    <x-slot:title>
        Đặt hàng thành công
    </x-slot>

    <div class="container mt-8 px-[60px] max-lg:px-8">
        <div class="grid place-items-center gap-y-5 max-md:gap-y-2.5">
            <img 
                class="max-md:h-[100px] max-md:w-[100px]"
                src="{{ bagisto_asset('images/thank-you.png') }}" 
                alt="Đặt hàng thành công" 
            >

            <p class="text-xl max-md:text-sm">
                Mã đơn hàng của bạn là <span class="font-semibold">#{{ $order->increment_id }}</span>
            </p>

            <p class="font-medium md:text-2xl">
                Cảm ơn bạn đã đặt hàng!
            </p>
            
            <p class="text-xl text-zinc-500 max-md:text-center max-md:text-xs">
                @if (! empty($order->checkout_message))
                    {!! nl2br($order->checkout_message) !!}
                @else
                    Chúng tôi sẽ gửi email xác nhận đơn hàng và thông tin chi tiết cho bạn.
                @endif
            </p>

            <a href="{{ url('/') }}">
                <div class="w-max cursor-pointer rounded-2xl bg-navyBlue px-11 py-3 text-center text-base font-medium text-white max-md:rounded-lg max-md:px-6 max-md:py-1.5">
                    Tiếp tục mua sắm
                </div> 
            </a>
        </div>
    </div>
</x-shop::layouts>
