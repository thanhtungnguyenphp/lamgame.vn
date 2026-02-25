@extends('layouts.master')

@section('page_title', $page_title)
@section('page_description', $page_description)
@section('og_type', 'website')
@section('og_image', $page->og_image_url)
@section('twitter_card', 'summary_large_image')

@section('content')
    {{-- Hero --}}
    <section class="lp-hero lp-hero--minigame" style="
        @if($page->hero_bg_image_url) background-image: url('{{ $page->hero_bg_image_url }}'); @endif
        @if($page->hero_bg_color) background-color: {{ $page->hero_bg_color }}; @endif
    ">
        <div class="lp-hero__overlay"></div>
        <div class="container">
            <div class="lp-hero__content">
                @if($page->hero_title)
                    <h1 class="lp-hero__title">🎮 {{ $page->hero_title }}</h1>
                @endif
                @if($page->hero_subtitle)
                    <p class="lp-hero__subtitle">{{ $page->hero_subtitle }}</p>
                @endif

                {{-- Prize info from sections --}}
                @if($page->getSection('prizes'))
                <div class="lp-prizes">
                    @foreach($page->getSection('prizes') as $prize)
                    <div class="lp-prize">
                        <div class="lp-prize__rank">{{ $prize['rank'] ?? '' }}</div>
                        <div class="lp-prize__value">{{ $prize['value'] ?? '' }}</div>
                    </div>
                    @endforeach
                </div>
                @endif

                @if($page->hero_cta_text && $page->hero_cta_url)
                    <a href="{{ $page->hero_cta_url }}" class="lp-hero__cta lp-hero__cta--bounce">{{ $page->hero_cta_text }}</a>
                @endif
            </div>
        </div>
    </section>

    {{-- Dynamic Sections --}}
    @if($page->sections)
        @foreach($page->sections as $key => $section)
            @if($key !== 'prizes')
                @include('lamgame.landing.partials.section-block', ['section' => $section])
            @endif
        @endforeach
    @endif

    {{-- Body Content --}}
    @if($page->description)
    <section class="lp-content">
        <div class="container">
            <div class="lp-content__body post-body">
                {!! $page->description !!}
            </div>
        </div>
    </section>
    @endif

    @push('styles')
    <style>
        .lp-hero--minigame { min-height: 500px; background-color: #0f0c29; }
        .lp-hero--minigame .lp-hero__overlay { background: linear-gradient(135deg, rgba(15,12,41,0.6), rgba(48,43,99,0.5), rgba(36,36,62,0.6)); }
        .lp-hero--minigame .lp-hero__title { font-size: 2.5rem; }

        /* Prizes */
        .lp-prizes { display: flex; justify-content: center; gap: 1.5rem; margin: 2rem 0; flex-wrap: wrap; }
        .lp-prize {
            background: rgba(255,255,255,0.1); backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.2); border-radius: 16px;
            padding: 1.5rem 2rem; text-align: center; min-width: 140px;
        }
        .lp-prize__rank { font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; opacity: 0.7; margin-bottom: 0.5rem; }
        .lp-prize__value { font-size: 1.5rem; font-weight: 800; color: #ffd700; }

        /* Bouncing CTA */
        .lp-hero__cta--bounce { animation: bounce 2s infinite; }
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-8px); }
        }

        .lp-content { padding: 3rem 0; }
        .lp-content__body {
            max-width: 800px; margin: 0 auto;
            font-size: 1.1rem; line-height: 1.8; color: #333;
        }
        .lp-content__body img { max-width: 100%; height: auto; border-radius: 8px; margin: 1rem 0; display: block; }
        .lp-content__body table { width: 100%; border-collapse: collapse; margin: 1.5rem 0; display: block; overflow-x: auto; }
        .lp-content__body table th, .lp-content__body table td { padding: 0.75rem 1rem; border: 1px solid #dee2e6; }
        .lp-content__body table th { background: #6a4c93; color: #fff; }

        .lp-section { padding: 3rem 0; }
        .lp-section--alt { background: #f8f6fb; }
        .lp-section__title { text-align: center; font-size: 2rem; font-weight: 700; color: #2c3e50; margin-bottom: 2rem; }

        @media (max-width: 768px) {
            .lp-hero--minigame { min-height: 400px; }
            .lp-hero--minigame .lp-hero__title { font-size: 1.6rem; }
            .lp-prizes { gap: 0.75rem; }
            .lp-prize { min-width: 100px; padding: 1rem; }
            .lp-prize__value { font-size: 1.1rem; }
        }
    </style>
    @endpush
@endsection
