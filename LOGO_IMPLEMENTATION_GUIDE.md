# 🎨 LamGame Logo Implementation Guide

This guide provides instructions for implementing the LamGame logo assets throughout the website and applications.

## 📁 Asset Structure

```
public/assets/logos/
├── png/                    # Raster images (PNG format)
│   ├── logo-16.png        # 16×16px - Browser favicon
│   ├── logo-32.png        # 32×32px - Small mobile icons
│   ├── logo-48.png        # 48×48px - Standard mobile icons
│   ├── logo-64.png        # 64×64px - Medium desktop icons
│   ├── logo-120.png       # 120×120px - Header logos, iOS icons
│   ├── logo-170.png       # 170×170px - Facebook profile pictures
│   ├── logo-200.png       # 200×200px - TikTok profile pictures
│   ├── logo-256.png       # 256×256px - High-resolution displays
│   ├── logo-320.png       # 320×320px - Instagram profile pictures
│   ├── logo-400.png       # 400×400px - Twitter profile pictures
│   └── logo-512.png       # 512×512px - Original/source quality
├── svg/                    # Vector graphics (SVG format)
│   ├── logo.svg           # Full circular logo with text rings
│   ├── logo-icon.svg      # Icon version without text rings
│   └── logo-horizontal.svg # Horizontal layout for navigation
├── webp/                   # Modern web format (WebP)
│   └── README.md          # Instructions for WebP conversion
└── favicon/                # Browser favicon files
    ├── favicon.ico        # Traditional ICO format
    ├── favicon-16x16.png  # 16×16px PNG favicon
    └── favicon-32x32.png  # 32×32px PNG favicon
```

## 🎯 Usage Guidelines by Context

### 1. Website Header/Navigation
```html
<!-- Primary header logo (desktop) -->
<img src="/assets/logos/png/logo-120.png" 
     srcset="/assets/logos/png/logo-120.png 1x, /assets/logos/png/logo-256.png 2x"
     alt="LamGame.vn - Game News & Community"
     class="header-logo">

<!-- Mobile header logo (smaller) -->
<img src="/assets/logos/png/logo-64.png" 
     srcset="/assets/logos/png/logo-64.png 1x, /assets/logos/png/logo-120.png 2x"
     alt="LamGame.vn"
     class="mobile-header-logo">
```

### 2. Navigation Bar (Compact)
```html
<!-- Horizontal logo for navigation bars -->
<img src="/assets/logos/svg/logo-horizontal.svg" 
     alt="LamGame.vn" 
     class="nav-logo"
     height="40">
```

### 3. Favicon Implementation
```html
<!-- In your HTML <head> section -->
<link rel="icon" type="image/x-icon" href="/assets/logos/favicon/favicon.ico">
<link rel="icon" type="image/png" sizes="32x32" href="/assets/logos/favicon/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="/assets/logos/favicon/favicon-16x16.png">
```

### 4. Social Media Profile Pictures
```html
<!-- For different platforms, use specific sizes -->
<!-- Facebook: 170×170px -->
<img src="/assets/logos/png/logo-170.png" alt="LamGame.vn Facebook">

<!-- Instagram: 320×320px -->
<img src="/assets/logos/png/logo-320.png" alt="LamGame.vn Instagram">

<!-- Twitter: 400×400px -->
<img src="/assets/logos/png/logo-400.png" alt="LamGame.vn Twitter">

<!-- TikTok: 200×200px -->
<img src="/assets/logos/png/logo-200.png" alt="LamGame.vn TikTok">
```

### 5. Mobile App Icons
```html
<!-- iOS App Icon: 120×120px -->
<img src="/assets/logos/png/logo-120.png" alt="LamGame.vn App">

<!-- Android App Icon: 48×48px -->
<img src="/assets/logos/png/logo-48.png" alt="LamGame.vn App">
```

## 🎨 CSS Implementation

### Responsive Logo Styles
```css
/* Base logo styles */
.header-logo {
  width: auto;
  height: 60px;
  object-fit: contain;
  transition: height 0.3s ease;
}

.mobile-header-logo {
  width: auto;
  height: 40px;
  object-fit: contain;
}

.nav-logo {
  width: auto;
  height: 32px;
  object-fit: contain;
}

/* Responsive breakpoints */
@media (max-width: 768px) {
  .header-logo {
    height: 40px;
  }
}

@media (max-width: 480px) {
  .header-logo {
    height: 32px;
  }
}

/* Dark mode support */
@media (prefers-color-scheme: dark) {
  .logo {
    filter: brightness(0.95);
  }
}

/* High-DPI display optimization */
@media (-webkit-min-device-pixel-ratio: 2), (min-resolution: 192dpi) {
  .logo-retina {
    /* Use 2x resolution images */
  }
}
```

