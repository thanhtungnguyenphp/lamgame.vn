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
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('parent_id')->nullable()->index('invoice_items_parent_id_foreign');
            $table->string('name')->nullable();
            $table->string('description')->nullable();
            $table->string('sku')->nullable();
            $table->integer('qty')->nullable();
            $table->decimal('price', 12, 4)->default(0);
            $table->decimal('base_price', 12, 4)->default(0);
            $table->decimal('total', 12, 4)->default(0);
            $table->decimal('base_total', 12, 4)->default(0);
            $table->decimal('tax_amount', 12, 4)->nullable()->default(0);
            $table->decimal('base_tax_amount', 12, 4)->nullable()->default(0);
            $table->decimal('discount_percent', 12, 4)->nullable()->default(0);
            $table->decimal('discount_amount', 12, 4)->nullable()->default(0);
            $table->decimal('base_discount_amount', 12, 4)->nullable()->default(0);
            $table->decimal('price_incl_tax', 12, 4)->default(0);
            $table->decimal('base_price_incl_tax', 12, 4)->default(0);
            $table->decimal('total_incl_tax', 12, 4)->default(0);
            $table->decimal('base_total_incl_tax', 12, 4)->default(0);
            $table->unsignedInteger('product_id')->nullable();
            $table->string('product_type')->nullable();
            $table->unsignedInteger('order_item_id')->nullable();
            $table->unsignedInteger('invoice_id')->nullable()->index('invoice_items_invoice_id_foreign');
            $table->json('additional')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_items');
    }
};
