import Phaser from 'phaser';
import { BRAND } from '@lamgame/shared';

const WORDS = ['KHONG','DUONG','CHUNG','NGOAI','TRONG','NGUOI','CHINH','MUONG','TRUOC','THANG','PHONG','TRANG','KHACH','GIONG','CHUOI','BUONG','LUONG','QUANG','SUONG','XUONG','CHIEN','THIEN','NGHEN','THUOC','DUNG','CHUOT','TUONG','CUONG','NUONG','VUONG'];
const MAX_GUESSES = 6;
const WORD_LENGTH = 5;

export class GameScene extends Phaser.Scene {
  private answer = '';
  private guesses: string[] = [];
  private currentGuess = '';
  private grid: Phaser.GameObjects.Text[][] = [];
  private keyboardTexts: Map<string, Phaser.GameObjects.Text> = new Map();
  private messageText!: Phaser.GameObjects.Text;
  private isOver = false;

  constructor() { super({ key: 'Game' }); }

  create() {
    const { width, height } = this.scale;
    this.answer = WORDS[Math.floor(Math.random() * WORDS.length)];
    this.guesses = [];
    this.currentGuess = '';
    this.isOver = false;
    this.grid = [];
    this.keyboardTexts.clear();

    // Title
    this.add.text(width / 2, 35, 'ĐOÁN TỪ', {
      fontFamily: BRAND.fonts.game, fontSize: '22px', color: BRAND.colors.primary,
    }).setOrigin(0.5);

    this.messageText = this.add.text(width / 2, 70, 'Nhập từ 5 chữ cái (không dấu)', {
      fontFamily: BRAND.fonts.ui, fontSize: '13px', color: '#AAA',
    }).setOrigin(0.5);

    // Grid (6 rows × 5 cols)
    const cellSize = 56;
    const gap = 6;
    const gridW = WORD_LENGTH * (cellSize + gap) - gap;
    const startX = (width - gridW) / 2;
    const startY = 100;

    for (let r = 0; r < MAX_GUESSES; r++) {
      this.grid[r] = [];
      for (let c = 0; c < WORD_LENGTH; c++) {
        const x = startX + c * (cellSize + gap) + cellSize / 2;
        const y = startY + r * (cellSize + gap) + cellSize / 2;
        const bg = this.add.graphics();
        bg.lineStyle(2, 0x555555, 1);
        bg.strokeRect(x - cellSize / 2, y - cellSize / 2, cellSize, cellSize);
        const txt = this.add.text(x, y, '', {
          fontFamily: BRAND.fonts.ui, fontSize: '28px', color: '#FFF', fontStyle: 'bold',
        }).setOrigin(0.5);
        this.grid[r][c] = txt;
      }
    }

    // Virtual keyboard
    const rows = ['QWERTYUIOP', 'ASDFGHJKL', 'ZXCVBNM'];
    const kbStartY = startY + MAX_GUESSES * (cellSize + gap) + 30;
    rows.forEach((row, ri) => {
      const keyW = 38;
      const rowW = row.length * (keyW + 4) - 4;
      const rx = (width - rowW) / 2;
      for (let i = 0; i < row.length; i++) {
        const letter = row[i];
        const kx = rx + i * (keyW + 4) + keyW / 2;
        const ky = kbStartY + ri * 50;
        const key = this.add.text(kx, ky, letter, {
          fontFamily: BRAND.fonts.ui, fontSize: '16px', color: '#FFF',
          backgroundColor: '#444', padding: { x: 8, y: 10 },
        }).setOrigin(0.5).setInteractive({ useHandCursor: true });
        key.on('pointerdown', () => this.addLetter(letter));
        this.keyboardTexts.set(letter, key);
      }
    });

    // Enter & Backspace
    const lastRowY = kbStartY + 2 * 50;
    const enterBtn = this.add.text(width / 2 - 120, lastRowY + 55, 'ENTER', {
      fontFamily: BRAND.fonts.ui, fontSize: '14px', color: '#FFF',
      backgroundColor: BRAND.colors.success, padding: { x: 12, y: 10 },
    }).setOrigin(0.5).setInteractive({ useHandCursor: true });
    enterBtn.on('pointerdown', () => this.submitGuess());

    const delBtn = this.add.text(width / 2 + 120, lastRowY + 55, '⌫', {
      fontFamily: BRAND.fonts.ui, fontSize: '20px', color: '#FFF',
      backgroundColor: '#666', padding: { x: 14, y: 8 },
    }).setOrigin(0.5).setInteractive({ useHandCursor: true });
    delBtn.on('pointerdown', () => this.deleteLetter());

    // Physical keyboard
    this.input.keyboard?.on('keydown', (e: KeyboardEvent) => {
      if (this.isOver) return;
      if (e.key === 'Enter') this.submitGuess();
      else if (e.key === 'Backspace') this.deleteLetter();
      else if (/^[a-zA-Z]$/.test(e.key)) this.addLetter(e.key.toUpperCase());
    });

    this.add.text(width / 2, height - 25, 'lamgame.vn', {
      fontFamily: BRAND.fonts.ui, fontSize: '11px', color: '#555',
    }).setOrigin(0.5);
  }

