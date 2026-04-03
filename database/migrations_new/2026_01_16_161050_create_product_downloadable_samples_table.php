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
        Schema::create('product_downloadable_samples', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('product_id')->index('product_downloadable_samples_product_id_foreign');
            $table->string('url')->nullable();
            $table->string('file')->nullable();
            $table->string('file_name')->nullable();
            $table->string('type');
            $table->integer('sort_order')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_downloadable_samples');
    }
};
