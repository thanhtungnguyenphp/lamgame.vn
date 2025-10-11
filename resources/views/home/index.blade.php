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

    <!-- Featured Forum Topics -->
    <section id="loi-ich" class="benefits-section featured-topics-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">🔥 Chủ Đề Nổi Bật</h2>
                <p>Khám phá thêm {{ isset($hotForumTopics['total_posts']) ? $hotForumTopics['total_posts'] : '50+' }} chủ đề thú vị từ cộng đồng</p>
                <a href="{{ route('forum.index') }}" class="btn btn-outline" target="_blank">
                    Xem Tất Cả Forum
                </a>
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


                @endif
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
                    <span class="subtitle-meta">• Khám phá thêm {{ isset($latestBlogs['total_posts']) ? $latestBlogs['total_posts'] : '50+' }} bài viết chất lượng</span>
                    <a href="{{ route('lamgame.blog') }}" class="subtitle-link" target="_blank">Xem tất cả blog</a>
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
                @endif
            </div>

        </div>
    </section>

    <!-- Source Code Marketplace Section -->
    <section id="source-marketplace" class="source-marketplace-section">
        <div class="container">
            <div class="marketplace-cta">
                <div class="marketplace-action">
                    <h2 class="section-title">🛒 Source Code Marketplace</h2>
                    <p class="section-subtitle">
                        Khám phá và tải về những source code game chất lượng cao từ cộng đồng developer
                    </p>
                </div>
                <div class="marketplace-stats">
                    <p></p>
                    <div class="stat">
                        <span class="stat-number">{{ $sourceGames['total_sources'] ?? '25' }}+</span>
                        <span class="stat-label">Source codes</span>
                    </div>
                    <div class="stat">
                        <span class="stat-number">{{ $sourceGames['free_sources'] ?? '8' }}</span>
                        <span class="stat-label">Miễn phí</span>
                    </div>
                    <div class="stat">
                        <span class="stat-number">Unity</span>
                        <span class="stat-label">Engine chính</span>
                    </div>

                </div>

            </div>
            <div class="marketplace-grid">
                @if(isset($sourceGames['featured']) && count($sourceGames['featured']) > 0)
                    @foreach($sourceGames['featured'] as $index => $source)
                        <div class="source-card {{ $source['is_featured'] ?? false ? 'featured' : '' }}">
                            @if($source['is_featured'] ?? false)
                                <div class="source-badge featured-badge">Nổi bật 🔥</div>
                            @endif
                            @if($source['is_free'] ?? false)
                                <div class="source-badge free-badge">Miễn phí</div>
                            @elseif($source['price'] ?? 0 < $source['original_price'] ?? 0)
                                <div class="source-badge sale-badge">Sale</div>
                            @endif

                            <div class="source-image">
                                <img src="{{ $source['thumbnail'] ?? '' }}"
                                     srcset="{{ $source['thumbnail'] ?? '' }}&w=320 320w, {{ $source['thumbnail'] ?? '' }}&w=640 640w, {{ $source['thumbnail'] ?? '' }}&w=800 800w"
                                     sizes="(max-width: 480px) 320px, (max-width: 768px) 640px, 800px"
                                     alt="{{ $source['title'] ?? 'No title' }}"
                                     loading="lazy" />
                                <div class="source-overlay">
                                    <div class="source-engine-badge">{{ $source['engine'] ?? 'Unknown' }}</div>
                                    <div class="source-rating">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= floor($source['rating'] ?? 0))
                                                <i class="fa fa-star"></i>
                                            @elseif($i - 0.5 <= $source['rating'] ?? 0)
                                                <i class="fa fa-star-half-o"></i>
                                            @else
                                                <i class="fa fa-star-o"></i>
                                            @endif
                                        @endfor
                                        <span class="rating-number">{{ number_format($source['rating'] ?? 0, 1) }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="source-content">
                                <div class="source-category">{{ $source['category'] ?? 'General' }}</div>
                                <h3 class="source-title">
                                    <a href="{{ $source['url'] ?? '#' }}">{{ $source['title'] ?? 'No title' }}</a>
                                </h3>
                                <p class="source-description">{{ $source['short_description'] ?? 'No description available' }}</p>

                                <div class="source-meta">
                                    <div class="meta-item">
                                        <i class="fa fa-download"></i>
                                        <span>{{ number_format($source['downloads'] ?? 0) }}</span>
                                    </div>
                                    <div class="meta-item">
                                        <i class="fa fa-code"></i>
                                        <span>{{ $source['language'] ?? 'N/A' }}</span>
                                    </div>
                                    <div class="meta-item">
                                        <i class="fa fa-clock-o"></i>
                                        <span>{{ $source['updated_ago'] ?? 'Unknown' }}</span>
                                    </div>
                                </div>

                                <div class="source-tags">
                                    @foreach(array_slice($source['tags'] ?? [], 0, 3) as $tag)
                                        <span class="tag">{{ $tag }}</span>
                                    @endforeach
                                </div>

                                <div class="source-price-action">
                                    <div class="source-pricing">
                                        @if($source['is_free'] ?? false)
                                            <span class="price-current free-price">Miễn phí</span>
                                        @else
                                            <span class="price-current">{{ number_format($source['price'] ?? 0 / 1000, 0) }}k VND</span>
                                            @if($source['price'] ?? 0 < $source['original_price'] ?? 0)
                                                <span class="price-original">{{ number_format($source['original_price'] ?? 0 / 1000, 0) }}k</span>
                                            @endif
                                        @endif
                                    </div>
                                    <a href="{{ $source['url'] ?? '#' }}" class="source-btn">
                                        {{ $source['is_free'] ?? false ? 'Tải miễn phí' : 'Xem chi tiết' }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <!-- Fallback content if no source games -->
                    <div class="source-card featured">
                        <div class="source-badge featured-badge">Nổi bật 🔥</div>
                        <div class="source-image">
                            <img src="https://images.unsplash.com/photo-1551650975-87deedd944c3?w=400&h=250&fit=crop&q=80" alt="Unity Template" loading="lazy" />
                            <div class="source-overlay">
                                <div class="source-engine-badge">Unity</div>
                                <div class="source-rating">
                                    <i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star-half-o"></i>
                                    <span class="rating-number">4.8</span>
                                </div>
                            </div>
                        </div>
                        <div class="source-content">
                            <div class="source-category">2D Game Kit</div>
                            <h3 class="source-title">
                                <a href="{{ route('lamgame.source-game') }}">Roguelike Unity Kit</a>
                            </h3>
                            <p class="source-description">Complete roguelike template with procedural generation</p>
                            <div class="source-meta">
                                <div class="meta-item"><i class="fa fa-download"></i><span>1.2k</span></div>
                                <div class="meta-item"><i class="fa fa-code"></i><span>C#</span></div>
                                <div class="meta-item"><i class="fa fa-clock-o"></i><span>1 ngày trước</span></div>
                            </div>
                            <div class="source-tags">
                                <span class="tag">Unity</span><span class="tag">2D</span><span class="tag">Roguelike</span>
                            </div>
                            <div class="source-price-action">
                                <div class="source-pricing">
                                    <span class="price-current free-price">Miễn phí</span>
                                </div>
                                <a href="{{ route('lamgame.source-game') }}" class="source-btn">Tải miễn phí</a>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <!-- Featured Jobs Section - Enhanced -->
    <section id="viec-lam-noi-bat" class="courses-section">
        <div class="container">
            <div class="courses-cta jobs-cta">
                <div class="jobs-stats">
                    <div class="stat">
                        <div class="stat-number">{{ $jobs['total_count'] ?? '50+' }}</div>
                        <div class="stat-label">Jobs hiện có</div>
                    </div>
                    <div class="stat">
                        <div class="stat-number">{{ isset($jobs['featured']) ? count($jobs['featured']) : 0 }}</div>
                        <div class="stat-label">Nổi bật</div>
                    </div>
                    <div class="stat">
                        <div class="stat-number">95%</div>
                        <div class="stat-label">Hỗ trợ có việc</div>
                    </div>
                </div>
                <div class="jobs-action">
                    <h2 class="section-title">💼 Bảng Tin Tuyển Dụng</h2>
                    <p>
                        Cơ hội việc làm hot nhất từ các studio game hàng đầu Việt Nam
                    </p>
                    <p><a href="{{ route('lamgame.viec-lam-game') }}" class="btn btn-outline" target="_blank">Xem tất cả việc làm</a></p>
                </div>
            </div>

            <div class="courses-grid enhanced-jobs-grid">
                @if(isset($jobs['featured']) && count($jobs['featured']) > 0)
                    @php
                        $featuredJobs = array_slice($jobs['featured'], 0, 5); // Lấy 5 việc làm đầu tiên
                        $jobImages = [
                            'https://images.unsplash.com/photo-1560472354-b33ff0c44a43?w=400&h=250&fit=crop', // Game dev
                            'https://images.unsplash.com/photo-1556438064-2d7646166914?w=400&h=250&fit=crop', // Unity
                            'https://images.unsplash.com/photo-1542751371-adc38448a05e?w=400&h=250&fit=crop', // Programming
                            'https://images.unsplash.com/photo-1551650975-87deedd944c3?w=400&h=250&fit=crop', // Mobile Dev
                            'https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=400&h=250&fit=crop'  // VR/AR
                        ];
                        $jobLevels = ['Entry → Mid Level', 'Mid → Senior Level', 'Senior Level', 'Mid Level', 'All Levels'];
                        $badges = ['Hot 🔥', 'Urgent', 'High Salary', 'Remote OK', 'New'];
                    @endphp

                    @foreach($featuredJobs as $index => $job)
                        <div class="course-card job-card {{ $index === 0 ? 'featured' : '' }}">
                            @if($index < 2)
                                <div class="course-badge job-badge">{{ $badges[$index] ?? 'Hot 🔥' }}</div>
                            @endif
                            <div class="course-image">
                                <img src="{{ $jobImages[$index] ?? $jobImages[0] }}" alt="{{ $job['title'] }} at {{ $job['company'] }}" loading="lazy" />
                                <div class="course-overlay">
                                    <div class="course-level">{{ $jobLevels[$index] ?? 'All Levels' }}</div>
                                </div>
                            </div>
                            <div class="course-content">
                                <h3 class="course-title job-title">
                                    <a href="{{ $job['url'] }}">{{ $job['title'] }}</a>
                                </h3>
                                <p class="course-description job-description">
                                    Cơ hội việc làm tại {{ $job['company'] }} - một trong những studio game hàng đầu tại {{ $job['location'] }}.
                                </p>
                                <div class="course-features job-features">
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
                                <div class="course-price job-salary">
                                    <span class="current-price salary-range">{{ $job['salary'] }}</span>
                                </div>
                                <a href="{{ $job['url'] }}" class="course-btn job-apply-btn">Apply Ngay</a>
                            </div>
                        </div>
                    @endforeach
                @else
                    <!-- Enhanced fallback jobs with 4 positions -->
                    <div class="course-card job-card featured">
                        <div class="course-badge job-badge">Hot 🔥</div>
                        <div class="course-image">
                            <img src="https://images.unsplash.com/photo-1560472354-b33ff0c44a43?w=400&h=250&fit=crop" alt="Unity Developer at VNG" loading="lazy" />
                            <div class="course-overlay">
                                <div class="course-level">Mid → Senior Level</div>
                            </div>
                        </div>
                        <div class="course-content">
                            <h3 class="course-title job-title">
                                <a href="{{ route('lamgame.viec-lam-game') }}">Unity Developer</a>
                            </h3>
                            <p class="course-description job-description">
                                VNG Corporation tuyển Unity Developer cho dự án game mobile mới.
                            </p>
                            <div class="course-features job-features">
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
                            <div class="course-price job-salary">
                                <span class="current-price salary-range">25-40 triệu VND</span>
                            </div>
                            <a href="{{ route('lamgame.viec-lam-game') }}" class="course-btn job-apply-btn">Apply Ngay</a>
                        </div>
                    </div>

                    <div class="course-card job-card">
                        <div class="course-badge job-badge">Urgent</div>
                        <div class="course-image">
                            <img src="https://images.unsplash.com/photo-1556438064-2d7646166914?w=400&h=250&fit=crop" alt="3D Artist at Gameloft" loading="lazy" />
                            <div class="course-overlay">
                                <div class="course-level">Entry → Mid Level</div>
                            </div>
                        </div>
                        <div class="course-content">
                            <h3 class="course-title job-title">
                                <a href="{{ route('lamgame.viec-lam-game') }}">3D Artist</a>
                            </h3>
                            <p class="course-description job-description">
                                Gameloft Vietnam tuyển 3D Artist cho dự án game mobile AAA.
                            </p>
                            <div class="course-features job-features">
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
                            <div class="course-price job-salary">
                                <span class="current-price salary-range">20-30 triệu VND</span>
                            </div>
                            <a href="{{ route('lamgame.viec-lam-game') }}" class="course-btn job-apply-btn">Apply Ngay</a>
                        </div>
                    </div>

                    <div class="course-card job-card">
                        <div class="course-image">
                            <img src="https://images.unsplash.com/photo-1542751371-adc38448a05e?w=400&h=250&fit=crop" alt="Game Backend Developer" loading="lazy" />
                            <div class="course-overlay">
                                <div class="course-level">Senior Level</div>
                            </div>
                        </div>
                        <div class="course-content">
                            <h3 class="course-title job-title">
                                <a href="{{ route('lamgame.viec-lam-game') }}">Backend Developer</a>
                            </h3>
                            <p class="course-description job-description">
                                Appota tuyển Backend Developer cho hệ thống server game online.
                            </p>
                            <div class="course-features job-features">
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
                            <div class="course-price job-salary">
                                <span class="current-price salary-range">30-45 triệu VND</span>
                            </div>
                            <a href="{{ route('lamgame.viec-lam-game') }}" class="course-btn job-apply-btn">Apply Ngay</a>
                        </div>
                    </div>

                    <div class="course-card job-card">
                        <div class="course-image">
                            <img src="https://images.unsplash.com/photo-1551650975-87deedd944c3?w=400&h=250&fit=crop" alt="Mobile Game Developer" loading="lazy" />
                            <div class="course-overlay">
                                <div class="course-level">Mid Level</div>
                            </div>
                        </div>
                        <div class="course-content">
                            <h3 class="course-title job-title">
                                <a href="{{ route('lamgame.viec-lam-game') }}">Mobile Game Dev</a>
                            </h3>
                            <p class="course-description job-description">
                                Studio indie tuyển Mobile Game Developer cho dự án puzzle game.
                            </p>
                            <div class="course-features job-features">
                                <div class="feature">
                                    <i class="fa fa-building"></i>
                                    <span>IndieStudio VN</span>
                                </div>
                                <div class="feature">
                                    <i class="fa fa-map-marker"></i>
                                    <span>Remote</span>
                                </div>
                                <div class="feature">
                                    <i class="fa fa-clock-o"></i>
                                    <span>3 ngày trước</span>
                                </div>
                            </div>
                            <div class="course-price job-salary">
                                <span class="current-price salary-range">15-25 triệu VND</span>
                            </div>
                            <a href="{{ route('lamgame.viec-lam-game') }}" class="course-btn job-apply-btn">Apply Ngay</a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>

@push('styles')
<style>
/* Source Code Marketplace Section - Mobile First */
.source-marketplace-section {
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    padding: 3rem 0;
    position: relative;
}

.source-marketplace-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse"><path d="M 10 0 L 0 0 0 10" fill="none" stroke="%23e2e8f0" stroke-width="0.5" opacity="0.3"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>') repeat;
    opacity: 0.6;
    z-index: 1;
}

.source-marketplace-section .container {
    position: relative;
    z-index: 2;
}

/* Mobile-first marketplace grid */
.marketplace-grid {
    display: grid;
    grid-template-columns: 1fr; /* Single column on mobile */
    gap: 1.5rem;
    margin: 2rem 0;
}

/* Tablet breakpoint */
@media (min-width: 481px) {
    .marketplace-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 2rem;
    }
}

