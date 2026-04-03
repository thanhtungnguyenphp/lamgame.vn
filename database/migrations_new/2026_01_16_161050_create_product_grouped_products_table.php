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
        Schema::create('product_grouped_products', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('product_id');
            $table->unsignedInteger('associated_product_id')->index('product_grouped_products_associated_product_id_foreign');
            $table->integer('qty')->default(0);
            $table->integer('sort_order')->default(0);

            $table->unique(['product_id', 'associated_product_id'], 'grouped_products_product_id_associated_product_id_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_grouped_products');
    }
};
