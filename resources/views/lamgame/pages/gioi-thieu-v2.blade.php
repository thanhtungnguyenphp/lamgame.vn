@extends('layouts.master')

@section('page_title', 'Giới thiệu LamGame.vn — Hệ sinh thái Game Developer Việt Nam')
@section('page_description', 'LamGame.vn là hệ sinh thái dành cho cộng đồng Game Developer Việt Nam với source code, tutorial, việc làm và cộng đồng.')

@push('schema_markup')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Organization",
    "name": "LamGame.vn",
    "alternateName": "Làm Game",
    "url": "{{ url('/') }}",
    "logo": "{{ asset('logo/lamgame-logo.png') }}",
    "description": "Hệ sinh thái dành cho Game Developer Việt Nam - Source code, tutorial, việc làm và cộng đồng",
    "foundingDate": "2024",
    "founder": {
        "@type": "Person",
        "name": "LamGame Team"
    },
    "sameAs": [
        "https://www.facebook.com/lamgamevn",
        "https://www.youtube.com/@lamgamevn",
        "https://github.com/lamgamevn"
    ],
    "contactPoint": {
        "@type": "ContactPoint",
        "contactType": "customer service",
        "telephone": "+84-91111-8300",
        "email": "salegamevui@gmail.com",
        "url": "{{ url('/lien-he') }}",
        "availableLanguage": ["Vietnamese", "English"]
    },
    "address": {
        "@type": "PostalAddress",
        "streetAddress": "Tòa nhà E.Town Central, 11 Đoàn Văn Bơ",
        "addressLocality": "Hồ Chí Minh",
        "addressCountry": "VN"
    },
    "areaServed": "VN",
    "knowsAbout": [
        "Game Development",
        "Unity",
        "Unreal Engine",
        "Godot",
        "Game Design",
        "Mobile Game Development",
        "Indie Game"
    ]
}
</script>
@endpush

