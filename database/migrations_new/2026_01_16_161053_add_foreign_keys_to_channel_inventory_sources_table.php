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
        Schema::table('channel_inventory_sources', function (Blueprint $table) {
            $table->foreign(['channel_id'])->references(['id'])->on('channels')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['inventory_source_id'])->references(['id'])->on('inventory_sources')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('channel_inventory_sources', function (Blueprint $table) {
            $table->dropForeign('channel_inventory_sources_channel_id_foreign');
            $table->dropForeign('channel_inventory_sources_inventory_source_id_foreign');
        });
    }
};
