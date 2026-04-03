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
        Schema::table('theme_customizations', function (Blueprint $table) {
            $table->foreign(['channel_id'])->references(['id'])->on('channels')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('theme_customizations', function (Blueprint $table) {
            $table->dropForeign('theme_customizations_channel_id_foreign');
        });
    }
};
