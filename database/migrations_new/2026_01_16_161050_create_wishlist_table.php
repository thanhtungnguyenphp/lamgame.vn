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
        Schema::create('wishlist', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('channel_id')->index('wishlist_channel_id_foreign');
            $table->unsignedInteger('product_id')->index('wishlist_product_id_foreign');
            $table->unsignedInteger('customer_id')->index('wishlist_customer_id_foreign');
            $table->json('item_options')->nullable();
            $table->date('moved_to_cart')->nullable();
            $table->boolean('shared')->nullable();
            $table->date('time_of_moving')->nullable();
            $table->json('additional')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wishlist');
    }
};
