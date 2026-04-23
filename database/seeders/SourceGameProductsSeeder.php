<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Webkul\Attribute\Models\Attribute;
use Webkul\Attribute\Models\AttributeOption;
use Webkul\Product\Models\Product;
use Webkul\Product\Models\ProductAttributeValue;
use Webkul\Product\Models\ProductDownloadableLink;

class SourceGameProductsSeeder extends Seeder
{
    public function run(): void
    {
        $channel = 'default';

        // Helper to get option ID by attribute code + option name
        $optionId = function (string $code, string $name) {
            $attr = Attribute::where('code', $code)->first();
            return $attr
                ? AttributeOption::where('attribute_id', $attr->id)->where('admin_name', $name)->value('id')
                : null;
        };

        // 10 Free + 10 x $1 + 10 x $2 = 30 products
        $games = [
            // ── FREE (10) ──
            ['sku' => 'mario-clone',        'name' => 'Super Mario Clone',         'price' => 0, 'engine' => 'Unity',          'lang' => 'C#',         'size' => '25 MB',  'cat' => '2D',      'desc' => 'Source code game Mario kinh điển với đầy đủ tính năng.'],
            ['sku' => 'space-shooter-2d',   'name' => 'Space Shooter 2D',          'price' => 0, 'engine' => 'Unity',          'lang' => 'C#',         'size' => '18 MB',  'cat' => '2D',      'desc' => 'Game bắn phi thuyền 2D với AI và power-ups.'],
            ['sku' => 'rpg-inventory',      'name' => 'RPG Inventory System',      'price' => 0, 'engine' => 'Unreal Engine',  'lang' => 'Blueprint',  'size' => '45 MB',  'cat' => 'Modern',  'desc' => 'Hệ thống inventory hoàn chỉnh cho game RPG.'],
            ['sku' => 'puzzle-mobile',      'name' => 'Mobile Puzzle Game',        'price' => 0, 'engine' => 'Unity',          'lang' => 'C#',         'size' => '32 MB',  'cat' => 'Mobile',  'desc' => 'Game puzzle di động với touch controls.'],
            ['sku' => 'platformer-3d',      'name' => '3D Platformer Demo',        'price' => 0, 'engine' => 'Unity',          'lang' => 'C#',         'size' => '67 MB',  'cat' => '3D',      'desc' => 'Demo game 3D platformer với physics-based gameplay.'],
            ['sku' => 'snake-classic',      'name' => 'Snake Classic',             'price' => 0, 'engine' => 'Godot',          'lang' => 'GDScript',   'size' => '5 MB',   'cat' => 'Classic', 'desc' => 'Game rắn săn mồi kinh điển viết bằng Godot.'],
            ['sku' => 'flappy-bird-clone',  'name' => 'Flappy Bird Clone',         'price' => 0, 'engine' => 'Unity',          'lang' => 'C#',         'size' => '12 MB',  'cat' => '2D',      'desc' => 'Clone Flappy Bird hoàn chỉnh với leaderboard.'],
            ['sku' => 'tetris-html5',       'name' => 'Tetris HTML5',              'price' => 0, 'engine' => 'Construct 3',    'lang' => 'JavaScript', 'size' => '3 MB',   'cat' => 'Classic', 'desc' => 'Game Tetris chạy trên trình duyệt.'],
            ['sku' => 'pong-multiplayer',   'name' => 'Pong Multiplayer',          'price' => 0, 'engine' => 'Godot',          'lang' => 'GDScript',   'size' => '8 MB',   'cat' => 'Classic', 'desc' => 'Game Pong 2 người chơi local multiplayer.'],
            ['sku' => 'breakout-game',      'name' => 'Breakout Game',             'price' => 0, 'engine' => 'Unity',          'lang' => 'C#',         'size' => '15 MB',  'cat' => '2D',      'desc' => 'Game phá gạch Breakout với nhiều level.'],

            // ── $1 (10) ──
            ['sku' => 'tower-defense',      'name' => 'Tower Defense Complete',    'price' => 1, 'engine' => 'Unity',          'lang' => 'C#',         'size' => '85 MB',  'cat' => '2D',      'desc' => 'Game Tower Defense hoàn chỉnh với 20 loại tower.'],
            ['sku' => 'match3-candy',       'name' => 'Match-3 Candy Game',       'price' => 1, 'engine' => 'Unity',          'lang' => 'C#',         'size' => '40 MB',  'cat' => 'Mobile',  'desc' => 'Game xếp kẹo Match-3 phong cách Candy Crush.'],
            ['sku' => 'endless-runner',     'name' => 'Endless Runner 3D',        'price' => 1, 'engine' => 'Unity',          'lang' => 'C#',         'size' => '55 MB',  'cat' => '3D',      'desc' => 'Game chạy vô tận 3D với obstacle generation.'],
            ['sku' => 'card-game-engine',   'name' => 'Card Game Engine',         'price' => 1, 'engine' => 'Godot',          'lang' => 'GDScript',   'size' => '20 MB',  'cat' => 'Modern',  'desc' => 'Engine cho card game với hệ thống deck building.'],
            ['sku' => 'top-down-shooter',   'name' => 'Top-Down Shooter',         'price' => 1, 'engine' => 'Unity',          'lang' => 'C#',         'size' => '35 MB',  'cat' => '2D',      'desc' => 'Game bắn súng top-down với weapon system.'],
            ['sku' => 'racing-2d',          'name' => '2D Racing Game',           'price' => 1, 'engine' => 'Construct 3',    'lang' => 'JavaScript', 'size' => '22 MB',  'cat' => '2D',      'desc' => 'Game đua xe 2D với AI opponents.'],
            ['sku' => 'farming-sim',        'name' => 'Farming Simulator Lite',   'price' => 1, 'engine' => 'Godot',          'lang' => 'GDScript',   'size' => '48 MB',  'cat' => 'Modern',  'desc' => 'Game nông trại đơn giản với hệ thống trồng trọt.'],
            ['sku' => 'quiz-trivia',        'name' => 'Quiz Trivia App',          'price' => 1, 'engine' => 'Unity',          'lang' => 'C#',         'size' => '15 MB',  'cat' => 'Mobile',  'desc' => 'App quiz trivia với hệ thống câu hỏi động.'],
            ['sku' => 'bubble-shooter',     'name' => 'Bubble Shooter',           'price' => 1, 'engine' => 'Unity',          'lang' => 'C#',         'size' => '28 MB',  'cat' => 'Mobile',  'desc' => 'Game bắn bóng Bubble Shooter với physics.'],
            ['sku' => 'chess-ai',           'name' => 'Chess with AI',            'price' => 1, 'engine' => 'Godot',          'lang' => 'GDScript',   'size' => '10 MB',  'cat' => 'Classic', 'desc' => 'Game cờ vua với AI minimax algorithm.'],

            // ── $2 (10) ──
            ['sku' => 'mmorpg-starter',     'name' => 'MMORPG Starter Kit',       'price' => 2, 'engine' => 'Unity',          'lang' => 'C#',         'size' => '150 MB', 'cat' => '3D',      'desc' => 'Starter kit MMORPG với networking và character system.'],
            ['sku' => 'fps-multiplayer',    'name' => 'FPS Multiplayer Template', 'price' => 2, 'engine' => 'Unreal Engine',  'lang' => 'C++',        'size' => '200 MB', 'cat' => '3D',      'desc' => 'Template FPS multiplayer với Unreal Networking.'],
            ['sku' => 'survival-craft',     'name' => 'Survival Crafting Game',   'price' => 2, 'engine' => 'Unity',          'lang' => 'C#',         'size' => '120 MB', 'cat' => '3D',      'desc' => 'Game sinh tồn với crafting và inventory system.'],
            ['sku' => 'rts-framework',      'name' => 'RTS Game Framework',       'price' => 2, 'engine' => 'Unity',          'lang' => 'C#',         'size' => '95 MB',  'cat' => 'Modern',  'desc' => 'Framework game chiến thuật thời gian thực.'],
            ['sku' => 'roguelike-dungeon',  'name' => 'Roguelike Dungeon',        'price' => 2, 'engine' => 'Godot',          'lang' => 'GDScript',   'size' => '60 MB',  'cat' => '2D',      'desc' => 'Game roguelike với procedural dungeon generation.'],
            ['sku' => 'visual-novel',       'name' => 'Visual Novel Engine',      'price' => 2, 'engine' => 'Unity',          'lang' => 'C#',         'size' => '45 MB',  'cat' => 'Modern',  'desc' => 'Engine visual novel với dialogue system và branching.'],
            ['sku' => 'battle-royale',      'name' => 'Battle Royale Template',   'price' => 2, 'engine' => 'Unreal Engine',  'lang' => 'Blueprint',  'size' => '180 MB', 'cat' => '3D',      'desc' => 'Template Battle Royale với zone shrinking system.'],
            ['sku' => 'rhythm-game',        'name' => 'Rhythm Game Complete',     'price' => 2, 'engine' => 'Unity',          'lang' => 'C#',         'size' => '70 MB',  'cat' => 'Modern',  'desc' => 'Game nhịp điệu hoàn chỉnh với beat mapping editor.'],
            ['sku' => 'city-builder',       'name' => 'City Builder Sim',         'price' => 2, 'engine' => 'Unity',          'lang' => 'C#',         'size' => '110 MB', 'cat' => '3D',      'desc' => 'Game xây dựng thành phố với economy system.'],
            ['sku' => 'horror-fps',         'name' => 'Horror FPS Game',          'price' => 2, 'engine' => 'Unreal Engine',  'lang' => 'C++',        'size' => '160 MB', 'cat' => '3D',      'desc' => 'Game kinh dị FPS với AI enemy và atmosphere system.'],
        ];

        // Get custom attribute IDs
        $gameEngineAttr       = Attribute::where('code', 'game_engine')->first();
        $progLangAttr         = Attribute::where('code', 'programming_language')->first();
        $fileSizeAttr         = Attribute::where('code', 'file_size')->first();
        $downloadCountAttr    = Attribute::where('code', 'downloads_count')->first();
        $sourceRatingAttr     = Attribute::where('code', 'source_rating')->first();
        $sourceCategoryAttr   = Attribute::where('code', 'source_category')->first();

        foreach ($games as $g) {
            if (Product::where('sku', $g['sku'])->exists()) {
                echo "SKIP {$g['sku']}\n";
                continue;
            }

            $product = Product::create([
                'sku'                 => $g['sku'],
                'type'                => 'downloadable',
                'attribute_family_id' => 1,
            ]);

            $urlKey = Str::slug($g['name']);

            // Category mapping (default to category 2 = Source Game)
            DB::table('product_categories')->insert([
                'product_id'  => $product->id,
                'category_id' => 2,
            ]);

            // ── Core attribute values ──
            $values = [
                // No locale, no channel
                ['attribute_id' => 1,  'text_value' => $g['sku']],                    // sku
                ['attribute_id' => 7,  'boolean_value' => 1],                          // visible_individually
                ['attribute_id' => 11, 'float_value' => $g['price']],                  // price
            ];

            foreach ($values as $v) {
                ProductAttributeValue::create(array_merge([
                    'product_id' => $product->id,
                    'channel'    => null,
                    'locale'     => null,
                ], $v));
            }

            // Channel-scoped (status)
            ProductAttributeValue::create([
                'product_id'    => $product->id,
                'attribute_id'  => 8,
                'boolean_value' => 1,
                'channel'       => $channel,
                'locale'        => null,
            ]);

            // Locale-scoped (name, url_key, short_description, description)
            foreach (['vi', 'en'] as $locale) {
                $localeValues = [
                    ['attribute_id' => 2,  'text_value' => $g['name']],
                    ['attribute_id' => 3,  'text_value' => $urlKey],
                    ['attribute_id' => 9,  'text_value' => $g['desc']],
                    ['attribute_id' => 10, 'text_value' => $g['desc']],
                ];
                foreach ($localeValues as $v) {
                    ProductAttributeValue::create(array_merge([
                        'product_id' => $product->id,
                        'channel'    => null,
                        'locale'     => $locale,
                    ], $v));
                }
            }

            // ── Custom attributes (no locale/channel) ──
            if ($gameEngineAttr) {
                $oid = $optionId('game_engine', $g['engine']);
                if ($oid) {
                    ProductAttributeValue::create([
                        'product_id'    => $product->id,
                        'attribute_id'  => $gameEngineAttr->id,
                        'integer_value' => $oid,
                    ]);
                }
            }

            if ($progLangAttr) {
                // multiselect stores comma-separated option IDs as text
                $oid = $optionId('programming_language', $g['lang']);
                if ($oid) {
                    $col = $progLangAttr->type === 'multiselect' ? 'text_value' : 'integer_value';
                    ProductAttributeValue::create([
                        'product_id'   => $product->id,
                        'attribute_id' => $progLangAttr->id,
                        $col           => (string) $oid,
                    ]);
                }
            }

            if ($fileSizeAttr) {
                ProductAttributeValue::create([
                    'product_id'   => $product->id,
                    'attribute_id' => $fileSizeAttr->id,
                    'text_value'   => $g['size'],
                ]);
            }

            if ($downloadCountAttr) {
                ProductAttributeValue::create([
                    'product_id'   => $product->id,
                    'attribute_id' => $downloadCountAttr->id,
                    'text_value'   => (string) rand(100, 2000),
                ]);
            }

            if ($sourceRatingAttr) {
                ProductAttributeValue::create([
                    'product_id'   => $product->id,
                    'attribute_id' => $sourceRatingAttr->id,
                    'text_value'   => number_format(rand(35, 50) / 10, 1),
                ]);
            }

            if ($sourceCategoryAttr) {
                $oid = $optionId('source_category', $g['cat']);
                if ($oid) {
                    ProductAttributeValue::create([
                        'product_id'    => $product->id,
                        'attribute_id'  => $sourceCategoryAttr->id,
                        'integer_value' => $oid,
                    ]);
                }
            }

            // ── Downloadable link ──
            $link = ProductDownloadableLink::create([
                'product_id' => $product->id,
                'type'       => 'file',
                'file'       => $g['sku'] . '.zip',
                'file_name'  => $g['sku'] . '.zip',
                'sort_order' => 1,
                'downloads'  => 0,
                'price'      => $g['price'],
            ]);

            // Title is translatable — insert into translations table
            DB::table('product_downloadable_link_translations')->insert([
                ['product_downloadable_link_id' => $link->id, 'locale' => 'vi', 'title' => $g['name'] . ' - Source Code'],
                ['product_downloadable_link_id' => $link->id, 'locale' => 'en', 'title' => $g['name'] . ' - Source Code'],
            ]);

            // ── Sync product_flat for listing/search ──
            foreach (['vi', 'en'] as $locale) {
                DB::table('product_flat')->insert([
                    'product_id'            => $product->id,
                    'sku'                   => $g['sku'],
                    'type'                  => 'downloadable',
                    'name'                  => $g['name'],
                    'url_key'               => $urlKey,
                    'short_description'     => $g['desc'],
                    'description'           => $g['desc'],
                    'price'                 => $g['price'],
                    'status'                => 1,
                    'visible_individually'  => 1,
                    'new'                   => 1,
                    'featured'              => 1,
                    'channel'               => $channel,
                    'locale'                => $locale,
                    'created_at'            => now(),
                    'updated_at'            => now(),
                ]);
            }

            echo "Created: {$g['name']} (\${$g['price']})\n";
        }

        echo "SourceGameProductsSeeder completed! 30 products created.\n";
    }
}
