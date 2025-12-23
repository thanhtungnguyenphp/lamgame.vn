@extends('shop::layouts.master')

@section('page_title')
    Quản lý sản phẩm
@endsection

@section('content-wrapper')
<div class="container px-4 py-8 mx-auto max-w-7xl">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-bold">Sản phẩm của tôi</h1>
        <a href="{{ route('seller.products.create') }}" class="px-6 py-3 text-white bg-green-600 rounded-lg hover:bg-green-700">
            + Thêm sản phẩm mới
        </a>
    </div>

    @if(session('success'))
        <div class="p-4 mb-6 text-green-800 bg-green-100 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-hidden bg-white rounded-lg shadow">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Sản phẩm</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Giá</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Trạng thái</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Lượt tải</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-right text-gray-500 uppercase">Thao tác</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($products as $product)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            @if($product->images->first())
                                <img src="{{ Storage::url($product->images->first()->path) }}" class="w-16 h-16 mr-4 rounded" alt="">
                            @endif
                            <div>
                                <div class="font-medium text-gray-900">{{ $product->flat->name ?? $product->name }}</div>
                                <div class="text-sm text-gray-500">SKU: {{ $product->sku }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">
                            @if($product->flat && $product->flat->price > 0)
                                {{ number_format($product->flat->price) }}đ
                            @else
                                <span class="text-green-600">Miễn phí</span>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($product->flat && $product->flat->status)
                            <span class="px-2 py-1 text-xs text-green-800 bg-green-100 rounded-full">Đã duyệt</span>
                        @else
                            <span class="px-2 py-1 text-xs text-yellow-800 bg-yellow-100 rounded-full">Chờ duyệt</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">
                            {{ $product->downloadable_links->sum('downloads') }}
                        </div>
                    </td>
                    <td class="px-6 py-4 text-right whitespace-nowrap">
                        <a href="{{ route('seller.products.edit', $product->id) }}" class="mr-3 text-blue-600 hover:text-blue-900">Sửa</a>
                        <form action="{{ route('seller.products.destroy', $product->id) }}" method="POST" class="inline" onsubmit="return confirm('Xác nhận xóa?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900">Xóa</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                        Chưa có sản phẩm nào. <a href="{{ route('seller.products.create') }}" class="text-green-600 hover:underline">Thêm sản phẩm đầu tiên</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $products->links() }}
    </div>
</div>
@endsection
