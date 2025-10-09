<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('name')->index(); // Banner name for admin reference
            $table->string('type')->default('image'); // image, html, video
            $table->string('position')->index(); // homepage_hero, sidebar, footer, custom
            $table->enum('device_type', ['all', 'desktop', 'tablet', 'mobile'])->default('all')->index();
            $table->unsignedBigInteger('channel_id')->nullable()->index();
            $table->string('locale')->nullable()->index(); // vi, en, null for all
            $table->datetime('start_date')->nullable()->index();
            $table->datetime('end_date')->nullable()->index();
            $table->integer('sort_order')->default(0)->index();
            $table->boolean('status')->default(true)->index();
            
            // Content fields
            $table->text('title')->nullable();
            $table->longText('content')->nullable();
            $table->string('image')->nullable();
            $table->text('image_alt')->nullable();
            $table->string('link')->nullable();
            $table->enum('target', ['_self', '_blank'])->default('_self');
            
            // Advanced options
            $table->json('css_classes')->nullable(); // Additional CSS classes
            $table->json('attributes')->nullable(); // Custom HTML attributes
            $table->json('settings')->nullable(); // Additional settings (animation, etc.)
            
            // Analytics & tracking
            $table->integer('clicks_count')->default(0);
            $table->integer('impressions_count')->default(0);
            
            $table->timestamps();
            
            // Foreign key constraints
            $table->foreign('channel_id')->references('id')->on('channels')->onDelete('cascade');
            
            // Composite indexes for performance
            $table->index(['status', 'position', 'device_type']);
            $table->index(['start_date', 'end_date', 'status']);
            $table->index(['channel_id', 'locale', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('banners');
    }
};