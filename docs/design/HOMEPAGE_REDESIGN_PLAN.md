# Homepage Redesign — Implementation Plan

> Ngày tạo: 01/07/2026 | Design file: `docs/design/home.png`
> Document ID: 37646c90-ac13-4026-bd2c-55b22b7313fc

---

## Brand Identity

### Positioning
- **Trước:** Cộng đồng Game Dev đa mục đích (portal)
- **Sau:** Marketplace Source Game chuyên nghiệp cho Unity & Unreal Developer

### Color Palette (Dark Theme)

| Tên | Hex | Dùng cho |
|-----|-----|----------|
| Background Primary | `#0D0D1A` | Nền chính |
| Background Secondary | `#1A1A2E` | Cards, sections |
| Background Tertiary | `#252540` | Hover, inputs |
| Accent Primary | `#8B5CF6` | CTA, links, active |
| Accent Secondary | `#A78BFA` | Hover, highlights |
| Accent Gradient | `#8B5CF6 → #6366F1` | Buttons, hero |
| Text Primary | `#FFFFFF` | Headings, body |
| Text Secondary | `#A1A1AA` | Descriptions |
| Text Muted | `#71717A` | Placeholder |
| Border | `#2E2E4A` | Cards, dividers |
| Success | `#10B981` | Verified badge |
| Warning | `#F59E0B` | Star ratings |
| Danger | `#EF4444` | HOT badge |
| Info | `#3B82F6` | NEW badge |

### Typography

| Element | Font | Size | Weight |
|---------|------|------|--------|
| H1 (Hero) | Inter | 48px | 700 |
| H2 (Section) | Inter | 28px | 600 |
| H3 (Card) | Inter | 16px | 600 |
| Body | Inter | 14px | 400 |
| Small | Inter | 12px | 400 |
| Button | Inter | 14px | 500 |

### Spacing & Grid
- Container: max-width 1280px
- Grid: 4 columns, gap 24px
- Section spacing: 64px (py-16)
- Card padding: 16px
- Border radius: 12px (cards), 8px (buttons), 6px (tags)

### Badges

| Badge | Color | Điều kiện |
|-------|-------|-----------|
| HOT | `#EF4444` | Top views/sales tuần |
| BEST SELLER | `#F59E0B` | Top 10 sales all-time |
| NEW | `#3B82F6` | Tạo < 14 ngày |
| VERIFIED | `#10B981` | Đã kiểm duyệt |
| TRENDING | `#8B5CF6` | Sales growth > 50% |
| -XX% | `#EF4444` | Đang giảm giá |

---

## Implementation Phases

### Phase 1: Layout & Dark Theme Foundation (2 ngày)
- Tailwind config: dark colors, typography, spacing
- CSS variables cho color tokens
- Base layout Blade template
- Dark scrollbar, selection styling

### Phase 2: Header & Navigation (2 ngày)
- Sticky header with backdrop blur
- Menu: Trang chủ, Source Game, AI Tools, Blog, Forum, Thành viên
- Search modal (Ctrl+K)
- Cart + Notifications + Auth buttons
- Mobile hamburger → drawer

### Phase 3: Hero Section (2 ngày)
- Badge + H1 + Subtitle + 2 CTAs
- Video thumbnail + play overlay
- 4 USP floating cards
- Stats bar with icons
- Responsive layout

### Phase 4: Categories & Curated Sections (2 ngày)
- Horizontal scroll category cards
- Trending / Best Selling / Staff Picks (3 cols)
- Real data from database
- Responsive: stack on mobile

### Phase 5: Product Grid & Filter System (3 ngày) ⭐ Core
- 6 dropdown filters (AJAX)
- Sort + View toggle
- Product cards with full info
- Wishlist toggle
- Pagination / Load More
- Mobile: filter drawer

### Phase 6: Trust Bar & Footer (1 ngày)
- 5 trust items
- Footer 4 columns + newsletter
- Social links

### Phase 7: Data & Backend (3 ngày) ⭐ Critical
- Database migration: new fields
- Seed data for 68 products
- Homepage API endpoint
- Redis cache (5 min TTL)
- Badge logic automation

### Phase 8: Mobile Responsive & Polish (2 ngày)
- All breakpoints fine-tuned
- Animations (scroll, hover, loading)
- Performance optimization
- Lighthouse > 90

---

## Timeline Estimate

| Phase | Effort | Priority | Dependencies |
|-------|--------|----------|--------------|
| Phase 1 | 2 ngày | HIGH | — |
| Phase 7 | 3 ngày | HIGH | — |
| Phase 2 | 2 ngày | HIGH | Phase 1 |
| Phase 3 | 2 ngày | HIGH | Phase 1 |
| Phase 4 | 2 ngày | MEDIUM | Phase 1, 7 |
| Phase 5 | 3 ngày | HIGH | Phase 1, 7 |
| Phase 6 | 1 ngày | MEDIUM | Phase 1 |
| Phase 8 | 2 ngày | MEDIUM | All above |

**Tổng estimate: ~17 ngày làm việc (3-4 tuần)**

### Parallel tracks:
- Track A: Phase 1 → 2 → 3 → 6 (Frontend structure)
- Track B: Phase 7 (Backend data) — chạy song song với Track A
- Track C: Phase 4 → 5 (Data-dependent UI) — sau Track A + B
- Track D: Phase 8 (Polish) — cuối cùng

---

## Quyết định đã xác nhận (01/07/2026)

1. ✅ **Xổ số, Thể thao, World Cup** → Đẩy xuống block dưới cùng homepage, ẩn khỏi nav chính. Không bỏ hẳn.
2. ✅ **Product data** → Dùng sản phẩm có trong database, thêm giá trị (pricing) dựa trên data hiện có (68 products).
3. ✅ **Video hero** → Tạm dùng YouTube embed (game dev showcase video). Owner sẽ tạo video chính thức sau. Task riêng đã tạo.
4. ✅ **Redesign trang khác** → Redesign toàn bộ sub-pages cho đồng bộ dark theme. Ưu tiên: Source Game > AI Tools > Blog > Forum > Việc làm.

---

## Tasks Bổ Sung

| Task | Priority | Status |
|------|:--------:|:------:|
| Hero Video Production (Owner tạo video) | MEDIUM | Todo |
| Phase 9: Redesign Sub-pages | MEDIUM | Backlog |

## Updated Timeline

```
Week 1: Phase 1 (foundation) + Phase 7 (backend data) — song song
Week 2: Phase 2 (header) + Phase 3 (hero) + Phase 6 (footer)
Week 3: Phase 4 (categories) + Phase 5 (product grid + filters)
Week 4: Phase 8 (responsive + polish)
Week 5-6: Phase 9 (redesign sub-pages)
```

**Tổng: ~5-6 tuần cho full redesign**
