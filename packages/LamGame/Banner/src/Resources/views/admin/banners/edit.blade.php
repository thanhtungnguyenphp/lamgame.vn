<x-admin::layouts>
    <x-slot:title>
        @lang('banner::app.admin.banners.edit.title')
    </x-slot>

    @php
        $currentLocale = core()->getRequestedLocale();
    @endphp

    {!! view_render_event('bagisto.admin.banners.edit.before', ['banner' => $banner]) !!}

    <!-- Banner Edit Form -->
    <x-admin::form
        :action="route('admin.banners.update', $banner->id)"
        enctype="multipart/form-data"
        method="PUT"
    >

        {!! view_render_event('bagisto.admin.banners.edit.edit_form_controls.before', ['banner' => $banner]) !!}

        <div class="flex items-center justify-between gap-4 max-sm:flex-wrap">
            <p class="text-xl font-bold text-gray-800 dark:text-white">
                @lang('banner::app.admin.banners.edit.title')
            </p>

            <div class="flex items-center gap-x-2.5">
                <!-- Back Button -->
                <a
                    href="{{ route('admin.banners.index') }}"
                    class="transparent-button hover:bg-gray-200 dark:text-white dark:hover:bg-gray-800"
                >
                    @lang('banner::app.admin.banners.edit.back-btn')
                </a>

                <!-- Save Button -->
                <button
                    type="submit"
                    class="primary-button"
                >
                    @lang('banner::app.admin.banners.edit.save-btn')
                </button>
            </div>
        </div>

        <!-- Main Content -->
        <div class="mt-3.5 flex gap-2.5 max-xl:flex-wrap">
            <!-- Left Section -->
            <div class="flex flex-1 flex-col gap-2 max-xl:flex-auto">

                {!! view_render_event('bagisto.admin.banners.edit.card.general.before', ['banner' => $banner]) !!}

                <!-- General Information -->
                <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
                    <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                        @lang('banner::app.admin.banners.edit.general')
                    </p>

                    <!-- Banner Name -->
                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label class="required">
                            @lang('banner::app.admin.banners.edit.name')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="text"
                            name="name"
                            :value="old('name', $banner->name ?? '')"
                            rules="required"
                            :label="trans('banner::app.admin.banners.edit.name')"
                            :placeholder="trans('banner::app.admin.banners.edit.name')"
                        />

                        <x-admin::form.control-group.error control-name="name" />
                    </x-admin::form.control-group>

                    <!-- Banner Type -->
                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label class="required">
                            @lang('banner::app.admin.banners.edit.type')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="select"
                            name="type"
                            rules="required"
                            :value="old('type', $banner->type ?? 'image')"
                            :label="trans('banner::app.admin.banners.edit.type')"
                        >
                            <option value="image" {{ (old('type', $banner->type ?? '') == 'image') ? 'selected' : '' }}>
                                @lang('banner::app.admin.banners.edit.type-image')
                            </option>
                            <option value="html" {{ (old('type', $banner->type ?? '') == 'html') ? 'selected' : '' }}>
                                @lang('banner::app.admin.banners.edit.type-html')
                            </option>
                            <option value="video" {{ (old('type', $banner->type ?? '') == 'video') ? 'selected' : '' }}>
                                @lang('banner::app.admin.banners.edit.type-video')
                            </option>
                        </x-admin::form.control-group.control>

                        <x-admin::form.control-group.error control-name="type" />
                    </x-admin::form.control-group>

                    <!-- Position -->
                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label class="required">
                            @lang('banner::app.admin.banners.edit.position')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="select"
                            name="position"
                            rules="required"
                            :value="old('position', $banner->position ?? '')"
                            :label="trans('banner::app.admin.banners.edit.position')"
                        >
                            @foreach($positions as $key => $position)
                                <option value="{{ $key }}" {{ (old('position', $banner->position ?? '') == $key) ? 'selected' : '' }}>
                                    {{ $position }}
                                </option>
                            @endforeach
                        </x-admin::form.control-group.control>

                        <x-admin::form.control-group.error control-name="position" />
                    </x-admin::form.control-group>

                    <!-- Device Type (Mobile-first consideration) -->
                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label class="required">
                            @lang('banner::app.admin.banners.edit.device-type')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="select"
                            name="device_type"
                            rules="required"
                            :value="old('device_type', $banner->device_type ?? 'all')"
                            :label="trans('banner::app.admin.banners.edit.device-type')"
                        >
                            @foreach($deviceTypes as $key => $deviceType)
                                <option value="{{ $key }}" {{ (old('device_type', $banner->device_type ?? '') == $key) ? 'selected' : '' }}>
                                    {{ $deviceType }}
                                </option>
                            @endforeach
                        </x-admin::form.control-group.control>

                        <x-admin::form.control-group.error control-name="device_type" />
                    </x-admin::form.control-group>

                    <!-- Status -->
                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label>
                            @lang('banner::app.admin.banners.edit.status')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="switch"
                            name="status"
                            :value="old('status', $banner->status ?? true)"
                            :label="trans('banner::app.admin.banners.edit.status')"
                            :checked="old('status', $banner->status ?? true)"
                        />

                        <x-admin::form.control-group.error control-name="status" />
                    </x-admin::form.control-group>
                </div>

                {!! view_render_event('bagisto.admin.banners.edit.card.general.after', ['banner' => $banner]) !!}

                {!! view_render_event('bagisto.admin.banners.edit.card.content.before', ['banner' => $banner]) !!}

                <!-- Content Section -->
                <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
                    <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                        @lang('banner::app.admin.banners.edit.content')
                    </p>

                    <!-- Title -->
                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label>
                            @lang('banner::app.admin.banners.edit.banner-title')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="text"
                            name="title"
                            :value="old('title', $banner->title ?? '')"
                            :label="trans('banner::app.admin.banners.edit.banner-title')"
                            :placeholder="trans('banner::app.admin.banners.edit.banner-title')"
                        />

                        <x-admin::form.control-group.error control-name="title" />
                    </x-admin::form.control-group>

                    <!-- Content -->
                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label>
                            @lang('banner::app.admin.banners.edit.banner-content')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="textarea"
                            name="content"
                            :value="old('content', $banner->content ?? '')"
                            :label="trans('banner::app.admin.banners.edit.banner-content')"
                            :placeholder="trans('banner::app.admin.banners.edit.banner-content')"
                        />

                        <x-admin::form.control-group.error control-name="content" />
                    </x-admin::form.control-group>

                    <!-- Link URL -->
                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label>
                            @lang('banner::app.admin.banners.edit.link')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="url"
                            name="link"
                            :value="old('link', $banner->link ?? '')"
                            :label="trans('banner::app.admin.banners.edit.link')"
                            :placeholder="trans('banner::app.admin.banners.edit.link-placeholder')"
                        />

                        <x-admin::form.control-group.error control-name="link" />
                    </x-admin::form.control-group>

                    <!-- Link Target -->
                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label>
                            @lang('banner::app.admin.banners.edit.target')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="select"
                            name="target"
                            :value="old('target', $banner->target ?? '_self')"
                            :label="trans('banner::app.admin.banners.edit.target')"
                        >
                            <option value="_self" {{ (old('target', $banner->target ?? '_self') == '_self') ? 'selected' : '' }}>
                                @lang('banner::app.admin.banners.edit.target-self')
                            </option>
                            <option value="_blank" {{ (old('target', $banner->target ?? '') == '_blank') ? 'selected' : '' }}>
                                @lang('banner::app.admin.banners.edit.target-blank')
                            </option>
                        </x-admin::form.control-group.control>

                        <x-admin::form.control-group.error control-name="target" />
                    </x-admin::form.control-group>
                </div>

                {!! view_render_event('bagisto.admin.banners.edit.card.content.after', ['banner' => $banner]) !!}

            </div>

            <!-- Right Section -->
            <div class="flex w-[360px] max-w-full flex-col gap-2 max-sm:w-full">

                {!! view_render_event('bagisto.admin.banners.edit.card.settings.before', ['banner' => $banner]) !!}

                <!-- Settings -->
                <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
                    <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                        @lang('banner::app.admin.banners.edit.settings')
                    </p>

                    <!-- Sort Order -->
                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label>
                            @lang('banner::app.admin.banners.edit.sort-order')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="number"
                            name="sort_order"
                            :value="old('sort_order', $banner->sort_order ?? 0)"
                            :label="trans('banner::app.admin.banners.edit.sort-order')"
                            min="0"
                            placeholder="0"
                        />

                        <x-admin::form.control-group.error control-name="sort_order" />
                    </x-admin::form.control-group>

                    <!-- Start Date -->
                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label>
                            @lang('banner::app.admin.banners.edit.start-date')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="date"
                            name="start_date"
                            :value="old('start_date', $banner->start_date ? \Carbon\Carbon::parse($banner->start_date)->format('Y-m-d') : '')"
                            :label="trans('banner::app.admin.banners.edit.start-date')"
                        />

                        <x-admin::form.control-group.error control-name="start_date" />
                    </x-admin::form.control-group>

                    <!-- End Date -->
                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label>
                            @lang('banner::app.admin.banners.edit.end-date')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="date"
                            name="end_date"
                            :value="old('end_date', $banner->end_date ? \Carbon\Carbon::parse($banner->end_date)->format('Y-m-d') : '')"
                            :label="trans('banner::app.admin.banners.edit.end-date')"
                        />

                        <x-admin::form.control-group.error control-name="end_date" />
                    </x-admin::form.control-group>
                </div>

                {!! view_render_event('bagisto.admin.banners.edit.card.settings.after', ['banner' => $banner]) !!}

                {!! view_render_event('bagisto.admin.banners.edit.card.media.before', ['banner' => $banner]) !!}

                <!-- Media Section -->
                <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
                    <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                        @lang('banner::app.admin.banners.edit.media')
                    </p>

                    <!-- Banner Image -->
                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label>
                            @lang('banner::app.admin.banners.edit.image')
                        </x-admin::form.control-group.label>

                        <p class="text-xs text-gray-500 mb-2">
                            @lang('banner::app.admin.banners.edit.image-size-hint')
                        </p>

                        <x-admin::media.images
                            name="image"
                            :uploaded-images="$banner->image_url ? [['id' => 'banner_image', 'url' => $banner->image_url]] : []"
                        />

                        <x-admin::form.control-group.error control-name="image" />
                    </x-admin::form.control-group>

                    <!-- Image Alt Text -->
                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label>
                            @lang('banner::app.admin.banners.edit.image-alt')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="text"
                            name="image_alt"
                            :value="old('image_alt', $banner->image_alt ?? '')"
                            :label="trans('banner::app.admin.banners.edit.image-alt')"
                            :placeholder="trans('banner::app.admin.banners.edit.image-alt-placeholder')"
                        />

                        <x-admin::form.control-group.error control-name="image_alt" />
                    </x-admin::form.control-group>
                </div>

                {!! view_render_event('bagisto.admin.banners.edit.card.media.after', ['banner' => $banner]) !!}

            </div>
        </div>

        {!! view_render_event('bagisto.admin.banners.edit.edit_form_controls.after', ['banner' => $banner]) !!}

    </x-admin::form>

    {!! view_render_event('bagisto.admin.banners.edit.after', ['banner' => $banner]) !!}

    @push('scripts')
        <script>
            // Mobile-first responsive behavior
            document.addEventListener('DOMContentLoaded', function() {
                const typeSelect = document.querySelector('select[name="type"]');
                const imageSection = document.querySelector('.media-section');
                
                if (typeSelect && imageSection) {
                    typeSelect.addEventListener('change', function() {
                        if (this.value === 'html') {
                            imageSection.style.opacity = '0.5';
                        } else {
                            imageSection.style.opacity = '1';
                        }
                    });
                }

                // Touch-friendly mobile enhancements
                if (window.innerWidth <= 768) {
                    document.querySelectorAll('input, select, textarea').forEach(function(element) {
                        element.style.fontSize = '16px'; // Prevent zoom on iOS
                        element.style.minHeight = '44px'; // Touch-friendly size
                    });
                }
            });
        </script>
    @endpush

</x-admin::layouts>