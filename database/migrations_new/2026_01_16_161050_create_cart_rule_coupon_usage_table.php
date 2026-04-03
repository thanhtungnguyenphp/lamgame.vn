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
        Schema::create('cart_rule_coupon_usage', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('times_used')->default(0);
            $table->unsignedInteger('cart_rule_coupon_id')->index('cart_rule_coupon_usage_cart_rule_coupon_id_foreign');
            $table->unsignedInteger('customer_id')->index('cart_rule_coupon_usage_customer_id_foreign');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_rule_coupon_usage');
    }
};
