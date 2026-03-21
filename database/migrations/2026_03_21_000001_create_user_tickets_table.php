<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_id', 50)->unique();
            $table->string('fcm_token', 500);
            $table->json('numbers');
            $table->string('region', 20);
            $table->string('province_code', 10)->nullable();
            $table->date('draw_date');
            $table->enum('status', ['pending', 'won', 'lost'])->default('pending');
            $table->json('matched_prizes')->nullable();
            $table->timestamp('notified_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'draw_date']);
            $table->index('fcm_token');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_tickets');
    }
};
