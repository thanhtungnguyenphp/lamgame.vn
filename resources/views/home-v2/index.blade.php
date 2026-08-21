{{-- LAMGAME.VN HOMEPAGE V2 — Dark Theme Marketplace --}}
@extends('layouts.master-v2')

@section('page_title', 'LamGame.vn — Source Game chất lượng cho Unity & Unreal Developer')
@section('page_description', 'Marketplace source game hàng đầu Việt Nam. Tiết kiệm hàng trăm giờ phát triển với source code, hệ thống gameplay và template production-ready.')

@section('content')

{{-- HERO SECTION --}}
<section class="lg-v2-hero">
    <div class="lg-v2-container">
        <div class="lg-v2-hero__grid">
            <div class="lg-v2-hero__content">
                <span class="lg-v2-hero__badge">MARKETPLACE SOURCE GAME HÀNG ĐẦU VIỆT NAM</span>
                <h1>Source Game chất lượng <br>cho <span class="lg-v2-hero__accent">Unity & Unreal</span> Developer</h1>
                <p class="lg-v2-hero__sub">Tiết kiệm hàng trăm giờ phát triển với source code, hệ thống gameplay và template production-ready.</p>
                <div class="lg-v2-hero__cta">
                    <a href="{{ route('lamgame.source-game') }}" class="lg-v2-btn lg-v2-btn--primary">✨ Khám phá Source Hot</a>
                    <a href="{{ route('lamgame.source-game') }}?sort=featured" class="lg-v2-btn lg-v2-btn--secondary">⭐ Source Nổi Bật</a>
                </div>
            </div>
            <div class="lg-v2-hero__media">
                {{-- Video placeholder --}}
                <div class="lg-v2-hero__video">
                    <img src="https://img.youtube.com/vi/OHbte7hdxYU/maxresdefault.jpg" alt="LamGame Marketplace" loading="eager">
                    <button class="lg-v2-hero__play-btn">
                        <svg width="48" height="48" fill="white" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                    </button>
                </div>
                {{-- USP Cards --}}
                <div class="lg-v2-hero__usps">
                    <div class="lg-v2-hero__usp">✅ Production Ready</div>
                    <div class="lg-v2-hero__usp">🔄 Cập nhật thường xuyên</div>
                    <div class="lg-v2-hero__usp">💬 Hỗ trợ tận tâm</div>
                    <div class="lg-v2-hero__usp">💰 Hoàn tiền 7 ngày</div>
                </div>
            </div>
        </div>

        {{-- Stats --}}
        <div class="lg-v2-hero__stats">
            <div class="lg-v2-hero__stat">
                <span class="lg-v2-hero__stat-icon">🎮</span>
                <span class="lg-v2-hero__stat-number">1,200+</span>
                <span class="lg-v2-hero__stat-label">Source Game</span>
            </div>
            <div class="lg-v2-hero__stat">
                <span class="lg-v2-hero__stat-icon">👥</span>
                <span class="lg-v2-hero__stat-number">{{ number_format($siteMetrics['registered_users'] ?? 0) }}+</span>
                <span class="lg-v2-hero__stat-label">Developers</span>
            </div>
            <div class="lg-v2-hero__stat">
                <span class="lg-v2-hero__stat-icon">💼</span>
                <span class="lg-v2-hero__stat-number">{{ $siteMetrics['job_listings'] ?? 0 }}+</span>
                <span class="lg-v2-hero__stat-label">Việc làm</span>
            </div>
            <div class="lg-v2-hero__stat">
                <span class="lg-v2-hero__stat-icon">📝</span>
                <span class="lg-v2-hero__stat-number">{{ $siteMetrics['forum_posts'] ?? 0 }}+</span>
                <span class="lg-v2-hero__stat-label">Bài viết Forum</span>
            </div>
        </div>
    </div>
</section>

{{-- CATEGORIES --}}
<section class="lg-v2-section">
    <div class="lg-v2-container">
        <div class="lg-v2-section__header">
            <h2 class="lg-v2-section__title">Danh mục phổ biến</h2>
            <a href="{{ route('lamgame.source-game') }}" class="lg-v2-section__link">Xem tất cả danh mục →</a>
        </div>
        <div class="lg-v2-hscroll">
            @foreach($categories ?? [] as $cat)
            <a href="{{ route('lamgame.source-game') }}?genre={{ $cat['slug'] }}" class="lg-v2-category-card {{ ($cat['active'] ?? false) ? 'lg-v2-category-card--active' : '' }}">
                <span class="lg-v2-category-card__icon">{{ $cat['icon'] }}</span>
                <span class="lg-v2-category-card__name">{{ $cat['name'] }}</span>
                <span class="lg-v2-category-card__count">{{ $cat['count'] }}+</span>
            </a>
            @endforeach
        </div>
    </div>
