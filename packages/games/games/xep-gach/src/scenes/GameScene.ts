import Phaser from 'phaser';
import { BRAND } from '@lamgame/shared';

const COLS = 10;
const ROWS = 20;
const CELL = 32;
const DROP_INTERVAL = 800;

const SHAPES: number[][][] = [
  [[1,1,1,1]],                          // I
  [[1,1],[1,1]],                        // O
  [[0,1,0],[1,1,1]],                    // T
  [[1,0,0],[1,1,1]],                    // L
  [[0,0,1],[1,1,1]],                    // J
  [[0,1,1],[1,1,0]],                    // S
  [[1,1,0],[0,1,1]],                    // Z
];

const COLORS = [0x00f0f0, 0xf0f000, 0xa000f0, 0xf0a000, 0x0000f0, 0x00f000, 0xf00000];

export class GameScene extends Phaser.Scene {
  private board: number[][] = [];
  private current!: { shape: number[][]; x: number; y: number; color: number };
  private score = 0;
  private lines = 0;
  private level = 1;
  private scoreText!: Phaser.GameObjects.Text;
  private linesText!: Phaser.GameObjects.Text;
  private graphics!: Phaser.GameObjects.Graphics;
  private dropTimer = 0;
  private boardX = 0;
  private boardY = 0;
  private gameActive = true;

  constructor() {
    super({ key: 'Game' });
  }

  create() {
    const { width, height } = this.scale;
    this.score = 0;
    this.lines = 0;
    this.level = 1;
    this.gameActive = true;

    this.boardX = (width - COLS * CELL) / 2;
    this.boardY = 100;
    this.board = Array.from({ length: ROWS }, () => Array(COLS).fill(0));
    this.graphics = this.add.graphics();

    // UI
    this.add.text(width / 2, 30, 'XẾP GẠCH', {
      fontFamily: BRAND.fonts.game, fontSize: '24px', color: BRAND.colors.primary,
    }).setOrigin(0.5);

    this.scoreText = this.add.text(width / 2, 65, 'Score: 0', {
      fontFamily: BRAND.fonts.ui, fontSize: '16px', color: BRAND.colors.light,
    }).setOrigin(0.5);

    this.linesText = this.add.text(width - 20, 100, 'Lines: 0', {
      fontFamily: BRAND.fonts.ui, fontSize: '14px', color: '#aaa',
    }).setOrigin(1, 0);

    this.spawnPiece();
    this.dropTimer = 0;

    // Keyboard
    this.input.keyboard?.on('keydown', (e: KeyboardEvent) => {
      if (!this.gameActive) return;
      switch (e.key) {
        case 'ArrowLeft': this.movePiece(-1, 0); break;
        case 'ArrowRight': this.movePiece(1, 0); break;
        case 'ArrowDown': this.movePiece(0, 1); break;
        case 'ArrowUp': this.rotatePiece(); break;
        case ' ': this.hardDrop(); break;
      }
    });

    // Touch controls
    let startX = 0, startY = 0, startTime = 0;
    this.input.on('pointerdown', (p: Phaser.Input.Pointer) => {
      startX = p.x; startY = p.y; startTime = Date.now();
    });
    this.input.on('pointerup', (p: Phaser.Input.Pointer) => {
      if (!this.gameActive) return;
      const dx = p.x - startX, dy = p.y - startY;
      const dt = Date.now() - startTime;
      if (dt < 200 && Math.abs(dx) < 20 && Math.abs(dy) < 20) {
        this.rotatePiece();
      } else if (Math.abs(dx) > Math.abs(dy) && Math.abs(dx) > 30) {
        this.movePiece(dx > 0 ? 1 : -1, 0);
      } else if (dy > 50) {
        this.hardDrop();
      }
    });

    // Footer
    this.add.text(width / 2, height - 20, 'lamgame.vn', {
      fontFamily: BRAND.fonts.ui, fontSize: '12px', color: '#555',
    }).setOrigin(0.5);
  }

  update(_: number, delta: number) {
    if (!this.gameActive) return;
    this.dropTimer += delta;
    const speed = Math.max(100, DROP_INTERVAL - (this.level - 1) * 80);
    if (this.dropTimer >= speed) {
      this.dropTimer = 0;
      if (!this.movePiece(0, 1)) {
        this.lockPiece();
        this.clearLines();
        this.spawnPiece();
        if (this.collides(this.current.shape, this.current.x, this.current.y)) {
          this.gameActive = false;
          this.scene.start('GameOver', { score: this.score, gameKey: 'xep-gach' });
        }
      }
    }
    this.draw();
  }

