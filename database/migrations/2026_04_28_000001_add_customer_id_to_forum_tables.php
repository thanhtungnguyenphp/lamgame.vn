<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Add customer_id to forum_posts
        Schema::table('forum_posts', function (Blueprint $table) {
            $table->unsignedInteger('customer_id')->nullable()->after('id');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('set null');
            $table->index('customer_id');
        });

        // Add customer_id to forum_comments
        Schema::table('forum_comments', function (Blueprint $table) {
            $table->unsignedInteger('customer_id')->nullable()->after('post_id');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('set null');
            $table->index('customer_id');
        });

        // Add customer_id to forum_votes
        Schema::table('forum_votes', function (Blueprint $table) {
            $table->unsignedInteger('customer_id')->nullable()->after('id');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('set null');
            $table->index('customer_id');
        });

        // Data migration: link existing posts/comments to customers by email
        DB::statement("
            UPDATE forum_posts fp
            INNER JOIN customers c ON LOWER(fp.author_email) = LOWER(c.email)
            SET fp.customer_id = c.id
            WHERE fp.author_email IS NOT NULL AND fp.customer_id IS NULL
        ");

        DB::statement("
            UPDATE forum_comments fc
            INNER JOIN customers c ON LOWER(fc.author_email) = LOWER(c.email)
            SET fc.customer_id = c.id
            WHERE fc.author_email IS NOT NULL AND fc.customer_id IS NULL
        ");

        DB::statement("
            UPDATE forum_votes fv
            INNER JOIN customers c ON fv.voter_identifier = CAST(c.id AS CHAR)
            SET fv.customer_id = c.id
            WHERE fv.customer_id IS NULL
        ");
    }

    public function down(): void
    {
        Schema::table('forum_posts', function (Blueprint $table) {
            $table->dropForeign(['customer_id']);
            $table->dropColumn('customer_id');
        });

        Schema::table('forum_comments', function (Blueprint $table) {
            $table->dropForeign(['customer_id']);
            $table->dropColumn('customer_id');
        });

        Schema::table('forum_votes', function (Blueprint $table) {
            $table->dropForeign(['customer_id']);
            $table->dropColumn('customer_id');
        });
    }
};
