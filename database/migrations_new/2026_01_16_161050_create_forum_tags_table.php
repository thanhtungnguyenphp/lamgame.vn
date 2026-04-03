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
        Schema::create('forum_tags', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('slug')->index();
            $table->string('description')->nullable();
            $table->string('color', 7)->default('#6b7280');
            $table->integer('posts_count')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->timestamps();

            $table->index(['is_featured', 'posts_count']);
            $table->unique(['slug']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('forum_tags');
    }
};
