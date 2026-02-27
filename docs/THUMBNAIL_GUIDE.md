# Hướng Dẫn Tạo Thumbnail Blog - LamGame.vn

## 1. Thông Số Kỹ Thuật

| Thông số | Giá trị | Ghi chú |
|----------|---------|---------|
| Kích thước | 1200 x 630 px | Chuẩn OG image cho Facebook/Zalo/Telegram |
| Định dạng | PNG | Dùng `quality=95` khi save |
| Dung lượng mục tiêu | < 300KB | Compress bằng TinyPNG nếu vượt |
| Color mode | RGB | Convert từ RGBA trước khi save |

## 2. Font Chữ — QUAN TRỌNG

### Font bắt buộc: Google Noto Sans

```
Regular: /tmp/mlbb_assets/NotoSans-Regular.ttf
Bold:    /tmp/mlbb_assets/NotoSans-Bold.ttf
```

**Tải font:**
```bash
curl -sL -o /tmp/mlbb_assets/NotoSans-Regular.ttf \
  "https://github.com/googlefonts/noto-fonts/raw/main/hinted/ttf/NotoSans/NotoSans-Regular.ttf"

curl -sL -o /tmp/mlbb_assets/NotoSans-Bold.ttf \
  "https://github.com/googlefonts/noto-fonts/raw/main/hinted/ttf/NotoSans/NotoSans-Bold.ttf"
```

### Tại sao KHÔNG dùng font hệ thống macOS?

| Font | Vấn đề |
|------|--------|
| Helvetica.ttc | Dùng combining diacritics → Pillow render sai vị trí dấu tiếng Việt (ờ, ượ, ổ bị lệch) |
| SFNS.ttf | Tương tự Helvetica, lỗi dấu khi kết hợp với Pillow |
| Arial Unicode.ttf | Tiếng Việt OK nhưng emoji hiển thị ô vuông (□) |

### Noto Sans giải quyết được vì:
- Dùng **precomposed Unicode** (NFC) cho tiếng Việt → dấu gắn liền với ký tự gốc
- Hỗ trợ đầy đủ Latin Extended + Vietnamese block
- Có cả Regular và Bold weight
- Miễn phí, open source (OFL license)

## 3. Bảng Font Size Chuẩn

| Vai trò | Font | Size | Dùng cho |
|---------|------|------|----------|
| font_xl | Bold | 42-50 | Tiêu đề chính (META M7, tên giải đấu) |
| font_lg | Bold | 25-30 | Tiêu đề phụ, kết quả trận đấu |
| font_md | Regular | 17-19 | Nội dung info card |
| font_md_b | Bold | 19-21 | Nội dung info card (nhấn mạnh) |
| font_sm | Regular | 15 | Subtitle, mô tả phụ |
| font_sm_b | Bold | 15 | Badge, category label |
| font_brand | Bold | 19 | LAMGAME.VN watermark |

## 4. Emoji / Icon — KHÔNG dùng Unicode Emoji

Pillow không render được color emoji (📅🏆💰🏅 → hiển thị ô vuông hoặc mono xám).

**Giải pháp:** Vẽ icon bằng ImageDraw.

```python
def draw_calendar_icon(d, cx, cy, size=14, color='white'):
    s = size
    d.rounded_rectangle([cx-s, cy-s, cx+s, cy+s], radius=3, outline=color, width=2)
    d.line([(cx-s, cy-s//2), (cx+s, cy-s//2)], fill=color, width=2)
    for dx in [-s//2, 0, s//2]:
        for dy in [2, s//2+2]:
            d.rectangle([cx+dx-2, cy+dy-2, cx+dx+2, cy+dy+2], fill=color)

def draw_trophy_icon(d, cx, cy, size=14, color=(255, 215, 0)):
    s = size
    d.arc([cx-s, cy-s, cx+s, cy+s//3], 0, 180, fill=color, width=2)
    d.line([(cx-s, cy-s), (cx-s, cy+s//3)], fill=color, width=2)
    d.line([(cx+s, cy-s), (cx+s, cy+s//3)], fill=color, width=2)
    d.line([(cx-s, cy-s), (cx+s, cy-s)], fill=color, width=2)
    d.line([(cx, cy+s//3), (cx, cy+s-4)], fill=color, width=2)
    d.line([(cx-s//2, cy+s-4), (cx+s//2, cy+s-4)], fill=color, width=3)

def draw_money_icon(d, cx, cy, size=14, color=(0, 230, 118)):
    s = size
    d.ellipse([cx-s, cy-s, cx+s, cy+s], outline=color, width=2)
    d.text((cx-5, cy-10), "$", font=ImageFont.truetype(FONT_B, s+4), fill=color)
```

