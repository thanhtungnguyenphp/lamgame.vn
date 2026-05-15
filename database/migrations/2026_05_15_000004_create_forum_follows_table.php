<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('forum_follows', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('follower_id');
            $table->string('followable_type', 20); // user, category, tag
            $table->string('followable_id', 50);
            $table->timestamp('created_at')->useCurrent();
            $table->unique(['follower_id', 'followable_type', 'followable_id'], 'forum_follow_unique');
            $table->index('followable_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('forum_follows');
    }
};
