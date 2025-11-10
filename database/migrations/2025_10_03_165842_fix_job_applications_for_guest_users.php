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
        Schema::table('job_applications', function (Blueprint $table) {
            // Make applicant_user_id nullable to allow guest users
            $table->unsignedInteger('applicant_user_id')->nullable()->change();
            
            // Add unique constraint for logged-in users (prevent duplicate applications)
            // Only for non-null user_ids and same job_id
            $table->unique(['job_id', 'applicant_user_id'], 'unique_user_job_application');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_applications', function (Blueprint $table) {
            // Drop the unique constraint
            $table->dropUnique('unique_user_job_application');
            
            // Make applicant_user_id NOT NULL again
            $table->unsignedInteger('applicant_user_id')->nullable(false)->change();
        });
    }
};
