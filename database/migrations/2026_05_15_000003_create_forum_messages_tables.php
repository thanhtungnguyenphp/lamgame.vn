<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('forum_conversations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('participant_1');
            $table->unsignedBigInteger('participant_2');
            $table->timestamp('last_message_at')->nullable();
            $table->timestamps();
            $table->unique(['participant_1', 'participant_2']);
            $table->index('participant_1');
            $table->index('participant_2');
        });

        Schema::create('forum_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversation_id')->constrained('forum_conversations')->cascadeOnDelete();
            $table->unsignedBigInteger('sender_id');
            $table->text('content');
            $table->timestamp('read_at')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::create('forum_blocks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('blocker_id');
            $table->unsignedBigInteger('blocked_id');
            $table->timestamp('created_at')->useCurrent();
            $table->unique(['blocker_id', 'blocked_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('forum_messages');
        Schema::dropIfExists('forum_conversations');
        Schema::dropIfExists('forum_blocks');
    }
};
