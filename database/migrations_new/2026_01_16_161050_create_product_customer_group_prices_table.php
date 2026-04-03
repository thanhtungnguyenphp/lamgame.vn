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
        Schema::create('product_customer_group_prices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('qty')->default(0);
            $table->string('value_type');
            $table->decimal('value', 12, 4)->default(0);
            $table->unsignedInteger('product_id')->index('product_customer_group_prices_product_id_foreign');
            $table->unsignedInteger('customer_group_id')->nullable()->index('product_customer_group_prices_customer_group_id_foreign');
            $table->timestamps();
            $table->string('unique_id')->nullable()->unique();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_customer_group_prices');
    }
};
