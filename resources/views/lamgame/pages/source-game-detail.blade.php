@extends('layouts.master')

@section('page_title', $page_title)
@section('page_description', $page_description)

@push('styles')
<style>
    /* Source Game Detail Styles */
    .source-detail-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }
    
    .source-header {
        background: white;
        border-radius: 12px;
        padding: 32px;
        margin-bottom: 32px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    }
    
    .source-title {
        font-size: 2.5rem;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 16px;
        line-height: 1.2;
    }
    
    .source-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 24px;
        margin-bottom: 24px;
        padding: 20px 0;
        border-top: 1px solid #e5e7eb;
        border-bottom: 1px solid #e5e7eb;
    }
    
    .meta-item {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #6b7280;
        font-size: 0.95rem;
    }
    
    .meta-item i {
        color: #6a4c93;
        font-size: 1.1rem;
    }
    
    .source-description {
        font-size: 1.1rem;
        line-height: 1.7;
        color: #4b5563;
        margin-bottom: 32px;
    }
    
    .source-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 16px;
    }
    
    .btn {
        padding: 14px 28px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 1rem;
        text-decoration: none;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    
    .btn-primary {
        background: #6a4c93;
        color: white;
    }
    
    .btn-primary:hover {
        background: #5a3c83;
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(106, 76, 147, 0.3);
    }
    
    .btn-outline {
        background: transparent;
        color: #6a4c93;
        border: 2px solid #6a4c93;
    }
    
    .btn-outline:hover {
        background: #6a4c93;
        color: white;
        transform: translateY(-2px);
    }
    
    .btn-secondary {
        background: #f3f4f6;
        color: #374151;
        border: 1px solid #d1d5db;
    }
    
    .btn-secondary:hover {
        background: #e5e7eb;
        transform: translateY(-1px);
    }
    
    .content-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 32px;
        margin-bottom: 32px;
    }
    
    .main-content {
        background: white;
        border-radius: 12px;
        padding: 32px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    }
    
    .sidebar {
        display: flex;
        flex-direction: column;
        gap: 24px;
    }
    
    .sidebar-card {
        background: white;
        border-radius: 12px;
        padding: 24px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    }
    
    .section-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .gallery {
        margin-bottom: 32px;
    }
    
    .gallery-main {
        margin-bottom: 16px;
    }
    
    .gallery-main img {
        width: 100%;
        height: 400px;
        object-fit: cover;
        border-radius: 8px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }
    
    .gallery-thumbs {
        display: flex;
        gap: 12px;
        overflow-x: auto;
        padding-bottom: 8px;
    }
    
    .gallery-thumb {
        flex-shrink: 0;
        width: 80px;
        height: 60px;
        border-radius: 6px;
        overflow: hidden;
        cursor: pointer;
        border: 2px solid transparent;
        transition: all 0.3s ease;
    }
    
    .gallery-thumb:hover,
    .gallery-thumb.active {
        border-color: #6a4c93;
        transform: scale(1.05);
    }
    
    .gallery-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .features-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .features-list li {
        padding: 12px 0;
        border-bottom: 1px solid #f3f4f6;
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 0.95rem;
        line-height: 1.5;
    }
    
    .features-list li:last-child {
        border-bottom: none;
    }
    
    .features-list li::before {
        content: '✓';
        background: #10b981;
        color: white;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: 600;
        flex-shrink: 0;
    }
    
    .tech-specs {
        display: grid;
        gap: 16px;
    }
    
    .spec-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid #f3f4f6;
    }
    
    .spec-row:last-child {
        border-bottom: none;
    }
    
    .spec-label {
        font-weight: 500;
        color: #6b7280;
        font-size: 0.9rem;
    }
    
    .spec-value {
        font-weight: 600;
        color: #1f2937;
        font-size: 0.9rem;
    }
    
    .price-info {
        text-align: center;
        padding: 24px;
        background: linear-gradient(135deg, #6a4c93 0%, #9b59b6 100%);
        color: white;
        border-radius: 12px;
        margin-bottom: 24px;
    }
    
    .price-label {
        font-size: 0.9rem;
        opacity: 0.9;
        margin-bottom: 8px;
    }
    
    .price-value {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 16px;
    }
    
    .price-note {
        font-size: 0.85rem;
        opacity: 0.8;
    }
    
    .author-info {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 20px;
        background: #f9fafb;
        border-radius: 8px;
        margin-bottom: 20px;
    }
    
    .author-avatar {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, #6a4c93 0%, #9b59b6 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 1.5rem;
        flex-shrink: 0;
    }
    
    .author-details h4 {
        margin: 0 0 4px 0;
        color: #1f2937;
        font-size: 1.1rem;
    }
    
    .author-details p {
        margin: 0;
        color: #6b7280;
        font-size: 0.9rem;
        line-height: 1.4;
    }
    
    .video-demo {
        margin-bottom: 32px;
    }
    
    .video-container {
        position: relative;
        width: 100%;
        height: 0;
        padding-bottom: 56.25%; /* 16:9 aspect ratio */
        overflow: hidden;
        border-radius: 8px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }
    
    .video-container iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border: 0;
        border-radius: 8px;
    }
    
    .tags {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 16px;
    }
    
    .tag {
        background: #f3f4f6;
        color: #374151;
        padding: 6px 12px;
        border-radius: 16px;
        font-size: 0.8rem;
        font-weight: 500;
        border: 1px solid #e5e7eb;
        transition: all 0.3s ease;
    }
    
    .tag:hover {
        background: #6a4c93;
        color: white;
        border-color: #6a4c93;
    }
    
    .related-sources {
        background: white;
        border-radius: 12px;
        padding: 32px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    }
    
    .sources-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 24px;
        margin-top: 24px;
    }
    
    .source-card {
        background: #f9fafb;
        border-radius: 8px;
        overflow: hidden;
        transition: all 0.3s ease;
        border: 1px solid #e5e7eb;
    }
    
    .source-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 30px rgba(0,0,0,0.15);
        border-color: #6a4c93;
    }
    
    .source-card img {
        width: 100%;
        height: 160px;
        object-fit: cover;
    }
    
    .source-card-content {
        padding: 16px;
    }
    
    .source-card h4 {
        margin: 0 0 8px 0;
        font-size: 1.1rem;
        font-weight: 600;
        color: #1f2937;
    }
    
    .source-card-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.85rem;
        color: #6b7280;
    }
    
    .rating {
        display: flex;
        align-items: center;
        gap: 4px;
        color: #fbbf24;
    }
    
    /* Mobile Responsive */
    @media (max-width: 768px) {
        .source-detail-container {
            padding: 16px;
        }
        
        .source-header {
            padding: 24px;
        }
        
        .source-title {
            font-size: 1.8rem;
        }
        
        .source-meta {
            flex-direction: column;
            gap: 12px;
            align-items: flex-start;
        }
        
        .source-actions {
            flex-direction: column;
        }
        
        .btn {
            text-align: center;
            justify-content: center;
        }
        
        .content-grid {
            grid-template-columns: 1fr;
            gap: 24px;
        }
        
        .main-content,
        .sidebar-card {
            padding: 20px;
        }
        
        .gallery-main img {
            height: 250px;
        }
        
        .sources-grid {
            grid-template-columns: 1fr;
        }
        
        .author-info {
            flex-direction: column;
            text-align: center;
        }
        
        .price-value {
            font-size: 1.6rem;
        }
    }
    
    @media (max-width: 480px) {
        .source-title {
            font-size: 1.5rem;
        }
        
        .gallery-thumbs {
            justify-content: center;
        }
        
        .gallery-thumb {
            width: 60px;
            height: 45px;
        }
    }
