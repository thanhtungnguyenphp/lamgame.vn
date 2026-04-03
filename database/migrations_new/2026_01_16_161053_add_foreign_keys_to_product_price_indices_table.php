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
        Schema::table('product_price_indices', function (Blueprint $table) {
            $table->foreign(['channel_id'])->references(['id'])->on('channels')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['customer_group_id'])->references(['id'])->on('customer_groups')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['product_id'])->references(['id'])->on('products')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_price_indices', function (Blueprint $table) {
            $table->dropForeign('product_price_indices_channel_id_foreign');
            $table->dropForeign('product_price_indices_customer_group_id_foreign');
            $table->dropForeign('product_price_indices_product_id_foreign');
        });
    }
};
