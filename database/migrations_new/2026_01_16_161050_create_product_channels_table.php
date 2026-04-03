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
        Schema::create('product_channels', function (Blueprint $table) {
            $table->unsignedInteger('product_id');
            $table->unsignedInteger('channel_id')->index('product_channels_channel_id_foreign');

            $table->unique(['product_id', 'channel_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_channels');
    }
};
