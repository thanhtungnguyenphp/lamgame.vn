/**
 * Admin Layout JavaScript
 * Handles sidebar toggle and common admin functionality
 */

class AdminLayout {
    constructor() {
        this.init();
    }

    init() {
        this.createMobileToggle();
        this.bindEvents();
    }

    createMobileToggle() {
        // Create mobile menu toggle button
        const toggle = document.createElement('button');
        toggle.className = 'mobile-menu-toggle';
        toggle.innerHTML = '<i class="fas fa-bars"></i>';
        toggle.setAttribute('aria-label', 'Toggle menu');
        document.body.appendChild(toggle);

        // Create overlay
        const overlay = document.createElement('div');
        overlay.className = 'sidebar-overlay';
        document.body.appendChild(overlay);

        this.toggle = toggle;
        this.overlay = overlay;
    }

    bindEvents() {
        // Mobile menu toggle
        if (this.toggle) {
            this.toggle.addEventListener('click', () => this.toggleSidebar());
        }

        if (this.overlay) {
            this.overlay.addEventListener('click', () => this.closeSidebar());
        }

        // Close sidebar on escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                this.closeSidebar();
            }
        });

        // Auto-dismiss alerts
        this.autoDismissAlerts();
    }

    toggleSidebar() {
        const sidebar = document.querySelector('.admin-sidebar');
        if (sidebar) {
            sidebar.classList.toggle('open');
            this.overlay.classList.toggle('active');
            
            // Update toggle icon
            const icon = this.toggle.querySelector('i');
            if (sidebar.classList.contains('open')) {
                icon.className = 'fas fa-times';
            } else {
                icon.className = 'fas fa-bars';
            }
        }
    }

    closeSidebar() {
        const sidebar = document.querySelector('.admin-sidebar');
        if (sidebar) {
            sidebar.classList.remove('open');
            this.overlay.classList.remove('active');
            
            const icon = this.toggle.querySelector('i');
            icon.className = 'fas fa-bars';
        }
    }

    autoDismissAlerts() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            // Add close button
            const closeBtn = document.createElement('button');
            closeBtn.className = 'alert__close';
            closeBtn.innerHTML = '<i class="fas fa-times"></i>';
            closeBtn.setAttribute('aria-label', 'Close alert');
            closeBtn.addEventListener('click', () => {
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 300);
            });
            alert.appendChild(closeBtn);

            // Auto dismiss after 5 seconds
            setTimeout(() => {
                if (alert.parentElement) {
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 300);
                }
            }, 5000);
        });
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    window.adminLayout = new AdminLayout();
});

// Add alert close button styles
const style = document.createElement('style');
style.textContent = `
    .alert {
        position: relative;
        transition: opacity 0.3s ease;
    }

    .alert__close {
        position: absolute;
        top: 50%;
        right: var(--space-md);
        transform: translateY(-50%);
        background: none;
        border: none;
        color: inherit;
        cursor: pointer;
        padding: var(--space-xs);
        opacity: 0.7;
        transition: opacity 0.2s ease;
    }

    .alert__close:hover {
        opacity: 1;
    }

    .inline-form {
        display: inline;
        margin: 0;
    }

    .inline-form button {
        background: none;
        border: none;
        padding: 0;
        margin: 0;
        cursor: pointer;
    }

    .text-muted {
        color: var(--color-gray-500);
    }
`;
document.head.appendChild(style);
