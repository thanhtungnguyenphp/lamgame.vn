@extends('layouts.master')

@section('page_title', $page_title)
@section('page_description', $page_description)
@section('og_type', 'website')
@section('og_image', $page->og_image_url)
@section('twitter_card', 'summary_large_image')

@push('meta')
    <script type="application/ld+json">
    {!! \App\Helpers\StructuredDataHelper::breadcrumb([
        ['name' => 'Trang chủ', 'url' => config('app.url')],
        ['name' => $page->name, 'url' => $page->url]
    ]) !!}
    </script>
@endpush

@section('content')
    {{-- Hero Section --}}
    <section class="lp-hero lp-hero--{{ $page->template }}" style="
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
                @if($page->hero_cta_text && $page->hero_cta_url)
                    <a href="{{ $page->hero_cta_url }}" class="lp-hero__cta">{{ $page->hero_cta_text }}</a>
                @endif
            </div>
        </div>
    </section>

    {{-- Dynamic Sections from JSON --}}
    @if($page->sections)
        @foreach($page->sections as $section)
            @include('lamgame.landing.partials.section-block', ['section' => $section])
        @endforeach
    @endif

    {{-- Body Content (TinyMCE) --}}
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
        /* Landing Page Base Styles */
        .lp-hero {
            position: relative;
            min-height: 400px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: #fff;
            background-size: cover;
            background-position: center;
            background-color: #6a4c93;
            overflow: hidden;
        }
        .lp-hero__overlay {
            position: absolute; inset: 0;
            background: rgba(0,0,0,0.4);
        }
        .lp-hero__content {
            position: relative; z-index: 2;
            max-width: 800px; margin: 0 auto;
            padding: 3rem 1rem;
        }
        .lp-hero__title {
            font-size: 2.8rem; font-weight: 800;
            margin-bottom: 1rem;
            text-shadow: 0 2px 8px rgba(0,0,0,0.3);
        }
        .lp-hero__subtitle {
            font-size: 1.3rem; opacity: 0.95;
            margin-bottom: 2rem; line-height: 1.6;
        }
        .lp-hero__cta {
            display: inline-block;
            padding: 1rem 2.5rem;
            background: #ff6b35;
            color: #fff; text-decoration: none;
            border-radius: 50px; font-weight: 700;
            font-size: 1.1rem;
            transition: transform 0.2s, box-shadow 0.2s;
            box-shadow: 0 4px 15px rgba(255,107,53,0.4);
        }
        .lp-hero__cta:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255,107,53,0.6);
        }

        /* Content Section */
        .lp-content {
            padding: 3rem 0;
        }
        .lp-content__body {
            max-width: 800px; margin: 0 auto;
            font-size: 1.1rem; line-height: 1.8; color: #333;
        }
        .lp-content__body img { max-width: 100%; height: auto; border-radius: 8px; margin: 1rem 0; display: block; }
        .lp-content__body table { width: 100%; border-collapse: collapse; margin: 1.5rem 0; display: block; overflow-x: auto; }
        .lp-content__body table th, .lp-content__body table td { padding: 0.75rem 1rem; border: 1px solid #dee2e6; }
        .lp-content__body table th { background: #6a4c93; color: #fff; }

        /* Section Blocks */
        .lp-section { padding: 3rem 0; }
        .lp-section--alt { background: #f8f6fb; }
        .lp-section__title {
            text-align: center; font-size: 2rem; font-weight: 700;
            color: #2c3e50; margin-bottom: 2rem;
        }
        .lp-section__text {
            max-width: 800px; margin: 0 auto;
            font-size: 1.1rem; line-height: 1.8; color: #555;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .lp-hero { min-height: 300px; }
            .lp-hero__title { font-size: 1.8rem; }
            .lp-hero__subtitle { font-size: 1rem; }
            .lp-hero__content { padding: 2rem 1rem; }
            .lp-content { padding: 2rem 0; }
        }
    </style>
    @endpush
@endsection
