@extends('layouts.master')

@section('page_title', $page_title ?? 'Source Game Marketplace - Unity, Unreal, Godot, HTML5 - Làm Game')
@section('page_description', $page_description ?? 'Marketplace source game với giá, engine, ảnh, demo, ngày cập nhật và nội dung gói tải được công khai theo từng sản phẩm.')

{{-- SEO: Canonical always points to /source-game (filter pages are variations, not unique) --}}
@section('canonical_url'){{ route('lamgame.source-game') }}@endsection

{{-- SEO: Noindex filter/sort/search pages (only main /source-game should be indexed) --}}
@push('meta')
@if(request()->hasAny(['cat', 'engine', 'genre', 'platform', 'pricing', 'search', 'sort']))
<meta name="robots" content="noindex, follow">
@endif
@endpush

@push('schema_markup')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "CollectionPage",
    "name": "Source Game Marketplace",
    "description": "Marketplace source game với thông tin sản phẩm và điều khoản công khai",
    "url": "{{ route('lamgame.source-game') }}",
    "isPartOf": {"@type": "WebSite","name": "Làm Game","url": "{{ url('/') }}"}
    @if(!empty($featuredSources) && count($featuredSources))
    ,"itemListElement": [
        @foreach(array_slice($featuredSources, 0, 10) as $i => $source)
        {"@type": "ListItem","position": {{ $i + 1 }},"url": "{{ route('lamgame.source-game.detail', $source['url_key'] ?? $source['id'] ?? '') }}"}@if($i < count(array_slice($featuredSources, 0, 10)) - 1),@endif
        @endforeach
    ]
    @endif
}
</script>
@endpush

@push('pagination_links')
@php
    $currentPage = $pagination['current_page'] ?? 1;
    $hasMore = $pagination['has_more'] ?? false;
    $baseUrl = route('lamgame.source-game');
@endphp
@if($currentPage > 1)
    <link rel="prev" href="{{ $currentPage == 2 ? $baseUrl : $baseUrl . '?page=' . ($currentPage - 1) }}">
@endif
@if($hasMore)
    <link rel="next" href="{{ $baseUrl . '?page=' . ($currentPage + 1) }}">
@endif
@endpush

@section('content')
<div class="sg-page">

{{-- HERO --}}
<section class="sg-hero">
    <div class="sg-hero__bg"></div>
    <div class="sg-container sg-hero__inner">
        <span class="sg-hero__badge">🎮 Source Game Marketplace</span>
        <h1 class="sg-hero__title">{{ __('lamgame.catalog.hero_title_1') }} <br><span class="sg-glow">{{ __('lamgame.catalog.hero_title_2') }}</span></h1>
        <p class="sg-hero__sub">{{ __('lamgame.catalog.hero_sub') }}</p>
        <form action="{{ route('lamgame.source-game') }}" method="GET" class="sg-search">
            <svg class="sg-search__icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('lamgame.catalog.search_ph') }}" class="sg-search__input">
            <button type="submit" class="sg-search__btn">{{ __('lamgame.catalog.search') }}</button>
        </form>
    </div>
</section>

{{-- TRUST NUMBERS --}}
<section class="sg-trust">
    <div class="sg-container">
        <div class="sg-trust__grid">
            <div class="sg-trust__item"><strong>{{ number_format($siteMetrics['published_sources'] ?? 0) }}</strong><span>{{ __('lamgame.catalog.stat_source') }}</span></div>
            <div class="sg-trust__item"><strong>{{ number_format($siteMetrics['registered_users'] ?? 0) }}</strong><span>{{ __('lamgame.catalog.stat_users') }}</span></div>
            <div class="sg-trust__item"><strong>{{ number_format($siteMetrics['total_orders'] ?? 0) }}</strong><span>{{ __('lamgame.catalog.stat_orders') }}</span></div>
            <div class="sg-trust__item"><strong>{{ $siteMetrics['job_listings'] ?? 0 }}</strong><span>{{ __('lamgame.catalog.stat_jobs') }}</span></div>
        </div>
    </div>
</section>

