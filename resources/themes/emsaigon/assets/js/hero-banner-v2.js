/* ==============================================
   HERO BANNER V2 - Interactive Features
   ============================================== */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize all hero banner components
    initParticles();
    initBackgroundSlider();
    initCounterAnimation();
    initProgressBars();
    initTiltCards();
    initScrollIndicator();
    bindCTAEvents();
});

/* ==============================================
   PARTICLE ANIMATION
   ============================================== */
function initParticles() {
    const canvas = document.getElementById('particles-canvas');
    if (!canvas) return;
    
    const ctx = canvas.getContext('2d');
    let particles = [];
    let animationId;

    // Resize canvas
    function resizeCanvas() {
        canvas.width = canvas.offsetWidth;
        canvas.height = canvas.offsetHeight;
    }

    // Particle class
    class Particle {
        constructor() {
            this.x = Math.random() * canvas.width;
            this.y = Math.random() * canvas.height;
            this.size = Math.random() * 3 + 1;
            this.speedX = (Math.random() - 0.5) * 2;
            this.speedY = (Math.random() - 0.5) * 2;
            this.opacity = Math.random() * 0.5 + 0.2;
        }

        update() {
            this.x += this.speedX;
            this.y += this.speedY;

            // Wrap particles around screen
            if (this.x > canvas.width) this.x = 0;
            if (this.x < 0) this.x = canvas.width;
            if (this.y > canvas.height) this.y = 0;
            if (this.y < 0) this.y = canvas.height;
        }

        draw() {
            ctx.save();
            ctx.globalAlpha = this.opacity;
            ctx.fillStyle = '#ffffff';
            ctx.beginPath();
            ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
            ctx.fill();
            ctx.restore();
        }
    }

    // Initialize particles
    function createParticles() {
        particles = [];
        const particleCount = Math.min(100, canvas.width / 10);
        
        for (let i = 0; i < particleCount; i++) {
            particles.push(new Particle());
        }
    }

    // Animation loop
    function animate() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        
        particles.forEach(particle => {
            particle.update();
            particle.draw();
        });

        // Draw connections between nearby particles
        drawConnections();
        
        animationId = requestAnimationFrame(animate);
    }

    // Draw lines between nearby particles
    function drawConnections() {
        ctx.strokeStyle = 'rgba(255, 255, 255, 0.1)';
        ctx.lineWidth = 1;

        for (let i = 0; i < particles.length; i++) {
            for (let j = i + 1; j < particles.length; j++) {
                const dx = particles[i].x - particles[j].x;
                const dy = particles[i].y - particles[j].y;
                const distance = Math.sqrt(dx * dx + dy * dy);

                if (distance < 150) {
                    ctx.beginPath();
                    ctx.moveTo(particles[i].x, particles[i].y);
                    ctx.lineTo(particles[j].x, particles[j].y);
                    ctx.stroke();
                }
            }
        }
    }

    // Initialize
    resizeCanvas();
    createParticles();
    animate();

    // Handle resize
    window.addEventListener('resize', () => {
        resizeCanvas();
        createParticles();
    });

    // Cleanup on page unload
    window.addEventListener('beforeunload', () => {
        if (animationId) {
            cancelAnimationFrame(animationId);
        }
    });
}

/* ==============================================
   BACKGROUND SLIDER
   ============================================== */
function initBackgroundSlider() {
    const slides = document.querySelectorAll('.bg-slide');
    if (slides.length === 0) return;

    let currentSlide = 0;
    const slideInterval = 6000; // 6 seconds

    function nextSlide() {
        // Remove active class from current slide
        slides[currentSlide].classList.remove('active');
        
        // Move to next slide
        currentSlide = (currentSlide + 1) % slides.length;
        
        // Add active class to new slide
        slides[currentSlide].classList.add('active');
    }

    // Start auto-rotation
    setInterval(nextSlide, slideInterval);
}

