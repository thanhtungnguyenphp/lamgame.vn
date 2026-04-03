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
        Schema::table('source_game_earnings', function (Blueprint $table) {
            $table->foreign(['order_id'])->references(['id'])->on('orders')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['product_id'])->references(['id'])->on('products')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['seller_id'])->references(['id'])->on('source_game_sellers')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('source_game_earnings', function (Blueprint $table) {
            $table->dropForeign('source_game_earnings_order_id_foreign');
            $table->dropForeign('source_game_earnings_product_id_foreign');
            $table->dropForeign('source_game_earnings_seller_id_foreign');
        });
    }
};
