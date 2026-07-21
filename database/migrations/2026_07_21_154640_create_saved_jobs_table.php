<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('saved_jobs', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->unsignedBigInteger('job_posting_id');
            $table->timestamp('saved_at')->useCurrent();

            $table->unique(['user_id', 'job_posting_id']);
            $table->index('user_id');
            $table->index('job_posting_id');

            $table->foreign('user_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('job_posting_id')->references('id')->on('job_postings')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('saved_jobs');
    }
};
