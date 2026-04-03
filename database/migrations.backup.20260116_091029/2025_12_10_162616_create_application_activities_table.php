<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('application_activities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('application_id');
            $table->string('activity_type', 50); // 'status_changed', 'note_added', 'email_sent', 'cv_downloaded'
            $table->text('description');
            $table->string('old_value')->nullable();
            $table->string('new_value')->nullable();
            $table->string('performed_by_type', 50)->nullable(); // 'admin', 'system', 'applicant'
            $table->unsignedInteger('performed_by_id')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamp('created_at')->useCurrent();
            
            $table->foreign('application_id')->references('id')->on('job_applications')->onDelete('cascade');
            $table->index(['application_id', 'activity_type']);
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('application_activities');
    }
};
