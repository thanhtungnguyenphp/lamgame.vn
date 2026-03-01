<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lottery_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('draw_id')->constrained('lottery_draws')->cascadeOnDelete();
            $table->foreignId('province_id')->nullable()->constrained('lottery_provinces')->nullOnDelete();
            $table->json('prize_data');
            $table->json('jackpot_data')->nullable();
            $table->json('stats_data')->nullable();
            $table->timestamps();

            $table->index('draw_id');
            $table->index('province_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lottery_results');
    }
};
