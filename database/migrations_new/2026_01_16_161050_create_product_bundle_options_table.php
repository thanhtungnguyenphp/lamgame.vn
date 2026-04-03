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
        Schema::create('product_bundle_options', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('product_id')->index('product_bundle_options_product_id_foreign');
            $table->string('type');
            $table->boolean('is_required')->default(true);
            $table->integer('sort_order')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_bundle_options');
    }
};
