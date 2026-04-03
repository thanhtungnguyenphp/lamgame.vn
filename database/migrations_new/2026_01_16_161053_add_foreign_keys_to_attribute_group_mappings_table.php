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
        Schema::table('attribute_group_mappings', function (Blueprint $table) {
            $table->foreign(['attribute_group_id'])->references(['id'])->on('attribute_groups')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['attribute_id'])->references(['id'])->on('attributes')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attribute_group_mappings', function (Blueprint $table) {
            $table->dropForeign('attribute_group_mappings_attribute_group_id_foreign');
            $table->dropForeign('attribute_group_mappings_attribute_id_foreign');
        });
    }
};
