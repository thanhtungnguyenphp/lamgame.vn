<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Thêm unique constraint cho order_item_id trên source_game_earnings.
 * Mục đích: chống tạo earning TRÙNG cho cùng một order item (DATA-01),
 * khi listener order-complete bị bắn nhiều lần / đồng thời.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('source_game_earnings', function (Blueprint $table) {
            $table->unique('order_item_id', 'sge_order_item_id_unique');
        });
    }

    public function down(): void
    {
        Schema::table('source_game_earnings', function (Blueprint $table) {
            $table->dropUnique('sge_order_item_id_unique');
        });
    }
};
