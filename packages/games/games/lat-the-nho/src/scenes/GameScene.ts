import Phaser from 'phaser';
import { BRAND } from '@lamgame/shared';

const COLS = 4, ROWS = 5, CELL = 120, GAP = 10;
const EMOJIS = ['🎮','⚽','🎯','🎲','🏆','🎪','🎨','🎭','🎵','🎸'];

export class GameScene extends Phaser.Scene {
  private cards: { emoji: string; flipped: boolean; matched: boolean }[][] = [];
  private flippedCards: { r: number; c: number }[] = [];
  private score = 0; private moves = 0;
  private scoreText!: Phaser.GameObjects.Text;
  private boardX = 0; private boardY = 0;
  private cardTexts: (Phaser.GameObjects.Text | null)[][] = [];
  private cardBgs: (Phaser.GameObjects.Rectangle | null)[][] = [];
  private isChecking = false;

  constructor() { super({ key: 'Game' }); }

  create() {
    const { width, height } = this.scale;
    this.score = 0; this.moves = 0; this.isChecking = false; this.flippedCards = [];
    const totalCards = COLS * ROWS;
    const pairs = totalCards / 2;
    const emojis = Phaser.Utils.Array.Shuffle([...EMOJIS.slice(0, pairs), ...EMOJIS.slice(0, pairs)]);

    this.boardX = (width - COLS * (CELL + GAP)) / 2;
    this.boardY = 150;
    this.cards = []; this.cardTexts = []; this.cardBgs = [];

    let idx = 0;
    for (let r = 0; r < ROWS; r++) {
      this.cards[r] = []; this.cardTexts[r] = []; this.cardBgs[r] = [];
      for (let c = 0; c < COLS; c++) {
        this.cards[r][c] = { emoji: emojis[idx++], flipped: false, matched: false };
        this.cardTexts[r][c] = null; this.cardBgs[r][c] = null;
      }
    }

    this.add.text(width / 2, 30, 'LẬT THẺ NHỚ', { fontFamily: BRAND.fonts.game, fontSize: '20px', color: BRAND.colors.primary }).setOrigin(0.5);
    this.scoreText = this.add.text(width / 2, 75, 'Moves: 0 | Pairs: 0/10', { fontFamily: BRAND.fonts.ui, fontSize: '16px', color: BRAND.colors.light }).setOrigin(0.5);

    this.renderCards();

    this.input.on('pointerdown', (p: Phaser.Input.Pointer) => {
      if (this.isChecking) return;
      const c = Math.floor((p.x - this.boardX) / (CELL + GAP));
      const r = Math.floor((p.y - this.boardY) / (CELL + GAP));
      if (r < 0 || r >= ROWS || c < 0 || c >= COLS) return;
      this.flipCard(r, c);
    });

    this.add.text(width / 2, height - 20, 'lamgame.vn', { fontFamily: BRAND.fonts.ui, fontSize: '12px', color: '#444' }).setOrigin(0.5);
  }

  private flipCard(r: number, c: number) {
    const card = this.cards[r][c];
    if (card.flipped || card.matched || this.flippedCards.length >= 2) return;
    card.flipped = true;
    this.flippedCards.push({ r, c });
    this.renderCards();

    if (this.flippedCards.length === 2) {
      this.moves++;
      this.isChecking = true;
      const [a, b] = this.flippedCards;
      if (this.cards[a.r][a.c].emoji === this.cards[b.r][b.c].emoji) {
        this.cards[a.r][a.c].matched = true;
        this.cards[b.r][b.c].matched = true;
        this.score++;
        this.flippedCards = [];
        this.isChecking = false;
        this.scoreText.setText(`Moves: ${this.moves} | Pairs: ${this.score}/10`);
        if (this.score === COLS * ROWS / 2) {
          this.time.delayedCall(500, () => this.scene.start('GameOver', { score: 1000 - this.moves * 10, gameKey: 'lat-the-nho' }));
        }
      } else {
        this.time.delayedCall(600, () => {
          this.cards[a.r][a.c].flipped = false;
          this.cards[b.r][b.c].flipped = false;
          this.flippedCards = [];
          this.isChecking = false;
          this.renderCards();
        });
      }
      this.scoreText.setText(`Moves: ${this.moves} | Pairs: ${this.score}/10`);
    }
  }

  private renderCards() {
    this.cardBgs.forEach(row => row.forEach(b => b?.destroy()));
    this.cardTexts.forEach(row => row.forEach(t => t?.destroy()));
    for (let r = 0; r < ROWS; r++) for (let c = 0; c < COLS; c++) {
      const x = this.boardX + c * (CELL + GAP), y = this.boardY + r * (CELL + GAP);
      const card = this.cards[r][c];
      const color = card.matched ? 0x00D68F : card.flipped ? 0x3a3a5e : 0x444466;
      this.cardBgs[r][c] = this.add.rectangle(x + CELL / 2, y + CELL / 2, CELL, CELL, color).setStrokeStyle(2, 0x6C63FF);
      if (card.flipped || card.matched) {
        this.cardTexts[r][c] = this.add.text(x + CELL / 2, y + CELL / 2, card.emoji, { fontSize: '40px' }).setOrigin(0.5);
      } else {
        this.cardTexts[r][c] = this.add.text(x + CELL / 2, y + CELL / 2, '?', { fontFamily: BRAND.fonts.game, fontSize: '28px', color: '#6C63FF' }).setOrigin(0.5);
      }
    }
  }
}
