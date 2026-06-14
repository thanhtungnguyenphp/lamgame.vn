{{-- LAMGAME HOMEPAGE - Updated with Optimized 4-Slide Banner --}}
@extends('layouts.master')

@section('page_title', 'LamGame.vn — Cộng đồng Game Developer Việt Nam | Việc làm Game Dev')

@section('page_description', 'Cộng đồng Game Developer Việt Nam hàng đầu. Tìm việc làm game dev, thảo luận Unity/Unreal Engine, chia sẻ source code và ý tưởng game sáng tạo. 50+ jobs mới mỗi tuần từ VNG, Gameloft.')

@push('styles')
    {{-- CSS already in redesign-bundle.min.css --}}
@endpush


@section('content')
    <!-- Hero Section Redesign -->
    @includeIf('components.hero-redesign')

    <!-- Featured Jobs Section -->
    <section id="viec-lam-noi-bat" class="courses-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">💼 Việc Làm Nổi Bật</h2>
                <p class="section-subtitle">
                    Cơ hội việc làm hot nhất từ các studio game hàng đầu Việt Nam
                </p>
            </div>
            
            <div class="courses-grid">
                @if(isset($jobs['featured']) && count($jobs['featured']) > 0)
                    @php
                        $featuredJobs = array_slice($jobs['featured'], 0, 3); // Lấy 3 việc làm đầu tiên
                        $jobImages = [
                            'https://images.unsplash.com/photo-1560472354-b33ff0c44a43?w=400&h=250&fit=crop', // Game dev
                            'https://images.unsplash.com/photo-1556438064-2d7646166914?w=400&h=250&fit=crop', // Unity
                            'https://images.unsplash.com/photo-1542751371-adc38448a05e?w=400&h=250&fit=crop'  // Programming
                        ];
                        $jobLevels = ['Entry → Mid Level', 'Mid → Senior Level', 'Senior Level'];
                        $badges = ['Hot 🔥', 'Urgent', 'High Salary'];
                    @endphp
                    
                    @foreach($featuredJobs as $index => $job)
                        <div class="course-card {{ $index === 0 ? 'featured' : '' }}">
                            @if($index === 0)
                                <div class="course-badge">{{ $badges[$index] ?? 'Hot 🔥' }}</div>
                            @endif
                            <div class="course-image">
                                <img src="{{ $jobImages[$index] ?? $jobImages[0] }}" alt="{{ $job['title'] }} at {{ $job['company'] }}" />
                                <div class="course-overlay">
                                    <div class="course-level">{{ $jobLevels[$index] ?? 'All Levels' }}</div>
                                </div>
                            </div>
                            <div class="course-content">
                                <h3 class="course-title">{{ $job['title'] }}</h3>
                                <p class="course-description">
                                    Cơ hội việc làm tại {{ $job['company'] }} - một trong những studio game hàng đầu tại {{ $job['location'] }}. 
                                    Mức lương hấp dẫn và môi trường làm việc chuyên nghiệp.
                                </p>
                                <div class="course-features">
                                    <div class="feature">
                                        <i class="fa fa-building"></i>
                                        <span>{{ $job['company'] }}</span>
                                    </div>
                                    <div class="feature">
                                        <i class="fa fa-map-marker"></i>
                                        <span>{{ $job['location'] }}</span>
                                    </div>
                                    <div class="feature">
                                        <i class="fa fa-clock-o"></i>
                                        <span>{{ $job['posted_ago'] }}</span>
                                    </div>
                                </div>
                                <div class="course-price">
                                    <span class="current-price">{{ $job['salary'] }}</span>
                                </div>
                                <a href="{{ $job['url'] }}" class="course-btn">Apply Ngay</a>
                            </div>
                        </div>
                    @endforeach
                @else
                    <!-- Sample jobs nếu chưa có data -->
                    <div class="course-card featured">
                        <div class="course-badge">Hot 🔥</div>
                        <div class="course-image">
                            <img src="https://images.unsplash.com/photo-1560472354-b33ff0c44a43?w=400&h=250&fit=crop" alt="Unity Developer at VNG" />
                            <div class="course-overlay">
                                <div class="course-level">Mid → Senior Level</div>
                            </div>
                        </div>
                        <div class="course-content">
                            <h3 class="course-title">Unity Developer</h3>
                            <p class="course-description">
                                Cơ hội việc làm tại VNG Corporation - studio game hàng đầu Việt Nam. 
                                Tham gia phát triển game mobile với hàng triệu người chơi.
                            </p>
                            <div class="course-features">
                                <div class="feature">
                                    <i class="fa fa-building"></i>
                                    <span>VNG Corporation</span>
                                </div>
                                <div class="feature">
                                    <i class="fa fa-map-marker"></i>
                                    <span>TP.HCM</span>
                                </div>
                                <div class="feature">
                                    <i class="fa fa-clock-o"></i>
                                    <span>2 ngày trước</span>
                                </div>
                            </div>
                            <div class="course-price">
                                <span class="current-price">25-40 triệu VND</span>
                            </div>
                            <a href="{{ route('lamgame.viec-lam-game') }}" class="course-btn">Apply Ngay</a>
                        </div>
                    </div>
                    
                    <div class="course-card">
                        <div class="course-image">
                            <img src="https://images.unsplash.com/photo-1556438064-2d7646166914?w=400&h=250&fit=crop" alt="3D Artist at Gameloft" />
                            <div class="course-overlay">
                                <div class="course-level">Entry → Mid Level</div>
                            </div>
                        </div>
                        <div class="course-content">
                            <h3 class="course-title">3D Artist</h3>
                            <p class="course-description">
                                Gameloft Vietnam tuyển 3D Artist tài năng để tạo ra những tài sản 3D chất lượng cao 
                                cho game mobile AAA với hàng chục triệu downloads.
                            </p>
                            <div class="course-features">
                                <div class="feature">
                                    <i class="fa fa-building"></i>
                                    <span>Gameloft Vietnam</span>
                                </div>
                                <div class="feature">
                                    <i class="fa fa-map-marker"></i>
                                    <span>Hà Nội</span>
                                </div>
                                <div class="feature">
                                    <i class="fa fa-clock-o"></i>
                                    <span>5 ngày trước</span>
                                </div>
                            </div>
                            <div class="course-price">
                                <span class="current-price">20-30 triệu VND</span>
                            </div>
                            <a href="{{ route('lamgame.viec-lam-game') }}" class="course-btn">Apply Ngay</a>
                        </div>
                    </div>
                    
                    <div class="course-card">
                        <div class="course-image">
                            <img src="https://images.unsplash.com/photo-1542751371-adc38448a05e?w=400&h=250&fit=crop" alt="Game Backend Developer" />
                            <div class="course-overlay">
                                <div class="course-level">Senior Level</div>
                            </div>
                        </div>
                        <div class="course-content">
                            <h3 class="course-title">Backend Developer</h3>
                            <p class="course-description">
                                Appota tuyển Backend Developer để phát triển hệ thống server cho game online. 
                                Cơ hội làm việc với công nghệ mới nhất và team quốc tế.
                            </p>
                            <div class="course-features">
                                <div class="feature">
                                    <i class="fa fa-building"></i>
                                    <span>Appota</span>
                                </div>
                                <div class="feature">
                                    <i class="fa fa-map-marker"></i>
                                    <span>Remote/TP.HCM</span>
                                </div>
                                <div class="feature">
                                    <i class="fa fa-clock-o"></i>
                                    <span>1 tuần trước</span>
                                </div>
                            </div>
                            <div class="course-price">
                                <span class="current-price">30-45 triệu VND</span>
                            </div>
                            <a href="{{ route('lamgame.viec-lam-game') }}" class="course-btn">Apply Ngay</a>
                        </div>
                    </div>
                @endif
            </div>
            
            <div class="courses-cta">
                <p>Khám phá thêm {{ isset($jobs['total_count']) ? $jobs['total_count'] : '50+' }} việc làm game dev đang hot</p>
                <button class="btn btn-outline" onclick="window.location.href='{{ route('lamgame.viec-lam-game') }}'">
                    Xem Tất Cả Việc Làm
                </button>
            </div>
        </div>
    </section>

    <!-- Why Choose LamGame -->
    <!-- Forum Section Redesign -->
    @includeIf('components.forum-section')

    <!-- Blog Section Redesign -->
    @includeIf('components.blog-section')

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
                                <input id="contact-phone" name="phone" type="tel" required placeholder="09.1111.8300">
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
                        <p>Tòa nhà E.Town Central<br>11 Đoàn Văn Bơ, Phường 13<br>Quận 4, TP. Hồ Chí Minh, Việt Nam</p>
                    </div>
                    <div class="info-card">
                        <h3>📞 Liên hệ trực tiếp</h3>
                        <p>Hotline: <a href="tel:0911118300">09.1111.8300</a><br>
                        Email: <a href="mailto:salegamevui@gmail.com">salegamevui@gmail.com</a></p>
                    </div>
                    <div class="info-card">
                        <h3>📺 Kênh truyền thông</h3>
                        <p>YouTube: <a href="https://www.youtube.com/channel/UCv2lripWdZDKtlrRy1J0dBw" target="_blank">Làm Game Official</a><br>
                        Website: <a href="https://lamgame.vn">lamgame.vn</a></p>
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
