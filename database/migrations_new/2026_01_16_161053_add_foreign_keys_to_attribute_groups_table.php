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
        Schema::table('attribute_groups', function (Blueprint $table) {
            $table->foreign(['attribute_family_id'])->references(['id'])->on('attribute_families')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attribute_groups', function (Blueprint $table) {
            $table->dropForeign('attribute_groups_attribute_family_id_foreign');
        });
    }
};