  private spawnPiece() {
    const idx = Phaser.Math.Between(0, SHAPES.length - 1);
    this.current = {
      shape: SHAPES[idx].map(r => [...r]),
      x: Math.floor((COLS - SHAPES[idx][0].length) / 2),
      y: 0,
      color: COLORS[idx],
    };
  }

  private movePiece(dx: number, dy: number): boolean {
    const nx = this.current.x + dx;
    const ny = this.current.y + dy;
    if (!this.collides(this.current.shape, nx, ny)) {
      this.current.x = nx;
      this.current.y = ny;
      return true;
    }
    return false;
  }

  private rotatePiece() {
    const rotated = this.current.shape[0].map((_, i) =>
      this.current.shape.map(row => row[i]).reverse()
    );
    if (!this.collides(rotated, this.current.x, this.current.y)) {
      this.current.shape = rotated;
    }
  }

  private hardDrop() {
    while (this.movePiece(0, 1)) { this.score += 1; }
    this.lockPiece();
    this.clearLines();
    this.spawnPiece();
    if (this.collides(this.current.shape, this.current.x, this.current.y)) {
      this.gameActive = false;
      this.scene.start('GameOver', { score: this.score, gameKey: 'xep-gach' });
    }
  }

  private collides(shape: number[][], px: number, py: number): boolean {
    for (let r = 0; r < shape.length; r++)
      for (let c = 0; c < shape[r].length; c++) {
        if (!shape[r][c]) continue;
        const x = px + c, y = py + r;
        if (x < 0 || x >= COLS || y >= ROWS) return true;
        if (y >= 0 && this.board[y][x]) return true;
      }
    return false;
  }

  private lockPiece() {
    const { shape, x, y, color } = this.current;
    for (let r = 0; r < shape.length; r++)
      for (let c = 0; c < shape[r].length; c++)
        if (shape[r][c] && y + r >= 0)
          this.board[y + r][x + c] = color || 0xffffff;
  }

  private clearLines() {
    let cleared = 0;
    for (let r = ROWS - 1; r >= 0; r--) {
      if (this.board[r].every(c => c !== 0)) {
        this.board.splice(r, 1);
        this.board.unshift(Array(COLS).fill(0));
        cleared++;
        r++;
      }
    }
    if (cleared > 0) {
      const points = [0, 100, 300, 500, 800];
      this.score += (points[cleared] || 800) * this.level;
      this.lines += cleared;
      this.level = Math.floor(this.lines / 10) + 1;
      this.scoreText.setText(`Score: ${this.score}`);
      this.linesText.setText(`Lines: ${this.lines} | Lv.${this.level}`);
    }
  }

  private draw() {
    this.graphics.clear();

    // Board background
    this.graphics.fillStyle(0x111111, 1);
    this.graphics.fillRect(this.boardX, this.boardY, COLS * CELL, ROWS * CELL);

    // Grid lines
    this.graphics.lineStyle(1, 0x222222);
    for (let r = 0; r <= ROWS; r++)
      this.graphics.lineBetween(this.boardX, this.boardY + r * CELL, this.boardX + COLS * CELL, this.boardY + r * CELL);
    for (let c = 0; c <= COLS; c++)
      this.graphics.lineBetween(this.boardX + c * CELL, this.boardY, this.boardX + c * CELL, this.boardY + ROWS * CELL);

    // Locked cells
    for (let r = 0; r < ROWS; r++)
      for (let c = 0; c < COLS; c++)
        if (this.board[r][c]) {
          this.graphics.fillStyle(this.board[r][c], 1);
          this.graphics.fillRect(this.boardX + c * CELL + 1, this.boardY + r * CELL + 1, CELL - 2, CELL - 2);
        }

    // Ghost piece
    let ghostY = this.current.y;
    while (!this.collides(this.current.shape, this.current.x, ghostY + 1)) ghostY++;
    for (let r = 0; r < this.current.shape.length; r++)
      for (let c = 0; c < this.current.shape[r].length; c++)
        if (this.current.shape[r][c]) {
          this.graphics.fillStyle(this.current.color, 0.2);
          this.graphics.fillRect(
            this.boardX + (this.current.x + c) * CELL + 1,
            this.boardY + (ghostY + r) * CELL + 1, CELL - 2, CELL - 2
          );
        }

    // Current piece
    for (let r = 0; r < this.current.shape.length; r++)
      for (let c = 0; c < this.current.shape[r].length; c++)
        if (this.current.shape[r][c]) {
          this.graphics.fillStyle(this.current.color, 1);
          this.graphics.fillRect(
            this.boardX + (this.current.x + c) * CELL + 1,
            this.boardY + (this.current.y + r) * CELL + 1, CELL - 2, CELL - 2
          );
        }
  }
}
