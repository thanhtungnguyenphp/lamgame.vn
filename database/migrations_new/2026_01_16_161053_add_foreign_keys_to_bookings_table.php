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
        Schema::table('bookings', function (Blueprint $table) {
            $table->foreign(['booking_product_event_ticket_id'])->references(['id'])->on('booking_product_event_tickets')->onUpdate('no action')->onDelete('set null');
            $table->foreign(['order_id'])->references(['id'])->on('orders')->onUpdate('no action')->onDelete('set null');
            $table->foreign(['order_item_id'])->references(['id'])->on('order_items')->onUpdate('no action')->onDelete('set null');
            $table->foreign(['product_id'])->references(['id'])->on('products')->onUpdate('no action')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign('bookings_booking_product_event_ticket_id_foreign');
            $table->dropForeign('bookings_order_id_foreign');
            $table->dropForeign('bookings_order_item_id_foreign');
            $table->dropForeign('bookings_product_id_foreign');
        });
    }
};
