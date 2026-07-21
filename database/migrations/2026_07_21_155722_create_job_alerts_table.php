<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_alerts', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->string('keywords')->nullable();
            $table->json('skills')->nullable();
            $table->string('location')->nullable();
            $table->enum('frequency', ['daily', 'weekly'])->default('daily');
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_sent_at')->nullable();
            $table->timestamps();

            $table->index('user_id');
            $table->index('is_active');

            $table->foreign('user_id')->references('id')->on('customers')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_alerts');
    }
};
