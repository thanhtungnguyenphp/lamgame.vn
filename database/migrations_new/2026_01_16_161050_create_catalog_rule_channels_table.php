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
        Schema::create('catalog_rule_channels', function (Blueprint $table) {
            $table->unsignedInteger('catalog_rule_id');
            $table->unsignedInteger('channel_id')->index('catalog_rule_channels_channel_id_foreign');

            $table->primary(['catalog_rule_id', 'channel_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('catalog_rule_channels');
    }
};
