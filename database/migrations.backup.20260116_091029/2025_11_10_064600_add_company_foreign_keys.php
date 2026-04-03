<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Add foreign keys after all tables are created to avoid circular dependency
     */
    public function up(): void
    {
        // Add foreign key from companies to admins
        Schema::table('companies', function (Blueprint $table) {
            $table->foreign('created_by_admin_id')
                ->references('id')
                ->on('admins')
                ->onDelete('set null');
        });

        // Add foreign key from admins to companies
        Schema::table('admins', function (Blueprint $table) {
            $table->foreign('company_id')
                ->references('id')
                ->on('companies')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropForeign(['created_by_admin_id']);
        });

        Schema::table('admins', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
        });
    }
};
