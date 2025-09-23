<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;
use Carbon\Carbon;

class SourceGameSeeder extends Seeder
{
    /**
     * Run the database seeds for Source Game products.
     */
    public function run()
    {
        $sourceGames = [
            // Classic Arcade Games
            [
                'name' => 'Pac-Man Clone - Complete Source Code',
                'slug' => 'pac-man-clone-complete-source-code',
                'engine' => 'Unity',
                'language' => 'C#',
                'genre' => 'Arcade',
                'platform' => 'PC/Mobile',
                'description' => 'Mã nguồn hoàn chỉnh của game Pac-Man kinh điển với AI ghost, power pellets và scoring system. Perfect cho việc học tập về game AI và collision detection.',
                'features' => ['Classic Pac-Man gameplay', 'Ghost AI system', 'Level progression', 'Sound effects', 'Scoring system'],
                'difficulty' => 'Beginner',
                'file_size' => '25 MB',
                'downloads' => 2845,
                'rating' => 4.8,
                'price' => 0,
                'preview_image' => 'pacman-clone-preview.jpg'
            ],
            [
                'name' => 'Tetris Classic - Complete Implementation',
                'slug' => 'tetris-classic-complete-implementation',
                'engine' => 'Unity',
                'language' => 'C#',
                'genre' => 'Puzzle',
                'platform' => 'PC/Mobile',
                'description' => 'Triển khai đầy đủ game Tetris cổ điển với rotation mechanics, line clearing, increasing speed. Bao gồm particle effects và modern UI.',
                'features' => ['7 classic Tetris pieces', 'Line clearing animation', 'Speed progression', 'Next piece preview', 'High score system'],
                'difficulty' => 'Beginner',
                'file_size' => '18 MB',
                'downloads' => 3210,
                'rating' => 4.9,
                'price' => 0,
                'preview_image' => 'tetris-classic-preview.jpg'
            ],
            [
                'name' => 'Space Invaders Remake - Retro Style',
                'slug' => 'space-invaders-remake-retro-style',
                'engine' => 'Unity',
                'language' => 'C#',
                'genre' => 'Shooter',
                'platform' => 'PC/Mobile',
                'description' => 'Tái tạo hoàn hảo Space Invaders với pixel art graphics, wave system và classic sound effects. Ideal cho beginners học về collision và spawning.',
                'features' => ['Retro pixel art', 'Wave-based enemies', 'Power-ups', 'Lives system', 'Classic SFX'],
                'difficulty' => 'Beginner',
                'file_size' => '22 MB',
                'downloads' => 1876,
                'rating' => 4.7,
                'price' => 0,
                'preview_image' => 'space-invaders-preview.jpg'
            ],
            [
                'name' => 'Asteroids 2D - Physics Based',
                'slug' => 'asteroids-2d-physics-based',
                'engine' => 'Unity',
                'language' => 'C#',
                'genre' => 'Shooter',
                'platform' => 'PC/Mobile',
                'description' => 'Asteroids game với realistic physics, asteroid splitting, và thrust mechanics. Great example của 2D physics và particle systems.',
                'features' => ['Physics-based movement', 'Asteroid fragmentation', 'Thrust mechanics', 'Screen wrapping', 'Particle effects'],
                'difficulty' => 'Intermediate',
                'file_size' => '28 MB',
                'downloads' => 1543,
                'rating' => 4.6,
                'price' => 0,
                'preview_image' => 'asteroids-preview.jpg'
            ],
            [
                'name' => 'Frogger Classic - Traffic Dodging Game',
                'slug' => 'frogger-classic-traffic-dodging-game',
                'engine' => 'Unity',
                'language' => 'C#',
                'genre' => 'Arcade',
                'platform' => 'PC/Mobile',
                'description' => 'Frogger hoàn chỉnh với moving platforms, traffic patterns, và multiple levels. Perfect example cho pattern-based enemy movement.',
                'features' => ['Multiple lanes traffic', 'Moving platforms', 'Lives system', 'Level progression', 'Classic animations'],
                'difficulty' => 'Intermediate',
                'file_size' => '35 MB',
                'downloads' => 987,
                'rating' => 4.5,
                'price' => 0,
                'preview_image' => 'frogger-preview.jpg'
            ],

            // Platformer Games
            [
                'name' => 'Super Mario Bros Clone - Complete Platformer',
                'slug' => 'super-mario-bros-clone-complete-platformer',
                'engine' => 'Unity',
                'language' => 'C#',
                'genre' => 'Platformer',
                'platform' => 'PC/Mobile',
                'description' => 'Mã nguồn hoàn chỉnh Mario-style platformer với power-ups, enemies, pipes, và multiple worlds. Comprehensive example của 2D platformer mechanics.',
                'features' => ['Multi-world levels', 'Power-up system', 'Enemy AI', 'Pipe transitions', 'Coin collection'],
                'difficulty' => 'Advanced',
                'file_size' => '85 MB',
                'downloads' => 4521,
                'rating' => 4.9,
                'price' => 0,
                'preview_image' => 'mario-clone-preview.jpg'
            ],
            [
                'name' => 'Sonic Style Runner - High Speed Platformer',
                'slug' => 'sonic-style-runner-high-speed-platformer',
                'engine' => 'Unity',
                'language' => 'C#',
                'genre' => 'Platformer',
                'platform' => 'PC/Mobile',
                'description' => 'High-speed platformer với momentum physics, loop-de-loops, ring collection. Advanced physics và camera following systems.',
                'features' => ['Momentum-based physics', 'Speed boost zones', 'Ring collection', 'Loop mechanics', 'Dynamic camera'],
                'difficulty' => 'Advanced',
                'file_size' => '72 MB',
                'downloads' => 2134,
                'rating' => 4.7,
                'price' => 0,
                'preview_image' => 'sonic-runner-preview.jpg'
            ],
            [
                'name' => 'Metroid-style Exploration Game',
                'slug' => 'metroid-style-exploration-game',
                'engine' => 'Unity',
                'language' => 'C#',
                'genre' => 'Metroidvania',
                'platform' => 'PC/Mobile',
                'description' => 'Metroidvania với interconnected map, ability gates, save system. Complex example của procedural map generation và ability progression.',
                'features' => ['Interconnected map', 'Ability gating', 'Save/Load system', 'Map revelation', 'Backtracking mechanics'],
                'difficulty' => 'Expert',
                'file_size' => '125 MB',
                'downloads' => 1876,
                'rating' => 4.8,
                'price' => 0,
                'preview_image' => 'metroidvania-preview.jpg'
            ],

            // Puzzle Games  
            [
                'name' => 'Sokoban Puzzle - Box Pushing Game',
                'slug' => 'sokoban-puzzle-box-pushing-game',
                'engine' => 'Unity',
                'language' => 'C#',
                'genre' => 'Puzzle',
                'platform' => 'PC/Mobile',
                'description' => 'Classic Sokoban với level editor, undo system, và 50+ hand-crafted levels. Perfect example cho grid-based puzzle mechanics.',
                'features' => ['50+ puzzle levels', 'Undo system', 'Level editor', 'Grid-based movement', 'Solution validation'],
                'difficulty' => 'Intermediate',
                'file_size' => '42 MB',
                'downloads' => 1654,
                'rating' => 4.6,
                'price' => 0,
                'preview_image' => 'sokoban-preview.jpg'
            ],
            [
                'name' => 'Match-3 Game Engine - Candy Crush Style',
                'slug' => 'match-3-game-engine-candy-crush-style',
                'engine' => 'Unity',
                'language' => 'C#',
                'genre' => 'Puzzle',
                'platform' => 'Mobile',
                'description' => 'Complete match-3 engine với special pieces, cascading matches, objectives system. Mobile-optimized với touch controls.',
                'features' => ['Match-3 mechanics', 'Special pieces', 'Cascading system', 'Level objectives', 'Mobile touch controls'],
                'difficulty' => 'Advanced',
                'file_size' => '68 MB',
                'downloads' => 3876,
                'rating' => 4.8,
                'price' => 0,
                'preview_image' => 'match3-preview.jpg'
            ],

            // Fighting Games
            [
                'name' => 'Street Fighter Style - 2D Fighting Game',
                'slug' => 'street-fighter-style-2d-fighting-game',
                'engine' => 'Unity',
                'language' => 'C#',
                'genre' => 'Fighting',
                'platform' => 'PC',
                'description' => 'Complete 2D fighting game với combo system, special moves, character selection. Comprehensive fighting game mechanics và animation system.',
                'features' => ['2 playable fighters', 'Combo system', 'Special moves', 'Health/stamina bars', 'Round-based fights'],
                'difficulty' => 'Expert',
                'file_size' => '156 MB',
                'downloads' => 2341,
                'rating' => 4.7,
                'price' => 0,
                'preview_image' => 'fighting-game-preview.jpg'
            ],

            // Racing Games
            [
                'name' => 'Top-Down Racing Game - Arcade Style',
                'slug' => 'top-down-racing-game-arcade-style',
                'engine' => 'Unity',
                'language' => 'C#',
                'genre' => 'Racing',
                'platform' => 'PC/Mobile',
                'description' => 'Arcade-style top-down racing với AI opponents, multiple tracks, lap timing. Good example cho car physics và AI racing.',
                'features' => ['Multiple race tracks', 'AI opponents', 'Lap timing system', 'Car customization', 'Arcade physics'],
                'difficulty' => 'Advanced',
                'file_size' => '89 MB',
                'downloads' => 1987,
                'rating' => 4.5,
                'price' => 0,
                'preview_image' => 'racing-game-preview.jpg'
            ],

            // RPG Elements
            [
                'name' => 'Classic JRPG Battle System',
                'slug' => 'classic-jrpg-battle-system',
                'engine' => 'Unity',
                'language' => 'C#',
                'genre' => 'RPG',
                'platform' => 'PC/Mobile',
                'description' => 'Turn-based battle system inspired by classic JRPGs. Features party management, skill trees, equipment system.',
                'features' => ['Turn-based combat', 'Party management', 'Skill system', 'Equipment slots', 'Experience/Leveling'],
                'difficulty' => 'Advanced',
                'file_size' => '94 MB',
                'downloads' => 2654,
                'rating' => 4.8,
                'price' => 0,
                'preview_image' => 'jrpg-battle-preview.jpg'
            ],

            // Strategy Games
            [
                'name' => 'Chess Game with AI - Complete Implementation',
                'slug' => 'chess-game-with-ai-complete-implementation',
                'engine' => 'Unity',
                'language' => 'C#',
                'genre' => 'Strategy',
                'platform' => 'PC/Mobile',
                'description' => 'Full chess implementation với minimax AI, legal move validation, checkmate detection. Excellent example cho game AI và board game logic.',
                'features' => ['Complete chess rules', 'AI opponent', 'Move validation', 'Check/Checkmate detection', '2D board visualization'],
                'difficulty' => 'Advanced',
                'file_size' => '45 MB',
                'downloads' => 1765,
                'rating' => 4.6,
                'price' => 0,
                'preview_image' => 'chess-ai-preview.jpg'
            ],

            // Tower Defense
            [
                'name' => 'Tower Defense Complete - Path Finding System',
                'slug' => 'tower-defense-complete-path-finding-system',
                'engine' => 'Unity',
                'language' => 'C#',
                'genre' => 'Tower Defense',
                'platform' => 'PC/Mobile',
                'description' => 'Complete tower defense với A* pathfinding, multiple tower types, wave spawning. Advanced example cho pathfinding và strategy gameplay.',
                'features' => ['A* pathfinding', 'Multiple tower types', 'Wave system', 'Upgrade mechanics', 'Resource management'],
                'difficulty' => 'Expert',
                'file_size' => '112 MB',
                'downloads' => 3421,
                'rating' => 4.9,
                'price' => 0,
                'preview_image' => 'tower-defense-preview.jpg'
            ],

            // Retro Games Collection
            [
                'name' => 'Pong Classic - The Original Video Game',
                'slug' => 'pong-classic-the-original-video-game',
                'engine' => 'Unity',
                'language' => 'C#',
                'genre' => 'Sports',
                'platform' => 'PC/Mobile',
                'description' => 'Recreation của first video game ever made. Simple but perfect example cho collision detection và basic game physics.',
                'features' => ['Classic Pong gameplay', 'AI paddle opponent', 'Score system', 'Ball physics', 'Retro aesthetics'],
                'difficulty' => 'Beginner',
                'file_size' => '8 MB',
                'downloads' => 1234,
                'rating' => 4.3,
                'price' => 0,
                'preview_image' => 'pong-classic-preview.jpg'
            ],
            [
                'name' => 'Breakout Brick Breaker - Complete Game',
                'slug' => 'breakout-brick-breaker-complete-game',
                'engine' => 'Unity',
                'language' => 'C#',
                'genre' => 'Arcade',
                'platform' => 'PC/Mobile',
                'description' => 'Classic Breakout với multiple levels, power-ups, particle effects. Great starting point cho physics-based games.',
                'features' => ['Multiple brick layouts', 'Power-up system', 'Lives system', 'Particle effects', 'Progressive difficulty'],
                'difficulty' => 'Beginner',
                'file_size' => '32 MB',
                'downloads' => 2143,
                'rating' => 4.5,
                'price' => 0,
                'preview_image' => 'breakout-preview.jpg'
            ],

            // Modern Takes on Classics
            [
                'name' => 'Snake Game Modern - Multiplayer Ready',
                'slug' => 'snake-game-modern-multiplayer-ready',
                'engine' => 'Unity',
                'language' => 'C#',
                'genre' => 'Arcade',
                'platform' => 'PC/Mobile',
                'description' => 'Modern interpretation của Snake game với multiplayer support, power-ups, different game modes. Networking example included.',
                'features' => ['Multiplayer support', 'Power-ups', 'Multiple game modes', 'Leaderboards', 'Modern UI'],
                'difficulty' => 'Advanced',
                'file_size' => '67 MB',
                'downloads' => 2876,
                'rating' => 4.7,
                'price' => 0,
                'preview_image' => 'snake-modern-preview.jpg'
            ],

            // Simulation Games
            [
                'name' => 'Simple City Builder - Grid Based',
                'slug' => 'simple-city-builder-grid-based',
                'engine' => 'Unity',
                'language' => 'C#',
                'genre' => 'Simulation',
                'platform' => 'PC',
                'description' => 'Basic city builder với grid placement, resource management, population system. Good introduction to simulation game mechanics.',
                'features' => ['Grid-based building', 'Resource management', 'Population system', 'Building types', 'Economic simulation'],
                'difficulty' => 'Advanced',
                'file_size' => '78 MB',
                'downloads' => 1654,
                'rating' => 4.4,
                'price' => 0,
                'preview_image' => 'city-builder-preview.jpg'
            ],

            // Card Games
            [
                'name' => 'Solitaire Card Game - Complete Implementation',
                'slug' => 'solitaire-card-game-complete-implementation',
                'engine' => 'Unity',
                'language' => 'C#',
                'genre' => 'Card',
                'platform' => 'PC/Mobile',
                'description' => 'Full Solitaire implementation với drag-and-drop, auto-complete, statistics. Perfect example cho card game mechanics.',
                'features' => ['Drag and drop cards', 'Auto-complete', 'Statistics tracking', 'Multiple deck designs', 'Undo system'],
                'difficulty' => 'Intermediate',
                'file_size' => '54 MB',
                'downloads' => 1987,
                'rating' => 4.6,
                'price' => 0,
                'preview_image' => 'solitaire-preview.jpg'
            ],

            // Educational Games
            [
                'name' => 'Math Quiz Game - Educational Template',
                'slug' => 'math-quiz-game-educational-template',
                'engine' => 'Unity',
                'language' => 'C#',
                'genre' => 'Educational',
                'platform' => 'Mobile',
                'description' => 'Interactive math quiz game với progressive difficulty, achievements, child-friendly UI. Template for educational games.',
                'features' => ['Progressive difficulty', 'Achievement system', 'Kid-friendly UI', 'Performance tracking', 'Multiple math topics'],
                'difficulty' => 'Intermediate',
                'file_size' => '41 MB',
                'downloads' => 1432,
                'rating' => 4.5,
                'price' => 0,
                'preview_image' => 'math-quiz-preview.jpg'
            ],

            // Endless Runner
            [
                'name' => 'Endless Runner Template - Complete System',
                'slug' => 'endless-runner-template-complete-system',
                'engine' => 'Unity',
                'language' => 'C#',
                'genre' => 'Runner',
                'platform' => 'Mobile',
                'description' => 'Complete endless runner với procedural generation, power-ups, shop system. Mobile-optimized với touch controls.',
                'features' => ['Procedural generation', 'Power-up system', 'Shop/Currency', 'Touch controls', 'Performance optimized'],
                'difficulty' => 'Advanced',
                'file_size' => '89 MB',
                'downloads' => 4123,
                'rating' => 4.8,
                'price' => 0,
                'preview_image' => 'endless-runner-preview.jpg'
            ]
        ];

        // Add more games to reach 100 total
        $additionalGames = [
            // More Arcade Classics
            [
                'name' => 'Centipede Clone - Insect Shooter',
                'slug' => 'centipede-clone-insect-shooter',
                'engine' => 'Unity',
                'language' => 'C#',
                'genre' => 'Shooter',
                'platform' => 'PC/Mobile',
                'description' => 'Classic Centipede arcade shooter với mushroom obstacles, spider enemies, progressive waves.',
                'features' => ['Progressive waves', 'Multiple enemy types', 'Obstacle system', 'Classic arcade feel'],
                'difficulty' => 'Intermediate',
                'file_size' => '29 MB',
                'downloads' => 1234,
                'rating' => 4.4,
                'price' => 0,
                'preview_image' => 'centipede-preview.jpg'
            ],
            [
                'name' => 'Missile Command Defense',
                'slug' => 'missile-command-defense',
                'engine' => 'Unity',
                'language' => 'C#',
                'genre' => 'Shooter',
                'platform' => 'PC/Mobile',
                'description' => 'Defend cities from incoming missiles với trajectory-based shooting.',
                'features' => ['Trajectory shooting', 'City defense', 'Progressive difficulty', 'Explosion effects'],
                'difficulty' => 'Intermediate',
                'file_size' => '31 MB',
                'downloads' => 987,
                'rating' => 4.3,
                'price' => 0,
                'preview_image' => 'missile-command-preview.jpg'
            ],
            [
                'name' => 'Dig Dug Style - Underground Adventure',
                'slug' => 'dig-dug-style-underground-adventure',
                'engine' => 'Unity',
                'language' => 'C#',
                'genre' => 'Arcade',
                'platform' => 'PC/Mobile',
                'description' => 'Dig tunnels and defeat enemies underground với unique inflation mechanic.',
                'features' => ['Tunnel digging', 'Enemy AI', 'Rock physics', 'Classic gameplay'],
                'difficulty' => 'Advanced',
                'file_size' => '45 MB',
                'downloads' => 1543,
                'rating' => 4.6,
                'price' => 0,
                'preview_image' => 'dig-dug-preview.jpg'
            ]
        ];

        // Continue adding games until we have 100...
        $allGames = array_merge($sourceGames, $additionalGames);

        // Generate more games programmatically to reach 100
        $gameTypes = [
            'Platformer' => ['Mario Clone', 'Sonic Runner', 'Megaman Style', 'Castlevania Clone'],
            'Shooter' => ['Space Shooter', 'Side Scroller', 'Top Down', 'Twin Stick'],
            'Puzzle' => ['Block Puzzle', 'Word Game', 'Logic Puzzle', 'Physics Puzzle'],
            'RPG' => ['Turn Based', 'Action RPG', 'Dungeon Crawler', 'Text Adventure'],
            'Strategy' => ['Real Time', 'Turn Based', 'Tower Defense', 'Card Strategy'],
            'Simulation' => ['Life Sim', 'Business Sim', 'Farm Sim', 'City Builder'],
            'Racing' => ['Kart Racing', 'Formula Racing', 'Motorcycle', 'Boat Racing'],
            'Sports' => ['Soccer', 'Basketball', 'Tennis', 'Golf'],
            'Fighting' => ['Street Fighter', 'Tekken Style', '2D Fighter', 'Beat Em Up'],
            'Adventure' => ['Point Click', 'Text Adventure', 'Visual Novel', 'Exploration']
        ];

        while (count($allGames) < 100) {
            foreach ($gameTypes as $genre => $types) {
                if (count($allGames) >= 100) break;
                
                $type = $types[array_rand($types)];
                $randomName = $type . ' ' . $genre . ' - ' . ['Template', 'Clone', 'Complete', 'System', 'Engine'][array_rand(['Template', 'Clone', 'Complete', 'System', 'Engine'])];
                
                $allGames[] = [
                    'name' => $randomName,
                    'slug' => \Str::slug($randomName),
                    'engine' => ['Unity', 'Godot', 'GameMaker', 'Construct'][array_rand(['Unity', 'Godot', 'GameMaker', 'Construct'])],
                    'language' => ['C#', 'GDScript', 'JavaScript', 'C++'][array_rand(['C#', 'GDScript', 'JavaScript', 'C++'])],
                    'genre' => $genre,
                    'platform' => ['PC/Mobile', 'PC', 'Mobile', 'Web'][array_rand(['PC/Mobile', 'PC', 'Mobile', 'Web'])],
                    'description' => "Complete {$type} {$genre} implementation với modern features và optimized performance. Perfect learning resource.",
                    'features' => ['Modern implementation', 'Optimized code', 'Well documented', 'Easy to extend'],
                    'difficulty' => ['Beginner', 'Intermediate', 'Advanced'][array_rand(['Beginner', 'Intermediate', 'Advanced'])],
                    'file_size' => rand(15, 120) . ' MB',
                    'downloads' => rand(500, 5000),
                    'rating' => number_format(rand(35, 50) / 10, 1),
                    'price' => 0,
                    'preview_image' => strtolower(str_replace(' ', '-', $type)) . '-' . strtolower($genre) . '-preview.jpg'
                ];
            }
        }

        // Limit to exactly 100 games
        $allGames = array_slice($allGames, 0, 100);

        echo "Creating " . count($allGames) . " Source Game products...\n";

        foreach ($allGames as $index => $gameData) {
            // Create product
            $productId = DB::table('products')->insertGetId([
                'sku' => 'SRC_' . strtoupper(\Str::slug($gameData['slug'], '_')),
                'type' => 'simple',
                'attribute_family_id' => 1,
                'parent_id' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);

            echo "Created product {$productId}: {$gameData['name']}\n";

            // Create product flat entry for Vietnamese
            DB::table('product_flat')->insert([
                'product_id' => $productId,
                'sku' => 'SRC_' . strtoupper(\Str::slug($gameData['slug'], '_')),
                'name' => $gameData['name'],
                'description' => $gameData['description'],
                'short_description' => \Str::limit($gameData['description'], 200),
                'url_key' => $gameData['slug'],
                'meta_title' => $gameData['name'] . ' - Download Source Code',
                'meta_keywords' => $gameData['engine'] . ', ' . $gameData['language'] . ', ' . $gameData['genre'] . ', Source Code',
                'meta_description' => \Str::limit($gameData['description'], 160),
                'price' => $gameData['price'],
                'status' => 1,
                'visible_individually' => 1,
                'locale' => 'vi',
                'channel' => 'default',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);

            // Assign to Source Game category (ID: 101)
            DB::table('product_categories')->insert([
                'product_id' => $productId,
                'category_id' => 101
            ]);

            // Add custom attributes for source games
            $attributes = [
                'engine' => $gameData['engine'],
                'programming_language' => $gameData['language'],
                'game_genre' => $gameData['genre'],
                'target_platform' => $gameData['platform'],
                'difficulty_level' => $gameData['difficulty'],
                'file_size' => $gameData['file_size'],
                'download_count' => $gameData['downloads'],
                'user_rating' => $gameData['rating'],
                'key_features' => implode(', ', $gameData['features']),
                'preview_image' => $gameData['preview_image']
            ];

            foreach ($attributes as $attrCode => $value) {
                // Find or create attribute
                $attributeId = DB::table('attributes')
                    ->where('code', $attrCode)
                    ->value('id');

                if (!$attributeId) {
                    $attributeId = DB::table('attributes')->insertGetId([
                        'code' => $attrCode,
                        'admin_name' => ucwords(str_replace('_', ' ', $attrCode)),
                        'type' => in_array($attrCode, ['download_count']) ? 'integer' : 'text',
                        'validation' => null,
                        'position' => 1,
                        'is_required' => false,
                        'is_unique' => false,
                        'value_per_locale' => false,
                        'value_per_channel' => false,
                        'is_filterable' => true,
                        'is_configurable' => false,
                        'is_user_defined' => true,
                        'is_visible_on_front' => true,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now()
                    ]);

                    // Create attribute translation
                    DB::table('attribute_translations')->insert([
                        'attribute_id' => $attributeId,
                        'locale' => 'vi',
                        'name' => ucwords(str_replace('_', ' ', $attrCode))
                    ]);
                }

                // Insert attribute value
                DB::table('product_attribute_values')->insert([
                    'product_id' => $productId,
                    'attribute_id' => $attributeId,
                    'locale' => 'vi',
                    'channel' => 'default',
                    'text_value' => in_array($attrCode, ['download_count']) ? null : (string)$value,
                    'integer_value' => $attrCode === 'download_count' ? (int)$value : null,
                    'float_value' => $attrCode === 'user_rating' ? (float)$value : null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);
            }
        }

        echo "Successfully created " . count($allGames) . " Source Game products!\n";
    }
}
