@props(['title', 'value', 'icon', 'color' => 'primary'])

<div class="stat-card stat-card--{{ $color }}" {{ $attributes }}>
    <div class="stat-card__icon">
        <i class="{{ $icon }}"></i>
    </div>
    <div class="stat-card__content">
        <h3 class="stat-card__value">{{ $value }}</h3>
        <p class="stat-card__title">{{ $title }}</p>
    </div>
</div>
