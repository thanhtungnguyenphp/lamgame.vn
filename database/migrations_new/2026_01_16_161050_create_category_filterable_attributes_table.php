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
        Schema::create('category_filterable_attributes', function (Blueprint $table) {
            $table->unsignedInteger('category_id')->index('category_filterable_attributes_category_id_foreign');
            $table->unsignedInteger('attribute_id')->index('category_filterable_attributes_attribute_id_foreign');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_filterable_attributes');
    }
};
