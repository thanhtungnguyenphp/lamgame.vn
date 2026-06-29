import Phaser from 'phaser';
import { BRAND } from '../config';

/**
 * Splash Scene — Hiển thị logo LamGame 2s rồi chuyển sang game
 */
export class SplashScene extends Phaser.Scene {
  constructor() {
    super({ key: 'Splash' });
  }

  create() {
    const { width, height } = this.scale;

    // Background
    this.cameras.main.setBackgroundColor(BRAND.colors.dark);

    // Logo text
    const logo = this.add.text(width / 2, height / 2 - 40, '🎮 LamGame', {
      fontFamily: BRAND.fonts.game,
      fontSize: '32px',
      color: BRAND.colors.primary,
    }).setOrigin(0.5);

    // Tagline
    this.add.text(width / 2, height / 2 + 20, 'Game Dev Community Vietnam', {
      fontFamily: BRAND.fonts.ui,
      fontSize: '16px',
      color: BRAND.colors.light,
    }).setOrigin(0.5);

    // Fade in + transition
    this.cameras.main.fadeIn(500);
    this.time.delayedCall(2000, () => {
      this.cameras.main.fadeOut(300);
      this.time.delayedCall(300, () => this.scene.start('Game'));
    });
  }
}
