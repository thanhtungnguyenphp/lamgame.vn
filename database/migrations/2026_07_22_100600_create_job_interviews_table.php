<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_interviews', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('application_id');
            $table->unsignedInteger('employer_id'); // customer id (employer)
            $table->unsignedInteger('candidate_id'); // customer id (applicant)
            $table->dateTime('scheduled_at');
            $table->integer('duration_minutes')->default(60);
            $table->enum('type', ['online', 'onsite'])->default('online');
            $table->string('meeting_url')->nullable();
            $table->string('location')->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['proposed', 'confirmed', 'rescheduled', 'completed', 'cancelled'])->default('proposed');
            $table->timestamps();

            $table->foreign('application_id')->references('id')->on('job_applications')->onDelete('cascade');
            $table->foreign('employer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('candidate_id')->references('id')->on('customers')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_interviews');
    }
};
