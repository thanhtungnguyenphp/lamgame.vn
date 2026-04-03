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
            $table->bigIncrements('id');
            $table->string('title');
            $table->string('slug')->index();
            $table->text('content');
            $table->integer('views')->default(0)->index();
            $table->integer('hot_score')->default(0);
            $table->text('excerpt')->nullable();
            $table->enum('type', ['discussion', 'idea', 'question', 'showcase', 'job', 'review'])->default('discussion');
            $table->string('author_name');
            $table->string('author_email')->nullable();
            $table->string('author_avatar')->nullable();
            $table->unsignedBigInteger('category_id');
            $table->enum('status', ['draft', 'published', 'hidden', 'locked'])->default('published');
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_sticky')->default(false);
            $table->integer('views_count')->default(0);
            $table->integer('comments_count')->default(0);
            $table->integer('likes_count')->default(0);
            $table->integer('dislikes_count')->default(0);
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->json('edit_history')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('last_comment_at')->nullable()->index();
            $table->string('last_comment_author')->nullable();
            $table->timestamps();

            $table->index(['author_name', 'created_at']);
            $table->index(['category_id', 'status']);
            $table->index(['hot_score', 'created_at']);
            $table->unique(['slug']);
            $table->index(['status', 'is_featured', 'created_at']);
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
