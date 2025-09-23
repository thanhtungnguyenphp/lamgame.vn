<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Product\Repositories\ProductDownloadableLinkRepository;
use Webkul\Core\Models\Channel;
use Webkul\Inventory\Models\InventorySource;
use Webkul\Product\Models\Product;
use Webkul\Category\Models\Category;

class SourceGameProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $channel = Channel::first();
        $inventorySource = InventorySource::first();

        // Sample source game data based on featuredSources from controller
        $sourceGames = [
            [
                'sku' => 'super-mario-clone',
                'name' => 'Super Mario Clone',
                'description' => 'Source code hoàn chỉnh của game Mario kinh điển với đầy đủ tính năng di chuyển, thu thập coin, enemy AI và level design. Phù hợp cho việc học tập và nghiên cứu game development.',
                'short_description' => 'Source code hoàn chỉnh của game Mario kinh điển',
                'price' => 0, // Free
                'game_engine' => 'Unity',
                'programming_language' => 'C#',
                'file_size' => '25 MB',
                'downloads_count' => 1250,
                'rating' => 4.8,
                'source_category' => 'Classic',
                'category_id' => 3, // Unity Games category
                'images' => [
                    'https://images.unsplash.com/photo-1550745165-9bc0b252726f?w=800&h=600&fit=crop',
                ],
                'downloadable_links' => [
                    [
                        'title' => 'Super Mario Clone - Unity Project',
                        'file' => 'super-mario-clone.zip',
                        'downloads' => 0,
                    ]
                ]
            ],
            [
                'sku' => 'space-shooter-2d',
                'name' => 'Space Shooter 2D',
                'description' => 'Game bắn phi thuyền 2D với AI thông minh, power-ups đa dạng, hệ thống điểm số và nhiều level khác nhau. Source code được tổ chức rõ ràng và có comment chi tiết.',
                'short_description' => 'Game bắn phi thuyền 2D với AI và power-ups',
                'price' => 0, // Free
                'game_engine' => 'Unity',
                'programming_language' => 'C#',
                'file_size' => '18 MB',
                'downloads_count' => 890,
                'rating' => 4.6,
                'source_category' => '2D',
                'category_id' => 3, // Unity Games category
                'images' => [
                    'https://images.unsplash.com/photo-1614294148960-9aa740632117?w=800&h=600&fit=crop',
                ],
                'downloadable_links' => [
                    [
                        'title' => 'Space Shooter 2D - Unity Project',
                        'file' => 'space-shooter-2d.zip',
                        'downloads' => 0,
                    ]
                ]
            ],
            [
                'sku' => 'rpg-inventory-system',
                'name' => 'RPG Inventory System',
                'description' => 'Hệ thống inventory hoàn chỉnh cho game RPG với drag & drop, item stacking, equipment system, và UI tương tác trực quan. Được xây dựng với Unreal Engine Blueprint system.',
                'short_description' => 'Hệ thống inventory hoàn chỉnh cho game RPG',
                'price' => 0, // Free
                'game_engine' => 'Unreal Engine',
                'programming_language' => 'Blueprint',
                'file_size' => '45 MB',
                'downloads_count' => 567,
                'rating' => 4.9,
                'source_category' => 'Modern',
                'category_id' => 2, // Source Code Game category
                'images' => [
                    'https://images.unsplash.com/photo-1542751371-adc38448a05e?w=800&h=600&fit=crop',
                ],
                'downloadable_links' => [
                    [
                        'title' => 'RPG Inventory System - Unreal Project',
                        'file' => 'rpg-inventory-system.zip',
                        'downloads' => 0,
                    ]
                ]
            ],
            [
                'sku' => 'mobile-puzzle-game',
                'name' => 'Mobile Puzzle Game',
                'description' => 'Game puzzle di động với touch controls, level editor, progression system và monetization features. Được tối ưu cho cả Android và iOS.',
                'short_description' => 'Game puzzle di động với touch controls và level editor',
                'price' => 0, // Free
                'game_engine' => 'Unity',
                'programming_language' => 'C#',
                'file_size' => '32 MB',
                'downloads_count' => 445,
                'rating' => 4.7,
                'source_category' => 'Mobile',
                'category_id' => 4, // Mobile Games category
                'images' => [
                    'https://images.unsplash.com/photo-1606092195730-5d7b9af1efc5?w=800&h=600&fit=crop',
                ],
                'downloadable_links' => [
                    [
                        'title' => 'Mobile Puzzle Game - Unity Project',
                        'file' => 'mobile-puzzle-game.zip',
                        'downloads' => 0,
                    ]
                ]
            ],
            [
                'sku' => '3d-platformer-demo',
                'name' => '3D Platformer Demo',
                'description' => 'Demo game 3D platformer với character controller, physics-based gameplay, collectibles system và beautiful 3D environments. Ideal cho việc học 3D game development.',
                'short_description' => 'Demo game 3D platformer với physics-based gameplay',
                'price' => 0, // Free
                'game_engine' => 'Unity',
                'programming_language' => 'C#',
                'file_size' => '67 MB',
                'downloads_count' => 723,
                'rating' => 4.5,
                'source_category' => '3D',
                'category_id' => 3, // Unity Games category
                'images' => [
                    'https://images.unsplash.com/photo-1493711662062-fa541adb3fc8?w=800&h=600&fit=crop',
                ],
                'downloadable_links' => [
                    [
                        'title' => '3D Platformer Demo - Unity Project',
                        'file' => '3d-platformer-demo.zip',
                        'downloads' => 0,
                    ]
                ]
            ],
        ];

        foreach ($sourceGames as $gameData) {
            // Check if product already exists
            $existingProduct = Product::where('sku', $gameData['sku'])->first();
            
            if ($existingProduct) {
                echo "Product {$gameData['sku']} already exists, skipping...\n";
                continue;
            }

            // Create downloadable product
            $product = Product::create([
                'sku' => $gameData['sku'],
                'type' => 'downloadable',
                'attribute_family_id' => 1,
                'parent_id' => null,
            ]);

            // Add to category
            ProductCategory::create([
                'product_id' => $product->id,
                'category_id' => $gameData['category_id'],
            ]);

            // Create attribute values
            $attributeValues = [
                ['attribute_id' => 1, 'text_value' => $gameData['sku']], // sku
                ['attribute_id' => 2, 'text_value' => $gameData['name']], // name
                ['attribute_id' => 9, 'text_value' => $gameData['short_description']], // short_description
                ['attribute_id' => 10, 'text_value' => $gameData['description']], // description
                ['attribute_id' => 11, 'float_value' => $gameData['price']], // price
                ['attribute_id' => 8, 'boolean_value' => 1], // status (active)
                ['attribute_id' => 7, 'boolean_value' => 1], // visible_individually
            ];

            foreach ($attributeValues as $attrValue) {
                ProductAttributeValue::create([
                    'product_id' => $product->id,
                    'attribute_id' => $attrValue['attribute_id'],
                    'channel' => $channel->code,
                    'locale' => 'vi',
                    'text_value' => $attrValue['text_value'] ?? null,
                    'boolean_value' => $attrValue['boolean_value'] ?? null,
                    'integer_value' => $attrValue['integer_value'] ?? null,
                    'float_value' => $attrValue['float_value'] ?? null,
                ]);
            }

            // Create downloadable links
            foreach ($gameData['downloadable_links'] as $index => $linkData) {
                ProductDownloadableLink::create([
                    'product_id' => $product->id,
                    'url' => null,
                    'file' => $linkData['file'],
                    'file_name' => $linkData['file'],
                    'type' => 'file',
                    'downloads' => $linkData['downloads'],
                    'sort_order' => $index + 1,
                    'title' => $linkData['title'],
                ]);
            }

            echo "Created source game product: {$gameData['name']}\n";
        }

        echo "Source Game products seeder completed successfully!\n";
    }
}