/* ==============================================
   COUNTER ANIMATION
   ============================================== */
function initCounterAnimation() {
    const counters = document.querySelectorAll('.stat-card[data-counter]');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                animateCounter(entry.target);
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.5 });

    counters.forEach(counter => {
        observer.observe(counter);
    });

    function animateCounter(element) {
        const target = parseInt(element.dataset.counter);
        const numberElement = element.querySelector('.stat-number');
        const duration = 2000;
        const step = target / (duration / 16);
        let current = 0;

        const timer = setInterval(() => {
            current += step;
            if (current >= target) {
                current = target;
                clearInterval(timer);
            }
            
            // Format number with appropriate suffix
            let displayValue = Math.floor(current);
            if (target >= 1000) {
                displayValue = Math.floor(current);
            }
            if (target < 100 && target > 10) {
                displayValue = Math.floor(current) + '%';
            } else if (target >= 100) {
                displayValue = Math.floor(current);
                if (displayValue >= 1000) {
                    displayValue = (displayValue / 1000).toFixed(1) + 'k';
                }
            }
            
            numberElement.textContent = displayValue;
        }, 16);
    }
}

/* ==============================================
   PROGRESS BARS ANIMATION
   ============================================== */
function initProgressBars() {
    const progressBars = document.querySelectorAll('.progress-bar[data-progress]');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const bar = entry.target;
                const progress = bar.dataset.progress;
                
                setTimeout(() => {
                    bar.style.width = progress + '%';
                }, Math.random() * 500 + 500); // Random delay for staggered effect
                
                observer.unobserve(bar);
            }
        });
    }, { threshold: 0.5 });

    progressBars.forEach(bar => {
        observer.observe(bar);
    });
}

/* ==============================================
   TILT CARD EFFECTS
   ============================================== */
