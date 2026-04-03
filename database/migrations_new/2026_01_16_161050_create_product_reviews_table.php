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
        Schema::create('product_reviews', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->default('');
            $table->string('title');
            $table->integer('rating');
            $table->text('comment')->nullable();
            $table->string('status');
            $table->unsignedInteger('product_id')->index('product_reviews_product_id_foreign');
            $table->integer('customer_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_reviews');
    }
};
