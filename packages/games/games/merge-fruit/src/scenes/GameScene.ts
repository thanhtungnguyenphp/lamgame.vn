import Phaser from 'phaser';
import { BRAND } from '@lamgame/shared';

// Fruits: level 0 → level 10 (merge same → next level)
const FRUITS = [
  { emoji: '🍒', size: 24, points: 1 },
  { emoji: '🍓', size: 32, points: 3 },
  { emoji: '🍇', size: 40, points: 6 },
  { emoji: '🍊', size: 50, points: 10 },
  { emoji: '🍋', size: 58, points: 15 },
  { emoji: '🍎', size: 66, points: 21 },
  { emoji: '🍐', size: 74, points: 28 },
  { emoji: '🥝', size: 82, points: 36 },
  { emoji: '🍑', size: 90, points: 45 },
  { emoji: '🥥', size: 100, points: 55 },
  { emoji: '🍉', size: 120, points: 66 },
];

interface FruitBody {
  sprite: Phaser.GameObjects.Text;
  level: number;
  body: Phaser.Physics.Arcade.Body;
}

export class GameScene extends Phaser.Scene {
  private score = 0;
  private scoreText!: Phaser.GameObjects.Text;
  private nextLevel = 0;
  private nextText!: Phaser.GameObjects.Text;
  private fruits: FruitBody[] = [];
  private canDrop = true;
  private wallLeft = 110;
  private wallRight = 610;
  private wallBottom = 1200;
  private deathLine = 180;

  constructor() { super({ key: 'Game' }); }

  create() {
    const { width, height } = this.scale;
    this.score = 0;
    this.fruits = [];
    this.canDrop = true;
    this.nextLevel = Math.floor(Math.random() * 4);

    // Title
    this.add.text(width / 2, 30, '🍉 MERGE FRUIT', {
      fontFamily: BRAND.fonts.game, fontSize: '20px', color: BRAND.colors.primary,
    }).setOrigin(0.5);

    this.scoreText = this.add.text(width / 2, 70, 'Score: 0', {
      fontFamily: BRAND.fonts.ui, fontSize: '18px', color: '#FFD700',
    }).setOrigin(0.5);

    this.nextText = this.add.text(width / 2, 110, 'Next: ' + FRUITS[this.nextLevel].emoji, {
      fontFamily: BRAND.fonts.ui, fontSize: '16px', color: '#AAA',
    }).setOrigin(0.5);

    // Walls
    const g = this.add.graphics();
    g.fillStyle(0x333355, 1);
    g.fillRect(this.wallLeft - 10, this.deathLine, 10, this.wallBottom - this.deathLine); // left
    g.fillRect(this.wallRight, this.deathLine, 10, this.wallBottom - this.deathLine); // right
    g.fillRect(this.wallLeft - 10, this.wallBottom, this.wallRight - this.wallLeft + 20, 10); // bottom

    // Death line
    g.lineStyle(1, 0xff4444, 0.4);
    g.lineBetween(this.wallLeft, this.deathLine, this.wallRight, this.deathLine);

    // Drop on click
    this.input.on('pointerdown', (p: Phaser.Input.Pointer) => {
      if (!this.canDrop) return;
      const x = Phaser.Math.Clamp(p.x, this.wallLeft + 30, this.wallRight - 30);
      this.dropFruit(x, this.nextLevel);
      this.nextLevel = Math.floor(Math.random() * 4);
      this.nextText.setText('Next: ' + FRUITS[this.nextLevel].emoji);
      this.canDrop = false;
      this.time.delayedCall(500, () => { this.canDrop = true; });
    });

    // Collision check every frame
    this.time.addEvent({ delay: 200, callback: () => this.checkMerges(), loop: true });

    // Death check
    this.time.addEvent({ delay: 1000, callback: () => this.checkDeath(), loop: true });

    this.add.text(width / 2, height - 25, 'lamgame.vn', {
      fontFamily: BRAND.fonts.ui, fontSize: '11px', color: '#555',
    }).setOrigin(0.5);
  }

  private dropFruit(x: number, level: number) {
    const fruit = FRUITS[level];
    const sprite = this.add.text(x, this.deathLine + 20, fruit.emoji, {
      fontSize: `${fruit.size}px`,
    }).setOrigin(0.5);

    this.physics.add.existing(sprite);
    const body = sprite.body as Phaser.Physics.Arcade.Body;
    body.setCollideWorldBounds(false);
    body.setBounce(0.3);
    body.setGravityY(400);
    body.setCircle(fruit.size / 2);

    this.fruits.push({ sprite, level, body });
  }

  private checkMerges() {
    for (let i = 0; i < this.fruits.length; i++) {
      for (let j = i + 1; j < this.fruits.length; j++) {
        const a = this.fruits[i];
        const b = this.fruits[j];
        if (a.level !== b.level || a.level >= FRUITS.length - 1) continue;

        const dist = Phaser.Math.Distance.Between(a.sprite.x, a.sprite.y, b.sprite.x, b.sprite.y);
        const minDist = FRUITS[a.level].size * 0.8;

        if (dist < minDist) {
          this.mergeFruits(i, j);
          return; // one merge per tick
        }
      }
    }

    // Boundary enforcement
    for (const f of this.fruits) {
      if (f.sprite.x < this.wallLeft + FRUITS[f.level].size / 2) f.body.setVelocityX(50);
      if (f.sprite.x > this.wallRight - FRUITS[f.level].size / 2) f.body.setVelocityX(-50);
      if (f.sprite.y > this.wallBottom - FRUITS[f.level].size / 2) f.body.setVelocityY(-50);
    }
  }

  private mergeFruits(i: number, j: number) {
    const a = this.fruits[i];
    const b = this.fruits[j];
    const newLevel = a.level + 1;
    const midX = (a.sprite.x + b.sprite.x) / 2;
    const midY = (a.sprite.y + b.sprite.y) / 2;

    // Remove old
    a.sprite.destroy();
    b.sprite.destroy();
    this.fruits.splice(j, 1);
    this.fruits.splice(i, 1);

    // Create merged
    this.dropMerged(midX, midY, newLevel);
    this.score += FRUITS[newLevel].points;
    this.scoreText.setText('Score: ' + this.score);
  }

  private dropMerged(x: number, y: number, level: number) {
    const fruit = FRUITS[level];
    const sprite = this.add.text(x, y, fruit.emoji, {
      fontSize: `${fruit.size}px`,
    }).setOrigin(0.5);

    this.physics.add.existing(sprite);
    const body = sprite.body as Phaser.Physics.Arcade.Body;
    body.setBounce(0.3);
    body.setGravityY(400);
    body.setCircle(fruit.size / 2);

    // Pop animation
    this.tweens.add({ targets: sprite, scale: 1.3, duration: 100, yoyo: true });

    this.fruits.push({ sprite, level, body });
  }

  private checkDeath() {
    for (const f of this.fruits) {
      if (f.sprite.y < this.deathLine && f.body.velocity.y <= 0 && f.sprite.y > 0) {
        // Fruit above line and settled = game over
        this.scene.start('GameOver', { score: this.score, gameKey: 'merge-fruit' });
        return;
      }
    }
  }
}
