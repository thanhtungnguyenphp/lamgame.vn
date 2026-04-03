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
        Schema::create('attribute_option_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('attribute_option_id');
            $table->string('locale');
            $table->text('label')->nullable();

            $table->unique(['attribute_option_id', 'locale'], 'attribute_option_locale_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attribute_option_translations');
    }
};
