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
        Schema::table('cart_rule_channels', function (Blueprint $table) {
            $table->foreign(['cart_rule_id'])->references(['id'])->on('cart_rules')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['channel_id'])->references(['id'])->on('channels')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cart_rule_channels', function (Blueprint $table) {
            $table->dropForeign('cart_rule_channels_cart_rule_id_foreign');
            $table->dropForeign('cart_rule_channels_channel_id_foreign');
        });
    }
};
