<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lemon_squeezy_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('ls_order_id')->unique();
            $table->unsignedBigInteger('order_id')->nullable()->index();
            $table->unsignedBigInteger('cart_id')->nullable()->index();
            $table->unsignedBigInteger('customer_id')->nullable()->index();
            $table->string('status')->default('pending'); // pending, paid, refunded
            $table->integer('amount_usd_cents')->default(0);
            $table->decimal('amount_vnd', 12, 2)->default(0);
            $table->string('currency', 10)->default('USD');
            $table->string('receipt_url')->nullable();
            $table->json('webhook_payload')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lemon_squeezy_transactions');
    }
};