### Logo Hover Effects
```css
.interactive-logo {
  transition: transform 0.2s ease, opacity 0.2s ease;
}

.interactive-logo:hover {
  transform: scale(1.05);
  opacity: 0.9;
}

/* Subtle animation for brand awareness */
@keyframes subtle-pulse {
  0%, 100% { opacity: 1; }
  50% { opacity: 0.95; }
}

.logo-pulse {
  animation: subtle-pulse 3s infinite;
}
```

## 📱 Mobile-First Implementation

### HTML with Mobile Priority
```html
<!-- Mobile-first approach: smallest size first -->
<picture>
  <source media="(min-width: 1200px)" srcset="/assets/logos/png/logo-120.png">
  <source media="(min-width: 768px)" srcset="/assets/logos/png/logo-64.png">
  <img src="/assets/logos/png/logo-48.png" alt="LamGame.vn" class="responsive-logo">
</picture>
```

### Adaptive Logo Component (React/Vue)
```javascript
// React component example
const LamGameLogo = ({ size = 'medium', variant = 'full' }) => {
  const sizeMap = {
    small: '32',
    medium: '64', 
    large: '120',
    xlarge: '256'
  };
  
  const variantMap = {
    full: '/assets/logos/png/logo-',
    icon: '/assets/logos/svg/logo-icon.svg',
    horizontal: '/assets/logos/svg/logo-horizontal.svg'
  };
  
  const logoSrc = variant === 'icon' || variant === 'horizontal' 
    ? variantMap[variant]
    : `${variantMap.full}${sizeMap[size]}.png`;
    
  return (
    <img 
      src={logoSrc}
      alt="LamGame.vn"
      className={`lamgame-logo lamgame-logo--${size} lamgame-logo--${variant}`}
      loading="lazy"
    />
  );
};
```

## ⚡ Performance Optimization

### Preload Critical Logos
```html
<!-- Preload the most important logo for faster rendering -->
<link rel="preload" as="image" href="/assets/logos/png/logo-120.png" type="image/png">
```

### Lazy Loading for Non-Critical Logos
```html
<!-- Use lazy loading for logos below the fold -->
<img src="/assets/logos/png/logo-256.png" 
     loading="lazy" 
     alt="LamGame.vn">
```

### WebP Implementation (when available)
```html
<!-- Progressive enhancement with WebP -->
<picture>
  <source srcset="/assets/logos/webp/logo-120.webp" type="image/webp">
  <img src="/assets/logos/png/logo-120.png" alt="LamGame.vn">
</picture>
```

## 🔧 Laravel/PHP Implementation

### Blade Template Helper
```php
{{-- resources/views/components/logo.blade.php --}}
@props([
    'size' => 'medium',
    'variant' => 'full',
    'class' => '',
    'lazy' => false
])

@php
$sizeMap = [
    'small' => '32',
    'medium' => '64',
    'large' => '120',
    'xlarge' => '256'
];

$sizes = $sizeMap[$size] ?? '64';
$loading = $lazy ? 'lazy' : 'eager';
@endphp

@if($variant === 'horizontal')
    <img src="{{ asset('assets/logos/svg/logo-horizontal.svg') }}" 
         alt="LamGame.vn"
         class="lamgame-logo {{ $class }}"
         loading="{{ $loading }}"
         height="{{ $sizes }}">
@elseif($variant === 'icon')
    <img src="{{ asset('assets/logos/svg/logo-icon.svg') }}" 
         alt="LamGame.vn"
         class="lamgame-logo {{ $class }}"
         loading="{{ $loading }}"
         height="{{ $sizes }}">
@else
    <img src="{{ asset('assets/logos/png/logo-' . $sizes . '.png') }}" 
         srcset="{{ asset('assets/logos/png/logo-' . $sizes . '.png') }} 1x, {{ asset('assets/logos/png/logo-' . ($sizes * 2) . '.png') }} 2x"
         alt="LamGame.vn - Game News & Community"
         class="lamgame-logo {{ $class }}"
         loading="{{ $loading }}">
@endif
```

