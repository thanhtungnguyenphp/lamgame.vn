@extends('layouts.master')

@section('page_title', $page_title)
@section('page_description', $page_description)
@section('og_type', 'website')
@section('og_image', $page->og_image_url)
@section('twitter_card', 'summary_large_image')

@push('meta')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;600;700;800&display=swap" rel="stylesheet">
    <script type="application/ld+json">
    {!! json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'MobileApplication',
        'name' => $page->hero_title ?? 'Lotto Live',
        'operatingSystem' => 'Android, iOS',
        'applicationCategory' => 'UtilitiesApplication',
        'description' => $page_description,
        'url' => url()->current(),
        'image' => $page->og_image_url,
        'offers' => ['@type' => 'Offer', 'price' => '0', 'priceCurrency' => 'VND'],
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
    </script>
    <script type="application/ld+json">
    {!! \App\Helpers\StructuredDataHelper::breadcrumb([
        ['name' => 'Trang chủ', 'url' => config('app.url')],
        ['name' => $page->name, 'url' => $page->url]
    ]) !!}
    </script>
@endpush

@section('content')
<div class="ll-page">

    {{-- ===== HERO SECTION ===== --}}
    <section class="ll-hero">
        <div class="ll-hero__bg"></div>
        <div class="ll-hero__particles" id="particles"></div>
        <div class="container">
            <div class="ll-hero__grid">
                <div class="ll-hero__text">
                    <h1 class="ll-hero__title">{{ $page->hero_title ?? 'Lotto Live' }}</h1>
                    <p class="ll-hero__subtitle">{{ $page->hero_subtitle ?? 'May mắn trong tầm tay' }}</p>
                    <div class="ll-hero__buttons">
                        @if($page->getSection('app_store_url'))
                        <a href="{{ $page->getSection('app_store_url') }}" class="ll-btn ll-btn--store" target="_blank" rel="noopener">
                            <svg width="20" height="24" viewBox="0 0 384 512" fill="currentColor"><path d="M318.7 268.7c-.2-36.7 16.4-64.4 50-84.8-18.8-26.9-47.2-41.7-84.7-44.6-35.5-2.8-74.3 20.7-88.5 20.7-15 0-49.4-19.7-76.4-19.7C63.3 141.2 4 184.8 4 273.5c0 26.2 4.8 53.3 14.4 81.2 12.8 36.7 59 126.7 107.2 125.2 25.2-.6 43-17.9 75.8-17.9 31.8 0 48.3 17.9 76.4 17.9 48.6-.7 90.4-82.5 102.6-119.3-65.2-30.7-61.7-90-61.7-91.9zm-56.6-164.2c27.3-32.4 24.8-61.9 24-72.5-24.1 1.4-52 16.4-67.9 34.9-17.5 19.8-27.8 44.3-25.6 71.9 26.1 2 49.9-11.4 69.5-34.3z"/></svg>
                            App Store
                        </a>
                        @endif
                        @if($page->getSection('google_play_url'))
                        <a href="{{ $page->getSection('google_play_url') }}" class="ll-btn ll-btn--store" target="_blank" rel="noopener">
                            <svg width="20" height="22" viewBox="0 0 512 512" fill="currentColor"><path d="M325.3 234.3L104.6 13l280.8 161.2-60.1 60.1zM47 0C34 6.8 25.3 19.2 25.3 35.3v441.3c0 16.1 8.7 28.5 21.7 35.3l256.6-256L47 0zm425.2 225.6l-58.9-34.1-65.7 64.5 65.7 64.5 60.1-34.1c18-14.3 18-46.5-1.2-60.8zM104.6 499l280.8-161.2-60.1-60.1L104.6 499z"/></svg>
                            Google Play
                        </a>
                        @endif
                    </div>
                </div>
                <div class="ll-hero__mockup">
                    @if($page->getSection('hero_mockup_image'))
                    <img src="{{ $page->getSection('hero_mockup_image') }}" alt="{{ $page->hero_title ?? 'Lotto Live App' }}" width="300" height="600" loading="eager">
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{-- ===== FEATURES SECTION ===== --}}
    @if($page->getSection('features'))
    <section class="ll-features" id="features">
        <div class="container">
            <h2 class="ll-section-title">Tính năng nổi bật</h2>
            <div class="ll-features__grid">
                @foreach($page->getSection('features') as $feature)
                <div class="ll-card">
                    @if(!empty($feature['image']))
                    <div class="ll-card__img">
                        <img src="{{ $feature['image'] }}" alt="{{ $feature['title'] ?? '' }}" width="280" height="500" loading="lazy">
                    </div>
                    @endif
                    <div class="ll-card__icon">{{ $feature['icon'] ?? '⭐' }}</div>
                    <h3 class="ll-card__title">{{ $feature['title'] ?? '' }}</h3>
                    <p class="ll-card__desc">{{ $feature['text'] ?? '' }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- ===== HIGHLIGHT / FEATURE FOCUS ===== --}}
    @if($page->getSection('highlights'))
    <section class="ll-highlights">
        <div class="container">
            @foreach($page->getSection('highlights') as $i => $hl)
            <div class="ll-highlight {{ $i % 2 !== 0 ? 'll-highlight--reverse' : '' }}">
                <div class="ll-highlight__img">
                    @if(!empty($hl['image']))
                    <img src="{{ $hl['image'] }}" alt="{{ $hl['title'] ?? '' }}" width="300" height="600" loading="lazy">
                    @endif
                </div>
                <div class="ll-highlight__text">
                    <h2>{{ $hl['title'] ?? '' }}</h2>
                    <p>{{ $hl['description'] ?? '' }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </section>
    @endif

    {{-- ===== HOW IT WORKS ===== --}}
    @if($page->getSection('steps'))
    <section class="ll-steps" id="how-it-works">
        <div class="container">
            <h2 class="ll-section-title">Sử dụng dễ dàng</h2>
            <div class="ll-steps__grid">
                @foreach($page->getSection('steps') as $i => $step)
                <div class="ll-step">
                    <div class="ll-step__num">{{ $i + 1 }}</div>
                    <h3>{{ $step['title'] ?? '' }}</h3>
                    <p>{{ $step['text'] ?? '' }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- ===== SOCIAL PROOF ===== --}}
    @if($page->getSection('stats'))
    <section class="ll-stats">
        <div class="container">
            <div class="ll-stats__grid">
                @foreach($page->getSection('stats') as $stat)
                <div class="ll-stat">
                    <div class="ll-stat__num">{{ $stat['value'] ?? '' }}</div>
                    <div class="ll-stat__label">{{ $stat['label'] ?? '' }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- ===== BODY CONTENT (from admin editor) ===== --}}
    @if($page->description)
    <section class="ll-content">
        <div class="container">
            <div class="ll-content__body post-body">
                {!! $page->description !!}
            </div>
        </div>
    </section>
    @endif

    {{-- ===== FOOTER CTA ===== --}}
    <section class="ll-cta">
        <div class="container">
            <h2>{{ $page->getSection('cta_title') ?? 'Tải ngay Lotto Live' }}</h2>
            <p>{{ $page->getSection('cta_subtitle') ?? 'Miễn phí trên App Store & Google Play' }}</p>
            <div class="ll-hero__buttons">
                @if($page->getSection('app_store_url'))
                <a href="{{ $page->getSection('app_store_url') }}" class="ll-btn ll-btn--store" target="_blank" rel="noopener">App Store</a>
                @endif
                @if($page->getSection('google_play_url'))
                <a href="{{ $page->getSection('google_play_url') }}" class="ll-btn ll-btn--store" target="_blank" rel="noopener">Google Play</a>
                @endif
            </div>
        </div>
    </section>