  private addLetter(letter: string) {
    if (this.isOver || this.currentGuess.length >= WORD_LENGTH) return;
    this.currentGuess += letter;
    const row = this.guesses.length;
    const col = this.currentGuess.length - 1;
    this.grid[row][col].setText(letter);
  }

  private deleteLetter() {
    if (this.isOver || this.currentGuess.length === 0) return;
    const row = this.guesses.length;
    const col = this.currentGuess.length - 1;
    this.grid[row][col].setText('');
    this.currentGuess = this.currentGuess.slice(0, -1);
  }

  private submitGuess() {
    if (this.isOver || this.currentGuess.length !== WORD_LENGTH) return;

    const row = this.guesses.length;
    const guess = this.currentGuess;
    this.guesses.push(guess);

    // Color cells
    const ansArr = this.answer.split('');
    const used = Array(WORD_LENGTH).fill(false);

    // First pass: correct position (green)
    for (let i = 0; i < WORD_LENGTH; i++) {
      if (guess[i] === ansArr[i]) {
        this.colorCell(row, i, 0x538d4e); // green
        this.colorKey(guess[i], '#538d4e');
        used[i] = true;
      }
    }
    // Second pass: wrong position (yellow) or absent (gray)
    for (let i = 0; i < WORD_LENGTH; i++) {
      if (guess[i] === ansArr[i]) continue;
      const idx = ansArr.findIndex((ch, j) => ch === guess[i] && !used[j]);
      if (idx >= 0) {
        this.colorCell(row, i, 0xb59f3b); // yellow
        this.colorKey(guess[i], '#b59f3b');
        used[idx] = true;
      } else {
        this.colorCell(row, i, 0x3a3a4c); // gray
        this.colorKey(guess[i], '#3a3a4c');
      }
    }

    // Check win/lose
    if (guess === this.answer) {
      this.isOver = true;
      this.messageText.setText('🎉 Chính xác! Từ: ' + this.answer);
      this.messageText.setColor(BRAND.colors.success);
      this.time.delayedCall(1500, () => {
        this.scene.start('GameOver', { score: MAX_GUESSES - this.guesses.length + 1, gameKey: 'doan-tu-wordle' });
      });
    } else if (this.guesses.length >= MAX_GUESSES) {
      this.isOver = true;
      this.messageText.setText('😢 Từ đúng: ' + this.answer);
      this.messageText.setColor(BRAND.colors.secondary);
      this.time.delayedCall(2000, () => {
        this.scene.start('GameOver', { score: 0, gameKey: 'doan-tu-wordle' });
      });
    }

    this.currentGuess = '';
  }

  private colorCell(row: number, col: number, color: number) {
    const txt = this.grid[row][col];
    const bg = this.add.graphics();
    const cellSize = 56, gap = 6;
    const { width } = this.scale;
    const gridW = WORD_LENGTH * (cellSize + gap) - gap;
    const sx = (width - gridW) / 2;
    const x = sx + col * (cellSize + gap);
    const y = 100 + row * (cellSize + gap);
    bg.fillStyle(color, 1);
    bg.fillRect(x, y, cellSize, cellSize);
    txt.setDepth(1);
  }

  private colorKey(letter: string, color: string) {
    const key = this.keyboardTexts.get(letter);
    if (key) key.setBackgroundColor(color);
  }
}
