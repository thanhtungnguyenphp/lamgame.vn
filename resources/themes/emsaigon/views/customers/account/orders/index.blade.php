<x-layouts.account>
    <!-- Page Title -->
    <x-slot:title>
        @lang('shop::app.customers.account.orders.title')
    </x-slot>

    <!-- Breadcrumbs -->
    @if ((core()->getConfigData('general.general.breadcrumbs.shop')))
        @section('breadcrumbs')
            <x-shop::breadcrumbs name="orders" />
        @endSection
    @endif

    <div class="max-md:hidden">
         
    </div>

    <div class="mx-4 flex-auto max-md:mx-6 max-sm:mx-4">
        <div class="mb-8 flex items-center max-sm:mb-5">
            <!-- Back Button -->
            <a
                class="grid md:hidden"
                href="{{ route('shop.customers.account.index') }}"
            >
                <span class="icon-arrow-left rtl:icon-arrow-right text-2xl"></span>
            </a>

            <h2 class="text-2xl font-medium max-sm:text-base ltr:ml-2.5 md:ltr:ml-0 rtl:mr-2.5 md:rtl:mr-0">
                @lang('shop::app.customers.account.orders.title')
            </h2>
        </div>

        {!! view_render_event('bagisto.shop.customers.account.orders.list.before') !!}

        @php
            $myOrders = \Webkul\Sales\Models\Order::where('customer_id', auth()->guard('customer')->id())
                ->orderByDesc('id')
                ->get();
        @endphp

        @if($myOrders->isEmpty())
            <div style="padding:3rem 1rem;text-align:center;color:#6b7280;border:1px dashed #e5e7eb;border-radius:12px">
                <p style="margin-bottom:1rem">Bạn chưa có đơn hàng nào.</p>
                <a href="{{ route('lamgame.source-game') }}" style="color:#2c5f41;font-weight:600">🎮 Khám phá Source Game</a>
            </div>
        @else
            <div style="overflow-x:auto">
                <table style="width:100%;border-collapse:collapse;font-size:0.95rem">
                    <thead>
                        <tr style="text-align:left;border-bottom:2px solid #e5e7eb;color:#374151">
                            <th style="padding:12px 10px">Mã đơn</th>
                            <th style="padding:12px 10px">Ngày đặt</th>
                            <th style="padding:12px 10px">Trạng thái</th>
                            <th style="padding:12px 10px">Tổng tiền</th>
                            <th style="padding:12px 10px"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($myOrders as $o)
                            @php
                                $statusLabels = [
                                    'completed' => ['Hoàn thành', '#dcfce7', '#166534'],
                                    'processing' => ['Đang xử lý', '#dbeafe', '#1e40af'],
                                    'pending' => ['Chờ xử lý', '#fef9c3', '#854d0e'],
                                    'canceled' => ['Đã hủy', '#fee2e2', '#991b1b'],
                                    'closed' => ['Đã đóng', '#f3f4f6', '#374151'],
                                ];
                                $st = $statusLabels[$o->status] ?? [ucfirst($o->status), '#f3f4f6', '#374151'];
                            @endphp
                            <tr style="border-bottom:1px solid #eee">
                                <td style="padding:12px 10px;font-weight:600">#{{ $o->increment_id }}</td>
                                <td style="padding:12px 10px;color:#6b7280">{{ $o->created_at?->format('d/m/Y H:i') }}</td>
                                <td style="padding:12px 10px">
                                    <span style="padding:3px 10px;border-radius:12px;font-size:0.8rem;background:{{ $st[1] }};color:{{ $st[2] }}">{{ $st[0] }}</span>
                                </td>
                                <td style="padding:12px 10px;font-weight:700;color:#2c5f41">${{ number_format($o->grand_total, 2) }}</td>
                                <td style="padding:12px 10px">
                                    <a href="{{ route('shop.customers.account.orders.view', $o->id) }}" style="color:#2c5f41;font-weight:600">Xem</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        {!! view_render_event('bagisto.shop.customers.account.orders.list.after') !!}

    </div>
</x-layouts.account>
