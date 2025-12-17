<x-admin::layouts>
    <x-slot:title>
        Chi tiết Seller: {{ $seller->shop_name }}
    </x-slot:title>

    <div class="flex gap-4 justify-between items-center max-sm:flex-wrap mb-4">
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.sellers.index') }}" class="text-gray-600 hover:text-gray-800">
                <i class="icon-arrow-left text-2xl"></i>
            </a>
            <p class="text-xl text-gray-800 dark:text-white font-bold">
                Chi tiết Seller: {{ $seller->shop_name }}
            </p>
        </div>

        <div class="flex gap-x-2.5 items-center">
            @if($seller->isPending())
                <form action="{{ route('admin.sellers.approve', $seller->id) }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="primary-button" onclick="return confirm('Duyệt seller này?')">
                        ✓ Duyệt
                    </button>
                </form>
                <button type="button" class="secondary-button" onclick="document.getElementById('rejectModal').classList.remove('hidden')">
                    ✗ Từ chối
                </button>
            @elseif($seller->isActive())
                <form action="{{ route('admin.sellers.suspend', $seller->id) }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="secondary-button" onclick="return confirm('Tạm ngưng seller này?')">
                        Tạm ngưng
                    </button>
                </form>
            @elseif($seller->isSuspended())
                <form action="{{ route('admin.sellers.activate', $seller->id) }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="primary-button" onclick="return confirm('Kích hoạt lại seller này?')">
                        Kích hoạt
                    </button>
                </form>
            @endif
        </div>
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

    <div class="grid gap-4">
        <!-- Shop Info -->
        <x-admin::accordion>
            <x-slot:header>
                <p class="text-base text-gray-800 dark:text-white font-semibold">
                    Thông tin Shop
                </p>
            </x-slot:header>

            <x-slot:content>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs text-gray-600 dark:text-gray-300 font-medium">Shop Name</label>
                        <p class="text-sm text-gray-800 dark:text-white font-semibold">{{ $seller->shop_name }}</p>
                    </div>
                    <div>
                        <label class="text-xs text-gray-600 dark:text-gray-300 font-medium">Shop Slug</label>
                        <p class="text-sm text-gray-800 dark:text-white">{{ $seller->shop_slug }}</p>
                    </div>
                </div>
                <div class="mt-4">
                    <label class="text-xs text-gray-600 dark:text-gray-300 font-medium">Mô tả</label>
                    <p class="text-sm text-gray-800 dark:text-white">{{ $seller->shop_description ?: 'Chưa có mô tả' }}</p>
                </div>
                <div class="grid grid-cols-2 gap-4 mt-4">
                    @if($seller->shop_logo)
                    <div>
                        <label class="text-xs text-gray-600 dark:text-gray-300 font-medium">Logo</label><br>
                        <img src="{{ $seller->logo_url }}" alt="Logo" class="mt-2 max-w-[200px] border rounded p-2">
                    </div>
                    @endif
                    @if($seller->shop_banner)
                    <div>
                        <label class="text-xs text-gray-600 dark:text-gray-300 font-medium">Banner</label><br>
                        <img src="{{ $seller->banner_url }}" alt="Banner" class="mt-2 max-w-[400px] border rounded p-2">
                    </div>
                    @endif
                </div>
            </x-slot:content>
        </x-admin::accordion>

        <!-- Contact Info -->
        <x-admin::accordion>
            <x-slot:header>
                <p class="text-base text-gray-800 dark:text-white font-semibold">
                    Thông tin liên hệ
                </p>
            </x-slot:header>

            <x-slot:content>
                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label class="text-xs text-gray-600 dark:text-gray-300 font-medium">Email</label>
                        <p class="text-sm text-gray-800 dark:text-white">{{ $seller->contact_email }}</p>
                    </div>
                    <div>
                        <label class="text-xs text-gray-600 dark:text-gray-300 font-medium">Phone</label>
                        <p class="text-sm text-gray-800 dark:text-white">{{ $seller->contact_phone ?: 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="text-xs text-gray-600 dark:text-gray-300 font-medium">Website</label>
                        <p class="text-sm text-gray-800 dark:text-white">{{ $seller->website ?: 'N/A' }}</p>
                    </div>
                </div>
            </x-slot:content>
        </x-admin::accordion>

        <!-- Business Info -->
        <x-admin::accordion>
            <x-slot:header>
                <p class="text-base text-gray-800 dark:text-white font-semibold">
                    Thông tin doanh nghiệp
                </p>
            </x-slot:header>

            <x-slot:content>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs text-gray-600 dark:text-gray-300 font-medium">Loại hình</label>
                        <p class="text-sm">
                            <span class="px-2 py-1 rounded {{ $seller->business_type == 'company' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $seller->business_type == 'company' ? 'Công ty' : 'Cá nhân' }}
                            </span>
                        </p>
                    </div>
                    @if($seller->business_type == 'company')
                    <div>
                        <label class="text-xs text-gray-600 dark:text-gray-300 font-medium">Mã số thuế</label>
                        <p class="text-sm text-gray-800 dark:text-white">{{ $seller->tax_id }}</p>
                    </div>
                    @endif
                </div>
            </x-slot:content>
        </x-admin::accordion>

        <!-- Bank Info -->
        <x-admin::accordion>
            <x-slot:header>
                <p class="text-base text-gray-800 dark:text-white font-semibold">
                    Thông tin ngân hàng
                </p>
            </x-slot:header>

            <x-slot:content>
                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label class="text-xs text-gray-600 dark:text-gray-300 font-medium">Ngân hàng</label>
                        <p class="text-sm text-gray-800 dark:text-white">{{ $seller->bank_name }}</p>
                    </div>
                    <div>
                        <label class="text-xs text-gray-600 dark:text-gray-300 font-medium">Số tài khoản</label>
                        <p class="text-sm text-gray-800 dark:text-white">{{ $seller->bank_account }}</p>
                    </div>
                    <div>
                        <label class="text-xs text-gray-600 dark:text-gray-300 font-medium">Chủ tài khoản</label>
                        <p class="text-sm text-gray-800 dark:text-white">{{ $seller->bank_holder }}</p>
                    </div>
                </div>
            </x-slot:content>
        </x-admin::accordion>

        <!-- Status & Stats -->
        <x-admin::accordion>
            <x-slot:header>
                <p class="text-base text-gray-800 dark:text-white font-semibold">
                    Trạng thái & Thống kê
                </p>
            </x-slot:header>

            <x-slot:content>
                <div class="grid grid-cols-4 gap-4">
                    <div>
                        <label class="text-xs text-gray-600 dark:text-gray-300 font-medium">Trạng thái</label>
                        <p class="text-sm">
                            <span class="px-2 py-1 rounded {{ $seller->status == 'active' ? 'bg-green-100 text-green-800' : ($seller->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                {{ ucfirst($seller->status) }}
                            </span>
                        </p>
                    </div>
                    <div>
                        <label class="text-xs text-gray-600 dark:text-gray-300 font-medium">Tổng sản phẩm</label>
                        <p class="text-sm text-gray-800 dark:text-white font-semibold">{{ $seller->total_products }}</p>
                    </div>
                    <div>
                        <label class="text-xs text-gray-600 dark:text-gray-300 font-medium">Tổng đơn hàng</label>
                        <p class="text-sm text-gray-800 dark:text-white font-semibold">{{ $seller->total_sales }}</p>
                    </div>
                    <div>
                        <label class="text-xs text-gray-600 dark:text-gray-300 font-medium">Doanh thu</label>
                        <p class="text-sm text-gray-800 dark:text-white font-semibold">{{ number_format($seller->total_revenue, 0, ',', '.') }}đ</p>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4 mt-4">
                    <div>
                        <label class="text-xs text-gray-600 dark:text-gray-300 font-medium">Ngày đăng ký</label>
                        <p class="text-sm text-gray-800 dark:text-white">{{ $seller->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    @if($seller->verified_at)
                    <div>
                        <label class="text-xs text-gray-600 dark:text-gray-300 font-medium">Ngày duyệt</label>
                        <p class="text-sm text-gray-800 dark:text-white">{{ $seller->verified_at->format('d/m/Y H:i') }}</p>
                    </div>
                    @endif
                </div>
            </x-slot:content>
        </x-admin::accordion>

        <!-- Customer Info -->
        <x-admin::accordion>
            <x-slot:header>
                <p class="text-base text-gray-800 dark:text-white font-semibold">
                    Thông tin khách hàng
                </p>
            </x-slot:header>

            <x-slot:content>
                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label class="text-xs text-gray-600 dark:text-gray-300 font-medium">Tên</label>
                        <p class="text-sm text-gray-800 dark:text-white">{{ $seller->customer->first_name }} {{ $seller->customer->last_name }}</p>
                    </div>
                    <div>
                        <label class="text-xs text-gray-600 dark:text-gray-300 font-medium">Email</label>
                        <p class="text-sm text-gray-800 dark:text-white">{{ $seller->customer->email }}</p>
                    </div>
                    <div>
                        <label class="text-xs text-gray-600 dark:text-gray-300 font-medium">Phone</label>
                        <p class="text-sm text-gray-800 dark:text-white">{{ $seller->customer->phone ?: 'N/A' }}</p>
                    </div>
                </div>
            </x-slot:content>
        </x-admin::accordion>
    </div>

    <!-- Reject Modal -->
    <div id="rejectModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Từ chối Seller</h3>
                <form action="{{ route('admin.sellers.reject', $seller->id) }}" method="POST">
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
