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
        Schema::create('booking_product_event_ticket_translations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('booking_product_event_ticket_id');
            $table->string('locale');
            $table->text('name')->nullable();
            $table->text('description')->nullable();

            $table->unique(['booking_product_event_ticket_id', 'locale'], 'bpet_locale_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_product_event_ticket_translations');
    }
};
