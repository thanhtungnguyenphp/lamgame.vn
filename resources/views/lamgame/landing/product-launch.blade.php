@extends('layouts.master')

@section('page_title', $page_title)
@section('page_description', $page_description)
@section('og_type', 'website')
@section('og_image', $page->og_image_url)
@section('twitter_card', 'summary_large_image')

@section('content')
    {{-- Hero --}}
    <section class="lp-hero lp-hero--launch" style="
        @if($page->hero_bg_image_url) background-image: url('{{ $page->hero_bg_image_url }}'); @endif
        @if($page->hero_bg_color) background-color: {{ $page->hero_bg_color }}; @endif
    ">
        <div class="lp-hero__overlay"></div>
        <div class="container">
            <div class="lp-hero__content">
                @if($page->hero_title)
                    <h1 class="lp-hero__title">{{ $page->hero_title }}</h1>
                @endif
                @if($page->hero_subtitle)
                    <p class="lp-hero__subtitle">{{ $page->hero_subtitle }}</p>
                @endif

                {{-- Features from sections --}}
                @if($page->getSection('features'))
                <div class="lp-features">
                    @foreach($page->getSection('features') as $feature)
                    <div class="lp-feature">
                        <div class="lp-feature__icon">{{ $feature['icon'] ?? '🎯' }}</div>
                        <div class="lp-feature__text">{{ $feature['text'] ?? '' }}</div>
                    </div>
                    @endforeach
                </div>
                @endif

                @if($page->hero_cta_text && $page->hero_cta_url)
                    <a href="{{ $page->hero_cta_url }}" class="lp-hero__cta">{{ $page->hero_cta_text }}</a>
                @endif
            </div>
        </div>
    </section>

    {{-- Dynamic Sections --}}
    @if($page->sections)
        @foreach($page->sections as $key => $section)
            @if($key !== 'features')
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
        .lp-hero--launch { min-height: 500px; background-color: #0a1628; }
        .lp-hero--launch .lp-hero__overlay { background: linear-gradient(180deg, rgba(10,22,40,0.5) 0%, rgba(10,22,40,0.8) 100%); }
        .lp-hero--launch .lp-hero__title { font-size: 3rem; }

        /* Features */
        .lp-features { display: flex; justify-content: center; gap: 2rem; margin: 2rem 0; flex-wrap: wrap; }
        .lp-feature { text-align: center; max-width: 160px; }
        .lp-feature__icon { font-size: 2rem; margin-bottom: 0.5rem; }
        .lp-feature__text { font-size: 0.9rem; opacity: 0.9; }

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
            .lp-hero--launch { min-height: 400px; }
            .lp-hero--launch .lp-hero__title { font-size: 2rem; }
            .lp-features { gap: 1rem; }
            .lp-feature { max-width: 120px; }
        }
    </style>
    @endpush
@endsection
