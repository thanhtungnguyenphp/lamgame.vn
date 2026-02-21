{{-- LAMGAME HOMEPAGE - Updated with Optimized 4-Slide Banner --}}
@extends('layouts.master')

@section('page_title', 'LamGame.vn — Cộng đồng Game Developer Việt Nam | Việc làm Game Dev')

@section('page_description', 'Cộng đồng Game Developer Việt Nam hàng đầu. Tìm việc làm game dev, thảo luận Unity/Unreal Engine, chia sẻ source code và ý tưởng game sáng tạo. 50+ jobs mới mỗi tuần từ VNG, Gameloft.')

@push('meta')
    {{-- Additional SEO Meta Tags --}}
    <meta name="keywords" content="game developer việt nam, unity developer, unreal engine, việc làm game, lập trình game, forum game dev, source code game, tuyển dụng game">
    <meta name="author" content="LamGame.vn Team">
    <meta name="robots" content="index,follow">

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
    <style>
    /* Dynamic Banner Styles */
    .bg.custom-image {
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
    }

    /* Fallback banner styles */
    .banner-fallback {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 400px;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        color: white;
        position: relative;
        overflow: hidden;
    }

    .banner-fallback::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.3);
        z-index: 1;
    }

    .banner-fallback .content {
        position: relative;
        z-index: 2;
        max-width: 800px;
        padding: 2rem;
    }

    .banner-fallback h1 {
        font-size: 2.5rem;
        margin-bottom: 1rem;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
    }

    .banner-fallback p {
        font-size: 1.2rem;
        margin-bottom: 2rem;
        text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
    }

    .banner-fallback .btns {
        display: flex;
        gap: 1rem;
        justify-content: center;
        flex-wrap: wrap;
    }

    .banner-fallback .btn {
        padding: 1rem 2rem;
        text-decoration: none;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
        display: inline-block;
    }

    .banner-fallback .btn.primary {
        background: #ff6b35;
        color: white;
    }

    .banner-fallback .btn.primary:hover {
        background: #e55a2e;
        transform: translateY(-2px);
    }

    .banner-fallback .btn.secondary {
        background: transparent;
        color: white;
        border: 2px solid white;
    }

    .banner-fallback .btn.secondary:hover {
        background: white;
        color: #667eea;
    }

    /* Mobile responsive fallback */
    @media (max-width: 768px) {
        .banner-fallback {
            min-height: 300px;
        }

        .banner-fallback h1 {
            font-size: 2rem;
        }

        .banner-fallback p {
            font-size: 1rem;
        }

        .banner-fallback .btns {
            flex-direction: column;
            align-items: center;
        }

        .banner-fallback .btn {
            width: 100%;
            max-width: 300px;
        }
    }

    /* Banner tracking debug info (only in development) */
    @if(config('app.debug'))
    .banner-debug {
        position: absolute;
        top: 10px;
        right: 10px;
        background: rgba(0, 0, 0, 0.7);
        color: white;
        padding: 0.5rem;
        border-radius: 4px;
        font-size: 0.8rem;
        z-index: 10;
    }
    @endif
    </style>
@endpush


@section('content')
    <!-- LamGame Dynamic Banner Section -->
    <section class="hero-optimized" id="hero-banner" aria-label="Banner chính LamGame.vn">
        @if(!empty($homepageBanners['banners']))
            <button class="arrow banner-arrow prev" aria-label="Slide trước" tabindex="0">◄</button>
            <button class="arrow banner-arrow next" aria-label="Slide sau" tabindex="0">►</button>

            <div class="track" id="banner-track">
                @foreach($homepageBanners['banners'] as $index => $banner)
                    @if($banner['is_active'])
                        <div class="slide" data-banner-id="{{ $banner['id'] }}">
                            <a href="{{ $banner['link'] }}" class="slide-link" title="{{ $banner['title'] }}" target="{{ $banner['target'] }}" onclick="trackBannerClick({{ $banner['id'] }})">
                                @if($banner['image'])
                                    <div class="bg custom-image" style="background-image: url('{{ $banner['image'] }}');"></div>
                                @else
                                    <div class="bg {{ $banner['css_classes'] }}"></div>
                                @endif
                                <div class="overlay"></div>
                                <div class="content">
                                    <h1>{{ $banner['title'] }}</h1>
                                    <p>{{ $banner['content'] }}</p>
                                    <div class="btns">
                                        <span class="btn primary">Khám phá ngay</span>
                                        @if($index == 0)
                                            <a class="btn secondary" href="{{ route('forum.index') }}" onclick="event.stopPropagation()">Hỏi kinh nghiệm</a>
                                        @endif
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endif
                @endforeach
            </div>

            <div class="dots" aria-hidden="true">
                @foreach($homepageBanners['banners'] as $index => $banner)
                    @if($banner['is_active'])
                        <div class="dot banner-dot" tabindex="0" aria-label="Đi đến slide {{ $index + 1 }}"></div>
                    @endif
                @endforeach
            </div>

            @if($homepageBanners['has_banners'])
                <!-- Banner Analytics Tracking -->
                <script>
                    // Track banner impressions
                    document.addEventListener('DOMContentLoaded', function() {
                        const banners = document.querySelectorAll('[data-banner-id]');
                        banners.forEach(function(slide) {
                            const bannerId = slide.getAttribute('data-banner-id');
                            if (bannerId && bannerId !== 'fallback-1' && bannerId !== 'fallback-2' && bannerId !== 'fallback-3' && bannerId !== 'fallback-4') {
                                // Track impression after slide is visible for 1 second
                                setTimeout(function() {
                                    if (isElementVisible(slide)) {
                                        trackBannerImpression(bannerId);
                                    }
                                }, 1000);
                            }
                        });
                    });

                    // Track banner clicks
                    function trackBannerClick(bannerId) {
                        if (bannerId && !bannerId.toString().startsWith('fallback-')) {
                            fetch('/api/banners/' + bannerId + '/track-click', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest'
                                },
                                body: JSON.stringify({
                                    user_agent: navigator.userAgent,
                                    referrer: document.referrer
                                })
                            }).catch(function(error) {
                                console.log('Banner click tracking failed:', error);
                            });
                        }
                    }

                    // Track banner impressions
                    function trackBannerImpression(bannerId) {
                        fetch('/api/banners/' + bannerId + '/track-impression', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: JSON.stringify({
                                user_agent: navigator.userAgent,
                                referrer: document.referrer
                            })
                        }).catch(function(error) {
                            console.log('Banner impression tracking failed:', error);
                        });
                    }

                    // Helper function to check if element is visible
                    function isElementVisible(element) {
                        const rect = element.getBoundingClientRect();
                        return rect.top >= 0 && rect.left >= 0 &&
                               rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
                               rect.right <= (window.innerWidth || document.documentElement.clientWidth);
                    }
                </script>
            @endif
        @else
            <!-- Fallback static banner if no dynamic banners available -->
            <div class="banner-fallback">
                <div class="content">
                    <h1>LamGame.vn - Cộng đồng Game Developer Việt Nam</h1>
                    <p>Nơi kết nối các game developer, chia sẻ kiến thức và tìm kiếm cơ hội việc làm trong ngành game.</p>
                    <div class="btns">
                        <a href="{{ route('lamgame.viec-lam-game') }}" class="btn primary">Xem việc làm</a>
                        <a href="{{ route('forum.index') }}" class="btn secondary">Tham gia forum</a>
                    </div>
                </div>
            </div>
        @endif
    </section>

    <!-- Featured Forum Topics - Only show when quality data exists -->
    @php
        $hasQualityTopics = isset($hotForumTopics['featured']) 
            && count($hotForumTopics['featured']) >= 2
            && collect($hotForumTopics['featured'])->sum('replies') > 0;
    @endphp

    @if($hasQualityTopics)
    <section class="featured-topics-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">🔥 Chủ Đề Nổi Bật</h2>
                <p>{{ $hotForumTopics['total_posts'] }} chủ đề từ cộng đồng game developer</p>
            </div>

            <div class="topics-grid">
                @foreach(array_slice($hotForumTopics['featured'], 0, 3) as $topic)
                    <a href="{{ $topic['url'] }}" class="topic-card" target="_blank">
                        <div class="topic-header">
                            <span class="topic-category" style="background: {{ $topic['category_color'] }}15; color: {{ $topic['category_color'] }}">
                                {{ $topic['category_icon'] }} {{ $topic['category'] }}
                            </span>
                            <span class="topic-time">{{ $topic['time_ago'] }}</span>
                        </div>
                        <h4 class="topic-title">{{ $topic['title'] }}</h4>
                        <p class="topic-excerpt">{{ $topic['excerpt'] }}</p>
                        <div class="topic-stats">
                            <span class="stat-item">💬 {{ $topic['replies'] }}</span>
                            <span class="stat-item">👍 {{ $topic['likes'] }}</span>
                            <span class="stat-item">👁 {{ number_format($topic['views']) }}</span>
                        </div>
                    </a>
                @endforeach
            </div>

            <div class="topics-cta">
                <a href="{{ route('forum.index') }}" class="btn btn-outline">Xem tất cả Forum →</a>
            </div>
        </div>
    </section>
    @else
    {{-- Fallback: CTA nhẹ nhàng thay vì fake data --}}
    <section class="forum-cta-section">
        <div class="container">
            <div class="forum-cta-banner">
                <div class="forum-cta-content">
                    <h3>💬 Cộng đồng Game Developer Việt Nam</h3>
                    <p>Chia sẻ ý tưởng, hỏi đáp kỹ thuật, tìm team — tham gia forum ngay!</p>
                </div>
                <a href="{{ route('forum.index') }}" class="btn btn-primary">Vào Forum →</a>
            </div>
        </div>
    </section>
    @endif

