# LamGame.vn — Banner Specification

## Kích thước chuẩn

| Loại | Kích thước (px) | Tỷ lệ | Dùng cho |
|------|-----------------|--------|----------|
| Desktop Full HD | **1920 × 1080** | 16:9 | Màn hình ≥1440px, ảnh gốc upload |
| Desktop HD | **1280 × 720** | 16:9 | Màn hình 768–1440px |
| Mobile | **768 × 432** | 16:9 | Màn hình <768px |

**Khuyến nghị:** Upload ảnh gốc **1920×1080** — hệ thống sẽ tự scale xuống.

## CSS Container hiện tại

```css
.hero-optimized {
    aspect-ratio: 16/9;
    max-height: 560px;    /* Desktop không cao quá 560px */
    min-height: 320px;    /* Mobile không thấp quá 320px */
}

.slide-img {
    object-fit: cover;
    object-position: center center;  /* Có thể override qua focal_point */
}
```

## Safe Zones (vùng an toàn)

```
┌──────────────────────────────────────────────┐
│  ⚠ DANGER ZONE — 60px padding trái/phải     │
│  ┌──────────────────────────────────────┐    │
│  │                                      │    │
│  │     KEY VISUAL AREA (top 60%)        │    │
│  │     Đặt nhân vật, sản phẩm ở đây    │    │
│  │                                      │    │
│  │──────────────────────────────────────│    │
│  │                                      │    │
│  │     OVERLAY ZONE (bottom 40%)        │    │
│  │     Bị gradient tối che              │    │
│  │     ┌────────────────────┐           │    │
│  │     │ TEXT & CTA ZONE    │           │    │
│  │     │ Title, mô tả, nút │           │    │
│  │     └────────────────────┘           │    │
│  └──────────────────────────────────────┘    │
└──────────────────────────────────────────────┘
```

- **Key Visual Area (top 60%):** Đặt nội dung chính (nhân vật, logo, sản phẩm) ở đây
- **Overlay Zone (bottom 40%):** Bị gradient tối phủ — tránh đặt chi tiết quan trọng
- **Text Safe Zone:** Góc trái dưới, padding 60px (desktop) / 20px (mobile)
- **Danger Zone:** 60px mép trái/phải có thể bị crop trên một số màn hình

## Quy tắc thiết kế

1. **Format:** JPG (ảnh thực) hoặc WebP, chất lượng 80–85%
2. **Dung lượng:** Tối đa 200KB (đã nén)
3. **Focal Point:** Nếu ảnh cần focus vào vùng cụ thể, set `focal_point` trong admin (vd: `center top`, `left center`)
4. **Không đặt text quan trọng trên ảnh** — text được render bằng HTML overlay
5. **Contrast:** Đảm bảo phần dưới ảnh đủ tối hoặc đơn giản để text overlay đọc được

## Template files

- `banner_template_1920x1080.png` — Desktop Full HD template với safe zones
- `banner_template_1280x720.png` — Desktop HD template
- `banner_template_768x432.png` — Mobile template
