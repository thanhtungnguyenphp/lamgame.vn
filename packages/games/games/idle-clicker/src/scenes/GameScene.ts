import Phaser from 'phaser';
import { BRAND } from '@lamgame/shared';

interface Upgrade {
  name: string;
  cost: number;
  cps: number; // coins per second
  count: number;
  emoji: string;
}

export class GameScene extends Phaser.Scene {
  private coins = 0;
  private cps = 0; // coins per second (passive)
  private clickPower = 1;
  private coinsText!: Phaser.GameObjects.Text;
  private cpsText!: Phaser.GameObjects.Text;
  private upgrades: Upgrade[] = [];
  private upgradeTexts: Phaser.GameObjects.Text[] = [];

  constructor() { super({ key: 'Game' }); }

  create() {
    const { width, height } = this.scale;
    this.coins = 0;
    this.cps = 0;
    this.clickPower = 1;

    // Load saved progress
    this.loadProgress();

    // Initialize upgrades
    this.upgrades = [
      { name: 'Auto Tap', cost: 10, cps: 1, count: 0, emoji: '👆' },
      { name: 'Worker', cost: 50, cps: 5, count: 0, emoji: '👷' },
      { name: 'Robot', cost: 200, cps: 20, count: 0, emoji: '🤖' },
      { name: 'Factory', cost: 1000, cps: 100, count: 0, emoji: '🏭' },
      { name: 'Mine', cost: 5000, cps: 500, count: 0, emoji: '⛏️' },
      { name: 'Portal', cost: 25000, cps: 2500, count: 0, emoji: '🌀' },
    ];

    // Header
    this.add.text(width / 2, 40, '💰 IDLE CLICKER', {
      fontFamily: BRAND.fonts.game, fontSize: '22px', color: BRAND.colors.primary,
    }).setOrigin(0.5);

    this.coinsText = this.add.text(width / 2, 100, this.formatNumber(this.coins) + ' 🪙', {
      fontFamily: BRAND.fonts.ui, fontSize: '36px', color: '#FFD700', fontStyle: 'bold',
    }).setOrigin(0.5);

    this.cpsText = this.add.text(width / 2, 140, `${this.formatNumber(this.cps)}/giây`, {
      fontFamily: BRAND.fonts.ui, fontSize: '14px', color: '#AAA',
    }).setOrigin(0.5);

    // Click button (big coin)
    const coinBtn = this.add.text(width / 2, 280, '🪙', {
      fontSize: '100px',
    }).setOrigin(0.5).setInteractive({ useHandCursor: true });

    coinBtn.on('pointerdown', () => {
      this.coins += this.clickPower;
      this.updateUI();
      // Tap animation
      this.tweens.add({ targets: coinBtn, scale: 0.85, duration: 50, yoyo: true });
      // Float text
      const ft = this.add.text(width / 2 + Phaser.Math.Between(-30, 30), 220, `+${this.clickPower}`, {
        fontFamily: BRAND.fonts.ui, fontSize: '18px', color: '#FFD700',
      }).setOrigin(0.5);
      this.tweens.add({ targets: ft, y: 180, alpha: 0, duration: 600, onComplete: () => ft.destroy() });
    });

    // Click power upgrade
    const clickUpBtn = this.add.text(width / 2, 380, `👆 Tap Power (x${this.clickPower}) — 💰${this.getClickUpgradeCost()}`, {
      fontFamily: BRAND.fonts.ui, fontSize: '14px', color: '#FFF',
      backgroundColor: '#333', padding: { x: 16, y: 8 },
    }).setOrigin(0.5).setInteractive({ useHandCursor: true });

    clickUpBtn.on('pointerdown', () => {
      const cost = this.getClickUpgradeCost();
      if (this.coins >= cost) {
        this.coins -= cost;
        this.clickPower++;
        clickUpBtn.setText(`👆 Tap Power (x${this.clickPower}) — 💰${this.getClickUpgradeCost()}`);
        this.updateUI();
      }
    });

    // Upgrades list
    const startY = 440;
    this.upgrades.forEach((up, i) => {
      const y = startY + i * 55;
      const txt = this.add.text(width / 2, y, this.getUpgradeLabel(up), {
        fontFamily: BRAND.fonts.ui, fontSize: '13px', color: '#EEE',
        backgroundColor: '#2A2A40', padding: { x: 14, y: 8 },
        wordWrap: { width: 340 },
      }).setOrigin(0.5).setInteractive({ useHandCursor: true });

      txt.on('pointerdown', () => {
        if (this.coins >= up.cost) {
          this.coins -= up.cost;
          up.count++;
          up.cost = Math.floor(up.cost * 1.4);
          this.cps += up.cps;
          txt.setText(this.getUpgradeLabel(up));
          this.updateUI();
        }
      });
      this.upgradeTexts.push(txt);
    });

    // Passive income timer
    this.time.addEvent({
      delay: 1000,
      callback: () => {
        this.coins += this.cps;
        this.updateUI();
        this.saveProgress();
      },
      loop: true,
    });

    // Footer
    this.add.text(width / 2, height - 30, 'lamgame.vn', {
      fontFamily: BRAND.fonts.ui, fontSize: '12px', color: '#555',
    }).setOrigin(0.5);
  }

  private getClickUpgradeCost(): number {
    return Math.floor(10 * Math.pow(1.5, this.clickPower - 1));
  }

  private getUpgradeLabel(up: Upgrade): string {
    return `${up.emoji} ${up.name} (${up.count}) — +${up.cps}/s — 💰${this.formatNumber(up.cost)}`;
  }

  private updateUI() {
    this.coinsText.setText(this.formatNumber(this.coins) + ' 🪙');
    this.cpsText.setText(`${this.formatNumber(this.cps)}/giây`);
  }

  private formatNumber(n: number): string {
    if (n >= 1e9) return (n / 1e9).toFixed(1) + 'B';
    if (n >= 1e6) return (n / 1e6).toFixed(1) + 'M';
    if (n >= 1e3) return (n / 1e3).toFixed(1) + 'K';
    return Math.floor(n).toString();
  }

  private saveProgress() {
    const data = { coins: this.coins, cps: this.cps, clickPower: this.clickPower, upgrades: this.upgrades.map(u => u.count) };
    localStorage.setItem('lamgame_idle', JSON.stringify(data));
  }

  private loadProgress() {
    const raw = localStorage.getItem('lamgame_idle');
    if (!raw) return;
    try {
      const data = JSON.parse(raw);
      this.coins = data.coins || 0;
      this.cps = data.cps || 0;
      this.clickPower = data.clickPower || 1;
    } catch {}
  }
}
