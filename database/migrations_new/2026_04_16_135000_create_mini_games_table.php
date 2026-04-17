<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mini_games', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('keywords')->nullable();
            $table->string('category')->default('arcade')->index();
            $table->string('thumbnail')->nullable();
            $table->string('game_path');              // relative path: games/ran-san-moi
            $table->boolean('is_active')->default(true)->index();
            $table->boolean('is_mobile_ready')->default(false);
            $table->unsignedBigInteger('play_count')->default(0);
            $table->unsignedTinyInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mini_games');
    }
};
