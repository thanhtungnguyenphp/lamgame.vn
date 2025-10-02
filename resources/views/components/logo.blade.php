@props([
    'size' => 'medium',
    'variant' => 'horizontal',
    'class' => '',
    'lazy' => false,
    'interactive' => false,
    'alt' => 'LamGame.vn - Game Development Platform',
    'priority' => false
])

@php
// Optimized size mapping with actual available sizes
$sizeMap = [
    'xs' => 50,
    'small' => 80,
    'medium' => 60,
    'large' => 400,
    'xl' => 400, // Same as large for horizontal logos
];

// Support both named sizes and numeric sizes
if (is_numeric($size)) {
    $logoSize = (int)$size;
    // Map to nearest available size
    $availableSizes = [50, 60, 80, 100, 200, 400];
    $logoSize = collect($availableSizes)->reduce(function ($carry, $item) use ($logoSize) {
        return abs($item - $logoSize) < abs($carry - $logoSize) ? $item : $carry;
    }, $availableSizes[0]);
} else {
    $logoSize = $sizeMap[$size] ?? 60;
}

// Calculate dimensions based on super-trimmed logo aspect ratio (1265:200 for 200px height)
$aspectRatio = 1265 / 200; // Width/Height ratio from our super-trimmed logo
$logoWidth = (int)($logoSize * $aspectRatio);

$loading = $lazy ? 'lazy' : ($priority ? 'eager' : 'lazy');
$interactiveClass = $interactive ? 'interactive-logo' : '';
$combinedClass = trim("lamgame-logo {$interactiveClass} {$class}");

// Retina size (2x) - cap at 400px
$retinaSize = min(400, $logoSize * 2);
$retinaWidth = (int)($retinaSize * $aspectRatio);

// Check if WebP and retina versions exist
$hasWebP = file_exists(public_path("assets/logos/webp/logo-horizontal-{$logoSize}.webp"));
$hasRetina = file_exists(public_path("assets/logos/png/logo-horizontal-{$retinaSize}.png"));
$hasRetinaWebP = file_exists(public_path("assets/logos/webp/logo-horizontal-{$retinaSize}.webp"));
@endphp

{{-- Modern logo with WebP support and responsive loading --}}
<picture class="logo-picture">
    @if($hasWebP)
    {{-- WebP source with retina support --}}
    <source type="image/webp"
            @if($hasRetinaWebP)
            srcset="{{ asset('assets/logos/webp/logo-horizontal-' . $logoSize . '.webp') }} 1x, {{ asset('assets/logos/webp/logo-horizontal-' . $retinaSize . '.webp') }} 2x"
            @else
            srcset="{{ asset('assets/logos/webp/logo-horizontal-' . $logoSize . '.webp') }}"
            @endif
    >
    @endif
    
    {{-- PNG fallback source with retina support --}}
    <source type="image/png"
            @if($hasRetina)
            srcset="{{ asset('assets/logos/png/logo-horizontal-' . $logoSize . '.png') }} 1x, {{ asset('assets/logos/png/logo-horizontal-' . $retinaSize . '.png') }} 2x"
            @else
            srcset="{{ asset('assets/logos/png/logo-horizontal-' . $logoSize . '.png') }}"
            @endif
    >
    
    {{-- Fallback img element --}}
    <img src="{{ asset('assets/logos/png/logo-horizontal-' . $logoSize . '.png') }}"
         alt="{{ $alt }}"
         class="{{ $combinedClass }}"
         width="{{ $logoWidth }}"
         height="{{ $logoSize }}"
         loading="{{ $loading }}"
         decoding="async"
         @if($priority)
         fetchpriority="high"
         @endif
         style="width: auto; max-width: 100%; object-fit: contain;"
    >
</picture>

@once
@push('styles')
<style>
/* LamGame Logo Styles - Mobile First Approach with Trimmed Logo */
.lamgame-logo {
    object-fit: contain;
    max-width: 100%;
    /* Override any inline height styles with responsive sizing */
    height: 30px !important;
    max-height: 30px;
    width: auto;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    filter: drop-shadow(0 1px 3px rgba(0, 0, 0, 0.1));
    /* Performance optimizations */
    will-change: transform, filter;
    backface-visibility: hidden;
    transform: translateZ(0);
}

/* Picture element container */
.logo-picture {
    display: inline-block;
    line-height: 0;
}

/* Brand logo specific optimizations */
.lamgame-logo.brand-logo {
    height: 100px;
    max-height: 100px;
    width: auto;
    object-fit: contain;
    object-position: center;
    /* Optimized for super-trimmed aspect ratio */
    aspect-ratio: 1265 / 200;
}

.interactive-logo {
    transition: transform 0.2s ease, opacity 0.2s ease, filter 0.2s ease;
    cursor: pointer;
}

.interactive-logo:hover {
    transform: scale(1.02);
    opacity: 0.95;
    filter: drop-shadow(0 4px 8px rgba(102, 126, 234, 0.15));
}

/* Progressive Enhancement - Mobile First for Trimmed Logo */

/* Small mobile (375px+) */
@media (min-width: 375px) {
    .lamgame-logo {
        height: 40px !important;
        max-height: 40px;
    }
}

/* Large mobile / Small tablet (481px+) */
@media (min-width: 481px) {
    .lamgame-logo {
        height: 50px !important;
        max-height: 50px;
    }
    
    .lamgame-logo.brand-logo {
        height: 60px !important;
        max-height: 60px;
    }
}

/* Tablet (769px+) */
@media (min-width: 769px) {
    .lamgame-logo {
        height: 60px !important;
        max-height: 60px;
    }
    
    .lamgame-logo.brand-logo {
        height: 60px !important;
        max-height: 60px;
    }
    
    .interactive-logo:hover {
        transform: scale(1.03);
        filter: drop-shadow(0 2px 8px rgba(102, 126, 234, 0.15));
    }
}

/* Desktop (1025px+) */
@media (min-width: 1025px) {
    .lamgame-logo {
        height: 60px !important;
        max-height: 60px;
    }
    
    .lamgame-logo.brand-logo {
        height: 60px !important;
        max-height: 60px;
    }
    
    .interactive-logo:hover {
        transform: scale(1.05);
        filter: drop-shadow(0 4px 12px rgba(102, 126, 234, 0.2));
    }
}

/* Large desktop (1441px+) - prevent logos from becoming too large */
@media (min-width: 1441px) {
    .lamgame-logo {
        height: 60px !important;
        max-height: 60px;
    }
}

/* High DPI displays */
@media (-webkit-min-device-pixel-ratio: 2), (min-resolution: 192dpi) {
    .lamgame-logo {
        image-rendering: -webkit-optimize-contrast;
        image-rendering: crisp-edges;
    }
}

/* Dark mode support */
@media (prefers-color-scheme: dark) {
    .lamgame-logo {
        filter: brightness(0.95) drop-shadow(0 2px 4px rgba(255, 255, 255, 0.05));
    }
    
    .interactive-logo:hover {
        filter: brightness(1) drop-shadow(0 4px 8px rgba(102, 126, 234, 0.2));
    }
}

/* Reduce motion for accessibility */
@media (prefers-reduced-motion: reduce) {
    .lamgame-logo,
    .interactive-logo {
        transition: none;
    }
    
    .interactive-logo:hover {
        transform: none;
    }
}

/* Print styles */
@media print {
    .lamgame-logo {
        max-height: none;
        height: 2cm;
        filter: none;
    }
}
</style>
@endpush
@endonce