@push('styles')
<style>
/* Featured Topics Section */
.featured-topics-section {
    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
    color: white;
    padding: 4rem 0;
}

.featured-topics-section .section-header { text-align: center; margin-bottom: 2.5rem; }
.featured-topics-section .section-title { color: #fff; font-size: 2rem; margin-bottom: 0.5rem; }
.featured-topics-section .section-header p { color: rgba(255,255,255,0.6); font-size: 1rem; }

.topics-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
}

a.topic-card {
    display: block;
    background: rgba(255,255,255,0.06);
    border: 1px solid rgba(255,255,255,0.08);
    border-radius: 12px;
    padding: 1.5rem;
    color: #ccc;
    text-decoration: none;
    transition: all 0.2s;
}
a.topic-card:hover {
    background: rgba(255,255,255,0.1);
    border-color: rgba(106,76,147,0.5);
    transform: translateY(-3px);
}

.topic-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem; }
.topic-category { padding: 0.2rem 0.6rem; border-radius: 12px; font-size: 0.8rem; font-weight: 600; }
.topic-time { font-size: 0.8rem; color: rgba(255,255,255,0.4); }
.topic-title { color: #fff; font-size: 1.05rem; font-weight: 600; margin: 0 0 0.5rem; line-height: 1.4; }
.topic-excerpt { font-size: 0.88rem; line-height: 1.5; margin-bottom: 1rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; color: rgba(255,255,255,0.5); }
.topic-stats { display: flex; gap: 1rem; font-size: 0.8rem; color: rgba(255,255,255,0.4); }

.topics-cta { text-align: center; margin-top: 2rem; }
.topics-cta .btn-outline { background: transparent; color: #fff; border: 1px solid rgba(255,255,255,0.2); padding: 0.75rem 2rem; border-radius: 8px; text-decoration: none; font-weight: 500; transition: all 0.2s; display: inline-block; }
.topics-cta .btn-outline:hover { background: rgba(255,255,255,0.1); border-color: rgba(255,255,255,0.4); }

/* Forum CTA Fallback Banner */
.forum-cta-section { padding: 2rem 0; }
.forum-cta-banner {
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
    border-radius: 12px;
    padding: 2rem 2.5rem;
    gap: 2rem;
}
.forum-cta-content h3 { color: #fff; font-size: 1.2rem; margin-bottom: 0.3rem; }
.forum-cta-content p { color: rgba(255,255,255,0.6); font-size: 0.95rem; margin: 0; }
.forum-cta-banner .btn-primary { white-space: nowrap; padding: 0.75rem 1.5rem; border-radius: 8px; font-weight: 600; text-decoration: none; }

@media (max-width: 768px) {
    .topics-grid { grid-template-columns: 1fr; }
    .forum-cta-banner { flex-direction: column; text-align: center; padding: 1.5rem; }
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
                    <span class="subtitle-meta">• Khám phá thêm {{ isset($latestBlogs['total_posts']) ? $latestBlogs['total_posts'] : '50+' }} bài viết chất lượng</span>
                    <a href="{{ route('lamgame.blog') }}" class="subtitle-link" target="_blank">Xem tất cả blog</a>
                </p>
            </div>

            @php
                $blogItems = $latestBlogs['featured'] ?? [];
                $heroPost = $blogItems[0] ?? null;
                $sidePosts = array_slice($blogItems, 1, 2);
                $bottomPosts = array_slice($blogItems, 3, 4);
            @endphp

            @if($heroPost)
            <div class="blog-bento">
                {{-- Hero card lớn bên trái --}}
                <article class="blog-card blog-card--hero">
                    <a href="{{ $heroPost['url'] }}" class="blog-card__link" target="_blank">
                        <div class="blog-card__img">
                            <img src="{{ $heroPost['featured_image'] }}" alt="{{ $heroPost['title'] }}" loading="lazy">
                            <div class="blog-card__gradient"></div>
                            <span class="blog-card__badge" style="--badge-color: {{ $heroPost['category_color'] }}">{{ $heroPost['category'] }}</span>
                            @if(($heroPost['views'] ?? 0) > 500)
                                <span class="blog-card__hot">🔥 Hot</span>
                            @endif
                        </div>
                        <div class="blog-card__body">
                            <div class="blog-card__chips">
                                <span class="chip"><i class="fa fa-clock"></i> {{ $heroPost['reading_time'] }} phút</span>
                                <span class="chip"><i class="fa fa-calendar"></i> {{ $heroPost['time_ago'] }}</span>
                            </div>
                            <h3 class="blog-card__title blog-card__title--lg">{{ $heroPost['title'] }}</h3>
                            <p class="blog-card__excerpt">{{ $heroPost['excerpt'] }}</p>
                            @if(($heroPost['views'] ?? 0) > 0 || ($heroPost['shares'] ?? 0) > 0)
                            <div class="blog-card__stats">
                                @if(($heroPost['views'] ?? 0) > 0)
                                    <span><i class="fa fa-eye"></i> {{ number_format($heroPost['views']) }}</span>
                                @endif
                                @if(($heroPost['shares'] ?? 0) > 0)
                                    <span><i class="fa fa-share-alt"></i> {{ $heroPost['shares'] }}</span>
                                @endif
                                <span><i class="fa fa-comments"></i> {{ rand(5, 50) }}</span>
                            </div>
                            @endif
                            <span class="blog-card__cta">Đọc ngay <i class="fa fa-arrow-right"></i></span>
                        </div>
                    </a>
                </article>

                {{-- 2 card nhỏ bên phải --}}
                <div class="blog-bento__side">
                    @foreach($sidePosts as $i => $blog)
                    <article class="blog-card blog-card--side">
                        <a href="{{ $blog['url'] }}" class="blog-card__link" target="_blank">
                            <div class="blog-card__img">
                                <img src="{{ $blog['featured_image'] }}" alt="{{ $blog['title'] }}" loading="lazy">
                                <div class="blog-card__gradient"></div>
                                <span class="blog-card__badge" style="--badge-color: {{ $blog['category_color'] }}">{{ $blog['category'] }}</span>
                            </div>
                            <div class="blog-card__body">
                                <div class="blog-card__chips">
                                    <span class="chip"><i class="fa fa-clock"></i> {{ $blog['reading_time'] }} phút</span>
                                    <span class="chip"><i class="fa fa-calendar"></i> {{ $blog['time_ago'] }}</span>
                                </div>
                                <h3 class="blog-card__title">{{ $blog['title'] }}</h3>
                                @if(($blog['views'] ?? 0) > 0)
                                <div class="blog-card__stats">
                                    <span><i class="fa fa-eye"></i> {{ number_format($blog['views']) }}</span>
                                    <span><i class="fa fa-comments"></i> {{ rand(5, 50) }}</span>
                                </div>
                                @endif
                                <span class="blog-card__cta">Đọc ngay <i class="fa fa-arrow-right"></i></span>
                            </div>
                        </a>
                    </article>
                    @endforeach
                </div>
            </div>

            {{-- Bottom row: horizontal compact cards --}}
            @if(count($bottomPosts) > 0)
            <div class="blog-bottom-row">
                @foreach($bottomPosts as $blog)
                <article class="blog-card blog-card--compact">
                    <a href="{{ $blog['url'] }}" class="blog-card__link" target="_blank">
                        <div class="blog-card__img">
                            <img src="{{ $blog['featured_image'] }}" alt="{{ $blog['title'] }}" loading="lazy">
                            <span class="blog-card__badge blog-card__badge--sm" style="--badge-color: {{ $blog['category_color'] }}">{{ $blog['category'] }}</span>
                        </div>
                        <div class="blog-card__body">
                            <h3 class="blog-card__title blog-card__title--sm">{{ $blog['title'] }}</h3>
                            <div class="blog-card__chips">
                                <span class="chip chip--xs"><i class="fa fa-clock"></i> {{ $blog['reading_time'] }}p</span>
                                <span class="chip chip--xs"><i class="fa fa-calendar"></i> {{ $blog['time_ago'] }}</span>
                            </div>
                        </div>
                    </a>
                </article>
                @endforeach
            </div>
            @endif

            @else
            {{-- Fallback khi không có data --}}
            <div class="blog-bento">
                <article class="blog-card blog-card--hero">
                    <a href="{{ route('lamgame.blog') }}" class="blog-card__link" target="_blank">
                        <div class="blog-card__img">
                            <img src="https://images.unsplash.com/photo-1556438064-2d7646166914?w=800&h=500&fit=crop" alt="Unity 2024" loading="lazy">
                            <div class="blog-card__gradient"></div>
                            <span class="blog-card__badge" style="--badge-color: #ff6b35">Unity</span>
                            <span class="blog-card__hot">🔥 Hot</span>
                        </div>
                        <div class="blog-card__body">
                            <div class="blog-card__chips">
                                <span class="chip"><i class="fa fa-clock"></i> 8 phút</span>
                                <span class="chip"><i class="fa fa-calendar"></i> 2 giờ trước</span>
                            </div>
                            <h3 class="blog-card__title blog-card__title--lg">Hướng dẫn Unity 2024 - Tính năng mới đáng chú ý</h3>
                            <p class="blog-card__excerpt">Unity 2024 mang đến nhiều cải tiến quan trọng giúp game developer tăng hiệu suất và chất lượng game.</p>
                            <div class="blog-card__stats">
                                <span><i class="fa fa-eye"></i> 1,250</span>
                                <span><i class="fa fa-share-alt"></i> 85</span>
                                <span><i class="fa fa-comments"></i> 24</span>
                            </div>
                            <span class="blog-card__cta">Đọc ngay <i class="fa fa-arrow-right"></i></span>
                        </div>
                    </a>
                </article>
                <div class="blog-bento__side">
                    <article class="blog-card blog-card--side">
                        <a href="{{ route('lamgame.blog') }}" class="blog-card__link" target="_blank">
                            <div class="blog-card__img">
                                <img src="https://images.unsplash.com/photo-1542751371-adc38448a05e?w=600&h=300&fit=crop" alt="C# Programming" loading="lazy">
                                <div class="blog-card__gradient"></div>
                                <span class="blog-card__badge" style="--badge-color: #667eea">Programming</span>
                            </div>
                            <div class="blog-card__body">
                                <div class="blog-card__chips">
                                    <span class="chip"><i class="fa fa-clock"></i> 12 phút</span>
                                    <span class="chip"><i class="fa fa-calendar"></i> 1 ngày trước</span>
                                </div>
                                <h3 class="blog-card__title">C# Cơ bản cho Game Developer</h3>
                                <div class="blog-card__stats">
                                    <span><i class="fa fa-eye"></i> 980</span>
                                    <span><i class="fa fa-comments"></i> 18</span>
                                </div>
                                <span class="blog-card__cta">Đọc ngay <i class="fa fa-arrow-right"></i></span>
                            </div>
                        </a>
                    </article>
                    <article class="blog-card blog-card--side">
                        <a href="{{ route('lamgame.blog') }}" class="blog-card__link" target="_blank">
                            <div class="blog-card__img">
                                <img src="https://images.unsplash.com/photo-1511512578047-dfb367046420?w=600&h=300&fit=crop" alt="Game Design" loading="lazy">
                                <div class="blog-card__gradient"></div>
                                <span class="blog-card__badge" style="--badge-color: #10b981">Game Design</span>
                            </div>
                            <div class="blog-card__body">
                                <div class="blog-card__chips">
                                    <span class="chip"><i class="fa fa-clock"></i> 6 phút</span>
                                    <span class="chip"><i class="fa fa-calendar"></i> 3 ngày trước</span>
                                </div>
                                <h3 class="blog-card__title">10 nguyên tắc Game Design bạn cần biết</h3>
                                <div class="blog-card__stats">
                                    <span><i class="fa fa-eye"></i> 720</span>
                                    <span><i class="fa fa-comments"></i> 31</span>
                                </div>
                                <span class="blog-card__cta">Đọc ngay <i class="fa fa-arrow-right"></i></span>
                            </div>
                        </a>
                    </article>
                </div>
            </div>
            @endif

        </div>
    </section>

    <!-- Marketplace Hub Section -->
    <section id="source-marketplace" class="mkt-section">
        <div class="container">
            {{-- Header với service tabs --}}
            <div class="section-header">
                <h2 class="section-title">🎮 Game Services Marketplace</h2>
                <p class="section-subtitle">Source code chất lượng & booking freelancer game dev — tất cả trong một nền tảng</p>
            </div>

            <div class="mkt-tabs" role="tablist">
                <button class="mkt-tab active" data-tab="source" role="tab" aria-selected="true">
                    <i class="fa fa-code"></i> Source Code
                    <span class="mkt-tab__count">{{ $sourceGames['total_sources'] ?? '25' }}+</span>
                </button>
                <button class="mkt-tab" data-tab="hire" role="tab" aria-selected="false">
                    <i class="fa fa-user-circle"></i> Booking Freelancer
                    <span class="mkt-tab__count">Mới</span>
                </button>
            </div>

            {{-- Tab 1: Source Code --}}
            <div class="mkt-panel active" data-panel="source">
                <div class="mkt-grid">
                    @if(isset($sourceGames['featured']) && count($sourceGames['featured']) > 0)
                        @foreach($sourceGames['featured'] as $index => $source)
                        <article class="src-card {{ ($source['is_featured'] ?? false) ? 'src-card--hot' : '' }}">
                            <a href="{{ $source['url'] ?? '#' }}" class="src-card__link">
                                {{-- Badges --}}
                                @if($source['is_featured'] ?? false)
                                    <span class="src-badge src-badge--hot">🔥 Nổi bật</span>
                                @endif
                                @if($source['is_free'] ?? false)
                                    <span class="src-badge src-badge--free">Miễn phí</span>
                                @elseif(($source['price'] ?? 0) < ($source['original_price'] ?? 0))
                                    <span class="src-badge src-badge--sale">-{{ round((1 - ($source['price'] ?? 0) / ($source['original_price'] ?? 1)) * 100) }}%</span>
                                @endif

                                <div class="src-card__img">
                                    <img src="{{ $source['thumbnail'] ?? '' }}" alt="{{ $source['title'] ?? '' }}" loading="lazy">
                                    <div class="src-card__overlay">
                                        <span class="src-card__engine">{{ $source['engine'] ?? 'Unity' }}</span>
                                        <div class="src-card__rating">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fa fa-star{{ $i <= floor($source['rating'] ?? 0) ? '' : ($i - 0.5 <= ($source['rating'] ?? 0) ? '-half-o' : '-o') }}"></i>
                                            @endfor
                                            <span>{{ number_format($source['rating'] ?? 0, 1) }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="src-card__body">
                                    <span class="src-card__cat">{{ $source['category'] ?? 'General' }}</span>
                                    <h3 class="src-card__title">{{ $source['title'] ?? 'No title' }}</h3>
                                    <p class="src-card__desc">{{ $source['short_description'] ?? '' }}</p>

                                    <div class="src-card__meta">
                                        <span><i class="fa fa-download"></i> {{ number_format($source['downloads'] ?? 0) }}</span>
                                        <span><i class="fa fa-code"></i> {{ $source['language'] ?? 'C#' }}</span>
                                        <span><i class="fa fa-clock-o"></i> {{ $source['updated_ago'] ?? '' }}</span>
                                    </div>

                                    <div class="src-card__tags">
                                        @foreach(array_slice($source['tags'] ?? [], 0, 3) as $tag)
                                            <span class="src-tag">{{ $tag }}</span>
                                        @endforeach
                                    </div>

                                    <div class="src-card__footer">
                                        <div class="src-card__price">
                                            @if($source['is_free'] ?? false)
                                                <span class="src-price src-price--free">Miễn phí</span>
                                            @else
                                                <span class="src-price">{{ number_format(($source['price'] ?? 0) / 1000, 0) }}k VND</span>
                                                @if(($source['price'] ?? 0) < ($source['original_price'] ?? 0))
                                                    <span class="src-price--old">{{ number_format(($source['original_price'] ?? 0) / 1000, 0) }}k</span>
                                                @endif
                                            @endif
                                        </div>
                                        <span class="src-card__btn">
                                            {{ ($source['is_free'] ?? false) ? 'Tải ngay' : 'Mua ngay' }} <i class="fa fa-arrow-right"></i>
                                        </span>
                                    </div>
                                </div>
                            </a>
                        </article>
                        @endforeach
                    @else
                        {{-- Fallback --}}
                        <article class="src-card src-card--hot">
                            <a href="{{ route('lamgame.source-game') }}" class="src-card__link">
                                <span class="src-badge src-badge--hot">🔥 Nổi bật</span>
                                <span class="src-badge src-badge--free">Miễn phí</span>
                                <div class="src-card__img">
                                    <img src="https://images.unsplash.com/photo-1551650975-87deedd944c3?w=400&h=250&fit=crop&q=80" alt="Roguelike Unity Kit" loading="lazy">
                                    <div class="src-card__overlay">
                                        <span class="src-card__engine">Unity</span>
                                        <div class="src-card__rating">
                                            <i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star-half-o"></i>
                                            <span>4.8</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="src-card__body">
                                    <span class="src-card__cat">2D Game Kit</span>
                                    <h3 class="src-card__title">Roguelike Unity Kit</h3>
                                    <p class="src-card__desc">Complete roguelike template with procedural generation</p>
                                    <div class="src-card__meta">
                                        <span><i class="fa fa-download"></i> 1,250</span>
                                        <span><i class="fa fa-code"></i> C#</span>
                                        <span><i class="fa fa-clock-o"></i> 1 ngày trước</span>
                                    </div>
                                    <div class="src-card__tags">
                                        <span class="src-tag">Unity</span><span class="src-tag">2D</span><span class="src-tag">Roguelike</span>
                                    </div>
                                    <div class="src-card__footer">
                                        <span class="src-price src-price--free">Miễn phí</span>
                                        <span class="src-card__btn">Tải ngay <i class="fa fa-arrow-right"></i></span>
                                    </div>
                                </div>
                            </a>
                        </article>
                    @endif
                </div>
                <div class="mkt-more">
                    <a href="{{ route('lamgame.source-game') }}" class="mkt-more__btn" target="_blank">
                        Xem tất cả {{ $sourceGames['total_sources'] ?? '25' }}+ source code <i class="fa fa-arrow-right"></i>
                    </a>
                </div>
            </div>

            {{-- Tab 2: Booking Freelancer --}}
            <div class="mkt-panel" data-panel="hire">
                <div class="svc-grid">
                    <article class="svc-card">
                        <div class="svc-card__icon">👨‍💻</div>
                        <h3 class="svc-card__title">Game Programmer</h3>
                        <p class="svc-card__desc">Freelancer Unity, Unreal, Godot — nhận việc trong 24h, bảo hành code</p>
                        <ul class="svc-card__features">
                            <li><i class="fa fa-check"></i> Unity / Unreal / Godot</li>
                            <li><i class="fa fa-check"></i> Mobile, PC, WebGL</li>
                            <li><i class="fa fa-check"></i> Bảo hành 3 tháng</li>
                        </ul>
                        <div class="svc-card__price">Từ <strong>500k VND</strong>/giờ</div>
                        <a href="{{ route('lamgame.lien-he') }}" class="svc-card__btn">Đặt lịch ngay <i class="fa fa-arrow-right"></i></a>
                    </article>
                    <article class="svc-card svc-card--popular">
                        <span class="svc-card__badge">Phổ biến nhất</span>
                        <div class="svc-card__icon">🎨</div>
                        <h3 class="svc-card__title">Game Artist & Designer</h3>
                        <p class="svc-card__desc">UI/UX, 2D/3D Art, Animation — từ concept đến asset hoàn chỉnh</p>
                        <ul class="svc-card__features">
                            <li><i class="fa fa-check"></i> UI/UX & Concept Art</li>
                            <li><i class="fa fa-check"></i> 2D/3D Art & Animation</li>
                            <li><i class="fa fa-check"></i> Sprite & Tilemap</li>
                        </ul>
                        <div class="svc-card__price">Từ <strong>400k VND</strong>/giờ</div>
                        <a href="{{ route('lamgame.lien-he') }}" class="svc-card__btn svc-card__btn--primary">Đặt lịch ngay <i class="fa fa-arrow-right"></i></a>
                    </article>
                    <article class="svc-card">
                        <div class="svc-card__icon">🚀</div>
                        <h3 class="svc-card__title">Full-stack Game Dev</h3>
                        <p class="svc-card__desc">Trọn gói từ ý tưởng đến publish — code, art, QA, deploy lên Store</p>
                        <ul class="svc-card__features">
                            <li><i class="fa fa-check"></i> End-to-end development</li>
                            <li><i class="fa fa-check"></i> Project management</li>
                            <li><i class="fa fa-check"></i> Publish lên Store</li>
                        </ul>
                        <div class="svc-card__price">Từ <strong>800k VND</strong>/giờ</div>
                        <a href="{{ route('lamgame.lien-he') }}" class="svc-card__btn">Đặt lịch ngay <i class="fa fa-arrow-right"></i></a>
                    </article>
                </div>
            </div>

        </div>
    </section>

    {{-- Marketplace tab switching --}}
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var tabs = document.querySelectorAll('.mkt-tab');
        var panels = document.querySelectorAll('.mkt-panel');
        tabs.forEach(function(tab) {
            tab.addEventListener('click', function() {
                var target = this.getAttribute('data-tab');
                tabs.forEach(function(t) { t.classList.remove('active'); t.setAttribute('aria-selected', 'false'); });
                panels.forEach(function(p) { p.classList.remove('active'); });
                this.classList.add('active');
                this.setAttribute('aria-selected', 'true');
                document.querySelector('[data-panel="' + target + '"]').classList.add('active');
            });
        });
    });
    </script>

    <!-- Featured Jobs Section -->
    <section id="viec-lam-noi-bat" class="jb-section">
        <div class="container">
            {{-- Section header --}}
            <div class="jb-header">
                <div class="jb-header__text">
                    <h2 class="jb-header__title">Việc làm Game Dev</h2>
                    <p class="jb-header__sub">Cơ hội mới nhất từ các studio game tại Việt Nam</p>
                </div>
                <div class="jb-header__stats">
                    <span class="jb-stat"><strong>{{ $jobs['total_count'] ?? 0 }}</strong> vị trí</span>
                    <span class="jb-stat jb-stat--accent"><strong>{{ $jobs['weekly_new'] ?? 0 }}</strong> mới tuần này</span>
                </div>
            </div>

            {{-- Job list --}}
            @if(isset($jobs['featured']) && count($jobs['featured']) > 0)
                <div class="jb-grid">
                    @foreach(array_slice($jobs['featured'], 0, 6) as $index => $job)
                    <a href="{{ $job['url'] }}" class="jb-card" aria-label="{{ $job['title'] }} tại {{ $job['company'] }}">
                        {{-- Badge --}}
                        @if($index === 0)
                            <span class="jb-card__badge jb-card__badge--hot">Hot</span>
                        @elseif($index === 1)
                            <span class="jb-card__badge jb-card__badge--new">Mới</span>
                        @endif

                        {{-- Company initial avatar --}}
                        <div class="jb-card__avatar" aria-hidden="true">
                            {{ mb_strtoupper(mb_substr($job['company'], 0, 1)) }}
                        </div>

                        {{-- Content --}}
                        <div class="jb-card__body">
                            <h3 class="jb-card__title">{{ $job['title'] }}</h3>
                            <p class="jb-card__company">{{ $job['company'] }}</p>

                            <div class="jb-card__meta">
                                <span class="jb-card__tag"><i class="fa fa-map-marker"></i> {{ $job['location'] }}</span>
                                <span class="jb-card__tag"><i class="fa fa-briefcase"></i> {{ $job['type'] }}</span>
                                <span class="jb-card__tag"><i class="fa fa-clock-o"></i> {{ $job['posted_ago'] }}</span>
                            </div>
                        </div>

                        {{-- Salary --}}
                        <div class="jb-card__salary">{{ $job['salary'] }}</div>
                    </a>
                    @endforeach
                </div>
            @else
                <div class="jb-empty">
                    <p>Chưa có tin tuyển dụng nào. <a href="{{ route('lamgame.viec-lam-game') }}">Đăng tin miễn phí →</a></p>
                </div>
            @endif

            {{-- CTA --}}
            <div class="jb-footer">
                <a href="{{ route('lamgame.viec-lam-game') }}" class="jb-footer__btn">
                    Xem tất cả việc làm <i class="fa fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </section>

@push('styles')
<style>
/* Source Code Marketplace Section - Mobile First */
/* ===== Marketplace Hub Section ===== */
.mkt-section {
    background: #0c1220;
    padding: 5rem 0;
    position: relative;
    overflow: hidden;
}
.mkt-section::before {
    content: '';
    position: absolute;
    bottom: -30%;
    left: -10%;
    width: 500px;
    height: 500px;
    background: radial-gradient(circle, rgba(16,185,129,0.06) 0%, transparent 70%);
    pointer-events: none;
}
.mkt-section .section-title { color: #fff; }
.mkt-section .section-subtitle { color: #94a3b8; }

/* Tabs */
.mkt-tabs {
    display: flex;
    gap: 0.5rem;
    margin-top: 2rem;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    scrollbar-width: none;
    padding-bottom: 0.25rem;
}
.mkt-tabs::-webkit-scrollbar { display: none; }
.mkt-tab {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.7rem 1.25rem;
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 10px;
    background: rgba(255,255,255,0.04);
    color: #94a3b8;
    font-size: 0.85rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    white-space: nowrap;
}
.mkt-tab:hover { background: rgba(255,255,255,0.08); color: #e2e8f0; }
.mkt-tab.active {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: #fff;
    border-color: transparent;
    box-shadow: 0 4px 20px rgba(102,126,234,0.3);
}
.mkt-tab__count {
    background: rgba(255,255,255,0.15);
    padding: 0.1rem 0.5rem;
    border-radius: 8px;
    font-size: 0.7rem;
}
.mkt-tab.active .mkt-tab__count { background: rgba(255,255,255,0.25); }

/* Panels */
.mkt-panel { display: none; margin-top: 1.5rem; }
.mkt-panel.active { display: block; animation: fadeUp 0.4s ease; }
@keyframes fadeUp {
    from { opacity: 0; transform: translateY(12px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Source code grid */
.mkt-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 1.25rem;
}

/* Source card */
.src-card {
    position: relative;
    border-radius: 14px;
    overflow: hidden;
    background: #1a2332;
    border: 1px solid rgba(255,255,255,0.06);
    transition: transform 0.35s cubic-bezier(.22,1,.36,1), box-shadow 0.35s;
}
.src-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 16px 40px rgba(0,0,0,0.3);
}
.src-card--hot { border-color: rgba(255,107,53,0.3); }
.src-card__link {
    display: flex;
    flex-direction: column;
    height: 100%;
    text-decoration: none;
    color: inherit;
}

/* Badges */
.src-badge {
    position: absolute;
    z-index: 3;
    padding: 0.25rem 0.7rem;
    border-radius: 8px;
    font-size: 0.68rem;
    font-weight: 700;
    color: #fff;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}
.src-badge--hot {
    top: 0.6rem;
    left: 0.6rem;
    background: linear-gradient(135deg, #ff6b35, #ff3860);
    animation: pulse-hot 2s ease-in-out infinite;
}
.src-badge--free {
    top: 0.6rem;
    right: 0.6rem;
    background: linear-gradient(135deg, #10b981, #059669);
}
.src-badge--sale {
    top: 0.6rem;
    right: 0.6rem;
    background: linear-gradient(135deg, #f59e0b, #d97706);
}

/* Card image */
.src-card__img {
    position: relative;
    height: 170px;
    overflow: hidden;
    background: linear-gradient(135deg, #1e293b, #334155);
}
.src-card__img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s cubic-bezier(.22,1,.36,1);
}
.src-card:hover .src-card__img img { transform: scale(1.08); }
.src-card__overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    padding: 0.6rem 0.75rem;
    background: linear-gradient(0deg, rgba(0,0,0,0.7), transparent);
    display: flex;
    justify-content: space-between;
    align-items: center;
    opacity: 0;
    transition: opacity 0.3s;
}
.src-card:hover .src-card__overlay { opacity: 1; }
.src-card__engine {
    background: rgba(0,0,0,0.6);
    color: #fff;
    padding: 0.15rem 0.5rem;
    border-radius: 6px;
    font-size: 0.65rem;
    font-weight: 700;
    text-transform: uppercase;
}
.src-card__rating {
    display: flex;
    align-items: center;
    gap: 0.15rem;
    color: #fbbf24;
    font-size: 0.7rem;
}
.src-card__rating span { color: #fff; font-weight: 600; margin-left: 0.2rem; }

/* Card body */
.src-card__body {
    padding: 1.1rem;
    display: flex;
    flex-direction: column;
    flex: 1;
}
.src-card__cat {
    color: #667eea;
    font-size: 0.68rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 0.4rem;
}
.src-card__title {
    margin: 0 0 0.4rem;
    font-size: 1rem;
    font-weight: 700;
    color: #e2e8f0;
    line-height: 1.35;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    transition: color 0.3s;
}
.src-card:hover .src-card__title { color: #818cf8; }
.src-card__desc {
    color: #64748b;
    font-size: 0.8rem;
    line-height: 1.5;
    margin: 0 0 0.75rem;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Meta */
.src-card__meta {
    display: flex;
    gap: 0.75rem;
    font-size: 0.7rem;
    color: #64748b;
    margin-bottom: 0.6rem;
    flex-wrap: wrap;
}
.src-card__meta span { display: inline-flex; align-items: center; gap: 0.25rem; }
.src-card__meta i { color: #667eea; font-size: 0.65rem; }

/* Tags */
.src-card__tags {
    display: flex;
    gap: 0.35rem;
    margin-bottom: 0.75rem;
    flex-wrap: wrap;
}
.src-tag {
    background: rgba(102,126,234,0.1);
    color: #818cf8;
    padding: 0.15rem 0.5rem;
    border-radius: 6px;
    font-size: 0.65rem;
    font-weight: 500;
    border: 1px solid rgba(102,126,234,0.15);
}

/* Footer */
.src-card__footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: auto;
    padding-top: 0.75rem;
    border-top: 1px solid rgba(255,255,255,0.06);
}
.src-card__price { display: flex; align-items: baseline; gap: 0.4rem; }
.src-price {
    font-size: 1rem;
    font-weight: 800;
    color: #e2e8f0;
}
.src-price--free {
    color: #10b981;
    font-size: 0.9rem;
}
.src-price--old {
    font-size: 0.75rem;
    color: #64748b;
    text-decoration: line-through;
}
.src-card__btn {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    color: #667eea;
    font-size: 0.78rem;
    font-weight: 600;
    transition: gap 0.3s, color 0.3s;
}
.src-card:hover .src-card__btn { gap: 0.6rem; color: #818cf8; }

/* "View all" button */
.mkt-more {
    text-align: center;
    margin-top: 2rem;
}
.mkt-more__btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.8rem 2rem;
    border: 1px solid rgba(102,126,234,0.4);
    border-radius: 10px;
    color: #818cf8;
    font-size: 0.85rem;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s;
}
.mkt-more__btn:hover {
    background: rgba(102,126,234,0.1);
    border-color: #667eea;
    gap: 0.8rem;
}

/* ===== Service cards (Hire Dev / Ads tabs) ===== */
.svc-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1.25rem;
}
.svc-card {
    position: relative;
    background: #1a2332;
    border: 1px solid rgba(255,255,255,0.06);
    border-radius: 14px;
    padding: 1.75rem 1.5rem;
    text-align: center;
    transition: transform 0.35s cubic-bezier(.22,1,.36,1), box-shadow 0.35s;
}
.svc-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 16px 40px rgba(0,0,0,0.3);
}
.svc-card--popular {
    border-color: rgba(102,126,234,0.4);
    background: linear-gradient(180deg, rgba(102,126,234,0.08) 0%, #1a2332 100%);
}
.svc-card__badge {
    position: absolute;
    top: -0.6rem;
    left: 50%;
    transform: translateX(-50%);
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: #fff;
    padding: 0.2rem 0.8rem;
    border-radius: 8px;
    font-size: 0.68rem;
    font-weight: 700;
    white-space: nowrap;
}
.svc-card__icon {
    font-size: 2.5rem;
    margin-bottom: 1rem;
}
.svc-card__title {
    color: #e2e8f0;
    font-size: 1.1rem;
    font-weight: 700;
    margin: 0 0 0.5rem;
}
.svc-card__desc {
    color: #64748b;
    font-size: 0.82rem;
    line-height: 1.5;
    margin: 0 0 1rem;
}
.svc-card__features {
    list-style: none;
    padding: 0;
    margin: 0 0 1.25rem;
    text-align: left;
}
.svc-card__features li {
    color: #94a3b8;
    font-size: 0.8rem;
    padding: 0.3rem 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.svc-card__features i { color: #10b981; font-size: 0.7rem; }
.svc-card__price {
    color: #94a3b8;
    font-size: 0.85rem;
    margin-bottom: 1.25rem;
}
.svc-card__price strong { color: #e2e8f0; font-size: 1.1rem; }
.svc-card__btn {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    padding: 0.65rem 1.5rem;
    border: 1px solid rgba(102,126,234,0.4);
    border-radius: 10px;
    color: #818cf8;
    font-size: 0.82rem;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s;
}
.svc-card__btn:hover {
    background: rgba(102,126,234,0.1);
    border-color: #667eea;
    gap: 0.7rem;
}
.svc-card__btn--primary {
    background: linear-gradient(135deg, #667eea, #764ba2);
    border-color: transparent;
    color: #fff;
}
.svc-card__btn--primary:hover {
    box-shadow: 0 8px 25px rgba(102,126,234,0.4);
    transform: translateY(-1px);
}

/* Responsive marketplace */
@media (max-width: 768px) {
    .mkt-grid { grid-template-columns: 1fr; }
    .svc-grid { grid-template-columns: 1fr; }
    .mkt-tabs { gap: 0.35rem; }
    .mkt-tab { padding: 0.6rem 1rem; font-size: 0.78rem; }
}
@media (max-width: 480px) {
    .mkt-section { padding: 3rem 0; }
    .src-card__img { height: 140px; }
}

/* ===== Job Board Section ===== */
.jb-section {
    padding: 4rem 0;
    background: #f8fafc;
}

.jb-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    margin-bottom: 2rem;
    flex-wrap: wrap;
    gap: 1rem;
}
.jb-header__title {
    font-size: 1.75rem;
    font-weight: 700;
    color: #1e293b;
    margin: 0;
}
.jb-header__sub {
    color: #64748b;
    font-size: 0.95rem;
    margin: 0.25rem 0 0;
}
.jb-header__stats {
    display: flex;
    gap: 1rem;
}
.jb-stat {
    font-size: 0.85rem;
    color: #64748b;
    background: #fff;
    border: 1px solid #e2e8f0;
    padding: 0.35rem 0.75rem;
    border-radius: 20px;
}
.jb-stat strong { color: #1e293b; }
.jb-stat--accent {
    background: #ecfdf5;
    border-color: #a7f3d0;
    color: #047857;
}
.jb-stat--accent strong { color: #047857; }

/* Job grid — list-style rows on desktop, cards on mobile */
.jb-grid {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

/* Job card — horizontal row */
.jb-card {
    display: grid;
    grid-template-columns: 48px 1fr auto;
    align-items: center;
    gap: 1rem;
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 1rem 1.25rem;
    text-decoration: none;
    color: inherit;
    position: relative;
    transition: border-color 0.2s, box-shadow 0.2s;
}
.jb-card:hover {
    border-color: #667eea;
    box-shadow: 0 4px 20px rgba(102, 126, 234, 0.1);
}

/* Company initial avatar */
.jb-card__avatar {
    width: 48px;
    height: 48px;
    border-radius: 10px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: #fff;
    font-size: 1.2rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

/* Badge */
.jb-card__badge {
    position: absolute;
    top: -0.4rem;
    right: 1rem;
    font-size: 0.65rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 0.15rem 0.5rem;
    border-radius: 4px;
    color: #fff;
}
.jb-card__badge--hot { background: #ef4444; }
.jb-card__badge--new { background: #3b82f6; }

/* Body */
.jb-card__body { min-width: 0; }
.jb-card__title {
    font-size: 1rem;
    font-weight: 600;
    color: #1e293b;
    margin: 0;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.jb-card__company {
    font-size: 0.85rem;
    color: #64748b;
    margin: 0.15rem 0 0.5rem;
}
.jb-card__meta {
    display: flex;
    gap: 0.75rem;
    flex-wrap: wrap;
}
.jb-card__tag {
    font-size: 0.78rem;
    color: #64748b;
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
}
.jb-card__tag i {
    font-size: 0.7rem;
    color: #94a3b8;
}

/* Salary */
.jb-card__salary {
    font-size: 0.95rem;
    font-weight: 700;
    color: #10b981;
    white-space: nowrap;
    text-align: right;
    flex-shrink: 0;
}

/* Empty state */
.jb-empty {
    text-align: center;
    padding: 3rem 1rem;
    color: #64748b;
    background: #fff;
    border: 2px dashed #e2e8f0;
    border-radius: 12px;
}
.jb-empty a { color: #667eea; font-weight: 600; }

/* Footer CTA */
.jb-footer {
    text-align: center;
    margin-top: 1.5rem;
}
.jb-footer__btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 2rem;
    background: #1e293b;
    color: #fff;
    border-radius: 8px;
    font-size: 0.9rem;
    font-weight: 600;
    text-decoration: none;
    transition: background 0.2s, transform 0.2s;
    min-height: 44px;
}
.jb-footer__btn:hover {
    background: #334155;
    transform: translateY(-1px);
    color: #fff;
    text-decoration: none;
}

/* Mobile: stack card vertically */
@media (max-width: 640px) {
    .jb-header { flex-direction: column; align-items: flex-start; }
    .jb-card {
        grid-template-columns: 40px 1fr;
        grid-template-rows: auto auto;
        gap: 0.75rem;
        padding: 1rem;
    }
    .jb-card__avatar { width: 40px; height: 40px; font-size: 1rem; }
    .jb-card__salary {
        grid-column: 1 / -1;
        text-align: left;
        padding-top: 0.5rem;
        border-top: 1px solid #f1f5f9;
    }
    .jb-footer__btn { width: 100%; justify-content: center; }
}

/* Mobile Performance & Touch Optimizations */
@media (max-width: 768px) {
    /* Improve section spacing on mobile */
    section {
        padding: 1rem 0;
    }

    /* Enhanced touch targets */
    .btn, .source-btn, .course-btn, .jb-footer__btn {
        min-height: 44px;
        min-width: 44px;
        padding: 0.75rem 1rem;
    }

    /* Optimize text readability on mobile */
    .section-title {
        font-size: 1.8rem;
        line-height: 1.2;
        margin-bottom: 0.75rem;
    }

    .section-subtitle {
        font-size: 1rem;
        line-height: 1.4;
        margin-bottom: 2rem;
    }

    /* Improve card readability on mobile */
    .source-card, .topic-card, .blog-card, .course-card {
        padding: 1rem;
    }

    .source-content, .topic-content, .blog-content, .course-content {
        padding: 1rem;
    }

    /* Optimize font sizes for mobile screens */
    .source-title, .topic-title, .blog-title, .course-title {
        font-size: 1rem;
        line-height: 1.3;
    }

    .source-description, .topic-excerpt, .blog-excerpt, .course-description {
        font-size: 0.9rem;
        line-height: 1.5;
    }

    /* Reduce motion for better mobile performance */
    * {
        transition-duration: 0.2s !important;
    }

    /* Optimize grid gaps on mobile */
    .marketplace-grid, .topics-grid, .blog-grid, .courses-grid {
        gap: 1.25rem;
    }
}

/* Reduce motion for users with motion sensitivity */
@media (prefers-reduced-motion: reduce) {
    *, *::before, *::after {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
        scroll-behavior: auto !important;
    }

    .source-card:hover, .topic-card:hover, .blog-card:hover, .course-card:hover {
        transform: none !important;
    }
}

/* High contrast mode support */
@media (prefers-contrast: high) {
    .source-card, .topic-card, .blog-card, .course-card {
        border: 2px solid currentColor;
    }

    .source-overlay, .topic-overlay, .blog-overlay, .course-overlay {
        background: rgba(0, 0, 0, 0.8);
    }
}

/* ===== Blog & News Section — Modern Gaming Style ===== */
.blog-news-section {
    background: #0f1923;
    padding: 5rem 0;
    position: relative;
    overflow: hidden;
}
.blog-news-section::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -20%;
    width: 600px;
    height: 600px;
    background: radial-gradient(circle, rgba(102,126,234,0.08) 0%, transparent 70%);
    pointer-events: none;
}
.blog-news-section .section-title {
    color: #fff;
}
.blog-news-section .section-subtitle {
    color: #94a3b8;
}

/* Inline subtitle link and meta for Blog & News section */
.section-subtitle .subtitle-link {
    font-size: 0.9rem;
    margin-left: 0.75rem;
    color: #667eea;
    text-decoration: underline;
    white-space: nowrap;
}
.section-subtitle .subtitle-link:hover { color: #818cf8; }
.section-subtitle .subtitle-meta {
    color: #6b7280;
    font-size: 0.9rem;
    margin-left: 0.25rem;
}

/* Bento grid: hero left + 2 side right */
.blog-bento {
    display: grid;
    grid-template-columns: 1.4fr 1fr;
    gap: 1.25rem;
    margin-top: 2.5rem;
}
.blog-bento__side {
    display: flex;
    flex-direction: column;
    gap: 1.25rem;
}

/* Base card */
.blog-card {
    position: relative;
    border-radius: 16px;
    overflow: hidden;
    background: #1a2332;
    transition: transform 0.35s cubic-bezier(.22,1,.36,1), box-shadow 0.35s ease;
}
.blog-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 20px 50px rgba(102,126,234,0.18);
}
.blog-card__link {
    display: flex;
    flex-direction: column;
    height: 100%;
    text-decoration: none;
    color: inherit;
}

/* Image container */
.blog-card__img {
    position: relative;
    overflow: hidden;
}
.blog-card__img img {
    width: 100%;
    display: block;
    object-fit: cover;
    transition: transform 0.5s cubic-bezier(.22,1,.36,1);
}
.blog-card:hover .blog-card__img img {
    transform: scale(1.08);
}
.blog-card__gradient {
    position: absolute;
    inset: 0;
    background: linear-gradient(0deg, rgba(15,25,35,0.85) 0%, rgba(15,25,35,0.2) 50%, transparent 100%);
    pointer-events: none;
}

/* Badge */
.blog-card__badge {
    position: absolute;
    top: 0.75rem;
    left: 0.75rem;
    background: var(--badge-color, #667eea);
    color: #fff;
    padding: 0.3rem 0.85rem;
    border-radius: 20px;
    font-size: 0.72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    z-index: 2;
}
.blog-card__badge--sm {
    font-size: 0.65rem;
    padding: 0.2rem 0.6rem;
}
.blog-card__hot {
    position: absolute;
    top: 0.75rem;
    right: 0.75rem;
    background: linear-gradient(135deg, #ff6b35, #ff3860);
    color: #fff;
    padding: 0.3rem 0.75rem;
    border-radius: 20px;
    font-size: 0.7rem;
    font-weight: 700;
    z-index: 2;
    animation: pulse-hot 2s ease-in-out infinite;
}
@keyframes pulse-hot {
    0%, 100% { box-shadow: 0 0 0 0 rgba(255,56,96,0.4); }
    50% { box-shadow: 0 0 0 8px rgba(255,56,96,0); }
}

/* Body */
.blog-card__body {
    padding: 1.25rem;
    display: flex;
    flex-direction: column;
    flex: 1;
}

/* Chips */
.blog-card__chips {
    display: flex;
    gap: 0.5rem;
    margin-bottom: 0.75rem;
    flex-wrap: wrap;
}
.chip {
    background: rgba(255,255,255,0.08);
    color: #94a3b8;
    padding: 0.2rem 0.6rem;
    border-radius: 6px;
    font-size: 0.72rem;
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
}
.chip--xs { font-size: 0.65rem; padding: 0.15rem 0.5rem; }
.chip i { font-size: 0.65rem; }

/* Title */
.blog-card__title {
    margin: 0 0 0.5rem;
    font-size: 1rem;
    font-weight: 700;
    line-height: 1.4;
    color: #e2e8f0;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    transition: color 0.3s;
}
.blog-card:hover .blog-card__title { color: #818cf8; }
.blog-card__title--lg { font-size: 1.35rem; -webkit-line-clamp: 3; }
.blog-card__title--sm { font-size: 0.9rem; }

/* Excerpt */
.blog-card__excerpt {
    color: #94a3b8;
    font-size: 0.85rem;
    line-height: 1.6;
    margin: 0 0 0.75rem;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Stats */
.blog-card__stats {
    display: flex;
    gap: 1rem;
    font-size: 0.72rem;
    color: #64748b;
    margin-top: auto;
    padding-top: 0.75rem;
    border-top: 1px solid rgba(255,255,255,0.06);
}
.blog-card__stats span {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
}
.blog-card__stats i { font-size: 0.7rem; }

/* CTA */
.blog-card__cta {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    color: #667eea;
    font-size: 0.8rem;
    font-weight: 600;
    margin-top: 0.75rem;
    transition: gap 0.3s, color 0.3s;
}
.blog-card:hover .blog-card__cta {
    gap: 0.7rem;
    color: #818cf8;
}

/* Hero card specifics */
.blog-card--hero .blog-card__img { height: 280px; }
.blog-card--hero .blog-card__img img { height: 100%; }

/* Side card specifics */
.blog-card--side { flex: 1; }
.blog-card--side .blog-card__img { height: 140px; }
.blog-card--side .blog-card__img img { height: 100%; }
.blog-card--side .blog-card__body { padding: 1rem; }
.blog-card--side .blog-card__title { font-size: 0.95rem; }

/* Bottom row compact cards */
.blog-bottom-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 1rem;
    margin-top: 1.25rem;
}
.blog-card--compact .blog-card__link {
    flex-direction: row;
    align-items: center;
}
.blog-card--compact .blog-card__img {
    width: 100px;
    min-height: 80px;
    flex-shrink: 0;
}
.blog-card--compact .blog-card__img img {
    height: 100%;
    border-radius: 16px 0 0 16px;
}
.blog-card--compact .blog-card__body {
    padding: 0.75rem;
}
.blog-card--compact .blog-card__title {
    -webkit-line-clamp: 2;
    margin-bottom: 0.35rem;
}

/* Responsive */
@media (max-width: 768px) {
    .blog-bento {
        grid-template-columns: 1fr;
    }
    .blog-bento__side {
        flex-direction: row;
    }
    .blog-bento__side .blog-card--side {
        flex: 1;
    }
    .blog-bottom-row {
        grid-template-columns: 1fr;
    }
    .blog-card--hero .blog-card__img { height: 200px; }
    .blog-card--side .blog-card__img { height: 120px; }
}
@media (max-width: 480px) {
    .blog-bento__side {
        flex-direction: column;
    }
    .blog-card--compact .blog-card__link {
        flex-direction: column;
    }
    .blog-card--compact .blog-card__img {
        width: 100%;
        min-height: 120px;
    }
    .blog-card--compact .blog-card__img img {
        border-radius: 16px 16px 0 0;
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

/* Global section spacing override for homepage */
section { padding-top: 1rem !important; padding-bottom: 1rem !important; }

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

    <!-- Banner Slider JavaScript -->
    <script src="{{ asset('themes/shop/emsaigon/assets/js/lamgame-optimized-banner.js') }}"></script>
    @endpush
@endsection
