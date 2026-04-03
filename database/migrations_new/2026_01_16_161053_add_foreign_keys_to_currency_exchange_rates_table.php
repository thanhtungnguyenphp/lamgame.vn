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
        Schema::table('currency_exchange_rates', function (Blueprint $table) {
            $table->foreign(['target_currency'])->references(['id'])->on('currencies')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('currency_exchange_rates', function (Blueprint $table) {
            $table->dropForeign('currency_exchange_rates_target_currency_foreign');
        });
    }
};
