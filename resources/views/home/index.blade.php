{{-- LAMGAME.VN HOMEPAGE — Redesign: Product-first layout --}}
@extends('layouts.master')

@section('page_title', $page_title ?? 'LamGame.vn — Cộng đồng Game Developer Việt Nam')
@section('page_description', $page_description ?? '')

@section('content')
<div class="lg-home">

{{-- HERO — Video background + marketplace positioning --}}
<section class="lg-hero">
    <div class="lg-hero__bg">
        <video class="lg-hero__video" autoplay muted loop playsinline poster="{{ asset('assets/images/hero-poster.jpg') }}">
            <source src="{{ asset('assets/video/hero-bg.mp4') }}" type="video/mp4">
        </video>
        <div class="lg-hero__overlay"></div>
    </div>
    <div class="lg-hero__content">
        <span class="lg-hero__badge"><span class="lg-hero__badge-dot"></span> Marketplace Source Game hàng đầu Việt Nam</span>
        <h1 class="lg-hero__title">Source Game chất lượng cho <br><span class="lg-hero__gradient-text">Unity & Unreal Developer</span></h1>
        <p class="lg-hero__sub">Tiết kiệm hàng trăm giờ phát triển với source code production-ready, AI tools, và cộng đồng 12.000+ developers.</p>
        <div class="lg-hero__cta">
            <a href="{{ route('lamgame.source-game') }}" class="lg-btn lg-btn--primary">🎮 Khám phá Source Hot</a>
            <a href="{{ route('lamgame.source-game') }}?sort=best-selling" class="lg-btn lg-btn--glow">🏆 Source Bán Chạy</a>
            <a href="{{ route('forum.index') }}" class="lg-btn lg-btn--outline">💬 Cộng đồng Dev</a>
        </div>
        <div class="lg-hero__stats">
            <div class="lg-hero__stat"><strong>{{ $stats['source_games'] ?? '1,200' }}+</strong><span>Source Game</span></div>
            <div class="lg-hero__stat"><strong>12,000+</strong><span>Developers</span></div>
            <div class="lg-hero__stat"><strong>34</strong><span>Live Demo</span></div>
            <div class="lg-hero__stat"><strong>4.8/5</strong><span>Rating</span></div>
        </div>
        <div class="lg-hero__usp">
            <span>✅ Production-Ready</span>
            <span>🔄 Cập nhật thường xuyên</span>
            <span>🛡️ Hỗ trợ sau mua</span>
            <span>💰 Hoàn tiền 7 ngày</span>
        </div>
    </div>
</section>

{{-- SOURCE GAME — Featured, ngay sau hero --}}
@if(!empty($sourceGames['featured']))
<section class="lg-section" id="source-games">
    <div class="lg-container">
        <div class="lg-section__head">
            <div>
                <h2 class="lg-section__title">🎮 Source Game Hot</h2>
                <p class="lg-section__desc">Production-ready · Unity, Godot, Phaser · Bắt đầu từ $0</p>
            </div>
            <a href="/source-game" class="lg-btn lg-btn--sm">Xem tất cả →</a>
        </div>
        <div class="lg-grid lg-grid--3">
            @foreach(array_slice($sourceGames['featured'], 0, 6) as $i => $game)
            <a href="{{ $game['url'] ?? '#' }}" class="lg-card {{ $i === 0 ? 'lg-card--featured' : '' }}">
                <div class="lg-card__img">
                    <img src="{{ $game['thumbnail'] ?? '' }}" alt="{{ $game['title'] }}" loading="lazy">
                    @if(($game['price'] ?? 0) > 0)
                    <span class="lg-card__price">${{ number_format($game['price'], 0) }}</span>
                    @else
                    <span class="lg-card__price lg-card__price--free">Free</span>
                    @endif
                    @if($i < 2)<span class="lg-card__hot">🔥 Hot</span>@endif
                </div>
                <div class="lg-card__body">
                    <h3 class="lg-card__title">{{ Str::limit($game['title'], 40) }}</h3>
                    <p class="lg-card__desc">{{ Str::limit($game['short_description'] ?? '', 60) }}</p>
                    <div class="lg-card__meta">
                        <span class="lg-tag">{{ $game['engine'] ?? 'Unity' }}</span>
                        <div class="lg-card__stats-row">
                            <span>⬇ {{ $game['downloads'] ?? 0 }}</span>
                            <span>⭐ {{ number_format($game['rating'] ?? 4.5, 1) }}</span>
                        </div>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- FORUM — Cộng đồng sôi động --}}
