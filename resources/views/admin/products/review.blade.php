<x-admin::layouts>
    <x-slot:title>
        Duyệt sản phẩm: {{ $product->name }}
    </x-slot:title>

    <div class="flex gap-4 justify-between items-center max-sm:flex-wrap mb-4">
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.products.pending') }}" class="text-gray-600 hover:text-gray-800">
                <i class="icon-arrow-left text-2xl"></i>
            </a>
            <p class="text-xl text-gray-800 dark:text-white font-bold">
                Duyệt sản phẩm: {{ $product->name }}
            </p>
        </div>

        <div class="flex gap-x-2.5 items-center">
            @if($product->pending_review)
                <form action="{{ route('admin.products.approve', $product->id) }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="primary-button" onclick="return confirm('Duyệt sản phẩm này?')">
                        ✓ Duyệt
                    </button>
                </form>
                <button type="button" class="secondary-button" onclick="document.getElementById('rejectModal').classList.remove('hidden')">
                    ✗ Từ chối
                </button>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid gap-4">
        <!-- Product Info -->
        <x-admin::accordion>
            <x-slot:header>
                <p class="text-base text-gray-800 dark:text-white font-semibold">
                    Thông tin sản phẩm
                </p>
            </x-slot:header>

            <x-slot:content>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs text-gray-600 dark:text-gray-300 font-medium">Tên sản phẩm</label>
                        <p class="text-sm text-gray-800 dark:text-white font-semibold">{{ $product->name }}</p>
                    </div>
                    <div>
                        <label class="text-xs text-gray-600 dark:text-gray-300 font-medium">SKU</label>
                        <p class="text-sm text-gray-800 dark:text-white">{{ $product->sku }}</p>
                    </div>
                </div>
                <div class="mt-4">
                    <label class="text-xs text-gray-600 dark:text-gray-300 font-medium">Mô tả ngắn</label>
                    <p class="text-sm text-gray-800 dark:text-white">{{ $product->short_description ?: 'Chưa có' }}</p>
                </div>
                <div class="mt-4">
                    <label class="text-xs text-gray-600 dark:text-gray-300 font-medium">Mô tả chi tiết</label>
                    <p class="text-sm text-gray-800 dark:text-white">{{ $product->description ?: 'Chưa có' }}</p>
                </div>
                <div class="grid grid-cols-2 gap-4 mt-4">
                    <div>
                        <label class="text-xs text-gray-600 dark:text-gray-300 font-medium">Giá</label>
                        <p class="text-sm text-gray-800 dark:text-white font-semibold">{{ number_format($product->price, 0, ',', '.') }}đ</p>
                    </div>
                    <div>
                        <label class="text-xs text-gray-600 dark:text-gray-300 font-medium">Giá khuyến mãi</label>
                        <p class="text-sm text-gray-800 dark:text-white">{{ $product->special_price ? number_format($product->special_price, 0, ',', '.') . 'đ' : 'Không có' }}</p>
                    </div>
                </div>
            </x-slot:content>
        </x-admin::accordion>

        <!-- Images -->
        <x-admin::accordion>
            <x-slot:header>
                <p class="text-base text-gray-800 dark:text-white font-semibold">
                    Hình ảnh ({{ $product->images->count() }})
                </p>
            </x-slot:header>

            <x-slot:content>
                <div class="grid grid-cols-4 gap-4">
                    @foreach($product->images as $image)
                        <img src="{{ Storage::url($image->path) }}" alt="Product Image" class="w-full h-48 object-cover rounded border">
                    @endforeach
                </div>
            </x-slot:content>
        </x-admin::accordion>

        <!-- Categories -->
        <x-admin::accordion>
            <x-slot:header>
                <p class="text-base text-gray-800 dark:text-white font-semibold">
                    Danh mục
                </p>
            </x-slot:header>

            <x-slot:content>
                <div class="flex flex-wrap gap-2">
                    @foreach($product->categories as $category)
                        <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">{{ $category->name }}</span>
                    @endforeach
                </div>
            </x-slot:content>
        </x-admin::accordion>

        <!-- Seller Info -->
        @if($product->seller)
        <x-admin::accordion>
            <x-slot:header>
                <p class="text-base text-gray-800 dark:text-white font-semibold">
                    Thông tin Seller
                </p>
            </x-slot:header>

            <x-slot:content>
                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label class="text-xs text-gray-600 dark:text-gray-300 font-medium">Shop Name</label>
                        <p class="text-sm text-gray-800 dark:text-white">{{ $product->seller->shop_name }}</p>
                    </div>
                    <div>
                        <label class="text-xs text-gray-600 dark:text-gray-300 font-medium">Email</label>
                        <p class="text-sm text-gray-800 dark:text-white">{{ $product->seller->contact_email }}</p>
                    </div>
                    <div>
                        <label class="text-xs text-gray-600 dark:text-gray-300 font-medium">Status</label>
                        <p class="text-sm">
                            <span class="px-2 py-1 rounded {{ $product->seller->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ ucfirst($product->seller->status) }}
                            </span>
                        </p>
                    </div>
                </div>
            </x-slot:content>
        </x-admin::accordion>
        @endif
    </div>

    <!-- Reject Modal -->
    <div id="rejectModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Từ chối sản phẩm</h3>
                <form action="{{ route('admin.products.reject', $product->id) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="reason" class="block text-sm font-medium text-gray-700 mb-2">Lý do từ chối <span class="text-red-500">*</span></label>
                        <textarea name="reason" id="reason" rows="4" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="primary-button">Xác nhận từ chối</button>
                        <button type="button" onclick="document.getElementById('rejectModal').classList.add('hidden')" class="secondary-button">Hủy</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-admin::layouts>
