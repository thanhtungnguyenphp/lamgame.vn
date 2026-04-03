<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('job_applications', function (Blueprint $table) {
            // Check and drop foreign key if exists
            $foreignKeys = DB::select("
                SELECT CONSTRAINT_NAME 
                FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
                WHERE TABLE_SCHEMA = DATABASE() 
                AND TABLE_NAME = 'job_applications' 
                AND COLUMN_NAME = 'applicant_user_id'
                AND CONSTRAINT_NAME != 'PRIMARY'
            ");
            
            foreach ($foreignKeys as $fk) {
                if (strpos($fk->CONSTRAINT_NAME, 'foreign') !== false || strpos($fk->CONSTRAINT_NAME, 'fk') !== false) {
                    DB::statement("ALTER TABLE job_applications DROP FOREIGN KEY {$fk->CONSTRAINT_NAME}");
                }
            }
            
            // Check and drop unique constraint if exists
            $indexes = DB::select("
                SHOW INDEX FROM job_applications 
                WHERE Key_name = 'job_applications_job_id_applicant_user_id_unique'
            ");
            
            if (count($indexes) > 0) {
                $table->dropUnique(['job_id', 'applicant_user_id']);
            }
            
            // Check and drop index if exists
            $userStatusIndex = DB::select("
                SHOW INDEX FROM job_applications 
                WHERE Key_name LIKE '%applicant_user_id%status%'
            ");
            
            if (count($userStatusIndex) > 0) {
                foreach ($userStatusIndex as $idx) {
                    DB::statement("ALTER TABLE job_applications DROP INDEX {$idx->Key_name}");
                }
            }
        });
        
        // Make changes in separate statement
        Schema::table('job_applications', function (Blueprint $table) {
            // Make applicant_user_id nullable to support guest applications
            $table->unsignedInteger('applicant_user_id')->nullable()->change();
        });
        
        // Add constraints back
        Schema::table('job_applications', function (Blueprint $table) {
            // Add the foreign key back with nullable support
            $table->foreign('applicant_user_id')
                  ->references('id')
                  ->on('customers')
                  ->onDelete('set null');
            
            // Create new unique constraint for logged-in users only
            $table->unique(['job_id', 'applicant_user_id'], 'unique_user_job_application');
            
            // Recreate index for performance
            $table->index(['applicant_user_id', 'status']);
            
            // Add index for email to prevent spam
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
        });
        
        Schema::table('job_applications', function (Blueprint $table) {
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