@if(!empty($hotForumTopics['featured']))
<section class="lg-section lg-section--alt">
    <div class="lg-container">
        <div class="lg-section__head">
            <div>
                <h2 class="lg-section__title">💬 Cộng đồng đang bàn</h2>
                <p class="lg-section__desc">Thảo luận, chia sẻ project, hỏi đáp kỹ thuật</p>
            </div>
            <a href="/cong-dong" class="lg-btn lg-btn--sm">Xem Forum →</a>
        </div>
        <div class="lg-forum-list">
            @foreach($hotForumTopics['featured'] as $topic)
            <a href="{{ $topic['url'] ?? '#' }}" class="lg-forum-item">
                <div class="lg-forum-item__left">
                    <h3 class="lg-forum-item__title">{{ $topic['title'] ?? '' }}</h3>
                    <span class="lg-forum-item__author">{{ $topic['author'] ?? '' }} · {{ $topic['category'] ?? '' }} · {{ $topic['time_ago'] ?? '' }}</span>
                </div>
                <div class="lg-forum-item__stats">
                    <span>💬 {{ $topic['replies'] ?? 0 }}</span>
                    <span>👁 {{ $topic['views'] ?? 0 }}</span>
                    <span>❤️ {{ $topic['likes'] ?? 0 }}</span>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- BLOG --}}
@if(!empty($latestBlogs['featured']))
<section class="lg-section">
    <div class="lg-container">
        <div class="lg-section__head">
            <h2 class="lg-section__title">📝 Blog & Tutorial</h2>
            <a href="/blog" class="lg-btn lg-btn--sm">Xem tất cả →</a>
        </div>
        <div class="lg-grid lg-grid--3">
            @foreach(array_slice($latestBlogs['featured'], 0, 3) as $blog)
            <a href="{{ $blog['url'] ?? '#' }}" class="lg-card">
                <div class="lg-card__img">
                    <img src="{{ $blog['featured_image'] ?? '' }}" alt="{{ $blog['title'] ?? '' }}" loading="lazy">
                    <span class="lg-card__badge">{{ $blog['category'] ?? 'Tutorial' }}</span>
                </div>
                <div class="lg-card__body">
                    <h3 class="lg-card__title">{{ Str::limit($blog['title'] ?? '', 50) }}</h3>
                    <p class="lg-card__desc">{{ Str::limit($blog['excerpt'] ?? '', 80) }}</p>
                    <div class="lg-card__meta">
                        <span>{{ $blog['author'] ?? 'LamGame' }} · {{ $blog['time_ago'] ?? '' }}</span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- AI TOOLS — Compact row --}}
<section class="lg-section lg-section--alt">
    <div class="lg-container">
        <div class="lg-section__head">
            <div>
                <h2 class="lg-section__title">🤖 AI Tools cho Game Dev</h2>
                <p class="lg-section__desc">Tăng tốc 10x với AI — miễn phí cho thành viên</p>
            </div>
            <a href="/ai-tools" class="lg-btn lg-btn--sm">Dùng ngay →</a>
        </div>
        <div class="lg-grid lg-grid--4">
            <a href="/ai-tools" class="lg-ai-card"><span class="lg-ai-card__icon">🎨</span><h3>Asset Generator</h3><p>Sprite, tilemap, UI</p></a>
            <a href="/ai-tools" class="lg-ai-card"><span class="lg-ai-card__icon">📜</span><h3>GDD Generator</h3><p>Game Design Document</p></a>
            <a href="/ai-tools" class="lg-ai-card"><span class="lg-ai-card__icon">💻</span><h3>Code Assistant</h3><p>Debug & optimize</p></a>
            <a href="/ai-tools" class="lg-ai-card"><span class="lg-ai-card__icon">🗣️</span><h3>NPC Voice</h3><p>Giọng nói NPC tự nhiên</p></a>
        </div>
    </div>
