{{-- LAMGAME HOMEPAGE - Updated with Optimized 4-Slide Banner --}}
@extends('layouts.master')

@section('page_title', 'LamGame.vn — Cộng đồng Game Developer Việt Nam | Việc làm Game Dev')

@section('page_description', 'Cộng đồng Game Developer Việt Nam hàng đầu. Tìm việc làm game dev, thảo luận Unity/Unreal Engine, chia sẻ source code và ý tưởng game sáng tạo. 50+ jobs mới mỗi tuần từ VNG, Gameloft.')

@push('styles')
    <link rel="stylesheet" href="{{ asset('themes/shop/emsaigon/assets/css/lamgame-optimized-banner.css') }}">
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
                        <a class="btn primary" href="#viec-lam">Xem Jobs Mới</a>
                        <a class="btn secondary" href="#forum">Hỏi kinh nghiệm phỏng vấn</a>
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
                        <a class="btn primary" href="#forum">Tham Gia Thảo Luận</a>
                        <a class="btn secondary" href="#forum">Xem tất cả Topics</a>
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
                        <a class="btn primary" href="#blog">Đọc Bài Viết</a>
                        <a class="btn secondary" href="#blog">Xem tất cả Blog</a>
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
                        <a class="btn primary" href="#source-game">Khám Phá & Chia Sẻ</a>
                        <a class="btn secondary" href="#source-game">Xem Source Code</a>
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

    <!-- Featured Forum Topics -->
    <section id="loi-ich" class="benefits-section featured-topics-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">🔥 Chủ Đề Nổi Bật</h2>
                <p class="section-subtitle">
                    Top 6 topic mới/comment nhiều từ cộng đồng game developer Việt Nam
                </p>
            </div>
            
            <div class="topics-grid">
                @if(isset($hotForumTopics['featured']) && count($hotForumTopics['featured']) > 0)
                    @foreach($hotForumTopics['featured'] as $index => $topic)
                        <div class="topic-card {{ $index < 2 ? 'featured' : '' }}">
                            <div class="topic-header">
                                <div class="topic-category" style="background: {{ $topic['category_color'] }}20; color: {{ $topic['category_color'] }}">
                                    {{ $topic['category_icon'] }} {{ $topic['category'] }}
                                </div>
                                <div class="topic-meta">
                                    <span class="topic-time">{{ $topic['time_ago'] }}</span>
                                    @if($topic['replies'] > 50)
                                        <span class="topic-hot">🔥 Hot</span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="topic-content">
                                <h4 class="topic-title">
                                    <a href="{{ $topic['url'] }}" target="_blank">{{ $topic['title'] }}</a>
                                </h4>
                                <p class="topic-excerpt">{{ $topic['excerpt'] }}</p>
                                
                                @if($topic['comment_snippet'])
                                    <div class="topic-comment-teaser">
                                        <div class="comment-icon">💬</div>
                                        <div class="comment-content">
                                            <span class="comment-text">"{{ $topic['comment_snippet'] }}"</span>
                                            <span class="comment-author">- {{ $topic['latest_comment_author'] }}</span>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="topic-stats">
                                <div class="stat-item">
                                    <i class="fa fa-comments"></i>
                                    <span>{{ $topic['replies'] }} comments</span>
                                </div>
                                <div class="stat-item">
                                    <i class="fa fa-thumbs-up"></i>
                                    <span>{{ $topic['likes'] }} likes</span>
                                </div>
                                <div class="stat-item">
                                    <i class="fa fa-eye"></i>
                                    <span>{{ number_format($topic['views']) }} views</span>
                                </div>
                            </div>
                            
                            <div class="topic-cta">
                                <a href="{{ $topic['url'] }}" class="btn btn-outline btn-sm" target="_blank">
                                    Tham gia thảo luận
                                </a>
                            </div>
                        </div>
                    @endforeach
                @else
                    <!-- Fallback content -->
                    <div class="topic-card featured">
                        <div class="topic-header">
                            <div class="topic-category" style="background: #ffd70020; color: #ffd700">
                                💡 Chia sẻ ý tưởng
                            </div>
                            <div class="topic-meta">
                                <span class="topic-time">2 giờ trước</span>
                                <span class="topic-hot">🔥 Hot</span>
                            </div>
                        </div>
                        
                        <div class="topic-content">
                            <h4 class="topic-title">
                                <a href="{{ route('forum.index') }}" target="_blank">Share source code AR game</a>
                            </h4>
                            <p class="topic-excerpt">Mình đang phát triển AR game với Unity, muốn chia sẻ source code để cộng đồng cùng học hỏi.</p>
                            
                            <div class="topic-comment-teaser">
                                <div class="comment-icon">💬</div>
                                <div class="comment-content">
                                    <span class="comment-text">"Cảm ơn bạn! Source này rất hữu ích cho Unity developer..."</span>
                                    <span class="comment-author">- UnityExpert</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="topic-stats">
                            <div class="stat-item">
                                <i class="fa fa-comments"></i>
                                <span>300 comments</span>
                            </div>
                            <div class="stat-item">
                                <i class="fa fa-thumbs-up"></i>
                                <span>85 likes</span>
                            </div>
                            <div class="stat-item">
                                <i class="fa fa-eye"></i>
                                <span>1,250 views</span>
                            </div>
                        </div>
                        
                        <div class="topic-cta">
                            <a href="{{ route('forum.index') }}" class="btn btn-outline btn-sm" target="_blank">
                                Tham gia thảo luận
                            </a>
                        </div>
                    </div>
                    
                    <div class="topic-card featured">
                        <div class="topic-header">
                            <div class="topic-category" style="background: #667eea20; color: #667eea">
                                💭 Thảo luận
                            </div>
                            <div class="topic-meta">
                                <span class="topic-time">5 giờ trước</span>
                            </div>
                        </div>
                        
                        <div class="topic-content">
                            <h4 class="topic-title">
                                <a href="{{ route('forum.index') }}" target="_blank">Ý tưởng game dựa trên lịch sử VN</a>
                            </h4>
                            <p class="topic-excerpt">Làm game RPG lấy bối cảnh lịch sử Việt Nam, từ thời Hùng Vương đến các triều đại phong kiến.</p>
                            
                            <div class="topic-comment-teaser">
                                <div class="comment-icon">💬</div>
                                <div class="comment-content">
                                    <span class="comment-text">"Ý tưởng hay quá! Mình có thể hỗ trợ research lịch sử..."</span>
                                    <span class="comment-author">- VietHistorian</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="topic-stats">
                            <div class="stat-item">
                                <i class="fa fa-comments"></i>
                                <span>150 comments</span>
                            </div>
                            <div class="stat-item">
                                <i class="fa fa-thumbs-up"></i>
                                <span>65 likes</span>
                            </div>
                            <div class="stat-item">
                                <i class="fa fa-eye"></i>
                                <span>800 views</span>
                            </div>
                        </div>
                        
                        <div class="topic-cta">
                            <a href="{{ route('forum.index') }}" class="btn btn-outline btn-sm" target="_blank">
                                Tham gia thảo luận
                            </a>
                        </div>
                    </div>
                    
                    <div class="topic-card">
                        <div class="topic-header">
                            <div class="topic-category" style="background: #8b5cf620; color: #8b5cf6">
                                🛠️ Hỗ trợ kỹ thuật
                            </div>
                            <div class="topic-meta">
                                <span class="topic-time">1 ngày trước</span>
                            </div>
                        </div>
                        
                        <div class="topic-content">
                            <h4 class="topic-title">
                                <a href="{{ route('forum.index') }}" target="_blank">Làm thế nào để tối ưu performance Unity?</a>
                            </h4>
                            <p class="topic-excerpt">Game mobile FPS giảm mạnh, đã thử object pooling nhưng vẫn chưa đủ.</p>
                            
                            <div class="topic-comment-teaser">
                                <div class="comment-icon">💬</div>
                                <div class="comment-content">
                                    <span class="comment-text">"Thử giảm draw calls bằng cách merge meshes..."</span>
                                    <span class="comment-author">- IndieCreator</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="topic-stats">
                            <div class="stat-item">
                                <i class="fa fa-comments"></i>
                                <span>45 comments</span>
                            </div>
                            <div class="stat-item">
                                <i class="fa fa-thumbs-up"></i>
                                <span>25 likes</span>
                            </div>
                            <div class="stat-item">
                                <i class="fa fa-eye"></i>
                                <span>420 views</span>
                            </div>
                        </div>
                        
                        <div class="topic-cta">
                            <a href="{{ route('forum.index') }}" class="btn btn-outline btn-sm" target="_blank">
                                Tham gia thảo luận
                            </a>
                        </div>
                    </div>
                    
                    <div class="topic-card">
                        <div class="topic-header">
                            <div class="topic-category" style="background: #ff6b3520; color: #ff6b35">
                                👥 Tìm team
                            </div>
                            <div class="topic-meta">
                                <span class="topic-time">3 ngày trước</span>
                            </div>
                        </div>
                        
                        <div class="topic-content">
                            <h4 class="topic-title">
                                <a href="{{ route('forum.index') }}" target="_blank">Tìm Unity Developer cho game horror indie</a>
                            </h4>
                            <p class="topic-excerpt">Dự án "Midnight School" cần Unity dev với 2+ năm kinh nghiệm, rev-share model.</p>
                            
                            <div class="topic-comment-teaser">
                                <div class="comment-icon">💬</div>
                                <div class="comment-content">
                                    <span class="comment-text">"Mình quan tâm position này. Portfolio: [link]..."</span>
                                    <span class="comment-author">- GameOptimizer</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="topic-stats">
                            <div class="stat-item">
                                <i class="fa fa-comments"></i>
                                <span>28 comments</span>
                            </div>
                            <div class="stat-item">
                                <i class="fa fa-thumbs-up"></i>
                                <span>18 likes</span>
                            </div>
                            <div class="stat-item">
                                <i class="fa fa-eye"></i>
                                <span>180 views</span>
                            </div>
                        </div>
                        
                        <div class="topic-cta">
                            <a href="{{ route('forum.index') }}" class="btn btn-outline btn-sm" target="_blank">
                                Tham gia thảo luận
                            </a>
                        </div>
                    </div>
                    
                    <div class="topic-card">
                        <div class="topic-header">
                            <div class="topic-category" style="background: #10b98120; color: #10b981">
                                📚 Review khóa học
                            </div>
                            <div class="topic-meta">
                                <span class="topic-time">5 ngày trước</span>
                            </div>
                        </div>
                        
                        <div class="topic-content">
                            <h4 class="topic-title">
                                <a href="{{ route('forum.index') }}" target="_blank">Review khóa học Unity tại GameDev Academy</a>
                            </h4>
                            <p class="topic-excerpt">Vừa hoàn thành khóa Unity 3 tháng, chia sẻ review chi tiết cho ae.</p>
                            
                            <div class="topic-comment-teaser">
                                <div class="comment-icon">💬</div>
                                <div class="comment-content">
                                    <span class="comment-text">"Mức lương 12M cho junior dev ở HCM khá ok đấy..."</span>
                                    <span class="comment-author">- CodeMaster</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="topic-stats">
                            <div class="stat-item">
                                <i class="fa fa-comments"></i>
                                <span>31 comments</span>
                            </div>
                            <div class="stat-item">
                                <i class="fa fa-thumbs-up"></i>
                                <span>22 likes</span>
                            </div>
                            <div class="stat-item">
                                <i class="fa fa-eye"></i>
                                <span>445 views</span>
                            </div>
                        </div>
                        
                        <div class="topic-cta">
                            <a href="{{ route('forum.index') }}" class="btn btn-outline btn-sm" target="_blank">
                                Tham gia thảo luận
                            </a>
                        </div>
                    </div>
                    
                    <div class="topic-card">
                        <div class="topic-header">
                            <div class="topic-category" style="background: #f59e0b20; color: #f59e0b">
                                🎯 Showcase
                            </div>
                            <div class="topic-meta">
                                <span class="topic-time">1 tuần trước</span>
                            </div>
                        </div>
                        
                        <div class="topic-content">
                            <h4 class="topic-title">
                                <a href="{{ route('forum.index') }}" target="_blank">"Cyber Runner" - Game endless runner hoàn thành</a>
                            </h4>
                            <p class="topic-excerpt">Sau 6 tháng làm việc, mình đã hoàn thành game đầu tiên!</p>
                            
                            <div class="topic-comment-teaser">
                                <div class="comment-icon">💬</div>
                                <div class="comment-content">
                                    <span class="comment-text">"Amazing work for solo dev! Inspiration cho mình quá..."</span>
                                    <span class="comment-author">- GameOptimizer</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="topic-stats">
                            <div class="stat-item">
                                <i class="fa fa-comments"></i>
                                <span>67 comments</span>
                            </div>
                            <div class="stat-item">
                                <i class="fa fa-thumbs-up"></i>
                                <span>45 likes</span>
                            </div>
                            <div class="stat-item">
                                <i class="fa fa-eye"></i>
                                <span>523 views</span>
                            </div>
                        </div>
                        
                        <div class="topic-cta">
                            <a href="{{ route('forum.index') }}" class="btn btn-outline btn-sm" target="_blank">
                                Tham gia thảo luận
                            </a>
                        </div>
                    </div>
                @endif
            </div>
            
            <div class="topics-cta">
                <p>Khám phá thêm {{ isset($hotForumTopics['total_posts']) ? $hotForumTopics['total_posts'] : '50+' }} chủ đề thú vị từ cộng đồng</p>
                <a href="{{ route('forum.index') }}" class="btn btn-outline" target="_blank">
                    Xem Tất Cả Forum
                </a>
            </div>
        </div>
    </section>

