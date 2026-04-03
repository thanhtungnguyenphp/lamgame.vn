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
        Schema::table('forum_reports', function (Blueprint $table) {
            $table->foreign(['reporter_id'])->references(['id'])->on('customers')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['reviewed_by'])->references(['id'])->on('admins')->onUpdate('no action')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('forum_reports', function (Blueprint $table) {
            $table->dropForeign('forum_reports_reporter_id_foreign');
            $table->dropForeign('forum_reports_reviewed_by_foreign');
        });
    }
};
