{{-- Mobile Menu Component --}}
@if($mobileMenu && $mobileMenu->menuItems->count() > 0)
<!-- Mobile Menu Overlay -->
<div class="mobile-menu-overlay" id="mobile-menu-overlay" onclick="closeMobileMenu()"></div>

<!-- Mobile Menu -->
<div class="mobile-menu" id="mobile-menu" role="navigation">
    <div class="mobile-menu-header">
        <div class="mobile-menu-brand">
            <img src="{{ asset('themes/shop/emsaigon/images/LOGO-EMSAIGON.jpg') }}" alt="EMSAIGON Logo" class="mobile-logo">
        </div>
        <button class="mobile-menu-close" onclick="closeMobileMenu()" aria-label="Close menu">
            <span>&times;</span>
        </button>
    </div>
    
    <div class="mobile-menu-content">
        @foreach($mobileMenu->menuItems as $item)
            <div class="mobile-menu-item{{ $item->children->count() > 0 ? ' has-children' : '' }}">
                @if($item->children->count() > 0)
                    {{-- Parent with children - toggle submenu --}}
                    <div class="mobile-menu-toggle" onclick="toggleMobileSubmenu(this)">
                        @if($item->icon)
                            <i class="{{ $item->icon }}"></i>
                        @endif
                        <span>{{ $item->title }}</span>
                        <i class="toggle-icon">▼</i>
                    </div>
                    
                    {{-- Submenu --}}
                    <div class="mobile-submenu">
                        {{-- Add parent link if it has URL --}}
                        @if($item->url && $item->url !== '#')
                            <a href="{{ $item->url }}" 
                               target="{{ $item->target ?? '_self' }}"
                               class="mobile-submenu-item mobile-submenu-parent"
                               @if(strpos($item->url, '#') === 0)
                                   onclick="scrollToSection('{{ $item->url }}'); closeMobileMenu();"
                               @else
                                   onclick="closeMobileMenu();"
                               @endif>
                                @if($item->icon)
                                    <i class="{{ $item->icon }}"></i>
                                @endif
                                {{ $item->title }}
                            </a>
                        @endif
                        
                        {{-- Children links --}}
                        @foreach($item->children as $child)
                            <div class="mobile-submenu-item-wrapper{{ $child->children->count() > 0 ? ' has-children' : '' }}">
                                @if($child->children->count() > 0)
                                    {{-- Second level with children --}}
                                    <div class="mobile-menu-toggle level-2" onclick="toggleMobileSubmenu(this)">
                                        @if($child->icon)
                                            <i class="{{ $child->icon }}"></i>
                                        @endif
                                        <span>{{ $child->title }}</span>
                                        <i class="toggle-icon">▼</i>
                                    </div>
                                    
                                    <div class="mobile-submenu level-2">
                                        {{-- Add child parent link if has URL --}}
                                        @if($child->url && $child->url !== '#')
                                            <a href="{{ $child->url }}" 
                                               target="{{ $child->target ?? '_self' }}"
                                               class="mobile-submenu-item"
                                               @if(strpos($child->url, '#') === 0)
                                                   onclick="scrollToSection('{{ $child->url }}'); closeMobileMenu();"
                                               @else
                                                   onclick="closeMobileMenu();"
                                               @endif>
                                                @if($child->icon)
                                                    <i class="{{ $child->icon }}"></i>
                                                @endif
                                                {{ $child->title }}
                                            </a>
                                        @endif
                                        
                                        {{-- Third level items --}}
                                        @foreach($child->children as $grandChild)
                                            <a href="{{ $grandChild->url }}" 
                                               target="{{ $grandChild->target ?? '_self' }}"
                                               class="mobile-submenu-item level-3"
                                               @if(strpos($grandChild->url, '#') === 0)
                                                   onclick="scrollToSection('{{ $grandChild->url }}'); closeMobileMenu();"
                                               @else
                                                   onclick="closeMobileMenu();"
                                               @endif>
                                                @if($grandChild->icon)
                                                    <i class="{{ $grandChild->icon }}"></i>
                                                @endif
                                                {{ $grandChild->title }}
                                            </a>
                                        @endforeach
                                    </div>
                                @else
                                    {{-- Regular second level link --}}
                                    <a href="{{ $child->url }}" 
                                       target="{{ $child->target ?? '_self' }}"
                                       class="mobile-submenu-item"
                                       @if(strpos($child->url, '#') === 0)
                                           onclick="scrollToSection('{{ $child->url }}'); closeMobileMenu();"
                                       @else
                                           onclick="closeMobileMenu();"
                                       @endif>
                                        @if($child->icon)
                                            <i class="{{ $child->icon }}"></i>
                                        @endif
                                        {{ $child->title }}
                                    </a>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    {{-- Regular link without children --}}
                    <a href="{{ $item->url }}" 
                       target="{{ $item->target ?? '_self' }}"
                       class="mobile-menu-link"
                       @if(strpos($item->url, '#') === 0)
                           onclick="scrollToSection('{{ $item->url }}'); closeMobileMenu();"
                       @else
                           onclick="closeMobileMenu();"
                       @endif>
                        @if($item->icon)
                            <i class="{{ $item->icon }}"></i>
                        @endif
                        {{ $item->title }}
                    </a>
                @endif
            </div>
        @endforeach
    </div>
