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
            $table->bigIncrements('id');
            $table->string('application_code')->nullable()->index();
            $table->unsignedInteger('job_id');
            $table->unsignedInteger('applicant_user_id')->nullable()->index('job_applications_applicant_user_id_foreign');
            $table->string('applicant_name');
            $table->string('applicant_email');
            $table->string('applicant_phone')->nullable();
            $table->text('cover_letter')->nullable();
            $table->string('resume_file_path')->nullable();
            $table->json('additional_info')->nullable();
            $table->enum('status', ['pending', 'reviewed', 'shortlisted', 'rejected', 'accepted'])->default('pending');
            $table->text('employer_notes')->nullable();
            $table->timestamp('applied_at')->index();
            $table->timestamps();

            $table->unique(['application_code']);
            $table->index(['job_id', 'status']);
            $table->unique(['job_id', 'applicant_user_id'], 'unique_user_job_application');
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
