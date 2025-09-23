<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ForumTag;
use Illuminate\Support\Str;

class ForumTagsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = [
            // Game Engines
            ['name' => 'Unity', 'description' => 'Unity game engine'],
            ['name' => 'Unreal Engine', 'description' => 'Unreal Engine game development'],
            ['name' => 'Godot', 'description' => 'Godot game engine'],
            ['name' => 'GameMaker', 'description' => 'GameMaker Studio'],
            
            // Programming Languages
            ['name' => 'C#', 'description' => 'C# programming language'],
            ['name' => 'C++', 'description' => 'C++ programming language', 'slug' => 'cpp'],
            ['name' => 'JavaScript', 'description' => 'JavaScript for game development'],
            ['name' => 'Python', 'description' => 'Python game development'],
            ['name' => 'GDScript', 'description' => 'Godot scripting language'],
            ['name' => 'Blueprint', 'description' => 'Unreal Engine visual scripting'],
            
            // Game Types
            ['name' => '2D Games', 'description' => '2D game development'],
            ['name' => '3D Games', 'description' => '3D game development'],
            ['name' => 'Mobile Games', 'description' => 'Mobile game development'],
            ['name' => 'Web Games', 'description' => 'Browser game development'],
            ['name' => 'VR/AR', 'description' => 'Virtual and Augmented Reality games'],
            
            // Game Genres
            ['name' => 'RPG', 'description' => 'Role-playing games'],
            ['name' => 'Action', 'description' => 'Action games'],
            ['name' => 'Strategy', 'description' => 'Strategy games'],
            ['name' => 'Puzzle', 'description' => 'Puzzle games'],
            ['name' => 'Platformer', 'description' => 'Platform games'],
            ['name' => 'Shooter', 'description' => 'Shooting games'],
            ['name' => 'Racing', 'description' => 'Racing games'],
            ['name' => 'Simulation', 'description' => 'Simulation games'],
            
            // Technical Topics
            ['name' => 'AI', 'description' => 'Artificial Intelligence in games'],
            ['name' => 'Physics', 'description' => 'Game physics'],
            ['name' => 'Graphics', 'description' => 'Game graphics and rendering'],
            ['name' => 'Audio', 'description' => 'Game audio and sound'],
            ['name' => 'UI/UX', 'description' => 'User interface and experience'],
            ['name' => 'Optimization', 'description' => 'Performance optimization'],
            ['name' => 'Networking', 'description' => 'Multiplayer and networking'],
            
            // Development Process
            ['name' => 'Game Design', 'description' => 'Game design concepts'],
            ['name' => 'Art', 'description' => 'Game art and assets'],
            ['name' => 'Animation', 'description' => 'Game animation'],
            ['name' => 'Testing', 'description' => 'Game testing and QA'],
            ['name' => 'Publishing', 'description' => 'Game publishing and marketing'],
            ['name' => 'Monetization', 'description' => 'Game monetization strategies'],
            
            // Platforms
            ['name' => 'Steam', 'description' => 'Steam platform'],
            ['name' => 'Mobile', 'description' => 'Mobile platforms (iOS/Android)'],
            ['name' => 'Console', 'description' => 'Console gaming platforms'],
            ['name' => 'PC', 'description' => 'PC gaming'],
            
            // Learning
            ['name' => 'Beginner', 'description' => 'Beginner-friendly content', 'is_featured' => true],
            ['name' => 'Tutorial', 'description' => 'Tutorials and guides', 'is_featured' => true],
            ['name' => 'Tips', 'description' => 'Tips and tricks', 'is_featured' => true],
            ['name' => 'Resource', 'description' => 'Useful resources'],
            ['name' => 'Tools', 'description' => 'Development tools'],
        ];

        foreach ($tags as $tagData) {
            $tag = [
                'name' => $tagData['name'],
                'slug' => $tagData['slug'] ?? Str::slug($tagData['name']),
                'description' => $tagData['description'] ?? null,
                'is_featured' => $tagData['is_featured'] ?? false,
                'color' => $this->getRandomColor(),
                'posts_count' => 0,
            ];

            ForumTag::create($tag);
        }
    }

    /**
     * Get a random color for tags.
     */
    private function getRandomColor(): string
    {
        $colors = [
            '#667eea', '#764ba2', '#f093fb', '#f5576c',
            '#4facfe', '#00f2fe', '#43e97b', '#38f9d7',
            '#ffecd2', '#fcb69f', '#a8edea', '#fed6e3',
            '#fad0c4', '#ffd1ff', '#c2e9fb', '#a1c4fd',
        ];

        return $colors[array_rand($colors)];
    }
}
