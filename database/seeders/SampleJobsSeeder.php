<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class SampleJobsSeeder extends Seeder
{
    /**
     * Run the database seeds for sample job data.
     */
    public function run()
    {
        $this->seedJobProducts();
        $this->command->info('Sample jobs data seeded successfully!');
    }

    /**
     * Seed job products
     */
    private function seedJobProducts()
    {
        // First check if job categories exist, if not create them
        $jobCategoryId = $this->createJobCategory();

        $jobs = [
            [
                'sku' => 'JOB_VNG_UNITY_001',
                'name' => 'Unity Developer - VNG Corporation',
                'short_description' => 'Tuyển Unity Developer kinh nghiệm 2+ năm. Lương: 25-40 triệu VND. Địa điểm: TP.HCM. Yêu cầu: C#, Unity 2022+, Git.',
                'description' => 'VNG Corporation tuyển Unity Developer có kinh nghiệm để tham gia phát triển các game mobile hàng đầu. Yêu cầu thành thạo C#, Unity Engine, và có kinh nghiệm làm việc với team đông người. Mức lương hấp dẫn từ 25-40 triệu VND cùng các benefits đặc biệt.',
                'price' => 32500000, // 32.5 triệu VND
                'type' => 'simple',
                'status' => 1,
                'visible_individually' => 1,
            ],
            [
                'sku' => 'JOB_GAMELOFT_3D_002',
                'name' => '3D Artist - Gameloft Vietnam',
                'short_description' => '3D Artist cho game AAA mobile. Lương: 20-30 triệu VND. Địa điểm: Hà Nội. Yêu cầu: Maya, 3ds Max, Substance.',
                'description' => 'Gameloft Vietnam cần 3D Artist tài năng để tạo ra những tài sản 3D chất lượng cao cho game mobile. Ưu tiên ứng viên có portfolio mạnh về character modeling và environment art. Môi trường làm việc quốc tế với cơ hội thăng tiến cao.',
                'price' => 25000000, // 25 triệu VND
                'type' => 'simple',
                'status' => 1,
                'visible_individually' => 1,
            ],
            [
                'sku' => 'JOB_APPOTA_BACKEND_003',
                'name' => 'Game Backend Developer - Appota',
                'short_description' => 'Backend Developer cho game server. Lương: 30-45 triệu VND. Remote/TP.HCM. Yêu cầu: Node.js, MySQL, Redis.',
                'description' => 'Appota tuyển Backend Developer để phát triển hệ thống server cho game online. Cần có kinh nghiệm với microservices, database optimization, và real-time communication. Làm việc remote hoặc tại office TP.HCM.',
                'price' => 37500000, // 37.5 triệu VND
                'type' => 'simple',
                'status' => 1,
                'visible_individually' => 1,
            ],
            [
                'sku' => 'JOB_SOLITAIRE_UI_004',
                'name' => 'UI/UX Designer - Solitaire Game Studio',
                'short_description' => 'UI/UX Designer game mobile. Lương: 18-25 triệu VND. Địa điểm: TP.HCM. Yêu cầu: Figma, Adobe Creative, portfolio game UI.',
                'description' => 'Solitaire Game Studio tìm UI/UX Designer creative để thiết kế giao diện game mobile hấp dẫn. Ưu tiên ứng viên hiểu về game user experience và có kinh nghiệm làm game casual. Studio tập trung vào game puzzle và casual.',
                'price' => 21500000, // 21.5 triệu VND
                'type' => 'simple',
                'status' => 1,
                'visible_individually' => 1,
            ],
            [
                'sku' => 'JOB_INDIE_PROGRAMMER_005',
                'name' => 'Indie Game Programmer - Remote',
                'short_description' => 'Lập trình viên game indie. Lương: 15-25 triệu VND. Remote. Yêu cầu: Unity/Unreal, passion cho indie games.',
                'description' => 'Studio indie đang tìm programmer đam mê để cùng phát triển game độc lập sáng tạo. Cơ hội học hỏi và phát triển skills trong môi trường startup năng động. Làm việc remote 100% với team quốc tế.',
                'price' => 20000000, // 20 triệu VND
                'type' => 'simple',
                'status' => 1,
                'visible_individually' => 1,
            ],
            [
                'sku' => 'JOB_SKYMAVIS_BLOCKCHAIN_006',
                'name' => 'Blockchain Game Developer - Sky Mavis',
                'short_description' => 'Blockchain Game Developer. Lương: 35-50 triệu VND. TP.HCM. Yêu cầu: Solidity, Web3, Unity/Unreal.',
                'description' => 'Sky Mavis (tạo ra Axie Infinity) tuyển Blockchain Game Developer để phát triển thế hệ game blockchain mới. Yêu cầu kinh nghiệm với Solidity, Web3 technologies và game development. Mức lương cực kỳ cạnh tranh.',
                'price' => 42500000, // 42.5 triệu VND
                'type' => 'simple',
                'status' => 1,
                'visible_individually' => 1,
            ]
        ];

        foreach ($jobs as $jobData) {
            // Check if job already exists
            $existingJob = DB::table('products')->where('sku', $jobData['sku'])->first();
            if ($existingJob) {
                continue; // Skip if already exists
            }

            // Create product
            $productId = DB::table('products')->insertGetId([
                'sku' => $jobData['sku'],
                'type' => $jobData['type'],
                'attribute_family_id' => 1, // Default attribute family
                'created_at' => Carbon::now()->subDays(rand(1, 14)),
                'updated_at' => Carbon::now(),
            ]);

            // Create product flat (localized data)
            DB::table('product_flat')->insert([
                'product_id' => $productId,
                'sku' => $jobData['sku'],
                'name' => $jobData['name'],
                'short_description' => $jobData['short_description'],
                'description' => $jobData['description'],
                'price' => $jobData['price'],
                'status' => $jobData['status'],
                'visible_individually' => $jobData['visible_individually'],
                'locale' => 'vi',
                'channel' => 'default',
                'url_key' => Str::slug($jobData['name']),
                'created_at' => Carbon::now()->subDays(rand(1, 14)),
                'updated_at' => Carbon::now(),
            ]);

            // Associate with job category
            DB::table('product_categories')->insert([
                'product_id' => $productId,
                'category_id' => $jobCategoryId,
            ]);
        }
    }

    /**
     * Create job category if not exists
     */
    private function createJobCategory()
    {
        // Check if category translation exists
        $categoryTranslation = DB::table('category_translations')
            ->where('slug', 'viec-lam-game')
            ->where('locale', 'vi')
            ->first();
        
        if ($categoryTranslation) {
            return $categoryTranslation->category_id;
        }

        // Find the next available _lft and _rgt values
        $maxRgt = DB::table('categories')->max('_rgt') ?? 0;

        // Create root category
        $rootCategoryId = DB::table('categories')->insertGetId([
            'position' => 1,
            'status' => 1,
            'display_mode' => 'products_only',
            '_lft' => $maxRgt + 1,
            '_rgt' => $maxRgt + 2,
            'parent_id' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // Create category translation
        DB::table('category_translations')->insert([
            'category_id' => $rootCategoryId,
            'locale' => 'vi',
            'name' => 'Việc Làm Game Dev',
            'slug' => 'viec-lam-game',
            'description' => 'Tất cả việc làm trong ngành game development',
            'meta_title' => 'Việc Làm Game Developer',
            'meta_description' => 'Tìm việc làm game developer tại Việt Nam',
        ]);

        return $rootCategoryId;
    }
}