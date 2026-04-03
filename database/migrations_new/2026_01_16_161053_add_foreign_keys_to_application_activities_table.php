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
        Schema::table('application_activities', function (Blueprint $table) {
            $table->foreign(['application_id'])->references(['id'])->on('job_applications')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('application_activities', function (Blueprint $table) {
            $table->dropForeign('application_activities_application_id_foreign');
        });
    }
};
