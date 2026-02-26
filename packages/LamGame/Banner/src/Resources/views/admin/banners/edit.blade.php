<x-admin::layouts>
    <x-slot:title>
        @lang('banner::app.admin.banners.edit.title')
    </x-slot>

    <form
        action="{{ route('admin.banners.update', $banner->id) }}"
        method="POST"
        enctype="multipart/form-data"
    >
        @csrf
        @method('PUT')

        <div class="flex items-center justify-between gap-4 max-sm:flex-wrap">
            <p class="text-xl font-bold text-gray-800 dark:text-white">
                @lang('banner::app.admin.banners.edit.title')
            </p>

            <div class="flex items-center gap-x-2.5">
                <a
                    href="{{ route('admin.banners.index') }}"
                    class="transparent-button hover:bg-gray-200 dark:text-white dark:hover:bg-gray-800"
                >
                    @lang('banner::app.admin.banners.edit.back-btn')
                </a>

                <button type="submit" class="primary-button">
                    @lang('banner::app.admin.banners.edit.save-btn')
                </button>
            </div>
        </div>

        <!-- Main Content -->
        <div class="mt-3.5 flex gap-2.5 max-xl:flex-wrap">
            <!-- Left Section -->
            <div class="flex flex-1 flex-col gap-2 max-xl:flex-auto">

                <!-- General Information -->
                <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
                    <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                        @lang('banner::app.admin.banners.edit.general')
                    </p>

                    <!-- Banner Name -->
                    <div class="mb-4">
                        <label class="mb-1.5 flex items-center gap-1 text-xs font-medium text-gray-800 dark:text-white">
                            @lang('banner::app.admin.banners.edit.name') <span class="text-red-600">*</span>
                        </label>
                        <input
                            type="text"
                            name="name"
                            value="{{ old('name', $banner->name) }}"
                            required
                            class="w-full rounded-md border px-3 py-2.5 text-sm text-gray-600 transition-all hover:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300"
                        />
                        @error('name') <p class="mt-1 text-xs italic text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <!-- Banner Type -->
                    <div class="mb-4">
                        <label class="mb-1.5 flex items-center gap-1 text-xs font-medium text-gray-800 dark:text-white">
                            @lang('banner::app.admin.banners.edit.type') <span class="text-red-600">*</span>
                        </label>
                        <select name="type" required class="custom-select w-full rounded-md border bg-white px-3 py-2.5 text-sm text-gray-600 transition-all hover:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
                            @foreach(['image' => 'Image', 'html' => 'HTML', 'video' => 'Video'] as $val => $label)
                                <option value="{{ $val }}" {{ old('type', $banner->type) == $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Position -->
                    <div class="mb-4">
                        <label class="mb-1.5 flex items-center gap-1 text-xs font-medium text-gray-800 dark:text-white">
                            @lang('banner::app.admin.banners.edit.position') <span class="text-red-600">*</span>
                        </label>
                        <select name="position" required class="custom-select w-full rounded-md border bg-white px-3 py-2.5 text-sm text-gray-600 transition-all hover:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
                            @foreach($positions as $key => $position)
                                <option value="{{ $key }}" {{ old('position', $banner->position) == $key ? 'selected' : '' }}>{{ $position }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Device Type -->
                    <div class="mb-4">
                        <label class="mb-1.5 flex items-center gap-1 text-xs font-medium text-gray-800 dark:text-white">
                            @lang('banner::app.admin.banners.edit.device-type') <span class="text-red-600">*</span>
                        </label>
                        <select name="device_type" required class="custom-select w-full rounded-md border bg-white px-3 py-2.5 text-sm text-gray-600 transition-all hover:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
                            @foreach($deviceTypes as $key => $deviceType)
                                <option value="{{ $key }}" {{ old('device_type', $banner->device_type) == $key ? 'selected' : '' }}>{{ $deviceType }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Status -->
                    <div class="mb-4">
                        <label class="flex items-center gap-2">
                            <input type="hidden" name="status" value="0">
                            <input type="checkbox" name="status" value="1" {{ old('status', $banner->status) ? 'checked' : '' }}
                                   class="h-4 w-4 rounded border-gray-300 text-blue-600">
                            <span class="text-xs font-medium text-gray-800 dark:text-white">@lang('banner::app.admin.banners.edit.status')</span>
                        </label>
                    </div>
                </div>

                <!-- Content Section -->
                <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
                    <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                        @lang('banner::app.admin.banners.edit.content')
                    </p>

                    <div class="mb-4">
                        <label class="mb-1.5 text-xs font-medium text-gray-800 dark:text-white">@lang('banner::app.admin.banners.edit.banner-title')</label>
                        <input type="text" name="title" value="{{ old('title', $banner->title) }}"
                               class="w-full rounded-md border px-3 py-2.5 text-sm text-gray-600 transition-all hover:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300" />
                    </div>

                    <div class="mb-4">
                        <label class="mb-1.5 text-xs font-medium text-gray-800 dark:text-white">@lang('banner::app.admin.banners.edit.banner-content')</label>
                        <textarea name="content" rows="4"
                                  class="w-full rounded-md border px-3 py-2.5 text-sm text-gray-600 transition-all hover:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">{{ old('content', $banner->content) }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label class="mb-1.5 text-xs font-medium text-gray-800 dark:text-white">@lang('banner::app.admin.banners.edit.link')</label>
                        <input type="url" name="link" value="{{ old('link', $banner->link) }}"
                               class="w-full rounded-md border px-3 py-2.5 text-sm text-gray-600 transition-all hover:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300" />
                    </div>

                    <div class="mb-4">
                        <label class="mb-1.5 text-xs font-medium text-gray-800 dark:text-white">@lang('banner::app.admin.banners.edit.target')</label>
                        <select name="target" class="custom-select w-full rounded-md border bg-white px-3 py-2.5 text-sm text-gray-600 transition-all hover:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
                            <option value="_self" {{ old('target', $banner->target) == '_self' ? 'selected' : '' }}>Same Window</option>
                            <option value="_blank" {{ old('target', $banner->target) == '_blank' ? 'selected' : '' }}>New Window</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Right Section -->
            <div class="flex w-[360px] max-w-full flex-col gap-2 max-sm:w-full">

                <!-- Settings -->
                <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
                    <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                        @lang('banner::app.admin.banners.edit.settings')
                    </p>

                    <div class="mb-4">
                        <label class="mb-1.5 text-xs font-medium text-gray-800 dark:text-white">@lang('banner::app.admin.banners.edit.sort-order')</label>
                        <input type="number" name="sort_order" value="{{ old('sort_order', $banner->sort_order ?? 0) }}" min="0"
                               class="w-full rounded-md border px-3 py-2.5 text-sm text-gray-600 transition-all hover:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300" />
                    </div>

                    <div class="mb-4">
                        <label class="mb-1.5 text-xs font-medium text-gray-800 dark:text-white">@lang('banner::app.admin.banners.edit.start-date')</label>
                        <input type="date" name="start_date" value="{{ old('start_date', $banner->start_date ? $banner->start_date->format('Y-m-d') : '') }}"
                               class="w-full rounded-md border px-3 py-2.5 text-sm text-gray-600 transition-all hover:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300" />
                    </div>

                    <div class="mb-4">
                        <label class="mb-1.5 text-xs font-medium text-gray-800 dark:text-white">@lang('banner::app.admin.banners.edit.end-date')</label>
                        <input type="date" name="end_date" value="{{ old('end_date', $banner->end_date ? $banner->end_date->format('Y-m-d') : '') }}"
                               class="w-full rounded-md border px-3 py-2.5 text-sm text-gray-600 transition-all hover:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300" />
                    </div>
                </div>

                <!-- Media Section -->
                <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
                    <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                        @lang('banner::app.admin.banners.edit.media')
                    </p>

                    <div class="mb-4">
                        <label class="mb-1.5 text-xs font-medium text-gray-800 dark:text-white">@lang('banner::app.admin.banners.edit.image')</label>

                        @if($banner->image_url)
                            <div class="mb-2">
                                <img src="{{ $banner->image_url }}" alt="{{ $banner->image_alt }}" class="h-[120px] w-[120px] rounded object-cover" />
                                <p class="mt-1 text-xs text-gray-500">Current image. Choose a new file below to replace.</p>
                            </div>
                        @endif

                        <input type="file" name="image" accept="image/*"
                               class="w-full rounded-md border px-3 py-2.5 text-sm text-gray-600 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300" />
                        <p class="mt-1 text-xs text-gray-500">JPG, PNG, GIF, WebP (Max: 5MB)</p>
                    </div>

                    <div class="mb-4">
                        <label class="mb-1.5 text-xs font-medium text-gray-800 dark:text-white">@lang('banner::app.admin.banners.edit.image-alt')</label>
                        <input type="text" name="image_alt" value="{{ old('image_alt', $banner->image_alt) }}"
                               class="w-full rounded-md border px-3 py-2.5 text-sm text-gray-600 transition-all hover:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300" />
                    </div>
                </div>
            </div>
        </div>
    </form>

</x-admin::layouts>
