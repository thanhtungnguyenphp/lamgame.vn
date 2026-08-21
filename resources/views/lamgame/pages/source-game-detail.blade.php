@extends('layouts.master')

@section('page_title', $page_title)
@section('page_description', $page_description)
@section('og_image', $sourceGame['image'] ?? asset('assets/logos/png/logo-square-512.png'))
@section('twitter_card', 'summary_large_image')
@section('canonical_url', url()->current())

@push('og_extra')
<meta property="og:type" content="product">
<meta property="product:price:amount" content="{{ $sourceGame['price'] ?? 0 }}">
<meta property="product:price:currency" content="VND">
@endpush

@push('meta')
{{-- SoftwareSourceCode Schema --}}
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "SoftwareSourceCode",
    "name": "{{ $sourceGame['title'] }}",
    "description": "{{ addslashes(Str::limit(strip_tags($sourceGame['short_description'] ?? $sourceGame['description'] ?? ''), 300, '')) }}",
    "url": "{{ url()->current() }}",
    @if($sourceGame['image'] ?? null)"image": "{{ $sourceGame['image'] }}",@endif
    "codeRepository": "{{ $sourceGame['github_url'] ?? '' }}",
    "programmingLanguage": "{{ $sourceGame['language'] ?? 'C#' }}",
    "runtimePlatform": "{{ $sourceGame['engine'] ?? 'Multi-platform' }}",
    "applicationCategory": "GameApplication",
    "offers": {"@type": "Offer","price": "{{ $sourceGame['price'] ?? 0 }}","priceCurrency": "VND","availability": "https://schema.org/InStock"}
    @if(($sourceGame['rating'] ?? 0) > 0)
    ,"aggregateRating": {"@type": "AggregateRating","ratingValue": "{{ $sourceGame['rating'] }}","reviewCount": "{{ $sourceGame['review_count'] ?? 1 }}","bestRating": "5"}
    @endif
}
</script>

{{-- BreadcrumbList Schema --}}
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "BreadcrumbList",
    "itemListElement": [
        {"@type": "ListItem", "position": 1, "name": "Trang chủ", "item": "{{ url('/') }}"},
        {"@type": "ListItem", "position": 2, "name": "Source Game", "item": "{{ route('lamgame.source-game') }}"},
        {"@type": "ListItem", "position": 3, "name": "{{ Str::limit($sourceGame['title'], 50) }}", "item": "{{ url()->current() }}"}
    ]
}
</script>

{{-- FAQPage Schema (if has FAQ) --}}
@if(!empty($sourceGame['faq']))
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "FAQPage",
    "mainEntity": [
        @foreach($sourceGame['faq'] as $i => $qa)
        {"@type": "Question", "name": "{{ addslashes($qa['q']) }}", "acceptedAnswer": {"@type": "Answer", "text": "{{ addslashes($qa['a']) }}"}}@if(!$loop->last),@endif
        @endforeach
    ]
}
</script>
@endif
@endpush

@section('content')
<div class="sd-page">

{{-- BREADCRUMB --}}
<nav class="sd-breadcrumb" aria-label="Breadcrumb">
    <div class="sd-container">
        <a href="/">Trang chủ</a> <span>›</span>
        <a href="{{ route('lamgame.source-game') }}">Source Game</a> <span>›</span>
        <span class="sd-breadcrumb__current">{{ Str::limit($sourceGame['title'], 50) }}</span>
    </div>
</nav>

