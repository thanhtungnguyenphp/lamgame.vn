<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('landing_pages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();

            // Template & Layout
            $table->string('template')->default('general'); // event-countdown, product-launch, mini-game, general

            // Hero Section
            $table->string('hero_title')->nullable();
            $table->string('hero_subtitle')->nullable();
            $table->string('hero_cta_text')->nullable();
            $table->string('hero_cta_url')->nullable();
            $table->string('hero_bg_image')->nullable();
            $table->string('hero_bg_color')->nullable();

            // Content
            $table->longText('description')->nullable();       // TinyMCE body content
            $table->json('sections')->nullable();               // Dynamic content blocks

            // SEO
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->string('og_image')->nullable();

            // Scheduling
            $table->boolean('status')->default(false);
            $table->timestamp('start_at')->nullable();
            $table->timestamp('end_at')->nullable();

            // Tracking
            $table->string('author')->nullable();
            $table->unsignedInteger('author_id')->nullable();
            $table->unsignedBigInteger('views')->default(0);

            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'start_at', 'end_at']);
            $table->index('template');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('landing_pages');
    }
};
