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
        Schema::create('product_bundle_option_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('locale');
            $table->string('label')->nullable();
            $table->unsignedInteger('product_bundle_option_id');

            $table->unique(['locale', 'label', 'product_bundle_option_id'], 'bundle_option_translations_locale_label_bundle_option_id_unique');
            $table->unique(['product_bundle_option_id', 'locale'], 'product_bundle_option_translations_option_id_locale_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_bundle_option_translations');
    }
};
