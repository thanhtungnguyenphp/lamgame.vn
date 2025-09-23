@php
    $hasChildren = $item->children && $item->children->count() > 0;
    $indentStyle = $level > 0 ? 'margin-left: ' . ($level * 16) . 'px' : '';
@endphp

<div class="preview-item" style="{{ $indentStyle }}">
    <div class="flex items-center py-1 text-sm {{ $level > 0 ? 'text-gray-600' : 'text-gray-800' }}">
        @if($level > 0)
            <span class="text-gray-400 mr-1">{{ str_repeat('└─ ', 1) }}</span>
        @endif
        
        @if($item->icon)
            <i class="{{ $item->icon }} text-xs mr-2 text-gray-500"></i>
        @endif
        
        <span class="{{ $level === 0 ? 'font-medium' : 'font-normal' }}">{{ $item->title }}</span>
        
        @if($item->target == '_blank')
            <i class="icon-link-external text-xs ml-1 text-gray-400"></i>
        @endif
    </div>
    
    @if($hasChildren)
        @foreach($item->children->sortBy('sort_order') as $child)
            @include('menu::admin.items.partials.preview-item', ['item' => $child, 'level' => $level + 1])
        @endforeach
    @endif
</div>
