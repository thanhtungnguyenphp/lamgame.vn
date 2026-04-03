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
        Schema::create('product_customizable_option_prices', function (Blueprint $table) {
            $table->increments('id');
            $table->text('label')->nullable();
            $table->decimal('price', 12, 4)->default(0);
            $table->unsignedInteger('product_customizable_option_id')->index('pcop_product_customizable_option_id_foreign');
            $table->integer('sort_order')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_customizable_option_prices');
    }
};