@section('content')
    {{-- Hero Section --}}
    <section class="about-hero">
        <div class="container">
            <h1>LamGame.vn</h1>
            <p class="about-hero__tagline">Hệ sinh thái dành cho Game Developer Việt Nam</p>
            <p class="about-hero__motto">Learn. Build. Connect. Ship.</p>
        </div>
    </section>

    {{-- Main Content --}}
    <section class="about-content">
        <div class="container">
            <div class="about-grid">
                {{-- Left Column --}}
                <div class="about-main">
                    {{-- Mission --}}
                    <div class="about-block">
                        <h2>Sứ mệnh</h2>
                        <p class="about-intro">
                            <strong>LamGame.vn</strong> là hệ sinh thái dành cho cộng đồng Game Developer Việt Nam, 
                            kết nối kiến thức, công cụ, source code, cơ hội việc làm và cộng đồng 
                            để giúp developer <strong>học, xây dựng và phát hành game</strong> tốt hơn.
                        </p>
                        <p>
                            Chúng tôi tin rằng ngành game Việt Nam có tiềm năng to lớn. 
                            LamGame.vn ra đời để hỗ trợ developer từ bước đầu tiên học code 
                            cho đến khi ship game ra thị trường.
                        </p>
                    </div>

                    {{-- What We Offer --}}
                    <div class="about-block">
                        <h2>Chúng tôi cung cấp</h2>
                        <div class="about-features">
                            <div class="about-feature">
                                <div class="about-feature__icon">🎮</div>
                                <div class="about-feature__content">
                                    <h4>Source Game Marketplace</h4>
                                    <p>Mua bán source code game chất lượng cao, tiết kiệm thời gian phát triển.</p>
                                    <a href="{{ route('lamgame.source-game') }}">Khám phá Source Game →</a>
                                </div>
                            </div>
                            
                            <div class="about-feature">
                                <div class="about-feature__icon">📚</div>
                                <div class="about-feature__content">
                                    <h4>Tutorial & Kiến thức</h4>
                                    <p>Bài viết chuyên sâu về Unity, Godot, Unreal, Game Design và best practices.</p>
                                    <a href="{{ route('lamgame.blog') }}">Đọc Blog →</a>
                                </div>
                            </div>
                            
                            <div class="about-feature">
                                <div class="about-feature__icon">💼</div>
                                <div class="about-feature__content">
                                    <h4>Việc làm Game Developer</h4>
                                    <p>Cơ hội việc làm từ các game studio hàng đầu Việt Nam và quốc tế.</p>
                                    <a href="{{ route('lamgame.viec-lam-game') }}">Tìm việc →</a>
                                </div>
                            </div>
                            
                            <div class="about-feature">
                                <div class="about-feature__icon">💬</div>
                                <div class="about-feature__content">
                                    <h4>Cộng đồng & Forum</h4>
                                    <p>Nơi developer Việt Nam trao đổi, hỏi đáp và chia sẻ kinh nghiệm.</p>
                                    <a href="{{ route('forum.index') }}">Tham gia Forum →</a>
                                </div>
                            </div>
                            
                            <div class="about-feature">
                                <div class="about-feature__icon">🤖</div>
                                <div class="about-feature__content">
                                    <h4>AI Tools</h4>
                                    <p>Công cụ AI hỗ trợ game development: generate ideas, code snippets, và more.</p>
                                    <a href="{{ route('lamgame.ai-tools') }}">Thử AI Tools →</a>
                                </div>
                            </div>
                            
                            <div class="about-feature">
                                <div class="about-feature__icon">🎯</div>
                                <div class="about-feature__content">
                                    <h4>Showcase & Game Jam</h4>
                                    <p>Trưng bày dự án, tham gia game jam và nhận feedback từ cộng đồng.</p>
                                    <a href="{{ route('forum.index') }}?category=showcase">Xem Showcase →</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Community Stats --}}
                    <div class="about-block about-stats">
                        <h2>Cộng đồng LamGame</h2>
                        <div class="stats-grid">
                            <div class="stat-card">
                                <div class="stat-card__number">{{ number_format($metrics['registered_users'] ?? 0) }}</div>
                                <div class="stat-card__label">Registered Developers</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-card__number">{{ number_format($metrics['published_sources'] ?? 0) }}</div>
                                <div class="stat-card__label">Source Codes</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-card__number">{{ number_format($metrics['forum_posts'] ?? 0) }}</div>
                                <div class="stat-card__label">Forum Discussions</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-card__number">{{ number_format($metrics['job_listings'] ?? 0) }}</div>
                                <div class="stat-card__label">Job Listings</div>
                            </div>
                        </div>
                        <p class="stats-note">
                            <small>* Số liệu được cập nhật tự động từ hệ thống LamGame.vn</small>
                        </p>
                    </div>

                    {{-- Our Values --}}
                    <div class="about-block">
                        <h2>Giá trị cốt lõi</h2>
                        <div class="values-grid">
                            <div class="value-item">
                                <h4>🎯 Quality First</h4>
                                <p>Source code được review kỹ lưỡng. Nội dung technical được fact-check bởi developer có kinh nghiệm.</p>
                            </div>
                            <div class="value-item">
                                <h4>🤝 Community Driven</h4>
                                <p>Mọi quyết định đều hướng tới lợi ích của cộng đồng game developer Việt Nam.</p>
                            </div>
                            <div class="value-item">
                                <h4>🚀 Always Learning</h4>
                                <p>Liên tục cập nhật công nghệ mới, xu hướng mới trong ngành game development.</p>
                            </div>
                            <div class="value-item">
                                <h4>💡 Open & Transparent</h4>
                                <p>Quy trình rõ ràng, chính sách công khai, hỗ trợ nhanh chóng.</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Right Sidebar --}}
                <div class="about-sidebar">
                    {{-- Contact Card --}}
                    <div class="sidebar-card">
                        <h3>📞 Liên hệ</h3>
                        <div class="contact-list">
                            <div class="contact-item">
                                <i class="fa fa-phone"></i>
                                <span>09.1111.8300</span>
                            </div>
                            <div class="contact-item">
                                <i class="fa fa-envelope"></i>
                                <span>salegamevui@gmail.com</span>
                            </div>
                            <div class="contact-item">
                                <i class="fa fa-map-marker"></i>
                                <span>Quận 4, TP.HCM</span>
                            </div>
                        </div>
                        <a href="{{ route('lamgame.lien-he') }}" class="sidebar-btn">
                            Gửi tin nhắn
                        </a>
                    </div>

                    {{-- Social Links --}}
                    <div class="sidebar-card">
                        <h3>🔗 Kết nối</h3>
                        <div class="social-links">
                            <a href="https://facebook.com/lamgamevn" target="_blank" class="social-link">
                                <i class="fa fa-facebook"></i> Facebook
                            </a>
                            <a href="https://www.youtube.com/@lamgamevn" target="_blank" class="social-link">
                                <i class="fa fa-youtube-play"></i> YouTube
                            </a>
                            <a href="https://github.com/lamgamevn" target="_blank" class="social-link">
                                <i class="fa fa-github"></i> GitHub
                            </a>
                            <a href="https://discord.gg/lamgame" target="_blank" class="social-link">
                                <i class="fa fa-comments"></i> Discord
                            </a>
                        </div>
                    </div>

                    {{-- Policy Links --}}
                    <div class="sidebar-card">
                        <h3>📋 Chính sách</h3>
                        <ul class="policy-links">
                            <li><a href="{{ route('lamgame.chinh-sach-bien-tap') }}">Chính sách biên tập</a></li>
                            <li><a href="{{ route('lamgame.chinh-sach-chinh-sua') }}">Chính sách chỉnh sửa</a></li>
                            <li><a href="/page/chinh-sach-bao-mat">Chính sách bảo mật</a></li>
                            <li><a href="/page/dieu-khoan-dich-vu">Điều khoản dịch vụ</a></li>
                            <li><a href="/page/chinh-sach-hoan-tien-tranh-chap">Hoàn tiền & Tranh chấp</a></li>
                        </ul>
                    </div>

                    {{-- CTA --}}
                    <div class="sidebar-card sidebar-card--cta">
                        <h3>🚀 Bắt đầu ngay</h3>
                        <p>Tham gia cộng đồng game developer Việt Nam!</p>
                        <a href="{{ route('shop.customers.register.index') }}" class="sidebar-btn sidebar-btn--primary">
                            Đăng ký miễn phí
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

