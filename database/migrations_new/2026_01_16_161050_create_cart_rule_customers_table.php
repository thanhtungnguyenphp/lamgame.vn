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
        Schema::create('cart_rule_customers', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('times_used')->default(0);
            $table->unsignedInteger('customer_id')->index('cart_rule_customers_customer_id_foreign');
            $table->unsignedInteger('cart_rule_id')->index('cart_rule_customers_cart_rule_id_foreign');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_rule_customers');
    }
};
