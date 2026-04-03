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
        Schema::create('forum_comments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('post_id');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->text('content');
            $table->string('author_name');
            $table->string('author_email')->nullable();
            $table->string('author_avatar')->nullable();
            $table->string('author_website')->nullable();
            $table->enum('status', ['published', 'pending', 'hidden', 'spam'])->default('published')->index();
            $table->integer('likes_count')->default(0);
            $table->integer('dislikes_count')->default(0);
            $table->integer('replies_count')->default(0);
            $table->json('metadata')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();

            $table->index(['author_name', 'created_at']);
            $table->index(['parent_id', 'created_at']);
            $table->index(['post_id', 'status', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('forum_comments');
    }
};
