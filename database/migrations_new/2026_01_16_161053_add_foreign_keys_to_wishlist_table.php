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
        Schema::table('wishlist', function (Blueprint $table) {
            $table->foreign(['channel_id'])->references(['id'])->on('channels')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['customer_id'])->references(['id'])->on('customers')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['product_id'])->references(['id'])->on('products')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('wishlist', function (Blueprint $table) {
            $table->dropForeign('wishlist_channel_id_foreign');
            $table->dropForeign('wishlist_customer_id_foreign');
            $table->dropForeign('wishlist_product_id_foreign');
        });
    }
};
