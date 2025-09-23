<x-admin::layouts>
    <x-slot:title>
        Manage Menu Items - {{ $menu->name }}
    </x-slot>

    <!-- Page Content -->
    <div class="flex items-center justify-between gap-4 max-sm:flex-wrap">
        <div>
            <p class="text-xl font-bold text-gray-800 dark:text-white">
                Menu Items: {{ $menu->name }}
            </p>
            <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">
                Channel: {{ $menu->channel->name }}
            </p>
        </div>

        <div class="flex items-center gap-x-2.5">
            <!-- Add Menu Item Button -->
            <a href="{{ route('admin.menu.items.create', ['menu_id' => $menu->id]) }}" 
               class="primary-button">
                <i class="icon-plus text-xs"></i>
                Add Menu Item
            </a>

            <!-- Back Button -->
            <a href="{{ route('admin.menu.index') }}" 
               class="transparent-button hover:bg-gray-200 dark:text-white dark:hover:bg-gray-800">
                Back to Menus
            </a>
        </div>
    </div>

    <!-- Menu Items Management -->
    <div class="mt-3.5 flex gap-2.5 max-xl:flex-wrap">
        <!-- Left Section - Menu Items List -->
        <div class="flex flex-1 flex-col gap-2 max-xl:flex-auto">
            <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
                <div class="flex items-center justify-between mb-4">
                    <p class="text-base font-semibold text-gray-800 dark:text-white">
                        Menu Structure
                    </p>
                    <div class="flex items-center gap-2">
                        <button id="expand-all" class="text-sm text-blue-600 hover:text-blue-800">
                            Expand All
                        </button>
                        <span class="text-gray-300">|</span>
                        <button id="collapse-all" class="text-sm text-blue-600 hover:text-blue-800">
                            Collapse All
                        </button>
                    </div>
                </div>

                @if($menuItems->count() > 0)
                    <div id="menu-items-list" class="space-y-2">
                        @foreach($menuItems as $item)
                            <div class="menu-item" data-id="{{ $item->id }}" data-parent-id="">
                                @include('menu::admin.items.partials.menu-item', ['item' => $item, 'level' => 0])
                            </div>
                        @endforeach
                    </div>

                    <!-- Instructions -->
                    <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                        <h4 class="font-semibold text-blue-800 dark:text-blue-200 mb-2">
                            <i class="icon-information text-sm mr-1"></i>
                            How to manage menu items:
                        </h4>
                        <ul class="text-sm text-blue-700 dark:text-blue-300 space-y-1">
                            <li>• Click and drag menu items to reorder them</li>
                            <li>• Drag an item onto another item to make it a sub-menu</li>
                            <li>• Use the edit button to modify menu item details</li>
                            <li>• Use the delete button to remove menu items</li>
                        </ul>
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="icon-menu text-6xl text-gray-300 mb-4 mx-auto"></div>
                        <p class="text-lg font-medium text-gray-600 dark:text-gray-400 mb-2">
                            No menu items yet
                        </p>
                        <p class="text-sm text-gray-500 dark:text-gray-500 mb-4">
                            Start building your menu by adding some items
                        </p>
                        <a href="{{ route('admin.menu.items.create', ['menu_id' => $menu->id]) }}" 
                           class="primary-button">
                            <i class="icon-plus text-xs mr-1"></i>
                            Add First Menu Item
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Right Section - Menu Preview & Actions -->
        <div class="flex w-[360px] max-w-full flex-col gap-2">
            <!-- Menu Preview -->
            <x-admin::accordion>
                <x-slot:header>
                    <p class="p-2.5 text-base font-semibold text-gray-800 dark:text-white">
                        <i class="icon-eye text-sm mr-1"></i>
                        Live Preview
                    </p>
                </x-slot>

                <x-slot:content>
                    <div class="space-y-1">
                        @if($menuItems->count() > 0)
                            @foreach($menuItems as $item)
                                @include('menu::admin.items.partials.preview-item', ['item' => $item, 'level' => 0])
                            @endforeach
                        @else
                            <p class="text-gray-500 text-sm italic">No menu items to preview</p>
                        @endif
                    </div>
                </x-slot>
            </x-admin::accordion>

            <!-- Menu Statistics -->
            <x-admin::accordion>
                <x-slot:header>
                    <p class="p-2.5 text-base font-semibold text-gray-800 dark:text-white">
                        <i class="icon-statistics text-sm mr-1"></i>
                        Statistics
                    </p>
                </x-slot>

                <x-slot:content>
                    @php
                        $totalItems = $menu->allMenuItems->count();
                        $topLevelItems = $menuItems->count();
                        $subItems = $totalItems - $topLevelItems;
                        $maxDepth = 0;
                        
                        function calculateDepth($items, $currentDepth = 0) {
                            $maxDepth = $currentDepth;
                            foreach ($items as $item) {
                                if ($item->children->count() > 0) {
                                    $childDepth = calculateDepth($item->children, $currentDepth + 1);
                                    $maxDepth = max($maxDepth, $childDepth);
                                }
                            }
                            return $maxDepth;
                        }
                        
                        $maxDepth = calculateDepth($menuItems);
                    @endphp
                    
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span>Total Items:</span>
                            <span class="font-medium">{{ $totalItems }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Top Level:</span>
                            <span class="font-medium">{{ $topLevelItems }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Sub Items:</span>
                            <span class="font-medium">{{ $subItems }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Max Depth:</span>
                            <span class="font-medium">{{ $maxDepth + 1 }}</span>
                        </div>
                    </div>
                </x-slot>
            </x-admin::accordion>
        </div>
    </div>

    <style>
        .primary-button {
            @apply bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded transition inline-flex items-center;
        }
        
        .transparent-button {
            @apply bg-transparent border border-gray-300 hover:bg-gray-200 text-gray-700 font-semibold py-2 px-4 rounded transition;
        }
        
        .box-shadow {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        .menu-item {
            transition: all 0.2s ease;
        }

        .menu-item.dragging {
            opacity: 0.5;
            transform: rotate(5deg);
        }

        .menu-item.drag-over {
            border-left: 3px solid #3b82f6;
            background-color: rgba(59, 130, 246, 0.05);
        }

        .sortable-ghost {
            opacity: 0.4;
        }

        .sortable-chosen {
            cursor: grabbing;
        }
    </style>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Expand/Collapse functionality
            document.getElementById('expand-all').addEventListener('click', function() {
                document.querySelectorAll('.children-container').forEach(container => {
                    container.style.display = 'block';
                });
                document.querySelectorAll('.toggle-children').forEach(btn => {
                    btn.innerHTML = '<i class="icon-arrow-down text-xs"></i>';
                });
            });

            document.getElementById('collapse-all').addEventListener('click', function() {
                document.querySelectorAll('.children-container').forEach(container => {
                    container.style.display = 'none';
                });
                document.querySelectorAll('.toggle-children').forEach(btn => {
                    btn.innerHTML = '<i class="icon-arrow-right text-xs"></i>';
                });
            });

            // Initialize sortable for nested menu items
            initSortable();
        });

        function initSortable() {
            const menuItemsList = document.getElementById('menu-items-list');
            if (!menuItemsList) return;

            new Sortable(menuItemsList, {
                group: 'nested',
                animation: 150,
                fallbackOnBody: true,
                swapThreshold: 0.65,
                draggable: '.menu-item',
                handle: '.drag-handle',
                onEnd: function(evt) {
                    updateMenuOrder();
                }
            });

            // Initialize sortable for all children containers
            document.querySelectorAll('.children-container').forEach(container => {
                new Sortable(container, {
                    group: 'nested',
                    animation: 150,
                    fallbackOnBody: true,
                    swapThreshold: 0.65,
                    draggable: '.menu-item',
                    handle: '.drag-handle',
                    onEnd: function(evt) {
                        updateMenuOrder();
                    }
                });
            });
        }

        function updateMenuOrder() {
            const items = [];
            
            function processContainer(container, parentId = null) {
                const menuItems = container.children;
                for (let i = 0; i < menuItems.length; i++) {
                    const item = menuItems[i];
                    if (item.classList.contains('menu-item')) {
                        const itemId = item.getAttribute('data-id');
                        items.push({
                            id: parseInt(itemId),
                            parent_id: parentId || null, // Ensure null instead of undefined
                            sort_order: i
                        });
                        
                        const childrenContainer = item.querySelector('.children-container');
                        if (childrenContainer) {
                            processContainer(childrenContainer, parseInt(itemId));
                        }
                    }
                }
            }
            
            processContainer(document.getElementById('menu-items-list'));
            
            // Send AJAX request to update order
            fetch('{{ route("admin.menu.items.update-order") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ items: items })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('Menu order updated successfully');
                }
            })
            .catch(error => {
                console.error('Error updating menu order:', error);
            });
        }
    </script>
    @endpush
</x-admin::layouts>
