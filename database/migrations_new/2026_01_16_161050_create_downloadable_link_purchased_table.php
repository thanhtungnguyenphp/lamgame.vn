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
        Schema::create('downloadable_link_purchased', function (Blueprint $table) {
            $table->increments('id');
            $table->string('product_name')->nullable();
            $table->string('name')->nullable();
            $table->string('url')->nullable();
            $table->string('file')->nullable();
            $table->string('file_name')->nullable();
            $table->string('type');
            $table->integer('download_bought')->default(0);
            $table->integer('download_used')->default(0);
            $table->string('status')->nullable();
            $table->unsignedInteger('customer_id')->index('downloadable_link_purchased_customer_id_foreign');
            $table->unsignedInteger('order_id')->index('downloadable_link_purchased_order_id_foreign');
            $table->unsignedInteger('order_item_id')->index('downloadable_link_purchased_order_item_id_foreign');
            $table->integer('download_canceled')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('downloadable_link_purchased');
    }
};
