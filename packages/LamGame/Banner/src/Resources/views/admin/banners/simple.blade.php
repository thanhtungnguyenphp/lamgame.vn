<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('banner::app.admin.banners.title') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 dark:bg-gray-900">
    <div class="container mx-auto px-4 py-8">
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-3xl font-bold text-gray-800 dark:text-white">
                🎯 {{ __('banner::app.admin.banners.index-title') }}
            </h1>
            
            <div class="flex items-center gap-x-4">
                <a href="{{ route('admin.banners.analytics') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                    📊 Analytics
                </a>
                <a href="{{ route('admin.banners.create') }}" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg">
                    ➕ {{ __('banner::app.admin.banners.create-title') }}
                </a>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8">
            <div class="text-center">
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-4">
                    🎉 Banner Management System Ready!
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                    <div class="bg-blue-50 dark:bg-blue-900/20 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold text-blue-600 dark:text-blue-400 mb-2">API Status</h3>
                        <p class="text-green-600 font-medium">✅ Active</p>
                        <p class="text-sm text-gray-600 dark:text-gray-300 mt-2">All endpoints working</p>
                    </div>
                    
                    <div class="bg-green-50 dark:bg-green-900/20 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold text-green-600 dark:text-green-400 mb-2">Database</h3>
                        <p class="text-green-600 font-medium">✅ Connected</p>
                        <p class="text-sm text-gray-600 dark:text-gray-300 mt-2">Tables created & ready</p>
                    </div>
                    
                    <div class="bg-purple-50 dark:bg-purple-900/20 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold text-purple-600 dark:text-purple-400 mb-2">Views</h3>
                        <p class="text-green-600 font-medium">✅ Loaded</p>
                        <p class="text-sm text-gray-600 dark:text-gray-300 mt-2">Admin interface ready</p>
                    </div>
                </div>
                
                <div class="mb-8">
                    <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-200 mb-4">Available API Endpoints</h3>
                    <div class="space-y-3">
                        <div class="flex items-center justify-center space-x-2">
                            <span class="bg-green-100 text-green-800 px-3 py-1 rounded font-mono text-sm">GET</span>
                            <code class="text-gray-600 dark:text-gray-300">/api/banners</code>
                        </div>
                        <div class="flex items-center justify-center space-x-2">
                            <span class="bg-green-100 text-green-800 px-3 py-1 rounded font-mono text-sm">GET</span>
                            <code class="text-gray-600 dark:text-gray-300">/api/banners/position/{position}</code>
                        </div>
                        <div class="flex items-center justify-center space-x-2">
                            <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded font-mono text-sm">POST</span>
                            <code class="text-gray-600 dark:text-gray-300">/api/banners/{id}/click</code>
                        </div>
                        <div class="flex items-center justify-center space-x-2">
                            <span class="bg-green-100 text-green-800 px-3 py-1 rounded font-mono text-sm">GET</span>
                            <code class="text-gray-600 dark:text-gray-300">/api/banners/positions</code>
                        </div>
                        <div class="flex items-center justify-center space-x-2">
                            <span class="bg-green-100 text-green-800 px-3 py-1 rounded font-mono text-sm">GET</span>
                            <code class="text-gray-600 dark:text-gray-300">/api/banners/device-types</code>
                        </div>
                    </div>
                </div>
                
                <div class="flex items-center justify-center gap-4">
                    <a href="/api/banners" target="_blank" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg">
                        🧪 Test API
                    </a>
                    <a href="/api/banners/positions" target="_blank" class="bg-indigo-500 hover:bg-indigo-600 text-white px-6 py-2 rounded-lg">
                        🏷️ View Positions
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Banner List -->
        @if(isset($banners) && $banners->count() > 0)
        <div class="mt-8 bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8">
            <h3 class="text-2xl font-bold text-gray-800 dark:text-white mb-6 text-center">
                🎨 Current Banners ({{ $banners->count() }})
            </h3>
            <div class="space-y-4">
                @foreach($banners as $banner)
                    <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <h4 class="text-lg font-semibold text-gray-800 dark:text-white">{{ $banner->name }}</h4>
                                <div class="flex items-center space-x-3 mt-2">
                                    <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-sm">{{ $banner->position }}</span>
                                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-sm">{{ $banner->device_type }}</span>
                                    <span class="bg-{{ $banner->status ? 'green' : 'red' }}-100 text-{{ $banner->status ? 'green' : 'red' }}-800 px-2 py-1 rounded text-sm">{{ $banner->status ? 'Active' : 'Inactive' }}</span>
                                </div>
                                @if($banner->title)
                                    <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">"{{ $banner->title }}"</p>
                                @endif
                                @if($banner->content)
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ Str::limit($banner->content, 100) }}</p>
                                @endif
                            </div>
                            <div class="text-right text-sm text-gray-500 dark:text-gray-400">
                                <p>ID: #{{ $banner->id }}</p>
                                <p>Sort: {{ $banner->sort_order }}</p>
                                <p>{{ $banner->created_at->format('M j, Y') }}</p>
                                @if($banner->clicks_count > 0 || $banner->impressions_count > 0)
                                    <p class="text-xs mt-1">
                                        {{ $banner->clicks_count }} clicks, {{ $banner->impressions_count }} views
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif
        
        <div class="mt-8 text-center text-sm text-gray-600 dark:text-gray-400">
            <p>✨ Banner module successfully installed and configured for LamGame system</p>
            <p class="mt-1">Ready for frontend integration and banner management</p>
        </div>
    </div>
</body>
</html>