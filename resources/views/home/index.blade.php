{{-- LAMGAME.VN HOMEPAGE — Dark Gaming UI --}}
@extends('layouts.master')

@section('page_title', $page_title ?? 'LamGame.vn — Cộng đồng Game Developer Việt Nam')
@section('page_description', $page_description ?? '')

@section('content')
<div class="lg-home">

{{-- HERO --}}
<section class="lg-hero">
    <div class="lg-hero__bg"></div>
    <div class="lg-hero__content">
        <span class="lg-hero__badge"><span class="lg-hero__badge-dot"></span> Cộng đồng #1 Việt Nam</span>
        <h1 class="lg-hero__title">Học hỏi. Chia sẻ.<br>Phát triển cùng nhau.</h1>
        <p class="lg-hero__sub">Nơi quy tụ các Game Developer Việt Nam — Forum, Marketplace, AI Tools, Việc làm & hơn thế nữa.</p>
        <div class="lg-hero__cta">
            <a href="/choi-game" class="lg-btn lg-btn--primary">Khám phá ngay</a>
            <a href="/cong-dong" class="lg-btn lg-btn--outline">Tham gia cộng đồng</a>
        </div>
    </div>
</section>

{{-- STATS --}}
<section class="lg-stats">
    <div class="lg-container">
        <div class="lg-stats__grid">
            <div class="lg-stats__item"><span class="lg-stats__num">12.000+</span><span class="lg-stats__label">Thành viên</span></div>
            <div class="lg-stats__item"><span class="lg-stats__num">1.250+</span><span class="lg-stats__label">Source Code</span></div>
            <div class="lg-stats__item"><span class="lg-stats__num">{{ $stats['blog_posts'] ?? 850 }}+</span><span class="lg-stats__label">Bài viết</span></div>
            <div class="lg-stats__item"><span class="lg-stats__num">450+</span><span class="lg-stats__label">Việc làm</span></div>
            <div class="lg-stats__item"><span class="lg-stats__num">80+</span><span class="lg-stats__label">Studio</span></div>
        </div>
    </div>
</section>

{{-- QUICK MENU --}}
<section class="lg-section">
    <div class="lg-container">
        <div class="lg-quick">
            <a href="/choi-game" class="lg-quick__item"><span class="lg-quick__icon">🎮</span><span class="lg-quick__text">Chơi Game</span></a>
            <a href="/source-game" class="lg-quick__item"><span class="lg-quick__icon">💾</span><span class="lg-quick__text">Source Game</span></a>
            <a href="/ai-tools" class="lg-quick__item"><span class="lg-quick__icon">🤖</span><span class="lg-quick__text">AI Tools</span></a>
            <a href="/viec-lam-game" class="lg-quick__item"><span class="lg-quick__icon">💼</span><span class="lg-quick__text">Việc làm</span></a>
            <a href="/cong-dong" class="lg-quick__item"><span class="lg-quick__icon">💬</span><span class="lg-quick__text">Forum</span></a>
            <a href="/blog" class="lg-quick__item"><span class="lg-quick__icon">📝</span><span class="lg-quick__text">Blog</span></a>
        </div>
    </div>
</section>

{{-- SOURCE MARKETPLACE --}}
@if(!empty($sourceGames['featured']))
<section class="lg-section">
    <div class="lg-container">
        <div class="lg-section__head">
            <h2 class="lg-section__title">Source Game Marketplace</h2>
            <a href="/source-game" class="lg-section__more">Xem tất cả →</a>
        </div>
        <div class="lg-grid lg-grid--3">
            @foreach(array_slice($sourceGames['featured'], 0, 6) as $game)
            <a href="{{ $game['url'] ?? '#' }}" class="lg-card">
                <div class="lg-card__img">
                    <img src="{{ $game['thumbnail'] ?? '' }}" alt="{{ $game['title'] }}" loading="lazy">
                    @if(($game['price'] ?? 0) > 0)
                    <span class="lg-card__price">${{ number_format($game['price'], 0) }}</span>
                    @else
                    <span class="lg-card__price lg-card__price--free">Free</span>
                    @endif
                </div>
                <div class="lg-card__body">
                    <h3 class="lg-card__title">{{ Str::limit($game['title'], 40) }}</h3>
                    <p class="lg-card__desc">{{ Str::limit($game['short_description'] ?? '', 60) }}</p>
                    <div class="lg-card__meta">
                        <span class="lg-tag">{{ $game['engine'] ?? 'Unity' }}</span>
                        <span>⬇ {{ $game['downloads'] ?? 0 }}</span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- JOBS --}}
