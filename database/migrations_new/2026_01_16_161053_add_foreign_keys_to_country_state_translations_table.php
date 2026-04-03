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
        Schema::table('country_state_translations', function (Blueprint $table) {
            $table->foreign(['country_state_id'])->references(['id'])->on('country_states')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('country_state_translations', function (Blueprint $table) {
            $table->dropForeign('country_state_translations_country_state_id_foreign');
        });
    }
};
