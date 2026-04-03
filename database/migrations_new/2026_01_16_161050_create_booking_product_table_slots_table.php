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
        Schema::create('booking_product_table_slots', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('booking_product_id')->index('booking_product_table_slots_booking_product_id_foreign');
            $table->string('price_type');
            $table->integer('guest_limit')->default(0);
            $table->integer('duration');
            $table->integer('break_time');
            $table->integer('prevent_scheduling_before');
            $table->boolean('same_slot_all_days')->nullable();
            $table->json('slots')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_product_table_slots');
    }
};
