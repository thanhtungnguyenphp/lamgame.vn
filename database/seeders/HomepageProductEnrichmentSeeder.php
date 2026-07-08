<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HomepageProductEnrichmentSeeder extends Seeder
{
    /**
     * Enrich existing products with marketplace data for Homepage V2.
     * Assigns engine, genre, platform, pricing, badges, sales counts.
     */
    public function run(): void
    {
        $products = DB::table('product_flat')
            ->where('channel', 'default')
            ->where('locale', 'vi')
            ->whereNull('engine')
            ->get();

        if ($products->isEmpty()) {
            $this->command->info('No products to enrich (already done or no products found).');
            return;
        }

        $genres = ['FPS', 'RPG', 'Action', 'MOBA', 'Racing', 'Puzzle', 'Strategy', 'Survival', 'Platformer'];
        $engines = ['Unity', 'Unreal', 'Godot', 'Phaser'];
        $platforms = [
            ['PC', 'Mobile'],
            ['PC', 'Mobile', 'Web'],
            ['PC'],
            ['Mobile'],
            ['PC', 'Mobile', 'Console'],
            ['Web'],
        ];
        $difficulties = ['beginner', 'intermediate', 'advanced'];
        $featurePool = ['Multiplayer', 'AI', 'Networking', 'Procedural Generation', 'Save System', 'Leaderboard', 'IAP', 'Ads Integration', 'Analytics', 'Localization'];

        $badges = ['hot', 'bestseller', 'new', 'verified', 'trending', null, null, null];
        $priceRange = [0, 9.99, 19.99, 29.99, 39.99, 49.99, 59.99, 79.99];

        $count = 0;
        foreach ($products as $product) {
            $sku = $product->sku ?? '';

            // Determine engine from SKU
            $engine = 'Unity';
            if (str_contains(strtolower($sku), 'phaser') || str_contains(strtolower($product->name ?? ''), 'phaser')) {
                $engine = 'Phaser';
            } elseif (str_contains(strtolower($sku), 'godot') || str_contains(strtolower($product->name ?? ''), 'godot')) {
                $engine = 'Godot';
            } elseif (str_contains(strtolower($sku), 'unreal') || str_contains(strtolower($product->name ?? ''), 'unreal')) {
                $engine = 'Unreal';
            }

            // Determine genre from name
            $name = strtolower($product->name ?? '');
            $genre = $this->detectGenre($name);
            $genreTags = $this->generateGenreTags($genre, $name);

            // Random but deterministic data based on product_id
            $seed = $product->product_id ?? $product->id;
            $platform = $platforms[$seed % count($platforms)];
            $difficulty = $difficulties[$seed % count($difficulties)];
            $features = array_slice($featurePool, $seed % 5, rand(2, 4));
            $salesCount = ($seed * 7 + 13) % 1500 + 50;
            $isStaffPick = ($seed % 7 === 0);
            $badge = $badges[$seed % count($badges)];

            // Price: Phaser games cheaper, others varied
            $displayPrice = $engine === 'Phaser' ? $priceRange[$seed % 3] : $priceRange[$seed % count($priceRange)];
            if ($displayPrice == 0) $displayPrice = null; // Free

            // Mark newest products as "new"
            if ($product->created_at && strtotime($product->created_at) > strtotime('-14 days')) {
                $badge = 'new';
            }

            // Top sales = bestseller
            if ($salesCount > 1000) {
                $badge = 'bestseller';
            }

            DB::table('product_flat')
                ->where('id', $product->id)
                ->update([
                    'engine' => $engine,
                    'platform' => json_encode($platform),
                    'difficulty_level' => $difficulty,
                    'features' => json_encode($features),
                    'genre' => $genre,
                    'genre_tags' => json_encode($genreTags),
                    'sales_count' => $salesCount,
                    'is_staff_pick' => $isStaffPick,
                    'badge_type' => $badge,
                    'display_price_usd' => $displayPrice,
                ]);

            $count++;
        }

        $this->command->info("Enriched {$count} products with marketplace data.");
    }

    private function detectGenre(string $name): string
    {
        $genreMap = [
            'fps' => 'FPS', 'shooter' => 'FPS', 'bắn' => 'FPS', 'súng' => 'FPS',
            'rpg' => 'RPG', 'nhập vai' => 'RPG', 'quest' => 'RPG',
            'racing' => 'Racing', 'đua' => 'Racing', 'xe' => 'Racing',
            'puzzle' => 'Puzzle', 'xếp' => 'Puzzle', 'ghép' => 'Puzzle', 'đoán' => 'Puzzle', 'nhớ' => 'Puzzle', 'mìn' => 'Puzzle', 'sudoku' => 'Puzzle', 'kim cương' => 'Puzzle',
            'strategy' => 'Strategy', 'chiến thuật' => 'Strategy', 'cờ' => 'Strategy', 'tower' => 'Strategy', 'phòng thủ' => 'Strategy',
            'survival' => 'Survival', 'sinh tồn' => 'Survival',
            'platformer' => 'Platformer', 'nhảy' => 'Platformer', 'chạy' => 'Platformer', 'ếch' => 'Platformer', 'chim' => 'Platformer',
            'action' => 'Action', 'combat' => 'Action', 'rắn' => 'Action', 'hứng' => 'Action',
            'moba' => 'MOBA',
        ];

        foreach ($genreMap as $keyword => $genre) {
            if (str_contains($name, $keyword)) {
                return $genre;
            }
        }

        return 'Action'; // Default
    }

    private function generateGenreTags(string $genre, string $name): array
    {
        $tags = [$genre];

        $extraTags = [
            'FPS' => ['Shooter', 'Multiplayer'],
            'RPG' => ['Open World', 'Quest'],
            'Racing' => ['Vehicle', 'Multiplayer'],
            'Puzzle' => ['Casual', 'Brain'],
            'Strategy' => ['Tower Defense', 'Tactical'],
            'Survival' => ['Crafting', 'Inventory'],
            'Platformer' => ['2D', 'Arcade'],
            'Action' => ['Combat', 'Skills'],
            'MOBA' => ['Multiplayer', 'Framework'],
        ];

        if (isset($extraTags[$genre])) {
            $tags = array_merge($tags, array_slice($extraTags[$genre], 0, 2));
        }

        return array_slice(array_unique($tags), 0, 3);
    }
}
