<x-admin::layouts>
    <x-slot:title>
        Quản lý Sellers
    </x-slot>

    <div class="flex gap-4 justify-between items-center max-sm:flex-wrap">
        <p class="text-xl text-gray-800 dark:text-white font-bold">
            Quản lý Sellers
        </p>

        <div class="flex gap-x-2.5 items-center">
            <a href="{{ route('admin.sellers.pending') }}" class="primary-button">
                Chờ duyệt ({{ \App\Models\SourceGameSeller::where('status', 'pending')->count() }})
            </a>
        </div>
    </div>

    <x-admin::datagrid :src="route('admin.sellers.index')" /></x-admin::layouts>
