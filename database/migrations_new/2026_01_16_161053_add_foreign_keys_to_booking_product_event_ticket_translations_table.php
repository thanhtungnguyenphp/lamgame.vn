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
        Schema::table('booking_product_event_ticket_translations', function (Blueprint $table) {
            $table->foreign(['booking_product_event_ticket_id'], 'bpet_translations_fk')->references(['id'])->on('booking_product_event_tickets')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('booking_product_event_ticket_translations', function (Blueprint $table) {
            $table->dropForeign('bpet_translations_fk');
        });
    }
};
