<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->boolean('is_verified')->default(false)->after('status');
            $table->dateTime('verified_at')->nullable()->after('is_verified');
            $table->string('verification_document')->nullable()->after('verified_at'); // file path
            $table->text('verification_notes')->nullable()->after('verification_document');
        });
    }

    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn(['is_verified', 'verified_at', 'verification_document', 'verification_notes']);
        });
    }
};
