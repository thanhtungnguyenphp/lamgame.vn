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
        <h1 class="sg-hero__title">Build game nhanh hơn với <br><span class="sg-glow">source code có thông tin rõ ràng</span></h1>
        <p class="sg-hero__sub">Giá, demo, thông số và trạng thái gói tải được công khai theo dữ liệu hiện có.</p>
        <form action="{{ route('lamgame.source-game') }}" method="GET" class="sg-search">
            <svg class="sg-search__icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Tìm source game, engine, genre..." class="sg-search__input">
            <button type="submit" class="sg-search__btn">Tìm kiếm</button>
        </form>
    </div>
</section>

{{-- TRUST NUMBERS --}}
<section class="sg-trust">
    <div class="sg-container">
        <div class="sg-trust__grid">
            <div class="sg-trust__item"><strong>{{ number_format($siteMetrics['published_sources'] ?? 0) }}</strong><span>Source công khai</span></div>
            <div class="sg-trust__item"><strong>{{ number_format($siteMetrics['registered_users'] ?? 0) }}</strong><span>Tài khoản hoạt động</span></div>
            <div class="sg-trust__item"><strong>{{ number_format($siteMetrics['total_orders'] ?? 0) }}</strong><span>Đơn hoàn tất</span></div>
            <div class="sg-trust__item"><strong>{{ $siteMetrics['job_listings'] ?? 0 }}</strong><span>Việc làm đang mở</span></div>
        </div>
    </div>
</section>

{{-- TRENDING SOURCE --}}
@if(!empty($trendingSources ?? []))
<section class="sg-sec">
    <div class="sg-container">
        <div class="sg-sec__head">
            <h2 class="sg-sec__title">🔥 Đang được mua</h2>
            <a href="{{ route('lamgame.source-game', ['sort' => 'popular']) }}" class="sg-sec__link">Xem tất cả →</a>
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
            <h2 class="sg-sec__title">✅ Source đã kiểm chứng</h2>
            <a href="{{ route('lamgame.source-game', ['sort' => 'featured']) }}" class="sg-sec__link">Xem tất cả →</a>
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
                <option value="free" {{ request('pricing') == 'free' ? 'selected' : '' }}>Miễn phí</option>
                <option value="paid" {{ request('pricing') == 'paid' ? 'selected' : '' }}>Trả phí</option>
            </select>
            <select name="sort" class="sg-select" onchange="this.form.submit()">
                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Mới nhất</option>
                <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Phổ biến</option>
                <option value="price-asc" {{ request('sort') == 'price-asc' ? 'selected' : '' }}>Giá thấp → cao</option>
                <option value="price-desc" {{ request('sort') == 'price-desc' ? 'selected' : '' }}>Giá cao → thấp</option>
            </select>
            @if(request('search'))<input type="hidden" name="search" value="{{ request('search') }}">@endif
        </form>
    </div>
</section>

{{-- MARKETPLACE GRID --}}
<section class="sg-sec">
    <div class="sg-container">
        @if(request('search'))
        <p class="sg-results-info">Kết quả cho "<strong>{{ request('search') }}</strong>"</p>
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
                <a href="{{ route('lamgame.source-game', array_merge(request()->query(), ['page' => $pagination['current_page'] - 1])) }}" class="sg-pager__btn">← Trước</a>
            @endif
            <span class="sg-pager__info">Trang {{ $pagination['current_page'] }}</span>
            @if($pagination['has_more'])
                <a href="{{ route('lamgame.source-game', array_merge(request()->query(), ['page' => $pagination['current_page'] + 1])) }}" class="sg-pager__btn">Tiếp →</a>
            @endif
        </div>
        @endif
        @else
        <div class="sg-empty">
            <h3>Chưa có source game nào</h3>
            <p>Hãy quay lại sau hoặc <a href="{{ route('lamgame.lien-he') }}">liên hệ</a> để đóng góp source game.</p>
        </div>
        @endif
    </div>
</section>

