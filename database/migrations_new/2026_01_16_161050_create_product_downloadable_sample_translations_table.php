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
        Schema::create('product_downloadable_sample_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('product_downloadable_sample_id')->index('sample_translations_sample_id_foreign');
            $table->string('locale');
            $table->text('title')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_downloadable_sample_translations');
    }
};
