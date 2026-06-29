import Phaser from 'phaser';
import { BRAND } from '@lamgame/shared';

const CELL = 48, COLS = 15, ROWS = 20;
const PATH = [[0,3],[1,3],[2,3],[3,3],[3,4],[3,5],[3,6],[3,7],[4,7],[5,7],[6,7],[7,7],[7,8],[7,9],[7,10],[7,11],[8,11],[9,11],[10,11],[11,11],[11,10],[11,9],[11,8],[11,7],[11,6],[12,6],[13,6],[14,6],[14,7],[14,8],[14,9],[14,10],[14,11],[14,12],[14,13],[14,14]];

interface Enemy { x: number; y: number; hp: number; maxHp: number; pathIdx: number; speed: number }
interface Tower { r: number; c: number; range: number; damage: number; cooldown: number; lastShot: number }

export class GameScene extends Phaser.Scene {
  private enemies: Enemy[] = [];
  private towers: Tower[] = [];
  private lives = 10; private gold = 100; private wave = 0; private score = 0;
  private graphics!: Phaser.GameObjects.Graphics;
  private boardX = 0; private boardY = 0;
  private waveTimer = 0; private spawnTimer = 0; private toSpawn = 0;
  private goldText!: Phaser.GameObjects.Text;
  private livesText!: Phaser.GameObjects.Text;
  private waveText!: Phaser.GameObjects.Text;

  constructor() { super({ key: 'Game' }); }

  create() {
    const { width, height } = this.scale;
    this.enemies = []; this.towers = [];
    this.lives = 10; this.gold = 100; this.wave = 0; this.score = 0; this.toSpawn = 0;
    this.boardX = (width - COLS * CELL) / 2; this.boardY = 100;
    this.graphics = this.add.graphics();

    this.add.text(width / 2, 20, 'PHÒNG THỦ', { fontFamily: BRAND.fonts.game, fontSize: '18px', color: BRAND.colors.primary }).setOrigin(0.5);
    this.goldText = this.add.text(20, 50, '💰 100', { fontFamily: BRAND.fonts.ui, fontSize: '14px', color: '#FFD700' });
    this.livesText = this.add.text(width / 2, 50, '❤️ 10', { fontFamily: BRAND.fonts.ui, fontSize: '14px', color: '#FF6584' }).setOrigin(0.5);
    this.waveText = this.add.text(width - 20, 50, 'Wave 0', { fontFamily: BRAND.fonts.ui, fontSize: '14px', color: '#aaa' }).setOrigin(1, 0);

    this.add.text(width / 2, 75, 'Tap ô trống = Đặt tháp (💰20)', { fontFamily: BRAND.fonts.ui, fontSize: '11px', color: '#666' }).setOrigin(0.5);

    this.input.on('pointerdown', (p: Phaser.Input.Pointer) => {
      const c = Math.floor((p.x - this.boardX) / CELL);
      const r = Math.floor((p.y - this.boardY) / CELL);
      if (r < 0 || r >= ROWS || c < 0 || c >= COLS) return;
      this.placeTower(r, c);
    });

    this.waveTimer = 2000;
    this.add.text(width / 2, height - 15, 'lamgame.vn', { fontFamily: BRAND.fonts.ui, fontSize: '11px', color: '#444' }).setOrigin(0.5);
  }

  update(_: number, delta: number) {
    // Wave spawning
    this.waveTimer -= delta;
    if (this.waveTimer <= 0 && this.toSpawn <= 0) {
      this.wave++; this.toSpawn = 3 + this.wave * 2; this.waveTimer = 15000;
      this.waveText.setText(`Wave ${this.wave}`);
    }
    if (this.toSpawn > 0) {
      this.spawnTimer -= delta;
      if (this.spawnTimer <= 0) {
        this.spawnEnemy(); this.toSpawn--; this.spawnTimer = 500;
      }
    }

    // Move enemies
    for (const e of this.enemies) {
      if (e.hp <= 0) continue;
      const target = PATH[e.pathIdx];
      if (!target) { e.hp = 0; this.lives--; this.livesText.setText(`❤️ ${this.lives}`); continue; }
      const tx = this.boardX + target[1] * CELL + CELL / 2;
      const ty = this.boardY + target[0] * CELL + CELL / 2;
      const dx = tx - e.x, dy = ty - e.y;
      const dist = Math.sqrt(dx * dx + dy * dy);
      if (dist < 5) { e.pathIdx++; } else {
        e.x += (dx / dist) * e.speed * (delta / 1000);
        e.y += (dy / dist) * e.speed * (delta / 1000);
      }
    }

    // Towers shoot
    const now = Date.now();
    for (const t of this.towers) {
      if (now - t.lastShot < t.cooldown) continue;
      const tx = this.boardX + t.c * CELL + CELL / 2;
      const ty = this.boardY + t.r * CELL + CELL / 2;
      for (const e of this.enemies) {
        if (e.hp <= 0) continue;
        const d = Math.sqrt((e.x - tx) ** 2 + (e.y - ty) ** 2);
        if (d <= t.range) { e.hp -= t.damage; t.lastShot = now; if (e.hp <= 0) { this.gold += 5; this.score += 10; this.goldText.setText(`💰 ${this.gold}`); } break; }
      }
    }

    // Remove dead
    this.enemies = this.enemies.filter(e => e.hp > 0);

    if (this.lives <= 0) { this.scene.start('GameOver', { score: this.score, gameKey: 'phong-thu' }); return; }

    this.draw();
  }

  private spawnEnemy() {
    const p = PATH[0];
    this.enemies.push({ x: this.boardX + p[1] * CELL + CELL / 2, y: this.boardY + p[0] * CELL + CELL / 2, hp: 30 + this.wave * 10, maxHp: 30 + this.wave * 10, pathIdx: 1, speed: 80 + this.wave * 5 });
  }

  private placeTower(r: number, c: number) {
    if (this.gold < 20) return;
    if (PATH.some(([pr, pc]) => pr === r && pc === c)) return;
    if (this.towers.some(t => t.r === r && t.c === c)) return;
    this.gold -= 20; this.goldText.setText(`💰 ${this.gold}`);
    this.towers.push({ r, c, range: CELL * 2.5, damage: 15, cooldown: 800, lastShot: 0 });
  }

  private draw() {
    this.graphics.clear();
    // Board
    this.graphics.fillStyle(0x1a1a1a, 1);
    this.graphics.fillRect(this.boardX, this.boardY, COLS * CELL, ROWS * CELL);
    // Path
    for (const [r, c] of PATH) {
      this.graphics.fillStyle(0x333355, 1);
      this.graphics.fillRect(this.boardX + c * CELL, this.boardY + r * CELL, CELL, CELL);
    }
    // Towers
    for (const t of this.towers) {
      this.graphics.fillStyle(0x6C63FF, 1);
      this.graphics.fillCircle(this.boardX + t.c * CELL + CELL / 2, this.boardY + t.r * CELL + CELL / 2, 16);
    }
    // Enemies
    for (const e of this.enemies) {
      this.graphics.fillStyle(0xFF6584, 1);
      this.graphics.fillCircle(e.x, e.y, 10);
      // HP bar
      this.graphics.fillStyle(0x333333, 1); this.graphics.fillRect(e.x - 12, e.y - 16, 24, 4);
      this.graphics.fillStyle(0x00D68F, 1); this.graphics.fillRect(e.x - 12, e.y - 16, 24 * (e.hp / e.maxHp), 4);
    }
  }
}
