<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('admin_user_info', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('admin_id')->index();
            $table->date('date_of_birth')->nullable()->comment('Ngày sinh');
            $table->enum('gender', ['male', 'female', 'other'])->nullable()->comment('Giới tính');
            $table->string('phone', 20)->nullable()->index()->comment('Số điện thoại');
            $table->text('address')->nullable()->comment('Địa chỉ chi tiết');
            $table->string('city', 100)->nullable()->index()->comment('Thành phố');
            $table->string('state', 100)->nullable()->comment('Tỉnh/Bang');
            $table->string('country', 100)->default('Vietnam')->index()->comment('Quốc gia');
            $table->string('postal_code', 20)->nullable()->comment('Mã bưu chính');
            $table->text('bio')->nullable()->comment('Tiểu sử/Mô tả bản thân');
            $table->string('website')->nullable()->comment('Website cá nhân');
            $table->string('job_title')->nullable()->comment('Chức danh công việc');
            $table->string('company')->nullable()->comment('Công ty');
            $table->json('social_links')->nullable()->comment('Liên kết mạng xã hội (Facebook, LinkedIn, etc.)');
            $table->json('preferences')->nullable()->comment('Tùy chọn người dùng (ngôn ngữ, timezone, notifications)');
            $table->json('emergency_contact')->nullable()->comment('Thông tin liên hệ khẩn cấp');
            $table->json('custom_fields')->nullable()->comment('Các trường tùy chỉnh có thể mở rộng');
            $table->timestamp('profile_completed_at')->nullable()->comment('Thời điểm hoàn thành profile');
            $table->boolean('is_public')->default(false)->comment('Profile có public không');
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['admin_id']);
            $table->index(['is_public', 'deleted_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_user_info');
    }
};
