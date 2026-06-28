import Phaser from 'phaser';
import { BRAND } from '../config';

/**
 * GameOver Scene — Score, best score, leaderboard, share, play again
 */
export class GameOverScene extends Phaser.Scene {
  constructor() {
    super({ key: 'GameOver' });
  }

  create(data: { score: number; gameKey: string }) {
    const { width, height } = this.scale;
    const { score, gameKey } = data;

    // Best score
    const bestKey = `lamgame_best_${gameKey}`;
    const best = Math.max(score, parseInt(localStorage.getItem(bestKey) || '0'));
    localStorage.setItem(bestKey, best.toString());

    // Submit to leaderboard API
    const playerName = localStorage.getItem('lamgame_player') || 'Guest';
    this.submitScore(gameKey, playerName, score);

    this.cameras.main.setBackgroundColor(0x1a1a2e);

    // Game Over title
    this.add.text(width / 2, height * 0.25, 'GAME OVER', {
      fontFamily: BRAND.fonts.game,
      fontSize: '28px',
      color: BRAND.colors.secondary,
    }).setOrigin(0.5);

    // Score
    this.add.text(width / 2, height * 0.4, `Score: ${score}`, {
      fontFamily: BRAND.fonts.game,
      fontSize: '20px',
      color: BRAND.colors.text,
    }).setOrigin(0.5);

    this.add.text(width / 2, height * 0.47, `Best: ${best}`, {
      fontFamily: BRAND.fonts.ui,
      fontSize: '16px',
      color: BRAND.colors.primary,
    }).setOrigin(0.5);

    // Play Again button
    const btn = this.add.text(width / 2, height * 0.6, '▶ CHƠI LẠI', {
      fontFamily: BRAND.fonts.ui,
      fontSize: '20px',
      color: BRAND.colors.dark,
      backgroundColor: BRAND.colors.primary,
      padding: { x: 24, y: 12 },
    }).setOrigin(0.5).setInteractive({ useHandCursor: true });

    btn.on('pointerdown', () => this.scene.start('Game'));

    // Share button
    const shareBtn = this.add.text(width / 2, height * 0.7, '📤 Chia sẻ', {
      fontFamily: BRAND.fonts.ui,
      fontSize: '16px',
      color: BRAND.colors.light,
    }).setOrigin(0.5).setInteractive({ useHandCursor: true });

    shareBtn.on('pointerdown', () => {
      const text = `Tôi đạt ${score} điểm tại ${gameKey} trên LamGame.vn! 🎮`;
      if (navigator.share) {
        navigator.share({ title: 'LamGame Score', text, url: BRAND.url });
      } else {
        navigator.clipboard.writeText(text);
      }
    });

    // Footer
    this.add.text(width / 2, height * 0.9, 'Made with ❤️ by LamGame Team', {
      fontFamily: BRAND.fonts.ui,
      fontSize: '12px',
      color: '#666',
    }).setOrigin(0.5);
  }

  private submitScore(gameKey: string, player: string, score: number) {
    fetch(`/api/games/${gameKey}/leaderboard`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ player, score }),
    }).catch(() => {});
  }
}
