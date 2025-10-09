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
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            
            // Basic info
            $table->string('name');
            $table->string('type', 50)->default('image'); // image, html, video
            $table->string('position');
            $table->enum('device_type', ['all', 'desktop', 'tablet', 'mobile'])->default('all');
            
            // Channel & Locale
            $table->unsignedInteger('channel_id')->nullable();
            $table->string('locale', 10)->nullable();
            
            // Scheduling
            $table->datetime('start_date')->nullable();
            $table->datetime('end_date')->nullable();
            
            // Display
            $table->integer('sort_order')->default(0);
            $table->boolean('status')->default(true);
            
            // Content (for non-translatable content)
            $table->text('title')->nullable();
            $table->longText('content')->nullable();
            $table->string('image')->nullable();
            $table->text('image_alt')->nullable();
            $table->string('link')->nullable();
            $table->enum('target', ['_self', '_blank'])->default('_self');
            
            // Metadata
            $table->json('css_classes')->nullable();
            $table->json('attributes')->nullable();
            $table->json('settings')->nullable();
            
            // Analytics
            $table->unsignedInteger('clicks_count')->default(0);
            $table->unsignedInteger('impressions_count')->default(0);
            
            $table->timestamps();
            
            // Indexes for better performance
            $table->index(['position', 'device_type', 'status']);
            $table->index(['channel_id', 'locale']);
            $table->index(['start_date', 'end_date']);
            $table->index('sort_order');
            
            // Foreign key
            $table->foreign('channel_id')->references('id')->on('channels')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};
