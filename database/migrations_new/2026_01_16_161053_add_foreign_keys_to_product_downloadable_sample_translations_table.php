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
        Schema::table('product_downloadable_sample_translations', function (Blueprint $table) {
            $table->foreign(['product_downloadable_sample_id'], 'sample_translations_sample_id_foreign')->references(['id'])->on('product_downloadable_samples')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_downloadable_sample_translations', function (Blueprint $table) {
            $table->dropForeign('sample_translations_sample_id_foreign');
        });
    }
};
