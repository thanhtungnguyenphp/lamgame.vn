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
        Schema::table('banner_translations', function (Blueprint $table) {
            $table->foreign(['banner_id'])->references(['id'])->on('banners')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('banner_translations', function (Blueprint $table) {
            $table->dropForeign('banner_translations_banner_id_foreign');
        });
    }
};
