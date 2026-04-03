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
        Schema::create('channel_inventory_sources', function (Blueprint $table) {
            $table->unsignedInteger('channel_id');
            $table->unsignedInteger('inventory_source_id')->index('channel_inventory_sources_inventory_source_id_foreign');

            $table->unique(['channel_id', 'inventory_source_id'], 'channel_inventory_source_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('channel_inventory_sources');
    }
};
