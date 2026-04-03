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
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('sku')->unique();
            $table->string('type');
            $table->unsignedInteger('parent_id')->nullable()->index('products_parent_id_foreign');
            $table->unsignedBigInteger('seller_id')->nullable()->index();
            $table->boolean('pending_review')->default(false)->index();
            $table->text('rejection_reason')->nullable();
            $table->unsignedInteger('attribute_family_id')->nullable()->index('products_attribute_family_id_foreign');
            $table->unsignedInteger('created_by_admin_id')->nullable();
            $table->unsignedBigInteger('company_id')->nullable()->index();
            $table->json('additional')->nullable();
            $table->timestamps();

            $table->index(['created_by_admin_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
