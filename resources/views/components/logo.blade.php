@props([
    'size' => 'medium',
    'variant' => 'full',
    'class' => '',
    'lazy' => false,
    'interactive' => false
])

@php
$sizeMap = [
    'tiny' => '16',
    'small' => '32',
    'medium' => '64',
    'large' => '120',
    'xlarge' => '256',
    'xxlarge' => '512'
];

// Support both named sizes and numeric sizes
if (is_numeric($size)) {
    $logoSize = $size;
} else {
    $logoSize = $sizeMap[$size] ?? '64';
}
$loading = $lazy ? 'lazy' : 'eager';
$interactiveClass = $interactive ? 'interactive-logo' : '';
$combinedClass = trim("lamgame-logo {$interactiveClass} {$class}");
@endphp

@if($variant === 'horizontal')
    <img src="{{ asset('assets/logos/png/logo-horizontal-' . $logoSize . '.png') }}" 
         alt="LamGame.vn - Game News & Community"
         class="{{ $combinedClass }}"
         loading="{{ $loading }}"
         style="height: {{ $logoSize }}px; width: auto;">
@elseif($variant === 'text')
    <img src="{{ asset('assets/logos/png/logo-horizontal-' . $logoSize . '.png') }}" 
         alt="LamGame.vn"
         class="{{ $combinedClass }}"
         loading="{{ $loading }}"
         style="height: {{ $logoSize }}px; width: auto;">
@elseif($variant === 'icon')
    <img src="{{ asset('assets/logos/svg/logo-icon.svg') }}" 
         alt="LamGame.vn"
         class="{{ $combinedClass }}"
         loading="{{ $loading }}"
         style="height: {{ $logoSize }}px; width: auto;">
@else
    @php
        $retinaSize = min(512, (int)$logoSize * 2);
        $hasRetina = file_exists(public_path("assets/logos/png/logo-{$retinaSize}.png"));
    @endphp
    
    <img src="{{ asset("assets/logos/png/logo-{$logoSize}.png") }}" 
         @if($hasRetina)
         srcset="{{ asset("assets/logos/png/logo-{$logoSize}.png") }} 1x, {{ asset("assets/logos/png/logo-{$retinaSize}.png") }} 2x"
         @endif
         alt="LamGame.vn - Game News & Community"
         class="{{ $combinedClass }}"
         loading="{{ $loading }}"
         style="height: {{ $logoSize }}px; width: auto;">
@endif

@once
@push('styles')
<style>
/* LamGame Logo Styles */
.lamgame-logo {
    object-fit: contain;
    max-width: 100%;
    height: auto;
}

.interactive-logo {
    transition: transform 0.2s ease, opacity 0.2s ease;
    cursor: pointer;
}

.interactive-logo:hover {
    transform: scale(1.05);
    opacity: 0.9;
}

/* Responsive logo adjustments */
@media (max-width: 768px) {
    .lamgame-logo {
        max-height: 48px;
    }
}

@media (max-width: 480px) {
    .lamgame-logo {
        max-height: 40px;
    }
}

/* Dark mode support */
@media (prefers-color-scheme: dark) {
    .lamgame-logo {
        filter: brightness(0.95);
    }
}
</style>
@endpush
@endonce