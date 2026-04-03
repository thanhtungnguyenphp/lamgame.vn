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
        Schema::table('product_downloadable_link_translations', function (Blueprint $table) {
            $table->foreign(['product_downloadable_link_id'], 'link_translations_link_id_foreign')->references(['id'])->on('product_downloadable_links')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_downloadable_link_translations', function (Blueprint $table) {
            $table->dropForeign('link_translations_link_id_foreign');
        });
    }
};
