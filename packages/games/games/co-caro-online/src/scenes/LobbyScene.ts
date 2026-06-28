import Phaser from 'phaser';
import { BRAND } from '@lamgame/shared';

export class LobbyScene extends Phaser.Scene {
  private roomInput!: string;

  constructor() {
    super({ key: 'Lobby' });
    this.roomInput = '';
  }

  create() {
    const { width, height } = this.scale;

    this.add.text(width / 2, 80, 'CỜ CARO ONLINE', {
      fontFamily: BRAND.fonts.game, fontSize: '24px', color: BRAND.colors.primary,
    }).setOrigin(0.5);

    this.add.text(width / 2, 140, '15×15 • Thắng 5 liên tiếp', {
      fontFamily: BRAND.fonts.ui, fontSize: '16px', color: BRAND.colors.light,
    }).setOrigin(0.5);

    // Create room button
    const createBtn = this.add.text(width / 2, 280, '🎮 TẠO PHÒNG', {
      fontFamily: BRAND.fonts.ui, fontSize: '22px', color: '#FFF',
      backgroundColor: BRAND.colors.primary, padding: { x: 32, y: 16 },
    }).setOrigin(0.5).setInteractive({ useHandCursor: true });

    createBtn.on('pointerdown', () => this.createRoom());

    // Join section
    this.add.text(width / 2, 420, 'Hoặc nhập mã phòng:', {
      fontFamily: BRAND.fonts.ui, fontSize: '16px', color: '#AAA',
    }).setOrigin(0.5);

    const codeDisplay = this.add.text(width / 2, 480, '______', {
      fontFamily: BRAND.fonts.game, fontSize: '28px', color: BRAND.colors.text,
      backgroundColor: '#333', padding: { x: 24, y: 12 },
    }).setOrigin(0.5);

    const joinBtn = this.add.text(width / 2, 560, '🚪 THAM GIA', {
      fontFamily: BRAND.fonts.ui, fontSize: '20px', color: '#FFF',
      backgroundColor: BRAND.colors.success, padding: { x: 28, y: 14 },
    }).setOrigin(0.5).setInteractive({ useHandCursor: true });

    joinBtn.on('pointerdown', () => {
      if (this.roomInput.length === 6) this.joinRoom(this.roomInput);
    });

    // Keyboard input for room code
    this.input.keyboard?.on('keydown', (e: KeyboardEvent) => {
      if (e.key === 'Backspace') {
        this.roomInput = this.roomInput.slice(0, -1);
      } else if (e.key.length === 1 && this.roomInput.length < 6) {
        this.roomInput += e.key.toUpperCase();
      }
      codeDisplay.setText(this.roomInput.padEnd(6, '_'));
    });

    this.add.text(width / 2, height - 40, 'lamgame.vn', {
      fontFamily: BRAND.fonts.ui, fontSize: '14px', color: '#666',
    }).setOrigin(0.5);
  }

  private async createRoom() {
    const name = 'Player' + Math.floor(Math.random() * 999);
    const res = await fetch('/api/games/rooms', {
      method: 'POST', headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ player: name }),
    });
    const data = await res.json();
    this.scene.start('Game', { code: data.code, player: 'x', name });
  }

  private async joinRoom(code: string) {
    const name = 'Player' + Math.floor(Math.random() * 999);
    const res = await fetch(`/api/games/rooms/${code}/join`, {
      method: 'POST', headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ player: name }),
    });
    if (res.ok) {
      const data = await res.json();
      this.scene.start('Game', { code: data.code, player: 'o', name });
    }
  }
}
