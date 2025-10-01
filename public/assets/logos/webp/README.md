# WebP Optimization Instructions

The WebP versions should be created from the PNG files using a tool like:
- ImageMagick: convert logo-120.png logo-120.webp
- cwebp: cwebp logo-120.png -o logo-120.webp
- Online tools: squoosh.app, tinypng.com

Priority files for WebP conversion:
- logo-120.png → logo-120.webp (main header logo)
- logo-256.png → logo-256.webp (high-res displays)
- logo-64.png → logo-64.webp (medium icons)
- logo-48.png → logo-48.webp (mobile icons)

Expected file size reduction: 25-35% smaller than PNG

