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
        Schema::table('order_items', function (Blueprint $table) {
            $table->foreign(['order_id'])->references(['id'])->on('orders')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['parent_id'])->references(['id'])->on('order_items')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['tax_category_id'])->references(['id'])->on('tax_categories')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropForeign('order_items_order_id_foreign');
            $table->dropForeign('order_items_parent_id_foreign');
            $table->dropForeign('order_items_tax_category_id_foreign');
        });
    }
};
