<x-admin::layouts>
    <x-slot:title>
        Add Menu Item - {{ $menu->name }}
    </x-slot>

    <!-- Page Content -->
    <div class="flex items-center justify-between gap-4 max-sm:flex-wrap">
        <div>
            <p class="text-xl font-bold text-gray-800 dark:text-white">
                Add Menu Item
            </p>
            <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">
                Menu: {{ $menu->name }} | Channel: {{ $menu->channel->name }}
                @if($parentItem)
                    | Parent: {{ $parentItem->title }}
                @endif
            </p>
        </div>

        <div class="flex items-center gap-x-2.5">
            <!-- Cancel Button -->
            <a href="{{ route('admin.menu.items.index', $menu->id) }}" 
               class="transparent-button hover:bg-gray-200 dark:text-white dark:hover:bg-gray-800">
                Cancel
            </a>
        </div>
    </div>

    <!-- Create Form -->
    <x-admin::form action="{{ route('admin.menu.items.store') }}" method="POST">
        <input type="hidden" name="menu_id" value="{{ $menu->id }}">
        @if($parentItem)
            <input type="hidden" name="parent_id" value="{{ $parentItem->id }}">
        @endif

        <div class="mt-3.5 flex gap-2.5 max-xl:flex-wrap">
            <!-- Left Section -->
            <div class="flex flex-1 flex-col gap-2 max-xl:flex-auto">
                <!-- Menu Item Details -->
                <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
                    <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                        Menu Item Details
                    </p>

                    <!-- Title -->
                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label class="required">
                            Title
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="text"
                            name="title"
                            rules="required"
                            :value="old('title')"
                            :label="trans('Title')"
                            placeholder="Enter menu item title (e.g., Home, About Us)"
                        />

                        <x-admin::form.control-group.error control-name="title" />
                    </x-admin::form.control-group>

                    <!-- URL -->
                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label class="required">
                            URL
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="text"
                            name="url"
                            rules="required"
                            :value="old('url')"
                            :label="trans('URL')"
                            placeholder="Enter URL (e.g., /home, https://example.com)"
                        />

                        <x-admin::form.control-group.error control-name="url" />
                    </x-admin::form.control-group>

                    <!-- Parent Item (if not already set) -->
                    @if(!$parentItem)
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label>
                                Parent Item
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="select"
                                name="parent_id"
                                :value="old('parent_id')"
                                :label="trans('Parent Item')"
                            >
                                <option value="">-- No Parent (Top Level) --</option>
                                @foreach($potentialParents as $potential)
                                    <option value="{{ $potential->id }}" 
                                        {{ old('parent_id') == $potential->id ? 'selected' : '' }}>
                                        {{ $potential->title }}
                                    </option>
                                @endforeach
                            </x-admin::form.control-group.control>

                            <x-admin::form.control-group.error control-name="parent_id" />
                        </x-admin::form.control-group>
                    @endif

                    <!-- Sort Order -->
                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label>
                            Sort Order
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="number"
                            name="sort_order"
                            min="0"
                            :value="old('sort_order', 0)"
                            :label="trans('Sort Order')"
                            placeholder="0"
                        />

                        <x-admin::form.control-group.error control-name="sort_order" />
                        <p class="text-xs text-gray-500 mt-1">Leave empty to auto-assign the next order</p>
                    </x-admin::form.control-group>
                </div>

                <!-- Advanced Settings -->
                <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
                    <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                        Advanced Settings
                    </p>

                    <!-- Target -->
                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label>
                            Link Target
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="select"
                            name="target"
                            :value="old('target', '_self')"
                            :label="trans('Target')"
                        >
                            <option value="_self" {{ old('target', '_self') == '_self' ? 'selected' : '' }}>
                                Same Window (_self)
                            </option>
                            <option value="_blank" {{ old('target') == '_blank' ? 'selected' : '' }}>
                                New Window (_blank)
                            </option>
                        </x-admin::form.control-group.control>

                        <x-admin::form.control-group.error control-name="target" />
                    </x-admin::form.control-group>

                    <!-- Icon -->
                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label>
                            Icon Class
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="text"
                            name="icon"
                            :value="old('icon')"
                            :label="trans('Icon')"
                            placeholder="e.g., icon-home, fas fa-home"
                        />

                        <x-admin::form.control-group.error control-name="icon" />
                        <p class="text-xs text-gray-500 mt-1">Optional icon class (FontAwesome, Bagisto icons, etc.)</p>
                    </x-admin::form.control-group>
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
                            Create Menu Item
                        </button>
                    </x-slot>
                </x-admin::accordion>

                <!-- Help -->
                <x-admin::accordion>
                    <x-slot:header>
                        <p class="p-2.5 text-base font-semibold text-gray-800 dark:text-white">
                            <i class="icon-information text-sm mr-1"></i>
                            Help
                        </p>
                    </x-slot>

                    <x-slot:content>
                        <div class="space-y-3 text-sm">
                            <div>
                                <h5 class="font-medium text-gray-800 dark:text-white mb-1">URL Examples:</h5>
                                <ul class="text-gray-600 dark:text-gray-400 space-y-1">
                                    <li>• Internal: <code>/categories</code></li>
                                    <li>• External: <code>https://example.com</code></li>
                                    <li>• Route: <code>{{ url('/') }}/products</code></li>
                                </ul>
                            </div>
                            
                            <div>
                                <h5 class="font-medium text-gray-800 dark:text-white mb-1">Icon Examples:</h5>
                                <ul class="text-gray-600 dark:text-gray-400 space-y-1">
                                    <li>• Bagisto: <code>icon-home</code></li>
                                    <li>• FontAwesome: <code>fas fa-home</code></li>
                                    <li>• Custom: <code>my-custom-icon</code></li>
                                </ul>
                            </div>
                            
                            @if($parentItem)
                            <div class="p-2 bg-blue-50 dark:bg-blue-900/20 rounded">
                                <p class="text-blue-700 dark:text-blue-300 text-xs">
                                    This item will be added as a sub-menu under: <strong>{{ $parentItem->title }}</strong>
                                </p>
                            </div>
                            @endif
                        </div>
                    </x-slot>
                </x-admin::accordion>

                <!-- Menu Info -->
                <x-admin::accordion>
                    <x-slot:header>
                        <p class="p-2.5 text-base font-semibold text-gray-800 dark:text-white">
                            Menu Info
                        </p>
                    </x-slot>

                    <x-slot:content>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span>Menu:</span>
                                <span class="font-medium">{{ $menu->name }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Channel:</span>
                                <span class="font-medium">{{ $menu->channel->name }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Current Items:</span>
                                <span class="font-medium">{{ $menu->allMenuItems->count() }}</span>
                            </div>
                            @if($parentItem)
                                <div class="flex justify-between">
                                    <span>Parent:</span>
                                    <span class="font-medium">{{ $parentItem->title }}</span>
                                </div>
                            @endif
                        </div>
                    </x-slot>
                </x-admin::accordion>
            </div>
        </div>
    </x-admin::form>

    <style>
        .primary-button {
            @apply bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded transition;
        }
        
        .transparent-button {
            @apply bg-transparent border border-gray-300 hover:bg-gray-200 text-gray-700 font-semibold py-2 px-4 rounded transition;
        }
        
        .box-shadow {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        code {
            @apply bg-gray-100 text-gray-800 px-1 py-0.5 rounded text-xs;
        }

        .form-input {
            @apply flex min-h-[39px] w-full rounded-md border px-3 py-2 text-sm text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 dark:focus:border-gray-400;
        }
    </style>
</x-admin::layouts>
