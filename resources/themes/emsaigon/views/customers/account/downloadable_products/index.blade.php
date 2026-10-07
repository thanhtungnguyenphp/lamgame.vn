<x-layouts.account>
    <!-- Page Title -->
    <x-slot:title>
        @lang('shop::app.customers.account.downloadable-products.name')
    </x-slot>

    <!-- Breadcrumbs -->
    @if ((core()->getConfigData('general.general.breadcrumbs.shop')))
        @section('breadcrumbs')
            <x-shop::breadcrumbs name="downloadable-products" />
        @endSection
    @endif

    <div class="max-md:hidden">
         
    </div>

    <div class="mx-4 flex-auto max-md:mx-6 max-sm:mx-4">
        <div class="mb-8 flex items-center max-md:mb-5">
            <!-- Back Button -->
            <a
                class="grid md:hidden"
                href="{{ route('shop.customers.account.index') }}"
            >
                <span class="icon-arrow-left rtl:icon-arrow-right text-2xl"></span>
            </a>

            <h2 class="text-2xl font-medium max-md:text-xl max-sm:text-base ltr:ml-2.5 md:ltr:ml-0 rtl:mr-2.5 md:rtl:mr-0">
                @lang('shop::app.customers.account.downloadable-products.name')
            </h2>
        </div>

        {!! view_render_event('bagisto.shop.customers.account.downloadable_products.list.before') !!}

        @php
            $myDownloads = \Webkul\Sales\Models\DownloadableLinkPurchased::where('customer_id', auth()->guard('customer')->id())
                ->orderByDesc('id')
                ->get();
        @endphp

        @if($myDownloads->isEmpty())
            <div style="padding:3rem 1rem;text-align:center;color:#6b7280;border:1px dashed #e5e7eb;border-radius:12px">
                <p style="margin-bottom:1rem">Bạn chưa có sản phẩm tải về nào.</p>
                <a href="{{ route('lamgame.source-game') }}" style="color:#2c5f41;font-weight:600">🎮 Khám phá Source Game</a>
            </div>
        @else
            <div style="overflow-x:auto">
                <table style="width:100%;border-collapse:collapse;font-size:0.95rem">
                    <thead>
                        <tr style="text-align:left;border-bottom:2px solid #e5e7eb;color:#374151">
                            <th style="padding:12px 10px">Sản phẩm</th>
                            <th style="padding:12px 10px">Mã đơn</th>
                            <th style="padding:12px 10px">Trạng thái</th>
                            <th style="padding:12px 10px"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($myDownloads as $dl)
                            @php
                                $canDownload = $dl->status !== 'pending';
                            @endphp
                            <tr style="border-bottom:1px solid #eee">
                                <td style="padding:12px 10px;font-weight:600">{{ $dl->name }}</td>
                                <td style="padding:12px 10px;color:#6b7280">#{{ optional($dl->order)->increment_id ?? $dl->order_id }}</td>
                                <td style="padding:12px 10px">
                                    @if($canDownload)
                                        <span style="padding:3px 10px;border-radius:12px;font-size:0.8rem;background:#dcfce7;color:#166534">Sẵn sàng</span>
                                    @else
                                        <span style="padding:3px 10px;border-radius:12px;font-size:0.8rem;background:#fef9c3;color:#854d0e">Chờ xử lý</span>
                                    @endif
                                </td>
                                <td style="padding:12px 10px">
                                    @if($canDownload)
                                        <a href="{{ route('shop.customers.account.downloadable_products.download', $dl->id) }}" style="color:#2c5f41;font-weight:600">⬇ Tải về</a>
                                    @else
                                        <span style="color:#9ca3af">—</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        {!! view_render_event('bagisto.shop.customers.account.downloadable_products.list.after') !!}

    </div>
</x-layouts.account>