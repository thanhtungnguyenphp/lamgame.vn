import Phaser from 'phaser';
import { BRAND } from '@lamgame/shared';

const GROUND_Y = 1050;
const PLAYER_X = 150;
const SCROLL_SPEED = 350;
const JUMP_VELOCITY = -580;
const GRAVITY = 1800;

type Obstacle = { type: 'spike' | 'block' | 'gap'; x: number };

export class GameScene extends Phaser.Scene {
  private player!: Phaser.GameObjects.Rectangle;
  private playerBody!: Phaser.Physics.Arcade.Body;
  private obstacles!: Phaser.Physics.Arcade.Group;
  private ground!: Phaser.GameObjects.Rectangle;
  private score = 0;
  private scoreText!: Phaser.GameObjects.Text;
  private gameActive = true;
  private spawnTimer = 0;
  private bgGraphics!: Phaser.GameObjects.Graphics;
  private distance = 0;

  constructor() {
    super({ key: 'Game' });
  }

  create() {
    const { width, height } = this.scale;
    this.score = 0;
    this.distance = 0;
    this.gameActive = true;
    this.spawnTimer = 0;

    this.cameras.main.setBackgroundColor(BRAND.colors.dark);

    // Scrolling background lines
    this.bgGraphics = this.add.graphics();

    // Ground
    this.ground = this.add.rectangle(width / 2, GROUND_Y + 25, width, 50, 0x333355);
    this.physics.add.existing(this.ground, true);

    // Ground line
    this.add.rectangle(width / 2, GROUND_Y, width, 3, Phaser.Display.Color.HexStringToColor(BRAND.colors.primary).color);

    // Player (square)
    this.player = this.add.rectangle(PLAYER_X, GROUND_Y - 25, 40, 40, Phaser.Display.Color.HexStringToColor(BRAND.colors.primary).color);
    this.physics.add.existing(this.player);
    this.playerBody = this.player.body as Phaser.Physics.Arcade.Body;
    this.playerBody.setGravityY(GRAVITY);
    this.playerBody.setCollideWorldBounds(false);

    // Obstacles group
    this.obstacles = this.physics.add.group();

    // Collisions
    this.physics.add.collider(this.player, this.ground);
    this.physics.add.overlap(this.player, this.obstacles, () => this.die());

    // UI
    this.scoreText = this.add.text(width / 2, 50, '0%', {
      fontFamily: BRAND.fonts.game, fontSize: '28px', color: BRAND.colors.light,
    }).setOrigin(0.5);

    this.add.text(width / 2, 90, 'TAP TO JUMP', {
      fontFamily: BRAND.fonts.ui, fontSize: '14px', color: '#666',
    }).setOrigin(0.5);

    // Input
    this.input.on('pointerdown', () => this.jump());
    this.input.keyboard?.on('keydown-SPACE', () => this.jump());
    this.input.keyboard?.on('keydown-UP', () => this.jump());

    this.add.text(width / 2, height - 20, 'lamgame.vn', {
      fontFamily: BRAND.fonts.ui, fontSize: '12px', color: '#444',
    }).setOrigin(0.5);
  }

  update(_: number, delta: number) {
    if (!this.gameActive) return;

    this.distance += SCROLL_SPEED * (delta / 1000);
    this.score = Math.floor(this.distance / 50);
    this.scoreText.setText(`${Math.min(this.score, 100)}%`);

    // Rotate player while jumping
    if (!this.playerBody.touching.down) {
      this.player.rotation += 0.08;
    } else {
      this.player.rotation = 0;
    }

    // Spawn obstacles
    this.spawnTimer += delta;
    const spawnRate = Math.max(600, 1200 - this.score * 5);
    if (this.spawnTimer > spawnRate) {
      this.spawnTimer = 0;
      this.spawnObstacle();
    }

    // Remove off-screen
    this.obstacles.getChildren().forEach((obj: any) => {
      if (obj.x < -100) obj.destroy();
    });

    // Die if fall
    if (this.player.y > GROUND_Y + 200) this.die();
  }

  private jump() {
    if (!this.gameActive) return;
    if (this.playerBody.touching.down || this.playerBody.blocked.down) {
      this.playerBody.setVelocityY(JUMP_VELOCITY);
    }
  }

  private spawnObstacle() {
    const { width } = this.scale;
    const type = Phaser.Math.Between(0, 2);

    if (type === 0) {
      // Spike triangle (represented as small rect)
      const spike = this.add.rectangle(width + 50, GROUND_Y - 20, 30, 40, 0xFF6584);
      spike.setOrigin(0.5, 1);
      this.physics.add.existing(spike);
      const body = spike.body as Phaser.Physics.Arcade.Body;
      body.setVelocityX(-SCROLL_SPEED);
      body.allowGravity = false;
      body.setImmovable(true);
      this.obstacles.add(spike);
    } else if (type === 1) {
      // Double spike
      const s1 = this.add.rectangle(width + 50, GROUND_Y - 20, 30, 40, 0xFF6584);
      s1.setOrigin(0.5, 1);
      this.physics.add.existing(s1);
      (s1.body as Phaser.Physics.Arcade.Body).setVelocityX(-SCROLL_SPEED);
      (s1.body as Phaser.Physics.Arcade.Body).allowGravity = false;
      this.obstacles.add(s1);

      const s2 = this.add.rectangle(width + 90, GROUND_Y - 20, 30, 40, 0xFF6584);
      s2.setOrigin(0.5, 1);
      this.physics.add.existing(s2);
      (s2.body as Phaser.Physics.Arcade.Body).setVelocityX(-SCROLL_SPEED);
      (s2.body as Phaser.Physics.Arcade.Body).allowGravity = false;
      this.obstacles.add(s2);
    } else {
      // Floating block
      const block = this.add.rectangle(width + 50, GROUND_Y - 120, 60, 30, 0xFF6584);
      this.physics.add.existing(block);
      (block.body as Phaser.Physics.Arcade.Body).setVelocityX(-SCROLL_SPEED);
      (block.body as Phaser.Physics.Arcade.Body).allowGravity = false;
      (block.body as Phaser.Physics.Arcade.Body).setImmovable(true);
      this.obstacles.add(block);
    }
  }

  private die() {
    if (!this.gameActive) return;
    this.gameActive = false;
    this.physics.pause();
    this.player.setFillStyle(0xff0000);
    this.cameras.main.shake(200, 0.01);
    this.time.delayedCall(600, () => {
      this.scene.start('GameOver', { score: this.score, gameKey: 'nhay-hinh-hoc' });
    });
  }
}
