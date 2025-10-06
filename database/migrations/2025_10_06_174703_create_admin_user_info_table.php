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
            $table->id();
            
            // Foreign key to admins table
            $table->unsignedInteger('admin_id')->unique();
            $table->foreign('admin_id')->references('id')->on('admins')->onDelete('cascade');
            
            // Personal Information
            $table->date('date_of_birth')->nullable()->comment('Ngày sinh');
            $table->enum('gender', ['male', 'female', 'other'])->nullable()->comment('Giới tính');
            $table->string('phone', 20)->nullable()->comment('Số điện thoại');
            
            // Address Information  
            $table->text('address')->nullable()->comment('Địa chỉ chi tiết');
            $table->string('city', 100)->nullable()->comment('Thành phố');
            $table->string('state', 100)->nullable()->comment('Tỉnh/Bang');
            $table->string('country', 100)->default('Vietnam')->comment('Quốc gia');
            $table->string('postal_code', 20)->nullable()->comment('Mã bưu chính');
            
            // Extended Profile Information
            $table->text('bio')->nullable()->comment('Tiểu sử/Mô tả bản thân');
            $table->string('website')->nullable()->comment('Website cá nhân');
            $table->string('job_title')->nullable()->comment('Chức danh công việc');
            $table->string('company')->nullable()->comment('Công ty');
            
            // Social Links (JSON)
            $table->json('social_links')->nullable()->comment('Liên kết mạng xã hội (Facebook, LinkedIn, etc.)');
            
            // User Preferences (JSON)
            $table->json('preferences')->nullable()->comment('Tùy chọn người dùng (ngôn ngữ, timezone, notifications)');
            
            // Emergency Contact (JSON)
            $table->json('emergency_contact')->nullable()->comment('Thông tin liên hệ khẩn cấp');
            
            // Additional JSON for future extensibility
            $table->json('custom_fields')->nullable()->comment('Các trường tùy chỉnh có thể mở rộng');
            
            // Tracking fields
            $table->timestamp('profile_completed_at')->nullable()->comment('Thời điểm hoàn thành profile');
            $table->boolean('is_public')->default(false)->comment('Profile có public không');
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('admin_id');
            $table->index('phone');
            $table->index('city');
            $table->index('country');
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
