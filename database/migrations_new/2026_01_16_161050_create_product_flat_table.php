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
        Schema::create('product_flat', function (Blueprint $table) {
            $table->increments('id');
            $table->string('sku');
            $table->string('type')->nullable();
            $table->string('product_number')->nullable();
            $table->string('name')->nullable();
            $table->text('short_description')->nullable();
            $table->text('description')->nullable();
            $table->string('url_key')->nullable();
            $table->boolean('new')->nullable();
            $table->boolean('featured')->nullable();
            $table->boolean('status')->nullable();
            $table->text('meta_title')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->text('meta_description')->nullable();
            $table->decimal('price', 12, 4)->nullable();
            $table->decimal('special_price', 12, 4)->nullable();
            $table->date('special_price_from')->nullable();
            $table->date('special_price_to')->nullable();
            $table->decimal('weight', 12, 4)->nullable();
            $table->dateTime('created_at')->nullable();
            $table->string('locale')->nullable();
            $table->string('channel')->nullable();
            $table->unsignedInteger('attribute_family_id')->nullable()->index('product_flat_attribute_family_id_foreign');
            $table->unsignedInteger('product_id');
            $table->dateTime('updated_at')->nullable();
            $table->unsignedInteger('parent_id')->nullable()->index('product_flat_parent_id_foreign');
            $table->boolean('visible_individually')->nullable();

            $table->unique(['product_id', 'channel', 'locale'], 'product_flat_unique_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_flat');
    }
};
