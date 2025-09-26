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
        Schema::table('forum_posts', function (Blueprint $table) {
            $table->integer('views')->default(0)->after('content');
            $table->integer('hot_score')->default(0)->after('views');
            $table->index(['hot_score', 'created_at']);
            $table->index(['views']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('forum_posts', function (Blueprint $table) {
            $table->dropIndex(['hot_score', 'created_at']);
            $table->dropIndex(['views']);
            $table->dropColumn(['views', 'hot_score']);
        });
    }
};
