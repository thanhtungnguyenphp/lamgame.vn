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
        Schema::create('product_customizable_option_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('locale');
            $table->text('label')->nullable();
            $table->unsignedInteger('product_customizable_option_id');

            $table->unique(['product_customizable_option_id', 'locale'], 'product_customizable_option_id_locale_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_customizable_option_translations');
    }
};
