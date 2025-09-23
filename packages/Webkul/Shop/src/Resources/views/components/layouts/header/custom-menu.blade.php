{{-- Menu data is provided by MenuComposer --}}
@if(isset($hasMenuError) && $hasMenuError && config('app.debug'))
    <!-- Debug: Menu loading error -->
    <div class="custom-menu-error text-red-500 text-xs p-2">
        Menu Error: {{ $menuError ?? 'Unknown error' }}
    </div>
@endif

@if($customMenus->count() > 0)
    <div class="custom-menu-container">
        @foreach($customMenus as $menu)
            @if($menu->menuItems->count() > 0)
                <nav class="custom-menu flex items-center space-x-6" data-menu="{{ $menu->name }}">
                    @foreach($menu->menuItems->sortBy('sort_order') as $item)
                        <div class="menu-item-wrapper">
                            @if($item->children->count() > 0)
                                <!-- Dropdown Menu Item -->
                                <div class="custom-dropdown-wrapper relative">
                                    <button class="menu-item flex items-center space-x-1 dropdown-toggle {{ request()->fullUrlIs($item->url) ? 'active' : '' }}" 
                                            data-dropdown="dropdown-{{ $loop->index }}">
                                        @if($item->icon)
                                            <span class="{{ $item->icon }} text-sm"></span>
                                        @endif
                                        <span>{{ $item->title }}</span>
                                        <span class="icon-arrow-down text-xs transition-transform duration-200"></span>
                                    </button>
                                    
                                    <div class="custom-dropdown-menu absolute z-50 hidden bg-white shadow-lg rounded-lg py-2 mt-2 min-w-[200px]" 
                                         id="dropdown-{{ $loop->index }}">
                                        @foreach($item->children->sortBy('sort_order') as $child)
                                            <a href="{{ $child->url }}" 
                                               target="{{ $child->target }}"
                                               class="dropdown-item flex items-center px-4 py-2 text-sm hover:bg-gray-100 transition-colors {{ request()->fullUrlIs($child->url) ? 'bg-blue-50 text-blue-600' : 'text-gray-700' }}">
                                                @if($child->icon)
                                                    <span class="{{ $child->icon }} text-sm mr-2"></span>
                                                @endif
                                                {{ $child->title }}
                                                @if($child->target === '_blank')
                                                    <span class="icon-external-link text-xs ml-auto"></span>
                                                @endif
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                <!-- Simple Menu Item -->
                                <a href="{{ $item->url }}" 
                                   target="{{ $item->target }}"
                                   class="menu-item flex items-center space-x-1 {{ request()->fullUrlIs($item->url) || (request()->is(trim($item->url, '/')) && $item->url !== '/') ? 'active' : '' }}">
                                    @if($item->icon)
                                        <span class="{{ $item->icon }} text-sm"></span>
                                    @endif
                                    <span>{{ $item->title }}</span>
                                    @if($item->target === '_blank')
                                        <span class="icon-external-link text-xs"></span>
                                    @endif
                                </a>
                            @endif
                        </div>
                    @endforeach
                </nav>
            @endif
        @endforeach
    </div>
@endif

@pushOnce('styles')
<style>
/* Custom Menu Styles - Scoped to avoid conflicts */
.custom-menu-container {
    display: flex;
    align-items: center;
    gap: 2rem;
    position: relative;
    z-index: 10;
}

.custom-menu {
    background: transparent;
    position: relative;
}

/* Menu Item Base Styles */
.custom-menu .menu-item {
    color: #374151; /* gray-700 */
    font-weight: 500;
    transition: all 0.2s ease;
    padding: 0.5rem 0.75rem;
    border-radius: 0.375rem;
    text-decoration: none;
    position: relative;
    display: flex;
    align-items: center;
    gap: 0.25rem;
    cursor: pointer;
    border: none;
    background: transparent;
    font-family: inherit;
    font-size: inherit;
}

