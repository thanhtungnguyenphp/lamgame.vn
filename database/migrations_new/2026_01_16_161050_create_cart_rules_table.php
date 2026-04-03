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
        Schema::create('cart_rules', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('description')->nullable();
            $table->dateTime('starts_from')->nullable();
            $table->dateTime('ends_till')->nullable();
            $table->boolean('status')->default(false);
            $table->integer('coupon_type')->default(1);
            $table->boolean('use_auto_generation')->default(false);
            $table->integer('usage_per_customer')->default(0);
            $table->integer('uses_per_coupon')->default(0);
            $table->unsignedInteger('times_used')->default(0);
            $table->boolean('condition_type')->default(true);
            $table->json('conditions')->nullable();
            $table->boolean('end_other_rules')->default(false);
            $table->boolean('uses_attribute_conditions')->default(false);
            $table->string('action_type')->nullable();
            $table->decimal('discount_amount', 12, 4)->default(0);
            $table->integer('discount_quantity')->default(1);
            $table->string('discount_step')->default('1');
            $table->boolean('apply_to_shipping')->default(false);
            $table->boolean('free_shipping')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_rules');
    }
};
