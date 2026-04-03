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
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('type', 50)->default('image');
            $table->string('position');
            $table->enum('device_type', ['all', 'desktop', 'tablet', 'mobile'])->default('all');
            $table->unsignedInteger('channel_id')->nullable();
            $table->string('locale', 10)->nullable();
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->integer('sort_order')->default(0)->index();
            $table->boolean('status')->default(true);
            $table->text('title')->nullable();
            $table->longText('content')->nullable();
            $table->string('image')->nullable();
            $table->text('image_alt')->nullable();
            $table->string('link')->nullable();
            $table->enum('target', ['_self', '_blank'])->default('_self');
            $table->json('css_classes')->nullable();
            $table->json('attributes')->nullable();
            $table->json('settings')->nullable();
            $table->unsignedInteger('clicks_count')->default(0);
            $table->unsignedInteger('impressions_count')->default(0);
            $table->timestamps();

            $table->index(['channel_id', 'locale']);
            $table->index(['position', 'device_type', 'status']);
            $table->index(['start_date', 'end_date']);
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