</section>

{{-- BANNER PROMO (if active) --}}
@if(!empty($homepageBanners['has_banners']) && !empty($homepageBanners['banners']))
<section class="lg-section">
    <div class="lg-container">
        @foreach($homepageBanners['banners'] as $banner)
        <a href="{{ $banner['link'] }}" target="{{ $banner['target'] }}" class="lg-banner-promo__item">
            <img src="{{ $banner['image'] }}" alt="{{ $banner['image_alt'] ?? $banner['title'] }}" loading="lazy">
            @if($banner['title'])
            <div class="lg-banner-promo__overlay">
                <span class="lg-banner-promo__title">{{ $banner['title'] }}</span>
                @if($banner['content'])<span class="lg-banner-promo__desc">{{ $banner['content'] }}</span>@endif
            </div>
            @endif
        </a>
        @endforeach
    </div>
</section>
@endif

{{-- SOCIAL PROOF + CTA --}}
<section class="lg-cta">
    <div class="lg-container">
        <div class="lg-stats__grid">
            <div class="lg-stats__item"><span class="lg-stats__num">12.000+</span><span class="lg-stats__label">Developers</span></div>
            <div class="lg-stats__item"><span class="lg-stats__num">1.250+</span><span class="lg-stats__label">Source Code</span></div>
            <div class="lg-stats__item"><span class="lg-stats__num">{{ $stats['blog_posts'] ?? 850 }}+</span><span class="lg-stats__label">Bài viết</span></div>
            <div class="lg-stats__item"><span class="lg-stats__num">80+</span><span class="lg-stats__label">Game Studio</span></div>
        </div>
        <h2 class="lg-cta__title">Ship game nhanh hơn, bắt đầu ngay hôm nay.</h2>
        <div class="lg-cta__btns">
            <a href="{{ route('lamgame.source-game') }}" class="lg-btn lg-btn--primary">🎮 Xem Source Game</a>
            <a href="{{ route('forum.index') }}" class="lg-btn lg-btn--outline">💬 Tham gia cộng đồng</a>
        </div>
    </div>
</section>

{{-- INTERNAL LINKS — SEO: boost crawl for discovered-not-indexed pages --}}
<section class="lg-section" style="padding:32px 0">
    <div class="lg-container">
        <nav class="lg-internal-links" aria-label="Khám phá thêm">
            <h3 style="font-size:.9rem;color:#7A8599;margin-bottom:12px;font-weight:500">Khám phá thêm trên LamGame</h3>
            <div style="display:flex;flex-wrap:wrap;gap:8px">
                <a href="/viec-lam-game" class="lg-tag">💼 Việc làm Game</a>
                <a href="/khoa-hoc/unity" class="lg-tag">🎓 Khóa học Unity</a>
                <a href="/khoa-hoc/unreal" class="lg-tag">🎓 Khóa học Unreal</a>
                <a href="/the-thao" class="lg-tag">⚽ Thể thao</a>
                <a href="/xo-so" class="lg-tag">🎰 Xổ số</a>
                <a href="/choi-game" class="lg-tag">🕹️ Chơi Game</a>
                <a href="/thue-team-dev" class="lg-tag">👨‍💻 Thuê Team Dev</a>
                <a href="/forum/trending" class="lg-tag">🔥 Forum Hot</a>
                <a href="/forum/leaderboard" class="lg-tag">🏆 Bảng xếp hạng</a>
                <a href="/world-cup-2026" class="lg-tag">⚽ World Cup 2026</a>
                <a href="/seller/register" class="lg-tag">🏪 Đăng ký bán hàng</a>
                <a href="/employer/register" class="lg-tag">🏢 Đăng tuyển dụng</a>
                <a href="/gioi-thieu" class="lg-tag">Giới thiệu</a>
                <a href="/lien-he" class="lg-tag">Liên hệ</a>
            </div>
        </nav>
    </div>
