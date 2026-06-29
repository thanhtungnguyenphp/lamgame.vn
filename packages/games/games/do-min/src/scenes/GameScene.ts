import Phaser from 'phaser';
import { BRAND } from '@lamgame/shared';

const COLS = 9, ROWS = 14, MINES = 20, CELL = 48;

export class GameScene extends Phaser.Scene {
  private board: number[][] = []; // -1=mine, 0-8=count
  private revealed: boolean[][] = [];
  private flagged: boolean[][] = [];
  private boardX = 0; private boardY = 0;
  private graphics!: Phaser.GameObjects.Graphics;
  private texts: (Phaser.GameObjects.Text | null)[][] = [];
  private mineText!: Phaser.GameObjects.Text;
  private gameActive = true;
  private firstClick = true;
  private startTime = 0;

  constructor() { super({ key: 'Game' }); }

  create() {
    const { width, height } = this.scale;
    this.boardX = (width - COLS * CELL) / 2;
    this.boardY = 130;
    this.gameActive = true; this.firstClick = true;
    this.revealed = Array.from({ length: ROWS }, () => Array(COLS).fill(false));
    this.flagged = Array.from({ length: ROWS }, () => Array(COLS).fill(false));
    this.texts = Array.from({ length: ROWS }, () => Array(COLS).fill(null));
    this.graphics = this.add.graphics();

    this.add.text(width / 2, 30, 'DÒ MÌN', { fontFamily: BRAND.fonts.game, fontSize: '22px', color: BRAND.colors.primary }).setOrigin(0.5);
    this.mineText = this.add.text(width / 2, 75, `💣 ${MINES}`, { fontFamily: BRAND.fonts.ui, fontSize: '18px', color: BRAND.colors.light }).setOrigin(0.5);

    this.add.text(width / 2, 100, 'Nhấn giữ = Cắm cờ', { fontFamily: BRAND.fonts.ui, fontSize: '12px', color: '#666' }).setOrigin(0.5);

    this.drawBoard();

    let downTime = 0;
    this.input.on('pointerdown', () => { downTime = Date.now(); });
    this.input.on('pointerup', (p: Phaser.Input.Pointer) => {
      if (!this.gameActive) return;
      const c = Math.floor((p.x - this.boardX) / CELL);
      const r = Math.floor((p.y - this.boardY) / CELL);
      if (r < 0 || r >= ROWS || c < 0 || c >= COLS) return;
      if (Date.now() - downTime > 300) this.toggleFlag(r, c);
      else this.reveal(r, c);
    });

    this.add.text(width / 2, height - 20, 'lamgame.vn', { fontFamily: BRAND.fonts.ui, fontSize: '12px', color: '#444' }).setOrigin(0.5);
  }

  private generateBoard(safeR: number, safeC: number) {
    this.board = Array.from({ length: ROWS }, () => Array(COLS).fill(0));
    let placed = 0;
    while (placed < MINES) {
      const r = Phaser.Math.Between(0, ROWS - 1), c = Phaser.Math.Between(0, COLS - 1);
      if (this.board[r][c] === -1 || (Math.abs(r - safeR) <= 1 && Math.abs(c - safeC) <= 1)) continue;
      this.board[r][c] = -1; placed++;
    }
    for (let r = 0; r < ROWS; r++)
      for (let c = 0; c < COLS; c++) {
        if (this.board[r][c] === -1) continue;
        let count = 0;
        for (let dr = -1; dr <= 1; dr++) for (let dc = -1; dc <= 1; dc++) {
          const nr = r + dr, nc = c + dc;
          if (nr >= 0 && nr < ROWS && nc >= 0 && nc < COLS && this.board[nr][nc] === -1) count++;
        }
        this.board[r][c] = count;
      }
  }

  private reveal(r: number, c: number) {
    if (this.flagged[r][c] || this.revealed[r][c]) return;
    if (this.firstClick) { this.generateBoard(r, c); this.firstClick = false; this.startTime = Date.now(); }

    this.revealed[r][c] = true;
    if (this.board[r][c] === -1) { this.gameActive = false; this.revealAll(); this.time.delayedCall(800, () => this.scene.start('GameOver', { score: 0, gameKey: 'do-min' })); return; }
    if (this.board[r][c] === 0) {
      for (let dr = -1; dr <= 1; dr++) for (let dc = -1; dc <= 1; dc++) {
        const nr = r + dr, nc = c + dc;
        if (nr >= 0 && nr < ROWS && nc >= 0 && nc < COLS) this.reveal(nr, nc);
      }
    }
    this.drawBoard();
    if (this.checkWin()) { this.gameActive = false; const t = Math.floor((Date.now() - this.startTime) / 1000); this.time.delayedCall(500, () => this.scene.start('GameOver', { score: 1000 - t, gameKey: 'do-min' })); }
  }

  private toggleFlag(r: number, c: number) {
    if (this.revealed[r][c]) return;
    this.flagged[r][c] = !this.flagged[r][c];
    const flags = this.flagged.flat().filter(f => f).length;
    this.mineText.setText(`💣 ${MINES - flags}`);
    this.drawBoard();
  }

  private checkWin(): boolean {
    for (let r = 0; r < ROWS; r++) for (let c = 0; c < COLS; c++)
      if (this.board[r][c] !== -1 && !this.revealed[r][c]) return false;
    return true;
  }

  private revealAll() { for (let r = 0; r < ROWS; r++) for (let c = 0; c < COLS; c++) this.revealed[r][c] = true; this.drawBoard(); }

  private drawBoard() {
    this.graphics.clear();
    this.texts.forEach(row => row.forEach(t => t?.destroy()));
    const numColors = ['', '#3B82F6', '#22C55E', '#EF4444', '#1E40AF', '#991B1B', '#0D9488', '#000', '#666'];

    for (let r = 0; r < ROWS; r++) for (let c = 0; c < COLS; c++) {
      const x = this.boardX + c * CELL, y = this.boardY + r * CELL;
      if (this.revealed[r][c]) {
        this.graphics.fillStyle(this.board[r][c] === -1 ? 0xff0000 : 0x222222, 1);
        this.graphics.fillRect(x + 1, y + 1, CELL - 2, CELL - 2);
        if (this.board[r][c] === -1) {
          this.texts[r][c] = this.add.text(x + CELL / 2, y + CELL / 2, '💣', { fontSize: '22px' }).setOrigin(0.5);
        } else if (this.board[r][c] > 0) {
          this.texts[r][c] = this.add.text(x + CELL / 2, y + CELL / 2, this.board[r][c].toString(), { fontFamily: BRAND.fonts.ui, fontSize: '20px', color: numColors[this.board[r][c]], fontStyle: 'bold' }).setOrigin(0.5);
        }
      } else {
        this.graphics.fillStyle(0x444466, 1);
        this.graphics.fillRect(x + 1, y + 1, CELL - 2, CELL - 2);
        if (this.flagged[r][c]) this.texts[r][c] = this.add.text(x + CELL / 2, y + CELL / 2, '🚩', { fontSize: '20px' }).setOrigin(0.5);
      }
    }
  }
}
