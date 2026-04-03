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
        Schema::create('compare_items', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('product_id')->index('compare_items_product_id_foreign');
            $table->unsignedInteger('customer_id')->index('compare_items_customer_id_foreign');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('compare_items');
    }
};
