{{-- LAMGAME HOMEPAGE - Updated with Optimized 4-Slide Banner --}}
@extends('layouts.master')

@section('page_title', 'LamGame.vn — Cộng đồng Game Developer Việt Nam | Việc làm Game Dev')

@section('page_description', 'Cộng đồng Game Developer Việt Nam hàng đầu. Tìm việc làm game dev, thảo luận Unity/Unreal Engine, chia sẻ source code và ý tưởng game sáng tạo. 50+ jobs mới mỗi tuần từ VNG, Gameloft.')

@push('meta')
    {{-- Additional SEO Meta Tags --}}
    <meta name="keywords" content="game developer việt nam, unity developer, unreal engine, việc làm game, lập trình game, forum game dev, source code game, tuyển dụng game">
    <meta name="author" content="LamGame.vn Team">
    <meta name="robots" content="index,follow">
    <link rel="canonical" href="{{ url()->current() }}">
    
    {{-- Language and Locale --}}
    <meta name="language" content="Vietnamese">
    <meta property="og:locale" content="vi_VN">
    <meta property="og:site_name" content="LamGame.vn">
    
    {{-- Twitter Cards --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('page_title')">
    <meta name="twitter:description" content="@yield('page_description')">
    <meta name="twitter:image" content="{{ asset('assets/logos/png/logo-square-512.png') }}">
    
    {{-- Mobile App Meta --}}
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="LamGame">
    
    {{-- Structured Data for Organization --}}
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Organization",
        "name": "LamGame.vn",
        "alternateName": "Làm Game Vietnam",
        "description": "Cộng đồng Game Developer Việt Nam hàng đầu",
        "url": "{{ url('/') }}",
        "logo": "{{ asset('assets/logos/png/logo-square-512.png') }}",
        "image": "{{ asset('assets/logos/png/logo-square-512.png') }}",
        "telephone": "+84-911-118-300",
        "email": "salegamevui@gmail.com",
        "address": {
            "@type": "PostalAddress",
            "streetAddress": "Tòa nhà E.Town Central, 11 Đoàn Văn Bơ, Phường 13",
            "addressLocality": "Quận 4",
            "addressRegion": "TP. Hồ Chí Minh",
            "addressCountry": "VN"
        },
        "sameAs": [
            "https://www.youtube.com/channel/UCv2lripWdZDKtlrRy1J0dBw",
            "https://lamgame.vn"
        ]
    }
    </script>
    
    {{-- Structured Data for Website --}}
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "WebSite",
        "name": "LamGame.vn",
        "description": "Cộng đồng Game Developer Việt Nam hàng đầu",
        "url": "{{ url('/') }}",
        "potentialAction": {
            "@type": "SearchAction",
            "target": {
                "@type": "EntryPoint",
                "urlTemplate": "{{ url('/') }}/search?q={search_term_string}"
            },
            "query-input": "required name=search_term_string"
        }
    }
    </script>
@endpush

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
                <a href="{{ route('lamgame.viec-lam-game') }}" class="slide-link" title="Khám phá việc làm Game Developer">
                    <div class="bg jobs"></div>
                    <div class="overlay"></div>
                    <div class="content">
                        <h1>Khám Phá Việc Làm Game Dev Hot Nhất!</h1>
                        <p>Hàng trăm vị trí từ VNG, Gameloft: Unity Developer lương 20-40tr VNĐ. <span class="dynamic-content" id="job-stats">50+ jobs tuần này</span>, apply ngay để kết nối với công ty hàng đầu!</p>
                        <div class="btns">
                            <span class="btn primary">Xem Jobs Mới</span>
                            <a class="btn secondary" href="{{ route('forum.index') }}" onclick="event.stopPropagation()">Hỏi kinh nghiệm phỏng vấn</a>
                        </div>
                    </div>
                </a>
            </div>
            
            <!-- Slide 2: Topic Forum Hot -->
            <div class="slide">
                <a href="{{ route('forum.index') }}" class="slide-link" title="Tham gia thảo luận trên Forum">
                    <div class="bg forum"></div>
                    <div class="overlay"></div>
                    <div class="content">
                        <h1>Thảo Luận Sôi Động: Topic Forum Nóng Hổi!</h1>
                        <p>Topic hot: <span class="dynamic-content" id="hot-topic">'Unity vs Unreal cho game mobile?'</span> – <span class="dynamic-content" id="topic-stats">150 comments, 500 views, 80 likes</span> trong 24h. Tham gia ngay để chia sẻ kinh nghiệm với cộng đồng dev!</p>
                        <div class="btns">
                            <span class="btn primary">Tham Gia Thảo Luận</span>
                            <span class="btn secondary">Xem tất cả Topics</span>
                        </div>
                    </div>
                </a>
            </div>
            
            <!-- Slide 3: Bài viết mới -->
            <div class="slide">
                <a href="{{ route('lamgame.blog') }}" class="slide-link" title="Đọc bài viết từ cộng đồng Developer">
                    <div class="bg blog"></div>
                    <div class="overlay"></div>
                    <div class="content">
                        <h1>Bài Viết Mới Nhất Từ Developer!</h1>
                        <p>Bài mới: <span class="dynamic-content" id="new-blog">'Tối ưu hóa performance Unity cho game 3D'</span> – Đăng bởi dev @UserX, <span class="dynamic-content" id="blog-stats">200 views, 50 shares</span>. Đọc để cập nhật kiến thức hot nhất!</p>
                        <div class="btns">
                            <span class="btn primary">Đọc Bài Viết</span>
                            <span class="btn secondary">Xem tất cả Blog</span>
                        </div>
                    </div>
                </a>
            </div>
            
            <!-- Slide 4: Game & Source mới -->
            <div class="slide">
                <a href="{{ route('lamgame.source-game') }}" class="slide-link" title="Khám phá Source Game và ý tưởng sáng tạo">
                    <div class="bg creative"></div>
                    <div class="overlay"></div>
                    <div class="content">
                        <h1>Khám Phá Game Mới & Ý Tưởng Sáng Tạo!</h1>
                        <p>Source mới: <span class="dynamic-content" id="new-source">'Roguelike Unity kit'</span> trên GitHub. Ý tưởng: <span class="dynamic-content" id="new-idea">'VR adventure Việt Nam folklore'</span>. Game demo từ dev cộng đồng – Download & phát triển ngay!</p>
                        <div class="btns">
                            <span class="btn primary">Khám Phá & Chia Sẻ</span>
                            <span class="btn secondary">Xem Source Code</span>
                        </div>
                    </div>
                </a>
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
                                <h3 class="course-title">
                                    <a href="{{ $job['url'] }}">{{ $job['title'] }}</a>
                                </h3>
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
                            <h3 class="course-title">
                                <a href="{{ route('lamgame.viec-lam-game') }}">Unity Developer</a>
                            </h3>
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
                            <h3 class="course-title">
                                <a href="{{ route('lamgame.viec-lam-game') }}">3D Artist</a>
                            </h3>
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
                            <h3 class="course-title">
                                <a href="{{ route('lamgame.viec-lam-game') }}">Backend Developer</a>
                            </h3>
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

