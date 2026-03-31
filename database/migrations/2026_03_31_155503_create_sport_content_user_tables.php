<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sport_highlights', function (Blueprint $table) {
            $table->id();
            $table->string('title', 500);
            $table->string('thumbnail_url', 500)->nullable();
            $table->string('video_url', 500);
            $table->unsignedSmallInteger('duration')->default(0); // seconds
            $table->unsignedInteger('view_count')->default(0);
            $table->string('sport_id', 30);
            $table->string('match_id', 50)->nullable();
            $table->string('league_id', 50)->nullable();
            $table->timestamps();

            $table->foreign('sport_id')->references('id')->on('sports');
            $table->foreign('match_id')->references('id')->on('sport_matches')->nullOnDelete();
            $table->foreign('league_id')->references('id')->on('leagues');
            $table->index(['sport_id', 'created_at']);
        });

        Schema::create('sport_articles', function (Blueprint $table) {
            $table->id();
            $table->string('title', 500);
            $table->text('summary')->nullable();
            $table->longText('content');
            $table->string('image_url', 500)->nullable();
            $table->string('type', 20)->default('recap'); // recap, preview, opinion, roundup
            $table->string('sport_id', 30);
            $table->unsignedTinyInteger('read_time_minutes')->default(3);
            $table->json('related_matches')->nullable(); // ["match-123", "match-456"]
            $table->timestamps();

            $table->foreign('sport_id')->references('id')->on('sports');
            $table->index(['sport_id', 'type', 'created_at']);
        });

        Schema::create('user_sport_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('firebase_uid', 128)->unique();
            $table->string('display_name', 200)->nullable();
            $table->string('email', 200)->nullable();
            $table->string('photo_url', 500)->nullable();
            $table->json('favorite_teams')->nullable(); // ["man-utd", "lakers"]
            $table->json('favorite_sports')->nullable(); // ["football", "basketball"]
            $table->json('notification_settings')->nullable(); // {live_score, match_reminder, highlights, favorite_teams_only}
            $table->boolean('is_premium')->default(false);
            $table->timestamps();
        });

        Schema::create('user_sport_reminders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_sport_profile_id');
            $table->string('match_id', 50);
            $table->unsignedSmallInteger('remind_before_minutes')->default(15);
            $table->boolean('sent')->default(false);
            $table->timestamps();

            $table->foreign('user_sport_profile_id')->references('id')->on('user_sport_profiles')->cascadeOnDelete();
            $table->foreign('match_id')->references('id')->on('sport_matches')->cascadeOnDelete();
            $table->unique(['user_sport_profile_id', 'match_id']);
        });

        Schema::create('user_sport_fcm_tokens', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_sport_profile_id');
            $table->string('token', 500);
            $table->string('platform', 10)->default('android'); // android, ios
            $table->timestamps();

            $table->foreign('user_sport_profile_id')->references('id')->on('user_sport_profiles')->cascadeOnDelete();
            $table->index('user_sport_profile_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_sport_fcm_tokens');
        Schema::dropIfExists('user_sport_reminders');
        Schema::dropIfExists('user_sport_profiles');
        Schema::dropIfExists('sport_articles');
        Schema::dropIfExists('sport_highlights');
    }
};
