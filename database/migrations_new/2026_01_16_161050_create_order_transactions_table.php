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
        Schema::create('order_transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('transaction_id');
            $table->string('status')->nullable();
            $table->string('type')->nullable();
            $table->decimal('amount', 12, 4)->nullable()->default(0);
            $table->string('payment_method')->nullable();
            $table->json('data')->nullable();
            $table->unsignedInteger('invoice_id');
            $table->unsignedInteger('order_id')->index('order_transactions_order_id_foreign');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_transactions');
    }
};
