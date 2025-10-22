<x-admin::layouts>
    <x-slot:title>
        Quản lý Báo cáo Forum
    </x-slot>

    <div class="flex items-center justify-between gap-4 max-sm:flex-wrap">
        <p class="py-3 text-xl font-bold text-gray-800 dark:text-white">
            Quản lý Báo cáo Vi phạm
        </p>

        <div class="flex items-center gap-x-2.5">
            <!-- Statistics -->
            <div class="flex gap-4">
                <div class="flex items-center gap-2">
                    <span class="text-sm text-gray-600 dark:text-gray-300">Tổng:</span>
                    <span class="text-sm font-semibold">{{ \App\Models\ForumReport::count() }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-sm text-gray-600 dark:text-gray-300">Chờ xử lý:</span>
                    <span class="text-sm font-semibold text-red-600">{{ \App\Models\ForumReport::where('status', 'pending')->count() }}</span>
                </div>
            </div>
        </div>
    </div>

    <x-admin::datagrid :src="route('admin.forum.reports.index')" />
</x-admin::layouts>
