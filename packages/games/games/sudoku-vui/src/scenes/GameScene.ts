import Phaser from 'phaser';
import { BRAND } from '@lamgame/shared';

const SIZE = 9;
const CELL = 65;
const BOARD_PAD = 20;

export class GameScene extends Phaser.Scene {
  private puzzle: number[][] = [];
  private solution: number[][] = [];
  private playerGrid: number[][] = [];
  private fixed: boolean[][] = [];
  private selected: { r: number; c: number } | null = null;
  private cellTexts: Phaser.GameObjects.Text[][] = [];
  private boardX = 0;
  private boardY = 0;
  private errors = 0;
  private timer = 0;
  private timerText!: Phaser.GameObjects.Text;
  private errText!: Phaser.GameObjects.Text;
  private graphics!: Phaser.GameObjects.Graphics;

  constructor() {
    super({ key: 'Game' });
  }

  create() {
    const { width, height } = this.scale;
    this.errors = 0;
    this.timer = 0;
    this.selected = null;

    this.boardX = (width - SIZE * CELL) / 2;
    this.boardY = 140;
    this.graphics = this.add.graphics();

    // Generate puzzle
    this.generatePuzzle('medium');

    // UI
    this.add.text(width / 2, 30, 'SUDOKU', {
      fontFamily: BRAND.fonts.game, fontSize: '24px', color: BRAND.colors.primary,
    }).setOrigin(0.5);

    this.timerText = this.add.text(width / 2 - 80, 75, '⏱ 0:00', {
      fontFamily: BRAND.fonts.ui, fontSize: '16px', color: BRAND.colors.light,
    });

    this.errText = this.add.text(width / 2 + 40, 75, '❌ 0/3', {
      fontFamily: BRAND.fonts.ui, fontSize: '16px', color: '#FF6584',
    });

    // Draw board
    this.drawBoard();
    this.renderNumbers();

    // Number pad (1-9 + clear)
    const padY = this.boardY + SIZE * CELL + 40;
    for (let i = 1; i <= 9; i++) {
      const x = this.boardX + (i - 1) * (CELL + 2);
      const btn = this.add.text(x + CELL / 2, padY, i.toString(), {
        fontFamily: BRAND.fonts.ui, fontSize: '28px', color: BRAND.colors.light,
        backgroundColor: '#333', padding: { x: 18, y: 10 },
      }).setOrigin(0.5).setInteractive({ useHandCursor: true });
      btn.on('pointerdown', () => this.enterNumber(i));
    }

    // Clear button
    this.add.text(width / 2, padY + 70, '⌫ Xóa', {
      fontFamily: BRAND.fonts.ui, fontSize: '18px', color: '#aaa',
    }).setOrigin(0.5).setInteractive({ useHandCursor: true })
      .on('pointerdown', () => this.enterNumber(0));

    // Board click
    this.input.on('pointerdown', (p: Phaser.Input.Pointer) => {
      const c = Math.floor((p.x - this.boardX) / CELL);
      const r = Math.floor((p.y - this.boardY) / CELL);
      if (r >= 0 && r < SIZE && c >= 0 && c < SIZE && !this.fixed[r][c]) {
        this.selected = { r, c };
        this.drawBoard();
      }
    });

    // Keyboard
    this.input.keyboard?.on('keydown', (e: KeyboardEvent) => {
      const num = parseInt(e.key);
      if (num >= 1 && num <= 9) this.enterNumber(num);
      if (e.key === 'Backspace' || e.key === 'Delete') this.enterNumber(0);
    });

    this.add.text(width / 2, height - 20, 'lamgame.vn', {
      fontFamily: BRAND.fonts.ui, fontSize: '12px', color: '#444',
    }).setOrigin(0.5);
  }

  update(_: number, delta: number) {
    this.timer += delta;
    const s = Math.floor(this.timer / 1000);
    this.timerText.setText(`⏱ ${Math.floor(s / 60)}:${(s % 60).toString().padStart(2, '0')}`);
  }

  private enterNumber(num: number) {
    if (!this.selected) return;
    const { r, c } = this.selected;
    if (this.fixed[r][c]) return;

    if (num === 0) {
      this.playerGrid[r][c] = 0;
    } else {
      this.playerGrid[r][c] = num;
      if (num !== this.solution[r][c]) {
        this.errors++;
        this.errText.setText(`❌ ${this.errors}/3`);
        if (this.errors >= 3) {
          this.time.delayedCall(300, () => {
            this.scene.start('GameOver', { score: Math.floor(this.timer / 1000), gameKey: 'sudoku-vui' });
          });
          return;
        }
      }
    }

    this.renderNumbers();
    this.drawBoard();

    // Check win
    if (this.checkWin()) {
      this.time.delayedCall(500, () => {
        this.scene.start('GameOver', { score: 1000 - Math.floor(this.timer / 1000) - this.errors * 100, gameKey: 'sudoku-vui' });
      });
    }
  }

