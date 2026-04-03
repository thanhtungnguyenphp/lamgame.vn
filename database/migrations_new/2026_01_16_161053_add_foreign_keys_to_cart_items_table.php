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
        Schema::table('cart_items', function (Blueprint $table) {
            $table->foreign(['cart_id'])->references(['id'])->on('cart')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['parent_id'])->references(['id'])->on('cart_items')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['product_id'])->references(['id'])->on('products')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['tax_category_id'])->references(['id'])->on('tax_categories')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropForeign('cart_items_cart_id_foreign');
            $table->dropForeign('cart_items_parent_id_foreign');
            $table->dropForeign('cart_items_product_id_foreign');
            $table->dropForeign('cart_items_tax_category_id_foreign');
        });
    }
};
