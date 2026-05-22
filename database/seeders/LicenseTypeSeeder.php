<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LicenseType;

class LicenseTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            [
                'name' => 'Single',
                'slug' => 'single',
                'description' => 'Sử dụng cho 1 dự án duy nhất. Không được bán lại.',
                'max_projects' => 1,
                'allows_resale' => false,
                'allows_modification' => true,
            ],
            [
                'name' => 'Multi',
                'slug' => 'multi',
                'description' => 'Sử dụng cho tối đa 5 dự án. Không được bán lại.',
                'max_projects' => 5,
                'allows_resale' => false,
                'allows_modification' => true,
            ],
            [
                'name' => 'Extended',
                'slug' => 'extended',
                'description' => 'Sử dụng không giới hạn dự án. Được phép bán lại sản phẩm cuối.',
                'max_projects' => 0,
                'allows_resale' => true,
                'allows_modification' => true,
            ],
        ];

        foreach ($types as $type) {
            LicenseType::updateOrCreate(['slug' => $type['slug']], $type);
        }
    }
}