@if(!empty($jobs))
<section class="lg-section lg-section--alt">
    <div class="lg-container">
        <div class="lg-section__head">
            <h2 class="lg-section__title">Việc làm Game Dev</h2>
            <a href="/viec-lam-game" class="lg-section__more">Xem tất cả →</a>
        </div>
        <div class="lg-grid lg-grid--2">
            @foreach($jobs as $job)
            <div class="lg-job">
                <div class="lg-job__logo">{{ strtoupper(substr($job['company'] ?? 'G', 0, 1)) }}</div>
                <div class="lg-job__info">
                    <h3 class="lg-job__title">{{ Str::limit($job['title'] ?? $job['name'] ?? '', 50) }}</h3>
                    <p class="lg-job__company">{{ $job['company'] ?? 'Game Studio' }}</p>
                    <div class="lg-job__tags">
                        @if($job['salary'] ?? null)<span class="lg-tag lg-tag--cyan">{{ $job['salary'] }}</span>@endif
                        @if($job['location'] ?? null)<span class="lg-tag">{{ $job['location'] }}</span>@endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- BLOG --}}
@if(!empty($latestBlogs))
<section class="lg-section">
    <div class="lg-container">
        <div class="lg-section__head">
            <h2 class="lg-section__title">Blog & Tutorial</h2>
            <a href="/blog" class="lg-section__more">Xem tất cả →</a>
        </div>
        <div class="lg-grid lg-grid--3">
            @foreach($latestBlogs as $blog)
            <div class="lg-card">
                <div class="lg-card__img">
                    <img src="{{ $blog['thumbnail'] ?? $blog['image'] ?? '' }}" alt="{{ $blog['title'] ?? '' }}" loading="lazy">
                    <span class="lg-card__badge">{{ $blog['category'] ?? 'Tutorial' }}</span>
                </div>
                <div class="lg-card__body">
                    <h3 class="lg-card__title">{{ Str::limit($blog['title'] ?? '', 50) }}</h3>
                    <div class="lg-card__meta">
                        <span>{{ $blog['author'] ?? 'LamGame' }}</span>
                        <span>{{ $blog['views'] ?? 0 }} views</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- FORUM --}}
@if(!empty($hotForumTopics))
<section class="lg-section lg-section--alt">
    <div class="lg-container">
        <div class="lg-section__head">
            <h2 class="lg-section__title">Thảo luận nổi bật</h2>
            <a href="/cong-dong" class="lg-section__more">Xem tất cả →</a>
        </div>
        <div class="lg-forum-list">
            @foreach($hotForumTopics as $topic)
            <a href="{{ $topic['url'] ?? '#' }}" class="lg-forum-item">
                <div class="lg-forum-item__left">
                    <h3 class="lg-forum-item__title">{{ $topic['title'] ?? '' }}</h3>
                    <span class="lg-forum-item__author">{{ $topic['author'] ?? '' }} · {{ $topic['category'] ?? '' }}</span>
                </div>
                <div class="lg-forum-item__stats">
                    <span>💬 {{ $topic['comments'] ?? 0 }}</span>
                    <span>👁 {{ $topic['views'] ?? 0 }}</span>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- AI TOOLS --}}
