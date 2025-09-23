<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BlogCategoriesTagsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing data
        DB::table('blog_categories')->truncate();
        DB::table('blog_tags')->truncate();
        
        // Blog Categories
        $categories = [
            [
                'name' => 'Unity Development',
                'slug' => 'unity-development',
                'description' => 'Tất cả về lập trình game với Unity Engine',
                'image' => '',
                'status' => 1,
                'parent_id' => 0,
                'locale' => 'vi',
                'meta_title' => 'Unity Game Development - Hướng dẫn và Tips',
                'meta_description' => 'Học lập trình game Unity từ cơ bản đến nâng cao với các bài viết chi tiết và thực tế.',
                'meta_keywords' => 'Unity, Unity 3D, Unity 2D, C#, Game Development, Unity Tutorial',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Unreal Engine',
                'slug' => 'unreal-engine',
                'description' => 'Phát triển game với Unreal Engine',
                'meta_title' => 'Unreal Engine Game Development',
                'meta_description' => 'Hướng dẫn phát triển game 3D chất lượng cao với Unreal Engine và Blueprint.',
                'meta_keywords' => 'Unreal Engine, UE4, UE5, Blueprint, C++, 3D Game',
                'status' => 1,
                'sort_order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Game Design',
                'slug' => 'game-design',
                'description' => 'Thiết kế game và lý thuyết game development',
                'meta_title' => 'Game Design - Thiết kế Game Chuyên nghiệp',
                'meta_description' => 'Học cách thiết kế game từ ý tưởng đến gameplay, UI/UX và player experience.',
                'meta_keywords' => 'Game Design, Gameplay, UI Design, UX, Player Experience',
                'status' => 1,
                'sort_order' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Programming',
                'slug' => 'programming',
                'description' => 'Lập trình cho game development',
                'meta_title' => 'Game Programming - Lập trình Game',
                'meta_description' => 'Học các ngôn ngữ lập trình phổ biến trong game development như C#, C++, JavaScript.',
                'meta_keywords' => 'C#, C++, JavaScript, Python, Game Programming',
                'status' => 1,
                'sort_order' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Mobile Game',
                'slug' => 'mobile-game',
                'description' => 'Phát triển game mobile cho Android và iOS',
                'meta_title' => 'Mobile Game Development - Game Di động',
                'meta_description' => 'Hướng dẫn phát triển game mobile hiệu quả cho Android và iOS.',
                'meta_keywords' => 'Mobile Game, Android, iOS, Unity Mobile, Performance',
                'status' => 1,
                'sort_order' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => '2D Game',
                'slug' => '2d-game',
                'description' => 'Phát triển game 2D',
                'meta_title' => '2D Game Development - Game 2D',
                'meta_description' => 'Tạo game 2D với các công cụ và kỹ thuật hiện đại.',
                'meta_keywords' => '2D Game, Sprite, Animation, Pixel Art, 2D Physics',
                'status' => 1,
                'sort_order' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => '3D Game',
                'slug' => '3d-game',
                'description' => 'Phát triển game 3D chuyên nghiệp',
                'meta_title' => '3D Game Development - Game 3D',
                'meta_description' => 'Học cách tạo game 3D với đồ họa đẹp và gameplay hấp dẫn.',
                'meta_keywords' => '3D Game, 3D Modeling, Rendering, Lighting, Shaders',
                'status' => 1,
                'sort_order' => 7,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'VR/AR Game',
                'slug' => 'vr-ar-game',
                'description' => 'Phát triển game thực tế ảo và thực tế tăng cường',
                'meta_title' => 'VR/AR Game Development - Game VR AR',
                'meta_description' => 'Khám phá thế giới phát triển game VR và AR với các công nghệ mới nhất.',
                'meta_keywords' => 'VR, AR, Virtual Reality, Augmented Reality, Oculus, HoloLens',
                'status' => 1,
                'sort_order' => 8,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Game Art',
                'slug' => 'game-art',
                'description' => 'Nghệ thuật và đồ họa trong game',
                'meta_title' => 'Game Art - Nghệ thuật Game',
                'meta_description' => 'Học cách tạo art assets, animation và hiệu ứng visual cho game.',
                'meta_keywords' => 'Game Art, 3D Art, Concept Art, Animation, VFX, Texturing',
                'status' => 1,
                'sort_order' => 9,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Game Industry',
                'slug' => 'game-industry',
                'description' => 'Tin tức và xu hướng ngành game',
                'meta_title' => 'Game Industry - Ngành Công nghiệp Game',
                'meta_description' => 'Cập nhật tin tức, xu hướng và cơ hội nghề nghiệp trong ngành game.',
                'meta_keywords' => 'Game Industry, Game Jobs, Game Market, Game Trends',
                'status' => 1,
                'sort_order' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Insert categories
        foreach ($categories as $category) {
            DB::table('blog_categories')->insert($category);
        }

        // Blog Tags
        $tags = [
            // Unity tags
            ['name' => 'Unity', 'slug' => 'unity', 'status' => 1],
            ['name' => 'Unity 3D', 'slug' => 'unity-3d', 'status' => 1],
            ['name' => 'Unity 2D', 'slug' => 'unity-2d', 'status' => 1],
            ['name' => 'C#', 'slug' => 'csharp', 'status' => 1],
            ['name' => 'MonoBehaviour', 'slug' => 'monobehaviour', 'status' => 1],
            ['name' => 'Unity Physics', 'slug' => 'unity-physics', 'status' => 1],
            ['name' => 'Unity UI', 'slug' => 'unity-ui', 'status' => 1],
            ['name' => 'Unity Animation', 'slug' => 'unity-animation', 'status' => 1],
            ['name' => 'Unity Shader', 'slug' => 'unity-shader', 'status' => 1],
            ['name' => 'Unity Networking', 'slug' => 'unity-networking', 'status' => 1],

            // Unreal Engine tags
            ['name' => 'Unreal Engine', 'slug' => 'unreal-engine', 'status' => 1],
            ['name' => 'UE4', 'slug' => 'ue4', 'status' => 1],
            ['name' => 'UE5', 'slug' => 'ue5', 'status' => 1],
            ['name' => 'Blueprint', 'slug' => 'blueprint', 'status' => 1],
            ['name' => 'C++', 'slug' => 'cpp', 'status' => 1],
            ['name' => 'Unreal Blueprint', 'slug' => 'unreal-blueprint', 'status' => 1],

            // Programming tags
            ['name' => 'JavaScript', 'slug' => 'javascript', 'status' => 1],
            ['name' => 'Python', 'slug' => 'python', 'status' => 1],
            ['name' => 'Lua', 'slug' => 'lua', 'status' => 1],
            ['name' => 'HLSL', 'slug' => 'hlsl', 'status' => 1],
            ['name' => 'GLSL', 'slug' => 'glsl', 'status' => 1],

            // Game Design tags
            ['name' => 'Game Design', 'slug' => 'game-design', 'status' => 1],
            ['name' => 'Gameplay', 'slug' => 'gameplay', 'status' => 1],
            ['name' => 'Level Design', 'slug' => 'level-design', 'status' => 1],
            ['name' => 'UI Design', 'slug' => 'ui-design', 'status' => 1],
            ['name' => 'UX Design', 'slug' => 'ux-design', 'status' => 1],
            ['name' => 'Player Experience', 'slug' => 'player-experience', 'status' => 1],

            // Platform tags
            ['name' => 'Mobile Game', 'slug' => 'mobile-game', 'status' => 1],
            ['name' => 'Android', 'slug' => 'android', 'status' => 1],
            ['name' => 'iOS', 'slug' => 'ios', 'status' => 1],
            ['name' => 'PC Game', 'slug' => 'pc-game', 'status' => 1],
            ['name' => 'Console Game', 'slug' => 'console-game', 'status' => 1],
            ['name' => 'Web Game', 'slug' => 'web-game', 'status' => 1],

            // Genre tags
            ['name' => 'RPG', 'slug' => 'rpg', 'status' => 1],
            ['name' => 'FPS', 'slug' => 'fps', 'status' => 1],
            ['name' => 'Strategy', 'slug' => 'strategy', 'status' => 1],
            ['name' => 'Puzzle', 'slug' => 'puzzle', 'status' => 1],
            ['name' => 'Racing', 'slug' => 'racing', 'status' => 1],
            ['name' => 'Simulation', 'slug' => 'simulation', 'status' => 1],
            ['name' => 'Action', 'slug' => 'action', 'status' => 1],
            ['name' => 'Adventure', 'slug' => 'adventure', 'status' => 1],
            ['name' => 'Platformer', 'slug' => 'platformer', 'status' => 1],

            // Technical tags
            ['name' => 'Performance', 'slug' => 'performance', 'status' => 1],
            ['name' => 'Optimization', 'slug' => 'optimization', 'status' => 1],
            ['name' => 'Debug', 'slug' => 'debug', 'status' => 1],
            ['name' => 'AI', 'slug' => 'ai', 'status' => 1],
            ['name' => 'Machine Learning', 'slug' => 'machine-learning', 'status' => 1],
            ['name' => 'Physics', 'slug' => 'physics', 'status' => 1],
            ['name' => 'Networking', 'slug' => 'networking', 'status' => 1],
            ['name' => 'Multiplayer', 'slug' => 'multiplayer', 'status' => 1],
            ['name' => 'Audio', 'slug' => 'audio', 'status' => 1],
            ['name' => 'Sound Design', 'slug' => 'sound-design', 'status' => 1],

            // Art tags
            ['name' => '3D Modeling', 'slug' => '3d-modeling', 'status' => 1],
            ['name' => '2D Art', 'slug' => '2d-art', 'status' => 1],
            ['name' => 'Concept Art', 'slug' => 'concept-art', 'status' => 1],
            ['name' => 'Animation', 'slug' => 'animation', 'status' => 1],
            ['name' => 'VFX', 'slug' => 'vfx', 'status' => 1],
            ['name' => 'Shader', 'slug' => 'shader', 'status' => 1],
            ['name' => 'Lighting', 'slug' => 'lighting', 'status' => 1],
            ['name' => 'Texturing', 'slug' => 'texturing', 'status' => 1],
            ['name' => 'Pixel Art', 'slug' => 'pixel-art', 'status' => 1],

            // VR/AR tags
            ['name' => 'VR', 'slug' => 'vr', 'status' => 1],
            ['name' => 'AR', 'slug' => 'ar', 'status' => 1],
            ['name' => 'Virtual Reality', 'slug' => 'virtual-reality', 'status' => 1],
            ['name' => 'Augmented Reality', 'slug' => 'augmented-reality', 'status' => 1],
            ['name' => 'Oculus', 'slug' => 'oculus', 'status' => 1],
            ['name' => 'HTC Vive', 'slug' => 'htc-vive', 'status' => 1],

            // Industry tags
            ['name' => 'Game Jobs', 'slug' => 'game-jobs', 'status' => 1],
            ['name' => 'Career', 'slug' => 'career', 'status' => 1],
            ['name' => 'Indie Game', 'slug' => 'indie-game', 'status' => 1],
            ['name' => 'Game Marketing', 'slug' => 'game-marketing', 'status' => 1],
            ['name' => 'Game Publishing', 'slug' => 'game-publishing', 'status' => 1],
            ['name' => 'Steam', 'slug' => 'steam', 'status' => 1],
            ['name' => 'Google Play', 'slug' => 'google-play', 'status' => 1],
            ['name' => 'App Store', 'slug' => 'app-store', 'status' => 1],

            // Tutorial tags
            ['name' => 'Tutorial', 'slug' => 'tutorial', 'status' => 1],
            ['name' => 'Beginner', 'slug' => 'beginner', 'status' => 1],
            ['name' => 'Advanced', 'slug' => 'advanced', 'status' => 1],
            ['name' => 'Tips', 'slug' => 'tips', 'status' => 1],
            ['name' => 'Best Practices', 'slug' => 'best-practices', 'status' => 1],
            ['name' => 'How To', 'slug' => 'how-to', 'status' => 1],
        ];

        // Add timestamps to tags
        $timestamp = now();
        foreach ($tags as &$tag) {
            $tag['created_at'] = $timestamp;
            $tag['updated_at'] = $timestamp;
            $tag['meta_title'] = $tag['name'] . ' - LamGame Blog';
            $tag['meta_description'] = 'Tìm hiểu về ' . $tag['name'] . ' trong game development.';
            $tag['meta_keywords'] = $tag['name'] . ', game development, lập trình game';
        }

        // Insert tags
        foreach ($tags as $tag) {
            DB::table('blog_tags')->insert($tag);
        }

        $this->command->info('Blog categories and tags seeded successfully!');
    }
}
