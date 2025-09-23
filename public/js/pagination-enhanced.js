/**
 * Enhanced Pagination UX JavaScript
 * Provides loading states, smooth transitions, and better mobile experience
 */

class PaginationEnhancer {
    constructor() {
        this.init();
    }

    init() {
        this.addClickHandlers();
        this.addKeyboardNavigation();
        this.addLoadingStates();
        this.addSmoothScrolling();
        this.handleMobileOptimizations();
    }

    /**
     * Add loading states to pagination links
     */
    addLoadingStates() {
        const paginationWrapper = document.querySelector('.modern-pagination-wrapper');
        if (!paginationWrapper) return;

        const paginationLinks = paginationWrapper.querySelectorAll('.page-link:not(.disabled)');
        
        paginationLinks.forEach(link => {
            link.addEventListener('click', (e) => {
                // Don't add loading to current page
                if (link.closest('.page-item').classList.contains('active')) {
                    e.preventDefault();
                    return;
                }

                // Add loading state
                paginationWrapper.classList.add('pagination-loading');
                
                // Show loading indicator
                this.showLoadingIndicator();
                
                // Add timeout fallback in case page doesn't load
                setTimeout(() => {
                    this.hideLoadingIndicator();
                    paginationWrapper.classList.remove('pagination-loading');
                }, 10000);
            });
        });
    }

