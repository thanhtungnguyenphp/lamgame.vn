/**
 * Dynamic Menu JavaScript
 * Handles responsive navigation, mobile menu, dropdown functionality
 */

class DynamicMenu {
    constructor(options = {}) {
        this.options = {
            mobileBreakpoint: 768,
            animationDuration: 300,
            scrollOffset: 80,
            ...options
        };

        this.state = {
            isMobile: false,
            mobileMenuOpen: false,
            activeDropdown: null,
            scrollPosition: 0
        };

        this.elements = {
            mobileToggle: document.querySelector('.mobile-toggle'),
            mobileMenu: document.getElementById('mobile-menu'),
            mobileOverlay: document.getElementById('mobile-menu-overlay'),
            header: document.querySelector('.header'),
            nav: document.querySelector('.nav'),
            dropdowns: document.querySelectorAll('.dropdown'),
            mobileToggles: document.querySelectorAll('.mobile-menu-toggle')
        };

        this.init();
    }

    init() {
        this.checkMobile();
        this.bindEvents();
        this.initDropdowns();
        this.initMobileMenu();
        this.initScrollEffects();
        this.initKeyboardNavigation();
    }

    checkMobile() {
        this.state.isMobile = window.innerWidth <= this.options.mobileBreakpoint;
    }

