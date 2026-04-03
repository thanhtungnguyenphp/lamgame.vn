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
        Schema::create('customer_social_accounts', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('customer_id')->index('customer_social_accounts_customer_id_foreign');
            $table->string('provider_name')->nullable();
            $table->string('provider_id')->nullable()->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_social_accounts');
    }
};
