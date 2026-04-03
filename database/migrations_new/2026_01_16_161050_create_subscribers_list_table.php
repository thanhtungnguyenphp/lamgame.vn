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
        Schema::create('subscribers_list', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email');
            $table->boolean('is_subscribed')->default(false);
            $table->string('token')->nullable();
            $table->unsignedInteger('customer_id')->nullable()->index('subscribers_list_customer_id_foreign');
            $table->unsignedInteger('channel_id')->index('subscribers_list_channel_id_foreign');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscribers_list');
    }
};