<section class="lg-section">
    <div class="lg-container">
        <div class="lg-section__head">
            <h2 class="lg-section__title">AI Tools cho Game Dev</h2>
            <a href="/ai-tools" class="lg-section__more">Xem tất cả →</a>
        </div>
        <div class="lg-grid lg-grid--4">
            <div class="lg-ai-card"><span class="lg-ai-card__icon">🎨</span><h3>AI Asset Generator</h3><p>Tạo sprite, tilemap, UI assets</p></div>
            <div class="lg-ai-card"><span class="lg-ai-card__icon">🗣️</span><h3>AI NPC Voice</h3><p>Tạo giọng nói NPC tự nhiên</p></div>
            <div class="lg-ai-card"><span class="lg-ai-card__icon">📜</span><h3>AI Quest Generator</h3><p>Tạo quest & storyline tự động</p></div>
            <div class="lg-ai-card"><span class="lg-ai-card__icon">💻</span><h3>AI Code Assistant</h3><p>Debug & optimize game code</p></div>
        </div>
    </div>
</section>

{{-- CTA --}}
<section class="lg-cta">
    <div class="lg-container">
        <h2 class="lg-cta__title">Sẵn sàng tham gia cộng đồng?</h2>
        <p class="lg-cta__sub">Kết nối với hàng nghìn Game Developer Việt Nam</p>
        <div class="lg-cta__btns">
            <a href="/cong-dong" class="lg-btn lg-btn--primary">Tham gia ngay</a>
            <a href="https://discord.gg/lamgame" target="_blank" class="lg-btn lg-btn--outline">Discord Community</a>
        </div>
    </div>
</section>

