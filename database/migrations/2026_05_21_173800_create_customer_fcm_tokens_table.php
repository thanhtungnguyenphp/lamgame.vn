<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer_fcm_tokens', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('customer_id');
            $table->string('token', 500)->unique();
            $table->string('platform', 10)->default('web'); // web, android, ios
            $table->timestamps();

            $table->foreign('customer_id')->references('id')->on('customers')->cascadeOnDelete();
            $table->index('customer_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_fcm_tokens');
    }
};
