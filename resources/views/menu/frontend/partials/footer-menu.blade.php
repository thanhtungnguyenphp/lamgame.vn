<!-- Footer Menu -->
<div class="footer-section">
    <h4>Danh mục sản phẩm</h4>
    <nav class="footer-nav">
        @php
            $menuItems = DB::table('menu_items')
                ->where('menu_id', 1)
                ->orderBy('sort_order')
                ->get();
        @endphp
        
        @foreach($menuItems as $item)
        <div class="footer-nav-item">
            <a href="{{ $item->url }}" 
               target="{{ $item->target ?? '_self' }}"
               class="footer-nav-link"
               >
               {{ $item->title }}
            </a>
        </div>
        @endforeach
    </nav>
</div>

<style>
.footer-nav-item {
    margin-bottom: 0.5rem;
}

.footer-nav-link {
    color: #ccc;
    text-decoration: none;
    transition: color 0.3s;
}

.footer-nav-link:hover {
    color: white;
}
</style>
