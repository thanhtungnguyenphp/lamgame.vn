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
        Schema::create('forum_posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('content');
            $table->text('excerpt')->nullable();
            $table->enum('type', ['discussion', 'idea', 'question', 'showcase', 'job', 'review'])->default('discussion');
            
            // Author info (for now using string, later can reference users table)
            $table->string('author_name');
            $table->string('author_email')->nullable();
            $table->string('author_avatar')->nullable();
            
            // Category
            $table->foreignId('category_id')->constrained('forum_categories')->onDelete('cascade');
            
            // Status and moderation
            $table->enum('status', ['draft', 'published', 'hidden', 'locked'])->default('published');
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_sticky')->default(false);
            
            // Statistics
            $table->integer('views_count')->default(0);
            $table->integer('comments_count')->default(0);
            $table->integer('likes_count')->default(0);
            $table->integer('dislikes_count')->default(0);
            
            // SEO
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            
            // Timestamps for last activity
            $table->timestamp('last_comment_at')->nullable();
            $table->string('last_comment_author')->nullable();
            
            $table->timestamps();

            // Indexes
            $table->index(['status', 'is_featured', 'created_at']);
            $table->index(['category_id', 'status']);
            $table->index('slug');
            $table->index(['author_name', 'created_at']);
            $table->index('last_comment_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('forum_posts');
    }
};
