{{-- Desktop Menu Component --}}
@if($headerMenu && $headerMenu->menuItems->count() > 0)
<nav class="nav" id="nav-menu" role="navigation">
    @foreach($headerMenu->menuItems as $item)
        <div class="nav-item{{ $item->children->count() > 0 ? ' dropdown' : '' }}">
            @if(strpos($item->url, '#') === 0)
                {{-- Anchor link for smooth scrolling --}}
                <a href="{{ $item->url }}" 
                   onclick="scrollToSection('{{ $item->url }}')"
                   class="nav-link{{ $item->children->count() > 0 ? ' dropdown-toggle' : '' }}"
                   @if($item->children->count() > 0) 
                       data-toggle="dropdown" 
                       aria-haspopup="true" 
                       aria-expanded="false" 
                   @endif>
                    @if($item->icon)
                        <i class="{{ $item->icon }}"></i>
                    @endif
                    {{ $item->title }}
                    @if($item->children->count() > 0)
                        <i class="dropdown-arrow"></i>
                    @endif
                </a>
            @else
                {{-- Regular link --}}
                <a href="{{ $item->url }}" 
                   target="{{ $item->target ?? '_self' }}"
                   class="nav-link{{ $item->children->count() > 0 ? ' dropdown-toggle' : '' }}"
                   @if($item->children->count() > 0) 
                       data-toggle="dropdown" 
                       aria-haspopup="true" 
                       aria-expanded="false" 
                   @endif>
                    @if($item->icon)
                        <i class="{{ $item->icon }}"></i>
                    @endif
                    {{ $item->title }}
                    @if($item->children->count() > 0)
                        <i class="dropdown-arrow"></i>
                    @endif
                </a>
            @endif

            {{-- Dropdown menu for children --}}
            @if($item->children->count() > 0)
                <div class="dropdown-menu" aria-labelledby="dropdown-{{ $item->id }}">
                    @foreach($item->children as $child)
                        <div class="dropdown-item-wrapper">
                            @if(strpos($child->url, '#') === 0)
                                <a href="{{ $child->url }}" 
                                   onclick="scrollToSection('{{ $child->url }}')"
                                   class="dropdown-item">
                                    @if($child->icon)
                                        <i class="{{ $child->icon }}"></i>
                                    @endif
                                    {{ $child->title }}
                                </a>
                            @else
                                <a href="{{ $child->url }}" 
                                   target="{{ $child->target ?? '_self' }}"
                                   class="dropdown-item">
                                    @if($child->icon)
                                        <i class="{{ $child->icon }}"></i>
                                    @endif
                                    {{ $child->title }}
                                </a>
                            @endif

                            {{-- Third level submenu --}}
                            @if($child->children->count() > 0)
                                <div class="dropdown-submenu">
                                    @foreach($child->children as $grandChild)
                                        @if(strpos($grandChild->url, '#') === 0)
                                            <a href="{{ $grandChild->url }}" 
                                               onclick="scrollToSection('{{ $grandChild->url }}')"
                                               class="dropdown-item">
                                                @if($grandChild->icon)
                                                    <i class="{{ $grandChild->icon }}"></i>
                                                @endif
                                                {{ $grandChild->title }}
                                            </a>
                                        @else
                                            <a href="{{ $grandChild->url }}" 
                                               target="{{ $grandChild->target ?? '_self' }}"
                                               class="dropdown-item">
                                                @if($grandChild->icon)
                                                    <i class="{{ $grandChild->icon }}"></i>
                                                @endif
                                                {{ $grandChild->title }}
                                            </a>
                                        @endif
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    @endforeach
</nav>

<style>
/* Desktop Menu Styles */
.nav {
    display: flex;
    gap: 2rem;
    align-items: center;
}

.nav-item {
    position: relative;
}

.nav-link {
    text-decoration: none;
    color: #333;
    font-weight: 500;
    transition: color 0.3s;
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

.nav-link:hover {
    color: #2c5f41;
}

.nav-link.cta {
    background: #2c5f41;
    color: white !important;
    padding: 0.5rem 1rem;
    border-radius: 5px;
}

.nav-link.cta:hover {
    background: #1e4a2e;
}

/* Dropdown Styles */
.dropdown {
    position: relative;
}

.dropdown-arrow::before {
    content: '▼';
    font-size: 0.6rem;
    margin-left: 0.25rem;
}

.dropdown-menu {
    position: absolute;
    top: 100%;
    left: 0;
    background: white;
    border: 1px solid #ddd;
    border-radius: 8px;
    box-shadow: 0 8px 16px rgba(0,0,0,0.1);
    min-width: 200px;
    z-index: 1000;
    opacity: 0;
    visibility: hidden;
    transform: translateY(-10px);
    transition: all 0.3s ease;
    padding: 0.5rem 0;
}

.dropdown:hover .dropdown-menu {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}

.dropdown-item {
    display: block;
    padding: 0.5rem 1rem;
    color: #333;
    text-decoration: none;
    transition: background-color 0.2s;
}

.dropdown-item:hover {
    background-color: #f8f9fa;
    color: #2c5f41;
}

.dropdown-item i {
    margin-right: 0.5rem;
    width: 16px;
}

/* Third level submenu */
.dropdown-item-wrapper {
    position: relative;
}

.dropdown-submenu {
    position: absolute;
    left: 100%;
    top: 0;
    background: white;
    border: 1px solid #ddd;
    border-radius: 8px;
    box-shadow: 0 8px 16px rgba(0,0,0,0.1);
    min-width: 180px;
    z-index: 1001;
    opacity: 0;
    visibility: hidden;
    transform: translateX(-10px);
    transition: all 0.3s ease;
    padding: 0.5rem 0;
}

.dropdown-item-wrapper:hover .dropdown-submenu {
    opacity: 1;
    visibility: visible;
    transform: translateX(0);
}

/* Mobile hide */
@media (max-width: 768px) {
    .nav {
        display: none;
    }
}
</style>
@endif
