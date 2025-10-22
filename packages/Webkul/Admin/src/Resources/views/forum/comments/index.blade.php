<x-admin::layouts>
    <x-slot:title>
        Quản lý Bình luận Forum
    </x-slot>

    <div class="flex items-center justify-between gap-4 max-sm:flex-wrap">
        <p class="py-3 text-xl font-bold text-gray-800 dark:text-white">
            Quản lý Bình luận Forum
        </p>

        <div class="flex items-center gap-x-2.5">
            <!-- Statistics -->
            <div class="flex gap-4">
                <div class="flex items-center gap-2">
                    <span class="text-sm text-gray-600 dark:text-gray-300">Tổng:</span>
                    <span class="text-sm font-semibold">{{ \App\Models\ForumComment::count() }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-sm text-gray-600 dark:text-gray-300">Chờ duyệt:</span>
                    <span class="text-sm font-semibold text-orange-600">{{ \App\Models\ForumComment::where('status', 'pending')->count() }}</span>
                </div>
            </div>
        </div>
    </div>

    <x-admin::datagrid :src="route('admin.forum.comments.index')" />
</x-admin::layouts>
