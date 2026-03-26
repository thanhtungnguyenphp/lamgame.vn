<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_tickets', function (Blueprint $table) {
            $table->string('firebase_uid')->nullable()->after('id');
            $table->string('client_id')->nullable()->after('firebase_uid');
            $table->index('firebase_uid');
            $table->unique(['firebase_uid', 'client_id']);
        });
    }

    public function down(): void
    {
        Schema::table('user_tickets', function (Blueprint $table) {
            $table->dropUnique(['firebase_uid', 'client_id']);
            $table->dropIndex(['firebase_uid']);
            $table->dropColumn(['firebase_uid', 'client_id']);
        });
    }
};
