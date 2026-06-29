import Phaser from 'phaser';
import { BRAND } from '@lamgame/shared';

export class LobbyScene extends Phaser.Scene {
  private roomInput = '';
  private statusText!: Phaser.GameObjects.Text;

  constructor() {
    super({ key: 'Lobby' });
  }

  create() {
    const { width, height } = this.scale;
    this.roomInput = '';

    this.add.text(width / 2, 60, 'CỜ CARO ONLINE', {
      fontFamily: BRAND.fonts.game, fontSize: '24px', color: BRAND.colors.primary,
    }).setOrigin(0.5);

    this.add.text(width / 2, 110, '15×15 • Thắng 5 liên tiếp • Multiplayer', {
      fontFamily: BRAND.fonts.ui, fontSize: '14px', color: '#AAA',
    }).setOrigin(0.5);

    // Quick Match button
    const matchBtn = this.add.text(width / 2, 220, '⚡ TÌM ĐỐI THỦ', {
      fontFamily: BRAND.fonts.ui, fontSize: '22px', color: '#FFF',
      backgroundColor: BRAND.colors.success, padding: { x: 32, y: 16 },
    }).setOrigin(0.5).setInteractive({ useHandCursor: true });
    matchBtn.on('pointerdown', () => this.quickMatch());

    // Create Private Room
    const createBtn = this.add.text(width / 2, 320, '🎮 TẠO PHÒNG RIÊNG', {
      fontFamily: BRAND.fonts.ui, fontSize: '18px', color: '#FFF',
      backgroundColor: BRAND.colors.primary, padding: { x: 28, y: 14 },
    }).setOrigin(0.5).setInteractive({ useHandCursor: true });
    createBtn.on('pointerdown', () => this.createRoom());

    // Join section
    this.add.text(width / 2, 430, 'Nhập mã phòng:', {
      fontFamily: BRAND.fonts.ui, fontSize: '14px', color: '#888',
    }).setOrigin(0.5);

    const codeDisplay = this.add.text(width / 2, 480, '______', {
      fontFamily: BRAND.fonts.game, fontSize: '26px', color: BRAND.colors.text,
      backgroundColor: '#333', padding: { x: 24, y: 10 },
    }).setOrigin(0.5);

    const joinBtn = this.add.text(width / 2, 550, '🚪 THAM GIA', {
      fontFamily: BRAND.fonts.ui, fontSize: '18px', color: '#FFF',
      backgroundColor: '#555', padding: { x: 24, y: 12 },
    }).setOrigin(0.5).setInteractive({ useHandCursor: true });
    joinBtn.on('pointerdown', () => {
      if (this.roomInput.length === 6) this.joinRoom(this.roomInput);
    });

    this.input.keyboard?.on('keydown', (e: KeyboardEvent) => {
      if (e.key === 'Backspace') this.roomInput = this.roomInput.slice(0, -1);
      else if (e.key.length === 1 && this.roomInput.length < 6) this.roomInput += e.key.toUpperCase();
      codeDisplay.setText(this.roomInput.padEnd(6, '_'));
    });

    this.statusText = this.add.text(width / 2, 650, '', {
      fontFamily: BRAND.fonts.ui, fontSize: '14px', color: BRAND.colors.secondary,
    }).setOrigin(0.5);

    this.add.text(width / 2, height - 30, 'lamgame.vn', {
      fontFamily: BRAND.fonts.ui, fontSize: '12px', color: '#555',
    }).setOrigin(0.5);
  }

  private getPlayerName(): string {
    return localStorage.getItem('lamgame_player') || ('Player' + Math.floor(Math.random() * 999));
  }

  private async quickMatch() {
    this.statusText.setText('⏳ Đang tìm đối thủ...');
    const name = this.getPlayerName();
    const res = await fetch('/api/games/rooms', {
      method: 'POST', headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ player: name, mode: 'matchmake' }),
    });
    const data = await res.json();
    if (data.matched) {
      this.scene.start('Game', { code: data.code, player: 'o', name });
    } else {
      this.statusText.setText(`Phòng: ${data.code} — Chờ đối thủ...`);
      this.pollForOpponent(data.code, name);
    }
  }

  private async createRoom() {
    const name = this.getPlayerName();
    const res = await fetch('/api/games/rooms', {
      method: 'POST', headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ player: name, mode: 'create' }),
    });
    const data = await res.json();
    this.statusText.setText(`Mã phòng: ${data.code} — Chia sẻ cho bạn bè!`);
    this.pollForOpponent(data.code, name);
  }

  private async joinRoom(code: string) {
    const name = this.getPlayerName();
    const res = await fetch(`/api/games/rooms/${code}/join`, {
      method: 'POST', headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ player: name }),
    });
    if (res.ok) {
      this.scene.start('Game', { code, player: 'o', name });
    } else {
      this.statusText.setText('❌ Phòng không tồn tại hoặc đã đầy');
    }
  }

  private pollForOpponent(code: string, name: string) {
    const timer = this.time.addEvent({
      delay: 2000,
      callback: async () => {
        const res = await fetch(`/api/games/rooms/${code}`);
        if (!res.ok) { timer.destroy(); this.statusText.setText('❌ Phòng hết hạn'); return; }
        const room = await res.json();
        if (room.status === 'playing') {
          timer.destroy();
          this.scene.start('Game', { code, player: 'x', name });
        }
      },
      loop: true,
    });
  }
}
