<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('source_game_reviews', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('customer_id');
            $table->tinyInteger('rating')->unsigned(); // 1-5
            $table->string('title', 255)->nullable();
            $table->text('content');
            $table->text('pros')->nullable();
            $table->text('cons')->nullable();
            $table->enum('status', ['pending', 'published', 'hidden'])->default('pending');
            $table->boolean('is_verified_purchase')->default(false);
            $table->unsignedInteger('helpful_count')->default(0);
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->unique(['product_id', 'customer_id']); // 1 review per product per customer
            $table->index(['product_id', 'status', 'created_at']);
        });

        // Add avg_rating and review_count cache columns to products
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('avg_rating', 2, 1)->default(0)->after('status');
            $table->unsignedInteger('review_count')->default(0)->after('avg_rating');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('source_game_reviews');
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['avg_rating', 'review_count']);
        });
    }
};