{{-- TRENDING SOURCE --}}
@if(!empty($trendingSources ?? []))
<section class="sg-sec">
    <div class="sg-container">
        <div class="sg-sec__head">
            <h2 class="sg-sec__title">{{ __('lamgame.catalog.buying_now') }}</h2>
            <a href="{{ route('lamgame.source-game', ['sort' => 'popular']) }}" class="sg-sec__link">{{ __('lamgame.catalog.view_all') }}</a>
        </div>
        <div class="sg-scroll">
            @foreach(($trendingSources ?? array_slice($featuredSources, 0, 4)) as $source)
            @include('lamgame.partials.source-card', ['source' => $source])
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- FEATURED SOURCES (was Best Selling - changed because orders < 10) --}}
@if(!empty($bestSellingSources ?? []))
<section class="sg-sec">
    <div class="sg-container">
        <div class="sg-sec__head">
            <h2 class="sg-sec__title">{{ __('lamgame.catalog.verified') }}</h2>
            <a href="{{ route('lamgame.source-game', ['sort' => 'featured']) }}" class="sg-sec__link">{{ __('lamgame.catalog.view_all') }}</a>
        </div>
        <div class="sg-scroll">
            @foreach(($bestSellingSources ?? array_slice($featuredSources, 0, 4)) as $source)
            @include('lamgame.partials.source-card', ['source' => $source])
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- FILTERS --}}
<section class="sg-filters">
    <div class="sg-container">
        <form action="{{ route('lamgame.source-game') }}" method="GET" class="sg-filters__form">
            <select name="engine" class="sg-select" onchange="this.form.submit()">
                <option value="">Engine</option>
                <option value="unity" {{ request('engine') == 'unity' ? 'selected' : '' }}>Unity 6</option>
                <option value="unreal" {{ request('engine') == 'unreal' ? 'selected' : '' }}>Unreal</option>
                <option value="godot" {{ request('engine') == 'godot' ? 'selected' : '' }}>Godot</option>
                <option value="cocos" {{ request('engine') == 'cocos' ? 'selected' : '' }}>Cocos</option>
            </select>
            <select name="genre" class="sg-select" onchange="this.form.submit()">
                <option value="">Genre</option>
                <option value="action" {{ request('genre') == 'action' ? 'selected' : '' }}>Action</option>
                <option value="puzzle" {{ request('genre') == 'puzzle' ? 'selected' : '' }}>Puzzle</option>
                <option value="rpg" {{ request('genre') == 'rpg' ? 'selected' : '' }}>RPG</option>
                <option value="casual" {{ request('genre') == 'casual' ? 'selected' : '' }}>Casual</option>
                <option value="multiplayer" {{ request('genre') == 'multiplayer' ? 'selected' : '' }}>Multiplayer</option>
            </select>
            <select name="platform" class="sg-select" onchange="this.form.submit()">
                <option value="">Platform</option>
                <option value="mobile" {{ request('platform') == 'mobile' ? 'selected' : '' }}>Mobile</option>
                <option value="pc" {{ request('platform') == 'pc' ? 'selected' : '' }}>PC</option>
                <option value="webgl" {{ request('platform') == 'webgl' ? 'selected' : '' }}>WebGL</option>
                <option value="cross" {{ request('platform') == 'cross' ? 'selected' : '' }}>Cross-platform</option>
            </select>
            <select name="pricing" class="sg-select" onchange="this.form.submit()">
                <option value="">Pricing</option>
                <option value="free" {{ request('pricing') == 'free' ? 'selected' : '' }}>{{ __('lamgame.catalog.free') }}</option>
                <option value="paid" {{ request('pricing') == 'paid' ? 'selected' : '' }}>{{ __('lamgame.catalog.paid') }}</option>
            </select>
            <select name="sort" class="sg-select" onchange="this.form.submit()">
                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>{{ __('lamgame.catalog.sort_newest') }}</option>
                <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>{{ __('lamgame.catalog.sort_popular') }}</option>
                <option value="price-asc" {{ request('sort') == 'price-asc' ? 'selected' : '' }}>{{ __('lamgame.catalog.sort_price_asc') }}</option>
                <option value="price-desc" {{ request('sort') == 'price-desc' ? 'selected' : '' }}>{{ __('lamgame.catalog.sort_price_desc') }}</option>
            </select>
            @if(request('search'))<input type="hidden" name="search" value="{{ request('search') }}">@endif
        </form>
    </div>
