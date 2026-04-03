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
        Schema::create('addresses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('address_type');
            $table->unsignedInteger('parent_address_id')->nullable()->index('addresses_parent_address_id_foreign');
            $table->unsignedInteger('customer_id')->nullable()->index('addresses_customer_id_foreign')->comment('null if guest checkout');
            $table->unsignedInteger('cart_id')->nullable()->index('addresses_cart_id_foreign')->comment('only for cart_addresses');
            $table->unsignedInteger('order_id')->nullable()->index('addresses_order_id_foreign')->comment('only for order_addresses');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('gender')->nullable();
            $table->string('company_name')->nullable();
            $table->string('address');
            $table->string('city');
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->string('postcode')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('vat_id')->nullable();
            $table->boolean('default_address')->default(false)->comment('only for customer_addresses');
            $table->boolean('use_for_shipping')->default(false);
            $table->json('additional')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
