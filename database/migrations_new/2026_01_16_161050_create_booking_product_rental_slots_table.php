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
        Schema::create('booking_product_rental_slots', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('booking_product_id')->index('booking_product_rental_slots_booking_product_id_foreign');
            $table->string('renting_type');
            $table->decimal('daily_price', 12, 4)->nullable()->default(0);
            $table->decimal('hourly_price', 12, 4)->nullable()->default(0);
            $table->boolean('same_slot_all_days')->nullable();
            $table->json('slots')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_product_rental_slots');
    }
};
