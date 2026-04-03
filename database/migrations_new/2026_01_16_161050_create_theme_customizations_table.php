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
        Schema::create('theme_customizations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('theme_code')->nullable()->default('default');
            $table->string('type');
            $table->string('name');
            $table->integer('sort_order');
            $table->boolean('status')->default(false);
            $table->unsignedInteger('channel_id')->index('theme_customizations_channel_id_foreign');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('theme_customizations');
    }
};
