import Phaser from 'phaser';
import { BRAND } from '@lamgame/shared';

const GRID = 20;
const COLS = 18;
const ROWS = 28;
const BASE_SPEED = 150;

export class GameScene extends Phaser.Scene {
  private snake: { x: number; y: number }[] = [];
  private direction = { x: 0, y: -1 };
  private nextDir = { x: 0, y: -1 };
  private food = { x: 0, y: 0 };
  private score = 0;
  private scoreText!: Phaser.GameObjects.Text;
  private graphics!: Phaser.GameObjects.Graphics;
  private moveTimer = 0;
  private boardX = 0;
  private boardY = 0;
  private gameActive = true;

  constructor() {
    super({ key: 'Game' });
  }

  create() {
    const { width, height } = this.scale;
    this.score = 0;
    this.gameActive = true;
    this.boardX = (width - COLS * GRID) / 2;
    this.boardY = 100;
    this.graphics = this.add.graphics();

    // Init snake
    const cx = Math.floor(COLS / 2), cy = Math.floor(ROWS / 2);
    this.snake = [{ x: cx, y: cy }, { x: cx, y: cy + 1 }, { x: cx, y: cy + 2 }];
    this.direction = { x: 0, y: -1 };
    this.nextDir = { x: 0, y: -1 };

    this.spawnFood();

    // UI
    this.add.text(width / 2, 30, 'RẮN SĂN MỒI', {
      fontFamily: BRAND.fonts.game, fontSize: '20px', color: BRAND.colors.primary,
    }).setOrigin(0.5);

    this.scoreText = this.add.text(width / 2, 65, 'Score: 0', {
      fontFamily: BRAND.fonts.ui, fontSize: '16px', color: BRAND.colors.light,
    }).setOrigin(0.5);

    // Keyboard
    this.input.keyboard?.on('keydown', (e: KeyboardEvent) => {
      switch (e.key) {
        case 'ArrowUp': if (this.direction.y !== 1) this.nextDir = { x: 0, y: -1 }; break;
        case 'ArrowDown': if (this.direction.y !== -1) this.nextDir = { x: 0, y: 1 }; break;
        case 'ArrowLeft': if (this.direction.x !== 1) this.nextDir = { x: -1, y: 0 }; break;
        case 'ArrowRight': if (this.direction.x !== -1) this.nextDir = { x: 1, y: 0 }; break;
      }
    });

    // Swipe
    let sx = 0, sy = 0;
    this.input.on('pointerdown', (p: Phaser.Input.Pointer) => { sx = p.x; sy = p.y; });
    this.input.on('pointerup', (p: Phaser.Input.Pointer) => {
      const dx = p.x - sx, dy = p.y - sy;
      if (Math.max(Math.abs(dx), Math.abs(dy)) < 30) return;
      if (Math.abs(dx) > Math.abs(dy)) {
        if (dx > 0 && this.direction.x !== -1) this.nextDir = { x: 1, y: 0 };
        else if (dx < 0 && this.direction.x !== 1) this.nextDir = { x: -1, y: 0 };
      } else {
        if (dy > 0 && this.direction.y !== -1) this.nextDir = { x: 0, y: 1 };
        else if (dy < 0 && this.direction.y !== 1) this.nextDir = { x: 0, y: -1 };
      }
    });

    this.add.text(width / 2, height - 20, 'lamgame.vn', {
      fontFamily: BRAND.fonts.ui, fontSize: '12px', color: '#555',
    }).setOrigin(0.5);
  }

  update(_: number, delta: number) {
    if (!this.gameActive) return;
    this.moveTimer += delta;
    const speed = Math.max(50, BASE_SPEED - this.score * 2);
    if (this.moveTimer < speed) { this.draw(); return; }
    this.moveTimer = 0;

    this.direction = { ...this.nextDir };
    const head = this.snake[0];
    const nx = head.x + this.direction.x;
    const ny = head.y + this.direction.y;

    // Collision check
    if (nx < 0 || nx >= COLS || ny < 0 || ny >= ROWS || this.snake.some(s => s.x === nx && s.y === ny)) {
      this.gameActive = false;
      this.scene.start('GameOver', { score: this.score, gameKey: 'ran-san-moi' });
      return;
    }

    this.snake.unshift({ x: nx, y: ny });

    // Eat food
    if (nx === this.food.x && ny === this.food.y) {
      this.score += 10;
      this.scoreText.setText(`Score: ${this.score}`);
      this.spawnFood();
    } else {
      this.snake.pop();
    }

    this.draw();
  }

  private spawnFood() {
    let pos: { x: number; y: number };
    do {
      pos = { x: Phaser.Math.Between(0, COLS - 1), y: Phaser.Math.Between(0, ROWS - 1) };
    } while (this.snake.some(s => s.x === pos.x && s.y === pos.y));
    this.food = pos;
  }

  private draw() {
    this.graphics.clear();

    // Board
    this.graphics.fillStyle(0x111111, 1);
    this.graphics.fillRect(this.boardX, this.boardY, COLS * GRID, ROWS * GRID);

    // Food
    this.graphics.fillStyle(0xFF6584, 1);
    this.graphics.fillCircle(
      this.boardX + this.food.x * GRID + GRID / 2,
      this.boardY + this.food.y * GRID + GRID / 2, GRID / 2 - 2
    );

    // Snake
    this.snake.forEach((s, i) => {
      const color = i === 0 ? 0x00D68F : 0x6C63FF;
      this.graphics.fillStyle(color, 1);
      this.graphics.fillRoundedRect(
        this.boardX + s.x * GRID + 1, this.boardY + s.y * GRID + 1,
        GRID - 2, GRID - 2, 4
      );
    });
  }
}