.custom-menu .menu-item:hover {
    color: #2563eb; /* blue-600 */
    background-color: rgba(59, 130, 246, 0.1);
    transform: translateY(-1px);
}

.custom-menu .menu-item.active {
    color: #2563eb; /* blue-600 */
    font-weight: 600;
}

.custom-menu .menu-item.active::after {
    content: '';
    position: absolute;
    bottom: -2px;
    left: 50%;
    transform: translateX(-50%);
    width: 80%;
    height: 2px;
    background: linear-gradient(90deg, #3b82f6, #1d4ed8);
    border-radius: 1px;
}

/* Dropdown Styles */
.custom-dropdown-wrapper {
    position: relative;
    display: inline-block;
}

.custom-dropdown-menu {
    position: absolute;
    top: 100%;
    left: 0;
    z-index: 50;
    background: white;
    border-radius: 0.5rem;
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    border: 1px solid #e5e7eb;
    min-width: 200px;
    padding: 0.5rem 0;
    margin-top: 0.5rem;
    opacity: 0;
    visibility: hidden;
    transform: translateY(-10px);
    transition: all 0.2s ease;
}

.custom-dropdown-menu.show {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}

.dropdown-toggle .icon-arrow-down {
    transition: transform 0.2s ease;
}

.dropdown-toggle.active .icon-arrow-down {
    transform: rotate(180deg);
}

.custom-dropdown-menu .dropdown-item {
    display: flex;
    align-items: center;
    padding: 0.5rem 1rem;
    color: #374151;
    text-decoration: none;
    font-size: 0.875rem;
    transition: all 0.2s ease;
    border: none;
    background: none;
    width: 100%;
    text-align: left;
}

.custom-dropdown-menu .dropdown-item:hover {
    background-color: #f3f4f6;
    color: #1f2937;
    transform: translateX(4px);
}

.custom-dropdown-menu .dropdown-item.active {
    background-color: #eff6ff;
    color: #2563eb;
}

/* Mobile Responsive */
@media (max-width: 1024px) {
    .custom-menu-container {
        display: none; /* Hide on mobile, use mobile menu instead */
    }
}

/* Icon Styling */
.custom-menu .menu-item span[class*="icon-"] {
    font-size: 1rem;
    line-height: 1;
}

/* External Link Indicator */
.custom-menu .icon-external-link {
    opacity: 0.6;
    margin-left: auto;
}

/* Ensure proper spacing */
.custom-menu .menu-item-wrapper + .menu-item-wrapper {
    margin-left: 0;
}

/* Loading state */
.custom-menu-loading {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.custom-menu-loading .shimmer {
    background: linear-gradient(90deg, #f3f4f6 25%, #e5e7eb 50%, #f3f4f6 75%);
    background-size: 200% 100%;
    animation: shimmer 1.5s infinite;
    border-radius: 0.375rem;
    height: 1.5rem;
    width: 4rem;
}

@keyframes shimmer {
    0% { background-position: 200% 0; }
    100% { background-position: -200% 0; }
}
</style>
@endPushOnce

@pushOnce('scripts')
<script>
// Custom Menu Manager
class CustomMenuManager {
    constructor() {
        this.activeDropdown = null;
        this.init();
    }

    init() {
        this.highlightCurrentPage();
        this.setupDropdowns();
        this.setupClickOutside();
    }

    highlightCurrentPage() {
        const currentUrl = window.location.href;
        const currentPath = window.location.pathname;
        
        // Find all menu items
        const menuItems = document.querySelectorAll('.custom-menu .menu-item');
        
        menuItems.forEach(item => {
            const itemUrl = item.getAttribute('href');
            
            if (itemUrl) {
                // Check for exact match or path match
                if (itemUrl === currentUrl || 
                    itemUrl === currentPath || 
                    (itemUrl !== '/' && currentPath.startsWith(itemUrl.replace(/\/$/, '')))) {
                    item.classList.add('active');
                }
            }
        });
    }

    setupDropdowns() {
        const dropdownToggles = document.querySelectorAll('.dropdown-toggle');
        
        dropdownToggles.forEach(toggle => {
            const dropdownId = toggle.getAttribute('data-dropdown');
            const dropdownMenu = document.getElementById(dropdownId);
            
            if (dropdownMenu) {
                // Click event
                toggle.addEventListener('click', (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    this.toggleDropdown(toggle, dropdownMenu);
                });

                // Hover events for better UX
                const wrapper = toggle.closest('.custom-dropdown-wrapper');
                if (wrapper) {
                    let hoverTimeout;
                    
                    wrapper.addEventListener('mouseenter', () => {
                        clearTimeout(hoverTimeout);
                        hoverTimeout = setTimeout(() => {
                            this.showDropdown(toggle, dropdownMenu);
                        }, 150);
                    });
                    
                    wrapper.addEventListener('mouseleave', () => {
                        clearTimeout(hoverTimeout);
                        hoverTimeout = setTimeout(() => {
                            this.hideDropdown(toggle, dropdownMenu);
                        }, 200);
                    });
                }
            }
        });
    }

    toggleDropdown(toggle, menu) {
        if (this.activeDropdown && this.activeDropdown !== menu) {
            this.hideDropdown(
                this.activeDropdown.previousElementSibling,
                this.activeDropdown
            );
        }

        if (menu.classList.contains('show')) {
            this.hideDropdown(toggle, menu);
        } else {
            this.showDropdown(toggle, menu);
        }
    }

    showDropdown(toggle, menu) {
        menu.classList.remove('hidden');
        menu.classList.add('show');
        toggle.classList.add('active');
        this.activeDropdown = menu;
        
        // Adjust position if needed
        this.adjustDropdownPosition(menu);
    }

    hideDropdown(toggle, menu) {
        menu.classList.remove('show');
        toggle.classList.remove('active');
        
        // Delay hiding to allow for animation
        setTimeout(() => {
            if (!menu.classList.contains('show')) {
                menu.classList.add('hidden');
            }
        }, 200);
        
        if (this.activeDropdown === menu) {
            this.activeDropdown = null;
        }
    }

    adjustDropdownPosition(menu) {
        const rect = menu.getBoundingClientRect();
        const windowWidth = window.innerWidth;
        const windowHeight = window.innerHeight;
        
        // Check if dropdown goes off-screen horizontally
        if (rect.right > windowWidth) {
            menu.style.left = 'auto';
            menu.style.right = '0';
        }
        
        // Check if dropdown goes off-screen vertically
        if (rect.bottom > windowHeight) {
            menu.style.top = 'auto';
            menu.style.bottom = '100%';
            menu.style.marginBottom = '0.5rem';
            menu.style.marginTop = '0';
        }
    }

    setupClickOutside() {
        document.addEventListener('click', (e) => {
            if (this.activeDropdown && !e.target.closest('.custom-dropdown-wrapper')) {
                const toggle = this.activeDropdown.previousElementSibling;
                this.hideDropdown(toggle, this.activeDropdown);
            }
        });

        // Handle escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.activeDropdown) {
                const toggle = this.activeDropdown.previousElementSibling;
                this.hideDropdown(toggle, this.activeDropdown);
            }
        });
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    // Only initialize if custom menu exists
    if (document.querySelector('.custom-menu-container')) {
        new CustomMenuManager();
    }
});

// Handle window resize
window.addEventListener('resize', function() {
    const activeDropdowns = document.querySelectorAll('.custom-dropdown-menu.show');
    activeDropdowns.forEach(menu => {
        // Reset position styles
        menu.style.left = '0';
        menu.style.right = 'auto';
        menu.style.top = '100%';
        menu.style.bottom = 'auto';
        menu.style.marginTop = '0.5rem';
        menu.style.marginBottom = '0';
    });
});
</script>
@endPushOnce
