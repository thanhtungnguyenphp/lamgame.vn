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
        Schema::create('blogs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('slug');
            $table->string('short_description');
            $table->longText('description');
            $table->integer('views')->default(0)->index();
            $table->integer('shares')->default(0);
            $table->unsignedBigInteger('channels');
            $table->unsignedBigInteger('default_category');
            $table->string('categorys')->nullable();
            $table->string('tags');
            $table->string('author');
            $table->unsignedBigInteger('author_id')->default(0);
            $table->string('src');
            $table->string('locale');
            $table->boolean('status');
            $table->boolean('allow_comments');
            $table->string('meta_title');
            $table->string('meta_description');
            $table->string('meta_keywords');
            $table->dateTime('published_at');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blogs');
    }
};
