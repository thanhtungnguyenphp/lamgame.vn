{{-- Source Game Card — price, availability and social proof come from current data only --}}
@php
    $isAvailable = (bool) ($source['is_available'] ?? false);
    $isVerified = (bool) ($source['is_verified_catalog'] ?? false);
    $isFree = (float) ($source['price'] ?? 0) <= 0;
@endphp
<a href="{{ $source['href'] ?? '#' }}"
   class="sg-card {{ ! $isAvailable ? 'sg-card--unavailable' : '' }}"
   data-source-card
   data-product-id="{{ $source['id'] ?? '' }}"
   data-product-name="{{ $source['title'] ?? '' }}"
   data-price="{{ $source['price'] ?? 0 }}"
   data-available="{{ $isAvailable ? '1' : '0' }}">
    <div class="sg-card__thumb">
        <img src="{{ $source['preview_image'] }}" alt="{{ $source['title'] }}" loading="lazy">
        <div class="sg-card__overlay"><span class="sg-card__view">Xem chi tiết →</span></div>
        @if(! $isAvailable)
            <span class="sg-card__badge">Đang hoàn thiện</span>
        @elseif($isFree)
            <span class="sg-card__badge sg-card__badge--free">Miễn phí</span>
        @elseif(!empty($source['is_hot']))
            <span class="sg-card__badge sg-card__badge--hot">🔥 Bán chạy</span>
        @elseif($isVerified)
            <span class="sg-card__badge">✓ Đã kiểm chứng</span>
        @endif
        @if(!empty($source['has_demo']))
            <span class="sg-card__demo-badge" onclick="event.preventDefault(); window.trackRevenueEvent?.('select_content', {content_type:'source_demo', item_id:'{{ $source['id'] ?? '' }}'}); window.location='{{ $source['demo_href'] ?? '#' }}'">▶ Demo</span>
        @endif
    </div>
    <div class="sg-card__body">
        <h3 class="sg-card__title">{{ $source['title'] }}</h3>
        <div class="sg-card__tags">
            @if(!empty($source['engine']))
            <span class="sg-tag">{{ $source['engine'] }}</span>
            @endif
            @if(!empty($source['platform']))<span class="sg-tag">{{ $source['platform'] }}</span>@endif
            @if(!empty($source['multiplayer']))<span class="sg-tag sg-tag--accent">Multiplayer</span>@endif
        </div>
        @if(($source['review_count'] ?? 0) > 0 || ($source['downloads'] ?? 0) > 0)
        <div class="sg-card__social">
            @if(($source['review_count'] ?? 0) > 0 && ($source['rating'] ?? 0) > 0)
            <span class="sg-card__rating">⭐ {{ number_format($source['rating'], 1) }}<small>/5 · {{ $source['review_count'] }} đánh giá</small></span>
            @endif
            @if(($source['downloads'] ?? 0) > 0)
            <span class="sg-card__downloads">⬇ {{ $source['downloads'] }} lượt mua</span>
            @endif
        </div>
        @endif
        <div class="sg-card__footer">
            @if(! $isAvailable)
                <span class="sg-card__price">Chưa mở bán</span>
            @elseif($isFree)
                <span class="sg-card__price sg-card__price--free">Miễn phí</span>
            @else
                <span class="sg-card__price">{{ number_format($source['price'], 0, ',', '.') }}đ</span>
            @endif
            <span class="sg-card__cta">{{ $isAvailable ? 'Xem chi tiết' : 'Xem trạng thái' }}</span>
        </div>
    </div>
</a>
