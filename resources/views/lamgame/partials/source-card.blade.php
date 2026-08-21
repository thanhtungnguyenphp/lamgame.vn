{{-- Source Game Card — Enhanced with social proof + urgency --}}
<a href="{{ $source['href'] ?? '#' }}" class="sg-card">
    <div class="sg-card__thumb">
        <img src="{{ $source['preview_image'] }}" alt="{{ $source['title'] }}" loading="lazy">
        <div class="sg-card__overlay"><span class="sg-card__view">Xem chi tiết →</span></div>
        @if(($source['price'] ?? 0) <= 0)
            <span class="sg-card__badge sg-card__badge--free">Free</span>
        @elseif(!empty($source['is_hot']) || ($source['downloads'] ?? 0) > 50)
            <span class="sg-card__badge sg-card__badge--hot">🔥 Hot</span>
        @endif
        @if(!empty($source['has_demo']))
            <span class="sg-card__demo-badge" onclick="event.preventDefault(); window.location='{{ $source['demo_href'] ?? '#' }}'">▶ Demo</span>
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
        <div class="sg-card__social">
            @if(!empty($source['rating']))
            <span class="sg-card__rating">⭐ {{ number_format($source['rating'], 1) }}<small>/5</small></span>
            @endif
            <span class="sg-card__downloads">⬇ {{ $source['downloads'] ?? 0 }} lượt tải</span>
        </div>
        <div class="sg-card__footer">
            <span class="sg-card__price {{ ($source['price'] ?? 0) <= 0 ? 'sg-card__price--free' : '' }}">
                @if(($source['price'] ?? 0) <= 0)
                    Miễn phí
                @else
                    {{ number_format($source['price']) }}đ
                @endif
            </span>
            <span class="sg-card__cta">Xem ngay</span>
        </div>
    </div>
</a>
