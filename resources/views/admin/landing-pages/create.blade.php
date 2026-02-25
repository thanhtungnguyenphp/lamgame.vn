<x-admin::layouts>
    <x-slot:title>
        Tạo Landing Page
    </x-slot:title>

    <div class="flex items-center justify-between gap-4 max-sm:flex-wrap">
        <p class="text-xl font-bold text-gray-800 dark:text-white">
            Tạo Landing Page
        </p>
        <a href="{{ route('admin.landing-pages.index') }}" class="secondary-button">
            ← Quay lại
        </a>
    </div>

    <form action="{{ route('admin.landing-pages.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @include('admin.landing-pages._form', ['page' => null])
    </form>
</x-admin::layouts>
