<x-admin::layouts>
    <x-slot:title>
        Sửa: {{ $page->name }}
    </x-slot:title>

    <div class="flex items-center justify-between gap-4 max-sm:flex-wrap">
        <p class="text-xl font-bold text-gray-800 dark:text-white">
            Sửa: {{ $page->name }}
        </p>
        <div class="flex gap-2">
            <a href="{{ $page->url }}" target="_blank" class="secondary-button">
                Xem trang →
            </a>
            <a href="{{ route('admin.landing-pages.index') }}" class="secondary-button">
                ← Quay lại
            </a>
        </div>
    </div>

    <form action="{{ route('admin.landing-pages.update', $page->id) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')
        @include('admin.landing-pages._form', ['page' => $page])
    </form>
</x-admin::layouts>
