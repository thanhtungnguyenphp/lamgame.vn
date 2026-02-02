@extends('layouts.master')

@section('page_title', $page_title)
@section('page_description', $page_description)

@push('styles')
<style>
    .source-detail-container { max-width: 1200px; margin: 0 auto; padding: 20px; }
    
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
    .sidebar-card { background: white; border: 1px solid #e5e7eb; border-radius: 4px; padding: 20px; margin-bottom: 16px; }
    
    /* Price Box */
    .price-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 16px; }
    .license-select { padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 4px; font-size: 0.9rem; }
    .price-value { font-size: 2rem; font-weight: 700; color: #1f2937; }
    .price-value sup { font-size: 1rem; }
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
    .author-avatar { width: 64px; height: 64px; border-radius: 4px; background: linear-gradient(135deg, #6a4c93 0%, #9b59b6 100%); display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 1.5rem; }
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
    .features-list { list-style: none; padding: 0; margin: 0; }
    .features-list li { padding: 8px 0; display: flex; align-items: center; gap: 10px; }
    .features-list li::before { content: '✓'; color: #10b981; font-weight: 600; }
    
    /* Related Items */
    .related-section { margin-top: 40px; padding-top: 32px; border-top: 1px solid #e5e7eb; }
    .related-title { font-size: 1.1rem; color: #1f2937; margin-bottom: 16px; }
    .related-grid { display: flex; gap: 16px; overflow-x: auto; padding-bottom: 8px; }
    .related-item { flex-shrink: 0; width: 120px; }
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
            <span>Bởi <a href="#">{{ $sourceGame['author_name'] }}</a></span>
            <span><i class="fa fa-comment"></i> 0 Bình luận</span>
            <span><i class="fa fa-shopping-cart"></i> {{ number_format($sourceGame['downloads_count']) }} lượt mua</span>
        </div>
        <div class="compact-header-right">
            <span class="action-link" onclick="addToFavorites()"><i class="fa fa-heart"></i> Yêu thích</span>
            <span class="action-link" onclick="addToCollection()"><i class="fa fa-folder"></i> Bộ sưu tập</span>
        </div>
    </div>

    <!-- Tabs -->
    <div class="tabs">
        <div class="tab active">Chi tiết sản phẩm</div>
        <div class="tab">Bình luận</div>
    </div>

    <div class="content-grid">
        <!-- Main Content -->
        <div class="main-content">
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

            <!-- Full Description -->
            @if($sourceGame['full_description'])
            <div class="content-section">
                <h3 class="section-title">Mô tả chi tiết</h3>
                <div>{!! nl2br(e($sourceGame['full_description'])) !!}</div>
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
                <p class="price-note">Giá chưa bao gồm thuế VAT</p>
                <div id="cart-message" class="cart-message"></div>
                @elseif($sourceGame['is_free'])
                <a href="#download" class="btn btn-cart">
                    <i class="fa fa-download"></i> Tải về miễn phí
                </a>
                @endif
            </div>

            <!-- Author -->
            <div class="sidebar-card">
                <div class="author-card">
                    <div class="author-avatar">{{ strtoupper(substr($sourceGame['author_name'], 0, 1)) }}</div>
                    <div>
                        <div class="author-name">{{ $sourceGame['author_name'] }}</div>
                        <div class="author-badges">
                            <span class="badge" title="Verified">✓</span>
                        </div>
                    </div>
                </div>
                <button class="btn-portfolio">Xem Portfolio</button>
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
                    <div class="attr-row">
                        <span class="attr-label">Đánh giá</span>
                        <span class="attr-value">{{ $sourceGame['rating'] }}/5.0</span>
                    </div>
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
