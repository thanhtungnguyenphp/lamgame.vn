<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_crawl_logs', function (Blueprint $table) {
            $table->id();
            $table->string('source', 50)->index();          // 'topdev', 'itviec'
            $table->string('source_id', 100)->nullable();    // ID trên site gốc
            $table->string('source_url', 500);               // URL gốc
            $table->foreignId('job_posting_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('status', ['crawled', 'created', 'duplicate', 'failed'])->default('crawled');
            $table->json('raw_data')->nullable();
            $table->text('error_message')->nullable();
            $table->unsignedInteger('response_time_ms')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->unique(['source', 'source_id']);
            $table->index('status');
        });

        // Thêm cột crawl tracking vào job_postings
        Schema::table('job_postings', function (Blueprint $table) {
            $table->string('crawl_source', 50)->nullable()->after('created_by');
            $table->string('crawl_source_id', 100)->nullable()->after('crawl_source');
            $table->string('crawl_source_url', 500)->nullable()->after('crawl_source_id');

            $table->index('crawl_source');
        });
    }

    public function down(): void
    {
        Schema::table('job_postings', function (Blueprint $table) {
            $table->dropIndex(['crawl_source']);
            $table->dropColumn(['crawl_source', 'crawl_source_id', 'crawl_source_url']);
        });

        Schema::dropIfExists('job_crawl_logs');
    }
};
