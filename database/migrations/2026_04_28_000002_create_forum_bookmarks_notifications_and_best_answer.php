<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Bookmark table
        Schema::create('forum_bookmarks', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('customer_id');
            $table->unsignedBigInteger('post_id');
            $table->timestamps();

            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('post_id')->references('id')->on('forum_posts')->onDelete('cascade');
            $table->unique(['customer_id', 'post_id']);
        });

        // Pin best answer
        Schema::table('forum_comments', function (Blueprint $table) {
            $table->boolean('is_best_answer')->default(false)->after('status');
        });

        // Notification table
        Schema::create('forum_notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('customer_id');
            $table->string('type', 50); // reply_post, reply_comment, vote, mention, best_answer
            $table->string('title');
            $table->text('body')->nullable();
            $table->string('url')->nullable();
            $table->json('data')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->index(['customer_id', 'read_at', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('forum_notifications');
        Schema::dropIfExists('forum_bookmarks');
        Schema::table('forum_comments', function (Blueprint $table) {
            $table->dropColumn('is_best_answer');
        });
    }
};
