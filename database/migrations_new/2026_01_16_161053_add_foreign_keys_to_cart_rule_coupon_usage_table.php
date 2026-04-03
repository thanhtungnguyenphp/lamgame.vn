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
        Schema::table('cart_rule_coupon_usage', function (Blueprint $table) {
            $table->foreign(['cart_rule_coupon_id'])->references(['id'])->on('cart_rule_coupons')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['customer_id'])->references(['id'])->on('customers')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cart_rule_coupon_usage', function (Blueprint $table) {
            $table->dropForeign('cart_rule_coupon_usage_cart_rule_coupon_id_foreign');
            $table->dropForeign('cart_rule_coupon_usage_customer_id_foreign');
        });
    }
};
