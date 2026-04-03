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
        Schema::table('product_customizable_option_translations', function (Blueprint $table) {
            $table->foreign(['product_customizable_option_id'], 'pcot_product_customizable_option_id_foreign')->references(['id'])->on('product_customizable_options')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_customizable_option_translations', function (Blueprint $table) {
            $table->dropForeign('pcot_product_customizable_option_id_foreign');
        });
    }
};
