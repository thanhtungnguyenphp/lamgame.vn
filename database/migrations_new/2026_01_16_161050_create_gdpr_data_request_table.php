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
        Schema::create('gdpr_data_request', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('customer_id')->index('gdpr_data_request_customer_id_foreign');
            $table->string('email');
            $table->string('status');
            $table->string('type');
            $table->string('message', 500);
            $table->timestamp('revoked_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gdpr_data_request');
    }
};
