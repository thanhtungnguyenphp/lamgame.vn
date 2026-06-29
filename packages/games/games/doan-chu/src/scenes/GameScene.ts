import Phaser from 'phaser';
import { BRAND } from '@lamgame/shared';

const WORDS = ['PHASER','JAVASCRIPT','LAMGAME','DEVELOPER','ANDROID','MOBILE','ARCADE','PUZZLE','DRAGON','KNIGHT','WARRIOR','PLATFORM','POKEMON','ZELDA','MARIO','TETRIS','PACMAN','RACING','COMBAT','DESIGN','ENGINE','SHADER','SPRITE','RENDER','CANVAS','SERVER','CLIENT','DEPLOY','DOCKER','GITHUB'];

export class GameScene extends Phaser.Scene {
  private word = '';
  private guessed: Set<string> = new Set();
  private wrong = 0;
  private maxWrong = 7;
  private score = 0;
  private wordText!: Phaser.GameObjects.Text;
  private wrongText!: Phaser.GameObjects.Text;
  private graphics!: Phaser.GameObjects.Graphics;
  private keyButtons: Phaser.GameObjects.Text[] = [];

  constructor() { super({ key: 'Game' }); }

  create() {
    const { width, height } = this.scale;
    this.word = WORDS[Phaser.Math.Between(0, WORDS.length - 1)];
    this.guessed = new Set(); this.wrong = 0; this.score = 0;
    this.graphics = this.add.graphics();

    this.add.text(width / 2, 30, 'ĐOÁN CHỮ', { fontFamily: BRAND.fonts.game, fontSize: '22px', color: BRAND.colors.primary }).setOrigin(0.5);
    this.wrongText = this.add.text(width / 2, 70, `Sai: 0/${this.maxWrong}`, { fontFamily: BRAND.fonts.ui, fontSize: '16px', color: '#FF6584' }).setOrigin(0.5);

    // Word display
    this.wordText = this.add.text(width / 2, 350, '', { fontFamily: BRAND.fonts.game, fontSize: '32px', color: BRAND.colors.light, letterSpacing: 8 }).setOrigin(0.5);
    this.updateWordDisplay();

    // Keyboard (A-Z)
    const keys = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    const keySize = 52;
    const keysPerRow = 9;
    const startY = height - 250;

    for (let i = 0; i < keys.length; i++) {
      const row = Math.floor(i / keysPerRow);
      const col = i % keysPerRow;
      const rowOffset = row === 2 ? (keysPerRow - 8) * keySize / 2 : 0;
      const x = (width - keysPerRow * keySize) / 2 + col * keySize + keySize / 2 + rowOffset;
      const y = startY + row * (keySize + 8);
      const btn = this.add.text(x, y, keys[i], {
        fontFamily: BRAND.fonts.ui, fontSize: '20px', color: BRAND.colors.light,
        backgroundColor: '#333', padding: { x: 14, y: 10 },
      }).setOrigin(0.5).setInteractive({ useHandCursor: true });
      btn.on('pointerdown', () => this.guess(keys[i], btn));
      this.keyButtons.push(btn);
    }

    // Keyboard input
    this.input.keyboard?.on('keydown', (e: KeyboardEvent) => {
      const key = e.key.toUpperCase();
      if (key.length === 1 && key >= 'A' && key <= 'Z') {
        const btn = this.keyButtons.find(b => b.text === key);
        if (btn) this.guess(key, btn);
      }
    });

    this.drawHangman();
    this.add.text(width / 2, height - 20, 'lamgame.vn', { fontFamily: BRAND.fonts.ui, fontSize: '12px', color: '#444' }).setOrigin(0.5);
  }

  private guess(letter: string, btn: Phaser.GameObjects.Text) {
    if (this.guessed.has(letter)) return;
    this.guessed.add(letter);
    btn.setAlpha(0.3);

    if (this.word.includes(letter)) {
      btn.setStyle({ backgroundColor: '#00D68F' });
      this.updateWordDisplay();
      if (this.isWon()) {
        this.score = (this.maxWrong - this.wrong) * 100 + this.word.length * 50;
        this.time.delayedCall(500, () => this.scene.start('GameOver', { score: this.score, gameKey: 'doan-chu' }));
      }
    } else {
      btn.setStyle({ backgroundColor: '#FF6584' });
      this.wrong++;
      this.wrongText.setText(`Sai: ${this.wrong}/${this.maxWrong}`);
      this.drawHangman();
      if (this.wrong >= this.maxWrong) {
        this.wordText.setText(this.word);
        this.time.delayedCall(1000, () => this.scene.start('GameOver', { score: 0, gameKey: 'doan-chu' }));
      }
    }
  }

  private updateWordDisplay() {
    const display = this.word.split('').map(c => this.guessed.has(c) ? c : '_').join(' ');
    this.wordText.setText(display);
  }

  private isWon(): boolean {
    return this.word.split('').every(c => this.guessed.has(c));
  }

  private drawHangman() {
    const { width } = this.scale;
    const cx = width / 2, baseY = 130;
    this.graphics.clear();
    this.graphics.lineStyle(3, 0xffffff);

    if (this.wrong >= 1) { this.graphics.lineBetween(cx - 60, baseY + 160, cx + 60, baseY + 160); } // base
    if (this.wrong >= 2) { this.graphics.lineBetween(cx - 20, baseY + 160, cx - 20, baseY); } // pole
    if (this.wrong >= 3) { this.graphics.lineBetween(cx - 20, baseY, cx + 40, baseY); } // top
    if (this.wrong >= 4) { this.graphics.lineBetween(cx + 40, baseY, cx + 40, baseY + 30); } // rope
    if (this.wrong >= 5) { this.graphics.strokeCircle(cx + 40, baseY + 50, 20); } // head
    if (this.wrong >= 6) { this.graphics.lineBetween(cx + 40, baseY + 70, cx + 40, baseY + 120); } // body
    if (this.wrong >= 7) { // legs
      this.graphics.lineBetween(cx + 40, baseY + 120, cx + 20, baseY + 150);
      this.graphics.lineBetween(cx + 40, baseY + 120, cx + 60, baseY + 150);
    }
  }
}
