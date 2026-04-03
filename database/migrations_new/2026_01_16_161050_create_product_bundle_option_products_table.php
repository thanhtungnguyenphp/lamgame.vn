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
        Schema::create('product_bundle_option_products', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('product_id');
            $table->unsignedInteger('product_bundle_option_id')->index('product_bundle_option_id_foreign');
            $table->integer('qty')->default(0);
            $table->boolean('is_user_defined')->default(true);
            $table->boolean('is_default')->default(false);
            $table->integer('sort_order')->default(0);

            $table->unique(['product_id', 'product_bundle_option_id'], 'bundle_option_products_product_id_bundle_option_id_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_bundle_option_products');
    }
};
