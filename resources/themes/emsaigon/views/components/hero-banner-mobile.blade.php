{{-- MOBILE-FIRST HERO BANNER V3 --}}
<section class="hero-banner-v3" id="hero-banner-v3">
    <!-- Simplified Background for Mobile Performance -->
    <div class="hero-background-mobile"></div>
    
    <!-- Main Content Container -->
    <div class="hero-container-v3">
        <!-- Content Section -->
        <div class="hero-content-v3">
            <!-- Mobile-Optimized Badge -->
            <div class="hero-badge-v3 animate-fade-in">
                <span class="badge-icon-v3">🎮</span>
                <span class="badge-text">#1 Game Dev Center</span>
            </div>
            
            <!-- Mobile-First Headlines -->
            <h1 class="hero-title-v3 animate-slide-up">
                <span class="title-line">Trở Thành</span>
                <span class="title-highlight-v3">Game Developer</span>
                <span class="title-line">Chuyên Nghiệp</span>
            </h1>
            
            <!-- Mobile-Optimized Value Proposition -->
            <p class="hero-subtitle-v3 animate-slide-up delay-200">
                Từ <strong>Zero Code</strong> đến <strong>Pro Developer</strong> chỉ trong 
                <span class="highlight-text-v3">6 tháng</span>. 
                Học Unity, Unreal với <strong>dự án thực tế</strong>.
            </p>
            
            <!-- Mobile-First Stats -->
            <div class="hero-stats-mobile animate-slide-up delay-400">
                <div class="stat-card-mobile" data-counter="1250">
                    <div class="stat-number-mobile" data-target="1250">0</div>
                    <div class="stat-label-mobile">Học viên thành công</div>
                    <div class="stat-growth-mobile">+15% tháng này</div>
                </div>
                <div class="stat-card-mobile" data-counter="97">
                    <div class="stat-number-mobile" data-target="97">0</div>
                    <div class="stat-label-mobile">% Có việc làm</div>
                    <div class="stat-growth-mobile">Lương 15-45tr</div>
                </div>
                <div class="stat-card-mobile" data-counter="68">
                    <div class="stat-number-mobile" data-target="68">0</div>
                    <div class="stat-label-mobile">Công ty partner</div>
                    <div class="stat-growth-mobile">VNG, Gameloft...</div>
                </div>
            </div>
            
            <!-- Mobile-Optimized CTAs -->
            <div class="hero-cta-mobile animate-slide-up delay-600">
                <!-- Primary CTA with enhanced mobile UX -->
                <button class="btn-primary-mobile" onclick="openCourseModal()" 
                        aria-label="Đăng ký khóa học game development">
                    <span class="btn-icon-mobile">🚀</span>
                    <span>Đăng Ký Ngay</span>
                </button>
                
                <!-- Secondary CTA -->
                <button class="btn-secondary-mobile" onclick="playDemoVideo()" 
                        aria-label="Xem video demo khóa học">
                    <span class="btn-icon-mobile">▶️</span>
                    <span>Xem Demo 2 phút</span>
                </button>
                
                <!-- Trust indicators -->
                <div class="trust-indicators-mobile">
                    <div class="trust-item-mobile">✅ Học thử 7 ngày miễn phí</div>
                    <div class="trust-item-mobile">✅ Hoàn tiền 100% nếu không hài lòng</div>
                </div>
            </div>
            
            <!-- Social Proof for Mobile -->
            <div class="social-proof-mobile animate-slide-up delay-800">
                <div class="student-avatars-mobile">
                    <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=80&h=80&fit=crop&crop=face" 
                         alt="Học viên 1" class="avatar-mobile" loading="lazy">
                    <img src="https://images.unsplash.com/photo-1494790108755-2616b612b786?w=80&h=80&fit=crop&crop=face" 
                         alt="Học viên 2" class="avatar-mobile" loading="lazy">
                    <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=80&h=80&fit=crop&crop=face" 
                         alt="Học viên 3" class="avatar-mobile" loading="lazy">
                    <img src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=80&h=80&fit=crop&crop=face" 
                         alt="Học viên 4" class="avatar-mobile" loading="lazy">
                </div>
                <div class="social-text-mobile">
                    <strong>245+ học viên</strong> đã đăng ký tuần này
                </div>
            </div>
        </div>
        
        <!-- Visual Section - Mobile Optimized -->
        <div class="hero-visual-mobile animate-slide-up delay-1000">
            <!-- Mobile-Optimized Video Demo -->
            <div class="demo-video-mobile">
                <div class="video-thumbnail-mobile" onclick="playDemoVideo()" 
                     role="button" aria-label="Phát video demo khóa học" tabindex="0">
                    <img src="https://images.unsplash.com/photo-1542751371-adc38448a05e?w=600&h=340&fit=crop" 
                         alt="Demo khóa học game development" loading="lazy">
                    <div class="play-button-mobile">
                        <div class="play-icon-mobile">▶️</div>
                    </div>
                    <div class="video-badge-mobile">2:30</div>
                </div>
            </div>
            
            <!-- Mobile-First Tech Stack -->
            <div class="tech-stack-mobile">
                <div class="tech-card-mobile" role="button" tabindex="0" aria-label="Unity Engine">
                    <div class="card-icon">🎮</div>
                    <div class="card-title">Unity</div>
                </div>
                
                <div class="tech-card-mobile" role="button" tabindex="0" aria-label="Unreal Engine">
                    <div class="card-icon">🎯</div>
                    <div class="card-title">Unreal</div>
                </div>
                
                <div class="tech-card-mobile" role="button" tabindex="0" aria-label="C# Programming">
                    <div class="card-icon">💻</div>
                    <div class="card-title">C#</div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Mobile-Optimized Scroll Indicator -->
    <div class="scroll-indicator-mobile" onclick="scrollToNextSection()" 
         role="button" aria-label="Cuộn xuống để xem thêm" tabindex="0">
        <div class="scroll-text-mobile">Khám phá thêm</div>
        <div class="scroll-arrow-mobile">↓</div>
    </div>
