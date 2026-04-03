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
        Schema::create('channel_locales', function (Blueprint $table) {
            $table->unsignedInteger('channel_id');
            $table->unsignedInteger('locale_id')->index('channel_locales_locale_id_foreign');

            $table->primary(['channel_id', 'locale_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('channel_locales');
    }
};
