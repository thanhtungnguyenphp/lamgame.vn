<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('forum_polls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('forum_post_id')->constrained('forum_posts')->cascadeOnDelete();
            $table->string('question');
            $table->boolean('allow_multiple')->default(false);
            $table->boolean('is_anonymous')->default(false);
            $table->timestamp('expires_at')->nullable();
            $table->unsignedInteger('total_votes')->default(0);
            $table->timestamps();
        });

        Schema::create('forum_poll_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('forum_poll_id')->constrained('forum_polls')->cascadeOnDelete();
            $table->string('text');
            $table->unsignedInteger('vote_count')->default(0);
            $table->unsignedSmallInteger('sort_order')->default(0);
        });

        Schema::create('forum_poll_votes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('forum_poll_id')->constrained('forum_polls')->cascadeOnDelete();
            $table->foreignId('forum_poll_option_id')->constrained('forum_poll_options')->cascadeOnDelete();
            $table->unsignedBigInteger('customer_id');
            $table->timestamp('created_at')->useCurrent();
            $table->unique(['forum_poll_id', 'forum_poll_option_id', 'customer_id'], 'poll_vote_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('forum_poll_votes');
        Schema::dropIfExists('forum_poll_options');
        Schema::dropIfExists('forum_polls');
    }
};
