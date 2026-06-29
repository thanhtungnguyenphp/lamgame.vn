import Phaser from 'phaser';
import { BRAND } from '@lamgame/shared';

const COLORS = [0xFF6584, 0x6C63FF, 0x00D68F, 0xFFD700];
const PAD_SIZE = 200, GAP = 20;

export class GameScene extends Phaser.Scene {
  private sequence: number[] = [];
  private playerInput: number[] = [];
  private score = 0;
  private isShowingSequence = false;
  private pads: Phaser.GameObjects.Rectangle[] = [];
  private scoreText!: Phaser.GameObjects.Text;
  private statusText!: Phaser.GameObjects.Text;

  constructor() { super({ key: 'Game' }); }

  create() {
    const { width, height } = this.scale;
    this.sequence = []; this.playerInput = []; this.score = 0;

    this.add.text(width / 2, 50, 'SIMON NÓI', { fontFamily: BRAND.fonts.game, fontSize: '24px', color: BRAND.colors.primary }).setOrigin(0.5);
    this.scoreText = this.add.text(width / 2, 100, 'Chuỗi: 0', { fontFamily: BRAND.fonts.ui, fontSize: '20px', color: BRAND.colors.light }).setOrigin(0.5);
    this.statusText = this.add.text(width / 2, 140, 'Xem và nhớ...', { fontFamily: BRAND.fonts.ui, fontSize: '14px', color: '#aaa' }).setOrigin(0.5);

    // 4 color pads (2x2 grid)
    const startX = (width - PAD_SIZE * 2 - GAP) / 2;
    const startY = (height - PAD_SIZE * 2 - GAP) / 2;

    for (let i = 0; i < 4; i++) {
      const col = i % 2, row = Math.floor(i / 2);
      const x = startX + col * (PAD_SIZE + GAP) + PAD_SIZE / 2;
      const y = startY + row * (PAD_SIZE + GAP) + PAD_SIZE / 2;
      const pad = this.add.rectangle(x, y, PAD_SIZE, PAD_SIZE, COLORS[i], 0.6)
        .setInteractive({ useHandCursor: true })
        .setStrokeStyle(4, COLORS[i]);
      pad.on('pointerdown', () => this.handleInput(i));
      this.pads.push(pad);
    }

    this.add.text(width / 2, height - 30, 'lamgame.vn', { fontFamily: BRAND.fonts.ui, fontSize: '12px', color: '#444' }).setOrigin(0.5);

    // Start first round
    this.time.delayedCall(1000, () => this.nextRound());
  }

  private nextRound() {
    this.sequence.push(Phaser.Math.Between(0, 3));
    this.playerInput = [];
    this.score = this.sequence.length;
    this.scoreText.setText(`Chuỗi: ${this.score}`);
    this.statusText.setText('Xem và nhớ...');
    this.showSequence();
  }

  private showSequence() {
    this.isShowingSequence = true;
    let i = 0;
    const show = () => {
      if (i >= this.sequence.length) {
        this.isShowingSequence = false;
        this.statusText.setText('Đến lượt bạn!');
        return;
      }
      this.flashPad(this.sequence[i]);
      i++;
      this.time.delayedCall(600, show);
    };
    this.time.delayedCall(400, show);
  }

  private flashPad(idx: number) {
    const pad = this.pads[idx];
    pad.setAlpha(1);
    this.time.delayedCall(300, () => pad.setAlpha(0.6));
  }

  private handleInput(idx: number) {
    if (this.isShowingSequence) return;
    this.flashPad(idx);
    this.playerInput.push(idx);

    const i = this.playerInput.length - 1;
    if (this.playerInput[i] !== this.sequence[i]) {
      // Wrong!
      this.statusText.setText('❌ Sai rồi!');
      this.time.delayedCall(800, () => this.scene.start('GameOver', { score: this.score - 1, gameKey: 'simon-noi' }));
      return;
    }

    if (this.playerInput.length === this.sequence.length) {
      this.statusText.setText('✅ Đúng!');
      this.time.delayedCall(1000, () => this.nextRound());
    }
  }
}
