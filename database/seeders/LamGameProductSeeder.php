<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Webkul\Attribute\Models\Attribute;
use Webkul\Attribute\Models\AttributeGroup;
use Webkul\Attribute\Models\AttributeOption;
use Webkul\Attribute\Models\AttributeOptionTranslation;
use Webkul\Attribute\Models\AttributeTranslation;
use Webkul\Category\Models\Category;
use Webkul\Category\Models\CategoryTranslation;
use Webkul\Core\Models\Channel;
use Webkul\Core\Models\Locale;
use Webkul\Product\Models\Product;
use Webkul\Product\Models\ProductAttributeValue;
use Webkul\Category\Models\CategoryProduct;
use DB;

class LamGameProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->createGameCategories();
        // $this->createGameAttributes(); // Skip for now due to conflicts
        // $this->createSampleProducts(); // Skip for now, need custom attributes first
    }

    /**
     * Create game-related categories
     */
    private function createGameCategories()
    {
        $viLocale = Locale::where('code', 'vi')->first();
        $channel = Channel::first();

        // Main categories structure for game development marketplace
        $categories = [
            'source-code' => [
                'name_vi' => 'Source Code Game',
                'name_en' => 'Game Source Code',
                'description_vi' => 'Mã nguồn game hoàn chỉnh, template và framework',
                'description_en' => 'Complete game source code, templates and frameworks',
                'children' => [
                    'unity-games' => [
                        'name_vi' => 'Unity Games',
                        'name_en' => 'Unity Games',
                        'description_vi' => 'Source code game Unity 2D/3D',
                        'description_en' => 'Unity 2D/3D game source code'
                    ],
                    'mobile-games' => [
                        'name_vi' => 'Mobile Games',
                        'name_en' => 'Mobile Games',
                        'description_vi' => 'Game mobile Android/iOS',
                        'description_en' => 'Android/iOS mobile games'
                    ],
                    'web-games' => [
                        'name_vi' => 'Web Games',
                        'name_en' => 'Web Games',
                        'description_vi' => 'Game HTML5, JavaScript, WebGL',
                        'description_en' => 'HTML5, JavaScript, WebGL games'
                    ],
                    'pc-games' => [
                        'name_vi' => 'PC Games',
                        'name_en' => 'PC Games',
                        'description_vi' => 'Game PC Windows/Mac/Linux',
                        'description_en' => 'PC Windows/Mac/Linux games'
                    ]
                ]
            ],
            'game-assets' => [
                'name_vi' => 'Tài Nguyên Game',
                'name_en' => 'Game Assets',
                'description_vi' => 'Sprites, models 3D, âm thanh, hiệu ứng',
                'description_en' => 'Sprites, 3D models, sounds, effects',
                'children' => [
                    '2d-sprites' => [
                        'name_vi' => 'Sprites 2D',
                        'name_en' => '2D Sprites',
                        'description_vi' => 'Sprites nhân vật, background, UI elements',
                        'description_en' => 'Character sprites, backgrounds, UI elements'
                    ],
                    '3d-models' => [
                        'name_vi' => 'Models 3D',
                        'name_en' => '3D Models',
                        'description_vi' => 'Models 3D nhân vật, environment, props',
                        'description_en' => '3D character models, environments, props'
                    ],
                    'audio-sfx' => [
                        'name_vi' => 'Âm Thanh & SFX',
                        'name_en' => 'Audio & SFX',
                        'description_vi' => 'Nhạc nền, sound effects, voice acting',
                        'description_en' => 'Background music, sound effects, voice acting'
                    ],
                    'ui-kits' => [
                        'name_vi' => 'UI Kits',
                        'name_en' => 'UI Kits',
                        'description_vi' => 'Giao diện game, buttons, icons, fonts',
                        'description_en' => 'Game UI, buttons, icons, fonts'
                    ]
                ]
            ],
            'game-tools' => [
                'name_vi' => 'Công Cụ Game',
                'name_en' => 'Game Tools',
                'description_vi' => 'Tools hỗ trợ phát triển game',
                'description_en' => 'Game development support tools',
                'children' => [
                    'editors' => [
                        'name_vi' => 'Editors & IDEs',
                        'name_en' => 'Editors & IDEs',
                        'description_vi' => 'Code editors, IDE chuyên dụng',
                        'description_en' => 'Code editors, specialized IDEs'
                    ],
                    'plugins' => [
                        'name_vi' => 'Plugins',
                        'name_en' => 'Plugins',
                        'description_vi' => 'Unity plugins, Unreal extensions',
                        'description_en' => 'Unity plugins, Unreal extensions'
                    ],
                    'libraries' => [
                        'name_vi' => 'Libraries',
                        'name_en' => 'Libraries',
                        'description_vi' => 'Code libraries, frameworks',
                        'description_en' => 'Code libraries, frameworks'
                    ]
                ]
            ],
            'services' => [
                'name_vi' => 'Dịch Vụ Game',
                'name_en' => 'Game Services',
                'description_vi' => 'Dịch vụ phát triển game theo yêu cầu',
                'description_en' => 'Custom game development services',
                'children' => [
                    'custom-development' => [
                        'name_vi' => 'Phát Triển Theo Yêu Cầu',
                        'name_en' => 'Custom Development',
                        'description_vi' => 'Làm game theo yêu cầu riêng',
                        'description_en' => 'Custom game development'
                    ],
                    'porting-services' => [
                        'name_vi' => 'Chuyển Đổi Platform',
                        'name_en' => 'Porting Services',
                        'description_vi' => 'Chuyển game sang platform khác',
                        'description_en' => 'Game porting to other platforms'
                    ],
                    'optimization' => [
                        'name_vi' => 'Tối Ưu Hóa',
                        'name_en' => 'Optimization',
                        'description_vi' => 'Tối ưu hiệu năng, giảm dung lượng',
                        'description_en' => 'Performance optimization, size reduction'
                    ]
                ]
            ],
            'learning-resources' => [
                'name_vi' => 'Tài Liệu Học Tập',
                'name_en' => 'Learning Resources',
                'description_vi' => 'Khóa học, tutorial, ebook về game development',
                'description_en' => 'Courses, tutorials, ebooks for game development',
                'children' => [
                    'video-courses' => [
                        'name_vi' => 'Khóa Học Video',
                        'name_en' => 'Video Courses',
                        'description_vi' => 'Khóa học game development từ cơ bản đến nâng cao',
                        'description_en' => 'Game development courses from basic to advanced'
                    ],
                    'ebooks' => [
                        'name_vi' => 'Ebook & PDF',
                        'name_en' => 'Ebooks & PDF',
                        'description_vi' => 'Tài liệu PDF, ebook về game programming',
                        'description_en' => 'PDF materials, ebooks about game programming'
                    ],
                    'project-based' => [
                        'name_vi' => 'Học Qua Dự Án',
                        'name_en' => 'Project-Based Learning',
                        'description_vi' => 'Học làm game qua các dự án thực tế',
                        'description_en' => 'Learn game development through real projects'
                    ]
                ]
            ]
        ];

        $this->createCategoryTree($categories, 1); // Parent ID = 1 (Root)
    }

    /**
     * Recursively create category tree
     */
    private function createCategoryTree($categories, $parentId)
    {
        $viLocale = Locale::where('code', 'vi')->first();
        
        foreach ($categories as $slug => $categoryData) {
            // Create category
            $category = Category::create([
                'parent_id' => $parentId,
                'position' => 1,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Create Vietnamese translation - use slug as url_path for simplicity
            CategoryTranslation::create([
                'category_id' => $category->id,
                'name' => $categoryData['name_vi'],
                'slug' => $slug,
                'url_path' => $slug,
                'description' => $categoryData['description_vi'],
                'meta_title' => $categoryData['name_vi'],
                'meta_description' => $categoryData['description_vi'],
                'meta_keywords' => $categoryData['name_vi'],
                'locale_id' => $viLocale->id,
            ]);

            // English translation is handled by the vi locale for now

            // Create children if exists
            if (isset($categoryData['children'])) {
                $this->createCategoryTree($categoryData['children'], $category->id);
            }
        }
    }

    /**
     * Create game-specific product attributes
     */
    private function createGameAttributes()
    {
        $viLocale = Locale::where('code', 'vi')->first();

        // Get default attribute family
        $attributeFamily = \Webkul\Attribute\Models\AttributeFamily::where('code', 'default')->first();
        
        // Get or create attribute groups
        $gameInfoGroup = AttributeGroup::firstOrCreate(
            ['code' => 'game_info', 'attribute_family_id' => $attributeFamily->id],
            [
                'name' => 'Game Information',
                'column' => 1,
                'is_user_defined' => 1,
                'position' => 10,
            ]
        );

        $technicalGroup = AttributeGroup::firstOrCreate(
            ['code' => 'technical_details', 'attribute_family_id' => $attributeFamily->id],
            [
                'name' => 'Technical Details',
                'column' => 2,
                'is_user_defined' => 1,
                'position' => 11,
            ]
        );

        // Game-specific attributes
        $gameAttributes = [
            // Game Information Group
            [
                'code' => 'game_genre',
                'admin_name' => 'Game Genre',
                'type' => 'select',
                'group_id' => $gameInfoGroup->id,
                'position' => 1,
                'is_filterable' => 1,
                'translations' => [
                    'vi' => ['name' => 'Thể Loại Game'],
                    'en' => ['name' => 'Game Genre']
                ],
                'options' => [
                    ['vi' => 'Action - Hành động', 'en' => 'Action'],
                    ['vi' => 'Adventure - Phiêu lưu', 'en' => 'Adventure'], 
                    ['vi' => 'RPG - Nhập vai', 'en' => 'RPG'],
                    ['vi' => 'Strategy - Chiến thuật', 'en' => 'Strategy'],
                    ['vi' => 'Puzzle - Giải đố', 'en' => 'Puzzle'],
                    ['vi' => 'Racing - Đua xe', 'en' => 'Racing'],
                    ['vi' => 'Sports - Thể thao', 'en' => 'Sports'],
                    ['vi' => 'Simulation - Mô phỏng', 'en' => 'Simulation'],
                    ['vi' => 'Casual - Giải trí', 'en' => 'Casual'],
                    ['vi' => 'Educational - Giáo dục', 'en' => 'Educational']
                ]
            ],
            [
                'code' => 'game_platform',
                'admin_name' => 'Platform',
                'type' => 'multiselect',
                'group_id' => $gameInfoGroup->id,
                'position' => 2,
                'is_filterable' => 1,
                'translations' => [
                    'vi' => ['name' => 'Nền Tảng'],
                    'en' => ['name' => 'Platform']
                ],
                'options' => [
                    ['vi' => 'Android', 'en' => 'Android'],
                    ['vi' => 'iOS', 'en' => 'iOS'],
                    ['vi' => 'Windows PC', 'en' => 'Windows PC'],
                    ['vi' => 'Mac OS', 'en' => 'Mac OS'],
                    ['vi' => 'Linux', 'en' => 'Linux'],
                    ['vi' => 'Web Browser', 'en' => 'Web Browser'],
                    ['vi' => 'Nintendo Switch', 'en' => 'Nintendo Switch'],
                    ['vi' => 'PlayStation', 'en' => 'PlayStation'],
                    ['vi' => 'Xbox', 'en' => 'Xbox']
                ]
            ],
            [
                'code' => 'game_engine',
                'admin_name' => 'Game Engine',
                'type' => 'select',
                'group_id' => $technicalGroup->id,
                'position' => 3,
                'is_filterable' => 1,
                'translations' => [
                    'vi' => ['name' => 'Game Engine'],
                    'en' => ['name' => 'Game Engine']
                ],
                'options' => [
                    ['vi' => 'Unity', 'en' => 'Unity'],
                    ['vi' => 'Unreal Engine', 'en' => 'Unreal Engine'],
                    ['vi' => 'Godot', 'en' => 'Godot'],
                    ['vi' => 'Construct 3', 'en' => 'Construct 3'],
                    ['vi' => 'GameMaker Studio', 'en' => 'GameMaker Studio'],
                    ['vi' => 'Cocos2d', 'en' => 'Cocos2d'],
                    ['vi' => 'HTML5/JavaScript', 'en' => 'HTML5/JavaScript'],
                    ['vi' => 'Flutter', 'en' => 'Flutter'],
                    ['vi' => 'React Native', 'en' => 'React Native'],
                    ['vi' => 'Native (Java/Kotlin)', 'en' => 'Native (Java/Kotlin)'],
                    ['vi' => 'Native (Swift/Objective-C)', 'en' => 'Native (Swift/Objective-C)']
                ]
            ],
            [
                'code' => 'programming_language',
                'admin_name' => 'Programming Language',
                'type' => 'multiselect',
                'group_id' => $technicalGroup->id,
                'position' => 4,
                'is_filterable' => 1,
                'translations' => [
                    'vi' => ['name' => 'Ngôn Ngữ Lập Trình'],
                    'en' => ['name' => 'Programming Language']
                ],
                'options' => [
                    ['vi' => 'C#', 'en' => 'C#'],
                    ['vi' => 'JavaScript', 'en' => 'JavaScript'],
                    ['vi' => 'Python', 'en' => 'Python'],
                    ['vi' => 'Java', 'en' => 'Java'],
                    ['vi' => 'Kotlin', 'en' => 'Kotlin'],
                    ['vi' => 'Swift', 'en' => 'Swift'],
                    ['vi' => 'C++', 'en' => 'C++'],
                    ['vi' => 'HTML5/CSS3', 'en' => 'HTML5/CSS3'],
                    ['vi' => 'GDScript', 'en' => 'GDScript'],
                    ['vi' => 'Lua', 'en' => 'Lua']
                ]
            ],
            [
                'code' => 'difficulty_level',
                'admin_name' => 'Difficulty Level',
                'type' => 'select',
                'group_id' => $gameInfoGroup->id,
                'position' => 5,
                'is_filterable' => 1,
                'translations' => [
                    'vi' => ['name' => 'Độ Khó'],
                    'en' => ['name' => 'Difficulty Level']
                ],
                'options' => [
                    ['vi' => 'Người mới bắt đầu', 'en' => 'Beginner'],
                    ['vi' => 'Trung bình', 'en' => 'Intermediate'],
                    ['vi' => 'Nâng cao', 'en' => 'Advanced'],
                    ['vi' => 'Chuyên gia', 'en' => 'Expert']
                ]
            ],
            [
                'code' => 'includes_source',
                'admin_name' => 'Includes Source Code',
                'type' => 'boolean',
                'group_id' => $technicalGroup->id,
                'position' => 6,
                'is_filterable' => 1,
                'translations' => [
                    'vi' => ['name' => 'Bao Gồm Source Code'],
                    'en' => ['name' => 'Includes Source Code']
                ]
            ],
            [
                'code' => 'includes_assets',
                'admin_name' => 'Includes Assets',
                'type' => 'boolean',
                'group_id' => $technicalGroup->id,
                'position' => 7,
                'is_filterable' => 1,
                'translations' => [
                    'vi' => ['name' => 'Bao Gồm Assets'],
                    'en' => ['name' => 'Includes Assets']
                ]
            ],
            [
                'code' => 'documentation_included',
                'admin_name' => 'Documentation Included',
                'type' => 'boolean',
                'group_id' => $technicalGroup->id,
                'position' => 8,
                'is_filterable' => 1,
                'translations' => [
                    'vi' => ['name' => 'Có Tài Liệu'],
                    'en' => ['name' => 'Documentation Included']
                ]
            ],
            [
                'code' => 'support_included',
                'admin_name' => 'Support Included',
                'type' => 'select',
                'group_id' => $gameInfoGroup->id,
                'position' => 9,
                'is_filterable' => 1,
                'translations' => [
                    'vi' => ['name' => 'Hỗ Trợ'],
                    'en' => ['name' => 'Support Included']
                ],
                'options' => [
                    ['vi' => 'Không hỗ trợ', 'en' => 'No Support'],
                    ['vi' => 'Email hỗ trợ', 'en' => 'Email Support'],
                    ['vi' => 'Hỗ trợ 1-1', 'en' => '1-on-1 Support'],
                    ['vi' => 'Hỗ trợ trọn đời', 'en' => 'Lifetime Support']
                ]
            ],
            [
                'code' => 'license_type',
                'admin_name' => 'License Type',
                'type' => 'select',
                'group_id' => $technicalGroup->id,
                'position' => 10,
                'is_filterable' => 1,
                'translations' => [
                    'vi' => ['name' => 'Loại Bản Quyền'],
                    'en' => ['name' => 'License Type']
                ],
                'options' => [
                    ['vi' => 'Sử dụng cá nhân', 'en' => 'Personal Use'],
                    ['vi' => 'Thương mại', 'en' => 'Commercial Use'],
                    ['vi' => 'Mở rộng không giới hạn', 'en' => 'Extended License'],
                    ['vi' => 'Độc quyền', 'en' => 'Exclusive License']
                ]
            ]
        ];

        foreach ($gameAttributes as $attributeData) {
            // Create attribute
            $attribute = Attribute::create([
                'code' => $attributeData['code'],
                'admin_name' => $attributeData['admin_name'],
                'type' => $attributeData['type'],
                'position' => $attributeData['position'],
                'is_required' => 0,
                'is_unique' => 0,
                'is_filterable' => $attributeData['is_filterable'],
                'is_configurable' => 0,
                'is_user_defined' => 1,
                'is_visible_on_front' => 1,
                'value_per_locale' => 0,
                'value_per_channel' => 0,
            ]);

            // Add attribute to group
            DB::table('attribute_group_mappings')->insert([
                'attribute_id' => $attribute->id,
                'attribute_group_id' => $attributeData['group_id'],
                'position' => $attributeData['position'],
            ]);

            // Create Vietnamese translation only
            AttributeTranslation::create([
                'attribute_id' => $attribute->id,
                'name' => $attributeData['translations']['vi']['name'],
                'locale' => $viLocale->code,
            ]);

            // Create options for select/multiselect attributes
            if (in_array($attributeData['type'], ['select', 'multiselect']) && isset($attributeData['options'])) {
                foreach ($attributeData['options'] as $index => $optionData) {
                    $option = AttributeOption::create([
                        'attribute_id' => $attribute->id,
                        'sort_order' => $index + 1,
                    ]);

                    // Use Vietnamese label
                    AttributeOptionTranslation::create([
                        'attribute_option_id' => $option->id,
                        'locale' => $viLocale->code,
                        'label' => $optionData['vi'],
                    ]);
                }
            }
        }
    }

    /**
     * Create sample products
     */
    private function createSampleProducts()
    {
        $viLocale = Locale::where('code', 'vi')->first();
        $channel = Channel::first();
        
        // Get categories - find by slug in translations
        $sourceCodeCategory = Category::whereHas('translations', function($q) {
            $q->where('slug', 'unity-games');
        })->first();

        // If unity-games category doesn't exist, use the first available category after Root
        if (!$sourceCodeCategory) {
            $sourceCodeCategory = Category::where('id', '>', 1)->first();
        }
        
        if (!$sourceCodeCategory) {
            echo "No suitable category found for sample products.\n";
            return;
        }

        // Sample products
        $sampleProducts = [
            [
                'sku' => 'UNITY_ENDLESS_RUNNER_001',
                'name' => 'Unity 3D Endless Runner Game - Source Code',
                'description' => '<h2>Unity 3D Endless Runner Game - Complete Source Code</h2>
                <p>Một game endless runner 3D hoàn chỉnh được phát triển trên Unity, bao gồm đầy đủ source code, assets và documentation. Game có gameplay hấp dẫn với nhiều tính năng:</p>
                
                <h3>✅ Tính năng chính:</h3>
                <ul>
                <li>🎮 Gameplay endless runner mượt mà</li>
                <li>🏃‍♂️ Character controller với animation đẹp mắt</li>
                <li>🌍 Procedural level generation</li>
                <li>💰 Coin collection system</li>
                <li>🛍️ In-app purchase integration</li>
                <li>📊 Leaderboard & achievements</li>
                <li>🎵 Background music & sound effects</li>
                <li>📱 Mobile-optimized UI</li>
                </ul>
                
                <h3>🔧 Technical Details:</h3>
                <ul>
                <li>🎯 Unity 2022.3 LTS</li>
                <li>💻 C# programming</li>
                <li>📱 Ready for Android & iOS</li>
                <li>🎨 Complete art assets included</li>
                <li>📖 Documentation & setup guide</li>
                <li>🛠️ Easy to customize</li>
                </ul>',
                'short_description' => 'Unity 3D Endless Runner game với đầy đủ source code, assets và documentation. Sẵn sàng publish lên Android/iOS.',
                'price' => 1500000, // 1.5 million VND
                'weight' => 0,
                'status' => 1,
                'featured' => 1,
                'new' => 1,
                'visible_individually' => 1,
                'meta_title' => 'Unity 3D Endless Runner Game Source Code - LamGame',
                'meta_description' => 'Mua source code Unity 3D Endless Runner game hoàn chỉnh. Bao gồm assets, documentation, sẵn sàng publish Android/iOS.',
                'meta_keywords' => 'unity, endless runner, source code, mobile game, android, ios',
                'category_ids' => [$sourceCodeCategory->id],
                'attributes' => [
                    'game_genre' => 'Action - Hành động',
                    'game_platform' => ['Android', 'iOS'],
                    'game_engine' => 'Unity',
                    'programming_language' => ['C#'],
                    'difficulty_level' => 'Trung bình',
                    'includes_source' => 1,
                    'includes_assets' => 1,
                    'documentation_included' => 1,
                    'support_included' => 'Email hỗ trợ',
                    'license_type' => 'Thương mại'
                ]
            ],
            [
                'sku' => 'MOBILE_PUZZLE_MATCH3_001',
                'name' => 'Match-3 Puzzle Game - Complete Mobile Game Kit',
                'description' => '<h2>Match-3 Puzzle Game - Complete Mobile Game Kit</h2>
                <p>Game puzzle match-3 hoàn chỉnh với hơn 100 levels, được tối ưu cho mobile. Source code clean, dễ customize và extend.</p>
                
                <h3>🎯 Game Features:</h3>
                <ul>
                <li>🧩 100+ unique levels</li>
                <li>⭐ Star rating system</li>
                <li>🎁 Daily rewards & bonuses</li>
                <li>💎 Power-ups & special items</li>
                <li>🏆 Achievement system</li>
                <li>💰 Virtual currency system</li>
                <li>🎨 Colorful graphics & animations</li>
                </ul>
                
                <h3>💻 Technical Stack:</h3>
                <ul>
                <li>🎯 Unity 2022.3 LTS</li>
                <li>📱 Mobile-first design</li>
                <li>💾 Save/load game progress</li>
                <li>📊 Analytics integration ready</li>
                <li>🛒 IAP integration</li>
                <li>📱 Responsive UI for all screen sizes</li>
                </ul>',
                'short_description' => 'Game puzzle Match-3 hoàn chỉnh với 100+ levels. Source code Unity, sẵn sàng publish và monetize.',
                'price' => 2500000, // 2.5 million VND
                'weight' => 0,
                'status' => 1,
                'featured' => 1,
                'new' => 1,
                'visible_individually' => 1,
                'meta_title' => 'Match-3 Puzzle Game Unity Source Code - LamGame',
                'meta_description' => 'Source code game Match-3 Puzzle hoàn chỉnh với 100+ levels. Unity, sẵn sàng publish mobile.',
                'meta_keywords' => 'match-3, puzzle game, unity, mobile game, source code',
                'category_ids' => [$sourceCodeCategory->id],
                'attributes' => [
                    'game_genre' => 'Puzzle - Giải đố',
                    'game_platform' => ['Android', 'iOS'],
                    'game_engine' => 'Unity',
                    'programming_language' => ['C#'],
                    'difficulty_level' => 'Người mới bắt đầu',
                    'includes_source' => 1,
                    'includes_assets' => 1,
                    'documentation_included' => 1,
                    'support_included' => 'Hỗ trợ 1-1',
                    'license_type' => 'Thương mại'
                ]
            ]
        ];

        foreach ($sampleProducts as $productData) {
            // Create product
            $product = Product::create([
                'sku' => $productData['sku'],
                'type' => 'simple',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Create attribute values
            $this->createProductAttributeValues($product, $productData, $viLocale->id, $channel->id);

            // Assign to categories
            foreach ($productData['category_ids'] as $categoryId) {
                CategoryProduct::create([
                    'product_id' => $product->id,
                    'category_id' => $categoryId,
                ]);
            }
        }
    }

    /**
     * Create product attribute values
     */
    private function createProductAttributeValues($product, $productData, $localeId, $channelId)
    {
        $simpleAttributes = [
            'sku' => $productData['sku'],
            'name' => $productData['name'],
            'description' => $productData['description'],
            'short_description' => $productData['short_description'],
            'price' => $productData['price'],
            'weight' => $productData['weight'],
            'status' => $productData['status'],
            'featured' => $productData['featured'],
            'new' => $productData['new'],
            'visible_individually' => $productData['visible_individually'],
            'meta_title' => $productData['meta_title'],
            'meta_description' => $productData['meta_description'],
            'meta_keywords' => $productData['meta_keywords'],
        ];

        foreach ($simpleAttributes as $code => $value) {
            $attribute = Attribute::where('code', $code)->first();
            if ($attribute) {
                ProductAttributeValue::create([
                    'product_id' => $product->id,
                    'attribute_id' => $attribute->id,
                    'channel' => 'default',
                    'locale' => 'vi',
                    'text_value' => in_array($attribute->type, ['price']) ? null : (string)$value,
                    'float_value' => in_array($attribute->type, ['price']) ? (float)$value : null,
                    'integer_value' => in_array($attribute->type, ['boolean']) ? (int)$value : null,
                    'boolean_value' => in_array($attribute->type, ['boolean']) ? (bool)$value : null,
                ]);
            }
        }

        // Handle custom attributes
        if (isset($productData['attributes'])) {
            foreach ($productData['attributes'] as $code => $value) {
                $attribute = Attribute::where('code', $code)->first();
                if ($attribute) {
                    if (in_array($attribute->type, ['select', 'multiselect'])) {
                        $values = is_array($value) ? $value : [$value];
                        $optionIds = [];
                        
                        foreach ($values as $val) {
                            $option = AttributeOption::whereHas('translations', function($q) use ($val) {
                                $q->where('label', $val);
                            })->where('attribute_id', $attribute->id)->first();
                            
                            if ($option) {
                                $optionIds[] = $option->id;
                            }
                        }
                        
                        if (!empty($optionIds)) {
                            ProductAttributeValue::create([
                                'product_id' => $product->id,
                                'attribute_id' => $attribute->id,
                                'channel' => 'default',
                                'locale' => 'vi',
                                'text_value' => implode(',', $optionIds),
                            ]);
                        }
                    } elseif ($attribute->type === 'boolean') {
                        ProductAttributeValue::create([
                            'product_id' => $product->id,
                            'attribute_id' => $attribute->id,
                            'channel' => 'default',
                            'locale' => 'vi',
                            'integer_value' => (int)$value,
                            'boolean_value' => (bool)$value,
                        ]);
                    } else {
                        ProductAttributeValue::create([
                            'product_id' => $product->id,
                            'attribute_id' => $attribute->id,
                            'channel' => 'default',
                            'locale' => 'vi',
                            'text_value' => (string)$value,
                        ]);
                    }
                }
            }
        }
    }
}
