# Portfolio Images Guide

## Current Status

All portfolio images are SVG placeholders. Replace them with real screenshots for better conversion.

## Image Specifications

| Property | Value |
|----------|-------|
| **Dimensions** | 800 x 450 px (16:9 ratio) |
| **Format** | PNG or WebP (preferred) |
| **Max Size** | 200KB per image |
| **Location** | `/public/images/portfolio/` |

## Files to Replace

| File | Project | Suggested Content |
|------|---------|-------------------|
| `puzzle-game.png` | Puzzle Adventure | Game screenshot with puzzle board |
| `platformer.png` | Sky Runner 3D | Character running on platforms |
| `card-game.png` | Card Clash Arena | Card battle in progress |
| `html5-game.png` | Playable Ads | Ad preview on phone mockup |
| `unreal-game.png` | Horror Survival | Scary environment shot |
| `godot-game.png` | Pixel Roguelike | Dungeon exploration scene |
| `vr-game.png` | VR Training | VR hands interaction |
| `hypercasual.png` | Hyper Casual | Colorful gameplay montage |

## How to Add Real Images

### Option 1: Use Real Project Screenshots
If you have actual project screenshots:
```bash
# Copy your image to the portfolio folder
cp /path/to/your/screenshot.png public/images/portfolio/puzzle-game.png

# Optimize image (install imagemagick if needed)
convert public/images/portfolio/puzzle-game.png -resize 800x450^ -gravity center -extent 800x450 -quality 85 public/images/portfolio/puzzle-game.png
```

### Option 2: Create Mockups
Use tools like:
- **Figma** - Free design tool
- **Canva** - Easy mockup creator
- **Placeit** - Game mockup templates
- **Shotsnapp** - Device mockups

### Option 3: Use Stock Game Screenshots
Sites with free game assets:
- **Unsplash** - Search "video game"
- **itch.io** - Indie game screenshots
- **OpenGameArt** - Free game assets

## Image Optimization

Before uploading, optimize images:

```bash
# Using ImageMagick
convert input.png -resize 800x450 -quality 85 output.png

# Using cwebp (for WebP format)
cwebp -q 80 input.png -o output.webp

# Using online tools
# - TinyPNG.com
# - Squoosh.app
```

## Update Portfolio View

After adding images, update the portfolio blade if using different filenames:

```php
// In resources/views/lamgame/pages/portfolio.blade.php
<img src="{{ asset('images/portfolio/puzzle-game.png') }}" alt="Puzzle Adventure Game Screenshot">
```

## Fallback Behavior

Current SVG placeholders have inline fallback:
```html
onerror="this.src='data:image/svg+xml,...'"
```

This ensures the page doesn't break if images are missing.

## Recommended: Add WebP with Fallback

For better performance:
```html
<picture>
    <source srcset="{{ asset('images/portfolio/puzzle-game.webp') }}" type="image/webp">
    <img src="{{ asset('images/portfolio/puzzle-game.png') }}" alt="Puzzle Adventure" loading="lazy">
</picture>
```

---

## Quick Checklist

- [ ] Create/gather 8 project screenshots
- [ ] Resize to 800x450
- [ ] Optimize file size (<200KB)
- [ ] Upload to `/public/images/portfolio/`
- [ ] Test on `/portfolio` page
- [ ] Clear cache: `docker exec lg-php php artisan view:clear`

---

*Last updated: 2026-08-31*
