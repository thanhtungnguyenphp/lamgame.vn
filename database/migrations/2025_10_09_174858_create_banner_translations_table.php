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
        Schema::create('banner_translations', function (Blueprint $table) {
            $table->id();
            
            // Foreign keys
            $table->unsignedBigInteger('banner_id');
            $table->string('locale', 10);
            
            // Translatable content
            $table->text('title')->nullable();
            $table->longText('content')->nullable();
            $table->text('image_alt')->nullable();
            
            // SEO fields
            $table->text('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            
            // Additional settings
            $table->json('settings')->nullable();
            
            $table->timestamps();
            
            // Constraints
            $table->unique(['banner_id', 'locale']);
            $table->foreign('banner_id')->references('id')->on('banners')->onDelete('cascade');
            
            // Index for better performance
            $table->index(['banner_id', 'locale']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banner_translations');
    }
};