@push('styles')
<style>
/* Hero */
.about-hero {
    background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
    color: white;
    padding: 4rem 0;
    text-align: center;
}

.about-hero h1 {
    font-size: 3rem;
    font-weight: 700;
    margin: 0 0 0.5rem;
}

.about-hero__tagline {
    font-size: 1.25rem;
    margin: 0 0 0.5rem;
    opacity: 0.95;
}

.about-hero__motto {
    font-size: 1rem;
    margin: 0;
    opacity: 0.8;
    font-style: italic;
}

/* Content */
.about-content {
    padding: 3rem 0;
    background: #f8fafc;
}

.about-grid {
    display: grid;
    grid-template-columns: 1fr 320px;
    gap: 2rem;
}

/* Main */
.about-main {
    display: flex;
    flex-direction: column;
    gap: 2rem;
}

.about-block {
    background: white;
    padding: 2rem;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.about-block h2 {
    color: #1e1b4b;
    font-size: 1.5rem;
    margin: 0 0 1.5rem;
    padding-bottom: 0.75rem;
    border-bottom: 2px solid #e0e7ff;
}

.about-intro {
    font-size: 1.1rem;
    line-height: 1.7;
    color: #374151;
}

/* Features */
.about-features {
    display: grid;
    gap: 1.5rem;
}

.about-feature {
    display: flex;
    gap: 1rem;
    padding: 1rem;
    background: #f8fafc;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
    transition: all 0.2s;
}

.about-feature:hover {
    border-color: #c7d2fe;
    background: #fafafe;
}

.about-feature__icon {
    font-size: 2rem;
    flex-shrink: 0;
}

.about-feature__content h4 {
    margin: 0 0 0.5rem;
    color: #1e1b4b;
}

.about-feature__content p {
    margin: 0 0 0.5rem;
    color: #6b7280;
    font-size: 0.9rem;
}

.about-feature__content a {
    color: #6366f1;
    text-decoration: none;
    font-size: 0.9rem;
    font-weight: 500;
}

.about-feature__content a:hover {
    text-decoration: underline;
}

/* Stats */
.about-stats {
    background: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%);
    color: white;
}

