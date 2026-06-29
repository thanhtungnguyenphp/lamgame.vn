export const BRAND = {
  name: 'LamGame',
  url: 'https://lamgame.vn',
  colors: {
    primary: '#6C63FF',
    secondary: '#FF6584',
    dark: '#1A1A2E',
    light: '#F8F9FA',
    success: '#00D68F',
    text: '#FFFFFF',
  },
  fonts: {
    ui: 'Inter',
    game: '"Press Start 2P"',
  },
} as const;

export const GAME_CONFIG: Phaser.Types.Core.GameConfig = {
  type: Phaser.AUTO,
  scale: {
    mode: Phaser.Scale.FIT,
    autoCenter: Phaser.Scale.CENTER_BOTH,
    width: 720,
    height: 1280,
  },
  backgroundColor: BRAND.colors.dark,
  physics: {
    default: 'arcade',
    arcade: { debug: false },
  },
};
