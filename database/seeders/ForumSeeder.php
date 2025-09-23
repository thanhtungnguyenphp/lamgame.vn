<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ForumSeeder extends Seeder
{
    /**
     * Run the forum database seeds in correct order.
     */
    public function run(): void
    {
        $this->call([
            ForumCategoriesSeeder::class,
            ForumTagsSeeder::class,
            ForumPostsSeeder::class,
        ]);
    }
}
