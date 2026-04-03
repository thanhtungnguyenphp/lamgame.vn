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
        Schema::create('product_attribute_values', function (Blueprint $table) {
            $table->increments('id');
            $table->string('locale')->nullable();
            $table->string('channel')->nullable();
            $table->text('text_value')->nullable();
            $table->boolean('boolean_value')->nullable();
            $table->integer('integer_value')->nullable();
            $table->decimal('float_value', 12, 4)->nullable();
            $table->dateTime('datetime_value')->nullable();
            $table->date('date_value')->nullable();
            $table->json('json_value')->nullable();
            $table->unsignedInteger('product_id')->index('product_attribute_values_product_id_foreign');
            $table->unsignedInteger('attribute_id')->index('product_attribute_values_attribute_id_foreign');
            $table->string('unique_id')->nullable()->unique();

            $table->unique(['channel', 'locale', 'attribute_id', 'product_id'], 'chanel_locale_attribute_value_index_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_attribute_values');
    }
};
