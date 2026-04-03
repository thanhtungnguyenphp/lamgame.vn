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
        Schema::create('cart_rule_coupons', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code')->nullable();
            $table->unsignedInteger('usage_limit')->default(0);
            $table->unsignedInteger('usage_per_customer')->default(0);
            $table->unsignedInteger('times_used')->default(0);
            $table->unsignedInteger('type')->default(0);
            $table->boolean('is_primary')->default(false);
            $table->date('expired_at')->nullable();
            $table->unsignedInteger('cart_rule_id')->index('cart_rule_coupons_cart_rule_id_foreign');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_rule_coupons');
    }
};
