# 🎮 LAMGAME.VN - Brand Style Guide

## 📖 Table of Contents
- [Brand Overview](#brand-overview)
- [Logo Design](#logo-design)
- [Color Palette](#color-palette)
- [Typography](#typography)
- [Logo Usage Guidelines](#logo-usage-guidelines)
- [Logo Variations](#logo-variations)
- [Logo Sizes & Formats](#logo-sizes--formats)
- [Mobile-First Considerations](#mobile-first-considerations)
- [Web Implementation](#web-implementation)
- [Social Media Guidelines](#social-media-guidelines)
- [Brand Voice & Messaging](#brand-voice--messaging)
- [Do's and Don'ts](#dos-and-donts)

## 🎯 Brand Overview

**LamGame.vn** là một nền tảng game news và community dành cho game thủ Việt Nam, mang sứ mệnh "Game for Life" - đưa gaming trở thành một phần không thể thiếu trong cuộc sống.

### Brand Positioning
- **Primary Audience**: Game thủ Việt Nam mọi lứa tuổi
- **Core Values**: Community, Innovation, Entertainment, Authenticity
- **Brand Promise**: Providing the latest gaming news and fostering a vibrant gaming community
- **Tone of Voice**: Friendly, enthusiastic, knowledgeable, inclusive

## 🎨 Logo Design

### Logo Analysis
The LamGame logo features a distinctive circular design that embodies the gaming spirit:

- **Central Element**: Gaming controller with crown symbolizing excellence
- **Character Face**: Playful gaming character with expressive features
- **Text Arrangement**: "GAME NEWS" (top arc) and "GAME FOR LIFE" (bottom arc)
- **Domain Integration**: "LAMGAME.VN" prominently displayed
- **Overall Shape**: Perfect circle providing excellent scalability

### Design Philosophy
- **Crown**: Represents leadership in Vietnamese gaming news
- **Controller**: Universal gaming symbol connecting all gamers
- **Character**: Friendly mascot creating emotional connection
- **Circular Frame**: Unity and completeness of the gaming community

## 🎨 Color Palette

### Primary Colors

| Color | Hex Code | RGB | Usage |
|-------|----------|-----|--------|
| **LamGame Red** | #FF4444 | 255, 68, 68 | Primary brand color, text, accents |
| **Controller Orange** | #FF6B35 | 255, 107, 53 | Controller fill, gradients |
| **Crown Gold** | #FFD700 | 255, 215, 0 | Crown, premium elements |
| **Deep Black** | #1A1A1A | 26, 26, 26 | Outlines, text, borders |
| **Pure White** | #FFFFFF | 255, 255, 255 | Background, negative space |

### Secondary Colors

| Color | Hex Code | RGB | Usage |
|-------|----------|-----|--------|
| **Light Red** | #FF7A7A | 255, 122, 122 | Hover states, light backgrounds |
| **Dark Red** | #CC3636 | 204, 54, 54 | Active states, dark themes |
| **Warm Gray** | #F5F5F5 | 245, 245, 245 | Subtle backgrounds |
| **Text Gray** | #666666 | 102, 102, 102 | Secondary text |

### Color Usage Rules
- **Primary Red (#FF4444)**: Use for headlines, CTAs, and brand elements
- **Black (#1A1A1A)**: Use for body text and outlines
- **White (#FFFFFF)**: Use for backgrounds and negative space
- **Gold (#FFD700)**: Use sparingly for premium/special elements
- **Orange (#FF6B35)**: Use for highlights and interactive elements

## ✍️ Typography

### Primary Font Family
```css
font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
```

### Font Hierarchy

#### Headlines (H1-H2)
- **Font Weight**: 700 (Bold)
- **Color**: #FF4444 (LamGame Red) or #1A1A1A (Deep Black)
- **Usage**: Page titles, article headlines

#### Subheadings (H3-H4)
- **Font Weight**: 600 (Semi-Bold)
- **Color**: #1A1A1A (Deep Black)
- **Usage**: Section titles, card headers

#### Body Text
- **Font Weight**: 400 (Regular)
- **Color**: #1A1A1A (Deep Black) or #666666 (Text Gray)
- **Line Height**: 1.6
- **Usage**: Article content, descriptions

#### UI Elements
- **Font Weight**: 500 (Medium)
- **Usage**: Buttons, navigation, labels

### Vietnamese Typography Considerations
- Ensure proper rendering of Vietnamese diacritics
- Use fonts that support Vietnamese character set
- Maintain readability on mobile devices

## 📏 Logo Usage Guidelines

### Clear Space
- **Minimum Clear Space**: 1/4 of the logo width on all sides
- **Web Usage**: At least 20px clearance from other elements
- **Print Usage**: At least 5mm clearance from other elements

### Minimum Sizes
- **Web Display**: 48px × 48px (minimum for recognition)
- **Mobile Icons**: 32px × 32px (iOS/Android app icons)
- **Favicon**: 16px × 16px (browser tab icons)
- **Print**: 15mm × 15mm (business cards, letterheads)

### Background Usage
- **Light Backgrounds**: Use full-color logo
- **Dark Backgrounds**: Use white version with red accent
- **Busy Backgrounds**: Apply 80% opacity white backdrop circle
- **Photography**: Ensure sufficient contrast

## 🔄 Logo Variations

### Primary Logo (Full Version)
- **Usage**: Website headers, official documents, primary branding
- **Format**: Circular with full text and graphics
- **Size Range**: 120px - 512px for web

### Icon Version (Symbol Only)
- **Usage**: Social media profiles, favicons, app icons
- **Format**: Controller + crown symbol without text rings
- **Size Range**: 16px - 256px

### Horizontal Layout
- **Usage**: Website navigation bars, email signatures
- **Format**: Logo symbol + "LAMGAME.VN" text horizontally aligned
- **Aspect Ratio**: 3:1 or 4:1

### Monochrome Versions
- **Black Version**: For print, fax, single-color applications
- **White Version**: For dark backgrounds, reversed applications
- **Red Version**: For brand consistency on neutral backgrounds

## 📐 Logo Sizes & Formats

### Web Formats

#### PNG (Recommended for web)
- `logo-16.png` (16×16) - Favicon
- `logo-32.png` (32×32) - Small icons, mobile
- `logo-48.png` (48×48) - Thumbnail, app icons
- `logo-64.png` (64×64) - Medium icons
- `logo-120.png` (120×120) - Large icons, iOS
- `logo-256.png` (256×256) - High-res displays
- `logo-512.png` (512×512) - Original/source

#### SVG (Vector format)
- `logo.svg` - Scalable vector for all sizes
- `logo-icon.svg` - Icon version only
- `logo-horizontal.svg` - Horizontal layout

#### WebP (Modern browsers)
- `logo-120.webp` - Optimized for modern browsers
- `logo-256.webp` - High-quality, smaller file size

### Print Formats
- `logo-print.pdf` - Vector format for professional printing
- `logo-print-300dpi.png` - High-resolution raster for print

## 📱 Mobile-First Considerations

### Responsive Logo Implementation
```css
.logo {
  width: auto;
  height: 40px; /* Mobile default */
}

@media (min-width: 768px) {
  .logo {
    height: 60px; /* Tablet */
  }
}

@media (min-width: 1024px) {
  .logo {
    height: 80px; /* Desktop */
  }
}
```

### Touch Target Sizes
- **Minimum**: 44px × 44px (iOS guidelines)
- **Recommended**: 48dp × 48dp (Material Design)
- **Optimal**: 56px × 56px for better accessibility

### Mobile Logo Variations
1. **Full Logo**: Use on home page headers
2. **Icon Only**: Use in navigation bars when space is limited
3. **Text Only**: Use in footers or secondary locations

## 💻 Web Implementation

### HTML Implementation
```html
<!-- Primary Logo -->
<img src="/assets/logo-120.png" 
     srcset="/assets/logo-120.png 1x, /assets/logo-256.png 2x"
     alt="LamGame.vn - Game News &amp; Community"
     class="logo-primary">

<!-- Icon Version -->
<img src="/assets/logo-icon-48.png" 
     srcset="/assets/logo-icon-48.png 1x, /assets/logo-icon-96.png 2x"
     alt="LamGame.vn"
     class="logo-icon">
```

### CSS Implementation
```css
/* Logo base styles */
.logo-primary {
  width: auto;
  height: 60px;
  object-fit: contain;
}

.logo-icon {
  width: auto;
  height: 32px;
  object-fit: contain;
}

/* Dark theme variations */
@media (prefers-color-scheme: dark) {
  .logo-primary {
    filter: brightness(0.9);
  }
}
```

### Performance Optimization
```css
/* Preload critical logo */
<link rel="preload" as="image" href="/assets/logo-120.webp" type="image/webp">
<link rel="preload" as="image" href="/assets/logo-120.png" type="image/png">

/* Lazy loading for non-critical logos */
<img src="/assets/logo-large.png" loading="lazy" alt="LamGame.vn">
```

## 📱 Social Media Guidelines

### Profile Pictures
- **Facebook**: 170×170px (displays at 128×128px)
- **Instagram**: 320×320px (displays at 110×110px)
- **Twitter**: 400×400px (displays at 128×128px)
- **YouTube**: 800×800px (displays at 98×98px)
- **TikTok**: 200×200px

### Cover Images
- **Facebook**: 1640×856px (logo at 20% size, left-aligned)
- **Twitter**: 1500×500px (logo centered)
- **YouTube**: 2560×1440px (safe area: 1546×423px)
- **LinkedIn**: 1584×396px

### Social Media Logo Variations
1. **Profile Version**: Clean icon without text rings
2. **Cover Version**: Full logo with accompanying tagline
3. **Story Version**: Vertical orientation with text below

## 🗣️ Brand Voice & Messaging

### Tone of Voice
- **Enthusiastic**: Show passion for gaming
- **Inclusive**: Welcome all types of gamers
- **Knowledgeable**: Demonstrate gaming expertise
- **Friendly**: Use casual, approachable language
- **Local**: Incorporate Vietnamese gaming culture

### Key Messages
- **Primary**: "Game News for Vietnamese Gamers"
- **Secondary**: "Your Ultimate Gaming Community"
- **Tagline**: "Game for Life"

### Content Guidelines
- Use Vietnamese primarily, English for gaming terms
- Include gaming emoticons and expressions
- Reference Vietnamese gaming culture and events
- Maintain positive, community-focused messaging

## ✅ Do's and ❌ Don'ts

### ✅ DO's

#### Logo Usage
- ✅ Use official logo files provided
- ✅ Maintain original proportions
- ✅ Ensure adequate clear space
- ✅ Use appropriate size for medium
- ✅ Test readability at small sizes

#### Color Usage
- ✅ Use official brand colors
- ✅ Maintain sufficient contrast
- ✅ Test colors on different devices
- ✅ Consider accessibility guidelines

#### Typography
- ✅ Use recommended font families
- ✅ Maintain proper hierarchy
- ✅ Ensure Vietnamese character support

### ❌ DON'Ts

#### Logo Misuse
- ❌ Don't stretch or skew the logo
- ❌ Don't change colors arbitrarily
- ❌ Don't add effects (drop shadows, gradients)
- ❌ Don't place on busy backgrounds without backdrop
- ❌ Don't use low-resolution versions

#### Color Violations
- ❌ Don't use unauthorized colors
- ❌ Don't use colors that clash with brand palette
- ❌ Don't use insufficient contrast ratios

#### Typography Errors
- ❌ Don't use fonts that don't support Vietnamese
- ❌ Don't ignore hierarchy guidelines
- ❌ Don't use too many font weights

## 📁 Asset Organization

### Directory Structure
```
/public/assets/logos/
├── png/
│   ├── logo-16.png
│   ├── logo-32.png
│   ├── logo-48.png
│   ├── logo-64.png
│   ├── logo-120.png
│   ├── logo-256.png
│   └── logo-512.png
├── svg/
│   ├── logo.svg
│   ├── logo-icon.svg
│   └── logo-horizontal.svg
├── webp/
│   ├── logo-120.webp
│   └── logo-256.webp
└── favicon/
    ├── favicon.ico
    ├── favicon-16x16.png
    └── favicon-32x32.png
```

## 🔄 Brand Evolution

### Version Control
- **Current Version**: v1.0 (2024)
- **Last Updated**: October 2024
- **Next Review**: Q2 2025

### Guidelines Updates
- Review brand guidelines quarterly
- Update based on user feedback and market trends
- Maintain consistency across all touchpoints
- Document all changes with rationale

## 📊 Implementation Checklist

### Website Implementation
- [ ] Replace all logo instances with optimized versions
- [ ] Implement responsive logo sizing
- [ ] Add proper alt text for accessibility
- [ ] Test on various devices and browsers
- [ ] Optimize for Core Web Vitals

### Social Media Setup
- [ ] Update all social media profile pictures
- [ ] Create branded cover images
- [ ] Ensure consistent visual identity
- [ ] Prepare content templates

### Marketing Materials
- [ ] Create business card templates
- [ ] Design letterhead template
- [ ] Prepare presentation templates
- [ ] Design merchandise mockups

---

**Document Version**: 1.0  
**Last Updated**: October 1, 2024  
**Created By**: LamGame.vn Brand Team  
**Status**: ✅ **APPROVED FOR USE**

*This brand guide ensures consistent visual identity across all LamGame.vn touchpoints while maintaining the playful, gaming-focused spirit of the brand.*