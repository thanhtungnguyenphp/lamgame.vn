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
            $table->bigIncrements('id');
            $table->string('voteable_type');
            $table->unsignedBigInteger('voteable_id');
            $table->string('voter_identifier')->index();
            $table->enum('vote_type', ['like', 'dislike']);
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();

            $table->unique(['voteable_type', 'voteable_id', 'voter_identifier'], 'forum_votes_unique_vote');
            $table->index(['voteable_type', 'voteable_id']);
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
