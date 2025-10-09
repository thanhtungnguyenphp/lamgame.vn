<x-admin::layouts>
    <x-slot:title>
        {{ __('banner::app.admin.analytics.title') }}
    </x-slot:title>
    <div class="flex items-center justify-between">
        <p class="text-xl font-bold text-gray-800 dark:text-white">
            {{ __('banner::app.admin.analytics.title') }}
        </p>
    </div>

    <div class="mt-8">
        <div class="rounded-lg bg-white p-8 text-center shadow-lg dark:bg-gray-900">
            <div class="mb-6">
                <h2 class="text-3xl font-bold text-gray-800 dark:text-white">📊 Banner Analytics Dashboard</h2>
                <p class="text-lg text-gray-600 dark:text-gray-300 mt-2">Track banner performance, clicks, impressions, and more!</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-blue-50 dark:bg-blue-900/20 p-6 rounded-lg">
                    <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">0</div>
                    <div class="text-sm text-gray-600 dark:text-gray-300">Total Banners</div>
                </div>
                <div class="bg-green-50 dark:bg-green-900/20 p-6 rounded-lg">
                    <div class="text-2xl font-bold text-green-600 dark:text-green-400">0</div>
                    <div class="text-sm text-gray-600 dark:text-gray-300">Total Impressions</div>
                </div>
                <div class="bg-purple-50 dark:bg-purple-900/20 p-6 rounded-lg">
                    <div class="text-2xl font-bold text-purple-600 dark:text-purple-400">0</div>
                    <div class="text-sm text-gray-600 dark:text-gray-300">Total Clicks</div>
                </div>
            </div>
            
            <div class="text-gray-600 dark:text-gray-300">
                <p>Analytics features are ready to be implemented</p>
                <p class="text-sm mt-2">Connect to your data visualization tools or build custom reports</p>
            </div>
        </div>
    </div>
</x-admin::layouts>