</section>

{{-- CURATED: Trending / Best Selling / Staff Picks --}}
<section class="lg-v2-section" style="padding-top: 0;">
    <div class="lg-v2-container">
        <div class="lg-v2-curated">
            {{-- Trending --}}
            <div class="lg-v2-curated__col">
                <div class="lg-v2-curated__header">
                    <h3>🔥 Trending Now</h3>
                    <a href="{{ route('lamgame.source-game') }}?sort=trending" class="lg-v2-section__link">Xem tất cả →</a>
                </div>
                <p class="lg-v2-curated__desc">Những source đang được quan tâm</p>
                <div class="lg-v2-curated__thumbs">
                    @forelse(($trending ?? []) as $item)
                    <a href="{{ $item['url'] }}" class="lg-v2-curated__thumb" title="{{ $item['name'] }}">
                        <img src="{{ $item['thumbnail'] }}" alt="{{ $item['name'] }}" loading="lazy">
                    </a>
                    @empty
                    @for($i = 0; $i < 4; $i++)
                    <div class="lg-v2-skeleton" style="width:80px;height:80px;"></div>
                    @endfor
                    @endforelse
                </div>
            </div>

            {{-- Featured Sources (was Best Selling) --}}
            <div class="lg-v2-curated__col">
                <div class="lg-v2-curated__header">
                    <h3>⭐ Source Nổi Bật</h3>
                    <a href="{{ route('lamgame.source-game') }}?sort=featured" class="lg-v2-section__link">Xem tất cả →</a>
                </div>
                <p class="lg-v2-curated__desc">Được chọn lọc bởi LamGame</p>
                <div class="lg-v2-curated__thumbs">
                    @forelse(($best_selling ?? []) as $item)
                    <a href="{{ $item['url'] }}" class="lg-v2-curated__thumb" title="{{ $item['name'] }}">
                        <img src="{{ $item['thumbnail'] }}" alt="{{ $item['name'] }}" loading="lazy">
                    </a>
                    @empty
                    @for($i = 0; $i < 4; $i++)
                    <div class="lg-v2-skeleton" style="width:80px;height:80px;"></div>
                    @endfor
                    @endforelse
                </div>
            </div>

            {{-- Staff Picks --}}
            <div class="lg-v2-curated__col">
                <div class="lg-v2-curated__header">
                    <h3>👑 Staff Picks</h3>
                    <a href="{{ route('lamgame.source-game') }}?sort=staff_picks" class="lg-v2-section__link">Xem tất cả →</a>
                </div>
                <p class="lg-v2-curated__desc">Lựa chọn từ đội ngũ LamGame</p>
                <div class="lg-v2-curated__thumbs">
                    @forelse(($staff_picks ?? []) as $item)
                    <a href="{{ $item['url'] }}" class="lg-v2-curated__thumb" title="{{ $item['name'] }}">
                        <img src="{{ $item['thumbnail'] }}" alt="{{ $item['name'] }}" loading="lazy">
                    </a>
                    @empty
                    @for($i = 0; $i < 4; $i++)
                    <div class="lg-v2-skeleton" style="width:80px;height:80px;"></div>
                    @endfor
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</section>

