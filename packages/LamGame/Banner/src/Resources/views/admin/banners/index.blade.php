<x-admin::layouts>
    <x-slot:title>
        {{ __('banner::app.admin.banners.title') }}
    </x-slot:title>
    <div class="flex items-center justify-between">
        <p class="text-xl font-bold text-gray-800 dark:text-white">
            {{ __('banner::app.admin.banners.index-title') }}
        </p>

        <div class="flex items-center gap-x-2.5">
            <a href="{{ route('admin.banners.create') }}" class="primary-button">
                {{ __('banner::app.admin.banners.create-title') }}
            </a>
        </div>
    </div>

    <div class="mt-8">
        <div class="rounded-lg bg-white p-8 text-center shadow-lg dark:bg-gray-900">
            <div class="mb-6">
                <h2 class="text-3xl font-bold text-gray-800 dark:text-white">🎉 Banner Management System</h2>
                <p class="text-lg text-gray-600 dark:text-gray-300 mt-2">Banner module has been successfully installed!</p>
            </div>
            
            <div class="mb-6">
                <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-200 mb-4">Available API Endpoints:</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex items-center justify-center space-x-2">
                        <span class="bg-green-100 text-green-800 px-2 py-1 rounded font-mono">GET</span>
                        <span class="text-gray-600 dark:text-gray-300">/api/banners</span>
                    </div>
                    <div class="flex items-center justify-center space-x-2">
                        <span class="bg-green-100 text-green-800 px-2 py-1 rounded font-mono">GET</span>
                        <span class="text-gray-600 dark:text-gray-300">/api/banners/position/{position}</span>
                    </div>
                    <div class="flex items-center justify-center space-x-2">
                        <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded font-mono">POST</span>
                        <span class="text-gray-600 dark:text-gray-300">/api/banners/{id}/click</span>
                    </div>
                    <div class="flex items-center justify-center space-x-2">
                        <span class="bg-green-100 text-green-800 px-2 py-1 rounded font-mono">GET</span>
                        <span class="text-gray-600 dark:text-gray-300">/api/banners/positions</span>
                    </div>
                    <div class="flex items-center justify-center space-x-2">
                        <span class="bg-green-100 text-green-800 px-2 py-1 rounded font-mono">GET</span>
                        <span class="text-gray-600 dark:text-gray-300">/api/banners/device-types</span>
                    </div>
                </div>
            </div>
            
            <div class="flex items-center justify-center gap-4">
                <a href="{{ route('admin.banners.analytics') }}" class="primary-button">
                    📊 View Analytics
                </a>
                <a href="/api/banners" class="secondary-button" target="_blank">
                    🧪 Test API
                </a>
            </div>
        </div>
    </div>
</x-admin::layouts>
