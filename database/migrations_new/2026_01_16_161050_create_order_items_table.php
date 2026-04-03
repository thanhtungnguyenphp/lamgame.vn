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
        Schema::create('order_items', function (Blueprint $table) {
            $table->increments('id');
            $table->string('sku')->nullable();
            $table->string('type')->nullable();
            $table->string('name')->nullable();
            $table->string('coupon_code')->nullable();
            $table->decimal('weight', 12, 4)->nullable()->default(0);
            $table->decimal('total_weight', 12, 4)->nullable()->default(0);
            $table->integer('qty_ordered')->nullable()->default(0);
            $table->integer('qty_shipped')->nullable()->default(0);
            $table->integer('qty_invoiced')->nullable()->default(0);
            $table->integer('qty_canceled')->nullable()->default(0);
            $table->integer('qty_refunded')->nullable()->default(0);
            $table->decimal('price', 12, 4)->default(0);
            $table->decimal('base_price', 12, 4)->default(0);
            $table->decimal('total', 12, 4)->default(0);
            $table->decimal('base_total', 12, 4)->default(0);
            $table->decimal('total_invoiced', 12, 4)->default(0);
            $table->decimal('base_total_invoiced', 12, 4)->default(0);
            $table->decimal('amount_refunded', 12, 4)->default(0);
            $table->decimal('base_amount_refunded', 12, 4)->default(0);
            $table->decimal('discount_percent', 12, 4)->nullable()->default(0);
            $table->decimal('discount_amount', 12, 4)->nullable()->default(0);
            $table->decimal('base_discount_amount', 12, 4)->nullable()->default(0);
            $table->decimal('discount_invoiced', 12, 4)->nullable()->default(0);
            $table->decimal('base_discount_invoiced', 12, 4)->nullable()->default(0);
            $table->decimal('discount_refunded', 12, 4)->nullable()->default(0);
            $table->decimal('base_discount_refunded', 12, 4)->nullable()->default(0);
            $table->decimal('tax_percent', 12, 4)->nullable()->default(0);
            $table->decimal('tax_amount', 12, 4)->nullable()->default(0);
            $table->decimal('base_tax_amount', 12, 4)->nullable()->default(0);
            $table->decimal('tax_amount_invoiced', 12, 4)->nullable()->default(0);
            $table->decimal('base_tax_amount_invoiced', 12, 4)->nullable()->default(0);
            $table->decimal('tax_amount_refunded', 12, 4)->nullable()->default(0);
            $table->decimal('base_tax_amount_refunded', 12, 4)->nullable()->default(0);
            $table->decimal('price_incl_tax', 12, 4)->default(0);
            $table->decimal('base_price_incl_tax', 12, 4)->default(0);
            $table->decimal('total_incl_tax', 12, 4)->default(0);
            $table->decimal('base_total_incl_tax', 12, 4)->default(0);
            $table->unsignedInteger('product_id')->nullable();
            $table->string('product_type')->nullable();
            $table->unsignedInteger('order_id')->nullable()->index('order_items_order_id_foreign');
            $table->unsignedInteger('tax_category_id')->nullable()->index('order_items_tax_category_id_foreign');
            $table->unsignedInteger('parent_id')->nullable()->index('order_items_parent_id_foreign');
            $table->json('additional')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
