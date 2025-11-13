<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Webkul\Category\Models\Category;
use Webkul\Core\Models\Locale;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Đảm bảo category "Việc Làm" tồn tại
        $this->ensureJobCategoryExists();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Không xóa category vì có thể có jobs đang sử dụng
    }

    /**
     * Đảm bảo job category "Việc Làm" tồn tại
     */
    private function ensureJobCategoryExists(): void
    {
        $defaultLocale = Locale::where('code', 'vi')->first() ?? Locale::first();
        
        if (!$defaultLocale) {
            return; // Không có locale nào
        }

        // Kiểm tra xem category đã tồn tại chưa
        $jobCategory = Category::whereHas('translations', function ($query) {
            $query->where('slug', 'viec-lam');
        })->first();

        if (!$jobCategory) {
            // Tạo category "Việc Làm"
            $jobCategory = Category::create([
                'position' => 1,
                'status' => 1,
                'display_mode' => 'products_and_description',
                'is_filterable' => 1,
                'parent_id' => 1, // Root category
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Tạo translation
            $jobCategory->translations()->create([
                'locale' => $defaultLocale->code,
                'name' => 'Việc Làm',
                'slug' => 'viec-lam',
                'description' => 'Danh mục việc làm - Tìm kiếm và ứng tuyển các vị trí việc làm hấp dẫn',
                'meta_title' => 'Việc Làm - Tuyển Dụng',
                'meta_description' => 'Khám phá hàng ngàn cơ hội việc làm từ các công ty hàng đầu. Tìm kiếm và ứng tuyển việc làm phù hợp với bạn.',
                'meta_keywords' => 'việc làm, tuyển dụng, job, career, tìm việc, ứng tuyển',
            ]);

            echo "✅ Created job category 'Việc Làm' with ID: {$jobCategory->id}\n";
        } else {
            echo "✅ Job category 'Việc Làm' already exists with ID: {$jobCategory->id}\n";
        }
    }
};
