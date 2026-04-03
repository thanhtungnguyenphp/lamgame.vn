<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('source_game_earnings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('seller_id');
            $table->unsignedInteger('order_id');
            $table->unsignedBigInteger('order_item_id');
            $table->unsignedInteger('product_id');
            $table->decimal('order_amount', 12, 2);
            $table->decimal('platform_fee_percent', 5, 2)->default(30.00);
            $table->decimal('platform_fee_amount', 12, 2);
            $table->decimal('seller_amount', 12, 2);
            $table->enum('status', ['pending', 'completed', 'refunded'])->default('pending');
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->foreign('seller_id')->references('id')->on('source_game_sellers')->onDelete('cascade');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            
            $table->index(['seller_id', 'status']);
            $table->index('order_id');
        });

        Schema::create('source_game_withdrawals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('seller_id');
            $table->decimal('amount', 12, 2);
            $table->enum('status', ['pending', 'processing', 'completed', 'rejected'])->default('pending');
            $table->string('bank_name');
            $table->string('bank_account');
            $table->string('bank_holder');
            $table->text('note')->nullable();
            $table->text('admin_note')->nullable();
            $table->string('transaction_id')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->unsignedBigInteger('processed_by')->nullable();
            $table->timestamps();

            $table->foreign('seller_id')->references('id')->on('source_game_sellers')->onDelete('cascade');
            
            $table->index(['seller_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('source_game_withdrawals');
        Schema::dropIfExists('source_game_earnings');
    }
};
