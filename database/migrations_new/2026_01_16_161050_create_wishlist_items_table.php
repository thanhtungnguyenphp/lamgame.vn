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
        Schema::create('wishlist_items', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('channel_id')->index('wishlist_items_channel_id_foreign');
            $table->unsignedInteger('product_id')->index('wishlist_items_product_id_foreign');
            $table->unsignedInteger('customer_id')->index('wishlist_items_customer_id_foreign');
            $table->json('additional')->nullable();
            $table->date('moved_to_cart')->nullable();
            $table->boolean('shared')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wishlist_items');
    }
};
