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
        Schema::table('job_import_logs', function (Blueprint $table) {
            $table->foreign(['user_id'])->references(['id'])->on('admins')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_import_logs', function (Blueprint $table) {
            $table->dropForeign('job_import_logs_user_id_foreign');
        });
    }
};