</section>

{{-- Enhanced JavaScript for Mobile Experience --}}
<script>
// Mobile-First Interactive Features
class MobileHeroBanner {
    constructor() {
        this.init();
        this.setupTouchEvents();
        this.setupIntersectionObserver();
        this.startCounterAnimation();
    }
    
    init() {
        // Preload critical resources
        this.preloadImages();
        
        // Setup accessibility
        this.setupAccessibility();
        
        // Initialize performance monitoring
        this.initPerformanceTracking();
    }
    
    preloadImages() {
        const criticalImages = [
            'https://images.unsplash.com/photo-1542751371-adc38448a05e?w=600&h=340&fit=crop'
        ];
        
        criticalImages.forEach(src => {
            const img = new Image();
            img.src = src;
        });
    }
    
    setupTouchEvents() {
        // Enhanced touch feedback for mobile
        const touchElements = document.querySelectorAll(
            '.btn-primary-mobile, .btn-secondary-mobile, .stat-card-mobile, .tech-card-mobile'
        );
        
        touchElements.forEach(element => {
            let touchStartTime;
            
            element.addEventListener('touchstart', (e) => {
                touchStartTime = Date.now();
                element.style.transform = 'scale(0.98)';
                
                // Haptic feedback if supported
                if (navigator.vibrate) {
                    navigator.vibrate(10);
                }
            }, { passive: true });
            
            element.addEventListener('touchend', (e) => {
                const touchDuration = Date.now() - touchStartTime;
                
                setTimeout(() => {
                    element.style.transform = '';
                }, Math.max(0, 150 - touchDuration));
            }, { passive: true });
            
            element.addEventListener('touchcancel', () => {
                element.style.transform = '';
            }, { passive: true });
        });
    }
    
    setupIntersectionObserver() {
        // Lazy load animations for better performance
        const animatedElements = document.querySelectorAll('[class*="animate-"]');
        
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.animationPlayState = 'running';
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);
        
