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
        Schema::table('cart_shipping_rates', function (Blueprint $table) {
            $table->foreign(['cart_id'])->references(['id'])->on('cart')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cart_shipping_rates', function (Blueprint $table) {
            $table->dropForeign('cart_shipping_rates_cart_id_foreign');
        });
    }
};
