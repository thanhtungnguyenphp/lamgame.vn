<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Create main menu if not exists
        $menu = DB::table('menus')->where('name', 'Main Menu')->first();
        if (!$menu) {
            $menuId = DB::table('menus')->insertGetId([
                'name' => 'Main Menu',
                'channel_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $menuId = $menu->id;
            // Clear existing menu items
            DB::table('menu_items')->where('menu_id', $menuId)->delete();
        }

        // Insert menu items
        $menuItems = [
            [
                'menu_id' => $menuId,
                'title' => 'Trang chủ',
                'url' => '/',
                'sort_order' => 1,
                'target' => '_self',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'menu_id' => $menuId,
                'title' => 'Source Game',
                'url' => '/source-game',
                'sort_order' => 2,
                'target' => '_self',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'menu_id' => $menuId,
                'title' => 'Forum',
                'url' => '/forum',
                'sort_order' => 3,
                'target' => '_self',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'menu_id' => $menuId,
                'title' => 'Blog',
                'url' => '/blog',
                'sort_order' => 4,
                'target' => '_self',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'menu_id' => $menuId,
                'title' => 'Việc làm',
                'url' => '/viec-lam-game',
                'sort_order' => 5,
                'target' => '_self',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'menu_id' => $menuId,
                'title' => 'Giới thiệu',
                'url' => '/gioi-thieu',
                'sort_order' => 6,
                'target' => '_self',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'menu_id' => $menuId,
                'title' => 'Liên hệ',
                'url' => '/lien-he',
                'sort_order' => 7,
                'target' => '_self',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('menu_items')->insert($menuItems);
        
        $this->command->info('Main menu and menu items created successfully!');
    }
}
