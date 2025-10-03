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
            // Drop the foreign key constraint first
            $table->dropForeign(['applicant_user_id']);
            
            // Drop the unique constraint that includes applicant_user_id
            $table->dropUnique(['job_id', 'applicant_user_id']);
            
            // Drop the index that includes applicant_user_id
            $table->dropIndex(['applicant_user_id', 'status']);
            
            // Make applicant_user_id nullable to support guest applications
            $table->unsignedInteger('applicant_user_id')->nullable()->change();
            
            // Add the foreign key back with nullable support
            $table->foreign('applicant_user_id')
                  ->references('id')
                  ->on('customers')
                  ->onDelete('set null'); // Set to null if customer is deleted
            
            // Create new unique constraint for logged-in users only
            // This prevents duplicate applications from the same user for the same job
            // But allows multiple guest applications (since applicant_user_id will be null)
            $table->unique(['job_id', 'applicant_user_id'], 'unique_user_job_application');
            
            // Recreate index for performance
            $table->index(['applicant_user_id', 'status']);
            
            // Add index for email to prevent spam (optional)
            $table->index(['applicant_email', 'job_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_applications', function (Blueprint $table) {
            // Drop the indexes and constraints we added
            $table->dropForeign(['applicant_user_id']);
            $table->dropUnique('unique_user_job_application');
            $table->dropIndex(['applicant_user_id', 'status']);
            $table->dropIndex(['applicant_email', 'job_id']);
            
            // Revert applicant_user_id to NOT NULL
            $table->unsignedInteger('applicant_user_id')->nullable(false)->change();
            
            // Restore original constraints
            $table->foreign('applicant_user_id')
                  ->references('id')
                  ->on('customers')
                  ->onDelete('cascade');
            
            $table->unique(['job_id', 'applicant_user_id']);
            $table->index(['applicant_user_id', 'status']);
        });
    }
};
