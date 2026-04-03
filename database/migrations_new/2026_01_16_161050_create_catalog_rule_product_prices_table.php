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
        Schema::create('catalog_rule_product_prices', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('price', 12, 4)->default(0);
            $table->date('rule_date');
            $table->dateTime('starts_from')->nullable();
            $table->dateTime('ends_till')->nullable();
            $table->unsignedInteger('product_id')->index('catalog_rule_product_prices_product_id_foreign');
            $table->unsignedInteger('customer_group_id')->index('catalog_rule_product_prices_customer_group_id_foreign');
            $table->unsignedInteger('catalog_rule_id')->index('catalog_rule_product_prices_catalog_rule_id_foreign');
            $table->unsignedInteger('channel_id')->index('catalog_rule_product_prices_channel_id_foreign');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('catalog_rule_product_prices');
    }
};
