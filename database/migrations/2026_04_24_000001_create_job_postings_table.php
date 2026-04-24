<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_postings', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->text('short_description')->nullable();
            $table->string('job_type', 50)->nullable()->index();         // full-time, part-time, contract, freelance, intern
            $table->string('experience_level', 50)->nullable()->index(); // intern, fresher, junior, mid, senior, lead, manager
            $table->string('salary_range')->nullable();
            $table->decimal('salary_min', 15, 2)->nullable();
            $table->decimal('salary_max', 15, 2)->nullable();
            $table->string('salary_currency', 10)->default('VND');
            $table->string('location')->nullable()->index();
            $table->boolean('is_remote')->default(false);
            $table->string('education_level', 50)->nullable();
            $table->string('english_level', 50)->nullable();
            $table->string('company_size', 50)->nullable();

            // Company info
            $table->unsignedBigInteger('company_id')->nullable()->index();
            $table->string('company_name')->nullable();
            $table->string('company_logo')->nullable();

            // Contact
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('application_method')->nullable();
            $table->string('application_url')->nullable();

            // Status & visibility
            $table->enum('status', ['draft', 'active', 'paused', 'expired', 'archived'])->default('draft')->index();
            $table->boolean('is_featured')->default(false)->index();
            $table->boolean('is_urgent')->default(false);
            $table->date('application_deadline')->nullable()->index();
            $table->timestamp('published_at')->nullable();

            // SEO
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();

            // Stats
            $table->unsignedInteger('view_count')->default(0);
            $table->unsignedInteger('application_count')->default(0);
            $table->unsignedInteger('click_count')->default(0);

            // Owner
            $table->unsignedInteger('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('created_by')->references('id')->on('admins')->nullOnDelete();
            $table->index(['status', 'application_deadline']);
            $table->index(['status', 'is_featured', 'created_at']);
            $table->fulltext(['title', 'description']);
        });

        // Pivot: skills
        Schema::create('job_posting_skills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_posting_id')->constrained()->cascadeOnDelete();
            $table->string('skill_name');
            $table->timestamps();

            $table->unique(['job_posting_id', 'skill_name']);
            $table->index('skill_name');
        });

        // Pivot: benefits
        Schema::create('job_posting_benefits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_posting_id')->constrained()->cascadeOnDelete();
            $table->string('benefit_name');
            $table->timestamps();

            $table->unique(['job_posting_id', 'benefit_name']);
        });

        // Update job_applications to reference new table
        Schema::table('job_applications', function (Blueprint $table) {
            $table->unsignedBigInteger('job_posting_id')->nullable()->after('job_id')->index();
            $table->foreign('job_posting_id')->references('id')->on('job_postings')->cascadeOnDelete();
        });

        // Update application_activities (no FK change needed, references job_applications)
    }

    public function down(): void
    {
        Schema::table('job_applications', function (Blueprint $table) {
            $table->dropForeign(['job_posting_id']);
            $table->dropColumn('job_posting_id');
        });
        Schema::dropIfExists('job_posting_benefits');
        Schema::dropIfExists('job_posting_skills');
        Schema::dropIfExists('job_postings');
    }
};