{{-- HERO PRODUCT --}}
<section class="sd-hero">
    <div class="sd-hero__bg"></div>
    <div class="sd-container sd-hero__inner">
        <div class="sd-breadcrumb">
            <a href="{{ url('/') }}">Trang chủ</a><span>/</span>
            <a href="{{ route('lamgame.source-game') }}">Source Game</a><span>/</span>
            <span>{{ Str::limit($sourceGame['title'], 40) }}</span>
        </div>
        <div class="sd-hero__grid">
            {{-- Gameplay Autoplay Preview --}}
            <div class="sd-gallery">
                @if(!empty($sourceGame['video_demo_url']))
                <div class="sd-gallery__video">
                    <video autoplay muted loop playsinline poster="{{ $sourceGame['images'][0]['url'] ?? '' }}">
                        @if(!empty($sourceGame['video_preview_mp4']))
                        <source src="{{ $sourceGame['video_preview_mp4'] }}" type="video/mp4">
                        @endif
                    </video>
                    <div class="sd-gallery__video-badge">▶ Gameplay Preview</div>
                </div>
                @endif
                @if(count($sourceGame['images']) > 0)
                <div class="sd-gallery__main">
                    <img id="main-image" src="{{ $sourceGame['images'][0]['url'] }}" alt="{{ $sourceGame['title'] }}">
                </div>
                @if(count($sourceGame['images']) > 1)
                <div class="sd-gallery__thumbs">
                    @foreach($sourceGame['images'] as $i => $img)
                    <div class="sd-thumb {{ $i == 0 ? 'active' : '' }}" onclick="changeMainImage('{{ $img['url'] }}', this)">
                        <img src="{{ $img['url'] }}" alt="">
                    </div>
                    @endforeach
                </div>
                @endif
                @endif
            </div>

            {{-- Product Info --}}
            <div class="sd-info">
                <div class="sd-info__badges">
                    @if(!empty($sourceGame['engine']))<span class="sd-badge">{{ $sourceGame['engine'] }}</span>@endif
                    @if($sourceGame['is_free'])<span class="sd-badge sd-badge--free">Free</span>@endif
                    @if(!empty($sourceGame['production_ready']))<span class="sd-badge sd-badge--prod">Production Ready</span>@endif
                </div>
                <h1 class="sd-info__title">{{ $sourceGame['title'] }}</h1>
                <p class="sd-info__desc">{{ $sourceGame['description'] }}</p>

                {{-- Trust signals --}}
                <div class="sd-trust">
                    @if($sourceGame['rating'] > 0)
                    <span class="sd-trust__item">⭐ {{ number_format($sourceGame['rating'], 1) }}/5</span>
                    @endif
                    <span class="sd-trust__item">↓ {{ number_format($sourceGame['downloads_count']) }} lượt tải</span>
                    <span class="sd-trust__item">💬 {{ $sourceGame['review_count'] ?? 0 }} đánh giá</span>
                    <span class="sd-trust__item">🔄 {{ $sourceGame['last_updated'] }}</span>
                </div>

                {{-- CTA Priority: #1 Demo > #2 Buy > #3 Save --}}
                <div class="sd-price-box">
                    @if(!empty($sourceGame['demo_url']))
                    <a href="{{ $sourceGame['demo_url'] }}" target="_blank" class="sd-btn sd-btn--demo sd-btn--pulse">🚀 Chơi thử Demo</a>
                    @endif

                    <div class="sd-price">
                        @if($sourceGame['is_free'])
                            <span class="sd-price__value sd-price__value--free">Miễn phí</span>
                        @else
                            <span class="sd-price__value">{{ number_format($sourceGame['price']) }}đ</span>
                        @endif
                    </div>

                    @if(!$sourceGame['is_free'] && isset($sourceGame['id']) && !str_starts_with($sourceGame['id'], 'sample-'))
                    @php $downloadableLinks = \DB::table('product_downloadable_links')->where('product_id', $sourceGame['id'])->pluck('id')->toArray(); @endphp
                    <form id="add-to-cart-form">
                        <input type="hidden" name="product_id" value="{{ $sourceGame['id'] }}">
                        <input type="hidden" name="quantity" value="1">
                        @foreach($downloadableLinks as $linkId)
                        <input type="hidden" name="links[]" value="{{ $linkId }}">
                        @endforeach
                        <button type="button" id="btn-add-cart" class="sd-btn sd-btn--primary">📦 Mua Source Code</button>
                        <button type="button" id="btn-buy-now" class="sd-btn sd-btn--secondary">Mua ngay</button>
                    </form>
                    @elseif($sourceGame['is_free'])
                    @php
                        $freeLink = \DB::table('product_downloadable_links')->where('product_id', $sourceGame['id'])->first();
                        $directUrl = $freeLink && $freeLink->type === 'url' ? $freeLink->url : null;
                    @endphp
                    @if($directUrl)
                    <a href="{{ $directUrl }}" target="_blank" rel="noopener" id="btn-add-cart" class="sd-btn sd-btn--primary">📥 Tải Source Code (Free)</a>
                    @else
                    <form id="add-to-cart-form">
                        <input type="hidden" name="product_id" value="{{ $sourceGame['id'] }}">
                        <input type="hidden" name="quantity" value="1">
                        @php $freeLinks = \DB::table('product_downloadable_links')->where('product_id', $sourceGame['id'])->pluck('id')->toArray(); @endphp
                        @foreach($freeLinks as $linkId)
                        <input type="hidden" name="links[]" value="{{ $linkId }}">
                        @endforeach
                        <button type="button" id="btn-add-cart" class="sd-btn sd-btn--primary">📦 Tải về miễn phí</button>
                    </form>
                    @endif
                    @endif
                    <button class="sd-btn sd-btn--save" onclick="addToFavorites()">❤ Lưu Source</button>
                    <div id="cart-message" class="sd-message"></div>
                </div>

                {{-- Author --}}
                <div class="sd-author">
                    @if(!empty($sourceGame['author_logo']))
                    <img src="{{ $sourceGame['author_logo'] }}" alt="{{ $sourceGame['author_name'] ?? '' }}" class="sd-author__avatar">
                    @else
                    <div class="sd-author__avatar sd-author__avatar--placeholder">{{ mb_strtoupper(mb_substr($sourceGame['author_name'] ?? 'U', 0, 1)) }}</div>
                    @endif
                    <div>
                        <span class="sd-author__name">{{ $sourceGame['author_name'] ?? 'Unknown' }} @if(!empty($sourceGame['author_verified']))✓@endif</span>
                        @if(!empty($sourceGame['author_slug']))
                        <a href="{{ url('seller/' . $sourceGame['author_slug']) }}" class="sd-author__link">Xem portfolio →</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- KEY FEATURES --}}
