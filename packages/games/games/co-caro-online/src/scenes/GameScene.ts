import Phaser from 'phaser';
import { BRAND } from '@lamgame/shared';

const SIZE = 15;
const CELL = 42;
const PAD = 12;

export class GameScene extends Phaser.Scene {
  private code = '';
  private player = 'x';
  private myTurn = false;
  private board: (string | null)[][] = [];
  private statusText!: Phaser.GameObjects.Text;
  private codeText!: Phaser.GameObjects.Text;
  private boardX = 0;
  private boardY = 0;
  private pollTimer?: Phaser.Time.TimerEvent;
  private gameOver = false;

  constructor() {
    super({ key: 'Game' });
  }

  create(data: { code: string; player: string; name: string }) {
    this.code = data.code;
    this.player = data.player;
    this.myTurn = data.player === 'x';
    this.gameOver = false;
    this.board = Array.from({ length: SIZE }, () => Array(SIZE).fill(null));

    const { width, height } = this.scale;
    const boardSize = SIZE * CELL;
    this.boardX = (width - boardSize) / 2;
    this.boardY = 140;

    // Header
    this.codeText = this.add.text(width / 2, 30, `Phòng: ${this.code}`, {
      fontFamily: BRAND.fonts.ui, fontSize: '18px', color: BRAND.colors.primary,
    }).setOrigin(0.5);

    const symbol = this.player === 'x' ? '✕' : '○';
    this.add.text(width / 2, 60, `Bạn là: ${symbol}`, {
      fontFamily: BRAND.fonts.ui, fontSize: '16px', color: BRAND.colors.light,
    }).setOrigin(0.5);

    this.statusText = this.add.text(width / 2, 95, this.myTurn ? '🟢 Lượt bạn' : '⏳ Chờ đối thủ...', {
      fontFamily: BRAND.fonts.ui, fontSize: '16px', color: BRAND.colors.text,
    }).setOrigin(0.5);

    // Draw board
    const bg = this.add.graphics();
    bg.fillStyle(0x2d2d44, 1);
    bg.fillRect(this.boardX, this.boardY, boardSize, boardSize);

    // Grid lines
    const lines = this.add.graphics();
    lines.lineStyle(1, 0x444466, 0.6);
    for (let i = 0; i <= SIZE; i++) {
      lines.lineBetween(this.boardX + i * CELL, this.boardY, this.boardX + i * CELL, this.boardY + boardSize);
      lines.lineBetween(this.boardX, this.boardY + i * CELL, this.boardX + boardSize, this.boardY + i * CELL);
    }

    // Click handler
    this.input.on('pointerdown', (p: Phaser.Input.Pointer) => {
      if (!this.myTurn || this.gameOver) return;
      const col = Math.floor((p.x - this.boardX) / CELL);
      const row = Math.floor((p.y - this.boardY) / CELL);
      if (row < 0 || row >= SIZE || col < 0 || col >= SIZE) return;
      if (this.board[row][col] !== null) return;
      this.makeMove(row, col);
    });

    // Poll for opponent moves
    this.pollTimer = this.time.addEvent({
      delay: 1500,
      callback: () => this.pollState(),
      loop: true,
    });

    // Footer
    this.add.text(width / 2, height - 30, 'Chia sẻ mã phòng cho bạn bè để chơi cùng!', {
      fontFamily: BRAND.fonts.ui, fontSize: '12px', color: '#888',
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
    const room = await res.json();

    if (room.status === 'waiting') {
      this.statusText.setText('⏳ Chờ đối thủ vào phòng...');
      return;
    }

    // Sync board
    const serverBoard: (string | null)[][] = room.board_state;
    for (let r = 0; r < SIZE; r++) {
      for (let c = 0; c < SIZE; c++) {
        if (serverBoard[r][c] && this.board[r][c] !== serverBoard[r][c]) {
          this.board[r][c] = serverBoard[r][c];
          this.drawPiece(r, c, serverBoard[r][c]!);
        }
      }
    }

    if (room.winner) {
      this.endGame(room.winner);
    } else {
      this.myTurn = room.current_turn === this.player;
      this.statusText.setText(this.myTurn ? '🟢 Lượt bạn' : '⏳ Chờ đối thủ...');
    }
  }

  private drawPiece(row: number, col: number, piece: string) {
    const x = this.boardX + col * CELL + CELL / 2;
    const y = this.boardY + row * CELL + CELL / 2;
    const color = piece === 'x' ? BRAND.colors.secondary : BRAND.colors.success;
    const symbol = piece === 'x' ? '✕' : '○';
    this.add.text(x, y, symbol, {
      fontFamily: BRAND.fonts.ui, fontSize: '24px', color, fontStyle: 'bold',
    }).setOrigin(0.5);
  }

  private endGame(winner: string) {
    this.gameOver = true;
    this.pollTimer?.destroy();
    const isWinner = winner === this.player;
    this.statusText.setText(isWinner ? '🎉 BẠN THẮNG!' : '😢 Bạn thua!');
    this.statusText.setColor(isWinner ? BRAND.colors.success : BRAND.colors.secondary);

    const { width, height } = this.scale;
    const btn = this.add.text(width / 2, height - 80, '🔄 Chơi lại', {
      fontFamily: BRAND.fonts.ui, fontSize: '18px', color: '#FFF',
      backgroundColor: BRAND.colors.primary, padding: { x: 20, y: 10 },
    }).setOrigin(0.5).setInteractive({ useHandCursor: true });
    btn.on('pointerdown', () => this.scene.start('Lobby'));
  }
}
