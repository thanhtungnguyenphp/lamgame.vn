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
        Schema::table('booking_product_table_slots', function (Blueprint $table) {
            $table->foreign(['booking_product_id'])->references(['id'])->on('booking_products')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('booking_product_table_slots', function (Blueprint $table) {
            $table->dropForeign('booking_product_table_slots_booking_product_id_foreign');
        });
    }
};