</section>

</div>
@endsection

@push('styles')
<style>.lg-home{background:#070B14;min-height:100vh}</style>
<link rel="preload" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Space+Grotesk:wght@600;700&display=swap" as="style" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Space+Grotesk:wght@600;700&display=swap" rel="stylesheet"></noscript>
<style>
.lg-home{background:#070B14;color:#F5F7FA;font-family:'Inter',sans-serif}
.lg-container{max-width:1200px;margin:0 auto;padding:0 24px}

/* HERO — compact */
.lg-hero{position:relative;display:flex;align-items:center;justify-content:center;text-align:center;padding:100px 24px 70px;overflow:hidden;min-height:520px}
.lg-hero__bg{position:absolute;inset:0}
.lg-hero__video{position:absolute;inset:0;width:100%;height:100%;object-fit:cover;opacity:.3}
.lg-hero__overlay{position:absolute;inset:0;background:linear-gradient(180deg,rgba(13,13,26,.85) 0%,rgba(13,13,26,.95) 100%)}
.lg-hero__content{position:relative;z-index:1;max-width:760px}
.lg-hero__badge{display:inline-flex;align-items:center;gap:8px;background:rgba(124,92,255,.1);border:1px solid rgba(124,92,255,.3);border-radius:20px;padding:6px 16px;font-size:.85rem;color:#B7C0D1;margin-bottom:20px}
.lg-hero__badge-dot{width:8px;height:8px;background:#7C5CFF;border-radius:50%;animation:pulse 2s infinite}
.lg-hero__title{font-family:'Space Grotesk',sans-serif;font-size:clamp(2rem,5vw,3.2rem);font-weight:700;line-height:1.15;margin-bottom:16px;background:linear-gradient(135deg,#F5F7FA,#B7C0D1);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text}
.lg-hero__gradient-text{background:linear-gradient(135deg,#7C5CFF,#00D1FF);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text}
.lg-hero__sub{font-size:1.05rem;color:#7A8599;margin-bottom:28px;line-height:1.6}
.lg-hero__cta{display:flex;gap:14px;justify-content:center;flex-wrap:wrap}
.lg-hero__stats{display:flex;gap:24px;justify-content:center;margin-top:28px;padding-top:20px;border-top:1px solid rgba(139,92,246,.15)}
.lg-hero__stat{text-align:center}
.lg-hero__stat strong{display:block;font-size:1.2rem;font-weight:700;color:#A78BFA}
.lg-hero__stat span{font-size:.72rem;color:#71717A}
.lg-hero__usp{display:flex;gap:16px;justify-content:center;flex-wrap:wrap;margin-top:16px}
.lg-hero__usp span{font-size:.78rem;color:#A1A1AA;background:rgba(139,92,246,.06);padding:4px 10px;border-radius:12px;border:1px solid rgba(139,92,246,.1)}

/* BUTTONS */
.lg-btn{display:inline-flex;align-items:center;padding:12px 24px;border-radius:8px;font-weight:600;font-size:.9rem;text-decoration:none!important;transition:all .3s}
.lg-btn--primary{background:linear-gradient(135deg,#7C5CFF,#5B3FCC);color:#fff!important;box-shadow:0 4px 20px rgba(124,92,255,.3)}
.lg-btn--primary:hover{box-shadow:0 6px 30px rgba(124,92,255,.5);transform:translateY(-2px)}
.lg-btn--glow{background:linear-gradient(135deg,#10b981,#059669);color:#fff!important;box-shadow:0 4px 20px rgba(16,185,129,.3)}
.lg-btn--glow:hover{box-shadow:0 6px 30px rgba(16,185,129,.5);transform:translateY(-2px)}
.lg-btn--outline{background:transparent;color:#00D1FF!important;border:1.5px solid #00D1FF}
.lg-btn--outline:hover{background:rgba(0,209,255,.1)}
.lg-btn--sm{padding:8px 18px;font-size:.82rem;border-radius:6px;background:rgba(124,92,255,.1);color:#B7C0D1!important;border:1px solid rgba(124,92,255,.2)}
.lg-btn--sm:hover{background:rgba(124,92,255,.2);color:#fff!important}

/* SECTIONS */
.lg-section{padding:56px 0}
.lg-section--alt{background:#0B1020}
.lg-section__head{display:flex;justify-content:space-between;align-items:center;margin-bottom:28px}
.lg-section__title{font-family:'Space Grotesk',sans-serif;font-size:1.6rem;font-weight:700;color:#F5F7FA;margin:0}
.lg-section__desc{font-size:.85rem;color:#7A8599;margin-top:4px}
.lg-section__more{color:#7C5CFF!important;font-weight:500;text-decoration:none;font-size:.85rem}

/* GRID */
.lg-grid{display:grid;gap:20px}
.lg-grid--2{grid-template-columns:repeat(2,1fr)}
.lg-grid--3{grid-template-columns:repeat(3,1fr)}
.lg-grid--4{grid-template-columns:repeat(4,1fr)}

/* CARDS — Source Game (enhanced) */
.lg-card{background:rgba(17,24,39,.8);border:1px solid rgba(124,92,255,.1);border-radius:14px;overflow:hidden;transition:all .3s;text-decoration:none!important}
.lg-card:hover{border-color:#7C5CFF;box-shadow:0 8px 30px rgba(124,92,255,.15);transform:translateY(-4px)}
.lg-card--featured{border-color:rgba(0,209,255,.3);box-shadow:0 0 20px rgba(0,209,255,.08)}
.lg-card__img{position:relative;aspect-ratio:16/10;overflow:hidden;background:#111827}
.lg-card__img img{width:100%;height:100%;object-fit:cover;transition:transform .4s}
.lg-card:hover .lg-card__img img{transform:scale(1.05)}
.lg-card__price{position:absolute;top:12px;right:12px;background:rgba(0,209,255,.9);color:#070B14;padding:4px 10px;border-radius:6px;font-weight:700;font-size:.85rem}
.lg-card__price--free{background:rgba(16,185,129,.9)}
.lg-card__hot{position:absolute;top:12px;left:12px;background:rgba(239,68,68,.9);color:#fff;padding:3px 8px;border-radius:5px;font-size:.72rem;font-weight:700}
.lg-card__badge{position:absolute;top:12px;left:12px;background:rgba(124,92,255,.85);color:#fff;padding:4px 10px;border-radius:6px;font-size:.75rem;font-weight:600}
.lg-card__body{padding:16px}
.lg-card__title{font-size:1rem;font-weight:600;color:#F5F7FA;margin-bottom:6px}
.lg-card__desc{font-size:.82rem;color:#7A8599;margin-bottom:10px;line-height:1.4}
.lg-card__meta{display:flex;justify-content:space-between;align-items:center;font-size:.78rem;color:#7A8599}
.lg-card__stats-row{display:flex;gap:10px}
.lg-tag{display:inline-block;background:rgba(124,92,255,.1);color:#B7C0D1;border:1px solid rgba(124,92,255,.2);border-radius:5px;padding:2px 8px;font-size:.72rem}

/* FORUM */
.lg-forum-list{display:flex;flex-direction:column;gap:10px}
.lg-forum-item{display:flex;justify-content:space-between;align-items:center;padding:14px 18px;background:rgba(17,24,39,.6);border:1px solid rgba(124,92,255,.08);border-radius:10px;text-decoration:none!important;transition:all .3s}
.lg-forum-item:hover{border-color:#7C5CFF;background:rgba(124,92,255,.05)}
.lg-forum-item__title{font-size:.92rem;font-weight:500;color:#F5F7FA;margin-bottom:4px}
.lg-forum-item__author{font-size:.78rem;color:#7A8599}
.lg-forum-item__stats{display:flex;gap:14px;font-size:.78rem;color:#7A8599}

/* AI TOOLS */
.lg-ai-card{display:block;padding:20px;background:rgba(17,24,39,.6);border:1px solid rgba(124,92,255,.1);border-radius:14px;text-align:center;text-decoration:none!important;transition:all .3s}
.lg-ai-card:hover{border-color:#00D1FF;box-shadow:0 0 25px rgba(0,209,255,.12);transform:translateY(-3px)}
.lg-ai-card__icon{font-size:2rem;display:block;margin-bottom:10px}
.lg-ai-card h3{font-size:.9rem;font-weight:600;color:#F5F7FA;margin-bottom:6px}
.lg-ai-card p{font-size:.8rem;color:#7A8599;margin:0}

/* BANNER */
.lg-banner-promo__item{display:block;border-radius:14px;overflow:hidden;position:relative;box-shadow:0 8px 32px rgba(0,0,0,.4);transition:transform .3s}
.lg-banner-promo__item:hover{transform:translateY(-3px)}
.lg-banner-promo__item img{width:100%;height:180px;object-fit:cover;display:block}
.lg-banner-promo__overlay{position:absolute;bottom:0;left:0;right:0;padding:14px 18px;background:linear-gradient(transparent,rgba(0,0,0,.8))}
.lg-banner-promo__title{display:block;color:#fff;font-weight:700;font-size:1rem}
.lg-banner-promo__desc{display:block;color:#cbd5e1;font-size:.82rem;margin-top:3px}

/* CTA + STATS */
.lg-cta{padding:64px 24px;text-align:center;background:radial-gradient(ellipse at center,rgba(124,92,255,.06) 0%,transparent 70%)}
.lg-cta__title{font-family:'Space Grotesk',sans-serif;font-size:1.8rem;font-weight:700;color:#F5F7FA;margin:32px 0 24px}
.lg-cta__btns{display:flex;gap:16px;justify-content:center;flex-wrap:wrap}
.lg-stats__grid{display:grid;grid-template-columns:repeat(4,1fr);gap:16px;text-align:center;margin-bottom:8px}
.lg-stats__item{padding:16px;background:rgba(17,24,39,.6);border:1px solid rgba(124,92,255,.1);border-radius:10px}
.lg-stats__num{display:block;font-family:'Space Grotesk',sans-serif;font-size:1.6rem;font-weight:700;color:#00D1FF}
.lg-stats__label{font-size:.8rem;color:#7A8599;margin-top:2px}

@keyframes pulse{0%,100%{opacity:1}50%{opacity:.4}}
@media(max-width:1024px){.lg-grid--4{grid-template-columns:repeat(2,1fr)}}
@media(max-width:768px){.lg-hero{padding:60px 20px 40px;min-height:auto}.lg-hero__title{font-size:1.8rem}.lg-hero__cta{flex-direction:column;align-items:center}.lg-hero__stats{gap:12px;flex-wrap:wrap}.lg-hero__usp{gap:8px}.lg-grid--2,.lg-grid--3{grid-template-columns:1fr}.lg-stats__grid{grid-template-columns:repeat(2,1fr)}.lg-section__head{flex-direction:column;gap:8px;align-items:flex-start}.lg-forum-item{flex-direction:column;align-items:flex-start;gap:8px}}
@media(max-width:480px){.lg-grid--4{grid-template-columns:1fr}.lg-stats__grid{grid-template-columns:repeat(2,1fr)}}
</style>
@endpush