/* Course Title Link Styling */
.course-title a {
    color: #2c3e50;
    text-decoration: none;
    transition: color 0.3s ease;
    display: block;
    font-weight: 600;
}

.course-title a:hover {
    color: #667eea;
    text-decoration: none;
}

.course-title a:focus {
    outline: 2px solid #667eea;
    outline-offset: 2px;
    border-radius: 4px;
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

    <!-- Blog & News Section -->
    <section id="thanh-cong" class="blog-news-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">📰 Blog & Tin tức</h2>
                <p class="section-subtitle">
                    Những bài viết mới và được đọc giả nhiều nhất từ cộng đồng game developer
                </p>
            </div>
            
            <div class="blog-grid">
                @if(isset($latestBlogs['featured']) && count($latestBlogs['featured']) > 0)
                    @foreach($latestBlogs['featured'] as $index => $blog)
                        <article class="blog-card {{ $index < 2 ? 'featured' : '' }}">
                            <div class="blog-image">
                                <img src="{{ $blog['featured_image'] }}" alt="{{ $blog['title'] }}" loading="lazy">
                                <div class="blog-overlay">
                                    <div class="blog-category" style="background: {{ $blog['category_color'] }}; color: white;">
                                        {{ $blog['category'] }}
                                    </div>
                                    <div class="blog-reading-time">
                                        <i class="fa fa-clock"></i> {{ $blog['reading_time'] }} phút đọc
                                    </div>
                                </div>
                            </div>
                            
                            <div class="blog-content">
                                <div class="blog-meta">
                                    <span class="blog-author">
                                        <i class="fa fa-user"></i> {{ $blog['author'] }}
                                    </span>
                                    <span class="blog-date">
                                        <i class="fa fa-calendar"></i> {{ $blog['time_ago'] }}
                                    </span>
                                </div>
                                
                                <h3 class="blog-title">
                                    <a href="{{ $blog['url'] }}" target="_blank">{{ $blog['title'] }}</a>
                                </h3>
                                
                                <p class="blog-excerpt">{{ $blog['excerpt'] }}</p>
                                
                                @if($blog['comment_snippet'])
                                    <div class="blog-comment-teaser">
                                        <div class="comment-icon">💬</div>
                                        <div class="comment-content">
                                            <span class="comment-text">"{{ $blog['comment_snippet'] }}"</span>
                                            <span class="comment-author">- {{ $blog['latest_comment_author'] }}</span>
                                        </div>
                                    </div>
                                @endif
                                
                                <div class="blog-stats">
                                    <div class="stat-item">
                                        <i class="fa fa-eye"></i>
                                        <span>{{ number_format($blog['views']) }} lượt xem</span>
                                    </div>
                                    <div class="stat-item">
                                        <i class="fa fa-share"></i>
                                        <span>{{ $blog['shares'] }} lượt chia sẻ</span>
                                    </div>
                                    <div class="stat-item">
                                        <i class="fa fa-comments"></i>
                                        <span>{{ rand(5, 50) }} comments</span>
                                    </div>
                                </div>
                                
                                <div class="blog-cta">
                                    <a href="{{ $blog['url'] }}" class="btn btn-outline btn-sm" target="_blank">
                                        Đọc thêm
                                    </a>
                                </div>
                            </div>
                        </article>
                    @endforeach
                @else
                    <!-- Fallback blog content -->
                    <article class="blog-card featured">
                        <div class="blog-image">
                            <img src="https://images.unsplash.com/photo-1556438064-2d7646166914?w=400&h=250&fit=crop" alt="Unity 2024" loading="lazy">
                            <div class="blog-overlay">
                                <div class="blog-category" style="background: #ff6b35; color: white;">
                                    Unity
                                </div>
                                <div class="blog-reading-time">
                                    <i class="fa fa-clock"></i> 8 phút đọc
                                </div>
                            </div>
                        </div>
                        
                        <div class="blog-content">
                            <div class="blog-meta">
                                <span class="blog-author">
                                    <i class="fa fa-user"></i> LamGame Team
                                </span>
                                <span class="blog-date">
                                    <i class="fa fa-calendar"></i> 2 giờ trước
                                </span>
                            </div>
                            
                            <h3 class="blog-title">
                                <a href="{{ route('lamgame.blog') }}" target="_blank">Hướng dẫn Unity 2024 - Tính năng mới</a>
                            </h3>
                            
                            <p class="blog-excerpt">Unity 2024 mang đến nhiều cải tiến quan trọng giúp game developer tăng hiệu suất và chất lượng game.</p>
                            
                            <div class="blog-comment-teaser">
                                <div class="comment-icon">💬</div>
                                <div class="comment-content">
                                    <span class="comment-text">"Bài viết rất hữu ích! Netcode mới của Unity thực sự ấn tượng..."</span>
                                    <span class="comment-author">- UnityDev</span>
                                </div>
                            </div>
                            
                            <div class="blog-stats">
                                <div class="stat-item">
                                    <i class="fa fa-eye"></i>
                                    <span>1,250 lượt xem</span>
                                </div>
                                <div class="stat-item">
                                    <i class="fa fa-share"></i>
                                    <span>85 lượt chia sẻ</span>
                                </div>
                                <div class="stat-item">
                                    <i class="fa fa-comments"></i>
                                    <span>24 comments</span>
                                </div>
                            </div>
                            
                            <div class="blog-cta">
                                <a href="{{ route('lamgame.blog') }}" class="btn btn-outline btn-sm" target="_blank">
                                    Đọc thêm
                                </a>
                            </div>
                        </div>
                    </article>
                    
                    <article class="blog-card featured">
                        <div class="blog-image">
                            <img src="https://images.unsplash.com/photo-1542751371-adc38448a05e?w=400&h=250&fit=crop" alt="C# Programming" loading="lazy">
                            <div class="blog-overlay">
                                <div class="blog-category" style="background: #667eea; color: white;">
                                    Programming
                                </div>
                                <div class="blog-reading-time">
                                    <i class="fa fa-clock"></i> 12 phút đọc
                                </div>
                            </div>
                        </div>
                        
                        <div class="blog-content">
                            <div class="blog-meta">
                                <span class="blog-author">
                                    <i class="fa fa-user"></i> LamGame Team
                                </span>
                                <span class="blog-date">
                                    <i class="fa fa-calendar"></i> 1 ngày trước
                                </span>
                            </div>
                            
                            <h3 class="blog-title">
                                <a href="{{ route('lamgame.blog') }}" target="_blank">C# Cơ bản cho Game Developer</a>
                            </h3>
                            
                            <p class="blog-excerpt">Hướng dẫn C# từ cơ bản đến nâng cao dành cho Unity game development.</p>
                            
                            <div class="blog-comment-teaser">
                                <div class="comment-icon">💬</div>
                                <div class="comment-content">
                                    <span class="comment-text">"Giải thích MonoBehaviour rất rõ ràng, cảm ơn tác giả!"</span>
                                    <span class="comment-author">- BeginnerCoder</span>
                                </div>
                            </div>
                            
                            <div class="blog-stats">
                                <div class="stat-item">
                                    <i class="fa fa-eye"></i>
                                    <span>980 lượt xem</span>
                                </div>
                                <div class="stat-item">
                                    <i class="fa fa-share"></i>
                                    <span>65 lượt chia sẻ</span>
                                </div>
                                <div class="stat-item">
                                    <i class="fa fa-comments"></i>
                                    <span>18 comments</span>
                                </div>
                            </div>
                            
                            <div class="blog-cta">
                                <a href="{{ route('lamgame.blog') }}" class="btn btn-outline btn-sm" target="_blank">
                                    Đọc thêm
                                </a>
                            </div>
                        </div>
                    </article>
                    
                    <article class="blog-card">
                        <div class="blog-image">
                            <img src="https://images.unsplash.com/photo-1551650975-87deedd944c3?w=400&h=250&fit=crop" alt="Mobile Optimization" loading="lazy">
                            <div class="blog-overlay">
                                <div class="blog-category" style="background: #10b981; color: white;">
                                    Mobile Development
                                </div>
                                <div class="blog-reading-time">
                                    <i class="fa fa-clock"></i> 15 phút đọc
                                </div>
                            </div>
                        </div>
                        
                        <div class="blog-content">
                            <div class="blog-meta">
                                <span class="blog-author">
                                    <i class="fa fa-user"></i> LamGame Team
                                </span>
                                <span class="blog-date">
                                    <i class="fa fa-calendar"></i> 3 ngày trước
                                </span>
                            </div>
                            
                            <h3 class="blog-title">
                                <a href="{{ route('lamgame.blog') }}" target="_blank">Tối ưu hóa Performance Game Mobile</a>
                            </h3>
                            
                            <p class="blog-excerpt">Các kỹ thuật tối ưu hóa performance cho mobile game để đạt hiệu suất tốt nhất.</p>
                            
                            <div class="blog-comment-teaser">
                                <div class="comment-icon">💬</div>
                                <div class="comment-content">
                                    <span class="comment-text">"Object Pooling tip rất hay, đã áp dụng vào game của mình!"</span>
                                    <span class="comment-author">- MobileDev</span>
                                </div>
                            </div>
                            
                            <div class="blog-stats">
                                <div class="stat-item">
                                    <i class="fa fa-eye"></i>
                                    <span>1,580 lượt xem</span>
                                </div>
                                <div class="stat-item">
                                    <i class="fa fa-share"></i>
                                    <span>120 lượt chia sẻ</span>
                                </div>
                                <div class="stat-item">
                                    <i class="fa fa-comments"></i>
                                    <span>35 comments</span>
                                </div>
                            </div>
                            
                            <div class="blog-cta">
                                <a href="{{ route('lamgame.blog') }}" class="btn btn-outline btn-sm" target="_blank">
                                    Đọc thêm
                                </a>
                            </div>
                        </div>
                    </article>
                @endif
            </div>
            
            <div class="blog-cta-section">
                <p>Khám phá thêm {{ isset($latestBlogs['total_posts']) ? $latestBlogs['total_posts'] : '50+' }} bài viết chất lượng</p>
                <a href="{{ route('lamgame.blog') }}" class="btn btn-outline" target="_blank">
                    Xem Tất Cả Blog
                </a>
            </div>
        </div>
    </section>

@push('styles')
<style>
/* Blog & News Section Styles */
.blog-news-section {
    background: #f8fafc;
    padding: 5rem 0;
}

.blog-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 2rem;
    margin-top: 3rem;
}

