<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sports', function (Blueprint $table) {
            $table->string('id', 30)->primary(); // football, basketball, etc.
            $table->string('name', 100);
            $table->string('icon', 10);
            $table->unsignedTinyInteger('order')->default(0);
            $table->timestamps();
        });

        Schema::create('leagues', function (Blueprint $table) {
            $table->string('id', 50)->primary(); // premier-league, nba, etc.
            $table->string('name', 200);
            $table->string('sport_id', 30);
            $table->string('country', 100)->nullable();
            $table->string('logo_url', 500)->nullable();
            $table->string('season', 20)->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedTinyInteger('order')->default(0);
            $table->timestamps();

            $table->foreign('sport_id')->references('id')->on('sports');
            $table->index('sport_id');
        });

        Schema::create('teams', function (Blueprint $table) {
            $table->string('id', 50)->primary(); // man-utd, arsenal, etc.
            $table->string('name', 200);
            $table->string('short_name', 10);
            $table->string('logo_url', 500)->nullable();
            $table->string('sport_id', 30);
            $table->string('country', 100)->nullable();
            $table->string('venue', 200)->nullable();
            $table->unsignedSmallInteger('founded')->nullable();
            $table->timestamps();

            $table->foreign('sport_id')->references('id')->on('sports');
            $table->index('sport_id');
        });

        Schema::create('league_team', function (Blueprint $table) {
            $table->string('league_id', 50);
            $table->string('team_id', 50);
            $table->primary(['league_id', 'team_id']);
            $table->foreign('league_id')->references('id')->on('leagues')->cascadeOnDelete();
            $table->foreign('team_id')->references('id')->on('teams')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('league_team');
        Schema::dropIfExists('teams');
        Schema::dropIfExists('leagues');
        Schema::dropIfExists('sports');
    }
};
