<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;
use Carbon\Carbon;

class SourceGameRebuiltSeeder extends Seeder
{
    public function run()
    {
        $games = [
            [
                'name' => '2048 Ghép Số — Phaser 3 + TypeScript',
                'slug' => '2048-ghep-so-phaser3-typescript',
                'genre' => 'Puzzle',
                'description' => 'Game 2048 hoàn chỉnh xây dựng bằng Phaser 3 + TypeScript. Grid 4×4, swipe/keyboard, score tracking, responsive mobile. Monorepo architecture, Vite build, Capacitor-ready cho Android.',
                'features' => ['Phaser 3 + TypeScript', 'Swipe & keyboard input', 'Score + Best score', 'Responsive 720×1280', 'Capacitor Android ready', 'Leaderboard API integration'],
            ],
            [
                'name' => 'Xếp Gạch Kinh Điển (Tetris) — Phaser 3 + TypeScript',
                'slug' => 'xep-gach-tetris-phaser3-typescript',
                'genre' => 'Puzzle',
                'description' => 'Tetris classic với 7 mảnh chuẩn, rotation, line clearing, tăng tốc. Phaser 3 + TypeScript, responsive, có scoring system.',
                'features' => ['7 Tetrominos chuẩn', 'Rotation + wall kick', 'Line clear animation', 'Level progression', 'Ghost piece preview', 'Touch controls'],
            ],
            [
                'name' => 'Chim Bay Vượt Ống (Flappy Bird) — Phaser 3 + TypeScript',
                'slug' => 'chim-bay-flappy-bird-phaser3-typescript',
                'genre' => 'Arcade',
                'description' => 'Flappy Bird clone hoàn chỉnh: physics-based bird, random pipes, score, game over. Tối ưu mobile, có share score.',
                'features' => ['Arcade physics', 'Random pipe generation', 'Score counter', 'Death animation', 'One-tap control', 'Share score'],
            ],
            [
                'name' => 'Rắn Săn Mồi (Snake) — Phaser 3 + TypeScript',
                'slug' => 'ran-san-moi-snake-phaser3-typescript',
                'genre' => 'Arcade',
                'description' => 'Snake game modern: grid-based movement, food spawn, grow mechanics, self-collision detection. Clean code TypeScript.',
                'features' => ['Grid movement system', 'Food spawn logic', 'Grow & speed up', 'Self-collision', 'Swipe + Arrow keys', 'Score tracking'],
            ],
            [
                'name' => 'Kẹo Ngọt Xếp 3 (Match-3) — Phaser 3 + TypeScript',
                'slug' => 'keo-ngot-match3-phaser3-typescript',
                'genre' => 'Puzzle',
                'description' => 'Match-3 engine hoàn chỉnh style Candy Crush. Swap tiles, chain combos, cascade fill, score multiplier. Dễ customize theme.',
                'features' => ['Match-3 core engine', 'Swap animation', 'Cascade & chain combos', 'Score multiplier', 'Level system', 'Easy theme swap'],
            ],
            [
                'name' => 'Cờ Vua Online — Phaser 3 + WebSocket',
                'slug' => 'co-vua-online-phaser3-websocket',
                'genre' => 'Board',
                'description' => 'Chess game với đầy đủ rules: castling, en passant, promotion. Hỗ trợ multiplayer WebSocket và AI opponent cơ bản.',
                'features' => ['Full chess rules', 'Castling + En passant', 'Pawn promotion', 'WebSocket multiplayer', 'Basic AI opponent', 'Move history'],
            ],
            [
                'name' => 'Sudoku Vui — Phaser 3 + TypeScript',
                'slug' => 'sudoku-vui-phaser3-typescript',
                'genre' => 'Puzzle',
                'description' => 'Sudoku generator + solver + UI. Multiple difficulty levels, hint system, auto-validate, timer. Clean architecture.',
                'features' => ['Puzzle generator', 'Difficulty levels', 'Hint system', 'Auto-validate', 'Timer', 'Pencil marks'],
            ],
            [
                'name' => 'Nhảy Hình Học (Geometry Dash) — Phaser 3 + TypeScript',
                'slug' => 'nhay-hinh-hoc-geometry-dash-phaser3',
                'genre' => 'Platformer',
                'description' => 'Geometry Dash style endless runner. One-tap jump, obstacle generation, rhythm-based levels, particle effects.',
                'features' => ['One-tap jump physics', 'Procedural obstacles', 'Speed progression', 'Death + respawn', 'Particle effects', 'Score system'],
            ],
            [
                'name' => 'Lật Thẻ Nhớ (Memory) — Phaser 3 + TypeScript',
                'slug' => 'lat-the-nho-memory-phaser3-typescript',
                'genre' => 'Puzzle',
                'description' => 'Memory card game: flip, match pairs, timer, move counter. Multiple grid sizes, themes dễ thay đổi.',
                'features' => ['Flip animation', 'Pair matching', 'Timer + Moves', 'Multiple grid sizes', 'Custom themes', 'Best time tracking'],
            ],
            [
                'name' => 'Dò Mìn (Minesweeper) — Phaser 3 + TypeScript',
                'slug' => 'do-min-minesweeper-phaser3-typescript',
                'genre' => 'Puzzle',
                'description' => 'Minesweeper classic: reveal cells, flag mines, flood fill, number hints. 3 difficulty levels, timer.',
                'features' => ['Flood fill reveal', 'Flag system', 'Number hints', '3 difficulties', 'First-click safe', 'Timer + Mine counter'],
            ],
            [
                'name' => 'Đoán Chữ (Hangman) — Phaser 3 + TypeScript',
                'slug' => 'doan-chu-hangman-phaser3-typescript',
                'genre' => 'Word',
                'description' => 'Hangman game với word bank tiếng Việt + English. Keyboard input, wrong guess animation, hint system.',
                'features' => ['Vietnamese + English words', 'Virtual keyboard', 'Hangman animation', 'Hint system', 'Category filter', 'Win/lose tracking'],
            ],
            [
                'name' => 'Phòng Thủ (Tower Defense) — Phaser 3 + TypeScript',
                'slug' => 'phong-thu-tower-defense-phaser3',
                'genre' => 'Strategy',
                'description' => 'Tower Defense cơ bản: pathfinding enemies, placeable towers, upgrade, wave system. Good starting point cho game TD.',
                'features' => ['Path-based enemies', 'Tower placement', 'Upgrade system', 'Wave progression', 'Multiple tower types', 'Resource management'],
            ],
            [
                'name' => 'Simon Nói (Simon Says) — Phaser 3 + TypeScript',
                'slug' => 'simon-noi-simon-says-phaser3-typescript',
                'genre' => 'Memory',
                'description' => 'Simon Says memory game: sequence generation, color flash, player repeat, increasing difficulty. Sound effects.',
                'features' => ['Sequence generator', 'Color flash animation', 'Sound feedback', 'Increasing length', 'Score tracking', 'High score'],
            ],
        ];

        $now = Carbon::now();
        $categoryId = DB::table('category_translations')->where('slug', 'source-game')->value('category_id') ?? 2;

        foreach ($games as $i => $game) {
            $productId = DB::table('products')->insertGetId([
                'type' => 'simple',
                'sku' => 'SG-PHASER-' . str_pad($i + 1, 3, '0', STR_PAD_LEFT),
                'attribute_family_id' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            DB::table('product_flat')->insert([
                'product_id' => $productId,
                'sku' => 'SG-PHASER-' . str_pad($i + 1, 3, '0', STR_PAD_LEFT),
                'name' => $game['name'],
                'url_key' => $game['slug'],
                'description' => $game['description'],
                'short_description' => $game['description'],
                'price' => 4.99,
                'special_price' => null,
                'status' => 1,
                'new' => 1,
                'featured' => 1,
                'channel' => 'default',
                'locale' => 'vi',
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            DB::table('product_categories')->insert([
                'product_id' => $productId,
                'category_id' => $categoryId,
            ]);

            echo "Created: {$game['name']}\n";
        }

        echo "\n✅ SourceGameRebuiltSeeder: " . count($games) . " products created.\n";
    }
}