## 5. Bảng Màu Chuẩn LamGame.vn

| Tên | Hex | RGB | Dùng cho |
|-----|-----|-----|----------|
| Accent Orange | #FF6B35 | (255, 107, 53) | Top bar, bottom bar, badge, decorative line |
| Gold | #FFD700 | (255, 215, 0) | Tiêu đề phụ, brand watermark |
| Purple | #6A4C93 | (106, 76, 147) | Category badge background |
| Dark BG | #0A0528 | (10, 5, 40) | Background gradient base |
| Text White | #FFFFFF | (255, 255, 255) | Tiêu đề chính |
| Text Muted | #B4B4DC | (180, 180, 220) | Subtitle, mô tả phụ |
| Hero Red | #FF4444 | (255, 68, 68) | Label tướng/nhân vật (slot 1) |
| Hero Cyan | #00E5FF | (0, 229, 255) | Label tướng/nhân vật (slot 2) |
| Hero Gold | #FFD700 | (255, 215, 0) | Label tướng/nhân vật (slot 3) |
| Hero Pink | #FF69B4 | (255, 105, 180) | Label tướng/nhân vật (slot 4) |
| Hero Purple | #7B68EE | (123, 104, 238) | Label tướng/nhân vật (slot 5) |

## 6. Template Code Cơ Bản

```python
from PIL import Image, ImageDraw, ImageFont, ImageFilter, ImageEnhance
import os

W, H = 1200, 630
FONT_R = "/tmp/mlbb_assets/NotoSans-Regular.ttf"
FONT_B = "/tmp/mlbb_assets/NotoSans-Bold.ttf"

# === Load fonts ===
font_xl   = ImageFont.truetype(FONT_B, 42)
font_lg   = ImageFont.truetype(FONT_B, 28)
font_md   = ImageFont.truetype(FONT_R, 19)
font_md_b = ImageFont.truetype(FONT_B, 19)
font_sm   = ImageFont.truetype(FONT_R, 15)
font_sm_b = ImageFont.truetype(FONT_B, 15)

# === Helper: Centered text with shadow ===
def draw_centered(d, text, y, font, fill, shadow=True):
    bbox = d.textbbox((0, 0), text, font=font)
    tw = bbox[2] - bbox[0]
    x = (W - tw) // 2
    if shadow:
        d.text((x + 2, y + 2), text, fill=(0, 0, 0, 180), font=font)
    d.text((x, y), text, fill=fill, font=font)

# === Canvas ===
canvas = Image.new('RGBA', (W, H), (0, 0, 0, 255))
draw = ImageDraw.Draw(canvas)

# ... thêm nội dung ở đây ...

# === Các thành phần cố định ===

# Top accent bar
draw.rectangle([(0, 0), (W, 5)], fill=(255, 107, 53))

# Bottom accent bar
draw.rectangle([(0, H - 7), (W, H)], fill=(255, 107, 53))

# Category badge (bottom-left)
cat = "eSports · MLBB"
bbox_c = draw.textbbox((0, 0), cat, font=font_sm_b)
cw = bbox_c[2] - bbox_c[0]
ch = bbox_c[3] - bbox_c[1]
draw.rounded_rectangle([18, H-46, 18+cw+22, H-46+ch+14], radius=10, fill=(106, 76, 147, 220))
draw.text((29, H-40), cat, fill='white', font=font_sm_b)

# Brand watermark (bottom-right)
brand = "LAMGAME.VN"
font_brand = ImageFont.truetype(FONT_B, 19)
bbox_b = draw.textbbox((0, 0), brand, font=font_brand)
bw = bbox_b[2] - bbox_b[0]
draw.text((W-bw-24+1, H-40+1), brand, fill=(0, 0, 0, 150), font=font_brand)
draw.text((W-bw-24, H-40), brand, fill=(255, 215, 0), font=font_brand)

# === Save ===
output = canvas.convert('RGB')
output.save("docs/thumb-ten-bai-viet.png", 'PNG', quality=95)
```

## 7. Kỹ Thuật Xử Lý Ảnh Nền

