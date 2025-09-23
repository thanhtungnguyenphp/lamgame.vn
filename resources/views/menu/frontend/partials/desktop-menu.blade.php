<!-- Desktop Menu -->
<nav class="nav" id="nav-menu" role="navigation">
    @php
        $menuItems = DB::table('menu_items')
            ->where('menu_id', 1)
            ->orderBy('sort_order')
            ->get();
    @endphp
    
    @foreach($menuItems as $item)
    <div class="nav-item">
        <a href="{{ $item->url }}" 
           target="{{ $item->target ?? '_self' }}"
           class="nav-link"
           >
           {{ $item->title }}
        </a>
    </div>
    @endforeach
</nav>
