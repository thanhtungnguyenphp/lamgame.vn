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
        Schema::table('admin_user_info', function (Blueprint $table) {
            $table->foreign(['admin_id'])->references(['id'])->on('admins')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admin_user_info', function (Blueprint $table) {
            $table->dropForeign('admin_user_info_admin_id_foreign');
        });
    }
};
