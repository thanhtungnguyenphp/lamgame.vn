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
        Schema::create('cart_payment', function (Blueprint $table) {
            $table->increments('id');
            $table->string('method');
            $table->string('method_title')->nullable();
            $table->unsignedInteger('cart_id')->nullable()->index('cart_payment_cart_id_foreign');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_payment');
    }
};
