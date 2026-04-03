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
        Schema::create('cart_item_inventories', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('qty')->default(0);
            $table->unsignedInteger('inventory_source_id')->nullable();
            $table->unsignedInteger('cart_item_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_item_inventories');
    }
};
