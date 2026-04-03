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
        Schema::create('product_downloadable_links', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('product_id')->index('product_downloadable_links_product_id_foreign');
            $table->string('url')->nullable();
            $table->string('file')->nullable();
            $table->string('file_name')->nullable();
            $table->string('type');
            $table->decimal('price', 12, 4)->default(0);
            $table->string('sample_url')->nullable();
            $table->string('sample_file')->nullable();
            $table->string('sample_file_name')->nullable();
            $table->string('sample_type')->nullable();
            $table->integer('downloads')->default(0);
            $table->integer('sort_order')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_downloadable_links');
    }
};
