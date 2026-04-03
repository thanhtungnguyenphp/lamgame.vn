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
        Schema::table('cms_page_channels', function (Blueprint $table) {
            $table->foreign(['channel_id'])->references(['id'])->on('channels')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['cms_page_id'])->references(['id'])->on('cms_pages')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cms_page_channels', function (Blueprint $table) {
            $table->dropForeign('cms_page_channels_channel_id_foreign');
            $table->dropForeign('cms_page_channels_cms_page_id_foreign');
        });
    }
};