</section>

{{-- MARKETPLACE GRID --}}
<section class="sg-sec">
    <div class="sg-container">
        @if(request('search'))
        <p class="sg-results-info">{{ __('lamgame.catalog.results_for') }} "<strong>{{ request('search') }}</strong>"</p>
        @endif

        @if(count($featuredSources) > 0)
        <div class="sg-grid">
            @foreach($featuredSources as $source)
            @include('lamgame.partials.source-card', ['source' => $source])
            @endforeach
        </div>

        @if($pagination['has_more'] ?? false)
        <div class="sg-pager">
            @if($pagination['current_page'] > 1)
                <a href="{{ route('lamgame.source-game', array_merge(request()->query(), ['page' => $pagination['current_page'] - 1])) }}" class="sg-pager__btn">{{ __('lamgame.catalog.prev') }}</a>
            @endif
            <span class="sg-pager__info">Trang {{ $pagination['current_page'] }}</span>
            @if($pagination['has_more'])
                <a href="{{ route('lamgame.source-game', array_merge(request()->query(), ['page' => $pagination['current_page'] + 1])) }}" class="sg-pager__btn">{{ __('lamgame.catalog.next') }}</a>
            @endif
        </div>
        @endif
        @else
        <div class="sg-empty">
            <h3>{{ __('lamgame.catalog.empty_title') }}</h3>
            <p>{{ __('lamgame.catalog.back_later') }} <a href="{{ route('lamgame.lien-he') }}">{{ __('lamgame.catalog.contact') }}</a> {{ __('lamgame.catalog.empty_contribute') }}</p>
        </div>
        @endif
    </div>
</section>

{{-- TRUST SECTION --}}
<section class="sg-sec sg-sec--alt">
    <div class="sg-container">
        <h2 class="sg-sec__title" style="text-align:center;margin-bottom:32px">{{ __('lamgame.catalog.why_title') }}</h2>
        <div class="sg-why">
            <div class="sg-why__item"><span>🔎</span><h3>{{ __('lamgame.catalog.why_1_t') }}</h3><p>{{ __('lamgame.catalog.why_1_d') }}</p></div>
            <div class="sg-why__item"><span>🔐</span><h3>{{ __('lamgame.catalog.why_2_t') }}</h3><p>{{ __('lamgame.catalog.why_2_d') }}</p></div>
            <div class="sg-why__item"><span>📄</span><h3>{{ __('lamgame.catalog.why_3_t') }}</h3><p>{{ __('lamgame.catalog.why_3_d') }}</p></div>
            <div class="sg-why__item"><span>💬</span><h3>{{ __('lamgame.catalog.why_4_t') }}</h3><p>{{ __('lamgame.catalog.why_4_d') }}</p></div>
        </div>
    </div>
</section>

{{-- TESTIMONIALS - Only show if there are real orders/reviews --}}
@if(($siteMetrics['total_orders'] ?? 0) > 0)
<section class="sg-sec">
    <div class="sg-container">
        <h2 class="sg-sec__title" style="text-align:center;margin-bottom:32px">Developer nói gì?</h2>
        <div class="sg-testimonials">
            {{-- TODO: Load real testimonials from database when available --}}
        </div>
    </div>
</section>
@endif

