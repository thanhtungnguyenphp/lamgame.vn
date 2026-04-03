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
        Schema::create('product_inventories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('qty')->default(0);
            $table->unsignedInteger('product_id');
            $table->integer('vendor_id')->default(0);
            $table->unsignedInteger('inventory_source_id')->index('product_inventories_inventory_source_id_foreign');

            $table->unique(['product_id', 'inventory_source_id', 'vendor_id'], 'product_source_vendor_index_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_inventories');
    }
};
