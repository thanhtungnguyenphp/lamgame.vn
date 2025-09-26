{{-- LAMGAME HOMEPAGE - Updated with Optimized 4-Slide Banner --}}
@extends('layouts.master')

@section('page_title', 'LamGame.vn — Cộng đồng Game Developer Việt Nam | Việc làm Game Dev')

@section('page_description', 'Cộng đồng Game Developer Việt Nam hàng đầu. Tìm việc làm game dev, thảo luận Unity/Unreal Engine, chia sẻ source code và ý tưởng game sáng tạo. 50+ jobs mới mỗi tuần từ VNG, Gameloft.')

@push('styles')
    <link rel="stylesheet" href="{{ asset('themes/shop/emsaigon/assets/css/lamgame-optimized-banner.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('themes/shop/emsaigon/assets/js/lamgame-optimized-banner.js') }}"></script>
@endpush

@section('content')
    <!-- LamGame Optimized 4-Slide Banner -->
    <section class="hero-optimized" id="hero-banner" aria-label="Banner chính LamGame.vn">
        <button class="arrow banner-arrow prev" aria-label="Slide trước" tabindex="0">◄</button>
        <button class="arrow banner-arrow next" aria-label="Slide sau" tabindex="0">►</button>
        
        <div class="track" id="banner-track">
            <!-- Slide 1: Việc làm Game Dev -->
            <div class="slide">
                <div class="bg jobs"></div>
                <div class="overlay"></div>
                <div class="content">
                    <h1>Khám Phá Việc Làm Game Dev Hot Nhất!</h1>
                    <p>Hàng trăm vị trí từ VNG, Gameloft: Unity Developer lương 20-40tr VNĐ. <span class="dynamic-content" id="job-stats">50+ jobs tuần này</span>, apply ngay để kết nối với công ty hàng đầu!</p>
                    <div class="btns">
                        <a class="btn primary" href="{{ route('jobs.index') }}">Xem Jobs Mới</a>
                        <a class="btn secondary" href="{{ route('forum.index') }}">Hỏi kinh nghiệm phỏng vấn</a>
                    </div>
                </div>
            </div>
            
            <!-- Slide 2: Topic Forum Hot -->
            <div class="slide">
                <div class="bg forum"></div>
                <div class="overlay"></div>
                <div class="content">
                    <h1>Thảo Luận Sôi Động: Topic Forum Nóng Hổi!</h1>
                    <p>Topic hot: <span class="dynamic-content" id="hot-topic">'Unity vs Unreal cho game mobile?'</span> – <span class="dynamic-content" id="topic-stats">150 comments, 500 views, 80 likes</span> trong 24h. Tham gia ngay để chia sẻ kinh nghiệm với cộng đồng dev!</p>
                    <div class="btns">
                        <a class="btn primary" href="{{ route('forum.hot-topics') }}">Tham Gia Thảo Luận</a>
                        <a class="btn secondary" href="{{ route('forum.index') }}">Xem tất cả Topics</a>
                    </div>
                </div>
            </div>
            
            <!-- Slide 3: Bài viết mới -->
            <div class="slide">
                <div class="bg blog"></div>
                <div class="overlay"></div>
                <div class="content">
                    <h1>Bài Viết Mới Nhất Từ Developer!</h1>
                    <p>Bài mới: <span class="dynamic-content" id="new-blog">'Tối ưu hóa performance Unity cho game 3D'</span> – Đăng bởi dev @UserX, <span class="dynamic-content" id="blog-stats">200 views, 50 shares</span>. Đọc để cập nhật kiến thức hot nhất!</p>
                    <div class="btns">
                        <a class="btn primary" href="{{ route('blog.latest') }}">Đọc Bài Viết</a>
                        <a class="btn secondary" href="{{ route('blog.index') }}">Xem tất cả Blog</a>
                    </div>
                </div>
            </div>
            
            <!-- Slide 4: Game & Source mới -->
            <div class="slide">
                <div class="bg creative"></div>
                <div class="overlay"></div>
                <div class="content">
                    <h1>Khám Phá Game Mới & Ý Tưởng Sáng Tạo!</h1>
                    <p>Source mới: <span class="dynamic-content" id="new-source">'Roguelike Unity kit'</span> trên GitHub. Ý tưởng: <span class="dynamic-content" id="new-idea">'VR adventure Việt Nam folklore'</span>. Game demo từ dev cộng đồng – Download & phát triển ngay!</p>
                    <div class="btns">
                        <a class="btn primary" href="{{ route('shares.games') }}">Khám Phá & Chia Sẻ</a>
                        <a class="btn secondary" href="{{ route('sources.index') }}">Xem Source Code</a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="dots" aria-hidden="true">
            <div class="dot banner-dot" tabindex="0" aria-label="Đi đến slide 1"></div>
            <div class="dot banner-dot" tabindex="0" aria-label="Đi đến slide 2"></div>
            <div class="dot banner-dot" tabindex="0" aria-label="Đi đến slide 3"></div>
            <div class="dot banner-dot" tabindex="0" aria-label="Đi đến slide 4"></div>
        </div>
    </section>
        
        <!-- Animated Particles Layer -->
        <canvas id="particles-canvas"></canvas>
        
        <!-- Main Content Container -->
        <div class="hero-container">
            <div class="hero-grid">
                
                <!-- Content Column -->
                <div class="hero-content">
                    <!-- Dynamic Badge -->
                    <div class="hero-badge animate-fade-in">
                        <span class="badge-icon">🎮</span>
                        <span class="badge-text">#1 Game Development Training Center</span>
                    </div>
                    
                    <!-- Main Headlines -->
                    <h1 class="hero-title animate-slide-up">
                        <span class="title-line-1">Trở Thành</span>
                        <span class="title-highlight">Game Developer</span>
                        <span class="title-line-2">Chuyên Nghiệp</span>
                    </h1>
                    
                    <!-- Value Proposition -->
                    <p class="hero-subtitle animate-slide-up delay-200">
                        Từ <strong>Zero Code</strong> đến <strong>Pro Developer</strong> chỉ trong 
                        <span class="highlight-text">6 tháng</span>. 
                        Học Unity, Unreal Engine với <strong>dự án thực tế</strong> và 
                        <strong>mentor 1-on-1</strong>.
                    </p>
                    
                    <!-- Interactive Stats Counter -->
                    <div class="hero-stats-dynamic animate-slide-up delay-400">
                        <div class="stat-card" data-counter="1250">
                            <div class="stat-number">0</div>
                            <div class="stat-label">Học viên thành công</div>
                            <div class="stat-growth">+15% tháng này</div>
                        </div>
                        <div class="stat-card" data-counter="97">
                            <div class="stat-number">0</div>
                            <div class="stat-label">% Có việc làm</div>
                            <div class="stat-salary">Lương 15-45tr</div>
                        </div>
                        <div class="stat-card" data-counter="68">
                            <div class="stat-number">0</div>
                            <div class="stat-label">Công ty partner</div>
                            <div class="stat-companies">VNG, Gameloft...</div>
                        </div>
                    </div>
                    
                    <!-- Enhanced CTA Section -->
                    <div class="hero-cta-section animate-slide-up delay-600">
                        <!-- Primary CTA with urgency -->
                        <button class="btn btn-primary-xl" onclick="openCourseModal()">
                            <span class="btn-icon">🚀</span>
                            <span class="btn-text">
                                <div class="btn-main">Đăng Ký Ngay</div>
                                <div class="btn-sub">Khóa mới khai giảng 25/09</div>
                            </span>
                            <div class="btn-shine"></div>
                        </button>
                        
                        <!-- Secondary CTA -->
                        <button class="btn btn-secondary-xl" onclick="playDemoVideo()">
                            <span class="btn-icon">▶️</span>
                            <span class="btn-text">Xem Demo 2 phút</span>
                        </button>
                        
                        <!-- Trust indicators -->
                        <div class="trust-indicators">
                            <div class="trust-item">✅ Học thử 7 ngày miễn phí</div>
                            <div class="trust-item">✅ Hoàn tiền 100% nếu không hài lòng</div>
                        </div>
                    </div>
                    
                    <!-- Social Proof Avatars -->
                    <div class="social-proof animate-slide-up delay-800">
                        <div class="student-avatars">
                            <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=40&h=40&fit=crop&crop=face" alt="Student 1" class="avatar">
                            <img src="https://images.unsplash.com/photo-1494790108755-2616b612b786?w=40&h=40&fit=crop&crop=face" alt="Student 2" class="avatar">
                            <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=40&h=40&fit=crop&crop=face" alt="Student 3" class="avatar">
                            <img src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=40&h=40&fit=crop&crop=face" alt="Student 4" class="avatar">
                            <img src="https://images.unsplash.com/photo-1580489944761-15a19d654956?w=40&h=40&fit=crop&crop=face" alt="Student 5" class="avatar">
                        </div>
                        <div class="social-text">
                            <strong>245+ học viên</strong> đã đăng ký tuần này
                        </div>
                    </div>
                </div>
                
                <!-- Visual Column -->
                <div class="hero-visual-v2">
                    <!-- Video Demo Player -->
                    <div class="demo-video-container">
                        <div class="video-thumbnail">
                            <img src="https://images.unsplash.com/photo-1542751371-adc38448a05e?w=600&h=400&fit=crop" alt="Course Demo">
                            <div class="play-button" onclick="playDemoVideo()">
                                <div class="play-icon">▶️</div>
                            </div>
                            <div class="video-badge">2:30 Demo</div>
                        </div>
                    </div>
                    
                    <!-- Tech Stack Floating Cards -->
                    <div class="tech-stack-cards">
                        <div class="tech-card unity floating-card" data-tilt>
                            <div class="card-icon">🎮</div>
                            <div class="card-title">Unity 2023</div>
                            <div class="card-progress">
                                <div class="progress-bar" data-progress="85"></div>
                            </div>
                        </div>
                        
                        <div class="tech-card unreal floating-card" data-tilt>
                            <div class="card-icon">🎯</div>
                            <div class="card-title">Unreal 5.3</div>
                            <div class="card-progress">
                                <div class="progress-bar" data-progress="78"></div>
                            </div>
                        </div>
                        
                        <div class="tech-card csharp floating-card" data-tilt>
                            <div class="card-icon">💻</div>
                            <div class="card-title">C# .NET</div>
                            <div class="card-progress">
                                <div class="progress-bar" data-progress="92"></div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Student Games Showcase -->
                    <div class="games-showcase animate-slide-up delay-1000">
                        <div class="showcase-title">Học viên đã tạo ra:</div>
                        <div class="games-grid">
                            <div class="game-item">
                                <img src="https://images.unsplash.com/photo-1550745165-9bc0b252726f?w=120&h=80&fit=crop" alt="Space Adventure">
                                <div class="game-info">
                                    <h4>Space Adventure</h4>
                                    <p>by Nguyễn A</p>
                                </div>
                            </div>
                            <div class="game-item">
                                <img src="https://images.unsplash.com/photo-1614294148960-9aa740632117?w=120&h=80&fit=crop" alt="RPG Quest">
                                <div class="game-info">
                                    <h4>RPG Quest</h4>
                                    <p>by Trần B</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
        
        <!-- Scroll Indicator -->
        <div class="scroll-indicator">
            <div class="scroll-text">Khám phá thêm</div>
            <div class="scroll-arrow animate-bounce">↓</div>
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
                            Học tạo game 2D và 3D với Unity từ cơ bản đến nâng cao. Bao gồm C# programming, physics, animation và UI design.
                        </p>
                        <div class="course-meta">
                            <span class="duration">⏱️ 12 tuần</span>
                            <span class="level">📈 Cơ bản</span>
                            <span class="students">👥 1200+ học viên</span>
                        </div>
                        <div class="course-footer">
                            <div class="price">
                                <span class="current">8.500.000₫</span>
                                <span class="original">12.000.000₫</span>
                            </div>
                            <button class="btn btn-primary">Xem chi tiết</button>
                        </div>
                    </div>
                </div>
                
                <div class="course-card">
                    <div class="course-image">
                        <img src="https://images.unsplash.com/photo-1614294148960-9aa740632117?w=400&h=250&fit=crop" alt="Unreal Course" />
                    </div>
                    <div class="course-content">
                        <h3 class="course-title">Unreal Engine 5</h3>
                        <p class="course-description">
                            Tạo game AAA với Unreal Engine 5. Học Blueprint, Lighting, Materials và các tính năng mới nhất.
                        </p>
                        <div class="course-meta">
                            <span class="duration">⏱️ 16 tuần</span>
                            <span class="level">📈 Nâng cao</span>
                            <span class="students">👥 800+ học viên</span>
                        </div>
                        <div class="course-footer">
                            <div class="price">
                                <span class="current">15.000.000₫</span>
                                <span class="original">18.000.000₫</span>
                            </div>
                            <button class="btn btn-primary">Xem chi tiết</button>
                        </div>
                    </div>
                </div>
                
                <div class="course-card">
                    <div class="course-image">
                        <img src="https://images.unsplash.com/photo-1550745165-9bc0b252726f?w=400&h=250&fit=crop" alt="C# Course" />
                    </div>
                    <div class="course-content">
                        <h3 class="course-title">C# Programming cho Game</h3>
                        <p class="course-description">
                            Nắm vững C# từ cơ bản đến nâng cao. Object-oriented programming, design patterns và game architecture.
                        </p>
                        <div class="course-meta">
                            <span class="duration">⏱️ 8 tuần</span>
                            <span class="level">📈 Cơ bản</span>
                            <span class="students">👥 950+ học viên</span>
                        </div>
                        <div class="course-footer">
                            <div class="price">
                                <span class="current">6.500.000₫</span>
                                <span class="original">8.500.000₫</span>
                            </div>
                            <button class="btn btn-primary">Xem chi tiết</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Contact Section -->
    <section id="lien-he" class="contact-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Liên hệ với chúng tôi</h2>
                <p class="section-subtitle">
                    Bạn có thắc mắc hoặc muốn tư vấn? Hãy liên hệ ngay!
                </p>
            </div>
            
            <div class="contact-grid">
                <div class="contact-info">
                    <h3>Thông tin liên hệ</h3>
                    <div class="contact-item">
                        <div class="contact-icon">📍</div>
                        <div class="contact-details">
                            <h4>Địa chỉ</h4>
                            <p>123 Nguyễn Huệ, Quận 1, TP.HCM</p>
                        </div>
                    </div>
                    <div class="contact-item">
                        <div class="contact-icon">📞</div>
                        <div class="contact-details">
                            <h4>Hotline</h4>
                            <p>0909 123 456</p>
                        </div>
                    </div>
                    <div class="contact-item">
                        <div class="contact-icon">✉️</div>
                        <div class="contact-details">
                            <h4>Email</h4>
                            <p>info@lamgame.vn</p>
                        </div>
                    </div>
                </div>
                
                <div class="contact-form">
                    <h3>Đăng ký nhận tư vấn</h3>
                    <form onsubmit="handleContactSubmit(event)">
                        <div class="form-group">
                            <input type="text" name="name" placeholder="Họ và tên *" required>
                        </div>
                        <div class="form-group">
                            <input type="email" name="email" placeholder="Email *" required>
                        </div>
                        <div class="form-group">
                            <input type="tel" name="phone" placeholder="Số điện thoại">
                        </div>
                        <div class="form-group">
                            <select name="course" required>
                                <option value="">Chọn khóa học quan tâm</option>
                                <option value="unity">Unity Game Development</option>
                                <option value="unreal">Unreal Engine 5</option>
                                <option value="csharp">C# Programming</option>
                                <option value="gamedesign">Game Design</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <textarea name="message" placeholder="Tin nhắn của bạn..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary" style="width: 100%;">
                            Gửi liên hệ
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

@push('scripts')
<script>
    function handleContactSubmit(event) {
        event.preventDefault();
        
        // Track contact form submission
        if (typeof trackCTA === 'function') {
            trackCTA('contact_form_submit');
        }
        
        const formData = new FormData(event.target);
        const data = Object.fromEntries(formData.entries());
        
        console.log('Contact form data:', data);
        
        alert('Cảm ơn bạn đã liên hệ! Chúng tôi sẽ phản hồi sớm nhất.');
        event.target.reset();
    }
</script>
@endpush
@endsection