</style>
@endpush

@section('content')
<div class="source-detail-container">
    <!-- Source Game Header -->
    <div class="source-header">
        <h1 class="source-title">{{ $sourceGame['title'] }}</h1>
        <p class="source-description">{{ $sourceGame['description'] }}</p>
        
        <div class="source-meta">
            <div class="meta-item">
                <i class="fa fa-gamepad"></i>
                <span>{{ $sourceGame['engine'] }}</span>
            </div>
            <div class="meta-item">
                <i class="fa fa-code"></i>
                <span>{{ $sourceGame['language'] }}</span>
            </div>
            <div class="meta-item">
                <i class="fa fa-download"></i>
                <span>{{ number_format($sourceGame['downloads_count']) }} lượt tải</span>
            </div>
            <div class="meta-item">
                <i class="fa fa-star"></i>
                <span>{{ $sourceGame['rating'] }}/5.0</span>
            </div>
            <div class="meta-item">
                <i class="fa fa-hdd-o"></i>
                <span>{{ $sourceGame['file_size'] }}</span>
            </div>
            <div class="meta-item">
                <i class="fa fa-calendar"></i>
                <span>Cập nhật {{ $sourceGame['last_updated'] }}</span>
            </div>
        </div>
        
        <div class="source-actions">
            @if($sourceGame['is_free'])
                <a href="#download" class="btn btn-primary">
                    <i class="fa fa-download"></i>
                    Tải về miễn phí
                </a>
            @else
                <a href="#purchase" class="btn btn-primary">
                    <i class="fa fa-shopping-cart"></i>
                    Mua ngay {{ number_format($sourceGame['price']) }}đ
                </a>
            @endif
            
            @if($sourceGame['demo_url'])
                <a href="{{ $sourceGame['demo_url'] }}" target="_blank" class="btn btn-outline">
                    <i class="fa fa-play"></i>
                    Demo trực tuyến
                </a>
            @endif
            
            <a href="#contact-author" class="btn btn-secondary">
                <i class="fa fa-envelope"></i>
                Liên hệ tác giả
            </a>
        </div>
    </div>

    <div class="content-grid">
        <!-- Main Content -->
        <div class="main-content">
            <!-- Gallery -->
            @if(count($sourceGame['images']) > 0)
            <div class="gallery">
                <div class="gallery-main">
                    <img id="main-image" src="{{ $sourceGame['images'][0]['url'] }}" alt="{{ $sourceGame['images'][0]['alt'] }}">
                </div>
                @if(count($sourceGame['images']) > 1)
                <div class="gallery-thumbs">
                    @foreach($sourceGame['images'] as $index => $image)
                    <div class="gallery-thumb {{ $index == 0 ? 'active' : '' }}" onclick="changeMainImage('{{ $image['url'] }}', this)">
                        <img src="{{ $image['url'] }}" alt="{{ $image['alt'] }}">
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
            @endif

            <!-- Video Demo -->
            @if($sourceGame['video_demo_url'])
            <div class="video-demo">
                <h3 class="section-title">
                    <i class="fa fa-play-circle"></i>
                    Video hướng dẫn
                </h3>
                <div class="video-container">
                    <iframe src="{{ $sourceGame['video_demo_url'] }}" allowfullscreen></iframe>
                </div>
            </div>
            @endif

            <!-- Features -->
            @if(count($sourceGame['features']) > 0)
            <div class="features">
                <h3 class="section-title">
                    <i class="fa fa-list-ul"></i>
                    Tính năng nổi bật
                </h3>
                <ul class="features-list">
                    @foreach($sourceGame['features'] as $feature)
                    <li>{{ $feature }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Full Description -->
            @if($sourceGame['full_description'])
            <div class="description">
                <h3 class="section-title">
                    <i class="fa fa-info-circle"></i>
                    Mô tả chi tiết
                </h3>
                <div class="content">
                    {!! nl2br(e($sourceGame['full_description'])) !!}
                </div>
            </div>
            @endif

            <!-- Requirements -->
            @if($sourceGame['requirements'])
            <div class="requirements">
                <h3 class="section-title">
                    <i class="fa fa-wrench"></i>
                    Yêu cầu hệ thống
                </h3>
                <p>{{ $sourceGame['requirements'] }}</p>
            </div>
            @endif

            <!-- Tags -->
            @if(count($sourceGame['tags']) > 0)
            <div class="tags-section">
                <h3 class="section-title">
                    <i class="fa fa-tags"></i>
                    Thẻ
                </h3>
                <div class="tags">
                    @foreach($sourceGame['tags'] as $tag)
                    <span class="tag">{{ $tag }}</span>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Price & Download -->
            <div class="sidebar-card">
                <div class="price-info">
                    <div class="price-label">Giá</div>
                    <div class="price-value">
                        @if($sourceGame['is_free'])
                            Miễn phí
                        @else
                            {{ number_format($sourceGame['price']) }}đ
                        @endif
                    </div>
                    @if($sourceGame['is_free'])
                        <div class="price-note">Tải về và sử dụng hoàn toàn miễn phí</div>
                    @else
                        <div class="price-note">Giá đã bao gồm tất cả source code và tài liệu</div>
                    @endif
                </div>
                
                @if($sourceGame['is_free'])
                    <a href="#download" class="btn btn-primary" style="width: 100%; justify-content: center; margin-bottom: 16px;">
                        <i class="fa fa-download"></i>
                        Tải về miễn phí
                    </a>
                @else
                    <a href="#purchase" class="btn btn-primary" style="width: 100%; justify-content: center; margin-bottom: 16px;">
                        <i class="fa fa-shopping-cart"></i>
                        Mua ngay
                    </a>
                @endif
                
                <a href="#contact-support" class="btn btn-outline" style="width: 100%; justify-content: center;">
                    <i class="fa fa-question-circle"></i>
                    Hỗ trợ
                </a>
            </div>

            <!-- Technical Specifications -->
            <div class="sidebar-card">
                <h4 class="section-title">
                    <i class="fa fa-cog"></i>
                    Thông số kỹ thuật
                </h4>
                <div class="tech-specs">
                    <div class="spec-row">
                        <span class="spec-label">Game Engine</span>
                        <span class="spec-value">{{ $sourceGame['engine'] }}</span>
                    </div>
                    <div class="spec-row">
                        <span class="spec-label">Ngôn ngữ</span>
                        <span class="spec-value">{{ $sourceGame['language'] }}</span>
                    </div>
                    <div class="spec-row">
                        <span class="spec-label">Dung lượng</span>
                        <span class="spec-value">{{ $sourceGame['file_size'] }}</span>
                    </div>
                    <div class="spec-row">
                        <span class="spec-label">Phiên bản</span>
                        <span class="spec-value">{{ $sourceGame['version'] }}</span>
                    </div>
                    <div class="spec-row">
                        <span class="spec-label">Loại</span>
                        <span class="spec-value">{{ $sourceGame['category_name'] }}</span>
                    </div>
                    <div class="spec-row">
                        <span class="spec-label">Lượt tải</span>
                        <span class="spec-value">{{ number_format($sourceGame['downloads_count']) }}</span>
                    </div>
                    <div class="spec-row">
                        <span class="spec-label">Đánh giá</span>
                        <span class="spec-value">{{ $sourceGame['rating'] }}/5.0</span>
                    </div>
                </div>
            </div>

            <!-- Author Information -->
            <div class="sidebar-card" id="contact-author">
                <h4 class="section-title">
                    <i class="fa fa-user"></i>
                    Liên hệ lập trình viên
                </h4>
                <div class="author-info">
                    <div class="author-avatar">
                        {{ strtoupper(substr($sourceGame['author_name'], 0, 1)) }}
                    </div>
                    <div class="author-details">
                        <h4>{{ $sourceGame['author_name'] }}</h4>
                        <p>{{ $sourceGame['author_bio'] }}</p>
                    </div>
                </div>
                <a href="mailto:{{ $sourceGame['author_email'] }}" class="btn btn-outline" style="width: 100%; justify-content: center;">
                    <i class="fa fa-envelope"></i>
                    Gửi email
                </a>
            </div>
        </div>
    </div>

    <!-- Related Sources -->
    @if(count($relatedSources) > 0)
    <div class="related-sources">
        <h2 class="section-title">
            <i class="fa fa-gamepad"></i>
            Source Game liên quan
        </h2>
        <div class="sources-grid">
            @foreach($relatedSources as $source)
            <div class="source-card">
                <a href="{{ $source['url'] }}" style="text-decoration: none; color: inherit;">
                    <img src="{{ $source['image'] }}" alt="{{ $source['title'] }}">
                    <div class="source-card-content">
                        <h4>{{ $source['title'] }}</h4>
                        <div class="source-card-meta">
                            <span class="price">
                                @if($source['price'] > 0)
                                    {{ number_format($source['price']) }}đ
                                @else
                                    Miễn phí
                                @endif
                            </span>
                            <div class="rating">
                                <i class="fa fa-star"></i>
                                <span>{{ $source['rating'] }}</span>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
function changeMainImage(imageUrl, thumbElement) {
    // Update main image
    document.getElementById('main-image').src = imageUrl;
    
    // Update active thumbnail
    document.querySelectorAll('.gallery-thumb').forEach(thumb => {
        thumb.classList.remove('active');
    });
    thumbElement.classList.add('active');
}

// Smooth scrolling for anchor links
document.addEventListener('DOMContentLoaded', function() {
    const anchorLinks = document.querySelectorAll('a[href^="#"]');
    
    anchorLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            const targetId = this.getAttribute('href').substring(1);
            const targetElement = document.getElementById(targetId);
            
            if (targetElement) {
                e.preventDefault();
                targetElement.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
});
</script>
@endpush
