<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lottery_draws', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['traditional', 'vietlot']);
            $table->string('game', 20)->nullable()->comment('mega645, power655, max3d, max3d_pro, keno');
            $table->string('region', 20)->nullable()->comment('mien-nam, mien-trung, mien-bac');
            $table->date('date');
            $table->string('draw_time', 5)->nullable();
            $table->string('draw_id', 20)->nullable()->comment('Mã kỳ quay vietlot');
            $table->string('period', 10)->nullable()->comment('Kỳ Keno');
            $table->enum('status', ['pending', 'completed', 'error'])->default('pending');
            $table->string('source', 50)->nullable();
            $table->timestamp('scraped_at')->nullable();
            $table->timestamps();

            $table->index(['type', 'date']);
            $table->index(['game', 'date']);
            $table->index(['region', 'date']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lottery_draws');
    }
};
