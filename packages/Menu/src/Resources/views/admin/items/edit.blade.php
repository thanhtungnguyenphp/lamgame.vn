<x-admin::layouts>
    <x-slot:title>
        Edit Menu Item - {{ $menuItem->title }}
    </x-slot>

    <!-- Page Content -->
    <div class="flex items-center justify-between gap-4 max-sm:flex-wrap">
        <div>
            <p class="text-xl font-bold text-gray-800 dark:text-white">
                Edit Menu Item: {{ $menuItem->title }}
            </p>
            <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">
                Menu: {{ $menu->name }} | Channel: {{ $menu->channel->name }}
                @if($menuItem->parent)
                    | Parent: {{ $menuItem->parent->title }}
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

    <!-- Edit Form -->
    <x-admin::form action="{{ route('admin.menu.items.update', $menuItem->id) }}" method="PUT">
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
                            :value="old('title', $menuItem->title)"
                            :label="trans('Title')"
                            placeholder="Enter menu item title"
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
                            :value="old('url', $menuItem->url)"
                            :label="trans('URL')"
                            placeholder="Enter URL"
                        />

                        <x-admin::form.control-group.error control-name="url" />
                    </x-admin::form.control-group>

                    <!-- Parent Item -->
                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label>
                            Parent Item
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="select"
                            name="parent_id"
                            :value="old('parent_id', $menuItem->parent_id)"
                            :label="trans('Parent Item')"
                        >
                            <option value="">-- No Parent (Top Level) --</option>
                            @foreach($potentialParents as $potential)
                                <option value="{{ $potential->id }}" 
                                    {{ old('parent_id', $menuItem->parent_id) == $potential->id ? 'selected' : '' }}>
                                    {{ $potential->title }}
                                </option>
                            @endforeach
                        </x-admin::form.control-group.control>

                        <x-admin::form.control-group.error control-name="parent_id" />
                    </x-admin::form.control-group>

                    <!-- Sort Order -->
                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label>
                            Sort Order
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="number"
                            name="sort_order"
                            min="0"
                            :value="old('sort_order', $menuItem->sort_order)"
                            :label="trans('Sort Order')"
                        />

                        <x-admin::form.control-group.error control-name="sort_order" />
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
                            :value="old('target', $menuItem->target)"
                            :label="trans('Target')"
                        >
                            <option value="_self" {{ old('target', $menuItem->target) == '_self' ? 'selected' : '' }}>
                                Same Window (_self)
                            </option>
                            <option value="_blank" {{ old('target', $menuItem->target) == '_blank' ? 'selected' : '' }}>
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
                            :value="old('icon', $menuItem->icon)"
                            :label="trans('Icon')"
                            placeholder="e.g., icon-home, fas fa-home"
                        />

                        <x-admin::form.control-group.error control-name="icon" />
                        <p class="text-xs text-gray-500 mt-1">Optional icon class</p>
                    </x-admin::form.control-group>
                </div>

                @if($menuItem->children && $menuItem->children->count() > 0)
                <!-- Child Items Information -->
                <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
                    <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                        Child Items ({{ $menuItem->children->count() }})
                    </p>
                    
                    <div class="space-y-2">
                        @foreach($menuItem->children->sortBy('sort_order') as $child)
                            <div class="flex items-center justify-between p-2 border rounded bg-gray-50 dark:bg-gray-700">
                                <div>
                                    <span class="font-medium">{{ $child->title }}</span>
                                    <span class="text-gray-500 ml-2">→ {{ $child->url }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.menu.items.edit', $child->id) }}" 
                                       class="text-blue-600 hover:text-blue-800">
                                        <i class="icon-edit text-sm"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif
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
                        <div class="space-y-2">
                            <button type="submit" class="primary-button w-full">
                                Update Menu Item
                            </button>
                            
                            <a href="{{ route('admin.menu.items.create', ['menu_id' => $menu->id, 'parent_id' => $menuItem->id]) }}" 
                               class="secondary-button w-full">
                                <i class="icon-plus text-xs mr-1"></i>
                                Add Child Item
                            </a>
                        </div>
                    </x-slot>
                </x-admin::accordion>

                <!-- Item Info -->
                <x-admin::accordion>
                    <x-slot:header>
                        <p class="p-2.5 text-base font-semibold text-gray-800 dark:text-white">
                            Item Information
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
                                <span>Parent:</span>
                                <span class="font-medium">
                                    {{ $menuItem->parent ? $menuItem->parent->title : 'Top Level' }}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span>Child Items:</span>
                                <span class="font-medium">{{ $menuItem->children->count() }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Created:</span>
                                <span class="font-medium">{{ $menuItem->created_at->format('M d, Y') }}</span>
                            </div>
                        </div>
                    </x-slot>
                </x-admin::accordion>

                <!-- Danger Zone -->
                <x-admin::accordion>
                    <x-slot:header>
                        <p class="p-2.5 text-base font-semibold text-red-600 dark:text-red-400">
                            <i class="icon-warning text-sm mr-1"></i>
                            Danger Zone
                        </p>
                    </x-slot>

                    <x-slot:content>
                        <div class="space-y-3">
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                Delete this menu item permanently. This action cannot be undone.
                            </p>
                            
                            @if($menuItem->children->count() > 0)
                                <div class="p-2 bg-yellow-50 dark:bg-yellow-900/20 rounded text-xs text-yellow-700 dark:text-yellow-300">
                                    <strong>Warning:</strong> This item has {{ $menuItem->children->count() }} child items. 
                                    Deleting this item will also delete all its children.
                                </div>
                            @endif
                            
                            <form method="POST" action="{{ route('admin.menu.items.destroy', $menuItem->id) }}" 
                                  onsubmit="return confirm('Are you sure you want to delete this menu item?{{ $menuItem->children->count() > 0 ? ' This will also delete all its sub-items.' : '' }}')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="danger-button w-full">
                                    <i class="icon-delete text-xs mr-1"></i>
                                    Delete Menu Item
                                </button>
                            </form>
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
        
        .secondary-button {
            @apply bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2 px-4 rounded transition text-center inline-block;
        }
        
        .danger-button {
            @apply bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded transition;
        }
        
        .transparent-button {
            @apply bg-transparent border border-gray-300 hover:bg-gray-200 text-gray-700 font-semibold py-2 px-4 rounded transition;
        }
        
        .box-shadow {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        .form-input {
            @apply flex min-h-[39px] w-full rounded-md border px-3 py-2 text-sm text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 dark:focus:border-gray-400;
        }
    </style>
</x-admin::layouts>