.blog-card {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    position: relative;
}

.blog-card.featured {
    border: 2px solid #667eea;
    transform: scale(1.02);
    box-shadow: 0 8px 30px rgba(102, 126, 234, 0.15);
}

.blog-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 40px rgba(0,0,0,0.12);
}

.blog-image {
    position: relative;
    height: 200px;
    overflow: hidden;
}

.blog-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.blog-card:hover .blog-image img {
    transform: scale(1.05);
}

.blog-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(to bottom, rgba(0,0,0,0.1), rgba(0,0,0,0.6));
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    padding: 1rem;
}

.blog-category {
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
}

.blog-reading-time {
    background: rgba(0,0,0,0.7);
    color: white;
    padding: 0.25rem 0.75rem;
    border-radius: 15px;
    font-size: 0.75rem;
}

.blog-content {
    padding: 1.5rem;
}

.blog-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
    font-size: 0.8rem;
    color: #666;
}

.blog-meta i {
    margin-right: 0.25rem;
    width: 12px;
}

.blog-title {
    margin: 0 0 1rem 0;
    font-size: 1.2rem;
    font-weight: 600;
    line-height: 1.4;
}

.blog-title a {
    color: #2c3e50;
    text-decoration: none;
    transition: color 0.3s ease;
}

.blog-title a:hover {
    color: #667eea;
}

