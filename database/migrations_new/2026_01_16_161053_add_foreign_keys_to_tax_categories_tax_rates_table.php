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
        Schema::table('tax_categories_tax_rates', function (Blueprint $table) {
            $table->foreign(['tax_category_id'])->references(['id'])->on('tax_categories')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['tax_rate_id'])->references(['id'])->on('tax_rates')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tax_categories_tax_rates', function (Blueprint $table) {
            $table->dropForeign('tax_categories_tax_rates_tax_category_id_foreign');
            $table->dropForeign('tax_categories_tax_rates_tax_rate_id_foreign');
        });
    }
};
