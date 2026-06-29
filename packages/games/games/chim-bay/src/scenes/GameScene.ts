import Phaser from 'phaser';
import { BRAND } from '@lamgame/shared';

const GRAVITY = 800;
const FLAP_VELOCITY = -320;
const PIPE_SPEED = -200;
const PIPE_GAP = 180;
const PIPE_INTERVAL = 1800;
const BIRD_X = 120;

export class GameScene extends Phaser.Scene {
  private bird!: Phaser.GameObjects.Arc;
  private pipes!: Phaser.Physics.Arcade.Group;
  private score = 0;
  private scoreText!: Phaser.GameObjects.Text;
  private pipeTimer!: Phaser.Time.TimerEvent;
  private gameActive = true;

  constructor() {
    super({ key: 'Game' });
  }

  create() {
    const { width, height } = this.scale;
    this.score = 0;
    this.gameActive = true;

    // Background gradient
    this.cameras.main.setBackgroundColor(0x87ceeb);

    // Ground
    const ground = this.add.rectangle(width / 2, height - 30, width, 60, 0x8B4513);
    this.physics.add.existing(ground, true);

    // Bird
    this.bird = this.add.circle(BIRD_X, height / 2, 16, 0xFFD700);
    this.physics.add.existing(this.bird);
    const body = this.bird.body as Phaser.Physics.Arcade.Body;
    body.setGravityY(GRAVITY);
    body.setCircle(16);
    body.setCollideWorldBounds(true);

    // Pipes group
    this.pipes = this.physics.add.group();

    // Collision
    this.physics.add.collider(this.bird, ground, () => this.gameOver());
    this.physics.add.overlap(this.bird, this.pipes, () => this.gameOver());

    // Score
    this.scoreText = this.add.text(width / 2, 60, '0', {
      fontFamily: BRAND.fonts.game, fontSize: '48px', color: '#fff',
      stroke: '#000', strokeThickness: 4,
    }).setOrigin(0.5).setDepth(10);

    // Title hint
    this.add.text(width / 2, height / 2 + 80, 'TAP / CLICK / SPACE', {
      fontFamily: BRAND.fonts.ui, fontSize: '16px', color: '#fff',
    }).setOrigin(0.5).setAlpha(0.7);

    // Pipe spawner
    this.pipeTimer = this.time.addEvent({
      delay: PIPE_INTERVAL,
      callback: this.spawnPipe,
      callbackScope: this,
      loop: true,
    });

    // Input
    this.input.on('pointerdown', () => this.flap());
    this.input.keyboard?.on('keydown-SPACE', () => this.flap());

    // Footer
    this.add.text(width / 2, height - 8, 'lamgame.vn', {
      fontFamily: BRAND.fonts.ui, fontSize: '11px', color: '#555',
    }).setOrigin(0.5);
  }

  update() {
    if (!this.gameActive) return;

    // Rotate bird based on velocity
    const body = this.bird.body as Phaser.Physics.Arcade.Body;
    const angle = Phaser.Math.Clamp(body.velocity.y / 10, -30, 90);
    this.bird.setRotation(Phaser.Math.DegToRad(angle));

    // Score when passing pipes
    this.pipes.getChildren().forEach((pipe: any) => {
      if (!pipe.scored && pipe.x + pipe.width < BIRD_X) {
        pipe.scored = true;
        this.score += 0.5; // 2 pipes = 1 point
        if (this.score === Math.floor(this.score)) {
          this.scoreText.setText(this.score.toString());
        }
      }
    });

    // Remove off-screen pipes
    this.pipes.getChildren().forEach((pipe: any) => {
      if (pipe.x < -80) pipe.destroy();
    });
  }

  private flap() {
    if (!this.gameActive) return;
    const body = this.bird.body as Phaser.Physics.Arcade.Body;
    body.setVelocityY(FLAP_VELOCITY);
  }

  private spawnPipe() {
    if (!this.gameActive) return;
    const { width, height } = this.scale;
    const gapY = Phaser.Math.Between(150, height - 200);

    // Top pipe
    const topH = gapY - PIPE_GAP / 2;
    const top = this.add.rectangle(width + 30, topH / 2, 60, topH, 0x2ECC71);
    this.physics.add.existing(top);
    (top.body as Phaser.Physics.Arcade.Body).setVelocityX(PIPE_SPEED);
    (top.body as Phaser.Physics.Arcade.Body).setImmovable(true);
    (top.body as Phaser.Physics.Arcade.Body).allowGravity = false;
    this.pipes.add(top);

    // Bottom pipe
    const botY = gapY + PIPE_GAP / 2;
    const botH = height - 60 - botY;
    const bot = this.add.rectangle(width + 30, botY + botH / 2, 60, botH, 0x2ECC71);
    this.physics.add.existing(bot);
    (bot.body as Phaser.Physics.Arcade.Body).setVelocityX(PIPE_SPEED);
    (bot.body as Phaser.Physics.Arcade.Body).setImmovable(true);
    (bot.body as Phaser.Physics.Arcade.Body).allowGravity = false;
    this.pipes.add(bot);
  }

  private gameOver() {
    if (!this.gameActive) return;
    this.gameActive = false;
    this.pipeTimer.destroy();
    this.physics.pause();
    this.bird.setFillStyle(0xFF0000);
    this.time.delayedCall(500, () => {
      this.scene.start('GameOver', { score: Math.floor(this.score), gameKey: 'chim-bay' });
    });
  }
}