</div>

<style>
/* Mobile Menu Styles */
.mobile-menu-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    z-index: 999;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
}

.mobile-menu-overlay.active {
    opacity: 1;
    visibility: visible;
}

.mobile-menu {
    position: fixed;
    top: 0;
    right: -320px;
    width: 320px;
    height: 100vh;
    background: white;
    z-index: 1000;
    transition: right 0.3s ease;
    overflow-y: auto;
    box-shadow: -2px 0 10px rgba(0, 0, 0, 0.1);
}

.mobile-menu.active {
    right: 0;
}

.mobile-menu-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem;
    border-bottom: 1px solid #eee;
    background: #f8f9fa;
}

.mobile-menu-brand {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.mobile-logo {
    width: 30px;
    height: 30px;
    border-radius: 50%;
}


.mobile-menu-close {
    background: none;
    border: none;
    font-size: 1.5rem;
    cursor: pointer;
    padding: 0.25rem;
    color: #666;
}

.mobile-menu-content {
    padding: 1rem 0;
}

.mobile-menu-item {
    border-bottom: 1px solid #f0f0f0;
}

.mobile-menu-link,
.mobile-menu-toggle {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1rem 1.5rem;
    color: #333;
    text-decoration: none;
    font-weight: 500;
    cursor: pointer;
    transition: background-color 0.2s;
}

.mobile-menu-link:hover,
.mobile-menu-toggle:hover {
    background-color: #f8f9fa;
    color: #2c5f41;
}

.mobile-menu-link.cta {
    background: #2c5f41;
    color: white;
    margin: 0.5rem 1rem;
    border-radius: 5px;
}

.mobile-menu-link i,
.mobile-menu-toggle i:not(.toggle-icon) {
    margin-right: 0.75rem;
    width: 20px;
    text-align: center;
}

.toggle-icon {
    font-size: 0.8rem;
    transition: transform 0.3s ease;
}

.mobile-menu-toggle.active .toggle-icon {
    transform: rotate(180deg);
}

/* Submenu Styles */
.mobile-submenu {
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.3s ease;
    background: #f8f9fa;
}

.mobile-submenu.active {
    max-height: 500px; /* Adjust based on content */
}

.mobile-submenu-item {
    display: block;
    padding: 0.75rem 2rem;
    color: #555;
    text-decoration: none;
    transition: background-color 0.2s;
    border-bottom: 1px solid #e9ecef;
}

.mobile-submenu-item:hover {
    background-color: #e9ecef;
    color: #2c5f41;
}

.mobile-submenu-item.mobile-submenu-parent {
    font-weight: 600;
    color: #2c5f41;
    background: #e3f2fd;
}

.mobile-submenu-item i {
    margin-right: 0.5rem;
    width: 16px;
}

/* Level 2 submenu */
.mobile-submenu.level-2 {
    background: #e9ecef;
}

.mobile-submenu-item.level-3 {
    padding-left: 3rem;
    color: #666;
    font-size: 0.9rem;
}

.mobile-menu-toggle.level-2 {
    padding-left: 2rem;
    background: #f8f9fa;
    font-size: 0.95rem;
}

/* Hide on desktop */
@media (min-width: 769px) {
    .mobile-menu,
    .mobile-menu-overlay {
        display: none;
    }
}
</style>

<script>
// Mobile menu functions
function openMobileMenu() {
    document.getElementById('mobile-menu').classList.add('active');
    document.getElementById('mobile-menu-overlay').classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeMobileMenu() {
    document.getElementById('mobile-menu').classList.remove('active');
    document.getElementById('mobile-menu-overlay').classList.remove('active');
    document.body.style.overflow = '';
}

function toggleMobileSubmenu(element) {
    const submenu = element.nextElementSibling;
    const isActive = submenu.classList.contains('active');
    
    // Close all other submenus at the same level
    const parentContainer = element.closest('.mobile-menu-content') || element.closest('.mobile-submenu');
    const siblingToggles = parentContainer.querySelectorAll(':scope > .mobile-menu-item .mobile-menu-toggle, :scope > .mobile-submenu-item-wrapper .mobile-menu-toggle');
    
    siblingToggles.forEach(toggle => {
        if (toggle !== element) {
            toggle.classList.remove('active');
            toggle.nextElementSibling.classList.remove('active');
        }
    });
    
    // Toggle current submenu
    element.classList.toggle('active', !isActive);
    submenu.classList.toggle('active', !isActive);
}
</script>
@endif