{{-- PRODUCT GRID --}}
<section class="lg-v2-section">
    <div class="lg-v2-container">
        {{-- Filter Bar --}}
        <div class="lg-v2-filters">
            <button class="lg-v2-filters__mobile-toggle" id="filter-mobile-toggle">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 4h18M3 12h18M3 20h18"/></svg>
                Bộ lọc
            </button>
            <div class="lg-v2-filter-overlay" id="filter-overlay"></div>
            <div class="lg-v2-filters__left" id="filter-panel">
                <select class="lg-v2-filter-select" id="filter-engine"><option value="">Engine</option><option>Unity</option><option>Unreal</option><option>Godot</option><option>Phaser</option></select>
                <select class="lg-v2-filter-select" id="filter-genre"><option value="">Genre</option>@foreach($categories ?? [] as $cat)@if(!($cat['active'] ?? false))<option value="{{ $cat['slug'] }}">{{ $cat['name'] }}</option>@endif @endforeach</select>
                <select class="lg-v2-filter-select" id="filter-platform"><option value="">Platform</option><option>PC</option><option>Mobile</option><option>Web</option><option>Console</option></select>
                <select class="lg-v2-filter-select" id="filter-price"><option value="">Giá</option><option value="0-0">Free</option><option value="1-20">$1 - $20</option><option value="20-50">$20 - $50</option><option value="50-100">$50 - $100</option></select>
                <select class="lg-v2-filter-select" id="filter-difficulty"><option value="">Độ khó</option><option value="beginner">Beginner</option><option value="intermediate">Intermediate</option><option value="advanced">Advanced</option></select>
            </div>
            <div class="lg-v2-filters__right">
                <span style="color:var(--lg-text-muted);font-size:0.8125rem;">Sắp xếp:</span>
                <select class="lg-v2-filter-select" id="filter-sort"><option value="trending">Trending</option><option value="newest">Mới nhất</option><option value="featured">Nổi bật</option><option value="price_low">Giá thấp→cao</option><option value="price_high">Giá cao→thấp</option><option value="rating">Rating</option></select>
            </div>
        </div>

        {{-- Product Grid --}}
        <div class="lg-v2-grid lg-v2-grid--4" id="product-grid" style="margin-top:1.5rem;">
            @forelse(($products['items'] ?? []) as $product)
            <a href="{{ $product['url'] }}" class="lg-v2-card lg-v2-product-card">
                <div class="lg-v2-product-card__img">
                    <img src="{{ $product['thumbnail'] }}" alt="{{ $product['name'] }}" loading="lazy">
                    @if($product['badge'])
                    <span class="lg-v2-badge lg-v2-badge--{{ $product['badge'] }}">{{ strtoupper($product['badge'] === 'bestseller' ? 'BEST SELLER' : $product['badge']) }}</span>
                    @endif
                    <button class="lg-v2-product-card__wishlist" title="Thêm vào wishlist">♡</button>
                </div>
                <div class="lg-v2-product-card__body">
                    <h3 class="lg-v2-product-card__title">{{ Str::limit($product['name'], 35) }}</h3>
                    <p class="lg-v2-product-card__desc">{{ Str::limit($product['description'] ?? '', 50) }}</p>
                    <div class="lg-v2-product-card__tags">
                        @foreach(array_slice($product['genre_tags'], 0, 3) as $tag)
                        <span class="lg-v2-tag">{{ $tag }}</span>
                        @endforeach
                    </div>
                    <div class="lg-v2-product-card__meta">
                        <span class="lg-v2-tag">{{ $product['engine'] }}</span>
                        @foreach(array_slice($product['platform'], 0, 3) as $plat)
                        <span class="lg-v2-tag">{{ $plat }}</span>
                        @endforeach
                    </div>
                    <div class="lg-v2-product-card__footer">
                        <div class="lg-v2-product-card__rating">
                            <span>⭐ {{ $product['rating'] }} ({{ $product['review_count'] }})</span>
                            <span>• {{ number_format($product['sales_count']) }} sales</span>
                        </div>
                        <div class="lg-v2-product-card__price-row">
                            @if($product['is_free'])
                            <span class="lg-v2-product-card__price lg-v2-product-card__price--free">Free</span>
                            @else
                            <span class="lg-v2-product-card__price">${{ $product['price'] }}</span>
                            @endif
                            <span class="lg-v2-btn lg-v2-btn--outline lg-v2-btn--sm">Xem chi tiết</span>
                        </div>
                    </div>
                </div>
            </a>
            @empty
            {{-- Skeleton fallback --}}
            @for($i = 0; $i < 8; $i++)
            <div class="lg-v2-card" style="padding:0;overflow:hidden;">
                <div class="lg-v2-skeleton" style="height:180px;border-radius:0;"></div>
                <div style="padding:1rem;">
                    <div class="lg-v2-skeleton" style="height:16px;width:70%;margin-bottom:0.5rem;"></div>
                    <div class="lg-v2-skeleton" style="height:12px;width:90%;margin-bottom:0.75rem;"></div>
                    <div style="display:flex;gap:0.25rem;margin-bottom:0.75rem;">
                        <div class="lg-v2-skeleton" style="height:20px;width:40px;"></div>
                        <div class="lg-v2-skeleton" style="height:20px;width:50px;"></div>
                    </div>
                    <div style="display:flex;justify-content:space-between;align-items:center;">
                        <div class="lg-v2-skeleton" style="height:24px;width:50px;"></div>
                        <div class="lg-v2-skeleton" style="height:32px;width:90px;border-radius:8px;"></div>
                    </div>
                </div>
            </div>
            @endfor
            @endforelse
        </div>

        @if(($products['has_more'] ?? false))
        <div style="text-align:center;margin-top:2rem;">
            <button class="lg-v2-btn lg-v2-btn--secondary" id="load-more-btn">Xem thêm sản phẩm</button>
        </div>
        @endif
    </div>