{{-- SERVICES --}}
<section class="sg-sec sg-sec--alt">
    <div class="sg-container">
        <h2 class="sg-sec__title" style="text-align:center;margin-bottom:32px">Dịch vụ Game Development</h2>
        <div class="sg-services">
            <div class="sg-svc"><span class="sg-svc__icon">💻</span><h3>{{ __('lamgame.catalog.hire_t') }}</h3><p>{{ __('lamgame.catalog.hire_d') }}</p><a href="{{ route('lamgame.lien-he') }}" class="sg-svc__link">{{ __('lamgame.catalog.contact_arrow') }}</a></div>
            <div class="sg-svc"><span class="sg-svc__icon">💡</span><h3>{{ __('lamgame.catalog.idea_t') }}</h3><p>{{ __('lamgame.catalog.idea_d') }}</p><a href="{{ route('lamgame.lien-he') }}" class="sg-svc__link">{{ __('lamgame.catalog.idea_arrow') }}</a></div>
            <div class="sg-svc"><span class="sg-svc__icon">📦</span><h3>{{ __('lamgame.catalog.sell_t') }}</h3><p>{{ __('lamgame.catalog.sell_d') }}</p><a href="{{ route('lamgame.lien-he') }}" class="sg-svc__link">{{ __('lamgame.catalog.sell_arrow') }}</a></div>
        </div>
    </div>
</section>

{{-- FINAL CTA --}}
<section class="sg-cta">
    <div class="sg-container" style="text-align:center">
        <h2>{{ __('lamgame.catalog.sell_banner_t') }}</h2>
        <p>{{ __('lamgame.catalog.sell_banner_d') }}</p>
        <a href="{{ route('lamgame.lien-he') }}" class="sg-btn sg-btn--primary">{{ __('lamgame.catalog.sell_banner_cta') }}</a>
    </div>
</section>

{{-- INTERNAL LINKS — SEO: boost crawl for related pages --}}
<section class="sg-sec" style="padding:24px 0 40px">
    <div class="sg-container">
        <nav aria-label="Khám phá thêm">
            <h3 style="font-size:.85rem;color:#7A8599;margin-bottom:10px;font-weight:500">{{ __('lamgame.catalog.explore_more') }}</h3>
            <div style="display:flex;flex-wrap:wrap;gap:8px">
                <a href="{{ route('lamgame.blog') }}" class="sg-tag">📝 Blog Game Dev</a>
                <a href="{{ route('lamgame.viec-lam-game') }}" class="sg-tag">{{ __('lamgame.catalog.link_jobs') }}</a>
                <a href="{{ route('forum.index') }}" class="sg-tag">💬 Forum</a>
                <a href="{{ route('lamgame.ai-tools') }}" class="sg-tag">🤖 AI Tools</a>
                <a href="{{ route('mini-game.index') }}" class="sg-tag">{{ __('lamgame.catalog.link_play') }}</a>
                <a href="{{ route('lamgame.thue-team-dev') }}" class="sg-tag">{{ __('lamgame.catalog.link_hire') }}</a>
                <a href="/khoa-hoc/unity" class="sg-tag">{{ __('lamgame.catalog.link_unity') }}</a>
                <a href="/khoa-hoc/unreal" class="sg-tag">{{ __('lamgame.catalog.link_unreal') }}</a>
                <a href="{{ route('seller.register') }}" class="sg-tag">🏪 Đăng ký Seller</a>
                <a href="{{ route('employer.register') }}" class="sg-tag">🏢 Đăng tuyển dụng</a>
            </div>
        </nav>
    </div>
</section>

</div>
@endsection

@push('styles')
<style>.sg-page{background:#070B14;min-height:100vh}</style>
<link rel="preload" href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@600;700;800&display=swap" as="style" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@600;700;800&display=swap" rel="stylesheet"></noscript>
<link rel="stylesheet" href="{{ asset('css/source-game.css') }}">
@endpush


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    window.trackRevenueEvent?.('view_item_list', {
        item_list_id: 'source_game_marketplace',
        visible_items: document.querySelectorAll('[data-source-card]').length
    }, 'source-list-{{ request('page', 1) }}');

    document.querySelectorAll('[data-source-card]').forEach(function (card) {
        card.addEventListener('click', function () {
            window.trackRevenueEvent?.('select_item', {
                item_list_id: 'source_game_marketplace',
                items: [{
                    item_id: card.dataset.productId,
                    item_name: card.dataset.productName,
                    price: Number(card.dataset.price || 0)
                }]
            });
        });
    });
});
</script>
@endpush
