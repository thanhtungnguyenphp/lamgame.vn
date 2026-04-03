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
        Schema::create('product_inventory_indices', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('qty')->default(0);
            $table->unsignedInteger('product_id');
            $table->unsignedInteger('channel_id')->index('product_inventory_indices_channel_id_foreign');
            $table->timestamps();

            $table->unique(['product_id', 'channel_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_inventory_indices');
    }
};
