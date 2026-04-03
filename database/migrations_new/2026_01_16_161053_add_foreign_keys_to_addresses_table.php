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
        Schema::table('addresses', function (Blueprint $table) {
            $table->foreign(['cart_id'])->references(['id'])->on('cart')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['customer_id'])->references(['id'])->on('customers')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['order_id'])->references(['id'])->on('orders')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['parent_address_id'])->references(['id'])->on('addresses')->onUpdate('no action')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('addresses', function (Blueprint $table) {
            $table->dropForeign('addresses_cart_id_foreign');
            $table->dropForeign('addresses_customer_id_foreign');
            $table->dropForeign('addresses_order_id_foreign');
            $table->dropForeign('addresses_parent_address_id_foreign');
        });
    }
};