@if(count($sourceGame['features']) > 0)
<section class="sd-sec sd-fadein">
    <div class="sd-container">
        <h2 class="sd-sec__title">⚡ Tính năng nổi bật</h2>
        <div class="sd-features">
            @foreach($sourceGame['features'] as $feature)
            <div class="sd-feature"><span class="sd-feature__check">✓</span>{{ $feature }}</div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- TRUST PANEL --}}
<section class="sd-sec sd-sec--trust sd-fadein">
    <div class="sd-container">
        <h2 class="sd-sec__title">🛡️ Cam kết chất lượng</h2>
        <div class="sd-trust-panel">
            <div class="sd-trust-item"><span class="sd-trust-item__icon">✅</span><span>Production Ready</span></div>
            <div class="sd-trust-item"><span class="sd-trust-item__icon">✅</span><span>{{ $sourceGame['engine'] ? $sourceGame['engine'] . ' Compatible' : 'Ready to Use' }}</span></div>
            <div class="sd-trust-item"><span class="sd-trust-item__icon">✅</span><span>Clean Architecture</span></div>
            <div class="sd-trust-item"><span class="sd-trust-item__icon">✅</span><span>Mobile Optimized</span></div>
            <div class="sd-trust-item"><span class="sd-trust-item__icon">✅</span><span>Documentation Included</span></div>
            <div class="sd-trust-item"><span class="sd-trust-item__icon">✅</span><span>Fast Support</span></div>
        </div>
    </div>
</section>

