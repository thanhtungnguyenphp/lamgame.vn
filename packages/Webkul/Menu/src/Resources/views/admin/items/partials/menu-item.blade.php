@php
    $hasChildren = $item->children && $item->children->count() > 0;
    $indent = str_repeat('  ', $level);
@endphp

<div class="border rounded-lg p-3 bg-white dark:bg-gray-800 hover:shadow-md transition-shadow" 
     style="margin-left: {{ $level * 20 }}px">
    <div class="flex items-center justify-between">
        <!-- Left Section: Drag Handle + Item Info -->
        <div class="flex items-center flex-1">
            <!-- Drag Handle -->
            <div class="drag-handle cursor-grab hover:cursor-grabbing mr-3 text-gray-400 hover:text-gray-600">
                <i class="icon-drag text-lg"></i>
            </div>

            <!-- Toggle Children Button -->
            @if($hasChildren)
                <button class="toggle-children mr-3 text-gray-500 hover:text-gray-700 transition-colors"
                        onclick="toggleChildren({{ $item->id }})">
                    <i class="icon-arrow-down text-xs"></i>
                </button>
            @else
                <div class="w-6 mr-3"></div>
            @endif

            <!-- Item Info -->
            <div class="flex-1">
                <div class="flex items-center gap-2">
                    @if($item->icon)
                        <i class="{{ $item->icon }} text-sm text-gray-500"></i>
                    @endif
                    <span class="font-medium text-gray-800 dark:text-white">{{ $item->title }}</span>
                </div>
                <div class="text-sm text-gray-500 mt-1">
                    <span class="mr-3">→ {{ $item->url }}</span>
                    @if($item->target == '_blank')
                        <span class="text-xs bg-blue-100 text-blue-600 px-2 py-0.5 rounded">Opens in new tab</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Section: Actions -->
        <div class="flex items-center gap-2 ml-4">
            <!-- Add Child Button -->
            <a href="{{ route('admin.menu.items.create', ['menu_id' => $item->menu_id, 'parent_id' => $item->id]) }}" 
               class="text-green-600 hover:text-green-800 transition-colors"
               title="Add sub-menu item">
                <i class="icon-plus text-sm"></i>
            </a>

            <!-- Edit Button -->
            <a href="{{ route('admin.menu.items.edit', $item->id) }}" 
               class="text-blue-600 hover:text-blue-800 transition-colors"
               title="Edit menu item">
                <i class="icon-edit text-sm"></i>
            </a>

            <!-- Delete Button -->
            <form method="POST" action="{{ route('admin.menu.items.destroy', $item->id) }}" 
                  onsubmit="return confirm('Are you sure you want to delete this menu item? This will also delete all its sub-items.')"
                  class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="text-red-600 hover:text-red-800 transition-colors"
                        title="Delete menu item">
                    <i class="icon-delete text-sm"></i>
                </button>
            </form>
        </div>
    </div>

    <!-- Children Items -->
    @if($hasChildren)
        <div class="children-container mt-3 pl-6 border-l border-gray-200 dark:border-gray-600" 
             id="children-{{ $item->id }}">
            @foreach($item->children->sortBy('sort_order') as $child)
                <div class="menu-item mb-2" data-id="{{ $child->id }}" data-parent-id="{{ $item->id }}">
                    @include('menu::admin.items.partials.menu-item', ['item' => $child, 'level' => $level + 1])
                </div>
            @endforeach
        </div>
    @endif
</div>

<script>
function toggleChildren(itemId) {
    const childrenContainer = document.getElementById('children-' + itemId);
    const toggleButton = event.target.closest('.toggle-children');
    
    if (childrenContainer.style.display === 'none') {
        childrenContainer.style.display = 'block';
        toggleButton.innerHTML = '<i class="icon-arrow-down text-xs"></i>';
    } else {
        childrenContainer.style.display = 'none';
        toggleButton.innerHTML = '<i class="icon-arrow-right text-xs"></i>';
    }
}
</script>
