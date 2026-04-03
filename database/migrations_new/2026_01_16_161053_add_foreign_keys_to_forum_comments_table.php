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
        Schema::table('forum_comments', function (Blueprint $table) {
            $table->foreign(['parent_id'])->references(['id'])->on('forum_comments')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['post_id'])->references(['id'])->on('forum_posts')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('forum_comments', function (Blueprint $table) {
            $table->dropForeign('forum_comments_parent_id_foreign');
            $table->dropForeign('forum_comments_post_id_foreign');
        });
    }
};
