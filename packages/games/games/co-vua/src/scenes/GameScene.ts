import Phaser from 'phaser';
import { BRAND } from '@lamgame/shared';

const CELL = 72;
const PIECES: Record<string, string> = {
  K: '♔', Q: '♕', R: '♖', B: '♗', N: '♘', P: '♙',
  k: '♚', q: '♛', r: '♜', b: '♝', n: '♞', p: '♟',
};

const INIT_BOARD = [
  'rnbqkbnr',
  'pppppppp',
  '........',
  '........',
  '........',
  '........',
  'PPPPPPPP',
  'RNBQKBNR',
];

export class GameScene extends Phaser.Scene {
  private board: string[][] = [];
  private selected: { r: number; c: number } | null = null;
  private validMoves: { r: number; c: number }[] = [];
  private isWhiteTurn = true;
  private boardX = 0;
  private boardY = 0;
  private graphics!: Phaser.GameObjects.Graphics;
  private pieceTexts: (Phaser.GameObjects.Text | null)[][] = [];
  private statusText!: Phaser.GameObjects.Text;
  private moveCount = 0;

  constructor() {
    super({ key: 'Game' });
  }

  create() {
    const { width, height } = this.scale;
    this.isWhiteTurn = true;
    this.moveCount = 0;
    this.boardX = (width - 8 * CELL) / 2;
    this.boardY = 140;
    this.graphics = this.add.graphics();

    // Init board
    this.board = INIT_BOARD.map(row => row.split(''));
    this.pieceTexts = Array.from({ length: 8 }, () => Array(8).fill(null));

    // UI
    this.add.text(width / 2, 30, 'CỜ VUA', {
      fontFamily: BRAND.fonts.game, fontSize: '24px', color: BRAND.colors.primary,
    }).setOrigin(0.5);

    this.statusText = this.add.text(width / 2, 80, '⬜ Trắng đi trước', {
      fontFamily: BRAND.fonts.ui, fontSize: '16px', color: BRAND.colors.light,
    }).setOrigin(0.5);

    this.drawBoard();
    this.renderPieces();

    // Click handler
    this.input.on('pointerdown', (p: Phaser.Input.Pointer) => {
      const c = Math.floor((p.x - this.boardX) / CELL);
      const r = Math.floor((p.y - this.boardY) / CELL);
      if (r < 0 || r >= 8 || c < 0 || c >= 8) return;
      this.handleClick(r, c);
    });

    this.add.text(width / 2, height - 20, 'lamgame.vn', {
      fontFamily: BRAND.fonts.ui, fontSize: '12px', color: '#444',
    }).setOrigin(0.5);
  }

  private handleClick(r: number, c: number) {
    const piece = this.board[r][c];

    // If clicking a valid move target
    if (this.selected && this.validMoves.some(m => m.r === r && m.c === c)) {
      this.makeMove(this.selected.r, this.selected.c, r, c);
      this.selected = null;
      this.validMoves = [];
      this.drawBoard();
      this.renderPieces();

      // AI move after delay
      if (!this.isWhiteTurn) {
        this.time.delayedCall(400, () => this.aiMove());
      }
      return;
    }

    // Select own piece
    const isWhitePiece = piece !== '.' && piece === piece.toUpperCase();
    const isBlackPiece = piece !== '.' && piece === piece.toLowerCase();

    if (this.isWhiteTurn && isWhitePiece) {
      this.selected = { r, c };
      this.validMoves = this.getValidMoves(r, c);
    } else if (!this.isWhiteTurn && isBlackPiece) {
      this.selected = { r, c };
      this.validMoves = this.getValidMoves(r, c);
    } else {
      this.selected = null;
      this.validMoves = [];
    }

    this.drawBoard();
  }

  private makeMove(fr: number, fc: number, tr: number, tc: number) {
    const captured = this.board[tr][tc];
    this.board[tr][tc] = this.board[fr][fc];
    this.board[fr][fc] = '.';

    // Pawn promotion
    if (this.board[tr][tc] === 'P' && tr === 0) this.board[tr][tc] = 'Q';
    if (this.board[tr][tc] === 'p' && tr === 7) this.board[tr][tc] = 'q';

    this.isWhiteTurn = !this.isWhiteTurn;
    this.moveCount++;
    this.statusText.setText(this.isWhiteTurn ? '⬜ Lượt Trắng' : '⬛ Lượt Đen (AI)');

    // Check if king captured (simplified end)
    if (captured === 'k') {
      this.time.delayedCall(300, () => this.scene.start('GameOver', { score: this.moveCount, gameKey: 'co-vua' }));
    } else if (captured === 'K') {
      this.time.delayedCall(300, () => this.scene.start('GameOver', { score: 0, gameKey: 'co-vua' }));
    }
  }