    bindEvents() {
        // Window resize
        window.addEventListener('resize', this.debounce(() => {
            this.checkMobile();
            if (!this.state.isMobile && this.state.mobileMenuOpen) {
                this.closeMobileMenu();
            }
        }, 250));

        // Mobile toggle
        if (this.elements.mobileToggle) {
            this.elements.mobileToggle.addEventListener('click', () => {
                this.toggleMobileMenu();
            });
        }

        // Overlay click
        if (this.elements.mobileOverlay) {
            this.elements.mobileOverlay.addEventListener('click', () => {
                this.closeMobileMenu();
            });
        }

        // Document click (close dropdowns)
        document.addEventListener('click', (e) => {
            if (!e.target.closest('.dropdown')) {
                this.closeAllDropdowns();
            }
        });

        // Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                if (this.state.mobileMenuOpen) {
                    this.closeMobileMenu();
                } else {
                    this.closeAllDropdowns();
                }
            }
        });

        // Scroll
        window.addEventListener('scroll', this.throttle(() => {
            this.handleScroll();
        }, 16));
    }

    initDropdowns() {
        this.elements.dropdowns.forEach(dropdown => {
            const toggle = dropdown.querySelector('.nav-link');
            const menu = dropdown.querySelector('.dropdown-menu');

            if (toggle && menu) {
                // Mouse events for desktop
                dropdown.addEventListener('mouseenter', () => {
                    if (!this.state.isMobile) {
                        this.openDropdown(dropdown);
                    }
                });

                dropdown.addEventListener('mouseleave', () => {
                    if (!this.state.isMobile) {
                        this.closeDropdown(dropdown);
                    }
                });

                // Click events for mobile/touch
                toggle.addEventListener('click', (e) => {
                    if (this.state.isMobile || toggle.classList.contains('dropdown-toggle')) {
                        e.preventDefault();
                        this.toggleDropdown(dropdown);
                    }
                });

                // Initialize ARIA attributes
                toggle.setAttribute('aria-haspopup', 'true');
                toggle.setAttribute('aria-expanded', 'false');
                menu.setAttribute('aria-hidden', 'true');
            }
        });
    }

    initMobileMenu() {
        this.elements.mobileToggles.forEach(toggle => {
            toggle.addEventListener('click', (e) => {
                e.stopPropagation();
                this.toggleMobileSubmenu(toggle);
            });
        });
    }

    initScrollEffects() {
        this.state.scrollPosition = window.pageYOffset;
    }

    initKeyboardNavigation() {
        // Tab navigation for dropdowns
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Tab') {
                const focusedElement = document.activeElement;
                const dropdown = focusedElement.closest('.dropdown');

                if (dropdown) {
                    const menu = dropdown.querySelector('.dropdown-menu');
                    const isMenuVisible = menu && !menu.getAttribute('aria-hidden');

                    if (isMenuVisible && !e.shiftKey) {
                        // Tab forward into dropdown
                        const firstMenuItem = menu.querySelector('a');
                        if (firstMenuItem && !menu.contains(document.activeElement)) {
                            e.preventDefault();
                            firstMenuItem.focus();
                        }
                    } else if (!dropdown.contains(e.target)) {
                        // Tabbed out of dropdown
                        this.closeDropdown(dropdown);
                    }
                }
            }
        });
    }

    // Mobile Menu Methods
    openMobileMenu() {
        if (!this.elements.mobileMenu) return;

        this.state.mobileMenuOpen = true;
        this.elements.mobileMenu.classList.add('active');
        this.elements.mobileOverlay.classList.add('active');
        document.body.style.overflow = 'hidden';

        // Focus management
        const firstMenuItem = this.elements.mobileMenu.querySelector('a, button');
        if (firstMenuItem) {
            setTimeout(() => firstMenuItem.focus(), 100);
        }

        // Animation
        this.animateElement(this.elements.mobileMenu, 'slideInRight');
    }

    closeMobileMenu() {
        if (!this.elements.mobileMenu) return;

        this.state.mobileMenuOpen = false;
        this.elements.mobileMenu.classList.remove('active');
        this.elements.mobileOverlay.classList.remove('active');
        document.body.style.overflow = '';

        // Close all mobile submenus
        this.closeAllMobileSubmenus();

        // Return focus to toggle
        if (this.elements.mobileToggle) {
            this.elements.mobileToggle.focus();
        }
    }

    toggleMobileMenu() {
        if (this.state.mobileMenuOpen) {
            this.closeMobileMenu();
        } else {
            this.openMobileMenu();
        }
    }

    toggleMobileSubmenu(toggleElement) {
        const submenu = toggleElement.nextElementSibling;
        const isActive = submenu && submenu.classList.contains('active');

        // Close siblings
        const parentContainer = toggleElement.closest('.mobile-menu-content, .mobile-submenu');
        const siblings = parentContainer.querySelectorAll(':scope > .mobile-menu-item .mobile-menu-toggle, :scope > .mobile-submenu-item-wrapper .mobile-menu-toggle');

        siblings.forEach(sibling => {
            if (sibling !== toggleElement) {
                sibling.classList.remove('active');
                const siblingSubmenu = sibling.nextElementSibling;
                if (siblingSubmenu) {
                    siblingSubmenu.classList.remove('active');
                    siblingSubmenu.style.maxHeight = null;
                }
            }
        });

        // Toggle current
        if (submenu) {
            toggleElement.classList.toggle('active', !isActive);
            submenu.classList.toggle('active', !isActive);

            if (!isActive) {
                // Opening - calculate height
                submenu.style.maxHeight = submenu.scrollHeight + 'px';
                this.animateElement(submenu, 'slideDown');
            } else {
                // Closing
                submenu.style.maxHeight = null;
            }
        }
    }

    closeAllMobileSubmenus() {
        const activeToggles = document.querySelectorAll('.mobile-menu-toggle.active');
        activeToggles.forEach(toggle => {
            toggle.classList.remove('active');
            const submenu = toggle.nextElementSibling;
            if (submenu) {
                submenu.classList.remove('active');
                submenu.style.maxHeight = null;
            }
        });
    }

    // Desktop Dropdown Methods
    openDropdown(dropdown) {
        if (this.state.activeDropdown && this.state.activeDropdown !== dropdown) {
            this.closeDropdown(this.state.activeDropdown);
        }

        const menu = dropdown.querySelector('.dropdown-menu');
        const toggle = dropdown.querySelector('.nav-link');

        if (menu && toggle) {
            dropdown.classList.add('active');
            menu.setAttribute('aria-hidden', 'false');
            toggle.setAttribute('aria-expanded', 'true');
            this.state.activeDropdown = dropdown;

            this.animateElement(menu, 'fadeInUp');
        }
    }

    closeDropdown(dropdown) {
        const menu = dropdown.querySelector('.dropdown-menu');
        const toggle = dropdown.querySelector('.nav-link');

        if (menu && toggle) {
            dropdown.classList.remove('active');
            menu.setAttribute('aria-hidden', 'true');
            toggle.setAttribute('aria-expanded', 'false');

            if (this.state.activeDropdown === dropdown) {
                this.state.activeDropdown = null;
            }
        }
    }

    toggleDropdown(dropdown) {
        if (dropdown.classList.contains('active')) {
            this.closeDropdown(dropdown);
        } else {
            this.openDropdown(dropdown);
        }
    }

    closeAllDropdowns() {
        this.elements.dropdowns.forEach(dropdown => {
            this.closeDropdown(dropdown);
        });
    }

    // Scroll Effects
    handleScroll() {
        const currentScroll = window.pageYOffset;
        const scrollDifference = currentScroll - this.state.scrollPosition;

        // Header scroll effect
        if (this.elements.header) {
            if (currentScroll > 100) {
                this.elements.header.classList.add('scrolled');
            } else {
                this.elements.header.classList.remove('scrolled');
            }

            // Hide/show header on scroll
            if (Math.abs(scrollDifference) > 5) {
                if (scrollDifference > 0 && currentScroll > 200) {
                    // Scrolling down
                    this.elements.header.classList.add('header-hidden');
                } else {
                    // Scrolling up
                    this.elements.header.classList.remove('header-hidden');
                }
            }
        }

        this.state.scrollPosition = currentScroll;

        // Close dropdowns on scroll
        if (this.state.activeDropdown) {
            this.closeAllDropdowns();
        }
    }

    // Smooth Scrolling
    scrollToSection(target) {
        const element = document.querySelector(target);
        if (element) {
            const offsetTop = element.offsetTop - this.options.scrollOffset;
            
            // Close mobile menu if open
            if (this.state.mobileMenuOpen) {
                this.closeMobileMenu();
            }

            // Smooth scroll
            window.scrollTo({
                top: offsetTop,
                behavior: 'smooth'
            });

            // Update URL hash
            if (target.startsWith('#') && target.length > 1) {
                history.pushState(null, null, target);
            }
        }
    }

    // Animation Helper
    animateElement(element, animationType) {
        if (!element) return;

        element.style.animation = 'none';
        element.offsetHeight; // Trigger reflow

        const animations = {
            fadeInUp: 'fadeInUp 0.3s ease-out',
            slideInRight: 'slideInRight 0.3s ease-out',
            slideDown: 'slideDown 0.3s ease-out'
        };

        element.style.animation = animations[animationType] || animations.fadeInUp;
    }

    // Utility Methods
    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    throttle(func, limit) {
        let inThrottle;
        return function executedFunction(...args) {
            if (!inThrottle) {
                func.apply(this, args);
                inThrottle = true;
                setTimeout(() => inThrottle = false, limit);
            }
        };
    }

    // Public API Methods
    destroy() {
        // Remove event listeners
        window.removeEventListener('resize', this.checkMobile);
        window.removeEventListener('scroll', this.handleScroll);
        document.removeEventListener('click', this.closeAllDropdowns);
        document.removeEventListener('keydown', this.initKeyboardNavigation);

        // Reset state
        this.closeMobileMenu();
        this.closeAllDropdowns();
    }

    refresh() {
        this.destroy();
        this.init();
    }
}