</section>

{{-- TRUST BAR --}}
<section class="lg-v2-trust">
    <div class="lg-v2-container">
        <div class="lg-v2-trust__grid">
            <div class="lg-v2-trust__item">
                <span class="lg-v2-trust__icon">✅</span>
                <div>
                    <strong>Source chất lượng</strong>
                    <span>Được kiểm duyệt kỹ càng</span>
                </div>
            </div>
            <div class="lg-v2-trust__item">
                <span class="lg-v2-trust__icon">🔄</span>
                <div>
                    <strong>Cập nhật thường xuyên</strong>
                    <span>Update liên tục, tối ưu</span>
                </div>
            </div>
            <div class="lg-v2-trust__item">
                <span class="lg-v2-trust__icon">💬</span>
                <div>
                    <strong>Hỗ trợ nhanh chóng</strong>
                    <span>Hỗ trợ 24/7 từ đội ngũ</span>
                </div>
            </div>
            <div class="lg-v2-trust__item">
                <span class="lg-v2-trust__icon">📄</span>
                <div>
                    <strong>Tài liệu đầy đủ</strong>
                    <span>Hướng dẫn chi tiết, dễ hiểu</span>
                </div>
            </div>
            <div class="lg-v2-trust__item">
                <span class="lg-v2-trust__icon">💰</span>
                <div>
                    <strong>Hoàn tiền 7 ngày</strong>
                    <span>Không hài lòng, hoàn tiền 100%</span>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ============================================================
     SECONDARY CONTENT — Below the fold
     ============================================================ --}}

{{-- BLOG & TUTORIAL --}}
<section class="lg-v2-section">
    <div class="lg-v2-container">
        <div class="lg-v2-section__header">
            <h2 class="lg-v2-section__title">📝 Blog & Tutorial</h2>
            <a href="/blog" class="lg-v2-section__link">Xem tất cả →</a>
        </div>
        <div class="lg-v2-blog-grid">
            @forelse(($latestBlogs ?? []) as $blog)
            <a href="{{ $blog['url'] ?? '/blog' }}" class="lg-v2-blog-card">
                <div class="lg-v2-blog-card__img">
                    <img src="{{ $blog['thumbnail'] ?? asset('images/placeholder-game.svg') }}" alt="{{ $blog['title'] ?? '' }}" loading="lazy">
                </div>
                <div class="lg-v2-blog-card__body">
                    <h3>{{ Str::limit($blog['title'] ?? '', 60) }}</h3>
                    <p>{{ Str::limit($blog['excerpt'] ?? '', 80) }}</p>
                    <span class="lg-v2-blog-card__meta">{{ $blog['author'] ?? 'LamGame' }} · {{ $blog['date'] ?? '' }}</span>
                </div>
            </a>
            @empty
            @for($i = 0; $i < 3; $i++)
            <div class="lg-v2-blog-card">
                <div class="lg-v2-blog-card__img"><div class="lg-v2-skeleton" style="width:100%;height:100%;"></div></div>
                <div class="lg-v2-blog-card__body">
                    <div class="lg-v2-skeleton" style="height:16px;width:80%;margin-bottom:0.5rem;"></div>
                    <div class="lg-v2-skeleton" style="height:12px;width:100%;"></div>
                </div>
            </div>
            @endfor
            @endforelse
        </div>
    </div>
</section>

{{-- FORUM HOT TOPICS --}}
<section class="lg-v2-section lg-v2-section--alt">
    <div class="lg-v2-container">
        <div class="lg-v2-section__header">
            <h2 class="lg-v2-section__title">💬 Cộng đồng đang bàn</h2>
            <a href="/forum" class="lg-v2-section__link">Xem Forum →</a>
        </div>
        <div class="lg-v2-forum-list">
            @forelse(($hotForumTopics ?? []) as $topic)
            <a href="{{ $topic['url'] ?? '/forum' }}" class="lg-v2-forum-item">
                <div class="lg-v2-forum-item__content">
                    <h3>{{ Str::limit($topic['title'] ?? '', 70) }}</h3>
                    <span class="lg-v2-forum-item__meta">{{ $topic['author'] ?? '' }} · {{ $topic['category'] ?? '' }} · {{ $topic['time_ago'] ?? '' }}</span>
                </div>
                <div class="lg-v2-forum-item__stats">
                    <span>💬 {{ $topic['replies'] ?? 0 }}</span>
                    <span>👁 {{ number_format($topic['views'] ?? 0) }}</span>
                    <span>❤️ {{ $topic['likes'] ?? 0 }}</span>
                </div>
            </a>
            @empty
            @for($i = 0; $i < 3; $i++)
            <div class="lg-v2-forum-item">
                <div class="lg-v2-forum-item__content">
                    <div class="lg-v2-skeleton" style="height:16px;width:70%;margin-bottom:0.5rem;"></div>
                    <div class="lg-v2-skeleton" style="height:12px;width:40%;"></div>
                </div>
            </div>
            @endfor
            @endforelse
        </div>
    </div>