        animatedElements.forEach(el => {
            el.style.animationPlayState = 'paused';
            observer.observe(el);
        });
    }
    
    startCounterAnimation() {
        const counters = document.querySelectorAll('.stat-number-mobile[data-target]');
        
        const animateCounter = (counter) => {
            const target = parseInt(counter.dataset.target);
            const duration = 2000; // 2 seconds
            const start = Date.now();
            const startValue = 0;
            
            const updateCounter = () => {
                const elapsed = Date.now() - start;
                const progress = Math.min(elapsed / duration, 1);
                
                // Easing function for smooth animation
                const easeOutQuart = 1 - Math.pow(1 - progress, 4);
                const currentValue = Math.floor(startValue + (target - startValue) * easeOutQuart);
                
                counter.textContent = currentValue;
                
                if (progress < 1) {
                    requestAnimationFrame(updateCounter);
                }
            };
            
            requestAnimationFrame(updateCounter);
        };
        
        // Trigger counter animation when stats come into view
        const statsObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const counter = entry.target;
                    animateCounter(counter);
                    statsObserver.unobserve(counter);
                }
            });
        }, { threshold: 0.5 });
        
        counters.forEach(counter => statsObserver.observe(counter));
    }
    
    setupAccessibility() {
        // Enhanced keyboard navigation for mobile
        const interactiveElements = document.querySelectorAll(
            '.btn-primary-mobile, .btn-secondary-mobile, .video-thumbnail-mobile, ' +
            '.scroll-indicator-mobile, .tech-card-mobile'
        );
        
        interactiveElements.forEach(element => {
            element.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    element.click();
                }
            });
        });
    }
    
    initPerformanceTracking() {
        // Track mobile-specific performance metrics
        if ('PerformanceObserver' in window) {
            // Track First Contentful Paint
            const observer = new PerformanceObserver((list) => {
                list.getEntries().forEach(entry => {
                    if (entry.name === 'first-contentful-paint') {
                        // Log to analytics
                        this.trackPerformance('FCP', entry.startTime);
                    }
                });
            });
            observer.observe({ entryTypes: ['paint'] });
        }
    }
    
    trackPerformance(metric, value) {
        // Send to analytics (Google Analytics, Adobe Analytics, etc.)
        if (typeof gtag !== 'undefined') {
            gtag('event', 'mobile_performance', {
                event_category: 'Hero Banner',
                event_label: metric,
                value: Math.round(value)
            });
        }
    }
}

// Global functions for banner interactions
window.openCourseModal = function() {
    // Track interaction
    if (typeof gtag !== 'undefined') {
        gtag('event', 'cta_click', {
            event_category: 'Hero Banner',
            event_label: 'Primary CTA - Mobile',
            value: 1
        });
    }
    
    // Open course registration modal
    // Implementation depends on your modal system
    alert('Mở form đăng ký khóa học'); // Placeholder
};

window.playDemoVideo = function() {
    // Track interaction
    if (typeof gtag !== 'undefined') {
        gtag('event', 'video_play', {
            event_category: 'Hero Banner',
            event_label: 'Demo Video - Mobile',
            value: 1
        });
    }
    
    // Open video player
    // Implementation depends on your video system
    alert('Phát video demo'); // Placeholder
};

window.scrollToNextSection = function() {
    const nextSection = document.querySelector('#khoa-hoc, .courses-section');
    if (nextSection) {
        nextSection.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });
    }
};

// Initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        new MobileHeroBanner();
    });
} else {
    new MobileHeroBanner();
}

// Handle orientation change for mobile
window.addEventListener('orientationchange', () => {
    setTimeout(() => {
        // Recalculate layouts if needed
        window.dispatchEvent(new Event('resize'));
    }, 100);
});
</script>

{{-- CSS Animation Styles --}}
<style>
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes slideUp {
    from { opacity: 0; transform: translateY(30px); }
    to { opacity: 1; transform: translateY(0); }
}

.animate-fade-in {
    animation: fadeIn 0.6s ease-out;
}

.animate-slide-up {
    animation: slideUp 0.8s ease-out;
}

.delay-200 { animation-delay: 0.2s; }
.delay-400 { animation-delay: 0.4s; }
.delay-600 { animation-delay: 0.6s; }
.delay-800 { animation-delay: 0.8s; }
.delay-1000 { animation-delay: 1s; }
</style>