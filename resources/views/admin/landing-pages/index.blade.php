<x-admin::layouts>
    <x-slot:title>
        Landing Pages
    </x-slot:title>

    <div class="flex items-center justify-between gap-4 max-sm:flex-wrap">
        <p class="text-xl font-bold text-gray-800 dark:text-white">
            Landing Pages
        </p>
        <a href="{{ route('admin.landing-pages.create') }}" class="primary-button">
            Tạo Landing Page
        </a>
    </div>

    <div class="mt-7 overflow-x-auto rounded-xl bg-white dark:bg-gray-900 shadow-sm">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b bg-gray-50 dark:bg-gray-800">
                    <th class="px-4 py-3 text-left font-semibold">Tên</th>
                    <th class="px-4 py-3 text-left font-semibold">Template</th>
                    <th class="px-4 py-3 text-left font-semibold">URL</th>
                    <th class="px-4 py-3 text-left font-semibold">Trạng thái</th>
                    <th class="px-4 py-3 text-left font-semibold">Lượt xem</th>
                    <th class="px-4 py-3 text-left font-semibold">Thời gian</th>
                    <th class="px-4 py-3 text-left font-semibold">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pages as $page)
                <tr class="border-b hover:bg-gray-50 dark:hover:bg-gray-800">
                    <td class="px-4 py-3 font-medium">{{ $page->name }}</td>
                    <td class="px-4 py-3">
                        <span class="rounded-full bg-blue-100 px-2 py-1 text-xs text-blue-800">{{ $page->template_label }}</span>
                    </td>
                    <td class="px-4 py-3">
                        <a href="{{ $page->url }}" target="_blank" class="text-blue-600 hover:underline">/p/{{ $page->slug }}</a>
                    </td>
                    <td class="px-4 py-3">
                        @if($page->isLive())
                            <span class="rounded-full bg-green-100 px-2 py-1 text-xs text-green-800">Live</span>
                        @elseif($page->status)
                            <span class="rounded-full bg-yellow-100 px-2 py-1 text-xs text-yellow-800">Đã lên lịch</span>
                        @else
                            <span class="rounded-full bg-gray-100 px-2 py-1 text-xs text-gray-600">Nháp</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">{{ number_format($page->views) }}</td>
                    <td class="px-4 py-3 text-xs">
                        @if($page->start_at){{ $page->start_at->format('d/m/Y H:i') }}@endif
                        @if($page->end_at) → {{ $page->end_at->format('d/m/Y H:i') }}@endif
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex gap-2">
                            <a href="{{ route('admin.landing-pages.edit', $page->id) }}" class="cursor-pointer rounded-md p-1.5 text-blue-600 transition-all hover:bg-blue-100">
                                <i class="icon-edit text-xl"></i>
                            </a>
                            <form action="{{ route('admin.landing-pages.destroy', $page->id) }}" method="POST" onsubmit="return confirm('Xóa landing page này?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="cursor-pointer rounded-md p-1.5 text-red-600 transition-all hover:bg-red-100">
                                    <i class="icon-delete text-xl"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-4 py-8 text-center text-gray-500">Chưa có landing page nào.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-admin::layouts>