@push('styles')
<style>
/* Featured Topics Section Styles */
.featured-topics-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    position: relative;
    overflow: hidden;
}

.featured-topics-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.1);
    z-index: 1;
}

.featured-topics-section .container {
    position: relative;
    z-index: 2;
}

.featured-topics-section .section-title {
    color: white;
}

.featured-topics-section .section-subtitle {
    color: rgba(255,255,255,0.9);
}

.topics-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
    gap: 2rem;
    margin-top: 3rem;
}

.topic-card {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    color: #333;
}

.topic-card.featured {
    border: 3px solid #ffd700;
    transform: scale(1.02);
    box-shadow: 0 12px 35px rgba(255, 215, 0, 0.2);
}

.topic-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 40px rgba(0,0,0,0.15);
}

.topic-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1rem;
}

.topic-category {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
}

.topic-meta {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.topic-time {
    font-size: 0.8rem;
    color: #666;
}

.topic-hot {
    background: linear-gradient(45deg, #ff6b35, #ff4757);
    color: white;
    padding: 0.2rem 0.5rem;
    border-radius: 12px;
    font-size: 0.7rem;
    font-weight: bold;
}

.topic-title {
    margin: 0 0 0.75rem 0;
    font-size: 1.1rem;
    font-weight: 600;
    line-height: 1.3;
}

.topic-title a {
    color: #2c3e50;
    text-decoration: none;
    transition: color 0.3s ease;
}

.topic-title a:hover {
    color: #667eea;
}

.topic-excerpt {
    color: #666;
    font-size: 0.9rem;
    line-height: 1.5;
    margin-bottom: 1rem;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.topic-comment-teaser {
    background: #f8f9fa;
    border-left: 4px solid #667eea;
    padding: 0.75rem;
    border-radius: 8px;
    margin: 1rem 0;
    display: flex;
    gap: 0.75rem;
}

.comment-icon {
    font-size: 1.2rem;
    line-height: 1;
}

.comment-content {
    flex: 1;
}

.comment-text {
    display: block;
    font-style: italic;
    color: #555;
    font-size: 0.85rem;
    line-height: 1.4;
    margin-bottom: 0.25rem;
}

.comment-author {
    font-size: 0.8rem;
    color: #667eea;
    font-weight: 500;
}

.topic-stats {
    display: flex;
    gap: 1rem;
    margin: 1rem 0;
    padding: 0.75rem 0;
    border-top: 1px solid #eee;
}

.stat-item {
    display: flex;
    align-items: center;
    gap: 0.25rem;
    font-size: 0.8rem;
    color: #666;
}

.stat-item i {
    width: 14px;
    text-align: center;
}

.topic-cta {
    margin-top: 1rem;
}

.btn-sm {
    padding: 0.5rem 1rem;
    font-size: 0.85rem;
    border-radius: 6px;
}

.topics-cta {
    text-align: center;
    margin-top: 3rem;
}

.topics-cta p {
    color: rgba(255,255,255,0.9);
    margin-bottom: 1.5rem;
    font-size: 1.1rem;
}

.topics-cta .btn-outline {
    background: transparent;
    color: white;
    border: 2px solid white;
    padding: 1rem 2rem;
    font-weight: 600;
    text-decoration: none;
    border-radius: 30px;
    transition: all 0.3s ease;
    display: inline-block;
}

.topics-cta .btn-outline:hover {
    background: white;
    color: #667eea;
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(255,255,255,0.3);
}

/* Responsive */
@media (max-width: 768px) {
    .topics-grid {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
    
    .topic-card.featured {
        transform: none;
    }
    
    .topic-header {
        flex-direction: column;
        gap: 0.5rem;
        align-items: flex-start;
    }
    
    .topic-meta {
        margin-top: 0.5rem;
    }
    
    .topic-stats {
        flex-wrap: wrap;
        gap: 0.75rem;
    }
}
</style>
@endpush

    <!-- Old benefits section continues below if needed -->
    <div style="display: none;">
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
