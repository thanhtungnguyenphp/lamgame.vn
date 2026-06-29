import Phaser from 'phaser';
import { BRAND } from '@lamgame/shared';

const GRID = 8;
const CELL = 72;
const GAP = 4;
const TYPES = 6;
const COLORS = [0xFF6584, 0x6C63FF, 0x00D68F, 0xFFD700, 0xFF8C42, 0x00CED1];
const MOVES_LIMIT = 30;

export class GameScene extends Phaser.Scene {
  private board: number[][] = [];
  private gems: (Phaser.GameObjects.Arc | null)[][] = [];
  private selected: { r: number; c: number } | null = null;
  private score = 0;
  private moves = MOVES_LIMIT;
  private scoreText!: Phaser.GameObjects.Text;
  private movesText!: Phaser.GameObjects.Text;
  private boardX = 0;
  private boardY = 0;
  private isProcessing = false;

  constructor() {
    super({ key: 'Game' });
  }

  create() {
    const { width, height } = this.scale;
    this.score = 0;
    this.moves = MOVES_LIMIT;
    this.isProcessing = false;
    this.selected = null;

    const boardSize = GRID * (CELL + GAP) + GAP;
    this.boardX = (width - boardSize) / 2;
    this.boardY = 160;

    // UI
    this.add.text(width / 2, 30, 'KẸO NGỌT XẾP 3', {
      fontFamily: BRAND.fonts.game, fontSize: '18px', color: BRAND.colors.primary,
    }).setOrigin(0.5);

    this.scoreText = this.add.text(width / 2, 70, 'Score: 0', {
      fontFamily: BRAND.fonts.ui, fontSize: '20px', color: BRAND.colors.light,
    }).setOrigin(0.5);

    this.movesText = this.add.text(width / 2, 100, `Moves: ${this.moves}`, {
      fontFamily: BRAND.fonts.ui, fontSize: '16px', color: '#aaa',
    }).setOrigin(0.5);

    // Board background
    const bg = this.add.graphics();
    bg.fillStyle(0x222222, 1);
    bg.fillRoundedRect(this.boardX - GAP, this.boardY - GAP, boardSize, boardSize, 8);

    // Init board (no initial matches)
    this.board = Array.from({ length: GRID }, () => Array(GRID).fill(0));
    this.gems = Array.from({ length: GRID }, () => Array(GRID).fill(null));
    this.fillBoard();
    while (this.findMatches().length > 0) this.fillBoard();
    this.renderBoard();

    // Input
    this.input.on('pointerdown', (p: Phaser.Input.Pointer) => {
      if (this.isProcessing) return;
      const c = Math.floor((p.x - this.boardX) / (CELL + GAP));
      const r = Math.floor((p.y - this.boardY) / (CELL + GAP));
      if (r < 0 || r >= GRID || c < 0 || c >= GRID) return;
      this.handleSelect(r, c);
    });

    this.add.text(width / 2, height - 20, 'lamgame.vn', {
      fontFamily: BRAND.fonts.ui, fontSize: '12px', color: '#555',
    }).setOrigin(0.5);
  }

  private fillBoard() {
    for (let r = 0; r < GRID; r++)
      for (let c = 0; c < GRID; c++)
        this.board[r][c] = Phaser.Math.Between(0, TYPES - 1);
  }

  private renderBoard() {
    // Clear old gems
    for (let r = 0; r < GRID; r++)
      for (let c = 0; c < GRID; c++) {
        this.gems[r][c]?.destroy();
        this.gems[r][c] = null;
      }

    for (let r = 0; r < GRID; r++)
      for (let c = 0; c < GRID; c++) {
        if (this.board[r][c] < 0) continue;
        const x = this.boardX + c * (CELL + GAP) + CELL / 2;
        const y = this.boardY + r * (CELL + GAP) + CELL / 2;
        const gem = this.add.circle(x, y, CELL / 2 - 4, COLORS[this.board[r][c]]);
        gem.setInteractive();
        this.gems[r][c] = gem;
      }
  }

  private handleSelect(r: number, c: number) {
    if (!this.selected) {
      this.selected = { r, c };
      this.gems[r][c]?.setStrokeStyle(3, 0xffffff);
      return;
    }

    const prev = this.selected;
    this.gems[prev.r][prev.c]?.setStrokeStyle(0);
    this.selected = null;

    // Check adjacent
    const dr = Math.abs(r - prev.r), dc = Math.abs(c - prev.c);
    if ((dr === 1 && dc === 0) || (dr === 0 && dc === 1)) {
      this.swap(prev.r, prev.c, r, c);
      const matches = this.findMatches();
      if (matches.length > 0) {
        this.moves--;
        this.movesText.setText(`Moves: ${this.moves}`);
        this.processMatches(matches);
      } else {
        this.swap(prev.r, prev.c, r, c); // swap back
        this.renderBoard();
      }
    }
  }

  private swap(r1: number, c1: number, r2: number, c2: number) {
    [this.board[r1][c1], this.board[r2][c2]] = [this.board[r2][c2], this.board[r1][c1]];
  }

  private findMatches(): { r: number; c: number }[] {
    const matched = new Set<string>();
    // Horizontal
    for (let r = 0; r < GRID; r++)
      for (let c = 0; c < GRID - 2; c++) {
        const t = this.board[r][c];
        if (t >= 0 && t === this.board[r][c+1] && t === this.board[r][c+2]) {
          matched.add(`${r},${c}`); matched.add(`${r},${c+1}`); matched.add(`${r},${c+2}`);
        }
      }
    // Vertical
    for (let c = 0; c < GRID; c++)
      for (let r = 0; r < GRID - 2; r++) {
        const t = this.board[r][c];
        if (t >= 0 && t === this.board[r+1][c] && t === this.board[r+2][c]) {
          matched.add(`${r},${c}`); matched.add(`${r+1},${c}`); matched.add(`${r+2},${c}`);
        }
      }
    return [...matched].map(s => { const [r,c] = s.split(',').map(Number); return {r,c}; });
  }

  private processMatches(matches: { r: number; c: number }[]) {
    this.isProcessing = true;
    this.score += matches.length * 10;
    this.scoreText.setText(`Score: ${this.score}`);

    // Remove matched
    matches.forEach(({ r, c }) => { this.board[r][c] = -1; });

    // Gravity
    for (let c = 0; c < GRID; c++) {
      const col = [];
      for (let r = GRID - 1; r >= 0; r--)
        if (this.board[r][c] >= 0) col.push(this.board[r][c]);
      for (let r = GRID - 1; r >= 0; r--)
        this.board[r][c] = col.length > (GRID - 1 - r) ? col[GRID - 1 - r] : Phaser.Math.Between(0, TYPES - 1);
    }

    this.renderBoard();

    // Chain check
    this.time.delayedCall(200, () => {
      const newMatches = this.findMatches();
      if (newMatches.length > 0) {
        this.processMatches(newMatches);
      } else {
        this.isProcessing = false;
        if (this.moves <= 0) {
          this.scene.start('GameOver', { score: this.score, gameKey: 'keo-ngot-xep-3' });
        }
      }
    });
  }
}
