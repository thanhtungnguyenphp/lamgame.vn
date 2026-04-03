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
            $table->id();
            $table->foreignId('post_id')->constrained('forum_posts')->onDelete('cascade');
            $table->foreignId('parent_id')->nullable()->constrained('forum_comments')->onDelete('cascade'); // For nested comments
            
            $table->text('content');
            
            // Author info
            $table->string('author_name');
            $table->string('author_email')->nullable();
            $table->string('author_avatar')->nullable();
            $table->string('author_website')->nullable();
            
            // Status and moderation
            $table->enum('status', ['published', 'pending', 'hidden', 'spam'])->default('published');
            
            // Statistics
            $table->integer('likes_count')->default(0);
            $table->integer('dislikes_count')->default(0);
            $table->integer('replies_count')->default(0);
            
            // Additional fields
            $table->json('metadata')->nullable(); // For storing additional data like mentions, attachments, etc.
            $table->ipAddress('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            
            $table->timestamps();

            // Indexes
            $table->index(['post_id', 'status', 'created_at']);
            $table->index(['parent_id', 'created_at']);
            $table->index(['author_name', 'created_at']);
            $table->index('status');
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