.blog-excerpt {
    color: #666;
    font-size: 0.9rem;
    line-height: 1.6;
    margin-bottom: 1rem;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.blog-comment-teaser {
    background: #f8f9fa;
    border-left: 4px solid #667eea;
    padding: 0.75rem;
    border-radius: 8px;
    margin: 1rem 0;
    display: flex;
    gap: 0.75rem;
    font-size: 0.85rem;
}

.comment-icon {
    font-size: 1.1rem;
    line-height: 1;
}

.comment-content {
    flex: 1;
}

.comment-text {
    display: block;
    font-style: italic;
    color: #555;
    line-height: 1.4;
    margin-bottom: 0.25rem;
}

.comment-author {
    color: #667eea;
    font-weight: 500;
    font-size: 0.8rem;
}

.blog-stats {
    display: flex;
    gap: 1rem;
    margin: 1rem 0;
    padding: 0.75rem 0;
    border-top: 1px solid #eee;
    border-bottom: 1px solid #eee;
}

.stat-item {
    display: flex;
    align-items: center;
    gap: 0.25rem;
    font-size: 0.75rem;
    color: #666;
}

.stat-item i {
    width: 12px;
    text-align: center;
}

.blog-cta {
    margin-top: 1rem;
}

.blog-cta-section {
    text-align: center;
    margin-top: 3rem;
}

.blog-cta-section p {
    color: #666;
    margin-bottom: 1.5rem;
    font-size: 1.1rem;
}

.blog-cta-section .btn-outline {
    background: transparent;
    color: #667eea;
    border: 2px solid #667eea;
    padding: 1rem 2rem;
    font-weight: 600;
    text-decoration: none;
    border-radius: 30px;
    transition: all 0.3s ease;
    display: inline-block;
}

.blog-cta-section .btn-outline:hover {
    background: #667eea;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
}

/* Responsive */
@media (max-width: 768px) {
    .blog-grid {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
    
    .blog-card.featured {
        transform: none;
    }
    
    .blog-meta {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
    
    .blog-stats {
        flex-wrap: wrap;
        gap: 0.75rem;
    }
    
    .blog-comment-teaser {
        font-size: 0.8rem;
    }
}

/* YouTube Videos Section */
.youtube-section {
    background: #f8f9fa;
    padding: 4rem 0;
}

.youtube-videos-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 2rem;
    margin: 3rem 0;
}

.youtube-video-card {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    border: 1px solid #e5e7eb;
}

.youtube-video-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
}

.video-thumbnail {
    position: relative;
    overflow: hidden;
}

.thumbnail-link {
    display: block;
    position: relative;
}

.video-thumbnail img {
    width: 100%;
    height: 200px;
    object-fit: cover;
    transition: transform 0.3s ease;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.video-thumbnail img[src=""],
.video-thumbnail img:not([src]),
.video-thumbnail img[src*="404"] {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    position: relative;
}

.video-thumbnail img[src=""]:after,
.video-thumbnail img:not([src]):after,
.video-thumbnail img[src*="404"]:after {
    content: "\f04b";
    font-family: "Font Awesome 5 Free";
    font-weight: 900;
    color: white;
    font-size: 3rem;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}

.youtube-video-card:hover .video-thumbnail img {
    transform: scale(1.05);
}

.play-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.3);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.youtube-video-card:hover .play-overlay {
    opacity: 1;
}

.play-button {
    width: 60px;
    height: 60px;
    background: #ff0000;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 24px;
    transform: scale(0.8);
    transition: transform 0.3s ease;
}

.youtube-video-card:hover .play-button {
    transform: scale(1);
}

.video-duration {
    position: absolute;
    bottom: 8px;
    right: 8px;
    background: rgba(0, 0, 0, 0.8);
    color: white;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 0.8rem;
    font-weight: 500;
}

.video-info {
    padding: 1.5rem;
}

.video-category {
    display: inline-block;
    background: #667eea;
    color: white;
    padding: 0.25rem 0.75rem;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 0.75rem;
}

.video-title {
    margin: 0.75rem 0 0.5rem 0;
    font-size: 1.1rem;
    font-weight: 600;
    line-height: 1.4;
}

.video-title a {
    color: #1f2937;
    text-decoration: none;
    transition: color 0.3s ease;
}

.video-title a:hover {
    color: #667eea;
}

.video-description {
    color: #6b7280;
    font-size: 0.9rem;
    line-height: 1.5;
    margin-bottom: 1rem;
}

.video-stats {
    display: flex;
    gap: 1rem;
    font-size: 0.8rem;
    color: #9ca3af;
}

.video-stats span {
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

/* Enhanced YouTube Channel Section with Banner Background */
.youtube-cta-section {
    border-radius: 16px;
    margin-top: 3rem;
    overflow: hidden;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
    position: relative;
    background: #f8f9fa; /* Fallback background */
    width: 100%;
    /* Ensure full-width display */
    margin-left: auto;
    margin-right: auto;
}

.channel-banner-background {
    position: relative;
    /* Full width container for optimal cover display */
    width: 100%;
    height: 100%;
    /* Dynamic height based on screen width to maintain aspect ratio */
    min-height: calc(100vw / 6);
    max-height: 400px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    /* Optimize for crisp, non-blurry display */
    background-size: cover;
    background-position: center center;
    background-repeat: no-repeat;
    background-attachment: scroll;
    image-rendering: -webkit-optimize-contrast;
    image-rendering: crisp-edges;
    image-rendering: optimizeQuality;
    display: flex;
    flex-direction: column;
    justify-content: flex-end;
    align-items: stretch;
    padding: 0;
    transition: all 0.8s ease;
    animation: fadeInUp 0.6s ease-out;
    /* Hardware acceleration for sharp rendering */
    transform: translateZ(0);
    will-change: background-image;
    backface-visibility: hidden;
    -webkit-backface-visibility: hidden;
}

.channel-banner-background.banner-loaded {
    animation: bannerFadeIn 1s ease-out;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes bannerFadeIn {
    from {
        filter: blur(2px) brightness(0.8);
        transform: scale(1.02);
    }
    to {
        filter: blur(0) brightness(1);
        transform: scale(1);
    }
}

/* Banner Loading State */
.banner-loading {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(102, 126, 234, 0.9);
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    color: white;
    font-size: 1rem;
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.3s ease;
}

.banner-loading.loading {
    opacity: 1;
    pointer-events: all;
}

.loading-spinner {
    width: 40px;
    height: 40px;
    border: 3px solid rgba(255, 255, 255, 0.3);
    border-top: 3px solid white;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin-bottom: 1rem;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Minimal overlay - only at bottom for stats */
.banner-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(
        to top,
        rgba(0, 0, 0, 0.8) 0%,
        rgba(0, 0, 0, 0.4) 15%,
        rgba(0, 0, 0, 0.1) 30%,
        transparent 40%
    );
    z-index: 1;
    pointer-events: none;
}

/* Channel Avatar - REMOVED */

/* Channel Content */
.channel-content {
    position: relative;
    z-index: 2;
    text-align: center;
    color: white;
    max-width: 600px;
    width: 100%;
}

/* Minimal Channel Stats at Bottom */
.channel-stats-minimal {
    position: relative;
    z-index: 2;
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem 1rem;
    background: rgba(0, 0, 0, 0.6);
    backdrop-filter: blur(10px);
    border-top: 1px solid rgba(255, 255, 255, 0.1);
    font-size: 0.85rem;
    line-height: 1.2;
    margin: 0;
    width: 100%;
}

.stat-item-minimal {
    display: flex;
    align-items: baseline;
    gap: 0.25rem;
    white-space: nowrap;
}

.stat-number-minimal {
    font-size: 0.9rem;
    font-weight: 600;
    color: white;
    text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.7);
}

.stat-label-minimal {
    font-size: 0.75rem;
    color: rgba(255, 255, 255, 0.8);
    text-transform: lowercase;
    font-weight: 400;
}

.stat-divider-minimal {
    color: rgba(255, 255, 255, 0.6);
    font-size: 0.8rem;
    font-weight: 300;
    margin: 0 0.25rem;
}

.youtube-action-minimal {
    display: flex;
    align-items: center;
}

.btn-youtube-minimal {
    background: linear-gradient(135deg, #ff0000, #cc0000);
    color: white;
    border: none;
    padding: 0.3rem 0.6rem;
    border-radius: 15px;
    font-weight: 500;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    transition: all 0.2s ease;
    font-size: 0.75rem;
    box-shadow: 0 1px 5px rgba(255, 0, 0, 0.3);
    white-space: nowrap;
    line-height: 1;
}

.btn-youtube-minimal:hover {
    background: linear-gradient(135deg, #cc0000, #990000);
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(255, 0, 0, 0.4);
    color: white;
    text-decoration: none;
}

.btn-youtube-minimal i {
    font-size: 0.8rem;
}

/* Channel description removed - no longer used */

/* Old YouTube Actions removed - now inline in stats */

/* Responsive Design - Mobile First Approach */
@media (max-width: 480px) {
    .channel-banner-background {
        min-height: calc(100vw / 4);
        max-height: 300px;
    }
    
    .channel-stats-minimal {
        gap: 0.5rem;
        padding: 0.5rem 0.75rem;
        font-size: 0.8rem;
        flex-wrap: wrap;
        justify-content: center;
    }
    
    .stat-divider-minimal {
        display: none;
    }
    
    .stat-number-minimal {
        font-size: 0.8rem;
    }
    
    .stat-label-minimal {
        font-size: 0.7rem;
    }
    
    .btn-youtube-minimal {
        font-size: 0.7rem;
        padding: 0.25rem 0.5rem;
        margin-top: 0.25rem;
    }
    
    .btn-youtube-minimal i {
        font-size: 0.7rem;
    }
}

@media (min-width: 481px) and (max-width: 768px) {
    .channel-banner-background {
        min-height: calc(100vw / 5);
        max-height: 350px;
    }
    
    .channel-stats-minimal {
        gap: 0.6rem;
        padding: 0.6rem 1rem;
        font-size: 0.85rem;
    }
    
    .stat-number-minimal {
        font-size: 0.85rem;
    }
    
    .btn-youtube-minimal {
        font-size: 0.75rem;
        padding: 0.3rem 0.6rem;
    }
}

@media (min-width: 769px) and (max-width: 1024px) {
    .channel-banner-background {
        min-height: calc(100vw / 6);
        max-height: 350px;
    }
    
    .channel-stats-minimal {
        gap: 0.8rem;
        padding: 0.8rem 1.5rem;
    }
}

@media (min-width: 1025px) {
    .channel-banner-background {
        min-height: calc(100vw / 6);
        max-height: 400px;
    }
    
    .channel-stats-minimal {
        gap: 1rem;
        padding: 1rem 2rem;
        font-size: 0.9rem;
    }
    
    .stat-number-minimal {
        font-size: 1rem;
    }
    
    .stat-label-minimal {
        font-size: 0.8rem;
    }
    
    .btn-youtube-minimal {
        font-size: 0.8rem;
        padding: 0.4rem 0.8rem;
    }
    
    .btn-youtube-minimal i {
        font-size: 0.85rem;
    }
}

/* Legacy responsive fixes */
@media (max-width: 768px) {
    .youtube-videos-grid {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
}
</style>
@endpush
    <!-- YouTube Videos Section -->
    <section id="co-hoi-viec-lam" class="youtube-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">🎥 Channel - Lamgame</h2>
                <p class="section-subtitle">
                    Học game development từ các video tutorial chất lượng trên kênh YouTube Làm Game
                </p>
            </div>
            
            <div class="youtube-videos-grid">
                @foreach($youtubeVideos['featured'] as $video)
                <div class="youtube-video-card">
                    <div class="video-thumbnail">
                        <a href="{{ $video['url'] }}" target="_blank" class="thumbnail-link">
                            <img src="{{ $video['thumbnail'] }}" alt="{{ $video['title'] }}" loading="lazy" 
                                 onerror="this.onerror=null; this.style.background='linear-gradient(135deg, #667eea 0%, #764ba2 100%)'; this.style.display='flex'; this.style.alignItems='center'; this.style.justifyContent='center'; this.innerHTML='<i class=&quot;fa fa-play&quot; style=&quot;color:white;font-size:3rem;&quot;></i>'; this.removeAttribute('src');">
                            <div class="play-overlay">
                                <div class="play-button">
                                    <i class="fa fa-play"></i>
                                </div>
                            </div>
                            <div class="video-duration">{{ $video['duration'] }}</div>
                        </a>
                    </div>
                    <div class="video-info">
                        <div class="video-category">{{ $video['category'] }}</div>
                        <h3 class="video-title">
                            <a href="{{ $video['url'] }}" target="_blank">
                                {{ $video['title'] }}
                            </a>
                        </h3>
                        <p class="video-description">
                            {{ \Illuminate\Support\Str::limit($video['description'], 100) }}
                        </p>
                        <div class="video-stats">
                            <span class="video-views">
                                <i class="fa fa-eye"></i> {{ $video['views'] }} lượt xem
                            </span>
                            <span class="video-date">
                                <i class="fa fa-clock"></i> {{ $video['published_at'] }}
                            </span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            <div class="youtube-cta-section" id="youtube-channel-section" data-channel-id="{{ $youtubeVideos['channel_info']['channel_id'] }}">
                <!-- Banner Background Container -->
                <div class="channel-banner-background" id="channel-banner-bg">
                    <!-- Overlay for text readability -->
                    <div class="banner-overlay"></div>
                    
                    <!-- Minimal Channel Stats at Bottom -->
                    <div class="channel-stats-minimal">
                        <div class="stat-item-minimal">
                            <span class="stat-number-minimal">{{ $youtubeVideos['channel_info']['subscribers'] }}</span>
                            <span class="stat-label-minimal">subscribers</span>
                        </div>
                        <div class="stat-divider-minimal">•</div>
                        <div class="stat-item-minimal">
                            <span class="stat-number-minimal">{{ $youtubeVideos['channel_info']['total_views'] }}</span>
                            <span class="stat-label-minimal">total views</span>
                        </div>
                        <div class="stat-divider-minimal">•</div>
                        <div class="youtube-action-minimal">
                            <a href="{{ $youtubeVideos['channel_info']['channel_url'] }}" 
                               target="_blank" 
                               class="btn-youtube-minimal"
                               rel="noopener">
                                <i class="fab fa-youtube"></i> Xem thêm video
                            </a>
                        </div>
                    </div>
                    
                    <!-- Loading State -->
                    <div class="banner-loading" id="banner-loading">
                        <div class="loading-spinner"></div>
                        <p>Đang tải banner...</p>
                    </div>
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
        
        // YouTube Channel Banner Loading
        // To get a YouTube Data API v3 key:
        // 1. Go to https://console.developers.google.com/
        // 2. Create a new project or select existing
        // 3. Enable YouTube Data API v3
        // 4. Create credentials (API Key)
        // 5. Restrict the key to YouTube Data API v3 and your domain
        // 6. Replace the apiKey below with your valid key
        class YouTubeChannelBanner {
            constructor() {
                // YouTube Data API v3 key - replace with valid key
                this.apiKey = 'AQ.Ab8RN6IR67DyHGQz1jFL9Oz_hmaD7fZ-GSp4v6CdHdjnBJlc7w';
                this.channelId = 'UCv2lripWdZDKtlrRy1J0dBw';
                this.cache = {};
                this.retryCount = 0;
                this.maxRetries = 1; // Reduce retries to fail faster to fallback
                // Local banner image as ultimate fallback
                this.localFallbackUrl = '/lp/images/banner-em-sai-gon.jpg';
                // Flag to skip API calls if key is invalid
                this.apiKeyValid = true;
            }
            
            async fetchChannelBanner(channelId) {
                // Check cache first
                if (this.cache[channelId]) {
                    return this.cache[channelId];
                }
                
                // Try alternative method first - extract from channel page
                try {
                    const alternativeBanner = await this.extractBannerFromChannelPage(channelId);
                    if (alternativeBanner) {
                        console.log('Banner extracted from channel page:', alternativeBanner);
                        this.cache[channelId] = alternativeBanner;
                        return alternativeBanner;
                    }
                } catch (error) {
                    console.warn('Alternative banner extraction failed:', error);
                }
                
                // Skip API call if we know the key is invalid
                if (!this.apiKeyValid) {
                    console.log('Skipping YouTube API (invalid key), using local fallback');
                    return this.localFallbackUrl;
                }
                
                try {
                    console.log('Fetching YouTube channel banner via API...');
                    
                    // YouTube Data API v3 endpoint for channel branding
                    const apiUrl = `https://www.googleapis.com/youtube/v3/channels`;
                    const params = new URLSearchParams({
                        part: 'brandingSettings',
                        id: channelId,
                        key: this.apiKey
                    });
                    
                    // Try with OAuth2 token first (if key starts with certain patterns)
                    const isOAuthToken = this.apiKey.includes('.') || this.apiKey.startsWith('ya29');
                    
                    let response;
                    if (isOAuthToken) {
                        console.log('Using OAuth2 token for YouTube API...');
                        response = await fetch(`${apiUrl}?${params.toString().replace('key=', 'access_token=')}`, {
                            method: 'GET',
                            headers: {
                                'Accept': 'application/json',
                                'Authorization': `Bearer ${this.apiKey}`,
                                'Referer': window.location.origin
                            }
                        });
                    } else {
                        console.log('Using API key for YouTube API...');
                        response = await fetch(`${apiUrl}?${params}`, {
                            method: 'GET',
                            headers: {
                                'Accept': 'application/json',
                                'Referer': window.location.origin
                            }
                        });
                    }
                    
                    if (!response.ok) {
                        throw new Error(`YouTube API error: ${response.status} - ${response.statusText}`);
                    }
                    
                    const data = await response.json();
                    console.log('YouTube API response:', data);
                    
                    if (data.items && data.items.length > 0) {
                        const branding = data.items[0].brandingSettings;
                        const bannerUrl = branding?.image?.bannerExternalUrl;
                        
                        if (bannerUrl) {
                            console.log('Found YouTube banner:', bannerUrl);
                            
                            // Test if the banner URL is accessible
                            const testImg = new Image();
                            const isAccessible = await new Promise((resolve) => {
                                testImg.onload = () => {
                                    console.log('YouTube banner is accessible');
                                    resolve(true);
                                };
                                testImg.onerror = () => {
                                    console.warn('YouTube banner failed to load');
                                    resolve(false);
                                };
                                testImg.src = this.getResponsiveBannerUrl(bannerUrl);
                                // Timeout after 5 seconds
                                setTimeout(() => {
                                    console.warn('YouTube banner load timeout');
                                    resolve(false);
                                }, 5000);
                            });
                            
                            if (isAccessible) {
                                // Cache the successful result
                                this.cache[channelId] = bannerUrl;
                                this.retryCount = 0; // Reset retry count on success
                                return bannerUrl;
                            }
                        } else {
                            console.warn('No banner URL found in API response');
                        }
                    } else {
                        console.warn('No channel data found in API response');
                    }
                    
                } catch (error) {
                    console.error('YouTube API fetch failed:', error);
                    
                    // Check if it's an API key error
                    if (error.message.includes('API key not valid') || error.message.includes('400')) {
                        console.warn('Invalid API key detected, marking as invalid');
                        this.apiKeyValid = false;
                        // Don't retry for API key errors
                        console.log('Using local fallback due to invalid API key');
                        return this.localFallbackUrl;
                    }
                    
                    // Retry logic for other API failures (network, quota, etc.)
                    if (this.retryCount < this.maxRetries) {
                        this.retryCount++;
                        console.log(`Retrying YouTube API... (${this.retryCount}/${this.maxRetries})`);
                        await new Promise(resolve => setTimeout(resolve, 2000)); // Wait 2 seconds
                        return this.fetchChannelBanner(channelId);
                    }
                }
                
                // Fallback to local image if YouTube API fails
                console.log('Using local fallback banner');
                return this.localFallbackUrl;
            }
            
            async extractBannerFromChannelPage(channelId) {
                // Since we can't easily scrape YouTube due to CORS and dynamic content,
                // we'll use a known working banner URL for the LamGame channel
                // This can be manually updated when needed
                
                if (channelId === 'UCv2lripWdZDKtlrRy1J0dBw') {
                    // Known banner URLs for LamGame channel (update manually when needed)
                    const knownBanners = [
                        'https://yt3.googleusercontent.com/K8dWs8jRMbCSGnc0iF2eS-M7Hxsqi1CWZ9ZrE0pLr8ikUFu4Ogure4hyFmiYt6CHGZrISDRYxag=w2560-fcrop64=1,00005a57ffffa5a8-k-c0xffffffff-no-nd-rj',
                        // Fallback URLs if the primary changes
                        'https://yt3.googleusercontent.com/K8dWs8jRMbCSGnc0iF2eS-M7Hxsqi1CWZ9ZrE0pLr8ikUFu4Ogure4hyFmiYt6CHGZrISDRYxag=w1440-fcrop64=1,00005a57ffffa5a8-k-c0xffffffff-no-nd-rj',
                        'https://yt3.googleusercontent.com/K8dWs8jRMbCSGnc0iF2eS-M7Hxsqi1CWZ9ZrE0pLr8ikUFu4Ogure4hyFmiYt6CHGZrISDRYxag=w1024-fcrop64=1,00005a57ffffa5a8-k-c0xffffffff-no-nd-rj'
                    ];
                    
                    // Test each known banner URL
                    for (const bannerUrl of knownBanners) {
                        try {
                            const isAccessible = await new Promise((resolve) => {
                                const testImg = new Image();
                                testImg.onload = () => resolve(true);
                                testImg.onerror = () => resolve(false);
                                testImg.src = bannerUrl;
                                setTimeout(() => resolve(false), 3000); // 3 second timeout
                            });
                            
                            if (isAccessible) {
                                console.log('Found working banner URL:', bannerUrl);
                                return bannerUrl;
                            }
                        } catch (error) {
                            console.warn('Failed to test banner URL:', bannerUrl, error);
                        }
                    }
                }
                
                // If no known banners work, return null to try other methods
                return null;
            }
            
            getResponsiveBannerUrl(baseUrl) {
                if (!baseUrl) return null;
                
                // Check if it's a local image
                if (baseUrl.startsWith('/') || baseUrl.startsWith(window.location.origin)) {
                    return baseUrl;
                }
                
                // Handle YouTube banner URLs with responsive sizing
                if (baseUrl.includes('yt3.googleusercontent.com')) {
                    const screenWidth = window.innerWidth || 1920;
                    
                    // Extract base URL without size parameters
                    let baseImageUrl;
                    if (baseUrl.includes('=w')) {
                        // Remove existing size parameters
                        baseImageUrl = baseUrl.replace(/=w\d+-.*?-rj/g, '');
                    } else {
                        baseImageUrl = baseUrl;
                    }
                    
                    // Add appropriate size parameter based on screen width
                    let sizeParam;
                    if (screenWidth <= 480) {
                        sizeParam = '=w640-fcrop64=1,00005a57ffffa5a8-k-c0xffffffff-no-nd-rj';
                    } else if (screenWidth <= 768) {
                        sizeParam = '=w1024-fcrop64=1,00005a57ffffa5a8-k-c0xffffffff-no-nd-rj';
                    } else if (screenWidth <= 1024) {
                        sizeParam = '=w1440-fcrop64=1,00005a57ffffa5a8-k-c0xffffffff-no-nd-rj';
                    } else {
                        sizeParam = '=w2560-fcrop64=1,00005a57ffffa5a8-k-c0xffffffff-no-nd-rj';
                    }
                    
                    // Combine base URL with responsive size parameter
                    const responsiveUrl = baseImageUrl + sizeParam;
                    
                    console.log(`Responsive YouTube banner URL for width ${screenWidth}:`, responsiveUrl);
                    return responsiveUrl;
                }
                
                // For other external URLs, return as-is
                return baseUrl;
            }
            
            async applyBannerBackground() {
                const bannerContainer = document.getElementById('channel-banner-bg');
                const loadingElement = document.getElementById('banner-loading');
                
                if (!bannerContainer) {
                    console.warn('Banner container not found');
                    return;
                }
                
                console.log('Starting banner loading process...');
                const startTime = performance.now();
                
                // Show loading state
                if (loadingElement) {
                    loadingElement.classList.add('loading');
                }
                
                try {
                    const baseBannerUrl = await this.fetchChannelBanner(this.channelId);
                    
                    if (baseBannerUrl) {
                        const responsiveBannerUrl = this.getResponsiveBannerUrl(baseBannerUrl);
                        
                        // Preload the image
                        const img = new Image();
                        img.onload = () => {
                            const loadTime = performance.now() - startTime;
                            console.log(`Banner loaded successfully in ${loadTime.toFixed(2)}ms`);
                            
                            bannerContainer.style.backgroundImage = `url('${responsiveBannerUrl}')`;
                            bannerContainer.classList.add('banner-loaded');
                            
                            // Hide loading state
                            if (loadingElement) {
                                setTimeout(() => {
                                    loadingElement.classList.remove('loading');
                                }, 500);
                            }
                            
                            console.log('YouTube banner applied to background');
                        };
                        
                        img.onerror = () => {
                            console.warn('Failed to load banner image');
                            this.handleBannerError();
                        };
                        
                        img.src = responsiveBannerUrl;
                    } else {
                        this.handleBannerError();
                    }
                } catch (error) {
                    console.error('Error applying banner:', error);
                    this.handleBannerError();
                }
            }
            
            handleBannerError() {
                const bannerContainer = document.getElementById('channel-banner-bg');
                const loadingElement = document.getElementById('banner-loading');
                
                // Try local fallback image first
                if (bannerContainer && this.localFallbackUrl) {
                    console.log('Attempting to use local fallback image:', this.localFallbackUrl);
                    
                    const testImg = new Image();
                    testImg.onload = () => {
                        console.log('Local fallback image loaded successfully');
                        bannerContainer.style.backgroundImage = `url('${this.localFallbackUrl}')`;
                        bannerContainer.classList.add('banner-loaded');
                        
                        // Hide loading state
                        if (loadingElement) {
                            setTimeout(() => {
                                loadingElement.classList.remove('loading');
                            }, 300);
                        }
                    };
                    
                    testImg.onerror = () => {
                        console.warn('Local fallback image also failed, using gradient');
                        // Final fallback to gradient
                        bannerContainer.style.background = 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)';
                        
                        // Hide loading state
                        if (loadingElement) {
                            setTimeout(() => {
                                loadingElement.classList.remove('loading');
                            }, 300);
                        }
                    };
                    
                    testImg.src = this.localFallbackUrl;
                } else {
                    // Use gradient if no local fallback
                    if (bannerContainer) {
                        bannerContainer.style.background = 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)';
                    }
                    
                    // Hide loading state
                    if (loadingElement) {
                        setTimeout(() => {
                            loadingElement.classList.remove('loading');
                        }, 300);
                    }
                }
                
                console.log('Using fallback banner background');
            }
        }
        
        // Initialize YouTube banner loader
        let youtubeBanner;
        
        // Debug function to test banner loading and layout
        window.testYouTubeBanner = async function() {
            console.log('=== Testing Optimized YouTube Banner ===');
            const banner = new YouTubeChannelBanner();
            const channelId = 'UCv2lripWdZDKtlrRy1J0dBw';
            
            try {
                const bannerUrl = await banner.fetchChannelBanner(channelId);
                console.log('Final banner URL:', bannerUrl);
                
                const responsiveUrl = banner.getResponsiveBannerUrl(bannerUrl);
                console.log('Responsive banner URL:', responsiveUrl);
                
                // Test the URL and check aspect ratio
                const img = new Image();
                img.onload = () => {
                    const aspectRatio = (img.width / img.height).toFixed(2);
                    console.log('✅ Optimized banner test successful!', {
                        dimensions: `${img.width}x${img.height}`,
                        aspectRatio: aspectRatio,
                        isOptimal: aspectRatio >= '5.5' && aspectRatio <= '6.5' ? 'Yes' : 'Check ratio',
                        url: responsiveUrl
                    });
                    
                    // Test layout elements
                    const bannerContainer = document.getElementById('channel-banner-bg');
                    const statsContainer = document.querySelector('.channel-stats-minimal');
                    const youtubeButton = document.querySelector('.btn-youtube-minimal');
                    const description = document.querySelector('.channel-description');
                    
                    console.log('Minimal Layout Check:', {
                        bannerContainer: bannerContainer ? '✅ Found' : '❌ Missing',
                        statsContainerMinimal: statsContainer ? '✅ Found at bottom' : '❌ Missing',
                        youtubeButtonMinimal: youtubeButton ? '✅ Small inline button' : '❌ Missing',
                        description: description ? '❌ Still exists (should be removed)' : '✅ Removed',
                        bannerWidth: bannerContainer ? `${bannerContainer.offsetWidth}px (${(bannerContainer.offsetWidth / window.innerWidth * 100).toFixed(1)}%)` : 'N/A',
                        bannerHeight: bannerContainer ? `${bannerContainer.offsetHeight}px` : 'N/A',
                        dynamicHeight: `calc(100vw / 6) = ${window.innerWidth / 6}px`
                    });
                };
                img.onerror = () => {
                    console.error('❌ Banner test failed');
                };
                img.src = responsiveUrl;
                
            } catch (error) {
                console.error('Banner test error:', error);
            }
        };
        
        // Lazy loading with Intersection Observer
        function initializeLazyBannerLoading() {
            const channelSection = document.getElementById('youtube-channel-section');
            
            if (!channelSection || !('IntersectionObserver' in window)) {
                // Fallback for browsers without Intersection Observer
                if (channelSection) {
                    youtubeBanner = new YouTubeChannelBanner();
                    youtubeBanner.applyBannerBackground();
                }
                return;
            }
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting && !entry.target.dataset.bannerLoaded) {
                        entry.target.dataset.bannerLoaded = 'true';
                        
                        youtubeBanner = new YouTubeChannelBanner();
                        youtubeBanner.applyBannerBackground();
                        
                        // Stop observing after loading
                        observer.unobserve(entry.target);
                    }
                });
            }, {
                rootMargin: '100px 0px', // Load when 100px before entering viewport
                threshold: 0.1
            });
            
            observer.observe(channelSection);
        }
        
        // Handle window resize for responsive banner
        let resizeTimeout;
        function handleBannerResize() {
            if (youtubeBanner && window.innerWidth) {
                clearTimeout(resizeTimeout);
                resizeTimeout = setTimeout(() => {
                    youtubeBanner.applyBannerBackground();
                }, 250);
            }
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
            
            // Initialize YouTube channel banner lazy loading
            initializeLazyBannerLoading();
            
            // Add resize event listener for responsive banner
            window.addEventListener('resize', handleBannerResize);
        });
    </script>
    @endpush
@endsection
