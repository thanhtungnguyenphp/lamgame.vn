<x-admin::layouts>
    <x-slot:title>
        Edit Menu
    </x-slot>

    <!-- Page Content -->
    <div class="flex items-center justify-between gap-4 max-sm:flex-wrap">
        <p class="text-xl font-bold text-gray-800 dark:text-white">
            Edit Menu: {{ $menu->name }}
        </p>

        <div class="flex items-center gap-x-2.5">
            <!-- Back Button -->
            <a href="{{ route('admin.menu.index') }}" 
               class="transparent-button hover:bg-gray-200 dark:text-white dark:hover:bg-gray-800">
                Back
            </a>
        </div>
    </div>

    <!-- Edit Form -->
    <x-admin::form action="{{ route('admin.menu.update', $menu->id) }}" method="PUT">
        <div class="mt-3.5 flex gap-2.5 max-xl:flex-wrap">
            <!-- Left Section -->
            <div class="flex flex-1 flex-col gap-2 max-xl:flex-auto">
                <!-- Menu Details -->
                <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
                    <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                        Menu Details
                    </p>

                    <!-- Menu Name -->
                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label class="required">
                            Menu Name
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="text"
                            name="name"
                            rules="required"
                            :value="old('name', $menu->name)"
                            :label="trans('Menu Name')"
                            placeholder="Enter menu name (e.g., Main Navigation)"
                        />

                        <x-admin::form.control-group.error control-name="name" />
                    </x-admin::form.control-group>

                    <!-- Channel -->
                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label class="required">
                            Channel
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="select"
                            name="channel_id"
                            rules="required"
                            :value="old('channel_id', $menu->channel_id)"
                            :label="trans('Channel')"
                        >
                            <option value="">Select Channel</option>
                            @foreach(core()->getAllChannels() as $channel)
                                <option value="{{ $channel->id }}" 
                                    {{ old('channel_id', $menu->channel_id) == $channel->id ? 'selected' : '' }}>
                                    {{ $channel->name }}
                                </option>
                            @endforeach
                        </x-admin::form.control-group.control>

                        <x-admin::form.control-group.error control-name="channel_id" />
                    </x-admin::form.control-group>
                </div>

                <!-- Menu Items Information -->
                <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
                    <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                        Current Menu Items
                    </p>
                    
                    @if($menu->menuItems->count() > 0)
                        <div class="space-y-2">
                            @foreach($menu->menuItems->sortBy('sort_order') as $item)
                                <div class="border rounded p-3 bg-gray-50 dark:bg-gray-800">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <span class="font-medium dark:text-white">{{ $item->title }}</span>
                                            <span class="text-gray-600 dark:text-gray-300 ml-2">→ {{ $item->url }}</span>
                                            @if($item->children && $item->children->count() > 0)
                                                <span class="text-xs bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-300 px-2 py-0.5 rounded ml-2">
                                                    {{ $item->children->count() }} sub-items
                                                </span>
                                            @endif
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            Order: {{ $item->sort_order }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-4 flex flex-col gap-2">
                            <a href="{{ route('admin.menu.items.index', $menu->id) }}" 
                               class="primary-button text-center">
                                <i class="icon-settings text-xs mr-1"></i>
                                Manage Menu Items
                            </a>
                            <p class="text-xs text-gray-500 dark:text-gray-400 text-center">
                                Add, edit, delete, and reorder menu items with drag & drop
                            </p>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="icon-menu text-4xl text-gray-300 dark:text-gray-600 mb-3 mx-auto"></div>
                            <p class="text-gray-500 dark:text-gray-400 text-center mb-4">No menu items created yet.</p>
                            <a href="{{ route('admin.menu.items.create', ['menu_id' => $menu->id]) }}" 
                               class="primary-button">
                                <i class="icon-plus text-xs mr-1"></i>
                                Add First Menu Item
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Right Section -->
            <div class="flex w-[360px] max-w-full flex-col gap-2">
                <!-- Actions -->
                <x-admin::accordion>
                    <x-slot:header>
                        <p class="p-2.5 text-base font-semibold text-gray-800 dark:text-white">
                            Actions
                        </p>
                    </x-slot>

                    <x-slot:content>
                        <button type="submit" class="primary-button w-full">
                            Update Menu
                        </button>
                    </x-slot>
                </x-admin::accordion>

                <!-- Menu Statistics -->
                <x-admin::accordion>
                    <x-slot:header>
                        <p class="p-2.5 text-base font-semibold text-gray-800 dark:text-white">
                            Menu Statistics
                        </p>
                    </x-slot>

                    <x-slot:content>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span>Menu Items:</span>
                                <span class="font-medium">{{ $menu->menuItems->count() }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Created:</span>
                                <span class="font-medium">{{ $menu->created_at->format('M d, Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Last Updated:</span>
                                <span class="font-medium">{{ $menu->updated_at->format('M d, Y') }}</span>
                            </div>
                        </div>
                    </x-slot>
                </x-admin::accordion>
            </div>
        </div>
    </x-admin::form>

    <style>
        .form-input {
            @apply flex min-h-[39px] w-full rounded-md border px-3 py-2 text-sm text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 dark:focus:border-gray-400;
        }
        
        .primary-button {
            @apply bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded transition;
        }
        
        .transparent-button {
            @apply bg-transparent border border-gray-300 hover:bg-gray-200 text-gray-700 font-semibold py-2 px-4 rounded transition;
        }
        
        .box-shadow {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
    </style>
</x-admin::layouts>
