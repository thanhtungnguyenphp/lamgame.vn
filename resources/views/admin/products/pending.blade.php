<x-admin::layouts>
    <x-slot:title>
        Sản phẩm chờ duyệt
    </x-slot:title>

    <div class="flex gap-4 justify-between items-center max-sm:flex-wrap mb-4">
        <p class="text-xl text-gray-800 dark:text-white font-bold">
            Sản phẩm chờ duyệt ({{ $products->total() }})
        </p>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    @if($products->count() > 0)
        <div class="bg-white dark:bg-gray-900 rounded-lg shadow">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-800">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Hình</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Sản phẩm</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Seller</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Giá</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ngày gửi</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Hành động</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($products as $product)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                            <td class="px-4 py-3">
                                @if($product->images->first())
                                    <img src="{{ Storage::url($product->images->first()->path) }}" 
                                         alt="{{ $product->name }}" 
                                         class="w-16 h-16 object-cover rounded">
                                @else
                                    <div class="w-16 h-16 bg-gray-200 rounded flex items-center justify-center">
                                        📦
                                    </div>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="font-semibold text-gray-900 dark:text-white">{{ $product->name }}</div>
                                <div class="text-sm text-gray-500">SKU: {{ $product->sku }}</div>
                            </td>
                            <td class="px-4 py-3">
                                @if($product->seller)
                                    <div class="font-medium">{{ $product->seller->shop_name }}</div>
                                    <div class="text-sm text-gray-500">{{ $product->seller->customer->email }}</div>
                                @else
                                    <span class="text-gray-400">N/A</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 font-semibold text-green-600">
                                {{ number_format($product->price, 0, ',', '.') }}đ
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-500">
                                {{ $product->updated_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-4 py-3">
                                <a href="{{ route('admin.products.review', $product->id) }}" 
                                   class="text-blue-600 hover:text-blue-800 font-medium">
                                    Xem chi tiết →
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $products->links() }}
        </div>
    @else
        <div class="bg-white dark:bg-gray-900 rounded-lg shadow p-8 text-center">
            <div class="text-6xl mb-4">✅</div>
            <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">
                Không có sản phẩm chờ duyệt
            </h3>
            <p class="text-gray-500">Tất cả sản phẩm đã được xử lý</p>
        </div>
    @endif
</x-admin::layouts>
