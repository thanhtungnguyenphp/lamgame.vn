#!/usr/bin/env node
/**
 * Create new game from template
 * Usage: node scripts/new-game.js <slug> "<Display Name>"
 */
const fs = require('fs');
const path = require('path');

const [slug, name] = process.argv.slice(2);
if (!slug || !name) {
  console.log('Usage: node scripts/new-game.js <slug> "<Display Name>"');
  process.exit(1);
}

const dir = path.join(__dirname, '..', 'games', slug);
fs.mkdirSync(path.join(dir, 'src', 'scenes'), { recursive: true });
fs.mkdirSync(path.join(dir, 'public'), { recursive: true });
fs.mkdirSync(path.join(dir, 'assets'), { recursive: true });

// package.json
fs.writeFileSync(path.join(dir, 'package.json'), JSON.stringify({
  name: `@lamgame/${slug}`,
  version: '1.0.0',
  private: true,
  scripts: {
    dev: 'vite',
    build: 'vite build',
    preview: 'vite preview',
  },
  dependencies: { '@lamgame/shared': 'workspace:*', phaser: '^3.80.0' },
  devDependencies: { typescript: '^5.5.0', vite: '^5.4.0' },
}, null, 2));

// vite.config.ts
fs.writeFileSync(path.join(dir, 'vite.config.ts'),
`import { defineConfig } from 'vite';
export default defineConfig({
  base: '/choi-game/${slug}/',
  build: { outDir: 'dist', assetsDir: 'assets' },
});
`);

// tsconfig.json
fs.writeFileSync(path.join(dir, 'tsconfig.json'), JSON.stringify({
  compilerOptions: {
    target: 'ES2020', module: 'ESNext', moduleResolution: 'bundler',
    strict: true, esModuleInterop: true, skipLibCheck: true, outDir: 'dist',
  },
  include: ['src'],
}, null, 2));

// index.html
fs.writeFileSync(path.join(dir, 'index.html'),
`<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no">
  <title>${name} - Chơi Miễn Phí | LamGame</title>
  <meta name="description" content="${name} - Game miễn phí trên LamGame.vn">
  <style>*{margin:0;padding:0}body{background:#1A1A2E;overflow:hidden}canvas{display:block}</style>
</head>
<body>
  <script type="module" src="/src/main.ts"></script>
</body>
</html>
`);

// main.ts
fs.writeFileSync(path.join(dir, 'src', 'main.ts'),
`import Phaser from 'phaser';
import { GAME_CONFIG, SplashScene, GameOverScene } from '@lamgame/shared';
import { GameScene } from './scenes/GameScene';

new Phaser.Game({
  ...GAME_CONFIG,
  scene: [SplashScene, GameScene, GameOverScene],
});
`);

// GameScene.ts template
fs.writeFileSync(path.join(dir, 'src', 'scenes', 'GameScene.ts'),
`import Phaser from 'phaser';
import { BRAND } from '@lamgame/shared';

export class GameScene extends Phaser.Scene {
  private score = 0;

  constructor() {
    super({ key: 'Game' });
  }

  create() {
    const { width, height } = this.scale;
    this.score = 0;

    // TODO: Implement game logic here
    this.add.text(width / 2, height / 2, '${name}', {
      fontFamily: BRAND.fonts.game,
      fontSize: '24px',
      color: BRAND.colors.primary,
    }).setOrigin(0.5);
  }

  private gameOver() {
    this.scene.start('GameOver', { score: this.score, gameKey: '${slug}' });
  }
}
`);

// README.md
fs.writeFileSync(path.join(dir, 'README.md'),
`# ${name}

> 🎮 Game miễn phí bởi [LamGame Team](https://lamgame.vn)

## Chơi ngay

🌐 [Chơi trên web](https://lamgame.vn/choi-game/${slug})

## Phát triển

\`\`\`bash
pnpm install
pnpm dev
\`\`\`

## Build

\`\`\`bash
pnpm build          # Web
npx cap sync        # Android
\`\`\`

## Tech Stack

- **Phaser 3** — Game Framework
- **TypeScript** — Type-safe code
- **Vite** — Lightning fast build
- **Capacitor** — Android/iOS wrapper

## License

MIT © [LamGame Team](https://lamgame.vn)
`);

// LICENSE
fs.writeFileSync(path.join(dir, 'LICENSE'),
`MIT License

Copyright (c) 2026 LamGame Team (https://lamgame.vn)

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
`);

console.log(`✅ Game "${name}" created at games/${slug}/`);
console.log(`   Next: cd games/${slug} && pnpm install && pnpm dev`);
