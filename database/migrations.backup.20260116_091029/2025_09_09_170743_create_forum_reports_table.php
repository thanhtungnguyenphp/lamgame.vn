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
        Schema::create('forum_reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('reporter_id');
            $table->foreign('reporter_id')->references('id')->on('customers')->onDelete('cascade');
            $table->string('reportable_type'); // 'post' or 'comment'
            $table->unsignedBigInteger('reportable_id');
            $table->string('reason');
            $table->text('description')->nullable();
            $table->enum('status', ['pending', 'reviewed', 'resolved', 'dismissed'])->default('pending');
            $table->unsignedInteger('reviewed_by')->nullable();
            $table->foreign('reviewed_by')->references('id')->on('admins')->onDelete('set null');
            $table->text('admin_notes')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->string('ip_address')->nullable();
            $table->timestamps();
            
            // Index for polymorphic relationship
            $table->index(['reportable_type', 'reportable_id']);
            $table->index(['status', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('forum_reports');
    }
};
