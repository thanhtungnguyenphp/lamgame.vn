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
        Schema::create('bookings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('product_id')->nullable()->index('bookings_product_id_foreign');
            $table->unsignedInteger('order_item_id')->nullable()->index('bookings_order_item_id_foreign');
            $table->unsignedInteger('order_id')->nullable()->index('bookings_order_id_foreign');
            $table->integer('qty')->nullable()->default(0);
            $table->integer('from')->nullable();
            $table->integer('to')->nullable();
            $table->unsignedBigInteger('booking_product_event_ticket_id')->nullable()->index('bookings_booking_product_event_ticket_id_foreign');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
