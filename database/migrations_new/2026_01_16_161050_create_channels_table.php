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
        Schema::create('channels', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code');
            $table->string('timezone')->nullable();
            $table->string('theme')->nullable();
            $table->string('hostname')->nullable();
            $table->string('logo')->nullable();
            $table->string('favicon')->nullable();
            $table->json('home_seo')->nullable();
            $table->boolean('is_maintenance_on')->default(false);
            $table->text('allowed_ips')->nullable();
            $table->unsignedInteger('root_category_id')->nullable()->index('channels_root_category_id_foreign');
            $table->unsignedInteger('default_locale_id')->index('channels_default_locale_id_foreign');
            $table->unsignedInteger('base_currency_id')->index('channels_base_currency_id_foreign');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('channels');
    }
};