</div>

@push('styles')
<style>
/* ===== BASE ===== */
.ll-page { font-family: 'Be Vietnam Pro', sans-serif; color: #fff; --blue: #0047AB; --purple: #6A0DAD; --glow: rgba(100, 100, 255, 0.5); }

/* ===== HERO ===== */
.ll-hero { position: relative; min-height: 90vh; display: flex; align-items: center; overflow: hidden; }
.ll-hero__bg { position: absolute; inset: 0; background: linear-gradient(135deg, var(--blue) 0%, var(--purple) 100%); z-index: 0; }
.ll-hero__particles { position: absolute; inset: 0; z-index: 1; pointer-events: none; }
.ll-hero .container { position: relative; z-index: 2; }
.ll-hero__grid { display: grid; grid-template-columns: 1fr 1fr; gap: 3rem; align-items: center; }
.ll-hero__title { font-size: 3.2rem; font-weight: 800; margin-bottom: 1rem; line-height: 1.15; }
.ll-hero__subtitle { font-size: 1.3rem; opacity: 0.9; margin-bottom: 2rem; line-height: 1.6; color: #E0E0E0; }
.ll-hero__buttons { display: flex; gap: 1rem; flex-wrap: wrap; }
.ll-hero__mockup { text-align: center; }
.ll-hero__mockup img { max-width: 300px; width: 100%; height: auto; filter: drop-shadow(0 20px 40px rgba(0,0,0,0.4)); transform: rotate(2deg); }

/* ===== BUTTONS ===== */
.ll-btn--store {
    display: inline-flex; align-items: center; gap: 0.5rem;
    padding: 0.85rem 1.8rem; border-radius: 50px; font-weight: 700; font-size: 1rem;
    color: #fff; text-decoration: none;
    background: rgba(255,255,255,0.12); border: 1px solid rgba(255,255,255,0.3);
    backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px);
    transition: all 0.3s;
}
.ll-btn--store:hover { background: rgba(255,255,255,0.25); box-shadow: 0 0 20px var(--glow); transform: translateY(-2px); color: #fff; }

/* ===== SECTION TITLE ===== */
.ll-section-title { text-align: center; font-size: 2.2rem; font-weight: 700; margin-bottom: 2.5rem; }

/* ===== FEATURES ===== */
.ll-features { padding: 5rem 0; background: linear-gradient(180deg, #0a0a2e 0%, #1a1a4e 100%); }
.ll-features__grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 2rem; }
.ll-card {
    background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.12);
    border-radius: 16px; padding: 2rem; text-align: center;
    backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);
    transition: transform 0.3s, box-shadow 0.3s;
}
.ll-card:hover { transform: translateY(-6px); box-shadow: 0 12px 40px rgba(100,100,255,0.2); }
.ll-card__img { margin-bottom: 1rem; }
.ll-card__img img { max-width: 180px; width: 100%; height: auto; border-radius: 12px; margin: 0 auto; display: block; }
.ll-card__icon { font-size: 2.5rem; margin-bottom: 0.75rem; }
.ll-card__title { font-size: 1.2rem; font-weight: 700; margin-bottom: 0.5rem; }
.ll-card__desc { font-size: 0.95rem; color: #E0E0E0; line-height: 1.6; }

/* ===== HIGHLIGHTS (Z-pattern) ===== */
.ll-highlights { padding: 5rem 0; background: #0d0d35; }
.ll-highlight { display: grid; grid-template-columns: 1fr 1fr; gap: 3rem; align-items: center; margin-bottom: 4rem; }
.ll-highlight--reverse { direction: rtl; }
.ll-highlight--reverse > * { direction: ltr; }
.ll-highlight__img { text-align: center; }
.ll-highlight__img img { max-width: 260px; width: 100%; height: auto; border-radius: 20px; filter: drop-shadow(0 10px 30px rgba(0,0,0,0.4)); }
.ll-highlight__text h2 { font-size: 1.8rem; font-weight: 700; margin-bottom: 1rem; }
.ll-highlight__text p { font-size: 1.05rem; color: #E0E0E0; line-height: 1.7; }

/* ===== STEPS ===== */
.ll-steps { padding: 5rem 0; background: linear-gradient(180deg, #1a1a4e 0%, #0a0a2e 100%); }
.ll-steps__grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 2rem; text-align: center; }
.ll-step__num {
    width: 48px; height: 48px; border-radius: 50%; margin: 0 auto 1rem;
    display: flex; align-items: center; justify-content: center;
    background: linear-gradient(135deg, var(--blue), var(--purple));
    font-size: 1.3rem; font-weight: 800;
}
.ll-step h3 { font-size: 1.1rem; margin-bottom: 0.5rem; }
.ll-step p { font-size: 0.95rem; color: #E0E0E0; line-height: 1.5; }

/* ===== STATS ===== */
.ll-stats { padding: 4rem 0; background: rgba(255,255,255,0.04); }
.ll-stats__grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 2rem; text-align: center; }
.ll-stat__num { font-size: 2.5rem; font-weight: 800; background: linear-gradient(135deg, #60a5fa, #a78bfa); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
.ll-stat__label { font-size: 0.95rem; color: #E0E0E0; margin-top: 0.3rem; }

/* ===== CONTENT ===== */
.ll-content { padding: 3rem 0; background: #0d0d35; }
.ll-content__body { max-width: 800px; margin: 0 auto; font-size: 1.05rem; line-height: 1.8; color: #E0E0E0; }
.ll-content__body img { max-width: 100%; height: auto; border-radius: 8px; margin: 1rem 0; display: block; }

/* ===== FOOTER CTA ===== */
.ll-cta { padding: 5rem 0; text-align: center; background: linear-gradient(135deg, var(--purple) 0%, var(--blue) 100%); }
.ll-cta h2 { font-size: 2.2rem; font-weight: 800; margin-bottom: 0.75rem; }
.ll-cta p { font-size: 1.1rem; color: #E0E0E0; margin-bottom: 2rem; }
.ll-cta .ll-hero__buttons { justify-content: center; }

/* ===== RESPONSIVE ===== */
@media (max-width: 768px) {
    .ll-hero { min-height: auto; padding: 4rem 0 3rem; }
    .ll-hero__grid { grid-template-columns: 1fr; text-align: center; }
    .ll-hero__title { font-size: 2rem; }
    .ll-hero__subtitle { font-size: 1.05rem; }
    .ll-hero__buttons { justify-content: center; }
    .ll-hero__mockup { order: -1; }
    .ll-hero__mockup img { max-width: 220px; }
    .ll-highlight { grid-template-columns: 1fr; text-align: center; }
    .ll-highlight--reverse { direction: ltr; }
    .ll-highlight__img img { max-width: 200px; }
    .ll-section-title { font-size: 1.6rem; }
    .ll-cta h2 { font-size: 1.6rem; }
}
</style>
@endpush

@push('scripts')
<script>
// Floating lottery balls animation
(function() {
    const c = document.getElementById('particles');
    if (!c) return;
    const balls = 12;
    for (let i = 0; i < balls; i++) {
        const el = document.createElement('div');
        const size = 20 + Math.random() * 40;
        Object.assign(el.style, {
            position: 'absolute',
            width: size + 'px', height: size + 'px',
            borderRadius: '50%',
            background: `rgba(255,255,255,${0.04 + Math.random() * 0.08})`,
            left: Math.random() * 100 + '%',
            top: Math.random() * 100 + '%',
            animation: `llFloat ${6 + Math.random() * 8}s ease-in-out infinite`,
            animationDelay: `-${Math.random() * 6}s`,
        });
        c.appendChild(el);
    }
    if (!document.getElementById('ll-keyframes')) {
        const s = document.createElement('style');
        s.id = 'll-keyframes';
        s.textContent = '@keyframes llFloat{0%,100%{transform:translateY(0) scale(1)}50%{transform:translateY(-30px) scale(1.1)}}';
        document.head.appendChild(s);
    }
})();
</script>
@endpush
@endsection
