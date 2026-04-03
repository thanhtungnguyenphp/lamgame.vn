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
        Schema::table('customers', function (Blueprint $table) {
            $table->foreign(['banned_by'])->references(['id'])->on('admins')->onUpdate('no action')->onDelete('set null');
            $table->foreign(['channel_id'])->references(['id'])->on('channels')->onUpdate('no action')->onDelete('set null');
            $table->foreign(['customer_group_id'])->references(['id'])->on('customer_groups')->onUpdate('no action')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropForeign('customers_banned_by_foreign');
            $table->dropForeign('customers_channel_id_foreign');
            $table->dropForeign('customers_customer_group_id_foreign');
        });
    }
};
