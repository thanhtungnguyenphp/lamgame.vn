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
        Schema::create('tax_categories_tax_rates', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('tax_category_id');
            $table->unsignedInteger('tax_rate_id')->index('tax_categories_tax_rates_tax_rate_id_foreign');
            $table->timestamps();

            $table->unique(['tax_category_id', 'tax_rate_id'], 'tax_map_index_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tax_categories_tax_rates');
    }
};
