@if($customMenus->count() > 0)
    <div class="mobile-menu-container lg:hidden">
        @foreach($customMenus as $menu)
            @if($menu->menuItems->count() > 0)
                <nav class="mobile-custom-menu" data-menu="{{ $menu->name }}">
                    @foreach($menu->menuItems->sortBy('sort_order') as $item)
                        <div class="mobile-menu-item">
                            @if($item->children->count() > 0)
                                {{-- Collapsible Menu Item --}}
                                <div class="mobile-dropdown-wrapper">
                                    <button class="mobile-menu-toggle flex items-center justify-between w-full px-4 py-3 text-left border-b border-gray-200" 
                                            data-target="mobile-submenu-{{ $loop->index }}">
                                        <div class="flex items-center space-x-2">
                                            @if($item->icon)
                                                <span class="{{ $item->icon }} text-lg"></span>
                                            @endif
                                            <span class="font-medium">{{ $item->title }}</span>
                                        </div>
                                        <span class="icon-arrow-down text-sm transition-transform duration-200"></span>
                                    </button>
                                    
                                    <div class="mobile-submenu hidden bg-gray-50" id="mobile-submenu-{{ $loop->index }}">
                                        @foreach($item->children->sortBy('sort_order') as $child)
                                            <a href="{{ $child->url }}" 
                                               target="{{ $child->target }}"
                                               class="flex items-center px-8 py-2 text-sm text-gray-600 border-b border-gray-100 last:border-b-0">
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
                                {{-- Simple Menu Item --}}
                                <a href="{{ $item->url }}" 
                                   target="{{ $item->target }}"
                                   class="flex items-center px-4 py-3 border-b border-gray-200">
                                    @if($item->icon)
                                        <span class="{{ $item->icon }} text-lg mr-2"></span>
                                    @endif
                                    <span class="font-medium">{{ $item->title }}</span>
                                    @if($item->target === '_blank')
                                        <span class="icon-external-link text-xs ml-auto"></span>
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
/* Mobile Custom Menu Styles */
.mobile-menu-container {
    background: white;
    border-top: 1px solid #e5e7eb;
}

.mobile-custom-menu {
    width: 100%;
}

.mobile-menu-item {
    border-bottom: 1px solid #f3f4f6;
}

.mobile-menu-toggle {
    background: none;
    border: none;
    font-family: inherit;
    cursor: pointer;
    transition: background-color 0.2s ease;
}

.mobile-menu-toggle:hover {
    background-color: #f9fafb;
}

.mobile-menu-toggle.active .icon-arrow-down {
    transform: rotate(180deg);
}

.mobile-submenu {
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.3s ease;
}

.mobile-submenu.show {
    max-height: 500px;
}

.mobile-submenu a {
    transition: background-color 0.2s ease;
}

.mobile-submenu a:hover {
    background-color: #e5e7eb;
}
</style>
@endPushOnce

@pushOnce('scripts')
<script>
// Mobile Menu Manager
class MobileMenuManager {
    constructor() {
        this.init();
    }

    init() {
        this.setupToggles();
    }

    setupToggles() {
        const toggles = document.querySelectorAll('.mobile-menu-toggle');
        
        toggles.forEach(toggle => {
            const targetId = toggle.getAttribute('data-target');
            const submenu = document.getElementById(targetId);
            
            if (submenu) {
                toggle.addEventListener('click', (e) => {
                    e.preventDefault();
                    this.toggleSubmenu(toggle, submenu);
                });
            }
        });
    }

    toggleSubmenu(toggle, submenu) {
        const isActive = toggle.classList.contains('active');
        
        // Close all other submenus
        document.querySelectorAll('.mobile-menu-toggle.active').forEach(activeToggle => {
            if (activeToggle !== toggle) {
                activeToggle.classList.remove('active');
                const targetId = activeToggle.getAttribute('data-target');
                const activeSubmenu = document.getElementById(targetId);
                if (activeSubmenu) {
                    activeSubmenu.classList.remove('show');
                }
            }
        });

        if (isActive) {
            toggle.classList.remove('active');
            submenu.classList.remove('show');
        } else {
            toggle.classList.add('active');
            submenu.classList.add('show');
        }
    }
}

// Initialize mobile menu when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    if (document.querySelector('.mobile-menu-container')) {
        new MobileMenuManager();
    }
});
</script>
@endPushOnce
