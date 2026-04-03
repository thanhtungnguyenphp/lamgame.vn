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
        Schema::table('refund_items', function (Blueprint $table) {
            $table->foreign(['order_item_id'])->references(['id'])->on('order_items')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['parent_id'])->references(['id'])->on('refund_items')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['refund_id'])->references(['id'])->on('refunds')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('refund_items', function (Blueprint $table) {
            $table->dropForeign('refund_items_order_item_id_foreign');
            $table->dropForeign('refund_items_parent_id_foreign');
            $table->dropForeign('refund_items_refund_id_foreign');
        });
    }
};