### Usage in Blade Templates
```blade
{{-- Header logo --}}
<x-logo size="large" class="header-logo" />

{{-- Navigation logo --}}
<x-logo variant="horizontal" class="nav-logo" />

{{-- Mobile logo --}}
<x-logo size="small" lazy="true" class="mobile-logo" />

{{-- Social media icon --}}
<x-logo variant="icon" size="medium" />
```

## 🎨 Brand Consistency Checklist

### Logo Usage Compliance
- [ ] ✅ Logo maintains original proportions
- [ ] ✅ Minimum clear space is preserved (1/4 logo width)
- [ ] ✅ Appropriate size for context (min 32px for web)
- [ ] ✅ Correct color scheme used
- [ ] ✅ Logo is readable at intended size

### Technical Implementation
- [ ] ✅ Proper alt text for accessibility
- [ ] ✅ Responsive sizing across devices
- [ ] ✅ Optimized file sizes for web
- [ ] ✅ Fallback formats provided
- [ ] ✅ Loading performance considered

### File Management
- [ ] ✅ All logo sizes generated and organized
- [ ] ✅ Consistent naming convention used
- [ ] ✅ Asset paths updated throughout project
- [ ] ✅ Version control includes all assets
- [ ] ✅ Backup of original files maintained

## 🔄 Update Instructions

### When Logo Changes
1. **Update Source**: Replace `logo-512.png` with new version
2. **Regenerate Sizes**: Run the resize commands from this guide
3. **Update SVG**: Modify SVG files to match new design
4. **Test Implementation**: Verify all contexts still work correctly
5. **Clear Caches**: Clear browser and CDN caches if applicable

### Regenerating Logo Sizes
```bash
# Navigate to project root
cd /Users/Shared/jerry/ohha/lamgame.vn

# Recreate all sizes from the source 512px logo
sips -z 256 256 public/assets/logos/png/logo-512.png --out public/assets/logos/png/logo-256.png
sips -z 120 120 public/assets/logos/png/logo-512.png --out public/assets/logos/png/logo-120.png
sips -z 64 64 public/assets/logos/png/logo-512.png --out public/assets/logos/png/logo-64.png
sips -z 48 48 public/assets/logos/png/logo-512.png --out public/assets/logos/png/logo-48.png
sips -z 32 32 public/assets/logos/png/logo-512.png --out public/assets/logos/png/logo-32.png
sips -z 16 16 public/assets/logos/png/logo-512.png --out public/assets/logos/png/logo-16.png

# Social media sizes
sips -z 400 400 public/assets/logos/png/logo-512.png --out public/assets/logos/png/logo-400.png
sips -z 320 320 public/assets/logos/png/logo-512.png --out public/assets/logos/png/logo-320.png
sips -z 200 200 public/assets/logos/png/logo-512.png --out public/assets/logos/png/logo-200.png
sips -z 170 170 public/assets/logos/png/logo-512.png --out public/assets/logos/png/logo-170.png

# Update favicon
cp public/assets/logos/png/logo-32.png public/assets/logos/favicon/favicon-32x32.png
cp public/assets/logos/png/logo-16.png public/assets/logos/favicon/favicon-16x16.png
sips -s format ico public/assets/logos/png/logo-32.png --out public/assets/logos/favicon/favicon.ico
```

## 📞 Support & Troubleshooting

### Common Issues

**Q: Logo appears blurry on high-DPI screens**
A: Use srcset with 2x images or SVG format for crisp display

**Q: Logo takes too long to load**
A: Implement preloading for critical logos, lazy loading for others

**Q: Logo doesn't scale properly on mobile**
A: Use responsive CSS and appropriate sizing for mobile contexts

**Q: Favicon not showing in browser**
A: Clear browser cache and ensure correct file paths in HTML head

### File Size Guidelines
- **Favicon**: < 5KB
- **Small icons (16-48px)**: < 10KB
- **Medium logos (64-120px)**: < 25KB
- **Large logos (256px+)**: < 50KB

---

**Status**: ✅ **Ready for Implementation**  
**Last Updated**: October 1, 2024  
**Maintainer**: LamGame.vn Development Team

*This implementation guide ensures consistent and performant logo usage across all LamGame.vn digital touchpoints.*