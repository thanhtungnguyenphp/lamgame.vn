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
        Schema::create('forum_votes', function (Blueprint $table) {
            $table->id();
            $table->morphs('voteable'); // Can vote on posts or comments
            $table->string('voter_identifier'); // IP, email, or user ID
            $table->enum('vote_type', ['like', 'dislike']);
            $table->ipAddress('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();

            $table->unique(['voteable_type', 'voteable_id', 'voter_identifier'], 'forum_votes_unique_vote');
            $table->index('voter_identifier');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('forum_votes');
    }
};
