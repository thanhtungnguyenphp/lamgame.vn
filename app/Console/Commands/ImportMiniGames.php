<?php

namespace App\Console\Commands;

use App\Models\MiniGame;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ImportMiniGames extends Command
{
    protected $signature = 'mini-games:import {path? : Path to games directory}';
    protected $description = 'Import mini games from public/games/ directory';

    // slug → category mapping
    private const CATEGORY_MAP = [
        // arcade
        'chim-bay-vuot-ong' => 'arcade', 'ran-san-moi' => 'arcade', 'xep-gach-kinh-dien' => 'arcade',
        'ma-tran-an-diem' => 'arcade', 'pha-gach' => 'arcade', 'bong-ban-mini' => 'arcade',
        'ban-quai-vu-tru' => 'arcade', 'chay-bat-tan' => 'arcade', 'ran-io' => 'arcade',
        'bong-nay' => 'arcade', 'ban-tau' => 'arcade', 'nhay-hinh-hoc' => 'arcade',
        // puzzle
        '2048-ghep-so' => 'puzzle', 'do-min' => 'puzzle', 'lat-the-nho' => 'puzzle',
        'sudoku-vui' => 'puzzle', 'doan-tu' => 'puzzle', 'xep-hinh-truot' => 'puzzle',
        'xep-thap' => 'puzzle', 'thoat-me-cung' => 'puzzle', 'noi-4' => 'puzzle',
        'doan-chu' => 'puzzle', 'kim-cuong' => 'puzzle',
        // casual
        'keo-ngot-xep-3' => 'casual', 'may-xeng-may-man' => 'casual', 'dap-chuot' => 'casual',
        'click-banh' => 'casual', 'xuc-xac-may-man' => 'casual', 'ban-bong-bong' => 'casual',
        'keo-bua-bao' => 'casual', 'chem-hoa-qua' => 'casual', 'bat-emoji' => 'casual',
        'click-hinh' => 'casual', 'hung-con-trung' => 'casual',
        // card
        'co-ca-ro' => 'card', 'co-vua-online' => 'card', 'xep-bai-mot-minh' => 'card',
        'co-dam' => 'card', 'nhen-xep-bai' => 'card',
        // action
        'ech-qua-duong' => 'action', 'qua-duong-an-toan' => 'action', 'phong-thu' => 'action',
        'ban-cung' => 'action', 'nguoi-que' => 'action',
        // kids
        'do-vui-kien-thuc' => 'kids', 'simon-noi' => 'kids', 'go-phim-nhanh' => 'kids',
        'test-phan-xa' => 'kids',
    ];

    public function handle(): int
    {
        $sourcePath = $this->argument('path') ?: public_path('games');

        if (!File::isDirectory($sourcePath)) {
            $this->error("Directory not found: {$sourcePath}");
            return 1;
        }

        $dirs = File::directories($sourcePath);
        $imported = 0;
        $skipped = 0;

        foreach ($dirs as $dir) {
            $slug = basename($dir);
            $indexFile = $dir . '/index.html';

            if (!File::exists($indexFile)) {
                $this->warn("Skip {$slug}: no index.html");
                $skipped++;
                continue;
            }

            $html = File::get($indexFile);

            // Parse meta from HTML
            $title = $this->extractTag($html, 'title') ?: ucfirst(str_replace('-', ' ', $slug));
            // Remove " - Chơi Miễn Phí | LamGame" suffix for clean DB title
            $title = preg_replace('/\s*[-–|].*LamGame.*$/i', '', $title);

            $description = $this->extractMeta($html, 'description');
            $keywords = $this->extractMeta($html, 'keywords');
            $category = self::CATEGORY_MAP[$slug] ?? 'arcade';

            MiniGame::updateOrCreate(
                ['slug' => $slug],
                [
                    'title'       => $title,
                    'description' => $description,
                    'keywords'    => $keywords,
                    'category'    => $category,
                    'game_path'   => 'games/' . $slug,
                    'is_active'   => true,
                ]
            );

            $imported++;
            $this->line("✅ {$slug} → {$category}");
        }

        $this->info("Done: {$imported} imported, {$skipped} skipped.");
        return 0;
    }

    private function extractTag(string $html, string $tag): ?string
    {
        if (preg_match("/<{$tag}[^>]*>(.+?)<\/{$tag}>/is", $html, $m)) {
            return trim(html_entity_decode($m[1]));
        }
        return null;
    }

    private function extractMeta(string $html, string $name): ?string
    {
        if (preg_match('/name=["\']' . $name . '["\']\s+content=["\']([^"\']+)["\']/i', $html, $m)) {
            return trim(html_entity_decode($m[1]));
        }
        // Also try content before name
        if (preg_match('/content=["\']([^"\']+)["\']\s+name=["\']' . $name . '["\']/i', $html, $m)) {
            return trim(html_entity_decode($m[1]));
        }
        return null;
    }
}
