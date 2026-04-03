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
        Schema::table('blogs', function (Blueprint $table) {
            $table->integer('views')->default(0)->after('description');
            $table->integer('shares')->default(0)->after('views');
            // Status column already exists, skip it
            $table->index(['status', 'created_at']);
            $table->index(['views']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blogs', function (Blueprint $table) {
            $table->dropIndex(['status', 'created_at']);
            $table->dropIndex(['views']);
            $table->dropColumn(['views', 'shares']);
        });
    }
};