  private aiMove() {
    // Simple AI: find all black moves, pick best capture or random
    const moves: { fr: number; fc: number; tr: number; tc: number; score: number }[] = [];
    for (let r = 0; r < 8; r++)
      for (let c = 0; c < 8; c++) {
        const p = this.board[r][c];
        if (p === '.' || p === p.toUpperCase()) continue;
        const valid = this.getValidMoves(r, c);
        for (const m of valid) {
          const target = this.board[m.r][m.c];
          let score = Math.random() * 0.5;
          if (target !== '.') score += this.pieceValue(target) * 10;
          // Center control bonus
          if (m.r >= 3 && m.r <= 4 && m.c >= 3 && m.c <= 4) score += 0.5;
          moves.push({ fr: r, fc: c, tr: m.r, tc: m.c, score });
        }
      }

    if (moves.length === 0) {
      // Stalemate/checkmate — player wins
      this.scene.start('GameOver', { score: this.moveCount * 10, gameKey: 'co-vua' });
      return;
    }

    moves.sort((a, b) => b.score - a.score);
    const best = moves[0];
    this.makeMove(best.fr, best.fc, best.tr, best.tc);
    this.drawBoard();
    this.renderPieces();
  }

  private pieceValue(p: string): number {
    const v: Record<string, number> = { p: 1, n: 3, b: 3, r: 5, q: 9, k: 100, P: 1, N: 3, B: 3, R: 5, Q: 9, K: 100 };
    return v[p] || 0;
  }

  private getValidMoves(r: number, c: number): { r: number; c: number }[] {
    const piece = this.board[r][c];
    const isWhite = piece === piece.toUpperCase();
    const moves: { r: number; c: number }[] = [];
    const type = piece.toLowerCase();

    const canMove = (tr: number, tc: number): boolean => {
      if (tr < 0 || tr >= 8 || tc < 0 || tc >= 8) return false;
      const target = this.board[tr][tc];
      if (target === '.') return true;
      return isWhite ? target === target.toLowerCase() : target === target.toUpperCase();
    };

    const addSliding = (dirs: [number, number][]) => {
      for (const [dr, dc] of dirs) {
        for (let i = 1; i < 8; i++) {
          const nr = r + dr * i, nc = c + dc * i;
          if (nr < 0 || nr >= 8 || nc < 0 || nc >= 8) break;
          if (this.board[nr][nc] === '.') { moves.push({ r: nr, c: nc }); continue; }
          if (canMove(nr, nc)) moves.push({ r: nr, c: nc });
          break;
        }
      }
    };

    if (type === 'p') {
      const dir = isWhite ? -1 : 1;
      const startRow = isWhite ? 6 : 1;
      if (r + dir >= 0 && r + dir < 8 && this.board[r + dir][c] === '.') {
        moves.push({ r: r + dir, c });
        if (r === startRow && this.board[r + dir * 2][c] === '.') moves.push({ r: r + dir * 2, c });
      }
      for (const dc of [-1, 1]) {
        const nr = r + dir, nc = c + dc;
        if (nr >= 0 && nr < 8 && nc >= 0 && nc < 8 && this.board[nr][nc] !== '.' && canMove(nr, nc))
          moves.push({ r: nr, c: nc });
      }
    } else if (type === 'n') {
      for (const [dr, dc] of [[-2,-1],[-2,1],[-1,-2],[-1,2],[1,-2],[1,2],[2,-1],[2,1]]) {
        if (canMove(r + dr, c + dc)) moves.push({ r: r + dr, c: c + dc });
      }
    } else if (type === 'b') {
      addSliding([[-1,-1],[-1,1],[1,-1],[1,1]]);
    } else if (type === 'r') {
      addSliding([[-1,0],[1,0],[0,-1],[0,1]]);
    } else if (type === 'q') {
      addSliding([[-1,-1],[-1,1],[1,-1],[1,1],[-1,0],[1,0],[0,-1],[0,1]]);
    } else if (type === 'k') {
      for (let dr = -1; dr <= 1; dr++)
        for (let dc = -1; dc <= 1; dc++)
          if ((dr || dc) && canMove(r + dr, c + dc)) moves.push({ r: r + dr, c: c + dc });
    }

    return moves;
  }

  private drawBoard() {
    this.graphics.clear();
    for (let r = 0; r < 8; r++)
      for (let c = 0; c < 8; c++) {
        const light = (r + c) % 2 === 0;
        let color = light ? 0xF0D9B5 : 0xB58863;

        // Highlight selected
        if (this.selected?.r === r && this.selected?.c === c) color = 0x6C63FF;

        this.graphics.fillStyle(color, 1);
        this.graphics.fillRect(this.boardX + c * CELL, this.boardY + r * CELL, CELL, CELL);
      }

    // Valid move dots
    for (const m of this.validMoves) {
      this.graphics.fillStyle(0x00D68F, 0.5);
      this.graphics.fillCircle(this.boardX + m.c * CELL + CELL / 2, this.boardY + m.r * CELL + CELL / 2, 10);
    }
  }

  private renderPieces() {
    this.pieceTexts.forEach(row => row.forEach(t => t?.destroy()));
    for (let r = 0; r < 8; r++)
      for (let c = 0; c < 8; c++) {
        const p = this.board[r][c];
        if (p === '.') continue;
        const x = this.boardX + c * CELL + CELL / 2;
        const y = this.boardY + r * CELL + CELL / 2;
        this.pieceTexts[r][c] = this.add.text(x, y, PIECES[p] || '', {
          fontSize: '44px',
        }).setOrigin(0.5);
      }
  }
}
