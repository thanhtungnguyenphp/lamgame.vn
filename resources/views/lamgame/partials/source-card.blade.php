{{-- Source Game Card — Cinematic Marketplace Style --}}
<a href="{{ $source['href'] ?? '#' }}" class="sg-card">
    <div class="sg-card__thumb">
        <img src="{{ $source['preview_image'] }}" alt="{{ $source['title'] }}" loading="lazy">
        <div class="sg-card__overlay"><span class="sg-card__view">Xem chi tiết</span></div>
        @if(($source['price'] ?? 0) <= 0)
            <span class="sg-card__badge sg-card__badge--free">Free</span>
        @endif
        @if(!empty($source['production_ready']))
            <span class="sg-card__badge sg-card__badge--prod">Production Ready</span>
        @endif
    </div>
    <div class="sg-card__body">
        <h3 class="sg-card__title">{{ $source['title'] }}</h3>
        <div class="sg-card__tags">
            <span class="sg-tag">{{ $source['engine'] ?? 'Unity 6' }}</span>
            @if(!empty($source['platform']))<span class="sg-tag">{{ $source['platform'] }}</span>@endif
            @if(!empty($source['render_pipeline']))<span class="sg-tag">{{ $source['render_pipeline'] }}</span>@endif
            @if(!empty($source['multiplayer']))<span class="sg-tag">Multiplayer</span>@endif
        </div>
        <div class="sg-card__stats">
            @if(!empty($source['rating']))
            <span class="sg-card__rating">⭐ {{ number_format($source['rating'], 1) }}</span>
            @endif
            @if(!empty($source['downloads']))
            <span class="sg-card__downloads">↓ {{ $source['downloads'] }}</span>
            @endif
        </div>
        <div class="sg-card__footer">
            <span class="sg-card__price">
                @if(($source['price'] ?? 0) <= 0)
                    Miễn phí
                @else
                    {{ number_format($source['price']) }}đ
                @endif
            </span>
            <span class="sg-card__cta">Tải ngay →</span>
        </div>
    </div>
</a>
