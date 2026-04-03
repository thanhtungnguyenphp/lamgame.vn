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
        Schema::table('shipment_items', function (Blueprint $table) {
            $table->foreign(['shipment_id'])->references(['id'])->on('shipments')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shipment_items', function (Blueprint $table) {
            $table->dropForeign('shipment_items_shipment_id_foreign');
        });
    }
};
