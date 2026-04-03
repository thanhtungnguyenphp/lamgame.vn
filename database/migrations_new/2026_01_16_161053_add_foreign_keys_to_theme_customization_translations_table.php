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
        Schema::table('theme_customization_translations', function (Blueprint $table) {
            $table->foreign(['theme_customization_id'], 'theme_customization_id_foreign')->references(['id'])->on('theme_customizations')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('theme_customization_translations', function (Blueprint $table) {
            $table->dropForeign('theme_customization_id_foreign');
        });
    }
};
