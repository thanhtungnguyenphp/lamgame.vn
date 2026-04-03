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
        Schema::table('catalog_rule_channels', function (Blueprint $table) {
            $table->foreign(['catalog_rule_id'])->references(['id'])->on('catalog_rules')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['channel_id'])->references(['id'])->on('channels')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('catalog_rule_channels', function (Blueprint $table) {
            $table->dropForeign('catalog_rule_channels_catalog_rule_id_foreign');
            $table->dropForeign('catalog_rule_channels_channel_id_foreign');
        });
    }
};
