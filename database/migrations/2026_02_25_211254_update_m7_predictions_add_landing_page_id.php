<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('m7_predictions', function (Blueprint $table) {
            $table->unsignedBigInteger('landing_page_id')->nullable()->after('id');
            $table->dropColumn('session_id');
            $table->index('landing_page_id');
        });

        Schema::table('m7_matches', function (Blueprint $table) {
            $table->unsignedBigInteger('landing_page_id')->nullable()->after('id');
            $table->index('landing_page_id');
        });

        // Backfill existing data
        $pageId = \DB::table('landing_pages')->where('slug', 'mini-game-m7')->value('id');
        if ($pageId) {
            \DB::table('m7_predictions')->whereNull('landing_page_id')->update(['landing_page_id' => $pageId]);
            \DB::table('m7_matches')->whereNull('landing_page_id')->update(['landing_page_id' => $pageId]);
        }
    }

    public function down(): void
    {
        Schema::table('m7_predictions', function (Blueprint $table) {
            $table->string('session_id')->nullable()->after('user_id');
            $table->dropColumn('landing_page_id');
        });
        Schema::table('m7_matches', function (Blueprint $table) {
            $table->dropColumn('landing_page_id');
        });
    }
};
