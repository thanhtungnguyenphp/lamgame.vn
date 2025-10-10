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
        Schema::create('banner_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('banner_id');
            $table->string('locale', 10)->index();
            
            // Translatable fields
            $table->text('title')->nullable();
            $table->longText('content')->nullable();
            $table->text('image_alt')->nullable();
            $table->text('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->json('settings')->nullable(); // Locale-specific settings
            
            $table->timestamps();
            
            // Foreign key and unique constraints
            $table->foreign('banner_id')->references('id')->on('banners')->onDelete('cascade');
            $table->unique(['banner_id', 'locale']);
            
            // Indexes for performance
            $table->index(['locale', 'banner_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('banner_translations');
    }
};