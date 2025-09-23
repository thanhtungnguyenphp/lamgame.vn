{{-- Footer Menu Component --}}
@if($footerMenu && $footerMenu->menuItems->count() > 0)
<div class="footer-menu">
    <div class="footer-menu-columns">
        @foreach($footerMenu->menuItems as $parentItem)
            <div class="footer-menu-column">
                @if($parentItem->children->count() > 0)
                    {{-- Parent with children - show as column header --}}
                    <h4 class="footer-menu-title">
                        @if($parentItem->icon)
                            <i class="{{ $parentItem->icon }}"></i>
                        @endif
                        {{ $parentItem->title }}
                    </h4>
                    
                    <ul class="footer-menu-list">
                        {{-- Add parent link if it has URL --}}
                        @if($parentItem->url && $parentItem->url !== '#')
                            <li>
                                <a href="{{ $parentItem->url }}" 
                                   target="{{ $parentItem->target ?? '_self' }}"
                                   class="footer-menu-link parent-link"
                                   @if(strpos($parentItem->url, '#') === 0)
                                       onclick="scrollToSection('{{ $parentItem->url }}')"
                                   @endif>
                                    {{ $parentItem->title }}
                                </a>
                            </li>
                        @endif
                        
                        {{-- Children links --}}
                        @foreach($parentItem->children as $child)
                            <li>
                                <a href="{{ $child->url }}" 
                                   target="{{ $child->target ?? '_self' }}"
                                   class="footer-menu-link"
                                   @if(strpos($child->url, '#') === 0)
                                       onclick="scrollToSection('{{ $child->url }}')"
                                   @endif>
                                    @if($child->icon)
                                        <i class="{{ $child->icon }}"></i>
                                    @endif
                                    {{ $child->title }}
                                </a>
                            </li>
                            
                            {{-- Third level items (sub-sub menu) --}}
                            @if($child->children->count() > 0)
                                @foreach($child->children as $grandChild)
                                    <li class="footer-submenu-item">
                                        <a href="{{ $grandChild->url }}" 
                                           target="{{ $grandChild->target ?? '_self' }}"
                                           class="footer-menu-link submenu-link"
                                           @if(strpos($grandChild->url, '#') === 0)
                                               onclick="scrollToSection('{{ $grandChild->url }}')"
                                           @endif>
                                            @if($grandChild->icon)
                                                <i class="{{ $grandChild->icon }}"></i>
                                            @endif
                                            {{ $grandChild->title }}
                                        </a>
                                    </li>
                                @endforeach
                            @endif
                        @endforeach
                    </ul>
                @else
                    {{-- Single item without children --}}
                    <div class="footer-single-item">
                        <a href="{{ $parentItem->url }}" 
                           target="{{ $parentItem->target ?? '_self' }}"
                           class="footer-menu-link single-item"
                           @if(strpos($parentItem->url, '#') === 0)
                               onclick="scrollToSection('{{ $parentItem->url }}')"
                           @endif>
                            @if($parentItem->icon)
                                <i class="{{ $parentItem->icon }}"></i>
                            @endif
                            {{ $parentItem->title }}
                        </a>
                    </div>
                @endif
            </div>
        @endforeach
    </div>
</div>

<style>
/* Footer Menu Styles */
.footer-menu {
    margin-bottom: 2rem;
}

.footer-menu-columns {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 2rem;
}

.footer-menu-column {
    display: flex;
    flex-direction: column;
}

.footer-menu-title {
    color: white;
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid rgba(255, 255, 255, 0.2);
}

.footer-menu-title i {
    margin-right: 0.5rem;
}

.footer-menu-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.footer-menu-list li {
    margin-bottom: 0.5rem;
}

.footer-menu-link {
    color: rgba(255, 255, 255, 0.8);
    text-decoration: none;
    transition: color 0.3s ease;
    display: inline-flex;
    align-items: center;
}

.footer-menu-link:hover {
    color: white;
    text-decoration: underline;
}

.footer-menu-link.parent-link {
    font-weight: 500;
    color: rgba(255, 255, 255, 0.9);
    margin-bottom: 0.25rem;
}

.footer-menu-link i {
    margin-right: 0.5rem;
    width: 16px;
    text-align: center;
}

.footer-submenu-item {
    margin-left: 1rem;
    position: relative;
}

.footer-submenu-item::before {
    content: '└';
    position: absolute;
    left: -1rem;
    color: rgba(255, 255, 255, 0.5);
}

.footer-menu-link.submenu-link {
    font-size: 0.9rem;
    color: rgba(255, 255, 255, 0.7);
}

.footer-single-item {
    display: flex;
    align-items: center;
}

.footer-menu-link.single-item {
    font-size: 1.1rem;
    font-weight: 500;
    color: rgba(255, 255, 255, 0.9);
    padding: 0.5rem 0;
}

/* Responsive */
@media (max-width: 768px) {
    .footer-menu-columns {
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
    }
    
    .footer-menu-title {
        font-size: 1rem;
        margin-bottom: 0.75rem;
    }
    
    .footer-menu-link {
        font-size: 0.9rem;
    }
}

@media (max-width: 480px) {
    .footer-menu-columns {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
}
</style>
@endif