</section>

{{-- AI GAME DEV WIDGET — replaced lottery widget --}}
<section class="lg-v2-section">
    <div class="lg-v2-container">
        <a href="/ai-tools" class="lg-v2-lottery-widget">
            <div class="lg-v2-lottery-widget__left">
                <span class="lg-v2-lottery-widget__icon">🤖</span>
                <div>
                    <strong>AI Tools cho Game Developer</strong>
                    <span>Generate ideas, code snippets, assets và nhiều hơn nữa</span>
                </div>
            </div>
            <span class="lg-v2-btn lg-v2-btn--outline lg-v2-btn--sm">Thử AI Tools →</span>
        </a>
    </div>
</section>

@endsection

@push('styles')
<style>
/* ===== HERO ===== */
.lg-v2-hero {
    padding: 4rem 0 2rem;
    background: var(--lg-bg);
    background-image: radial-gradient(ellipse at 30% 50%, rgba(139, 92, 246, 0.08) 0%, transparent 50%);
}

.lg-v2-hero__grid {
    display: grid;
    gap: 2rem;
    align-items: center;
}

@media (min-width: 1024px) {
    .lg-v2-hero__grid {
        grid-template-columns: 1fr 1fr;
        gap: 3rem;
    }
}

.lg-v2-hero__badge {
    display: inline-block;
    padding: 0.375rem 0.75rem;
    font-size: 0.6875rem;
    font-weight: 600;
    letter-spacing: 0.05em;
    color: var(--lg-accent-light);
    border: 1px solid var(--lg-accent);
    border-radius: 100px;
    margin-bottom: 1.5rem;
}

.lg-v2-hero__content h1 {
    margin-bottom: 1rem;
}