{{-- VIDEO DEMO --}}
@if(!empty($sourceGame['video_demo_url']))
<section class="sd-sec sd-sec--alt">
    <div class="sd-container">
        <h2 class="sd-sec__title">🎬 Gameplay Preview</h2>
        <div class="sd-video">
            <iframe src="{{ $sourceGame['video_demo_url'] }}" allowfullscreen loading="lazy"></iframe>
        </div>
    </div>
</section>
@endif

{{-- TECHNICAL SPECS --}}
<section class="sd-sec">
    <div class="sd-container">
        <h2 class="sd-sec__title">🔧 Thông số kỹ thuật</h2>
        <div class="sd-specs">
            <div class="sd-spec"><span class="sd-spec__label">Game Engine</span><span class="sd-spec__value">{{ $sourceGame['engine'] }}</span></div>
            <div class="sd-spec"><span class="sd-spec__label">Ngôn ngữ</span><span class="sd-spec__value">{{ $sourceGame['language'] }}</span></div>
            <div class="sd-spec"><span class="sd-spec__label">Dung lượng</span><span class="sd-spec__value">{{ $sourceGame['file_size'] }}</span></div>
            <div class="sd-spec"><span class="sd-spec__label">Phiên bản</span><span class="sd-spec__value">{{ $sourceGame['version'] }}</span></div>
            <div class="sd-spec"><span class="sd-spec__label">Cập nhật</span><span class="sd-spec__value">{{ $sourceGame['last_updated'] }}</span></div>
            @if($sourceGame['requirements'])
            <div class="sd-spec"><span class="sd-spec__label">Yêu cầu</span><span class="sd-spec__value">{{ $sourceGame['requirements'] }}</span></div>
            @endif
        </div>
    </div>
</section>

{{-- FULL DESCRIPTION --}}
@if($sourceGame['full_description'])
<section class="sd-sec sd-sec--alt">
    <div class="sd-container">
        <h2 class="sd-sec__title">📖 Mô tả chi tiết</h2>
        <div class="sd-content">{!! strip_tags($sourceGame['full_description'], '<p><br><strong><b><em><i><ul><ol><li><a><h1><h2><h3><h4><h5><h6><img><table><tr><td><th><thead><tbody><blockquote><pre><code><span><div><hr>') !!}</div>
    </div>
</section>
@endif

{{-- REVIEWS --}}
<section class="sd-sec" id="reviews">
    <div class="sd-container">
        <h2 class="sd-sec__title">⭐ Đánh giá ({{ $sourceGame['review_count'] ?? 0 }})</h2>
        <div id="review-stats"></div>
        <div id="review-list"></div>
        @include('lamgame.partials.source-game-reviews', ['productId' => $sourceGame['id']])
    </div>
</section>

{{-- RELATED SOURCES --}}
@if(count($relatedSources) > 0)
<section class="sd-sec sd-sec--alt">
    <div class="sd-container">
        <h2 class="sd-sec__title">🎮 Source game liên quan</h2>
        <div class="sd-related">
            @foreach($relatedSources as $source)
            <a href="{{ $source['url'] }}" class="sd-related__card">
                <div class="sd-related__img"><img src="{{ $source['image'] }}" alt="{{ $source['title'] }}" loading="lazy"></div>
                <div class="sd-related__body">
                    <h3>{{ Str::limit($source['title'], 40) }}</h3>
                    <span class="sd-related__price">{{ ($source['price'] ?? 0) > 0 ? number_format($source['price']) . 'đ' : 'Miễn phí' }}</span>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- FAQ SECTION --}}
@if(!empty($sourceGame['faq']))
<section class="sd-sec">
    <div class="sd-container">
        <h2 class="sd-sec__title">❓ Câu hỏi thường gặp</h2>
        <div class="sd-faq">
            @foreach($sourceGame['faq'] as $qa)
            <details class="sd-faq__item">
                <summary class="sd-faq__q">{{ $qa['q'] }}</summary>
                <p class="sd-faq__a">{{ $qa['a'] }}</p>
            </details>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- FINAL CTA --}}
