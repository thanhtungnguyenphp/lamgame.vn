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
        Schema::create('product_price_indices', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('product_id');
            $table->unsignedInteger('customer_group_id')->nullable()->index('product_price_indices_customer_group_id_foreign');
            $table->unsignedInteger('channel_id')->default(1)->index('product_price_indices_channel_id_foreign');
            $table->decimal('min_price', 12, 4)->default(0);
            $table->decimal('regular_min_price', 12, 4)->default(0);
            $table->decimal('max_price', 12, 4)->default(0);
            $table->decimal('regular_max_price', 12, 4)->default(0);
            $table->timestamps();

            $table->unique(['product_id', 'customer_group_id', 'channel_id'], 'price_indices_product_id_customer_group_id_channel_id_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_price_indices');
    }
};
