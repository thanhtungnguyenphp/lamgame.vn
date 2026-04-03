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
        Schema::table('product_flat', function (Blueprint $table) {
            $table->foreign(['attribute_family_id'])->references(['id'])->on('attribute_families')->onUpdate('no action')->onDelete('restrict');
            $table->foreign(['parent_id'])->references(['id'])->on('product_flat')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['product_id'])->references(['id'])->on('products')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_flat', function (Blueprint $table) {
            $table->dropForeign('product_flat_attribute_family_id_foreign');
            $table->dropForeign('product_flat_parent_id_foreign');
            $table->dropForeign('product_flat_product_id_foreign');
        });
    }
};