.lg-v2-hero__accent {
    background: var(--lg-accent-gradient);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.lg-v2-hero__sub {
    font-size: 1rem;
    color: var(--lg-text-secondary);
    line-height: 1.6;
    margin-bottom: 1.5rem;
    max-width: 480px;
}

.lg-v2-hero__cta {
    display: flex;
    gap: 0.75rem;
    flex-wrap: wrap;
}

.lg-v2-hero__media {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.lg-v2-hero__video {
    position: relative;
    border-radius: var(--lg-radius-card);
    overflow: hidden;
    aspect-ratio: 16/9;
    border: 1px solid var(--lg-border);
}

.lg-v2-hero__video img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.lg-v2-hero__play-btn {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 64px;
    height: 64px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(139, 92, 246, 0.9);
    border: none;
    border-radius: 50%;
    cursor: pointer;
    transition: all var(--lg-transition);
    box-shadow: 0 0 30px rgba(139, 92, 246, 0.4);
}

.lg-v2-hero__play-btn:hover {
    transform: translate(-50%, -50%) scale(1.1);
}

.lg-v2-hero__usps {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 0.5rem;
}

.lg-v2-hero__usp {
    padding: 0.5rem 0.75rem;
    font-size: 0.75rem;
    color: var(--lg-text-secondary);
    background: var(--lg-bg-secondary);
    border: 1px solid var(--lg-border);
    border-radius: var(--lg-radius-btn);
}

/* Stats */
.lg-v2-hero__stats {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
    margin-top: 3rem;
    padding-top: 2rem;
    border-top: 1px solid var(--lg-border);
}

@media (min-width: 640px) {
    .lg-v2-hero__stats {
        grid-template-columns: repeat(4, 1fr);
    }
}

.lg-v2-hero__stat {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.lg-v2-hero__stat-icon {
    font-size: 1.5rem;
}

.lg-v2-hero__stat-number {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--lg-text);
}

.lg-v2-hero__stat-label {
    font-size: 0.75rem;
    color: var(--lg-text-muted);
}

.lg-v2-hero__stat {
    flex-direction: column;
    align-items: flex-start;
    gap: 0.25rem;
}

/* ===== CATEGORIES ===== */
.lg-v2-category-card {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
    padding: 1rem 1.25rem;
    min-width: 100px;
    background: var(--lg-bg-secondary);
    border: 1px solid var(--lg-border);
    border-radius: var(--lg-radius-card);
    text-decoration: none;
    transition: all var(--lg-transition);
}

.lg-v2-category-card:hover {
    border-color: var(--lg-accent);
    background: var(--lg-accent-subtle);
}

.lg-v2-category-card--active {
    border-color: var(--lg-accent);
    background: var(--lg-accent-subtle);
}

.lg-v2-category-card__icon {
    font-size: 1.5rem;
}

.lg-v2-category-card__name {
    font-size: 0.8125rem;
    font-weight: 500;
    color: var(--lg-text);
}

.lg-v2-category-card__count {
    font-size: 0.6875rem;
    color: var(--lg-text-muted);
}

/* ===== CURATED ===== */
.lg-v2-curated {
    display: grid;
    gap: 1.5rem;
    grid-template-columns: 1fr;
}

@media (min-width: 768px) {
    .lg-v2-curated {
        grid-template-columns: repeat(3, 1fr);
    }
}

.lg-v2-curated__col {
    padding: 1.25rem;
    background: var(--lg-bg-secondary);
    border: 1px solid var(--lg-border);
    border-radius: var(--lg-radius-card);
}

.lg-v2-curated__header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 0.25rem;
}

.lg-v2-curated__header h3 {
    font-size: 0.9375rem;
    font-weight: 600;
}

.lg-v2-curated__desc {
    font-size: 0.75rem;
    color: var(--lg-text-muted);
    margin-bottom: 1rem;
}

.lg-v2-curated__thumbs {
    display: flex;
    gap: 0.5rem;
}

.lg-v2-curated__thumb {
    width: 80px;
    height: 80px;
    border-radius: var(--lg-radius-btn);
    overflow: hidden;
    border: 1px solid var(--lg-border);
    transition: all var(--lg-transition);
    flex-shrink: 0;
}

.lg-v2-curated__thumb:hover {
    border-color: var(--lg-accent);
    transform: scale(1.05);
}

.lg-v2-curated__thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* ===== FILTERS ===== */
.lg-v2-filters {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 0.75rem;
    padding: 1rem 0;
    border-bottom: 1px solid var(--lg-border);
}

.lg-v2-filters__left {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.lg-v2-filters__right {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.lg-v2-filter-select {
    padding: 0.5rem 0.75rem;
    font-size: 0.8125rem;
    color: var(--lg-text-secondary);
    background: var(--lg-bg-secondary);
    border: 1px solid var(--lg-border);
    border-radius: var(--lg-radius-tag);
    cursor: pointer;
    font-family: inherit;
    outline: none;
}

.lg-v2-filter-select:hover {
    border-color: var(--lg-border-light);
}

/* ===== PRODUCT CARD ===== */
.lg-v2-product-card {
    padding: 0 !important;
    overflow: hidden;
    text-decoration: none;
    display: flex;
    flex-direction: column;
}

.lg-v2-product-card__img {
    position: relative;
    aspect-ratio: 16/10;
    overflow: hidden;
    background: var(--lg-bg-tertiary);
}

.lg-v2-product-card__img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform var(--lg-transition-slow);
}

.lg-v2-product-card:hover .lg-v2-product-card__img img {
    transform: scale(1.05);
}

.lg-v2-product-card__img .lg-v2-badge {
    position: absolute;
    top: 0.75rem;
    left: 0.75rem;
}

.lg-v2-product-card__wishlist {
    position: absolute;
    top: 0.75rem;
    right: 0.75rem;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(13, 13, 26, 0.7);
    border: 1px solid var(--lg-border);
    border-radius: 50%;
    color: var(--lg-text-secondary);
    font-size: 1rem;
    cursor: pointer;
    transition: all var(--lg-transition);
}

.lg-v2-product-card__wishlist:hover {
    color: var(--lg-danger);
    border-color: var(--lg-danger);
}

.lg-v2-product-card__body {
    padding: 1rem;
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.lg-v2-product-card__title {
    font-size: 0.9375rem;
    font-weight: 600;
    color: var(--lg-text);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.lg-v2-product-card__desc {
    font-size: 0.75rem;
    color: var(--lg-text-muted);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.lg-v2-product-card__tags {
    display: flex;
    gap: 0.25rem;
    flex-wrap: wrap;
}

.lg-v2-product-card__meta {
    display: flex;
    gap: 0.25rem;
    flex-wrap: wrap;
}

.lg-v2-product-card__footer {
    margin-top: auto;
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.lg-v2-product-card__rating {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.6875rem;
    color: var(--lg-text-muted);
}

.lg-v2-product-card__price-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.lg-v2-product-card__price {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--lg-text);
}

.lg-v2-product-card__price--free {
    color: var(--lg-success);
}

/* ===== TRUST BAR ===== */
.lg-v2-trust {
    padding: 2rem 0;
    background: var(--lg-bg-secondary);
    border-top: 1px solid var(--lg-border);
    border-bottom: 1px solid var(--lg-border);
}

.lg-v2-trust__grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.5rem;
}

@media (min-width: 768px) {
    .lg-v2-trust__grid {
        grid-template-columns: repeat(5, 1fr);
        gap: 1rem;
    }
}

.lg-v2-trust__item {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
}

.lg-v2-trust__icon {
    font-size: 1.25rem;
    flex-shrink: 0;
}

.lg-v2-trust__item strong {
    display: block;
    font-size: 0.8125rem;
    font-weight: 500;
    color: var(--lg-text);
    margin-bottom: 0.125rem;
}

.lg-v2-trust__item span {
    font-size: 0.6875rem;
    color: var(--lg-text-muted);
}
</style>
@endpush

@push('scripts')
{{-- Alpine.js is loaded in master-v2 layout --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const grid = document.getElementById('product-grid');
    const loadMoreBtn = document.getElementById('load-more-btn');
    let currentPage = 1;
    let isLoading = false;

    // Filter elements
    const filterEngine = document.getElementById('filter-engine');
    const filterGenre = document.getElementById('filter-genre');
    const filterPrice = document.getElementById('filter-price');
    const filterDifficulty = document.getElementById('filter-difficulty');
    const filterSort = document.getElementById('filter-sort');

    // Debounce helper
    function debounce(fn, delay) {
        let timer;
        return function(...args) {
            clearTimeout(timer);
            timer = setTimeout(() => fn.apply(this, args), delay);
        };
    }

    // Build query params
    function getFilterParams(page = 1) {
        const params = new URLSearchParams();
        if (filterEngine?.value) params.set('engine', filterEngine.value);
        if (filterGenre?.value) params.set('genre', filterGenre.value);
        if (filterDifficulty?.value) params.set('difficulty', filterDifficulty.value);
        if (filterSort?.value) params.set('sort', filterSort.value);
        if (filterPrice?.value) {
            const [min, max] = filterPrice.value.split('-');
            if (min) params.set('price_min', min);
            if (max) params.set('price_max', max);
        }
        params.set('page', page);
        return params.toString();
    }

    // Render product card HTML
    function renderProductCard(product) {
        const badgeHtml = product.badge ? `<span class="lg-v2-badge lg-v2-badge--${product.badge}">${(product.badge === 'bestseller' ? 'BEST SELLER' : product.badge).toUpperCase()}</span>` : '';
        const tagsHtml = (product.genre_tags || []).slice(0, 3).map(t => `<span class="lg-v2-tag">${t}</span>`).join('');
        const platformHtml = (product.platform || []).slice(0, 3).map(p => `<span class="lg-v2-tag">${p}</span>`).join('');
        const priceHtml = product.is_free
            ? '<span class="lg-v2-product-card__price lg-v2-product-card__price--free">Free</span>'
            : `<span class="lg-v2-product-card__price">$${product.price}</span>`;

        return `
        <a href="${product.url}" class="lg-v2-card lg-v2-product-card lg-v2-fade-in visible">
            <div class="lg-v2-product-card__img">
                <img src="${product.thumbnail}" alt="${product.name}" loading="lazy">
                ${badgeHtml}
                <button class="lg-v2-product-card__wishlist" title="Thêm vào wishlist">♡</button>
            </div>
            <div class="lg-v2-product-card__body">
                <h3 class="lg-v2-product-card__title">${product.name}</h3>
                <p class="lg-v2-product-card__desc">${product.description || ''}</p>
                <div class="lg-v2-product-card__tags">${tagsHtml}</div>
                <div class="lg-v2-product-card__meta">
                    <span class="lg-v2-tag">${product.engine}</span>
                    ${platformHtml}
                </div>
                <div class="lg-v2-product-card__footer">
                    <div class="lg-v2-product-card__rating">
                        <span>⭐ ${product.rating} (${product.review_count})</span>
                        <span>• ${product.sales_count.toLocaleString()} sales</span>
                    </div>
                    <div class="lg-v2-product-card__price-row">
                        ${priceHtml}
                        <span class="lg-v2-btn lg-v2-btn--outline lg-v2-btn--sm">Xem chi tiết</span>
                    </div>
                </div>
            </div>
        </a>`;
    }

    // Skeleton loader
    function renderSkeletons(count = 8) {
        let html = '';
        for (let i = 0; i < count; i++) {
            html += `
            <div class="lg-v2-card" style="padding:0;overflow:hidden;">
                <div class="lg-v2-skeleton" style="height:180px;border-radius:0;"></div>
                <div style="padding:1rem;">
                    <div class="lg-v2-skeleton" style="height:16px;width:70%;margin-bottom:0.5rem;"></div>
                    <div class="lg-v2-skeleton" style="height:12px;width:90%;margin-bottom:0.75rem;"></div>
                    <div style="display:flex;gap:0.25rem;margin-bottom:0.75rem;">
                        <div class="lg-v2-skeleton" style="height:20px;width:40px;"></div>
                        <div class="lg-v2-skeleton" style="height:20px;width:50px;"></div>
                    </div>
                    <div style="display:flex;justify-content:space-between;align-items:center;">
                        <div class="lg-v2-skeleton" style="height:24px;width:50px;"></div>
                        <div class="lg-v2-skeleton" style="height:32px;width:90px;border-radius:8px;"></div>
                    </div>
                </div>
            </div>`;
        }
        return html;
    }

    // Fetch and render products
    async function fetchProducts(page = 1, append = false) {
        if (isLoading) return;
        isLoading = true;

        if (!append) {
            grid.innerHTML = renderSkeletons();
        }

        try {
            const response = await fetch(`/api/v1/homepage/products?${getFilterParams(page)}`);
            const data = await response.json();

            if (data.success && data.data) {
                const products = data.data.items || [];
                const html = products.map(renderProductCard).join('');

                if (append) {
                    grid.insertAdjacentHTML('beforeend', html);
                } else {
                    grid.innerHTML = html;
                }

                // Update load more button
                if (loadMoreBtn) {
                    loadMoreBtn.style.display = data.data.has_more ? 'inline-flex' : 'none';
                }

                currentPage = page;
            }
        } catch (err) {
            console.error('Failed to fetch products:', err);
            if (!append) {
                grid.innerHTML = '<p style="text-align:center;color:var(--lg-text-muted);grid-column:1/-1;padding:2rem;">Không thể tải sản phẩm. Vui lòng thử lại.</p>';
            }
        } finally {
            isLoading = false;
        }
    }

    // Filter change handlers
    const handleFilterChange = debounce(() => {
        currentPage = 1;
        fetchProducts(1, false);
    }, 300);

    [filterEngine, filterGenre, filterPrice, filterDifficulty, filterSort].forEach(el => {
        if (el) el.addEventListener('change', handleFilterChange);
    });

    // Load more
    if (loadMoreBtn) {
        loadMoreBtn.addEventListener('click', () => {
            fetchProducts(currentPage + 1, true);
        });
    }

    // Mobile filter drawer
    const filterToggle = document.getElementById('filter-mobile-toggle');
    const filterPanel = document.getElementById('filter-panel');
    const filterOverlay = document.getElementById('filter-overlay');

    if (filterToggle && filterPanel) {
        filterToggle.addEventListener('click', () => {
            filterPanel.classList.toggle('is-open');
            filterOverlay.classList.toggle('is-open');
        });
        if (filterOverlay) {
            filterOverlay.addEventListener('click', () => {
                filterPanel.classList.remove('is-open');
                filterOverlay.classList.remove('is-open');
            });
        }
    }

    // Stats counter animation
    const statNumbers = document.querySelectorAll('.lg-v2-hero__stat-number');
    const statsObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('lg-v2-count-up');
                statsObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.5 });
    statNumbers.forEach(el => statsObserver.observe(el));

    // Category click → update genre filter
    document.querySelectorAll('.lg-v2-category-card').forEach(card => {
        card.addEventListener('click', function(e) {
            e.preventDefault();
            const url = new URL(this.href);
            const genre = url.searchParams.get('genre') || '';

            // Update active state
            document.querySelectorAll('.lg-v2-category-card').forEach(c => c.classList.remove('lg-v2-category-card--active'));
            this.classList.add('lg-v2-category-card--active');

            // Update filter and fetch
            if (filterGenre) {
                filterGenre.value = genre === 'all' ? '' : genre;
            }
            currentPage = 1;
            fetchProducts(1, false);
        });
    });
});
</script>
@endpush
