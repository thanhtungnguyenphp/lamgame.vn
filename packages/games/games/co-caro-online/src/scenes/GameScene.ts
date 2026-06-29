import Phaser from 'phaser';
import { BRAND } from '@lamgame/shared';

const SIZE = 15;
const CELL = 42;

export class GameScene extends Phaser.Scene {
  private code = '';
  private player = 'x';
  private myTurn = false;
  private board: (string | null)[][] = [];
  private statusText!: Phaser.GameObjects.Text;
  private boardX = 0;
  private boardY = 0;
  private pollTimer?: Phaser.Time.TimerEvent;
  private gameOver = false;

  constructor() { super({ key: 'Game' }); }

  create(data: { code: string; player: string; name: string }) {
    this.code = data.code;
    this.player = data.player;
    this.myTurn = data.player === 'x';
    this.gameOver = false;
    this.board = Array.from({ length: SIZE }, () => Array(SIZE).fill(null));

    const { width, height } = this.scale;
    const boardSize = SIZE * CELL;
    this.boardX = (width - boardSize) / 2;
    this.boardY = 130;

    // Header
    this.add.text(width / 2, 25, `Phòng: ${this.code}`, {
      fontFamily: BRAND.fonts.ui, fontSize: '16px', color: BRAND.colors.primary,
    }).setOrigin(0.5);

    const sym = this.player === 'x' ? '✕ (Đỏ)' : '○ (Xanh)';
    this.add.text(width / 2, 55, `Bạn là: ${sym}`, {
      fontFamily: BRAND.fonts.ui, fontSize: '14px', color: BRAND.colors.light,
    }).setOrigin(0.5);

    this.statusText = this.add.text(width / 2, 85, this.myTurn ? '🟢 Lượt bạn' : '⏳ Chờ đối thủ...', {
      fontFamily: BRAND.fonts.ui, fontSize: '16px', color: BRAND.colors.text,
    }).setOrigin(0.5);

    // Board background
    this.add.graphics().fillStyle(0x2d2d44, 1).fillRect(this.boardX, this.boardY, boardSize, boardSize);

    // Grid
    const g = this.add.graphics();
    g.lineStyle(1, 0x444466, 0.5);
    for (let i = 0; i <= SIZE; i++) {
      g.lineBetween(this.boardX + i * CELL, this.boardY, this.boardX + i * CELL, this.boardY + boardSize);
      g.lineBetween(this.boardX, this.boardY + i * CELL, this.boardX + boardSize, this.boardY + i * CELL);
    }

    // Click
    this.input.on('pointerdown', (p: Phaser.Input.Pointer) => {
      if (!this.myTurn || this.gameOver) return;
      const col = Math.floor((p.x - this.boardX) / CELL);
      const row = Math.floor((p.y - this.boardY) / CELL);
      if (row < 0 || row >= SIZE || col < 0 || col >= SIZE) return;
      if (this.board[row][col]) return;
      this.makeMove(row, col);
    });

    // Poll
    this.pollTimer = this.time.addEvent({ delay: 1500, callback: () => this.pollState(), loop: true });

    // Footer
    this.add.text(width / 2, height - 25, 'Chia sẻ mã phòng cho bạn bè!', {
      fontFamily: BRAND.fonts.ui, fontSize: '11px', color: '#666',
    }).setOrigin(0.5);
  }

  private async makeMove(row: number, col: number) {
    this.myTurn = false;
    this.statusText.setText('⏳ Chờ đối thủ...');
    this.board[row][col] = this.player;
    this.drawPiece(row, col, this.player);

    const res = await fetch(`/api/games/rooms/${this.code}/move`, {
      method: 'POST', headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ row, col, player: this.player }),
    });
    const data = await res.json();
    if (data.winner) this.endGame(data.winner);
  }

  private async pollState() {
    if (this.gameOver) return;
    const res = await fetch(`/api/games/rooms/${this.code}`);
    if (!res.ok) { this.statusText.setText('❌ Phòng hết hạn'); this.pollTimer?.destroy(); return; }
    const room = await res.json();

    if (room.status === 'waiting') {
      this.statusText.setText('⏳ Chờ đối thủ vào phòng...');
      return;
    }

    // Sync board
    const sb: (string | null)[][] = room.board_state;
    for (let r = 0; r < SIZE; r++)
      for (let c = 0; c < SIZE; c++)
        if (sb[r][c] && this.board[r][c] !== sb[r][c]) {
          this.board[r][c] = sb[r][c];
          this.drawPiece(r, c, sb[r][c]!);
        }

    if (room.winner) this.endGame(room.winner);
    else {
      this.myTurn = room.current_turn === this.player;
      this.statusText.setText(this.myTurn ? '🟢 Lượt bạn' : '⏳ Chờ đối thủ...');
    }
  }

  private drawPiece(row: number, col: number, piece: string) {
    const x = this.boardX + col * CELL + CELL / 2;
    const y = this.boardY + row * CELL + CELL / 2;
    const color = piece === 'x' ? '#FF6584' : '#00D68F';
    this.add.text(x, y, piece === 'x' ? '✕' : '○', {
      fontFamily: BRAND.fonts.ui, fontSize: '22px', color, fontStyle: 'bold',
    }).setOrigin(0.5);
  }

  private endGame(winner: string) {
    this.gameOver = true;
    this.pollTimer?.destroy();
    const won = winner === this.player;
    this.statusText.setText(won ? '🎉 BẠN THẮNG!' : winner === 'expired' ? '⏰ Hết thời gian' : '😢 Bạn thua!');
    this.statusText.setColor(won ? BRAND.colors.success : BRAND.colors.secondary);

    const { width, height } = this.scale;
    const y = height - 80;

    // Rematch
    const rematchBtn = this.add.text(width / 2 - 80, y, '🔄 Rematch', {
      fontFamily: BRAND.fonts.ui, fontSize: '16px', color: '#FFF',
      backgroundColor: BRAND.colors.primary, padding: { x: 16, y: 8 },
    }).setOrigin(0.5).setInteractive({ useHandCursor: true });
    rematchBtn.on('pointerdown', () => this.requestRematch());

    // Back to lobby
    const lobbyBtn = this.add.text(width / 2 + 80, y, '🏠 Lobby', {
      fontFamily: BRAND.fonts.ui, fontSize: '16px', color: '#FFF',
      backgroundColor: '#555', padding: { x: 16, y: 8 },
    }).setOrigin(0.5).setInteractive({ useHandCursor: true });
    lobbyBtn.on('pointerdown', () => this.scene.start('Lobby'));

    // Submit score
    if (won) {
      fetch('/api/games/co-caro-online/leaderboard', {
        method: 'POST', headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ player: localStorage.getItem('lamgame_player') || 'Guest', score: 1 }),
      }).catch(() => {});
    }
  }

  private async requestRematch() {
    const res = await fetch(`/api/games/rooms/${this.code}/rematch`, {
      method: 'POST', headers: { 'Content-Type': 'application/json' },
    });
    if (res.ok) {
      const data = await res.json();
      const newPlayer = this.player === 'x' ? 'o' : 'x'; // Swapped
      this.scene.start('Game', { code: data.code, player: newPlayer, name: localStorage.getItem('lamgame_player') || 'Guest' });
    }
  }
}
