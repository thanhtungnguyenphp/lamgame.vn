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
        Schema::create('product_review_attachments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('review_id')->index('product_review_images_review_id_foreign');
            $table->string('type')->default('image');
            $table->string('mime_type')->nullable();
            $table->string('path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_review_attachments');
    }
};