  private checkWin(): boolean {
    for (let r = 0; r < SIZE; r++)
      for (let c = 0; c < SIZE; c++)
        if (this.playerGrid[r][c] !== this.solution[r][c]) return false;
    return true;
  }

  private drawBoard() {
    this.graphics.clear();

    // Background
    this.graphics.fillStyle(0x1a1a2e, 1);
    this.graphics.fillRect(this.boardX, this.boardY, SIZE * CELL, SIZE * CELL);

    // Selected cell highlight
    if (this.selected) {
      this.graphics.fillStyle(0x6C63FF, 0.3);
      this.graphics.fillRect(
        this.boardX + this.selected.c * CELL,
        this.boardY + this.selected.r * CELL, CELL, CELL
      );
    }

    // Grid lines
    for (let i = 0; i <= SIZE; i++) {
      const thick = i % 3 === 0 ? 3 : 1;
      const color = i % 3 === 0 ? 0xffffff : 0x444444;
      this.graphics.lineStyle(thick, color);
      this.graphics.lineBetween(this.boardX, this.boardY + i * CELL, this.boardX + SIZE * CELL, this.boardY + i * CELL);
      this.graphics.lineBetween(this.boardX + i * CELL, this.boardY, this.boardX + i * CELL, this.boardY + SIZE * CELL);
    }
  }

  private renderNumbers() {
    // Clear old
    this.cellTexts.forEach(row => row.forEach(t => t?.destroy()));
    this.cellTexts = Array.from({ length: SIZE }, () => Array(SIZE).fill(null));

    for (let r = 0; r < SIZE; r++)
      for (let c = 0; c < SIZE; c++) {
        const val = this.playerGrid[r][c];
        if (val === 0) continue;
        const x = this.boardX + c * CELL + CELL / 2;
        const y = this.boardY + r * CELL + CELL / 2;
        const isFixed = this.fixed[r][c];
        const isWrong = !isFixed && val !== this.solution[r][c];
        const color = isFixed ? '#FFFFFF' : isWrong ? '#FF6584' : BRAND.colors.primary;
        this.cellTexts[r][c] = this.add.text(x, y, val.toString(), {
          fontFamily: BRAND.fonts.ui, fontSize: '26px', color, fontStyle: isFixed ? 'bold' : 'normal',
        }).setOrigin(0.5);
      }
  }

  private generatePuzzle(difficulty: 'easy' | 'medium' | 'hard') {
    // Generate valid completed grid
    this.solution = Array.from({ length: SIZE }, () => Array(SIZE).fill(0));
    this.fillGrid(this.solution);

    // Remove cells based on difficulty
    const remove = { easy: 35, medium: 45, hard: 55 }[difficulty];
    this.puzzle = this.solution.map(r => [...r]);
    this.fixed = Array.from({ length: SIZE }, () => Array(SIZE).fill(true));

    let removed = 0;
    while (removed < remove) {
      const r = Phaser.Math.Between(0, 8);
      const c = Phaser.Math.Between(0, 8);
      if (this.puzzle[r][c] !== 0) {
        this.puzzle[r][c] = 0;
        this.fixed[r][c] = false;
        removed++;
      }
    }

    this.playerGrid = this.puzzle.map(r => [...r]);
  }

  private fillGrid(grid: number[][]): boolean {
    for (let r = 0; r < SIZE; r++)
      for (let c = 0; c < SIZE; c++) {
        if (grid[r][c] !== 0) continue;
        const nums = Phaser.Utils.Array.Shuffle([1,2,3,4,5,6,7,8,9]);
        for (const n of nums) {
          if (this.isValid(grid, r, c, n)) {
            grid[r][c] = n;
            if (this.fillGrid(grid)) return true;
            grid[r][c] = 0;
          }
        }
        return false;
      }
    return true;
  }

  private isValid(grid: number[][], row: number, col: number, num: number): boolean {
    for (let i = 0; i < SIZE; i++) {
      if (grid[row][i] === num || grid[i][col] === num) return false;
    }
    const br = Math.floor(row / 3) * 3, bc = Math.floor(col / 3) * 3;
    for (let r = br; r < br + 3; r++)
      for (let c = bc; c < bc + 3; c++)
        if (grid[r][c] === num) return false;
    return true;
  }
}
