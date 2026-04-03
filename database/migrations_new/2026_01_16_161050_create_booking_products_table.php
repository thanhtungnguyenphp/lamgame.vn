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
        Schema::create('booking_products', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('product_id')->index('booking_products_product_id_foreign');
            $table->string('type');
            $table->integer('qty')->nullable()->default(0);
            $table->string('location')->nullable();
            $table->boolean('show_location')->default(false);
            $table->boolean('available_every_week')->nullable();
            $table->dateTime('available_from')->nullable();
            $table->dateTime('available_to')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_products');
    }
};
