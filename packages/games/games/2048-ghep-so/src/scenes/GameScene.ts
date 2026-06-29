import Phaser from 'phaser';
import { BRAND } from '@lamgame/shared';

const GRID_SIZE = 4;
const TILE_SIZE = 140;
const TILE_GAP = 12;
const BOARD_PADDING = 20;

const TILE_COLORS: Record<number, string> = {
  2: '#EEE4DA', 4: '#EDE0C8', 8: '#F2B179', 16: '#F59563',
  32: '#F67C5F', 64: '#F65E3B', 128: '#EDCF72', 256: '#EDCC61',
  512: '#EDC850', 1024: '#EDC53F', 2048: '#EDC22E',
};

const TEXT_COLORS: Record<number, string> = {
  2: '#776E65', 4: '#776E65',
};

export class GameScene extends Phaser.Scene {
  private grid: number[][] = [];
  private score = 0;
  private scoreText!: Phaser.GameObjects.Text;
  private tileSprites: (Phaser.GameObjects.Container | null)[][] = [];
  private boardX = 0;
  private boardY = 0;
  private isAnimating = false;

  constructor() {
    super({ key: 'Game' });
  }

  create() {
    const { width, height } = this.scale;
    this.score = 0;
    this.isAnimating = false;

    // Header
    this.add.text(width / 2, 60, '2048', {
      fontFamily: BRAND.fonts.game,
      fontSize: '48px',
      color: BRAND.colors.primary,
    }).setOrigin(0.5);

    this.scoreText = this.add.text(width / 2, 120, 'Score: 0', {
      fontFamily: BRAND.fonts.ui,
      fontSize: '20px',
      color: BRAND.colors.light,
    }).setOrigin(0.5);

    // Board position
    const boardSize = GRID_SIZE * TILE_SIZE + (GRID_SIZE + 1) * TILE_GAP;
    this.boardX = (width - boardSize) / 2;
    this.boardY = 180;

    // Draw board background
    const bg = this.add.graphics();
    bg.fillStyle(0x776e65, 1);
    bg.fillRoundedRect(this.boardX, this.boardY, boardSize, boardSize, 8);

    // Draw empty cells
    for (let r = 0; r < GRID_SIZE; r++) {
      for (let c = 0; c < GRID_SIZE; c++) {
        const x = this.boardX + TILE_GAP + c * (TILE_SIZE + TILE_GAP);
        const y = this.boardY + TILE_GAP + r * (TILE_SIZE + TILE_GAP);
        const cell = this.add.graphics();
        cell.fillStyle(0xcdc1b4, 1);
        cell.fillRoundedRect(x, y, TILE_SIZE, TILE_SIZE, 6);
      }
    }

    // Init grid
    this.grid = Array.from({ length: GRID_SIZE }, () => Array(GRID_SIZE).fill(0));
    this.tileSprites = Array.from({ length: GRID_SIZE }, () => Array(GRID_SIZE).fill(null));
    this.addRandomTile();
    this.addRandomTile();
    this.renderTiles();

    // Input — swipe + keyboard
    this.input.on('pointerdown', (p: Phaser.Input.Pointer) => { (this as any)._swipeStart = { x: p.x, y: p.y }; });
    this.input.on('pointerup', (p: Phaser.Input.Pointer) => {
      const start = (this as any)._swipeStart;
      if (!start) return;
      const dx = p.x - start.x;
      const dy = p.y - start.y;
      const absDx = Math.abs(dx);
      const absDy = Math.abs(dy);
      if (Math.max(absDx, absDy) < 30) return;
      if (absDx > absDy) this.move(dx > 0 ? 'right' : 'left');
      else this.move(dy > 0 ? 'down' : 'up');
    });

    this.input.keyboard?.on('keydown', (e: KeyboardEvent) => {
      const map: Record<string, string> = {
        ArrowUp: 'up', ArrowDown: 'down', ArrowLeft: 'left', ArrowRight: 'right',
      };
      if (map[e.key]) this.move(map[e.key] as any);
    });

    // Footer
    this.add.text(width / 2, height - 40, 'lamgame.vn', {
      fontFamily: BRAND.fonts.ui, fontSize: '14px', color: '#666',
    }).setOrigin(0.5);
  }

