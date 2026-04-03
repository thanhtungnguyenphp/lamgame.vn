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
        Schema::table('shipments', function (Blueprint $table) {
            $table->foreign(['inventory_source_id'])->references(['id'])->on('inventory_sources')->onUpdate('no action')->onDelete('set null');
            $table->foreign(['order_id'])->references(['id'])->on('orders')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shipments', function (Blueprint $table) {
            $table->dropForeign('shipments_inventory_source_id_foreign');
            $table->dropForeign('shipments_order_id_foreign');
        });
    }
};