<section class="sd-cta">
    <div class="sd-container" style="text-align:center">
        <h2>Sẵn sàng tiết kiệm thời gian phát triển?</h2>
        <p>Source code production-ready, document đầy đủ, support sau mua</p>
        <div class="sd-cta__btns">
            @if($sourceGame['is_free'])
            <button onclick="document.getElementById('btn-add-cart').click()" class="sd-btn sd-btn--primary sd-btn--lg">Tải về miễn phí →</button>
            @else
            <button onclick="document.getElementById('btn-buy-now').click()" class="sd-btn sd-btn--primary sd-btn--lg">Mua ngay — {{ number_format($sourceGame['price']) }}đ</button>
            @endif
            <a href="{{ route('lamgame.source-game') }}" class="sd-btn sd-btn--ghost">← Xem thêm source khác</a>
        </div>
    </div>
</section>

</div>

{{-- STICKY MOBILE CTA --}}
<div class="sd-sticky-cta">
    <span class="sd-sticky-cta__price">{{ $sourceGame['is_free'] ? 'Miễn phí' : number_format($sourceGame['price']) . 'đ' }}</span>
    <button onclick="document.getElementById('btn-add-cart').click()" class="sd-btn sd-btn--primary sd-btn--sm">{{ $sourceGame['is_free'] ? 'Tải về' : 'Mua ngay' }}</button>
</div>
@endsection

