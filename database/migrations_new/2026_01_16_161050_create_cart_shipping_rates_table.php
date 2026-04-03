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
        Schema::create('cart_shipping_rates', function (Blueprint $table) {
            $table->increments('id');
            $table->string('carrier');
            $table->string('carrier_title');
            $table->string('method');
            $table->string('method_title');
            $table->string('method_description')->nullable();
            $table->double('price')->nullable()->default(0);
            $table->double('base_price')->nullable()->default(0);
            $table->decimal('discount_amount', 12, 4)->default(0);
            $table->decimal('base_discount_amount', 12, 4)->default(0);
            $table->decimal('tax_percent', 12, 4)->default(0);
            $table->decimal('tax_amount', 12, 4)->default(0);
            $table->decimal('base_tax_amount', 12, 4)->default(0);
            $table->decimal('price_incl_tax', 12, 4)->default(0);
            $table->decimal('base_price_incl_tax', 12, 4)->default(0);
            $table->string('applied_tax_rate')->nullable();
            $table->boolean('is_calculate_tax')->default(true);
            $table->unsignedInteger('cart_address_id')->nullable();
            $table->timestamps();
            $table->unsignedInteger('cart_id')->nullable()->index('cart_shipping_rates_cart_id_foreign');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_shipping_rates');
    }
};
