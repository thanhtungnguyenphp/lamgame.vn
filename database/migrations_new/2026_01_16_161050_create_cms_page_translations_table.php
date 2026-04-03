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
        Schema::create('cms_page_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('page_title');
            $table->string('url_key');
            $table->longText('html_content')->nullable();
            $table->text('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->string('locale');
            $table->unsignedInteger('cms_page_id');

            $table->unique(['cms_page_id', 'url_key', 'locale']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cms_page_translations');
    }
};