{{-- TRUST SECTION --}}
<section class="sg-sec sg-sec--alt">
    <div class="sg-container">
        <h2 class="sg-sec__title" style="text-align:center;margin-bottom:32px">Tại sao chọn LamGame Marketplace?</h2>
        <div class="sg-why">
            <div class="sg-why__item"><span>🔎</span><h3>Thông tin minh bạch</h3><p>Giá, engine, ngày cập nhật, demo và nội dung gói tải được hiển thị theo dữ liệu hiện có.</p></div>
            <div class="sg-why__item"><span>🔐</span><h3>Tải qua tài khoản</h3><p>File trả phí được cấp theo đơn hàng và giới hạn tải của người mua.</p></div>
            <div class="sg-why__item"><span>📄</span><h3>Điều khoản công khai</h3><p>Chính sách hoàn tiền, bảo mật và điều khoản Marketplace có thể xem trước khi mua.</p></div>
            <div class="sg-why__item"><span>💬</span><h3>Kênh hỗ trợ rõ ràng</h3><p>Gửi câu hỏi qua Forum hoặc email; phạm vi hỗ trợ cụ thể tùy từng sản phẩm.</p></div>
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
            <div class="sg-svc"><span class="sg-svc__icon">💻</span><h3>Thuê Game Developer</h3><p>Trao đổi phạm vi Unity, Unreal, Godot và nhận estimate theo dự án</p><a href="{{ route('lamgame.lien-he') }}" class="sg-svc__link">Liên hệ →</a></div>
            <div class="sg-svc"><span class="sg-svc__icon">💡</span><h3>Chia sẻ ý tưởng Game</h3><p>Đăng ý tưởng game, tìm đội ngũ phát triển cùng bạn</p><a href="{{ route('lamgame.lien-he') }}" class="sg-svc__link">Gửi ý tưởng →</a></div>
            <div class="sg-svc"><span class="sg-svc__icon">📦</span><h3>Đăng bán Source Game</h3><p>Bán source code game của bạn cho cộng đồng developer</p><a href="{{ route('lamgame.lien-he') }}" class="sg-svc__link">Đăng bán →</a></div>
        </div>
    </div>
</section>

{{-- FINAL CTA --}}
<section class="sg-cta">
    <div class="sg-container" style="text-align:center">
        <h2>Bạn có source game muốn bán?</h2>
        <p>Đăng ký seller, công khai thông tin sản phẩm và tiếp cận cộng đồng developer</p>
        <a href="{{ route('lamgame.lien-he') }}" class="sg-btn sg-btn--primary">Đăng bán source game →</a>
    </div>
</section>

{{-- INTERNAL LINKS — SEO: boost crawl for related pages --}}
<section class="sg-sec" style="padding:24px 0 40px">
    <div class="sg-container">
        <nav aria-label="Khám phá thêm">
            <h3 style="font-size:.85rem;color:#7A8599;margin-bottom:10px;font-weight:500">Khám phá thêm trên LamGame</h3>
            <div style="display:flex;flex-wrap:wrap;gap:8px">
                <a href="{{ route('lamgame.blog') }}" class="sg-tag">📝 Blog Game Dev</a>
                <a href="{{ route('lamgame.viec-lam-game') }}" class="sg-tag">💼 Việc làm Game</a>
                <a href="{{ route('forum.index') }}" class="sg-tag">💬 Forum</a>
                <a href="{{ route('lamgame.ai-tools') }}" class="sg-tag">🤖 AI Tools</a>
                <a href="{{ route('mini-game.index') }}" class="sg-tag">🕹️ Chơi Game Online</a>
                <a href="{{ route('lamgame.thue-team-dev') }}" class="sg-tag">👨‍💻 Thuê Team Dev</a>
                <a href="/khoa-hoc/unity" class="sg-tag">🎓 Khóa học Unity</a>
                <a href="/khoa-hoc/unreal" class="sg-tag">🎓 Khóa học Unreal</a>
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
