@extends('layouts.master')

@section('page_title', $page_title)
@section('page_description', $page_description)
@section('og_image', $sourceGame['image'] ?? asset('assets/logos/png/logo-square-512.png'))

@push('meta')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "SoftwareSourceCode",
    "name": "{{ $sourceGame['title'] }}",
    "description": "{{ Str::limit(strip_tags($sourceGame['description'] ?? ''), 200) }}",
    "url": "{{ url()->current() }}",
    @if($sourceGame['image'] ?? null)"image": "{{ $sourceGame['image'] }}",@endif
    "programmingLanguage": "{{ $sourceGame['programming_language'] ?? '' }}",
    "offers": {
        "@type": "Offer",
        "price": "{{ $sourceGame['price'] ?? 0 }}",
        "priceCurrency": "VND",
        "availability": "https://schema.org/InStock"
    }
    @if(($sourceGame['rating'] ?? 0) > 0)
    ,"aggregateRating": {
        "@type": "AggregateRating",
        "ratingValue": "{{ $sourceGame['rating'] }}",
        "reviewCount": "{{ $sourceGame['review_count'] ?? 1 }}"
    }
    @endif
}
</script>
@endpush

@push('styles')
<style>
    .source-detail-container { max-width: 1200px; margin: 0 auto; padding: 20px; background: #fff; color: #1f2937; }
    
    /* Compact Header - như 3DOcean */
    .compact-header {
        display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap;
        padding: 16px 0; border-bottom: 1px solid #e5e7eb; margin-bottom: 24px; gap: 16px;
    }
    .compact-header-left { display: flex; align-items: center; gap: 24px; flex-wrap: wrap; }
    .compact-header-left a { color: #6a4c93; text-decoration: none; font-weight: 500; }
    .compact-header-left span { color: #6b7280; display: flex; align-items: center; gap: 6px; }
    .compact-header-right { display: flex; gap: 16px; }
    .action-link { color: #6b7280; text-decoration: none; display: flex; align-items: center; gap: 6px; transition: color 0.2s; cursor: pointer; }
    .action-link:hover { color: #6a4c93; }
    
    /* Tabs */
    .tabs { display: flex; gap: 0; border-bottom: 1px solid #e5e7eb; margin-bottom: 24px; }
    .tab { padding: 12px 20px; border: 1px solid #e5e7eb; border-bottom: none; background: #f9fafb; color: #6b7280; cursor: pointer; margin-right: -1px; }
    .tab.active { background: white; color: #1f2937; font-weight: 600; border-bottom: 1px solid white; margin-bottom: -1px; }
    
    /* Main Grid */
    .content-grid { display: grid; grid-template-columns: 1fr 340px; gap: 32px; }
    
    /* Gallery */
    .gallery-container { background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 4px; padding: 16px; margin-bottom: 16px; }
    .gallery-main { position: relative; }
    .gallery-main img { width: 100%; height: auto; max-height: 500px; object-fit: contain; }
    .more-images-btn { position: absolute; bottom: 16px; left: 50%; transform: translateX(-50%); background: rgba(0,0,0,0.7); color: white; padding: 8px 16px; border-radius: 4px; font-size: 0.9rem; cursor: pointer; display: flex; align-items: center; gap: 8px; }
    .gallery-thumbs { display: flex; gap: 8px; margin-top: 16px; overflow-x: auto; }
    .gallery-thumb { width: 80px; height: 60px; border: 2px solid transparent; cursor: pointer; flex-shrink: 0; }
    .gallery-thumb.active, .gallery-thumb:hover { border-color: #6a4c93; }
    .gallery-thumb img { width: 100%; height: 100%; object-fit: cover; }
    
    /* Quick Actions dưới gallery */
    .quick-actions { display: flex; gap: 12px; margin-bottom: 24px; }
    .quick-action-btn { flex: 1; padding: 12px; border: 1px solid #e5e7eb; background: white; border-radius: 4px; display: flex; align-items: center; justify-content: center; gap: 8px; cursor: pointer; color: #374151; transition: all 0.2s; }
    .quick-action-btn:hover { border-color: #6a4c93; color: #6a4c93; }
    
    /* Product Title */
    .product-title { font-size: 0.95rem; color: #6b7280; margin-bottom: 32px; }
    
    /* Sidebar */
    .sidebar { position: sticky; top: 20px; height: fit-content; }
    .sidebar-card { background: white; border: 1px solid #e5e7eb; border-radius: 4px; padding: 20px; margin-bottom: 16px; overflow: hidden; }
    
    /* Price Box */
    .price-header { display: flex; flex-direction: column; gap: 12px; margin-bottom: 16px; }
    .license-select { padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 4px; font-size: 0.9rem; align-self: flex-start; }
    .price-value { font-size: 1.75rem; font-weight: 700; color: #1f2937; word-break: break-word; }
    .price-value sup { font-size: 0.875rem; }
    .license-desc { font-size: 0.85rem; color: #6b7280; line-height: 1.5; margin-bottom: 16px; }
    .license-links { font-size: 0.85rem; margin-bottom: 20px; }
    .license-links a { color: #6a4c93; text-decoration: none; }
    
    /* CTA Buttons */
    .btn { width: 100%; padding: 14px; border-radius: 4px; font-weight: 600; font-size: 1rem; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; border: none; transition: all 0.2s; }
    .btn-cart { background: #82b440; color: white; margin-bottom: 8px; }
    .btn-cart:hover { background: #72a430; }
    .btn-buy { background: #6b7280; color: white; }
    .btn-buy:hover { background: #5b6270; }
    .price-note { font-size: 0.8rem; color: #9ca3af; text-align: center; margin-top: 12px; }
    
    /* Author Card */
    .author-card { display: flex; align-items: center; gap: 16px; margin-bottom: 16px; }
    .author-avatar { width: 64px; height: 64px; border-radius: 4px; background: linear-gradient(135deg, #6a4c93 0%, #9b59b6 100%); display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 1.5rem; flex-shrink: 0; }
    .author-name { font-weight: 600; color: #1f2937; margin-bottom: 4px; }
    .author-badges { display: flex; gap: 4px; flex-wrap: wrap; }
    .badge { width: 24px; height: 24px; border-radius: 50%; background: #e5e7eb; display: flex; align-items: center; justify-content: center; font-size: 0.7rem; }
    .btn-portfolio { width: 100%; padding: 10px; background: #f3f4f6; border: 1px solid #d1d5db; border-radius: 4px; color: #374151; cursor: pointer; }
    .btn-portfolio:hover { background: #e5e7eb; }
    
    /* Attributes */
    .attr-row { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #f3f4f6; font-size: 0.9rem; }
    .attr-row:last-child { border-bottom: none; }
    .attr-label { color: #6b7280; font-weight: 500; }
    .attr-value { color: #1f2937; font-weight: 600; }
    .more-attr-btn { width: 100%; padding: 10px; background: none; border: none; color: #6a4c93; cursor: pointer; font-weight: 500; margin-top: 8px; }
    .hidden-attrs { display: none; }
    .hidden-attrs.show { display: block; }
    
    /* Content Sections */
    .content-section { margin-bottom: 32px; }
    .section-title { font-size: 1.25rem; font-weight: 600; color: #1f2937; margin-bottom: 16px; display: flex; align-items: center; gap: 8px; }
    .description-content { line-height: 1.8; color: #374151; }
    .description-content p { margin-bottom: 1em; }
    .description-content img { max-width: 100%; height: auto; border-radius: 4px; }
    .description-content ul, .description-content ol { padding-left: 1.5em; margin-bottom: 1em; }
    .description-content a { color: #6a4c93; }
    .features-list { list-style: none; padding: 0; margin: 0; }
    .features-list li { padding: 8px 0; display: flex; align-items: center; gap: 10px; }
    .features-list li::before { content: '✓'; color: #10b981; font-weight: 600; }
    
    /* Related Items */
    .related-section { margin-top: 40px; padding-top: 32px; border-top: 1px solid #e5e7eb; }
    .related-title { font-size: 1.1rem; color: #1f2937; margin-bottom: 16px; }
    .related-grid { display: flex; gap: 16px; overflow-x: auto; padding-bottom: 8px; }
    .related-item { flex-shrink: 0; width: 120px; text-decoration: none; }
    .related-item img { width: 100%; height: 80px; object-fit: cover; border-radius: 4px; margin-bottom: 8px; }
    .related-item-title { font-size: 0.8rem; color: #374151; line-height: 1.3; }
    
    /* Footer links */
    .footer-links { text-align: center; font-size: 0.85rem; color: #9ca3af; margin-top: 16px; }
    .footer-links a { color: #6a4c93; text-decoration: none; }
    
    /* Message */
    .cart-message { margin-top: 12px; padding: 10px; border-radius: 4px; font-size: 0.9rem; display: none; }
    
    /* Mobile */
    @media (max-width: 900px) {
        .content-grid { grid-template-columns: 1fr; }
        .sidebar { position: static; }
        .compact-header { flex-direction: column; align-items: flex-start; }
    }
</style>
@endpush

@section('content')
<div class="source-detail-container">
    <!-- Compact Header -->
    <div class="compact-header">
        <div class="compact-header-left">
            <span>Bởi
                @if($sourceGame['author_slug'])
                    <a href="{{ url('seller/' . $sourceGame['author_slug']) }}">{{ $sourceGame['author_name'] }}</a>
                @else
                    <a href="#">{{ $sourceGame['author_name'] }}</a>
                @endif
            </span>
            <span>💬 {{ $sourceGame['review_count'] }} Bình luận</span>
            <span>🛒 {{ number_format($sourceGame['downloads_count']) }} lượt mua</span>
        </div>
        <div class="compact-header-right">
            <span class="action-link" onclick="addToFavorites()"><i class="fa fa-heart"></i> Yêu thích</span>
            <span class="action-link" onclick="addToCollection()"><i class="fa fa-folder"></i> Bộ sưu tập</span>
        </div>
    </div>

    <!-- Tabs -->
    <div class="tabs">
        <div class="tab active" onclick="switchTab(this, 'tab-detail')">Chi tiết sản phẩm</div>
        <div class="tab" onclick="switchTab(this, 'tab-reviews')">Đánh giá ({{ $sourceGame['review_count'] ?? 0 }})</div>
    </div>

    <div class="content-grid">
        <!-- Main Content -->
        <div class="main-content" id="tab-detail">
            <!-- Gallery -->
            @if(count($sourceGame['images']) > 0)
            <div class="gallery-container">
                <div class="gallery-main">
                    <img id="main-image" src="{{ $sourceGame['images'][0]['url'] }}" alt="{{ $sourceGame['title'] }}">
                    @if(count($sourceGame['images']) > 1)
                    <div class="more-images-btn" onclick="openGallery()">
                        <i class="fa fa-image"></i> Xem thêm ảnh
                    </div>
                    @endif
                </div>
                @if(count($sourceGame['images']) > 1)
                <div class="gallery-thumbs">
                    @foreach($sourceGame['images'] as $index => $image)
                    <div class="gallery-thumb {{ $index == 0 ? 'active' : '' }}" onclick="changeMainImage('{{ $image['url'] }}', this)">
                        <img src="{{ $image['url'] }}" alt="">
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
            @endif

            <!-- Quick Actions -->
            <div class="quick-actions">
                <button class="quick-action-btn" onclick="addToFavorites()">
                    <i class="fa fa-heart-o"></i> Thêm yêu thích
                </button>
                <button class="quick-action-btn" onclick="addToCollection()">
                    <i class="fa fa-folder-o"></i> Thêm bộ sưu tập
                </button>
            </div>

            <!-- Product Title/Description -->
            <p class="product-title">{{ $sourceGame['description'] }}</p>

            <!-- Features -->
            @if(count($sourceGame['features']) > 0)
            <div class="content-section">
                <h3 class="section-title">Tính năng nổi bật</h3>
                <ul class="features-list">
                    @foreach($sourceGame['features'] as $feature)
                    <li>{{ $feature }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Video Demo -->
            @if(!empty($sourceGame['video_demo_url']))
            <div class="content-section">
                <h3 class="section-title">Video Demo</h3>
                <div style="position:relative; padding-bottom:56.25%; height:0; overflow:hidden; border-radius:8px;">
                    <iframe src="{{ $sourceGame['video_demo_url'] }}" style="position:absolute; top:0; left:0; width:100%; height:100%; border:none;" allowfullscreen loading="lazy"></iframe>
                </div>
            </div>
            @endif

            <!-- Full Description -->
            @if($sourceGame['full_description'])
            <div class="content-section">
                <h3 class="section-title">Mô tả chi tiết</h3>
                <div class="description-content">{!! strip_tags($sourceGame['full_description'], '<p><br><strong><b><em><i><ul><ol><li><a><h1><h2><h3><h4><h5><h6><img><table><tr><td><th><thead><tbody><blockquote><pre><code><span><div><hr>') !!}</div>
            </div>
            @endif

            <!-- Requirements -->
            @if($sourceGame['requirements'])
            <div class="content-section">
                <h3 class="section-title">Yêu cầu hệ thống</h3>
                <p>{{ $sourceGame['requirements'] }}</p>
            </div>
            @endif

            <!-- Related Sources -->
            @if(count($relatedSources) > 0)
            <div class="related-section">
                <h3 class="related-title">Sản phẩm khác của {{ $sourceGame['author_name'] }}</h3>
                <div class="related-grid">
                    @foreach($relatedSources as $source)
                    <a href="{{ $source['url'] }}" class="related-item">
                        <img src="{{ $source['image'] }}" alt="{{ $source['title'] }}">
                        <div class="related-item-title">{{ Str::limit($source['title'], 40) }}</div>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- Reviews Tab (hidden by default) -->
        <div class="main-content" id="tab-reviews" style="display:none;">
            @include('lamgame.partials.source-game-reviews', ['productId' => $sourceGame['id']])
        </div>

        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Price & Purchase -->
            <div class="sidebar-card">
                <div class="price-header">
                    <select class="license-select">
                        <option>Giấy phép thường</option>
                    </select>
                    <div class="price-value">
                        @if($sourceGame['is_free'])
                            Miễn phí
                        @else
                            {{ number_format($sourceGame['price']) }}<sup>đ</sup>
                        @endif
                    </div>
                </div>
                
                <p class="license-desc">Sử dụng cho một dự án cá nhân hoặc thương mại. Giá đã bao gồm source code và tài liệu.</p>
                
                <div class="license-links">
                    <a href="#">Chi tiết giấy phép</a> | <a href="#">Tại sao mua tại LamGame?</a>
                </div>

                @if(!$sourceGame['is_free'] && isset($sourceGame['id']) && !str_starts_with($sourceGame['id'], 'sample-'))
                @php
                    $downloadableLinks = \DB::table('product_downloadable_links')->where('product_id', $sourceGame['id'])->pluck('id')->toArray();
                @endphp
                <form id="add-to-cart-form">
                    <input type="hidden" name="product_id" value="{{ $sourceGame['id'] }}">
                    <input type="hidden" name="quantity" value="1">
                    @foreach($downloadableLinks as $linkId)
                        <input type="hidden" name="links[]" value="{{ $linkId }}">
                    @endforeach
                    
                    <button type="button" id="btn-add-cart" class="btn btn-cart">
                        <i class="fa fa-cart-plus"></i> Thêm vào giỏ hàng
                    </button>
                    <button type="button" id="btn-buy-now" class="btn btn-buy">
                        Mua ngay
                    </button>
                </form>
                @if(!empty($sourceGame['demo_url']))
                <a href="{{ $sourceGame['demo_url'] }}" target="_blank" class="btn btn-demo" style="display:inline-block; margin-top:8px; background:#10b981; color:#fff; padding:10px 20px; border-radius:6px; text-decoration:none;">
                    <i class="fa fa-play-circle"></i> Xem Demo
                </a>
                @endif
                <p class="price-note">Giá chưa bao gồm thuế VAT</p>
                <div id="cart-message" class="cart-message"></div>
                @elseif($sourceGame['is_free'])
                @php
                    $freeLinks = \DB::table('product_downloadable_links')->where('product_id', $sourceGame['id'])->pluck('id')->toArray();
                @endphp
                <form id="add-to-cart-form">
                    <input type="hidden" name="product_id" value="{{ $sourceGame['id'] }}">
                    <input type="hidden" name="quantity" value="1">
                    @foreach($freeLinks as $linkId)
                        <input type="hidden" name="links[]" value="{{ $linkId }}">
                    @endforeach
                    <button type="button" id="btn-add-cart" class="btn btn-cart" onclick="addToCart(false, this)">
                        <i class="fa fa-download"></i> Tải về miễn phí
                    </button>
                </form>
                <div id="cart-message" class="cart-message"></div>
                @endif
            </div>

            <!-- Author -->
            <div class="sidebar-card">
                <div class="author-card">
                    @if($sourceGame['author_logo'])
                        <img src="{{ $sourceGame['author_logo'] }}" alt="{{ $sourceGame['author_name'] }}" style="width:64px;height:64px;border-radius:4px;object-fit:cover;flex-shrink:0;">
                    @else
                        <div class="author-avatar">{{ mb_strtoupper(mb_substr($sourceGame['author_name'], 0, 1)) }}</div>
                    @endif
                    <div>
                        <div class="author-name">{{ $sourceGame['author_name'] }}</div>
                        @if($sourceGame['author_verified'])
                            <div class="author-badges"><span class="badge" title="Đã xác minh">✓</span></div>
                        @endif
                    </div>
                </div>
                @if($sourceGame['author_slug'])
                    <a href="{{ url('seller/' . $sourceGame['author_slug']) }}" class="btn-portfolio" style="display:block;text-align:center;text-decoration:none;">Xem Portfolio</a>
                @else
                    <button class="btn-portfolio">Xem Portfolio</button>
                @endif
            </div>

            <!-- Attributes -->
            <div class="sidebar-card">
                <div class="attr-row">
                    <span class="attr-label">Cập nhật</span>
                    <span class="attr-value">{{ $sourceGame['last_updated'] }}</span>
                </div>
                <div class="hidden-attrs" id="more-attrs">
                    <div class="attr-row">
                        <span class="attr-label">Game Engine</span>
                        <span class="attr-value">{{ $sourceGame['engine'] }}</span>
                    </div>
                    <div class="attr-row">
                        <span class="attr-label">Ngôn ngữ</span>
                        <span class="attr-value">{{ $sourceGame['language'] }}</span>
                    </div>
                    <div class="attr-row">
                        <span class="attr-label">Dung lượng</span>
                        <span class="attr-value">{{ $sourceGame['file_size'] }}</span>
                    </div>
                    <div class="attr-row">
                        <span class="attr-label">Phiên bản</span>
                        <span class="attr-value">{{ $sourceGame['version'] }}</span>
                    </div>
                    @if($sourceGame['rating'] > 0)
                    <div class="attr-row">
                        <span class="attr-label">Đánh giá</span>
                        <span class="attr-value">{{ $sourceGame['rating'] }}/5.0</span>
                    </div>
                    @endif
                </div>
                <button class="more-attr-btn" onclick="toggleAttrs(this)">
                    Xem thêm thông số <i class="fa fa-chevron-down"></i>
                </button>
            </div>

            <!-- Footer -->
            <div class="footer-links">
                © {{ $sourceGame['author_name'] }}<br>
                <a href="#">Liên hệ hỗ trợ</a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function switchTab(el, tabId) {
    document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
    el.classList.add('active');
    document.getElementById('tab-detail').style.display = tabId === 'tab-detail' ? '' : 'none';
    document.getElementById('tab-reviews').style.display = tabId === 'tab-reviews' ? '' : 'none';
    if (tabId === 'tab-reviews' && !window._reviewsLoaded) {
        loadReviews({{ $sourceGame['id'] }});
        window._reviewsLoaded = true;
    }
}

function loadReviews(productId) {
    fetch('/api/v1/source-game/' + productId + '/review-stats')
        .then(r => r.json()).then(d => {
            if (d.data) renderStats(d.data);
        });
    fetch('/api/v1/source-game/' + productId + '/reviews?per_page=10')
        .then(r => r.json()).then(d => {
            if (d.data?.data) renderReviews(d.data.data);
        });
}

function renderStats(s) {
    const el = document.getElementById('review-stats');
    if (!el) return;
    let bars = '';
    for (let i = 5; i >= 1; i--) {
        const pct = s.total > 0 ? Math.round((s.distribution[i] || 0) / s.total * 100) : 0;
        bars += '<div class="rating-bar"><span>' + i + '★</span><div class="bar"><div class="bar-fill" style="width:' + pct + '%"></div></div><span>' + (s.distribution[i] || 0) + '</span></div>';
    }
    el.innerHTML = '<div class="rating-summary"><div class="rating-big">' + s.avg_rating + '<small>/5</small></div><div class="rating-count">' + s.total + ' đánh giá</div></div><div class="rating-bars">' + bars + '</div>';
}

function renderReviews(reviews) {
    const el = document.getElementById('review-list');
    if (!el) return;
    if (!reviews.length) { el.innerHTML = '<p class="no-reviews">Chưa có đánh giá nào.</p>'; return; }
    el.innerHTML = reviews.map(r => '<div class="review-item">' +
        '<div class="review-header"><strong>' + (r.customer?.first_name || 'Ẩn danh') + '</strong>' +
        (r.is_verified_purchase ? ' <span class="verified-badge">✓ Đã mua</span>' : '') +
        '<span class="review-date">' + new Date(r.created_at).toLocaleDateString('vi-VN') + '</span></div>' +
        '<div class="review-stars">' + '★'.repeat(r.rating) + '☆'.repeat(5 - r.rating) + '</div>' +
        (r.title ? '<div class="review-title">' + r.title + '</div>' : '') +
        '<p>' + r.content + '</p>' +
        (r.pros ? '<div class="review-pros">👍 ' + r.pros + '</div>' : '') +
        (r.cons ? '<div class="review-cons">👎 ' + r.cons + '</div>' : '') +
        '</div>').join('');
}

function submitReview(productId) {
    const form = document.getElementById('review-form');
    const data = Object.fromEntries(new FormData(form));
    data.rating = parseInt(data.rating);
    fetch('/api/v1/source-game/' + productId + '/reviews', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
        body: JSON.stringify(data)
    }).then(r => r.json()).then(d => {
        const msg = document.getElementById('review-message');
        if (d.status === 'success') {
            msg.innerHTML = '<div style="color:#16a34a">Đánh giá đã gửi, đang chờ duyệt!</div>';
            form.reset();
        } else {
            msg.innerHTML = '<div style="color:#dc2626">' + (d.message || 'Có lỗi xảy ra') + '</div>';
        }
    });
}

function changeMainImage(url, el) {
    document.getElementById('main-image').src = url;
    document.querySelectorAll('.gallery-thumb').forEach(t => t.classList.remove('active'));
    el.classList.add('active');
}

function toggleAttrs(btn) {
    const attrs = document.getElementById('more-attrs');
    attrs.classList.toggle('show');
    btn.innerHTML = attrs.classList.contains('show') 
        ? 'Thu gọn <i class="fa fa-chevron-up"></i>' 
        : 'Xem thêm thông số <i class="fa fa-chevron-down"></i>';
}

function addToFavorites() { alert('Tính năng đang phát triển'); }
function addToCollection() { alert('Tính năng đang phát triển'); }
function openGallery() { /* TODO: lightbox */ }

document.addEventListener('DOMContentLoaded', function() {
    const addCartBtn = document.getElementById('btn-add-cart');
    const buyNowBtn = document.getElementById('btn-buy-now');
    const messageDiv = document.getElementById('cart-message');
    
    function showMessage(text, isError = false) {
        if (!messageDiv) return;
        messageDiv.style.display = 'block';
        messageDiv.style.background = isError ? '#fef2f2' : '#f0fdf4';
        messageDiv.style.color = isError ? '#dc2626' : '#16a34a';
        messageDiv.innerHTML = text;
    }
    
    function addToCart(buyNow = false, btn = null) {
        const form = document.getElementById('add-to-cart-form');
        if (!form) return;
        
        const productId = form.querySelector('[name="product_id"]').value;
        const quantity = form.querySelector('[name="quantity"]').value;
        const links = Array.from(form.querySelectorAll('[name="links[]"]')).map(i => i.value);
        
        let originalText = '';
        if (btn) {
            originalText = btn.innerHTML;
            btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Đang xử lý...';
            btn.disabled = true;
        }
        
        fetch('{{ route("shop.api.checkout.cart.store") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
            body: JSON.stringify({ product_id: productId, quantity: quantity, is_buy_now: buyNow ? 1 : 0, links: links })
        })
        .then(r => r.json())
        .then(data => {
            if (btn) { btn.innerHTML = originalText; btn.disabled = false; }
            
            if (data.redirect) {
                window.location.href = data.redirect;
            } else if (data.message) {
                showMessage(data.message);
                // Redirect to cart after 1s if add to cart success
                if (!buyNow) setTimeout(() => window.location.href = '{{ route("shop.checkout.cart.index") }}', 1000);
            } else if (data.data?.message) {
                showMessage(data.data.message, true);
            }
        })
        .catch(() => {
            if (btn) { btn.innerHTML = originalText; btn.disabled = false; }
            showMessage('Có lỗi xảy ra. Vui lòng thử lại.', true);
        });
    }
    
    if (addCartBtn) addCartBtn.addEventListener('click', () => addToCart(false, addCartBtn));
    if (buyNowBtn) buyNowBtn.addEventListener('click', () => addToCart(true, buyNowBtn));
});
</script>
@endpush
