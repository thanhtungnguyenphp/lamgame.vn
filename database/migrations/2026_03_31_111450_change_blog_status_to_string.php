<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Step 1: Add new string column
        Schema::table('blogs', function (Blueprint $table) {
            $table->string('status_new', 20)->default('draft')->after('status');
        });

        // Step 2: Migrate data
        DB::statement("UPDATE blogs SET status_new = CASE
            WHEN status = 1 AND published_at <= NOW() THEN 'published'
            WHEN status = 1 AND published_at > NOW() THEN 'scheduled'
            WHEN status = 0 THEN 'draft'
            ELSE 'draft'
        END");

        // Step 3: Drop old, rename new
        Schema::table('blogs', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('blogs', function (Blueprint $table) {
            $table->renameColumn('status_new', 'status');
        });

        // Step 4: Add index
        Schema::table('blogs', function (Blueprint $table) {
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::table('blogs', function (Blueprint $table) {
            $table->dropIndex(['status']);
        });

        Schema::table('blogs', function (Blueprint $table) {
            $table->boolean('status_old')->default(0)->after('status');
        });

        DB::statement("UPDATE blogs SET status_old = CASE
            WHEN status = 'published' THEN 1
            ELSE 0
        END");

        Schema::table('blogs', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('blogs', function (Blueprint $table) {
            $table->renameColumn('status_old', 'status');
        });
    }
};
