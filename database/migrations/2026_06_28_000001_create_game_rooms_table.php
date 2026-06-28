<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('game_rooms', function (Blueprint $table) {
            $table->id();
            $table->string('code', 6)->unique();
            $table->string('game_type', 30)->default('caro');
            $table->string('player_x', 30)->nullable();
            $table->string('player_o', 30)->nullable();
            $table->json('board_state')->nullable();
            $table->enum('status', ['waiting', 'playing', 'finished'])->default('waiting');
            $table->enum('current_turn', ['x', 'o'])->default('x');
            $table->string('winner', 30)->nullable();
            $table->timestamps();
            $table->index(['status', 'game_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('game_rooms');
    }
};
