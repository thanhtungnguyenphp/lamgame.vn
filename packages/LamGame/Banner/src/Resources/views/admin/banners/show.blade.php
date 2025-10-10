<x-admin::layouts>
    <x-slot:title>
        @lang('banner::app.admin.banners.show.title')
    </x-slot>

    {!! view_render_event('bagisto.admin.banners.show.before', ['banner' => $banner]) !!}

    <div class="flex items-center justify-between gap-4 max-sm:flex-wrap">
        <p class="text-xl font-bold text-gray-800 dark:text-white">
            @lang('banner::app.admin.banners.show.title'): {{ $banner->name }}
        </p>

        <div class="flex items-center gap-x-2.5">
            <!-- Back Button -->
            <a
                href="{{ route('admin.banners.index') }}"
                class="transparent-button hover:bg-gray-200 dark:text-white dark:hover:bg-gray-800"
            >
                @lang('banner::app.admin.banners.show.back-btn')
            </a>

            <!-- Edit Button -->
            <a
                href="{{ route('admin.banners.edit', $banner->id) }}"
                class="primary-button"
            >
                @lang('banner::app.admin.banners.show.edit-btn')
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="mt-3.5 flex gap-2.5 max-xl:flex-wrap">
        <!-- Left Section - Banner Details -->
        <div class="flex flex-1 flex-col gap-2 max-xl:flex-auto">

            <!-- Banner Preview -->
            <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
                <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                    @lang('banner::app.admin.banners.show.preview')
                </p>

                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                    @if($banner->image)
                        <img 
                            src="{{ $banner->image_url }}" 
                            alt="{{ $banner->image_alt ?? $banner->name }}" 
                            class="max-w-full h-auto mx-auto rounded shadow-md max-h-64 object-contain"
                        />
                    @else
                        <div class="flex flex-col items-center justify-center py-12">
                            <div class="w-16 h-16 bg-gray-200 rounded-full flex items-center justify-center mb-4">
                                <span class="text-2xl">🖼️</span>
                            </div>
                            <p class="text-gray-500">@lang('banner::app.admin.banners.show.no-image')</p>
                        </div>
                    @endif
                </div>

                @if($banner->title || $banner->content)
                    <div class="mt-4 p-4 bg-gray-50 rounded-lg dark:bg-gray-800">
                        @if($banner->title)
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">
                                {{ $banner->title }}
                            </h3>
                        @endif
                        @if($banner->content)
                            <p class="text-gray-600 dark:text-gray-300">
                                {{ $banner->content }}
                            </p>
                        @endif
                        @if($banner->link)
                            <a 
                                href="{{ $banner->link }}" 
                                target="{{ $banner->target ?? '_self' }}"
                                class="inline-flex items-center mt-2 text-blue-600 hover:text-blue-800"
                            >
                                @lang('banner::app.admin.banners.show.view-link')
                                <span class="ml-1">→</span>
                            </a>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Banner Details -->
            <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
                <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                    @lang('banner::app.admin.banners.show.details')
                </p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Basic Information -->
                    <div class="space-y-3">
                        <div>
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                @lang('banner::app.admin.banners.show.name')
                            </label>
                            <p class="text-gray-900 dark:text-white">{{ $banner->name }}</p>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                @lang('banner::app.admin.banners.show.type')
                            </label>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ ucfirst($banner->type) }}
                            </span>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                @lang('banner::app.admin.banners.show.position')
                            </label>
                            <p class="text-gray-900 dark:text-white">{{ ucwords(str_replace('_', ' ', $banner->position)) }}</p>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                @lang('banner::app.admin.banners.show.device-type')
                            </label>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $banner->device_type == 'mobile' ? 'bg-green-100 text-green-800' : 'bg-purple-100 text-purple-800' }}">
                                {{ ucfirst($banner->device_type) }}
                            </span>
                        </div>
                    </div>

                    <!-- Settings -->
                    <div class="space-y-3">
                        <div>
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                @lang('banner::app.admin.banners.show.status')
                            </label>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $banner->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $banner->status ? 'Active' : 'Inactive' }}
                            </span>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                @lang('banner::app.admin.banners.show.sort-order')
                            </label>
                            <p class="text-gray-900 dark:text-white">{{ $banner->sort_order ?? 0 }}</p>
                        </div>

                        @if($banner->start_date)
                            <div>
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                    @lang('banner::app.admin.banners.show.start-date')
                                </label>
                                <p class="text-gray-900 dark:text-white">
                                    {{ \Carbon\Carbon::parse($banner->start_date)->format('M d, Y') }}
                                </p>
                            </div>
                        @endif

                        @if($banner->end_date)
                            <div>
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                    @lang('banner::app.admin.banners.show.end-date')
                                </label>
                                <p class="text-gray-900 dark:text-white">
                                    {{ \Carbon\Carbon::parse($banner->end_date)->format('M d, Y') }}
                                </p>
                            </div>
                        @endif

                        <div>
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                @lang('banner::app.admin.banners.show.created-at')
                            </label>
                            <p class="text-gray-900 dark:text-white">
                                {{ $banner->created_at->format('M d, Y H:i') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Section - Analytics -->
        <div class="flex w-[360px] max-w-full flex-col gap-2 max-sm:w-full">

            <!-- Analytics Summary -->
            <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
                <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                    @lang('banner::app.admin.banners.show.analytics')
                </p>

                <div class="space-y-4">
                    <!-- Impressions -->
                    <div class="text-center p-3 bg-blue-50 rounded-lg dark:bg-blue-900/20">
                        <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">
                            {{ $banner->impressions_count ?? 0 }}
                        </div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">
                            @lang('banner::app.admin.banners.show.impressions')
                        </div>
                    </div>

                    <!-- Clicks -->
                    <div class="text-center p-3 bg-green-50 rounded-lg dark:bg-green-900/20">
                        <div class="text-2xl font-bold text-green-600 dark:text-green-400">
                            {{ $banner->clicks_count ?? 0 }}
                        </div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">
                            @lang('banner::app.admin.banners.show.clicks')
                        </div>
                    </div>

                    <!-- CTR -->
                    <div class="text-center p-3 bg-purple-50 rounded-lg dark:bg-purple-900/20">
                        <div class="text-2xl font-bold text-purple-600 dark:text-purple-400">
                            @if(($banner->impressions_count ?? 0) > 0)
                                {{ number_format((($banner->clicks_count ?? 0) / $banner->impressions_count) * 100, 1) }}%
                            @else
                                0%
                            @endif
                        </div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">
                            @lang('banner::app.admin.banners.show.ctr')
                        </div>
                    </div>
                </div>

                @if(isset($analytics) && !empty($analytics))
                    <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            @lang('banner::app.admin.banners.show.recent-activity')
                        </h4>
                        <div class="space-y-2 text-sm text-gray-600 dark:text-gray-400">
                            @if(isset($analytics['last_click']))
                                <p>Last click: {{ $analytics['last_click'] }}</p>
                            @endif
                            @if(isset($analytics['today_impressions']))
                                <p>Today: {{ $analytics['today_impressions'] }} views</p>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            <!-- Mobile Performance -->
            <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
                <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                    @lang('banner::app.admin.banners.show.mobile-performance')
                </p>

                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Mobile Optimized</span>
                        <span class="text-sm font-medium {{ in_array($banner->device_type, ['mobile', 'all']) ? 'text-green-600' : 'text-orange-600' }}">
                            {{ in_array($banner->device_type, ['mobile', 'all']) ? 'Yes' : 'Limited' }}
                        </span>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Touch Friendly</span>
                        <span class="text-sm font-medium {{ $banner->link ? 'text-green-600' : 'text-gray-500' }}">
                            {{ $banner->link ? 'Yes' : 'N/A' }}
                        </span>
                    </div>

                    @if($banner->image)
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Image Alt Text</span>
                            <span class="text-sm font-medium {{ $banner->image_alt ? 'text-green-600' : 'text-orange-600' }}">
                                {{ $banner->image_alt ? 'Set' : 'Missing' }}
                            </span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Actions -->
            <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
                <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                    @lang('banner::app.admin.banners.show.actions')
                </p>

                <div class="space-y-2">
                    <a
                        href="{{ route('admin.banners.edit', $banner->id) }}"
                        class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                    >
                        @lang('banner::app.admin.banners.show.edit-banner')
                    </a>

                    @if($banner->link)
                        <a
                            href="{{ $banner->link }}"
                            target="{{ $banner->target ?? '_self' }}"
                            class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700"
                        >
                            @lang('banner::app.admin.banners.show.visit-link')
                        </a>
                    @endif

                    <form
                        action="{{ route('admin.banners.destroy', $banner->id) }}"
                        method="POST"
                        onsubmit="return confirm('@lang('banner::app.admin.banners.show.delete-confirmation')')"
                    >
                        @csrf
                        @method('DELETE')
                        <button
                            type="submit"
                            class="w-full inline-flex justify-center items-center px-4 py-2 border border-red-300 rounded-md shadow-sm text-sm font-medium text-red-700 bg-red-50 hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 dark:bg-red-900/20 dark:text-red-400 dark:border-red-700 dark:hover:bg-red-900/30"
                        >
                            @lang('banner::app.admin.banners.show.delete-banner')
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {!! view_render_event('bagisto.admin.banners.show.after', ['banner' => $banner]) !!}

    @push('styles')
        <style>
            /* Mobile-first responsive styles */
            @media (max-width: 768px) {
                .banner-preview img {
                    max-height: 200px;
                }
                
                .analytics-card {
                    margin-bottom: 1rem;
                }
                
                .action-buttons {
                    gap: 0.75rem;
                }
            }
            
            /* Touch-friendly buttons on mobile */
            @media (max-width: 768px) {
                .action-buttons a, .action-buttons button {
                    min-height: 48px;
                    font-size: 16px;
                }
            }
        </style>
    @endpush

</x-admin::layouts>