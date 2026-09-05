<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hire_requests', function (Blueprint $table) {
            $table->string('country', 100)->nullable()->after('company');
            $table->string('service_package', 50)->nullable()->after('project_type');
            $table->string('source', 50)->nullable()->after('service_package');
            $table->index(['source', 'service_package']);
        });
    }

    public function down(): void
    {
        Schema::table('hire_requests', function (Blueprint $table) {
            $table->dropIndex(['source', 'service_package']);
            $table->dropColumn(['country', 'service_package', 'source']);
        });
    }
};
