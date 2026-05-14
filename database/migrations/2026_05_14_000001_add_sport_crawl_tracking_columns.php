<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sport_matches', function (Blueprint $table) {
            $table->string('external_id', 50)->nullable()->after('id');
            $table->string('source', 30)->default('manual')->after('external_id');
            $table->timestamp('synced_at')->nullable()->after('source');
            $table->unique('external_id', 'idx_sport_matches_external_id');
        });

        Schema::table('teams', function (Blueprint $table) {
            $table->json('external_ids')->nullable()->after('founded');
        });

        Schema::table('leagues', function (Blueprint $table) {
            $table->json('external_ids')->nullable()->after('order');
        });

        Schema::table('sport_highlights', function (Blueprint $table) {
            $table->string('source_url', 500)->nullable()->after('view_count');
            $table->unique('source_url', 'idx_highlights_source_url');
        });

        Schema::table('sport_articles', function (Blueprint $table) {
            $table->string('source_url', 500)->nullable()->after('read_time_minutes');
            $table->string('source', 100)->nullable()->after('source_url');
            $table->unique('source_url', 'idx_articles_source_url');
        });

        Schema::create('sport_crawl_logs', function (Blueprint $table) {
            $table->id();
            $table->string('crawler', 50);
            $table->string('source', 100)->nullable();
            $table->enum('status', ['success', 'failed', 'partial']);
            $table->unsignedInteger('items_fetched')->default(0);
            $table->unsignedInteger('items_created')->default(0);
            $table->unsignedInteger('items_updated')->default(0);
            $table->unsignedInteger('items_skipped')->default(0);
            $table->text('error_message')->nullable();
            $table->unsignedInteger('duration_ms')->default(0);
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sport_crawl_logs');

        Schema::table('sport_articles', function (Blueprint $table) {
            $table->dropUnique('idx_articles_source_url');
            $table->dropColumn(['source_url', 'source']);
        });

        Schema::table('sport_highlights', function (Blueprint $table) {
            $table->dropUnique('idx_highlights_source_url');
            $table->dropColumn('source_url');
        });

        Schema::table('teams', function (Blueprint $table) {
            $table->dropColumn('external_ids');
        });

        Schema::table('leagues', function (Blueprint $table) {
            $table->dropColumn('external_ids');
        });

        Schema::table('sport_matches', function (Blueprint $table) {
            $table->dropUnique('idx_sport_matches_external_id');
            $table->dropColumn(['external_id', 'source', 'synced_at']);
        });
    }
};
