# Đóng góp cho LamGame Games

Cảm ơn bạn quan tâm đóng góp! 🎉

## Cách đóng góp

### 1. Sửa bug / cải thiện game có sẵn

```bash
git fork → git clone → pnpm install → tạo branch → sửa code → PR
```

### 2. Thêm game mới

```bash
node scripts/new-game.js "slug" "Tên Game"
# Implement GameScene logic
# Test: pnpm dev
# PR vào main
```

### Quy tắc code

- TypeScript strict mode
- Dùng `@lamgame/shared` cho UI chung (Splash, GameOver, Brand)
- Responsive: test 720x1280 (mobile) và 1920x1080 (desktop)
- Comment tiếng Việt hoặc Anh đều OK
- Không commit node_modules, dist

### Quy tắc assets

- Tự vẽ hoặc dùng asset free license (CC0, MIT)
- Logo LamGame không được sửa đổi
- Ghi credit nếu dùng asset bên thứ 3

## Liên hệ

- Forum: https://lamgame.vn/forum
- Email: dev@lamgame.vn
