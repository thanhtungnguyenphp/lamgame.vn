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
        Schema::table('downloadable_link_purchased', function (Blueprint $table) {
            $table->foreign(['customer_id'])->references(['id'])->on('customers')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['order_id'])->references(['id'])->on('orders')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['order_item_id'])->references(['id'])->on('order_items')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('downloadable_link_purchased', function (Blueprint $table) {
            $table->dropForeign('downloadable_link_purchased_customer_id_foreign');
            $table->dropForeign('downloadable_link_purchased_order_id_foreign');
            $table->dropForeign('downloadable_link_purchased_order_item_id_foreign');
        });
    }
};