  private move(dir: 'up' | 'down' | 'left' | 'right') {
    if (this.isAnimating) return;

    const prev = this.grid.map(r => [...r]);
    let moved = false;

    const rotate = (grid: number[][], times: number) => {
      let g = grid.map(r => [...r]);
      for (let t = 0; t < times; t++) {
        const n = g.map((_, i) => g.map(row => row[i]).reverse());
        g = n;
      }
      return g;
    };

    const rotations: Record<string, number> = { left: 0, up: 1, right: 2, down: 3 };
    const rot = rotations[dir];

    let g = rotate(this.grid, rot);
    // Slide left
    for (let r = 0; r < GRID_SIZE; r++) {
      let row = g[r].filter(v => v !== 0);
      for (let i = 0; i < row.length - 1; i++) {
        if (row[i] === row[i + 1]) {
          row[i] *= 2;
          this.score += row[i];
          row.splice(i + 1, 1);
        }
      }
      while (row.length < GRID_SIZE) row.push(0);
      g[r] = row;
    }
    this.grid = rotate(g, (4 - rot) % 4);

    // Check if moved
    for (let r = 0; r < GRID_SIZE; r++)
      for (let c = 0; c < GRID_SIZE; c++)
        if (this.grid[r][c] !== prev[r][c]) moved = true;

    if (moved) {
      this.addRandomTile();
      this.renderTiles();
      this.scoreText.setText(`Score: ${this.score}`);
      if (this.isGameOver()) {
        this.time.delayedCall(300, () => {
          this.scene.start('GameOver', { score: this.score, gameKey: '2048-ghep-so' });
        });
      }
    }
  }

  private addRandomTile() {
    const empty: [number, number][] = [];
    for (let r = 0; r < GRID_SIZE; r++)
      for (let c = 0; c < GRID_SIZE; c++)
        if (this.grid[r][c] === 0) empty.push([r, c]);
    if (empty.length === 0) return;
    const [r, c] = empty[Math.floor(Math.random() * empty.length)];
    this.grid[r][c] = Math.random() < 0.9 ? 2 : 4;
  }

  private renderTiles() {
    // Clear existing
    for (let r = 0; r < GRID_SIZE; r++)
      for (let c = 0; c < GRID_SIZE; c++) {
        this.tileSprites[r][c]?.destroy();
        this.tileSprites[r][c] = null;
      }

    for (let r = 0; r < GRID_SIZE; r++) {
      for (let c = 0; c < GRID_SIZE; c++) {
        const val = this.grid[r][c];
        if (val === 0) continue;

        const x = this.boardX + TILE_GAP + c * (TILE_SIZE + TILE_GAP);
        const y = this.boardY + TILE_GAP + r * (TILE_SIZE + TILE_GAP);

        const color = Phaser.Display.Color.HexStringToColor(TILE_COLORS[val] || '#3C3A32').color;
        const textColor = TEXT_COLORS[val] || '#FFFFFF';

        const bg = this.add.graphics();
        bg.fillStyle(color, 1);
        bg.fillRoundedRect(0, 0, TILE_SIZE, TILE_SIZE, 6);

        const fontSize = val >= 1024 ? '28px' : val >= 128 ? '34px' : '40px';
        const text = this.add.text(TILE_SIZE / 2, TILE_SIZE / 2, val.toString(), {
          fontFamily: BRAND.fonts.ui,
          fontSize,
          color: textColor,
          fontStyle: 'bold',
        }).setOrigin(0.5);

        const container = this.add.container(x, y, [bg, text]);
        this.tileSprites[r][c] = container;
      }
    }
  }

  private isGameOver(): boolean {
    for (let r = 0; r < GRID_SIZE; r++)
      for (let c = 0; c < GRID_SIZE; c++) {
        if (this.grid[r][c] === 0) return false;
        if (c < GRID_SIZE - 1 && this.grid[r][c] === this.grid[r][c + 1]) return false;
        if (r < GRID_SIZE - 1 && this.grid[r][c] === this.grid[r + 1][c]) return false;
      }
    return true;
  }
}
