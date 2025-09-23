{{-- LAMGAME HOMEPAGE - Updated to match lamgame.vn --}}
@extends('layouts.master')

@section('page_title', 'Làm Game • Học lập trình game, Unity, Unreal Engine từ cơ bản đến nâng cao')

@section('page_description', 'Làm Game - Trung tâm đào tạo lập trình game chuyên nghiệp. Học Unity, Unreal Engine, C#, Game Design từ cơ bản đến nâng cao. Cam kết việc làm sau khóa học.')

@push('styles')
    <!-- Mobile-First Hero Banner CSS -->
    <link rel="stylesheet" href="{{ asset('themes/shop/emsaigon/assets/css/hero-banner-mobile-first.css') }}">
    <style>
        /* Override conflicting styles from old banner */
        .hero-banner-v2 { display: none !important; }
        .hero-modern { display: none !important; }
    </style>
@endpush

@section('content')
    <!-- Mobile-First Hero Banner -->
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

    <!-- Courses Section -->
    <section id="khoa-hoc" class="courses-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Khóa học nổi bật</h2>
                <p class="section-subtitle">
                    Các khóa học được thiết kế bởi chuyên gia, phù hợp với mọi trình độ từ cơ bản đến nâng cao
                </p>
            </div>
            
            <div class="courses-grid">
                <div class="course-card featured">
                    <div class="course-badge">Phổ biến nhất</div>
                    <div class="course-image">
                        <img src="https://images.unsplash.com/photo-1556438064-2d7646166914?w=400&h=250&fit=crop" alt="Unity Course" />
                        <div class="course-overlay">
                            <div class="course-level">Cơ bản → Nâng cao</div>
                        </div>
                    </div>
                    <div class="course-content">
                        <h3 class="course-title">Unity Game Development</h3>
                        <p class="course-description">
                            Học lập trình game 2D & 3D với Unity từ cơ bản đến nâng cao. Tạo game hoàn chỉnh và publish lên Store.
                        </p>
                        <div class="course-features">
                            <div class="feature">
                                <i class="fa fa-clock-o"></i>
                                <span>3 tháng</span>
                            </div>
                            <div class="feature">
                                <i class="fa fa-users"></i>
                                <span>500+ học viên</span>
                            </div>
                            <div class="feature">
                                <i class="fa fa-star"></i>
                                <span>4.9/5</span>
                            </div>
                        </div>
                        <div class="course-price">
                            <span class="old-price">8,000,000đ</span>
                            <span class="current-price">5,000,000đ</span>
                        </div>
                        <a href="{{ route('lamgame.course-detail', 'unity') }}" class="course-btn">Xem chi tiết</a>
                    </div>
                </div>
                
                <div class="course-card">
                    <div class="course-image">
                        <img src="https://images.unsplash.com/photo-1560472354-b33ff0c44a43?w=400&h=250&fit=crop" alt="Unreal Engine Course" />
                        <div class="course-overlay">
                            <div class="course-level">Trung cấp → Nâng cao</div>
                        </div>
                    </div>
                    <div class="course-content">
                        <h3 class="course-title">Unreal Engine 5</h3>
                        <p class="course-description">
                            Phát triển game AAA chất lượng cao với Unreal Engine 5. Học từ cơ bản đến kỹ thuật nâng cao.
                        </p>
                        <div class="course-features">
                            <div class="feature">
                                <i class="fa fa-clock-o"></i>
                                <span>4 tháng</span>
                            </div>
                            <div class="feature">
                                <i class="fa fa-users"></i>
                                <span>300+ học viên</span>
                            </div>
                            <div class="feature">
                                <i class="fa fa-star"></i>
                                <span>4.8/5</span>
                            </div>
                        </div>
                        <div class="course-price">
                            <span class="current-price">7,000,000đ</span>
                        </div>
                        <a href="{{ route('lamgame.course-detail', 'unreal') }}" class="course-btn">Xem chi tiết</a>
                    </div>
                </div>
                
                <div class="course-card">
                    <div class="course-image">
                        <img src="https://images.unsplash.com/photo-1509718443690-d8e2fb3474b7?w=400&h=250&fit=crop" alt="C# Course" />
                        <div class="course-overlay">
                            <div class="course-level">Cơ bản → Trung cấp</div>
                        </div>
                    </div>
                    <div class="course-content">
                        <h3 class="course-title">C# Programming</h3>
                        <p class="course-description">
                            Nền tảng lập trình C# cho game development. Từ syntax cơ bản đến OOP và design patterns.
                        </p>
                        <div class="course-features">
                            <div class="feature">
                                <i class="fa fa-clock-o"></i>
                                <span>2 tháng</span>
                            </div>
                            <div class="feature">
                                <i class="fa fa-users"></i>
                                <span>700+ học viên</span>
                            </div>
                            <div class="feature">
                                <i class="fa fa-star"></i>
                                <span>4.9/5</span>
                            </div>
                        </div>
                        <div class="course-price">
                            <span class="current-price">4,000,000đ</span>
                        </div>
                        <a href="{{ route('lamgame.course-detail', 'csharp') }}" class="course-btn">Xem chi tiết</a>
                    </div>
                </div>
            </div>
            
            <div class="courses-cta">
                <p>Xem tất cả khóa học và tìm khóa học phù hợp với bạn</p>
                <button class="btn btn-outline" onclick="window.location.href='#'">
                    Xem tất cả khóa học
                </button>
            </div>
        </div>
    </section>

    <!-- Why Choose LamGame -->
    <section id="loi-ich" class="benefits-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Tại sao chọn Làm Game?</h2>
                <p class="section-subtitle">
                    6 lý do học viên tin tưởng và lựa chọn chúng tôi
                </p>
            </div>
            <div class="benefits-grid">
                <div class="benefit-card">
                    <div class="benefit-icon">🏆</div>
                    <div class="benefit-content">
                        <h4>Chất lượng giảng dạy</h4>
                        <p class="muted">Giảng viên là các chuyên gia có kinh nghiệm thực tế trong công nghiệp game.</p>
                    </div>
                </div>
                <div class="benefit-card">
                    <div class="benefit-icon">🛠️</div>
                    <div class="benefit-content">
                        <h4>Thực hành thực tế</h4>
                        <p class="muted">Học qua dự án thực tế, tạo game hoàn chỉnh từ đầu đến cuối.</p>
                    </div>
                </div>
                <div class="benefit-card">
                    <div class="benefit-icon">📚</div>
                    <div class="benefit-content">
                        <h4>Chương trình cập nhật</h4>
                        <p class="muted">Nội dung luôn được cập nhật theo xu hướng công nghệ mới nhất.</p>
                    </div>
                </div>
                <div class="benefit-card">
                    <div class="benefit-icon">👥</div>
                    <div class="benefit-content">
                        <h4>Lớp học nhỏ</h4>
                        <p class="muted">Tối đa 15 học viên/lớp, đảm bảo chất lượng và sự chăm sóc tận tình.</p>
                    </div>
                </div>
                <div class="benefit-card">
                    <div class="benefit-icon">🎯</div>
                    <div class="benefit-content">
                        <h4>Hỗ trợ tìm việc làm</h4>
                        <p class="muted">Kết nối với hơn 50 doanh nghiệp đối tác, tỷ lệ có việc làm 95%.</p>
                    </div>
                </div>
                <div class="benefit-card">
                    <div class="benefit-icon">✨</div>
                    <div class="benefit-content">
                        <h4>Hỗ trợ trọn đời</h4>
                        <p class="muted">Tư vấn miễn phí, hỗ trợ 24/7 ngay cả sau khi hoàn thành khóa học.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Student Success Stories -->
    <section id="thanh-cong" class="success-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Câu chuyện thành công</h2>
                <p class="section-subtitle">
                    Nghe chia sẻ từ những học viên đã thành công sau khi học tại Làm Game
                </p>
            </div>
            
            <div class="testimonials-grid">
                <div class="testimonial-card">
                    <div class="testimonial-header">
                        <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=100&h=100&fit=crop&crop=face" alt="Nguyễn Văn A" class="testimonial-avatar">
                        <div class="testimonial-info">
                            <h4>Nguyễn Văn A</h4>
                            <p>Unity Developer tầi VNG Corporation</p>
                        </div>
                    </div>
                    <div class="testimonial-content">
                        <p>"Sau khóa học Unity tại Làm Game, tôi đã tự tin xin việc và nhận lương 25 triệu/tháng. Giảng viên rất tận tình, hỗ trợ cả sau khi ra trường."</p>
                    </div>
                    <div class="testimonial-rating">
                        <span class="stars">★★★★★</span>
                    </div>
                </div>
                
                <div class="testimonial-card">
                    <div class="testimonial-header">
                        <img src="https://images.unsplash.com/photo-1494790108755-2616b612b786?w=100&h=100&fit=crop&crop=face" alt="Trần Thị B" class="testimonial-avatar">
                        <div class="testimonial-info">
                            <h4>Trần Thị B</h4>
                            <p>Game Designer tầi Gameloft</p>
                        </div>
                    </div>
                    <div class="testimonial-content">
                        <p>"Mình không có background IT nhưng vẫn học được nhờ chương trình học cụ thể. Giờ mình làm Game Designer và rất yêu thích công việc này."</p>
                    </div>
                    <div class="testimonial-rating">
                        <span class="stars">★★★★★</span>
                    </div>
                </div>
                
                <div class="testimonial-card">
                    <div class="testimonial-header">
                        <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=100&h=100&fit=crop&crop=face" alt="Lê Minh C" class="testimonial-avatar">
                        <div class="testimonial-info">
                            <h4>Lê Minh C</h4>
                            <p>Indie Game Developer</p>
                        </div>
                    </div>
                    <div class="testimonial-content">
                        <p>"Sau khóa học, mình đã tự phát triển game riêng và kiếm được 15 triệu/tháng. Làm Game không chỉ dạy kỹ thuật mà còn hỗ trợ marketing game nữa."</p>
                    </div>
                    <div class="testimonial-rating">
                        <span class="stars">★★★★★</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Job Opportunities -->
    <section id="co-hoi-viec-lam" class="job-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Cơ hội việc làm</h2>
                <p class="section-subtitle">
                    Nghiềp game development đang rất hot và có mức lương cao tại Việt Nam
                </p>
            </div>
            
            <div class="job-stats-grid">
                <div class="job-stat-card">
                    <div class="stat-icon">💰</div>
                    <h3>15-40 triệu VNĐ</h3>
                    <p>Mức lương Unity Developer</p>
                </div>
                <div class="job-stat-card">
                    <div class="stat-icon">📈</div>
                    <h3>45% tăng trưởng</h3>
                    <p>Nhu cầu tuyển dụng hàng năm</p>
                </div>
                <div class="job-stat-card">
                    <div class="stat-icon">🎯</div>
                    <h3>95% thành công</h3>
                    <p>Tỷ lệ có việc sau khóa học</p>
                </div>
                <div class="job-stat-card">
                    <div class="stat-icon">🏢</div>
                    <h3>50+ doanh nghiệp</h3>
                    <p>Đối tác tuyển dụng</p>
                </div>
            </div>
            
            <div class="cta-section">
                <h3>Sẵn sàng bắt đầu sự nghiệp game development?</h3>
                <p>Liên hệ ngay để nhận tư vấn miễn phí và lộ trình học phù hợp</p>
                <div class="cta-buttons">
                    <button class="btn btn-primary btn-large" onclick="scrollToSection('#lien-he')">
                        <i class="fa fa-phone"></i> Tư vấn miễn phí
                    </button>
                    <a href="{{ route('lamgame.viec-lam-game') }}" class="btn btn-outline btn-large">
                        <i class="fa fa-briefcase"></i> Xem việc làm
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="lien-he" class="contact-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Liên hệ tư vấn</h2>
                <p class="section-subtitle">
                    Nhận tư vấn miễn phí và lộ trình học phù hợp nhất cho bạn
                </p>
            </div>
            
            <div class="contact-content">
                <div class="contact-form">
                    <form class="modern-form" onsubmit="handleContactSubmit(event)">
                        <div class="form-grid">
                            <div class="form-field">
                                <label for="contact-name">Họ và tên *</label>
                                <input id="contact-name" name="name" required placeholder="Nguyễn Văn A">
                            </div>
                            <div class="form-field">
                                <label for="contact-phone">Số điện thoại *</label>
                                <input id="contact-phone" name="phone" type="tel" required placeholder="0909 123 456">
                            </div>
                        </div>
                        <div class="form-field">
                            <label for="contact-email">Email</label>
                            <input id="contact-email" name="email" type="email" placeholder="example@email.com">
                        </div>
                        <div class="form-field">
                            <label for="contact-course">Khóa học quan tâm</label>
                            <select id="contact-course" name="course">
                                <option value="">Chọn khóa học</option>
                                <option value="unity">Unity Game Development</option>
                                <option value="unreal">Unreal Engine 5</option>
                                <option value="csharp">C# Programming</option>
                                <option value="game-design">Game Design</option>
                            </select>
                        </div>
                        <div class="form-field">
                            <label for="contact-message">Lời nhắn</label>
                            <textarea id="contact-message" name="message" rows="4" placeholder="Câu hỏi hoặc thông tin bạn muốn biết thêm..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary btn-large" style="width: 100%;">
                            <i class="fa fa-paper-plane"></i> Gửi thông tin
                        </button>
                    </form>
                </div>
                
                <div class="contact-info">
                    <div class="info-card">
                        <h3>📍 Địa chỉ trụ sở</h3>
                        <p>Tầng 7, Tòa nhà ABC<br>123 Nguyễn Huệ, Quận 1<br>TP. Hồ Chí Minh</p>
                    </div>
                    <div class="info-card">
                        <h3>📞 Liên hệ trực tiếp</h3>
                        <p>Hotline: <a href="tel:0909123456">0909 123 456</a><br>
                        Email: <a href="mailto:info@lamgame.vn">info@lamgame.vn</a></p>
                    </div>
                    <div class="info-card">
                        <h3>⏰ Giờ làm việc</h3>
                        <p>Thứ 2 - Thứ 6: 8:00 - 20:00<br>
                        Thứ 7: 9:00 - 17:00<br>
                        Chủ nhật: 10:00 - 16:00</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @push('scripts')
    <script>
        // Smooth scrolling to sections
        function scrollToSection(selector) {
            const element = document.querySelector(selector);
            if (element) {
                element.scrollIntoView({ 
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        }
        
        // Contact form submission handler
        function handleContactSubmit(event) {
            event.preventDefault();
            
            // Collect form data
            const formData = new FormData(event.target);
            const data = Object.fromEntries(formData.entries());
            
            // Here you would normally send data to your server
            console.log('Contact form data:', data);
            
            // Show success message
            alert('Cảm ơn bạn đã gửi thông tin! Chúng tôi sẽ liên hệ với bạn trong thời gian sớm nhất.');
            
            // Optional: Reset form
            event.target.reset();
        }
        
        // Track user interactions (for analytics)
        function trackRegistration() {
            console.log('Registration attempt tracked');
            // Add your analytics code here
        }
        
        function trackCTA(action) {
            console.log('CTA clicked:', action);
            // Add your analytics code here
        }

        // Animate elements on scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        // Initialize when page loads
        document.addEventListener('DOMContentLoaded', function() {
            // Observe all sections for animation
            document.querySelectorAll('section').forEach(section => {
                section.style.opacity = '0';
                section.style.transform = 'translateY(20px)';
                section.style.transition = 'all 0.6s ease';
                observer.observe(section);
            });
            
            // Initialize hero section immediately
            const heroSection = document.querySelector('.hero-modern');
            if (heroSection) {
                heroSection.style.opacity = '1';
                heroSection.style.transform = 'translateY(0)';
            }
        });
    </script>
    @endpush
@endsection
