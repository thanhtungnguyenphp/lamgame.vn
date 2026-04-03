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
        Schema::table('products', function (Blueprint $table) {
            $table->foreign(['attribute_family_id'])->references(['id'])->on('attribute_families')->onUpdate('no action')->onDelete('restrict');
            $table->foreign(['company_id'])->references(['id'])->on('companies')->onUpdate('no action')->onDelete('set null');
            $table->foreign(['created_by_admin_id'])->references(['id'])->on('admins')->onUpdate('no action')->onDelete('set null');
            $table->foreign(['parent_id'])->references(['id'])->on('products')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['seller_id'])->references(['id'])->on('source_game_sellers')->onUpdate('no action')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign('products_attribute_family_id_foreign');
            $table->dropForeign('products_company_id_foreign');
            $table->dropForeign('products_created_by_admin_id_foreign');
            $table->dropForeign('products_parent_id_foreign');
            $table->dropForeign('products_seller_id_foreign');
        });
    }
};
