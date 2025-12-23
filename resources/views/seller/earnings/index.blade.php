@extends('shop::layouts.master')

@section('page_title')
    Doanh thu
@endsection

@section('content-wrapper')
<div class="container px-4 py-8 mx-auto max-w-7xl">
    <h1 class="mb-6 text-3xl font-bold">Doanh thu</h1>

    <!-- Stats -->
    <div class="grid grid-cols-1 gap-6 mb-8 md:grid-cols-3">
        <div class="p-6 bg-white rounded-lg shadow">
            <p class="text-sm text-gray-600">Tổng thu nhập</p>
            <p class="text-3xl font-bold text-green-600">{{ number_format($stats['total_earnings']) }}đ</p>
        </div>

        <div class="p-6 bg-white rounded-lg shadow">
            <p class="text-sm text-gray-600">Đã rút</p>
            <p class="text-3xl font-bold text-blue-600">{{ number_format($stats['total_withdrawn']) }}đ</p>
        </div>

        <div class="p-6 bg-white rounded-lg shadow">
            <p class="text-sm text-gray-600">Số dư khả dụng</p>
            <p class="text-3xl font-bold text-purple-600">{{ number_format($stats['available_balance']) }}đ</p>
            <a href="{{ route('seller.withdrawals.create') }}" class="inline-block px-4 py-2 mt-3 text-sm text-white bg-purple-600 rounded hover:bg-purple-700">
                Rút tiền
            </a>
        </div>
    </div>

    <!-- Earnings List -->
    <div class="overflow-hidden bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b">
            <h2 class="text-xl font-semibold">Lịch sử thu nhập</h2>
        </div>
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Ngày</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Đơn hàng</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Sản phẩm</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-right text-gray-500 uppercase">Giá trị đơn</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-right text-gray-500 uppercase">Phí (30%)</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-right text-gray-500 uppercase">Thu nhập</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($earnings as $earning)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $earning->created_at->format('d/m/Y') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">#{{ $earning->order_id }}</td>
                    <td class="px-6 py-4">{{ $earning->product->flat->name ?? 'N/A' }}</td>
                    <td class="px-6 py-4 text-right whitespace-nowrap">{{ number_format($earning->order_amount) }}đ</td>
                    <td class="px-6 py-4 text-right text-red-600 whitespace-nowrap">-{{ number_format($earning->platform_fee_amount) }}đ</td>
                    <td class="px-6 py-4 text-right font-bold text-green-600 whitespace-nowrap">{{ number_format($earning->seller_amount) }}đ</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">Chưa có doanh thu</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $earnings->links() }}
    </div>
</div>
@endsection
