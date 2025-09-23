<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ForumCategory;
use Illuminate\Support\Str;

class ForumCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Thảo luận',
                'slug' => 'thao-luan',
                'description' => 'Thảo luận về game development, kỹ thuật và kinh nghiệm',
                'icon' => '💭',
                'color' => '#667eea',
                'sort_order' => 1,
                'is_active' => true,
                'is_featured' => false,
            ],
            [
                'name' => 'Chia sẻ ý tưởng',
                'slug' => 'chia-se-y-tuong',
                'description' => 'Chia sẻ ý tưởng game mới và tìm team phát triển',
                'icon' => '💡',
                'color' => '#ffd700',
                'sort_order' => 2,
                'is_active' => true,
                'is_featured' => true,
            ],
            [
                'name' => 'Tìm team',
                'slug' => 'tim-team',
                'description' => 'Tìm kiếm đồng đội cho dự án game của bạn',
                'icon' => '👥',
                'color' => '#ff6b35',
                'sort_order' => 3,
                'is_active' => true,
                'is_featured' => false,
            ],
            [
                'name' => 'Review khóa học',
                'slug' => 'review-khoa-hoc',
                'description' => 'Đánh giá và review các khóa học game development',
                'icon' => '📚',
                'color' => '#10b981',
                'sort_order' => 4,
                'is_active' => true,
                'is_featured' => false,
            ],
            [
                'name' => 'Hỗ trợ kỹ thuật',
                'slug' => 'ho-tro-ky-thuat',
                'description' => 'Hỏi đáp và hỗ trợ kỹ thuật về game development',
                'icon' => '🛠️',
                'color' => '#8b5cf6',
                'sort_order' => 5,
                'is_active' => true,
                'is_featured' => false,
            ],
            [
                'name' => 'Showcase dự án',
                'slug' => 'showcase',
                'description' => 'Khoe game và dự án của bạn, nhận feedback từ cộng đồng',
                'icon' => '🎯',
                'color' => '#f59e0b',
                'sort_order' => 6,
                'is_active' => true,
                'is_featured' => false,
            ],
            [
                'name' => 'Tuyển dụng',
                'slug' => 'tuyen-dung',
                'description' => 'Thông tin tuyển dụng và cơ hội việc làm trong ngành game',
                'icon' => '💼',
                'color' => '#06b6d4',
                'sort_order' => 7,
                'is_active' => true,
                'is_featured' => false,
            ],
            [
                'name' => 'Game Jam',
                'slug' => 'game-jam',
                'description' => 'Thông tin về các cuộc thi Game Jam và sự kiện',
                'icon' => '🏆',
                'color' => '#ec4899',
                'sort_order' => 8,
                'is_active' => true,
                'is_featured' => false,
            ],
        ];

        foreach ($categories as $categoryData) {
            ForumCategory::create($categoryData);
        }
    }
}
