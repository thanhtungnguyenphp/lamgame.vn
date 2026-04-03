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
        Schema::create('channel_currencies', function (Blueprint $table) {
            $table->unsignedInteger('channel_id');
            $table->unsignedInteger('currency_id')->index('channel_currencies_currency_id_foreign');

            $table->primary(['channel_id', 'currency_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('channel_currencies');
    }
};
