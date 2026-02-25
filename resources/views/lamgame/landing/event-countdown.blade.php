@extends('layouts.master')

@section('page_title', $page_title)
@section('page_description', $page_description)
@section('og_type', 'website')
@section('og_image', $page->og_image_url)
@section('twitter_card', 'summary_large_image')

@push('meta')
    <script type="application/ld+json">
    {!! json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'Event',
        'name' => $page->hero_title ?? $page->name,
        'description' => $page->meta_description ?? $page->hero_subtitle,
        'startDate' => $page->start_at?->toIso8601String(),
        'endDate' => $page->end_at?->toIso8601String(),
        'image' => $page->og_image_url,
        'organizer' => ['@type' => 'Organization', 'name' => 'Làm Game', 'url' => config('app.url')],
        'eventAttendanceMode' => 'https://schema.org/OnlineEventAttendanceMode',
        'eventStatus' => 'https://schema.org/EventScheduled',
        'location' => ['@type' => 'VirtualLocation', 'url' => $page->url],
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
    </script>
@endpush

@section('content')
    {{-- Hero --}}
    <section class="ev-hero" style="
        @if($page->hero_bg_image_url) background-image: url('{{ $page->hero_bg_image_url }}'); @endif
        @if($page->hero_bg_color) --hero-bg: {{ $page->hero_bg_color }}; @endif
    ">
        <div class="ev-hero__bg"></div>
        <div class="ev-hero__particles" aria-hidden="true"></div>
        <div class="container">
            <div class="ev-hero__inner">
                @if($page->end_at && $page->end_at->isFuture() && $page->start_at && $page->start_at->isPast())
                    <div class="ev-badge ev-badge--live">🔴 ĐANG DIỄN RA</div>
                @endif

                @if($page->hero_title)
                    <h1 class="ev-hero__title">{{ $page->hero_title }}</h1>
                @endif

                <div class="ev-hero__divider"></div>

                @if($page->hero_subtitle)
                    <p class="ev-hero__sub">{{ $page->hero_subtitle }}</p>
                @endif

                {{-- Countdown --}}
                @if($page->start_at && $page->start_at->isFuture())
                <div class="ev-cd" data-target="{{ $page->start_at->toIso8601String() }}">
                    <p class="ev-cd__label">Sự kiện bắt đầu sau</p>
                    <div class="ev-cd__grid">
                        <div class="ev-cd__box"><span class="ev-cd__num" id="cd-days">00</span><span class="ev-cd__unit">Ngày</span></div>
                        <div class="ev-cd__sep">:</div>
                        <div class="ev-cd__box"><span class="ev-cd__num" id="cd-hours">00</span><span class="ev-cd__unit">Giờ</span></div>
                        <div class="ev-cd__sep">:</div>
                        <div class="ev-cd__box"><span class="ev-cd__num" id="cd-mins">00</span><span class="ev-cd__unit">Phút</span></div>
                        <div class="ev-cd__sep">:</div>
                        <div class="ev-cd__box"><span class="ev-cd__num" id="cd-secs">00</span><span class="ev-cd__unit">Giây</span></div>
                    </div>
                </div>
                @endif

                @if($page->hero_cta_text && $page->hero_cta_url)
                    <a href="{{ $page->hero_cta_url }}" class="ev-cta">
                        <span>{{ $page->hero_cta_text }}</span>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </a>
                @endif
            </div>
        </div>
    </section>

    {{-- Sections --}}
    @if($page->sections)
        @foreach($page->sections as $section)
            @include('lamgame.landing.partials.section-block', ['section' => $section])
        @endforeach
    @endif

    {{-- Body --}}
    @if($page->description)
    <section class="lp-content">
        <div class="container">
            <div class="lp-content__body post-body">{!! $page->description !!}</div>
        </div>
    </section>
    @endif

    @push('styles')
    <style>
        /* ===== HERO ===== */
        .ev-hero {
            position: relative;
            min-height: 85vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: #fff;
            overflow: hidden;
            background-size: cover;
            background-position: center;
        }
        .ev-hero__bg {
            position: absolute; inset: 0; z-index: 1;
            background: var(--hero-bg, #0a0e27);
            background-image:
                radial-gradient(ellipse 80% 50% at 50% -20%, rgba(120,80,200,0.35), transparent),
                radial-gradient(ellipse 60% 40% at 80% 100%, rgba(255,107,53,0.15), transparent),
                radial-gradient(ellipse 50% 50% at 20% 80%, rgba(59,130,246,0.12), transparent);
        }
        .ev-hero__particles {
            position: absolute; inset: 0; z-index: 2;
            background-image:
                radial-gradient(1px 1px at 10% 20%, rgba(255,255,255,0.4), transparent),
                radial-gradient(1px 1px at 30% 60%, rgba(255,255,255,0.3), transparent),
                radial-gradient(1.5px 1.5px at 50% 10%, rgba(255,255,255,0.5), transparent),
                radial-gradient(1px 1px at 70% 40%, rgba(255,255,255,0.3), transparent),
                radial-gradient(1px 1px at 90% 80%, rgba(255,255,255,0.4), transparent),
                radial-gradient(1.5px 1.5px at 15% 90%, rgba(255,255,255,0.3), transparent),
                radial-gradient(1px 1px at 85% 15%, rgba(255,255,255,0.35), transparent);
            animation: twinkle 4s ease-in-out infinite alternate;
        }
        @keyframes twinkle { 0% { opacity: 0.6; } 100% { opacity: 1; } }

        .ev-hero__inner {
            position: relative; z-index: 3;
            max-width: 750px; margin: 0 auto;
            padding: 4rem 1.5rem;
        }

        /* Badge */
        .ev-badge {
            display: inline-flex; align-items: center; gap: 0.4rem;
            padding: 0.5rem 1.5rem; border-radius: 50px;
            font-size: 0.8rem; font-weight: 700; letter-spacing: 1.5px;
            text-transform: uppercase; margin-bottom: 1.5rem;
        }
        .ev-badge--live {
            background: rgba(239,68,68,0.2);
            border: 1px solid rgba(239,68,68,0.5);
            color: #fca5a5;
            animation: livePulse 2s ease-in-out infinite;
        }
        @keyframes livePulse {
            0%, 100% { box-shadow: 0 0 0 0 rgba(239,68,68,0.4); }
            50% { box-shadow: 0 0 20px 4px rgba(239,68,68,0.2); }
        }

        /* Title */
        .ev-hero__title {
            font-size: 3.5rem; font-weight: 900;
            line-height: 1.1; margin: 0 0 1rem;
            letter-spacing: -0.02em;
            background: linear-gradient(135deg, #fff 0%, #c4b5fd 50%, #fbbf24 100%);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .ev-hero__divider {
            width: 80px; height: 3px; margin: 0 auto 1.5rem;
            background: linear-gradient(90deg, #8b5cf6, #f59e0b);
            border-radius: 2px;
        }

        .ev-hero__sub {
            font-size: 1.15rem; line-height: 1.7;
            color: rgba(255,255,255,0.85);
            margin: 0 0 2rem; max-width: 600px; margin-left: auto; margin-right: auto;
        }

        /* CTA */
        .ev-cta {
            display: inline-flex; align-items: center; gap: 0.6rem;
            padding: 1rem 2.5rem;
            background: linear-gradient(135deg, #f59e0b, #ef4444);
            color: #fff; text-decoration: none;
            border-radius: 50px; font-weight: 700; font-size: 1.05rem;
            box-shadow: 0 4px 25px rgba(245,158,11,0.4);
            transition: all 0.3s ease;
            position: relative; overflow: hidden;
        }
        .ev-cta::before {
            content: ''; position: absolute; inset: 0;
            background: linear-gradient(135deg, transparent 30%, rgba(255,255,255,0.2) 50%, transparent 70%);
            transform: translateX(-100%);
            transition: transform 0.6s;
        }
        .ev-cta:hover { transform: translateY(-3px); box-shadow: 0 8px 35px rgba(245,158,11,0.5); }
        .ev-cta:hover::before { transform: translateX(100%); }
        .ev-cta svg { transition: transform 0.3s; }
        .ev-cta:hover svg { transform: translateX(4px); }

        /* ===== COUNTDOWN ===== */
        .ev-cd { margin: 2.5rem 0; }
        .ev-cd__label {
            font-size: 0.8rem; text-transform: uppercase;
            letter-spacing: 2.5px; color: rgba(255,255,255,0.6);
            margin-bottom: 1rem;
        }
        .ev-cd__grid { display: flex; justify-content: center; align-items: center; gap: 0.75rem; }
        .ev-cd__box { text-align: center; }
        .ev-cd__num {
            display: flex; align-items: center; justify-content: center;
            font-size: 2.8rem; font-weight: 800; min-width: 85px; height: 85px;
            background: rgba(255,255,255,0.08);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255,255,255,0.15);
            border-radius: 16px;
            font-variant-numeric: tabular-nums;
        }
        .ev-cd__unit {
            display: block; font-size: 0.65rem; text-transform: uppercase;
            letter-spacing: 1.5px; margin-top: 0.6rem;
            color: rgba(255,255,255,0.5);
        }
        .ev-cd__sep {
            font-size: 2rem; font-weight: 700;
            color: rgba(255,255,255,0.3); padding-bottom: 1.8rem;
        }

        /* ===== SECTIONS ===== */
        .lp-section { padding: 4rem 0; }
        .lp-section--alt { background: #f8f6fb; }
        .lp-section__title {
            text-align: center; font-size: 1.8rem; font-weight: 800;
            color: #1e1b4b; margin-bottom: 2.5rem;
        }
        .lp-section__text {
            max-width: 900px; margin: 0 auto;
            font-size: 1.05rem; line-height: 1.8; color: #555;
        }

        /* ===== CONTENT ===== */
        .lp-content { padding: 4rem 0; }
        .lp-content__body {
            max-width: 800px; margin: 0 auto;
            font-size: 1.05rem; line-height: 1.8; color: #333;
        }
        .lp-content__body img { max-width: 100%; height: auto; border-radius: 8px; margin: 1rem 0; display: block; }
        .lp-content__body table { width: 100%; border-collapse: collapse; margin: 1.5rem 0; display: block; overflow-x: auto; }
        .lp-content__body table th, .lp-content__body table td { padding: 0.75rem 1rem; border: 1px solid #dee2e6; }
        .lp-content__body table th { background: #6a4c93; color: #fff; }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .ev-hero { min-height: 70vh; }
            .ev-hero__inner { padding: 3rem 1rem; }
            .ev-hero__title { font-size: 2rem; }
            .ev-hero__sub { font-size: 1rem; }
            .ev-cta { padding: 0.85rem 2rem; font-size: 0.95rem; }
            .ev-cd__num { font-size: 1.8rem; min-width: 60px; height: 60px; border-radius: 12px; }
            .ev-cd__grid { gap: 0.4rem; }
            .ev-cd__sep { font-size: 1.2rem; }
            .lp-section { padding: 3rem 0; }
            .lp-section__title { font-size: 1.4rem; }
        }

        @media (max-width: 400px) {
            .ev-hero__title { font-size: 1.6rem; }
            .ev-cd__num { font-size: 1.4rem; min-width: 48px; height: 48px; }
        }
    </style>
    @endpush

    @push('scripts')
    <script>
    (function() {
        var el = document.querySelector('.ev-cd');
        if (!el) return;
        var target = new Date(el.dataset.target).getTime();
        function tick() {
            var diff = target - Date.now();
            if (diff <= 0) {
                el.outerHTML = '<div class="ev-badge ev-badge--live" style="margin:2rem 0">🔴 ĐANG DIỄN RA</div>';
                return;
            }
            document.getElementById('cd-days').textContent = String(Math.floor(diff / 86400000)).padStart(2, '0');
            document.getElementById('cd-hours').textContent = String(Math.floor((diff % 86400000) / 3600000)).padStart(2, '0');
            document.getElementById('cd-mins').textContent = String(Math.floor((diff % 3600000) / 60000)).padStart(2, '0');
            document.getElementById('cd-secs').textContent = String(Math.floor((diff % 60000) / 1000)).padStart(2, '0');
            setTimeout(tick, 1000);
        }
        tick();
    })();
    </script>
    @endpush
@endsection
