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
        Schema::create('source_game_earnings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('seller_id');
            $table->unsignedInteger('order_id')->index();
            $table->unsignedBigInteger('order_item_id');
            $table->unsignedInteger('product_id')->index('source_game_earnings_product_id_foreign');
            $table->decimal('order_amount', 12);
            $table->decimal('platform_fee_percent', 5)->default(30);
            $table->decimal('platform_fee_amount', 12);
            $table->decimal('seller_amount', 12);
            $table->enum('status', ['pending', 'completed', 'refunded'])->default('pending');
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index(['seller_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('source_game_earnings');
    }
};
