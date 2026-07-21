<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->boolean('is_employer')->default(false)->after('is_verified');
            $table->unsignedBigInteger('company_id')->nullable()->after('is_employer');
            $table->enum('employer_status', ['pending', 'active', 'suspended'])->nullable()->after('company_id');
        });
    }

    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn(['is_employer', 'company_id', 'employer_status']);
        });
    }
};
