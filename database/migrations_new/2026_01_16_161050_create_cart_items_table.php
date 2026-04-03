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
        Schema::create('cart_items', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('quantity')->default(0);
            $table->string('sku')->nullable();
            $table->string('type')->nullable();
            $table->string('name')->nullable();
            $table->string('coupon_code')->nullable();
            $table->decimal('weight', 12, 4)->default(0);
            $table->decimal('total_weight', 12, 4)->default(0);
            $table->decimal('base_total_weight', 12, 4)->default(0);
            $table->decimal('price', 12, 4)->default(1);
            $table->decimal('base_price', 12, 4)->default(0);
            $table->decimal('custom_price', 12, 4)->nullable();
            $table->decimal('total', 12, 4)->default(0);
            $table->decimal('base_total', 12, 4)->default(0);
            $table->decimal('tax_percent', 12, 4)->nullable()->default(0);
            $table->decimal('tax_amount', 12, 4)->nullable()->default(0);
            $table->decimal('base_tax_amount', 12, 4)->nullable()->default(0);
            $table->decimal('discount_percent', 12, 4)->default(0);
            $table->decimal('discount_amount', 12, 4)->default(0);
            $table->decimal('base_discount_amount', 12, 4)->default(0);
            $table->decimal('price_incl_tax', 12, 4)->default(0);
            $table->decimal('base_price_incl_tax', 12, 4)->default(0);
            $table->decimal('total_incl_tax', 12, 4)->default(0);
            $table->decimal('base_total_incl_tax', 12, 4)->default(0);
            $table->string('applied_tax_rate')->nullable();
            $table->unsignedInteger('parent_id')->nullable()->index('cart_items_parent_id_foreign');
            $table->unsignedInteger('product_id')->index('cart_items_product_id_foreign');
            $table->unsignedInteger('cart_id')->index('cart_items_cart_id_foreign');
            $table->unsignedInteger('tax_category_id')->nullable()->index('cart_items_tax_category_id_foreign');
            $table->string('applied_cart_rule_ids')->nullable();
            $table->json('additional')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};