/* Desktop breakpoint */
@media (min-width: 769px) {
    .marketplace-grid {
        grid-template-columns: repeat(3, 1fr);
        gap: 2rem;
    }

    .source-marketplace-section {
        padding: 4rem 0;
    }
}

/* Large desktop */
@media (min-width: 1200px) {
    .marketplace-grid {
        grid-template-columns: repeat(3, 1fr);
        max-width: 1200px;
        margin: 3rem auto;
    }
}

/* Source card styling */
.source-card {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    position: relative;
    border: 2px solid transparent;
}

.source-card.featured {
    border-color: #ffd700;
    box-shadow: 0 8px 30px rgba(255, 215, 0, 0.2);
}

.source-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 12px 35px rgba(0, 0, 0, 0.15);
}

/* Source badges */
.source-badge {
    position: absolute;
    top: 0.75rem;
    z-index: 3;
    padding: 0.25rem 0.75rem;
    border-radius: 12px;
    font-size: 0.7rem;
    font-weight: 600;
    color: white;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.featured-badge {
    background: linear-gradient(45deg, #ff6b35, #ff4757);
    left: 0.75rem;
}

.free-badge {
    background: linear-gradient(45deg, #10b981, #059669);
    right: 0.75rem;
}

.sale-badge {
    background: linear-gradient(45deg, #f59e0b, #d97706);
    right: 0.75rem;
}

/* Source image */
.source-image {
    position: relative;
    height: 160px;
    overflow: hidden;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

@media (min-width: 769px) {
    .source-image {
        height: 180px;
    }
}

.source-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.source-card:hover .source-image img {
    transform: scale(1.05);
}

.source-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(to bottom, rgba(0, 0, 0, 0.1) 0%, rgba(0, 0, 0, 0.6) 100%);
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    padding: 1rem;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.source-card:hover .source-overlay {
    opacity: 1;
}

.source-engine-badge {
    background: rgba(0, 0, 0, 0.8);
    color: white;
    padding: 0.25rem 0.75rem;
    border-radius: 12px;
    font-size: 0.7rem;
    font-weight: 600;
    text-transform: uppercase;
}

.source-rating {
    display: flex;
    align-items: center;
    gap: 0.25rem;
    color: #ffd700;
    font-size: 0.8rem;
}

.rating-number {
    color: white;
    font-weight: 600;
    font-size: 0.75rem;
    margin-left: 0.25rem;
}

/* Source content */
.source-content {
    padding: 1.25rem;
}

.source-category {
    color: #667eea;
    font-size: 0.7rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 0.5rem;
}

.source-title {
    margin: 0 0 0.75rem 0;
    font-size: 1rem;
    font-weight: 600;
    line-height: 1.3;
}

@media (min-width: 769px) {
    .source-title {
        font-size: 1.1rem;
    }
}

.source-title a {
    color: #2c3e50;
    text-decoration: none;
    transition: color 0.3s ease;
}

.source-title a:hover {
    color: #667eea;
}

.source-description {
    color: #666;
    font-size: 0.85rem;
    line-height: 1.5;
    margin-bottom: 1rem;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Source meta information */
.source-meta {
    display: flex;
    gap: 0.75rem;
    margin-bottom: 0.75rem;
    font-size: 0.75rem;
    color: #666;
    flex-wrap: wrap;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

.meta-item i {
    width: 12px;
    text-align: center;
    color: #667eea;
}

/* Source tags */
.source-tags {
    display: flex;
    gap: 0.5rem;
    margin-bottom: 1rem;
    flex-wrap: wrap;
}

.tag {
    background: #f1f5f9;
    color: #475569;
    padding: 0.2rem 0.6rem;
    border-radius: 12px;
    font-size: 0.7rem;
    font-weight: 500;
    border: 1px solid #e2e8f0;
}

/* Price and action area */
.source-price-action {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 1rem;
}

@media (max-width: 480px) {
    .source-price-action {
        flex-direction: column;
        gap: 0.75rem;
        align-items: stretch;
    }
}

.source-pricing {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.price-current {
    font-size: 1rem;
    font-weight: 700;
    color: #2c3e50;
}

.price-current.free-price {
    color: #10b981;
    font-size: 0.9rem;
    font-weight: 600;
}

.price-original {
    font-size: 0.8rem;
    color: #94a3b8;
    text-decoration: line-through;
}

/* Source button - mobile-first sizing */
.source-btn {
    background: linear-gradient(45deg, #667eea, #764ba2);
    color: white;
    text-decoration: none;
    padding: 0.6rem 1rem;
    border-radius: 8px;
    font-size: 0.8rem;
    font-weight: 600;
    transition: all 0.3s ease;
    text-align: center;
    white-space: nowrap;
    min-height: 44px; /* Touch-friendly on mobile */
    display: flex;
    align-items: center;
    justify-content: center;
    min-width: 120px;
}

.source-btn:hover {
    background: linear-gradient(45deg, #5a67d8, #6b46c1);
    transform: translateY(-1px);
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
    color: white;
    text-decoration: none;
}

@media (max-width: 480px) {
    .source-btn {
        width: 100%;
        padding: 0.75rem 1rem;
    }
}

/* Marketplace CTA section */
.marketplace-cta {
    margin-top: 3rem;
    text-align: center;
    background: white;
    padding: 2rem;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
}

@media (min-width: 769px) {
    .marketplace-cta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        text-align: left;
        padding: 2.5rem;
    }
}

.marketplace-stats {
    display: flex;
    justify-content: center;
    gap: 2rem;
    margin-bottom: 1.5rem;
}

@media (max-width: 480px) {
    .marketplace-stats {
        gap: 1rem;
        margin-bottom: 2rem;
    }
}

@media (min-width: 769px) {
    .marketplace-stats {
        margin-bottom: 0;
    }
}

.stat {
    display: flex;
    flex-direction: column;
    align-items: center;
}

.stat-number {
    font-size: 1.5rem;
    font-weight: 700;
    color: #2c3e50;
    line-height: 1;
}

@media (max-width: 480px) {
    .stat-number {
        font-size: 1.2rem;
    }
}

.stat-label {
    font-size: 0.8rem;
    color: #666;
    margin-top: 0.25rem;
}

.marketplace-action p {
    color: #666;
    margin-bottom: 1.5rem;
    font-size: 1rem;
}

@media (max-width: 480px) {
    .marketplace-action p {
        font-size: 0.9rem;
    }
}

.marketplace-action .btn-outline {
    background: transparent;
    color: #667eea;
    border: 2px solid #667eea;
    padding: 1rem 2rem;
    font-weight: 600;
    text-decoration: none;
    border-radius: 30px;
    transition: all 0.3s ease;
    display: inline-block;
    min-height: 44px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.marketplace-action .btn-outline:hover {
    background: #667eea;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
}

@media (max-width: 480px) {
    .marketplace-action .btn-outline {
        width: 100%;
        padding: 0.75rem 1rem;
    }
}

/* Enhanced Jobs Section - Mobile First */
.enhanced-jobs-grid {
    display: grid;
    grid-template-columns: 1fr; /* Single column on mobile */
    gap: 1.5rem;
    margin: 2rem 0;
}

@media (min-width: 481px) {
    .enhanced-jobs-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 2rem;
    }
}

@media (min-width: 769px) {
    .enhanced-jobs-grid {
        grid-template-columns: repeat(3, 1fr);
        gap: 2rem;
    }
}

@media (min-width: 1200px) {
    .enhanced-jobs-grid {
        grid-template-columns: repeat(4, 1fr); /* 4 columns for wider screens */
        gap: 2rem;
    }
}

/* Job card specific styling */
.job-card {
    position: relative;
}

.job-badge {
    position: absolute;
    top: 0.75rem;
    left: 0.75rem;
    z-index: 3;
    padding: 0.25rem 0.75rem;
    border-radius: 12px;
    font-size: 0.7rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    background: linear-gradient(45deg, #ff6b35, #ff4757);
    color: white;
}

.job-title a {
    color: #2c3e50;
    text-decoration: none;
    font-weight: 600;
    transition: color 0.3s ease;
}

.job-title a:hover {
    color: #667eea;
}

.job-description {
    color: #666;
    font-size: 0.85rem;
    line-height: 1.5;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.job-features {
    margin: 1rem 0;
}

.job-features .feature {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 0.5rem;
    font-size: 0.8rem;
    color: #666;
}

.job-features .feature i {
    color: #667eea;
    width: 14px;
    text-align: center;
}

.job-salary {
    margin: 1rem 0;
}

.salary-range {
    font-size: 1rem;
    font-weight: 700;
    color: #10b981;
}

/* Job apply button - mobile-first */
.job-apply-btn {
    background: linear-gradient(45deg, #10b981, #059669);
    color: white;
    text-decoration: none;
    padding: 0.7rem 1.2rem;
    border-radius: 8px;
    font-size: 0.85rem;
    font-weight: 600;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-height: 44px;
    min-width: 120px;
    text-align: center;
}

.job-apply-btn:hover {
    background: linear-gradient(45deg, #059669, #047857);
    transform: translateY(-1px);
    box-shadow: 0 4px 15px rgba(16, 185, 129, 0.4);
    color: white;
    text-decoration: none;
}

@media (max-width: 480px) {
    .job-apply-btn {
        width: 100%;
        padding: 0.75rem 1rem;
    }
}

/* Jobs CTA section */
.jobs-cta {
    margin-top: 3rem;
    text-align: center;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 2rem;
    border-radius: 16px;
    position: relative;
    overflow: hidden;
}

.jobs-cta::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.1);
    z-index: 1;
}

.jobs-cta > * {
    position: relative;
    z-index: 2;
}

@media (min-width: 769px) {
    .jobs-cta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        text-align: left;
        padding: 2.5rem;
    }
}

.jobs-stats {
    display: flex;
    justify-content: center;
    gap: 2rem;
    margin-bottom: 1.5rem;
}

@media (max-width: 480px) {
    .jobs-stats {
        gap: 1rem;
        margin-bottom: 2rem;
    }
}

@media (min-width: 769px) {
    .jobs-stats {
        margin-bottom: 0;
    }
}

.jobs-stats .stat {
    display: flex;
    flex-direction: column;
    align-items: center;
}

.jobs-stats .stat-number {
    font-size: 1.5rem;
    font-weight: 700;
    color: white;
    line-height: 1;
}

@media (max-width: 480px) {
    .jobs-stats .stat-number {
        font-size: 1.2rem;
    }
}

.jobs-stats .stat-label {
    font-size: 0.8rem;
    color: rgba(255, 255, 255, 0.8);
    margin-top: 0.25rem;
}

.jobs-action p {
    color: rgba(255, 255, 255, 0.9);
    margin-bottom: 1.5rem;
    font-size: 1rem;
}

/* Inline small link inside jobs description */
.jobs-action .subtitle-link {
    font-size: 0.95rem;
    margin-left: 0.75rem;
    color: #fff;
    text-decoration: underline;
    white-space: nowrap;
}
@media (prefers-contrast: more) {
    .jobs-action .subtitle-link { text-decoration-thickness: 2px; }
}

/* Make jobs section title white */
#viec-lam-noi-bat .section-title { color: #fff; }

@media (max-width: 480px) {
    .jobs-action p {
        font-size: 0.9rem;
    }
}

.jobs-action .btn-outline {
    background: transparent;
    color: white;
    border: 2px solid white;
    padding: 1rem 2rem;
    font-weight: 600;
    text-decoration: none;
    border-radius: 30px;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-height: 44px;
    cursor: pointer;
}

.jobs-action .btn-outline:hover {
    background: white;
    color: #667eea;
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(255, 255, 255, 0.3);
}

@media (max-width: 480px) {
    .jobs-action .btn-outline {
        width: 100%;
        padding: 0.75rem 1rem;
    }
}

/* Mobile Performance & Touch Optimizations */
@media (max-width: 768px) {
    /* Improve section spacing on mobile */
    section {
        padding: 1rem 0;
    }

    /* Enhanced touch targets */
    .btn, .source-btn, .job-apply-btn, .course-btn {
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

/* Inline subtitle link and meta for Blog & News section */
.section-subtitle .subtitle-link {
    font-size: 0.9rem;
    margin-left: 0.75rem;
    color: #667eea;
    text-decoration: underline;
    white-space: nowrap;
}
.section-subtitle .subtitle-link:hover {
    color: #5a67d8;
}
.section-subtitle .subtitle-meta {
    color: #6b7280;
    font-size: 0.9rem;
    margin-left: 0.25rem;
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
