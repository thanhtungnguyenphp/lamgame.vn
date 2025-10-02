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
            $table->unsignedInteger('created_by_admin_id')->nullable()->after('attribute_family_id');
            $table->foreign('created_by_admin_id')->references('id')->on('admins')->onDelete('set null');
            $table->index(['created_by_admin_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['created_by_admin_id']);
            $table->dropIndex(['created_by_admin_id', 'created_at']);
            $table->dropColumn('created_by_admin_id');
        });
    }
};
