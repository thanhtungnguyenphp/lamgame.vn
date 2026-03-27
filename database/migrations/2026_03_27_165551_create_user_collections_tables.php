<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_collections', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('customer_id');
            $table->string('name');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->boolean('is_public')->default(true);
            $table->timestamps();

            $table->index('customer_id');
            $table->unique(['customer_id', 'slug']);
        });

        Schema::create('collection_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('collection_id')->constrained('user_collections')->cascadeOnDelete();
            $table->unsignedInteger('product_id');
            $table->timestamp('created_at')->useCurrent();

            $table->unique(['collection_id', 'product_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('collection_items');
        Schema::dropIfExists('user_collections');
    }
};
