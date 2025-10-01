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
        Schema::create('job_applications', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('job_id'); // Reference to products table (job postings)
            $table->unsignedInteger('applicant_user_id'); // User applying for job
            $table->string('applicant_name');
            $table->string('applicant_email');
            $table->string('applicant_phone')->nullable();
            $table->text('cover_letter')->nullable();
            $table->string('resume_file_path')->nullable(); // Path to uploaded resume
            $table->json('additional_info')->nullable(); // Any additional form data
            $table->enum('status', ['pending', 'reviewed', 'shortlisted', 'rejected', 'accepted'])->default('pending');
            $table->text('employer_notes')->nullable(); // Notes from employer
            $table->timestamp('applied_at');
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('job_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('applicant_user_id')->references('id')->on('customers')->onDelete('cascade');
            
            // Prevent duplicate applications
            $table->unique(['job_id', 'applicant_user_id']);
            
            // Indexes for performance
            $table->index(['job_id', 'status']);
            $table->index(['applicant_user_id', 'status']);
            $table->index(['applied_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_applications');
    }
};