</div>
@endsection

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Space+Grotesk:wght@600;700&display=swap" rel="stylesheet">
<style>
.lg-home{background:#070B14;color:#F5F7FA;font-family:'Inter',sans-serif}
.lg-container{max-width:1200px;margin:0 auto;padding:0 24px}
.lg-hero{position:relative;min-height:520px;display:flex;align-items:center;justify-content:center;text-align:center;padding:100px 24px;overflow:hidden}
.lg-hero__bg{position:absolute;inset:0;background:radial-gradient(ellipse at 30% 40%,rgba(124,92,255,.12) 0%,transparent 60%),radial-gradient(ellipse at 70% 60%,rgba(0,209,255,.08) 0%,transparent 50%)}
.lg-hero__content{position:relative;z-index:1;max-width:700px}
.lg-hero__badge{display:inline-flex;align-items:center;gap:8px;background:rgba(124,92,255,.1);border:1px solid rgba(124,92,255,.3);border-radius:20px;padding:6px 16px;font-size:.85rem;color:#B7C0D1;margin-bottom:24px}
.lg-hero__badge-dot{width:8px;height:8px;background:#7C5CFF;border-radius:50%;animation:pulse 2s infinite}
.lg-hero__title{font-family:'Space Grotesk',sans-serif;font-size:clamp(2.2rem,5vw,3.5rem);font-weight:700;line-height:1.15;margin-bottom:20px;background:linear-gradient(135deg,#F5F7FA,#B7C0D1);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text}
.lg-hero__sub{font-size:1.15rem;color:#7A8599;margin-bottom:32px;line-height:1.6}
.lg-hero__cta{display:flex;gap:16px;justify-content:center;flex-wrap:wrap}
.lg-btn{display:inline-flex;align-items:center;padding:12px 28px;border-radius:8px;font-weight:600;font-size:.95rem;text-decoration:none!important;transition:all .3s ease}
.lg-btn--primary{background:linear-gradient(135deg,#7C5CFF,#5B3FCC);color:#fff!important;box-shadow:0 4px 20px rgba(124,92,255,.3)}
.lg-btn--primary:hover{box-shadow:0 6px 30px rgba(124,92,255,.5);transform:translateY(-2px)}
.lg-btn--outline{background:transparent;color:#00D1FF!important;border:1.5px solid #00D1FF}
.lg-btn--outline:hover{background:rgba(0,209,255,.1);box-shadow:0 0 20px rgba(0,209,255,.2)}
.lg-stats{padding:48px 0;border-bottom:1px solid rgba(124,92,255,.1)}
.lg-stats__grid{display:grid;grid-template-columns:repeat(5,1fr);gap:24px;text-align:center}
.lg-stats__item{padding:20px;background:rgba(17,24,39,.6);border:1px solid rgba(124,92,255,.1);border-radius:12px;backdrop-filter:blur(8px);transition:all .3s}
.lg-stats__item:hover{border-color:#7C5CFF;box-shadow:0 0 20px rgba(124,92,255,.2)}
.lg-stats__num{display:block;font-family:'Space Grotesk',sans-serif;font-size:1.8rem;font-weight:700;color:#00D1FF}
.lg-stats__label{font-size:.85rem;color:#7A8599;margin-top:4px}
.lg-section{padding:64px 0}
.lg-section--alt{background:#0B1020}
.lg-section__head{display:flex;justify-content:space-between;align-items:center;margin-bottom:32px}
.lg-section__title{font-family:'Space Grotesk',sans-serif;font-size:1.8rem;font-weight:700;color:#F5F7FA}
.lg-section__more{color:#7C5CFF!important;font-weight:500;text-decoration:none;font-size:.9rem}
.lg-grid{display:grid;gap:24px}
.lg-grid--2{grid-template-columns:repeat(2,1fr)}
.lg-grid--3{grid-template-columns:repeat(3,1fr)}
.lg-grid--4{grid-template-columns:repeat(4,1fr)}
.lg-card{background:rgba(17,24,39,.8);border:1px solid rgba(124,92,255,.1);border-radius:14px;overflow:hidden;transition:all .3s;text-decoration:none!important}
.lg-card:hover{border-color:#7C5CFF;box-shadow:0 8px 30px rgba(124,92,255,.15);transform:translateY(-4px)}
.lg-card__img{position:relative;aspect-ratio:16/10;overflow:hidden;background:#111827}
.lg-card__img img{width:100%;height:100%;object-fit:cover}
.lg-card__price{position:absolute;top:12px;right:12px;background:rgba(0,209,255,.9);color:#070B14;padding:4px 10px;border-radius:6px;font-weight:700;font-size:.85rem}
.lg-card__price--free{background:rgba(77,163,255,.9)}
.lg-card__badge{position:absolute;top:12px;left:12px;background:rgba(124,92,255,.85);color:#fff;padding:4px 10px;border-radius:6px;font-size:.75rem;font-weight:600}
.lg-card__body{padding:16px}
.lg-card__title{font-size:1rem;font-weight:600;color:#F5F7FA;margin-bottom:8px}
.lg-card__desc{font-size:.85rem;color:#7A8599;margin-bottom:12px}
.lg-card__meta{display:flex;justify-content:space-between;align-items:center;font-size:.8rem;color:#7A8599}
.lg-tag{display:inline-block;background:rgba(124,92,255,.1);color:#B7C0D1;border:1px solid rgba(124,92,255,.2);border-radius:5px;padding:2px 8px;font-size:.75rem}
.lg-tag--cyan{background:rgba(0,209,255,.1);border-color:rgba(0,209,255,.3);color:#00D1FF}
.lg-job{display:flex;gap:16px;align-items:center;padding:20px;background:rgba(17,24,39,.6);border:1px solid rgba(124,92,255,.1);border-radius:12px;transition:all .3s}
.lg-job:hover{border-color:#7C5CFF;box-shadow:0 0 20px rgba(124,92,255,.15)}
.lg-job__logo{width:48px;height:48px;background:linear-gradient(135deg,#7C5CFF,#00D1FF);border-radius:10px;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:1.2rem;color:#fff;flex-shrink:0}
.lg-job__title{font-size:1rem;font-weight:600;color:#F5F7FA;margin-bottom:4px}
.lg-job__company{font-size:.85rem;color:#7A8599;margin-bottom:8px}
.lg-job__tags{display:flex;gap:8px;flex-wrap:wrap}
.lg-forum-list{display:flex;flex-direction:column;gap:12px}
.lg-forum-item{display:flex;justify-content:space-between;align-items:center;padding:16px 20px;background:rgba(17,24,39,.6);border:1px solid rgba(124,92,255,.08);border-radius:10px;text-decoration:none!important;transition:all .3s}
.lg-forum-item:hover{border-color:#7C5CFF;background:rgba(124,92,255,.05)}
.lg-forum-item__title{font-size:.95rem;font-weight:500;color:#F5F7FA;margin-bottom:4px}
.lg-forum-item__author{font-size:.8rem;color:#7A8599}
.lg-forum-item__stats{display:flex;gap:16px;font-size:.8rem;color:#7A8599}
.lg-ai-card{padding:24px;background:rgba(17,24,39,.6);border:1px solid rgba(124,92,255,.1);border-radius:14px;text-align:center;transition:all .3s}
.lg-ai-card:hover{border-color:#00D1FF;box-shadow:0 0 25px rgba(0,209,255,.15);transform:translateY(-4px)}
.lg-ai-card__icon{font-size:2.5rem;display:block;margin-bottom:12px}
.lg-ai-card h3{font-size:1rem;font-weight:600;color:#F5F7FA;margin-bottom:8px}
.lg-ai-card p{font-size:.85rem;color:#7A8599}
.lg-quick{display:grid;grid-template-columns:repeat(6,1fr);gap:16px}
.lg-quick__item{display:flex;flex-direction:column;align-items:center;gap:10px;padding:24px 16px;background:rgba(17,24,39,.6);border:1px solid rgba(124,92,255,.1);border-radius:12px;text-decoration:none!important;transition:all .3s}
.lg-quick__item:hover{border-color:#7C5CFF;box-shadow:0 0 20px rgba(124,92,255,.2);transform:translateY(-3px)}
.lg-quick__icon{font-size:2rem}
.lg-quick__text{font-size:.85rem;font-weight:500;color:#B7C0D1}
.lg-cta{padding:80px 24px;text-align:center;background:radial-gradient(ellipse at center,rgba(124,92,255,.08) 0%,transparent 70%)}
.lg-cta__title{font-family:'Space Grotesk',sans-serif;font-size:2rem;font-weight:700;color:#F5F7FA;margin-bottom:12px}
.lg-cta__sub{color:#7A8599;margin-bottom:32px;font-size:1.1rem}
.lg-cta__btns{display:flex;gap:16px;justify-content:center;flex-wrap:wrap}
@keyframes pulse{0%,100%{opacity:1}50%{opacity:.4}}
@media(max-width:1024px){.lg-grid--4{grid-template-columns:repeat(2,1fr)}.lg-quick{grid-template-columns:repeat(3,1fr)}}
@media(max-width:768px){.lg-hero{min-height:400px;padding:60px 20px}.lg-hero__title{font-size:2rem}.lg-stats__grid{grid-template-columns:repeat(3,1fr)}.lg-grid--2,.lg-grid--3{grid-template-columns:1fr}.lg-quick{grid-template-columns:repeat(3,1fr)}.lg-section__head{flex-direction:column;gap:8px;align-items:flex-start}.lg-forum-item{flex-direction:column;align-items:flex-start;gap:8px}}
@media(max-width:480px){.lg-stats__grid{grid-template-columns:repeat(2,1fr)}.lg-quick{grid-template-columns:repeat(2,1fr)}.lg-grid--4{grid-template-columns:1fr}.lg-hero__cta{flex-direction:column;align-items:center}}
</style>
@endpush