.about-stats h2 {
    color: white;
    border-bottom-color: rgba(255,255,255,0.2);
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1rem;
}

.stat-card {
    text-align: center;
    padding: 1rem;
    background: rgba(255,255,255,0.1);
    border-radius: 8px;
}

.stat-card__number {
    font-size: 2rem;
    font-weight: 700;
    color: #a5b4fc;
}

.stat-card__label {
    font-size: 0.8rem;
    color: rgba(255,255,255,0.8);
}

.stats-note {
    margin: 1rem 0 0;
    text-align: center;
    color: rgba(255,255,255,0.6);
}

/* Values */
.values-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.5rem;
}

.value-item h4 {
    margin: 0 0 0.5rem;
    color: #1e1b4b;
}

.value-item p {
    margin: 0;
    color: #6b7280;
    font-size: 0.9rem;
    line-height: 1.6;
}

/* Sidebar */
.about-sidebar {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.sidebar-card {
    background: white;
    padding: 1.5rem;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.sidebar-card h3 {
    margin: 0 0 1rem;
    font-size: 1.1rem;
    color: #1e1b4b;
}

.contact-list {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
    margin-bottom: 1rem;
}

.contact-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    color: #4b5563;
    font-size: 0.9rem;
}

.contact-item i {
    color: #6366f1;
    width: 16px;
}

.social-links {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.social-link {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.5rem 0.75rem;
    color: #4b5563;
    text-decoration: none;
    border-radius: 6px;
    transition: all 0.2s;
}

.social-link:hover {
    background: #f3f4f6;
    color: #6366f1;
}

.social-link i {
    width: 20px;
}

.policy-links {
    list-style: none;
    padding: 0;
    margin: 0;
}

.policy-links li {
    padding: 0.5rem 0;
    border-bottom: 1px solid #f3f4f6;
}

.policy-links li:last-child {
    border-bottom: none;
}

.policy-links a {
    color: #4b5563;
    text-decoration: none;
    font-size: 0.9rem;
}

.policy-links a:hover {
    color: #6366f1;
}

.sidebar-btn {
    display: block;
    text-align: center;
    padding: 0.75rem 1rem;
    background: #f3f4f6;
    color: #374151;
    text-decoration: none;
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.2s;
}

.sidebar-btn:hover {
    background: #e5e7eb;
}

.sidebar-card--cta {
    background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
    color: white;
}

.sidebar-card--cta h3 {
    color: white;
}

.sidebar-card--cta p {
    color: rgba(255,255,255,0.9);
    font-size: 0.9rem;
}

.sidebar-btn--primary {
    background: white;
    color: #6366f1;
}

.sidebar-btn--primary:hover {
    background: #f0f0ff;
}

/* Responsive */
@media (max-width: 968px) {
    .about-grid {
        grid-template-columns: 1fr;
    }
    
    .about-sidebar {
        order: -1;
        display: grid;
        grid-template-columns: repeat(2, 1fr);
    }
    
    .sidebar-card--cta {
        grid-column: span 2;
    }
    
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .values-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 640px) {
    .about-hero h1 {
        font-size: 2rem;
    }
    
    .about-sidebar {
        grid-template-columns: 1fr;
    }
    
    .sidebar-card--cta {
        grid-column: span 1;
    }
}
</style>
@endpush
@endsection
