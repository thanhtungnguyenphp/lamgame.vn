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
        Schema::create('cms_page_channels', function (Blueprint $table) {
            $table->unsignedInteger('cms_page_id');
            $table->unsignedInteger('channel_id')->index('cms_page_channels_channel_id_foreign');

            $table->unique(['cms_page_id', 'channel_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cms_page_channels');
    }
};
