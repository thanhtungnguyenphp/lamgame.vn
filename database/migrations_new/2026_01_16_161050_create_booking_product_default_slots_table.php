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
        Schema::create('booking_product_default_slots', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('booking_product_id')->index('booking_product_default_slots_booking_product_id_foreign');
            $table->string('booking_type');
            $table->integer('duration')->nullable();
            $table->integer('break_time')->nullable();
            $table->json('slots')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_product_default_slots');
    }
};
