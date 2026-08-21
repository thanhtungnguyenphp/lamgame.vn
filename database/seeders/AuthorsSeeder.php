<?php

namespace Database\Seeders;

use App\Models\Author;
use Illuminate\Database\Seeder;

class AuthorsSeeder extends Seeder
{
    public function run(): void
    {
        $authors = [
            [
                'name' => 'LamGame Team',
                'slug' => 'lamgame-team',
                'title' => 'Editorial Team',
                'bio' => 'Đội ngũ biên tập viên LamGame.vn chuyên tổng hợp và sản xuất nội dung về game development, game industry và career cho game developer Việt Nam.',
                'expertise' => ['Game Development', 'Unity', 'Unreal Engine', 'Game Design', 'Industry News'],
                'is_staff' => true,
                'is_verified' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Nguyễn Thanh Tùng',
                'slug' => 'nguyen-thanh-tung',
                'title' => 'Senior Game Developer',
                'bio' => 'Game developer với hơn 8 năm kinh nghiệm. Chuyên về Unity, C#, mobile game development và backend systems. Founder của LamGame.vn.',
                'experience_years' => 8,
                'expertise' => ['Unity', 'C#', 'Mobile Game', 'Backend', 'Game Architecture'],
                'social_links' => [
                    'github' => 'https://github.com/lamgamevn',
                    'linkedin' => 'https://linkedin.com/in/nguyenthanhtung',
                ],
                'is_staff' => true,
                'is_verified' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Tech Writer',
                'slug' => 'tech-writer',
                'title' => 'Technical Content Writer',
                'bio' => 'Chuyên viết technical content về game development, tutorials, và best practices cho game developers.',
                'expertise' => ['Technical Writing', 'Documentation', 'Tutorials'],
                'is_staff' => true,
                'is_verified' => false,
                'is_active' => true,
            ],
        ];

        foreach ($authors as $authorData) {
            Author::updateOrCreate(
                ['slug' => $authorData['slug']],
                $authorData
            );
        }

        $this->command->info('✅ Created ' . count($authors) . ' default authors');
    }
}
