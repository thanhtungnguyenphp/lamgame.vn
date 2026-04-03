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
        Schema::create('product_super_attributes', function (Blueprint $table) {
            $table->unsignedInteger('product_id');
            $table->unsignedInteger('attribute_id')->index('product_super_attributes_attribute_id_foreign');

            $table->unique(['product_id', 'attribute_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_super_attributes');
    }
};
