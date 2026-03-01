<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lottery_provinces', function (Blueprint $table) {
            $table->id();
            $table->string('code', 10)->unique();
            $table->string('name', 100);
            $table->enum('region', ['mien-nam', 'mien-trung', 'mien-bac']);
            $table->tinyInteger('sort_order')->unsigned()->default(0);
            $table->timestamps();

            $table->index('region');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lottery_provinces');
    }
};