function initTiltCards() {
    const tiltCards = document.querySelectorAll('[data-tilt]');
    
    tiltCards.forEach(card => {
        card.addEventListener('mouseenter', handleMouseEnter);
        card.addEventListener('mousemove', handleMouseMove);
        card.addEventListener('mouseleave', handleMouseLeave);
    });

    function handleMouseEnter(e) {
        e.target.style.transition = 'none';
    }

    function handleMouseMove(e) {
        const card = e.target;
        const rect = card.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;
        
        const centerX = rect.width / 2;
        const centerY = rect.height / 2;
        
        const rotateX = (y - centerY) / 10;
        const rotateY = (centerX - x) / 10;
        
        card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) translateZ(20px)`;
    }

    function handleMouseLeave(e) {
        e.target.style.transition = 'transform 0.5s ease';
        e.target.style.transform = 'perspective(1000px) rotateX(0deg) rotateY(0deg) translateZ(0px)';
    }
}

/* ==============================================
   SCROLL INDICATOR
   ============================================== */
function initScrollIndicator() {
    const scrollIndicator = document.querySelector('.scroll-indicator');
    if (!scrollIndicator) return;

    scrollIndicator.addEventListener('click', () => {
        const nextSection = document.querySelector('.hero-banner-v2').nextElementSibling;
        if (nextSection) {
            nextSection.scrollIntoView({ 
                behavior: 'smooth',
                block: 'start'
            });
        } else {
            window.scrollTo({
                top: window.innerHeight,
                behavior: 'smooth'
            });
        }
    });

    // Hide scroll indicator when user scrolls
    let scrollTimer;
    window.addEventListener('scroll', () => {
        clearTimeout(scrollTimer);
        if (window.scrollY > 100) {
            scrollIndicator.style.opacity = '0';
        } else {
            scrollIndicator.style.opacity = '1';
        }
        
        scrollTimer = setTimeout(() => {
            scrollIndicator.style.opacity = '1';
        }, 2000);
    });
}

/* ==============================================
   CTA BUTTON EVENTS
   ============================================== */
function bindCTAEvents() {
    // Course registration modal
    window.openCourseModal = function() {
        // Check if a registration modal exists
        const existingModal = document.getElementById('course-registration-modal');
        if (existingModal) {
            existingModal.style.display = 'block';
        } else {
            // Create basic modal for demonstration
            createRegistrationModal();
        }
        
        // Track analytics
        if (typeof gtag !== 'undefined') {
            gtag('event', 'course_registration_clicked', {
                'event_category': 'engagement',
                'event_label': 'hero_banner_primary_cta'
            });
        }
    };

    // Demo video player
    window.playDemoVideo = function() {
        // Check if video modal exists
        const existingVideoModal = document.getElementById('demo-video-modal');
        if (existingVideoModal) {
            existingVideoModal.style.display = 'block';
        } else {
            // Create video modal
            createVideoModal();
        }
        
        // Track analytics
        if (typeof gtag !== 'undefined') {
            gtag('event', 'demo_video_played', {
                'event_category': 'engagement',
                'event_label': 'hero_banner_video_cta'
            });
        }
    };
}

/* ==============================================
   MODAL CREATION FUNCTIONS
   ============================================== */
function createRegistrationModal() {
    const modal = document.createElement('div');
    modal.id = 'course-registration-modal';
    modal.className = 'modal-overlay';
    modal.innerHTML = `
        <div class="modal-content">
            <div class="modal-header">
                <h3>Đăng Ký Khóa Học Game Development</h3>
                <button class="modal-close">&times;</button>
            </div>
            <div class="modal-body">
                <form class="registration-form">
                    <div class="form-group">
                        <label>Họ và Tên *</label>
                        <input type="text" name="name" required>
                    </div>
                    <div class="form-group">
                        <label>Email *</label>
                        <input type="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label>Số điện thoại *</label>
                        <input type="tel" name="phone" required>
                    </div>
                    <div class="form-group">
                        <label>Khóa học quan tâm</label>
                        <select name="course">
                            <option value="unity">Unity Game Development</option>
                            <option value="unreal">Unreal Engine 5</option>
                            <option value="csharp">C# Programming</option>
                            <option value="fullstack">Full-stack Game Development</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Kinh nghiệm lập trình</label>
                        <select name="experience">
                            <option value="beginner">Người mới bắt đầu</option>
                            <option value="intermediate">Đã có kinh nghiệm cơ bản</option>
                            <option value="advanced">Đã có kinh nghiệm nâng cao</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary-modal">Đăng Ký Ngay</button>
                </form>
            </div>
        </div>
    `;

    document.body.appendChild(modal);
    addModalStyles();
    bindModalEvents(modal);
    modal.style.display = 'block';
}

function createVideoModal() {
    const modal = document.createElement('div');
    modal.id = 'demo-video-modal';
    modal.className = 'modal-overlay';
    modal.innerHTML = `
        <div class="modal-content modal-video">
            <div class="modal-header">
                <h3>Demo Khóa Học Game Development</h3>
                <button class="modal-close">&times;</button>
            </div>
            <div class="modal-body">
                <div class="video-container">
                    <iframe 
                        width="100%" 
                        height="315" 
                        src="https://www.youtube.com/embed/dQw4w9WgXcQ" 
                        frameborder="0" 
                        allowfullscreen>
                    </iframe>
                </div>
                <div class="video-info">
                    <h4>Khám phá quy trình học tập tại LamGame</h4>
                    <p>Video này giới thiệu về:</p>
                    <ul>
                        <li>✅ Phương pháp học tập hiệu quả</li>
                        <li>✅ Dự án thực tế được làm trong khóa học</li>
                        <li>✅ Mentor 1-on-1 support</li>
                        <li>✅ Cơ hội việc làm sau tốt nghiệp</li>
                    </ul>
                    <button class="btn btn-primary-modal" onclick="openCourseModal(); document.getElementById('demo-video-modal').style.display='none';">
                        Đăng Ký Học Thử Miễn Phí
                    </button>
                </div>
            </div>
        </div>
    `;

    document.body.appendChild(modal);
    addModalStyles();
    bindModalEvents(modal);
    modal.style.display = 'block';
}

function addModalStyles() {
    if (document.getElementById('modal-styles')) return;

    const styles = document.createElement('style');
    styles.id = 'modal-styles';
    styles.textContent = `
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.8);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10000;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        .modal-overlay[style*="block"] {
            opacity: 1;
        }
        .modal-content {
            background: white;
            border-radius: 20px;
            max-width: 500px;
            width: 90vw;
            max-height: 90vh;
            overflow-y: auto;
            transform: scale(0.8);
            transition: transform 0.3s ease;
        }
        .modal-overlay[style*="block"] .modal-content {
            transform: scale(1);
        }
        .modal-video {
            max-width: 800px;
        }
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem;
            border-bottom: 1px solid #eee;
        }
        .modal-header h3 {
            margin: 0;
            color: #1a1a1a;
        }
        .modal-close {
            background: none;
            border: none;
            font-size: 2rem;
            cursor: pointer;
            color: #666;
        }
        .modal-close:hover {
            color: #000;
        }
        .modal-body {
            padding: 1.5rem;
        }
        .form-group {
            margin-bottom: 1rem;
        }
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #333;
        }
        .form-group input, .form-group select {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #e1e5e9;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }
        .form-group input:focus, .form-group select:focus {
            outline: none;
            border-color: #ff6b35;
        }
        .btn-primary-modal {
            width: 100%;
            background: linear-gradient(45deg, #ff6b35, #ffd700);
            color: #1a1a1a;
            border: none;
            border-radius: 10px;
            padding: 1rem 2rem;
            font-size: 1.1rem;
            font-weight: 700;
            cursor: pointer;
            transition: transform 0.3s ease;
        }
        .btn-primary-modal:hover {
            transform: translateY(-2px);
        }
        .video-container {
            position: relative;
            width: 100%;
            height: 0;
            padding-bottom: 56.25%;
            margin-bottom: 1rem;
        }
        .video-container iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border-radius: 10px;
        }
        .video-info h4 {
            margin: 0 0 1rem 0;
            color: #1a1a1a;
        }
        .video-info ul {
            margin-bottom: 1.5rem;
            padding-left: 0;
            list-style: none;
        }
        .video-info li {
            margin-bottom: 0.5rem;
            color: #333;
        }
    `;

    document.head.appendChild(styles);
}

function bindModalEvents(modal) {
    // Close button
    const closeBtn = modal.querySelector('.modal-close');
    closeBtn.addEventListener('click', () => {
        modal.style.display = 'none';
        setTimeout(() => {
            modal.remove();
        }, 300);
    });

    // Click outside to close
    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.style.display = 'none';
            setTimeout(() => {
                modal.remove();
            }, 300);
        }
    });

    // Form submission (if registration modal)
    const form = modal.querySelector('.registration-form');
    if (form) {
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            
            // Collect form data
            const formData = new FormData(form);
            const data = Object.fromEntries(formData);
            
            // Here you would typically send data to your backend
            console.log('Registration data:', data);
            
            // Show success message
            alert('Cảm ơn bạn đã đăng ký! Chúng tôi sẽ liên hệ với bạn sớm.');
            
            // Close modal
            modal.style.display = 'none';
            setTimeout(() => {
                modal.remove();
            }, 300);
        });
    }

    // ESC key to close
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && modal.style.display === 'block') {
            modal.style.display = 'none';
            setTimeout(() => {
                modal.remove();
            }, 300);
        }
    });
}

/* ==============================================
   UTILITY FUNCTIONS
   ============================================== */

// Smooth scroll to section
window.scrollToSection = function(sectionId) {
    const section = document.querySelector(sectionId);
    if (section) {
        section.scrollIntoView({ 
            behavior: 'smooth',
            block: 'start'
        });
    }
};