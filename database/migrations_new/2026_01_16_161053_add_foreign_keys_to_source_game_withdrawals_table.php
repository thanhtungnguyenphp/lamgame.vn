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
        Schema::table('source_game_withdrawals', function (Blueprint $table) {
            $table->foreign(['seller_id'])->references(['id'])->on('source_game_sellers')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('source_game_withdrawals', function (Blueprint $table) {
            $table->dropForeign('source_game_withdrawals_seller_id_foreign');
        });
    }
};