// Global Functions (backward compatibility)
window.scrollToSection = function(target) {
    if (window.dynamicMenu) {
        window.dynamicMenu.scrollToSection(target);
    }
};

window.openMobileMenu = function() {
    if (window.dynamicMenu) {
        window.dynamicMenu.openMobileMenu();
    }
};

window.closeMobileMenu = function() {
    if (window.dynamicMenu) {
        window.dynamicMenu.closeMobileMenu();
    }
};

window.toggleMobileSubmenu = function(element) {
    if (window.dynamicMenu) {
        window.dynamicMenu.toggleMobileSubmenu(element);
    }
};

// CSS Animations
const menuStyles = `
<style>
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes slideInRight {
    from {
        transform: translateX(100%);
    }
    to {
        transform: translateX(0);
    }
}

@keyframes slideDown {
    from {
        max-height: 0;
    }
    to {
        max-height: var(--target-height, 500px);
    }
}

/* Enhanced Header Styles */
.header {
    transition: transform 0.3s ease-in-out, box-shadow 0.3s ease;
}

.header.scrolled {
    box-shadow: 0 2px 20px rgba(0,0,0,0.15);
}

.header.header-hidden {
    transform: translateY(-100%);
}

/* Enhanced Mobile Toggle */
.mobile-toggle {
    position: relative;
    z-index: 1001;
}

.mobile-toggle.active span:nth-child(1) {
    transform: rotate(45deg) translate(5px, 5px);
}

.mobile-toggle.active span:nth-child(2) {
    opacity: 0;
}

.mobile-toggle.active span:nth-child(3) {
    transform: rotate(-45deg) translate(7px, -6px);
}

/* Improved Dropdown Animations */
.dropdown-menu {
    transform: translateY(-10px) scale(0.95);
    transition: all 0.2s ease;
}

.dropdown.active .dropdown-menu {
    transform: translateY(0) scale(1);
}

/* Focus Indicators */
.nav-link:focus,
.mobile-menu-link:focus,
.mobile-menu-toggle:focus {
    outline: 2px solid #2c5f41;
    outline-offset: 2px;
}

/* Loading States */
.menu-loading {
    opacity: 0.6;
    pointer-events: none;
}

.menu-loading::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 20px;
    height: 20px;
    margin: -10px 0 0 -10px;
    border: 2px solid #2c5f41;
    border-top-color: transparent;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}
</style>
`;

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Inject styles
    document.head.insertAdjacentHTML('beforeend', menuStyles);
    
    // Initialize menu
    window.dynamicMenu = new DynamicMenu();
    
    // Handle hash navigation on page load
    if (window.location.hash) {
        setTimeout(() => {
            window.dynamicMenu.scrollToSection(window.location.hash);
        }, 100);
    }
});

// Export for module systems
if (typeof module !== 'undefined' && module.exports) {
    module.exports = DynamicMenu;
}