@push('styles')
<link rel="preload" href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@600;700;800&display=swap" as="style" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@600;700;800&display=swap" rel="stylesheet"></noscript>
<link rel="stylesheet" href="{{ asset('css/source-detail.css') }}">
<style>
.sd-breadcrumb{padding:12px 0;font-size:.82rem;color:#5A6577}
.sd-breadcrumb a{color:#7A8599;text-decoration:none}
.sd-breadcrumb a:hover{color:#7C5CFF}
.sd-breadcrumb span{margin:0 4px}
.sd-breadcrumb__current{color:#B7C0D1}
.sd-faq{display:flex;flex-direction:column;gap:8px}
.sd-faq__item{background:rgba(17,24,39,.6);border:1px solid rgba(124,92,255,.08);border-radius:10px;overflow:hidden}
.sd-faq__item[open]{border-color:rgba(124,92,255,.2)}
.sd-faq__q{padding:14px 18px;cursor:pointer;font-weight:500;color:#F5F7FA;font-size:.92rem;list-style:none}
.sd-faq__q::-webkit-details-marker{display:none}
.sd-faq__q::before{content:'▸ ';color:#7C5CFF}
.sd-faq__item[open] .sd-faq__q::before{content:'▾ '}
.sd-faq__a{padding:0 18px 14px;color:#9CA3AF;font-size:.88rem;line-height:1.6}
</style>
@endpush

@push('scripts')
<script>
function changeMainImage(url, el) {
    document.getElementById('main-image').src = url;
    document.querySelectorAll('.sd-thumb').forEach(t => t.classList.remove('active'));
    el.classList.add('active');
}

document.addEventListener('DOMContentLoaded', function() {
    const addCartBtn = document.getElementById('btn-add-cart');
    const buyNowBtn = document.getElementById('btn-buy-now');
    const messageDiv = document.getElementById('cart-message');

    function showMessage(text, isError) {
        if (!messageDiv) return;
        messageDiv.style.display = 'block';
        messageDiv.className = 'sd-message ' + (isError ? 'sd-message--error' : 'sd-message--success');
        messageDiv.innerHTML = text;
    }

    function addToCart(buyNow, btn) {
        const form = document.getElementById('add-to-cart-form');
        if (!form) return;
        const productId = form.querySelector('[name="product_id"]').value;
        const quantity = form.querySelector('[name="quantity"]').value;
        const links = Array.from(form.querySelectorAll('[name="links[]"]')).map(i => i.value);
        const orig = btn.innerHTML;
        btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Đang xử lý...';
        btn.disabled = true;

        fetch('{{ route("shop.api.checkout.cart.store") }}', {
            method: 'POST',
            headers: {'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}','Accept':'application/json'},
            body: JSON.stringify({product_id: productId, quantity: quantity, is_buy_now: buyNow ? 1 : 0, links: links})
        }).then(r => r.json()).then(data => {
            btn.innerHTML = orig; btn.disabled = false;
            if (data.redirect) window.location.href = data.redirect;
            else if (data.message) { showMessage(data.message, false); if (!buyNow) setTimeout(() => window.location.href = '{{ route("shop.checkout.cart.index") }}', 1000); }
            else if (data.data?.message) showMessage(data.data.message, true);
        }).catch(() => { btn.innerHTML = orig; btn.disabled = false; showMessage('Có lỗi xảy ra.', true); });
    }

    if (addCartBtn) addCartBtn.addEventListener('click', () => addToCart(false, addCartBtn));
    if (buyNowBtn) buyNowBtn.addEventListener('click', () => addToCart(true, buyNowBtn));

    // Load reviews
    fetch('/api/v1/source-game/{{ $sourceGame["id"] }}/review-stats').then(r=>r.json()).then(d=>{
        if(d.data && d.data.total > 0) renderStats(d.data);
        else document.getElementById('review-stats').innerHTML='<p style="color:#7A8599;font-size:.9rem">⭐ Chưa có đánh giá. Hãy là người đầu tiên!</p>';
    }).catch(()=>{document.getElementById('review-stats').innerHTML='<p style="color:#7A8599;font-size:.9rem">⭐ Chưa có đánh giá.</p>';});
    fetch('/api/v1/source-game/{{ $sourceGame["id"] }}/reviews?per_page=10').then(r=>r.json()).then(d=>{if(d.data?.data)renderReviews(d.data.data)}).catch(()=>{});
});

function renderStats(s){const el=document.getElementById('review-stats');if(!el)return;let bars='';for(let i=5;i>=1;i--){const p=s.total>0?Math.round((s.distribution[i]||0)/s.total*100):0;bars+='<div class="sd-rbar"><span>'+i+'★</span><div class="sd-rbar__track"><div class="sd-rbar__fill" style="width:'+p+'%"></div></div><span>'+(s.distribution[i]||0)+'</span></div>';}el.innerHTML='<div class="sd-rating-summary"><div class="sd-rating-big">'+s.avg_rating+'<small>/5</small></div><div class="sd-rating-count">'+s.total+' đánh giá</div></div><div class="sd-rating-bars">'+bars+'</div>';}

function renderReviews(reviews){const el=document.getElementById('review-list');if(!el)return;if(!reviews.length){el.innerHTML='<p style="color:#7A8599">Chưa có đánh giá nào.</p>';return;}el.innerHTML=reviews.map(r=>'<div class="sd-review"><div class="sd-review__head"><strong>'+(r.customer?.first_name||'Ẩn danh')+'</strong>'+(r.is_verified_purchase?' <span class="sd-verified">✓ Đã mua</span>':'')+'<span class="sd-review__date">'+new Date(r.created_at).toLocaleDateString('vi-VN')+'</span></div><div class="sd-review__stars">'+'★'.repeat(r.rating)+'☆'.repeat(5-r.rating)+'</div>'+(r.title?'<div class="sd-review__title">'+r.title+'</div>':'')+'<p>'+r.content+'</p></div>').join('');}

// Fade-in sections on scroll
const observer = new IntersectionObserver((entries) => {
    entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('visible'); observer.unobserve(e.target); } });
}, { threshold: 0.1 });
document.querySelectorAll('.sd-fadein').forEach(el => observer.observe(el));

function addToFavorites() { alert('Đã lưu vào danh sách yêu thích!'); }
</script>
@endpush