### Background từ ảnh thật (stadium, screenshot game):
```python
bg = Image.open('background.jpg').convert('RGBA')
# Resize to cover
ratio = max(W / bg.width, H / bg.height)
bg = bg.resize((int(bg.width * ratio), int(bg.height * ratio)), Image.LANCZOS)
# Center crop
sx = (bg.width - W) // 2
sy = (bg.height - H) // 2
bg = bg.crop((sx, sy, sx + W, sy + H))
# Darken + desaturate + blur
bg = ImageEnhance.Brightness(bg.convert('RGB')).enhance(0.3)
bg = ImageEnhance.Color(bg).enhance(0.5)
bg = bg.filter(ImageFilter.GaussianBlur(radius=3))
```

### Background gradient (dark gaming style):
```python
bg = Image.new('RGB', (W, H))
d = ImageDraw.Draw(bg)
for y in range(H):
    r = int(8 + 20 * y / H)
    g = int(5 + 8 * y / H)
    b = int(30 + 50 * (1 - y / H))
    d.line([(0, y), (W, y)], fill=(r, g, b))
```

### Gradient overlay cho text readability:
```python
overlay = Image.new('RGBA', (W, H), (0, 0, 0, 0))
od = ImageDraw.Draw(overlay)
for y in range(H):
    if y < 140:      # Top fade
        alpha = int(180 * (1 - y / 140))
    elif y > 490:     # Bottom fade
        alpha = int(200 * ((y - 490) / 140))
    else:
        alpha = 0
    od.line([(0, y), (W, y)], fill=(5, 5, 30, alpha))
canvas = Image.alpha_composite(canvas, overlay)
```

## 8. Kỹ Thuật Collage (Ghép Hero)

### Panel layout 3 hero:
```
| Hero 1 (0-400)  | Hero 2 (350-850) | Hero 3 (750-1200) |
|    overlap 50px  |   center, lớn    |    overlap 50px   |
```

### Edge fade giữa các panel:
```python
fade = Image.new('RGBA', (panel_w, panel_h), (0, 0, 0, 0))
fd = ImageDraw.Draw(fade)
# Left edge fade
for x in range(80):
    fd.line([(x, 0), (x, panel_h)], fill=(8, 5, 30, int(255*(1-x/80))))
# Right edge fade
for x in range(panel_w-80, panel_w):
    fd.line([(x, 0), (x, panel_h)], fill=(8, 5, 30, int(255*((x-(panel_w-80))/80))))
```

### Diagonal separator giữa panels:
```python
for offset, color in [(390, (255, 68, 68, 80)), (780, (255, 215, 0, 80))]:
    for i in range(-4, 5):
        draw.line([(offset+i+60, 0), (offset+i-60, H)], fill=color, width=1)
```

## 9. Nguồn Ảnh Hero MLBB

### Fandom Wiki API (ổn định, không bị Cloudflare):
```bash
# Lấy URL ảnh portrait hero
curl -sL "https://mobile-legends.fandom.com/api.php?action=query&titles=TEN_HERO&prop=pageimages&format=json&pithumbsize=600"

# Download ảnh
curl -sL -o hero.png "URL_TU_API" -H "User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7)"
```

### Liquipedia (backup, ảnh nhỏ hơn):
```bash
# Tìm URL ảnh trên trang hero
curl -sL "https://liquipedia.net/mobilelegends/TEN_HERO" \
  -H "User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7)" \
  | grep -oE 'https://liquipedia\.net/commons/images/[^"]*infobox\.(png|jpg)'
```

### Wikipedia (logo giải đấu, stadium):
```bash
# Tìm ảnh trên trang Wikipedia
curl -sL "https://en.wikipedia.org/wiki/TEN_TRANG" \
  -H "User-Agent: Mozilla/5.0" \
  | grep -oE 'src="//upload\.wikimedia\.org[^"]*'
# Prefix https: vào URL để download
```

## 10. Checklist Trước Khi Xuất Bản

- [ ] Font Noto Sans đã tải về `/tmp/mlbb_assets/`
- [ ] Tiếng Việt hiển thị đúng dấu (kiểm tra: ờ, ượ, ổ, ệ, ấ)
- [ ] Không dùng Unicode emoji — thay bằng icon vẽ hoặc text
- [ ] Kích thước đúng 1200x630
- [ ] Có top + bottom accent bar màu cam (#FF6B35)
- [ ] Có category badge (bottom-left, nền tím)
- [ ] Có brand "LAMGAME.VN" (bottom-right, màu vàng gold)
- [ ] Dung lượng < 300KB (compress nếu cần)
- [ ] Text đọc được rõ trên nền (có shadow hoặc dark overlay)
