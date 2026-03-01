<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lottery_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('province_id')->constrained('lottery_provinces')->cascadeOnDelete();
            $table->tinyInteger('day_of_week')->unsigned()->comment('1=Mon...7=Sun');
            $table->timestamps();

            $table->unique(['province_id', 'day_of_week']);
            $table->index('day_of_week');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lottery_schedules');
    }
};