    /**
     * Add smooth scrolling to top when pagination is clicked
     */
    addSmoothScrolling() {
        const paginationWrapper = document.querySelector('.modern-pagination-wrapper');
        if (!paginationWrapper) return;

        const paginationLinks = paginationWrapper.querySelectorAll('.page-link:not(.disabled)');
        
        paginationLinks.forEach(link => {
            link.addEventListener('click', (e) => {
                // Don't scroll if it's current page
                if (link.closest('.page-item').classList.contains('active')) {
                    return;
                }

                // Scroll to job listings section
                const jobsSection = document.querySelector('.job-listings-section');
                if (jobsSection) {
                    setTimeout(() => {
                        jobsSection.scrollIntoView({ 
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }, 100);
                }
            });
        });
    }

    /**
     * Add keyboard navigation support
     */
    addKeyboardNavigation() {
        document.addEventListener('keydown', (e) => {
            // Only handle if focus is on pagination or if Alt key is pressed
            const activeElement = document.activeElement;
            const isPaginationFocused = activeElement && activeElement.closest('.modern-pagination-wrapper');
            
            if (!isPaginationFocused && !e.altKey) return;

            const currentPage = document.querySelector('.page-item.active');
            if (!currentPage) return;

            let targetLink = null;

            switch(e.key) {
                case 'ArrowLeft':
                    // Previous page
                    targetLink = document.querySelector('.page-link[rel="prev"]');
                    break;
                case 'ArrowRight':
                    // Next page  
                    targetLink = document.querySelector('.page-link[rel="next"]');
                    break;
                case 'Home':
                    // First page
                    const firstPageLink = document.querySelector('.pagination-list .page-item:not(.disabled) .page-link');
                    if (firstPageLink && !firstPageLink.getAttribute('rel')) {
                        targetLink = firstPageLink;
                    }
                    break;
                case 'End':
                    // Last page
                    const pageLinks = document.querySelectorAll('.pagination-list .page-item:not(.disabled) .page-link');
                    const lastPageLink = Array.from(pageLinks).reverse().find(link => !link.getAttribute('rel'));
                    if (lastPageLink) {
                        targetLink = lastPageLink;
                    }
                    break;
            }

            if (targetLink && !targetLink.closest('.page-item').classList.contains('disabled')) {
                e.preventDefault();
                targetLink.click();
            }
        });
    }

    /**
     * Add click handlers for enhanced UX
     */
    addClickHandlers() {
        // Handle quick jump form
        const jumpForm = document.querySelector('.jump-form');
        if (jumpForm) {
            const jumpInput = jumpForm.querySelector('.jump-input');
            const jumpBtn = jumpForm.querySelector('.jump-btn');

            // Auto-submit on Enter
            jumpInput?.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    jumpForm.submit();
                }
            });

            // Validate input
            jumpInput?.addEventListener('input', (e) => {
                const value = parseInt(e.target.value);
                const min = parseInt(e.target.min);
                const max = parseInt(e.target.max);

                if (value < min || value > max || isNaN(value)) {
                    jumpBtn.disabled = true;
                    jumpBtn.style.opacity = '0.5';
                } else {
                    jumpBtn.disabled = false;
                    jumpBtn.style.opacity = '1';
                }
            });
        }
    }

    /**
     * Handle mobile-specific optimizations
     */
    handleMobileOptimizations() {
        // Add touch-friendly hover states on mobile
        if ('ontouchstart' in window) {
            const pageLinks = document.querySelectorAll('.page-link');
            
            pageLinks.forEach(link => {
                link.addEventListener('touchstart', function() {
                    this.classList.add('touch-hover');
                });
                
                link.addEventListener('touchend', function() {
                    setTimeout(() => {
                        this.classList.remove('touch-hover');
                    }, 150);
                });
            });
        }

        // Handle orientation change
        window.addEventListener('orientationchange', () => {
            setTimeout(() => {
                this.recalculateLayout();
            }, 200);
        });
    }

    /**
     * Show loading indicator
     */
    showLoadingIndicator() {
        let indicator = document.querySelector('.pagination-loading-indicator');
        
        if (!indicator) {
            indicator = document.createElement('div');
            indicator.className = 'pagination-loading-indicator';
            indicator.innerHTML = `
                <div class="loading-spinner"></div>
                <span>Đang tải...</span>
            `;
            
            // Add styles
            indicator.style.cssText = `
                position: fixed;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                background: white;
                padding: 1.5rem 2rem;
                border-radius: 8px;
                box-shadow: 0 4px 20px rgba(0,0,0,0.15);
                display: flex;
                align-items: center;
                gap: 1rem;
                z-index: 1000;
                font-size: 0.9rem;
                color: #64748b;
            `;

            // Add spinner styles
            const style = document.createElement('style');
            style.textContent = `
                .loading-spinner {
                    width: 20px;
                    height: 20px;
                    border: 2px solid #f3f3f3;
                    border-top: 2px solid #667eea;
                    border-radius: 50%;
                    animation: spin 1s linear infinite;
                }
                @keyframes spin {
                    0% { transform: rotate(0deg); }
                    100% { transform: rotate(360deg); }
                }
                .pagination-loading-indicator {
                    animation: fadeIn 0.2s ease-in-out;
                }
                @keyframes fadeIn {
                    from { opacity: 0; transform: translate(-50%, -50%) scale(0.9); }
                    to { opacity: 1; transform: translate(-50%, -50%) scale(1); }
                }
            `;
            document.head.appendChild(style);
            
            document.body.appendChild(indicator);
        }
        
        indicator.style.display = 'flex';
    }

    /**
     * Hide loading indicator
     */
    hideLoadingIndicator() {
        const indicator = document.querySelector('.pagination-loading-indicator');
        if (indicator) {
            indicator.style.display = 'none';
        }
    }

    /**
     * Recalculate layout for responsive adjustments
     */
    recalculateLayout() {
        const paginationWrapper = document.querySelector('.modern-pagination-wrapper');
        if (!paginationWrapper) return;

        // Force re-layout
        paginationWrapper.style.display = 'none';
        paginationWrapper.offsetHeight; // Trigger reflow
        paginationWrapper.style.display = '';
    }

    /**
     * Add analytics tracking (optional)
     */
    trackPaginationUsage(action, page) {
        // Track with Google Analytics, Adobe Analytics, etc.
        if (typeof gtag !== 'undefined') {
            gtag('event', 'pagination_interaction', {
                event_category: 'Job Listing',
                event_label: action,
                value: page
            });
        }

        // Track with custom analytics
        if (typeof window.analytics !== 'undefined') {
            window.analytics.track('Pagination Used', {
                action: action,
                page: page,
                section: 'job-listings'
            });
        }
    }
}

// Auto-initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        new PaginationEnhancer();
    });
} else {
    new PaginationEnhancer();
}

// Export for manual initialization if needed
window.PaginationEnhancer = PaginationEnhancer;