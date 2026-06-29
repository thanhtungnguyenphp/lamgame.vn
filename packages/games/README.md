# 🎮 LamGame Games — Open Source Mini Games

> Bộ sưu tập mini games mã nguồn mở bởi [LamGame Team](https://lamgame.vn)
>
> Phaser 3 + TypeScript + Vite + Capacitor (Android)

## 🕹️ Games

| Game | Web | Android | Source |
|------|-----|---------|--------|
| 2048 Ghép Số | [Chơi](https://lamgame.vn/choi-game/2048-ghep-so) | APK | [Source](./games/2048-ghep-so) |
| Xếp Gạch Kinh Điển | [Chơi](https://lamgame.vn/choi-game/xep-gach) | APK | [Source](./games/xep-gach) |
| Chim Bay Vượt Ống | [Chơi](https://lamgame.vn/choi-game/chim-bay) | APK | [Source](./games/chim-bay) |
| Rắn Săn Mồi | [Chơi](https://lamgame.vn/choi-game/ran-san-moi) | APK | [Source](./games/ran-san-moi) |
| Kẹo Ngọt Xếp 3 | [Chơi](https://lamgame.vn/choi-game/keo-ngot-xep-3) | APK | [Source](./games/keo-ngot-xep-3) |

## 🚀 Quick Start

```bash
# Clone
git clone https://github.com/lamgame/games.git
cd games

# Install
pnpm install

# Dev một game cụ thể
GAME=2048-ghep-so pnpm dev

# Build tất cả
pnpm build:all

# Tạo game mới
node scripts/new-game.js "ten-game" "Tên Game Hiển Thị"
```

## 📱 Build Android

```bash
cd games/2048-ghep-so
pnpm build
npx cap add android
npx cap sync
npx cap open android
```

## 🏗️ Tech Stack

- **[Phaser 3](https://phaser.io)** — HTML5 Game Framework
- **TypeScript** — Type-safe development
- **Vite** — Build tool siêu nhanh
- **Capacitor** — Native Android/iOS wrapper
- **pnpm workspaces** — Monorepo management

## 🤝 Contributing

Xem [CONTRIBUTING.md](./CONTRIBUTING.md) để bắt đầu đóng góp!

## 📄 License

MIT © [LamGame Team](https://lamgame.vn)
