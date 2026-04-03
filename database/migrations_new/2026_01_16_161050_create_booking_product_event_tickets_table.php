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
        Schema::create('booking_product_event_tickets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('booking_product_id')->index('booking_product_event_tickets_booking_product_id_foreign');
            $table->decimal('price', 12, 4)->nullable()->default(0);
            $table->integer('qty')->nullable()->default(0);
            $table->decimal('special_price', 12, 4)->nullable();
            $table->dateTime('special_price_from')->nullable();
            $table->dateTime('special_price_to')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_product_event_tickets');
    }
};
