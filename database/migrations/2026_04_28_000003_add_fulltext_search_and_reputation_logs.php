<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // FULLTEXT index for search
        DB::statement('ALTER TABLE forum_posts ADD FULLTEXT INDEX ft_forum_posts_search (title, content)');

        // Reputation log table
        Schema::create('forum_reputation_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('customer_id');
            $table->smallInteger('points');
            $table->string('action', 50); // post_created, comment_created, vote_received, best_answer, post_removed
            $table->nullableMorphs('reference'); // post/comment that triggered this
            $table->timestamps();

            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->index(['customer_id', 'created_at']);
        });
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE forum_posts DROP INDEX ft_forum_posts_search');
        Schema::dropIfExists('forum_reputation_logs');
    }
};
