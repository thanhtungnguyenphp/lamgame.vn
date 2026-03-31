<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sport_matches', function (Blueprint $table) {
            $table->string('id', 50)->primary(); // match-12345
            $table->string('home_team_id', 50);
            $table->string('away_team_id', 50);
            $table->string('league_id', 50);
            $table->string('sport_id', 30);
            $table->string('status', 20)->default('scheduled'); // scheduled, live, halftime, finished, postponed, cancelled
            $table->unsignedSmallInteger('minute')->nullable();
            $table->string('period', 20)->nullable(); // 1st_half, 2nd_half, extra_time, penalties
            $table->unsignedTinyInteger('home_score')->nullable();
            $table->unsignedTinyInteger('away_score')->nullable();
            $table->timestamp('start_time');
            $table->string('venue', 200)->nullable();
            $table->string('referee', 100)->nullable();
            $table->json('stats')->nullable(); // {home: {possession, shots...}, away: {...}}
            $table->timestamps();

            $table->foreign('home_team_id')->references('id')->on('teams');
            $table->foreign('away_team_id')->references('id')->on('teams');
            $table->foreign('league_id')->references('id')->on('leagues');
            $table->foreign('sport_id')->references('id')->on('sports');
            $table->index(['status', 'start_time']);
            $table->index(['sport_id', 'start_time']);
            $table->index('league_id');
        });

        Schema::create('match_events', function (Blueprint $table) {
            $table->id();
            $table->string('match_id', 50);
            $table->string('type', 30); // goal, own_goal, penalty_goal, penalty_miss, yellow_card, red_card, second_yellow, substitution, var_decision
            $table->unsignedSmallInteger('minute');
            $table->unsignedSmallInteger('extra_minute')->nullable();
            $table->string('team_side', 10); // home, away
            $table->string('player_name', 100)->nullable();
            $table->string('player_id', 50)->nullable();
            $table->string('assist_name', 100)->nullable();
            $table->string('assist_id', 50)->nullable();
            $table->string('player_in_name', 100)->nullable(); // for substitution
            $table->string('player_out_name', 100)->nullable();
            $table->string('detail', 100)->nullable();
            $table->timestamps();

            $table->foreign('match_id')->references('id')->on('sport_matches')->cascadeOnDelete();
            $table->index('match_id');
        });

        Schema::create('match_lineups', function (Blueprint $table) {
            $table->id();
            $table->string('match_id', 50);
            $table->string('team_side', 10); // home, away
            $table->string('formation', 20)->nullable(); // 4-2-3-1
            $table->json('starting')->nullable(); // [{id, name, number, position}]
            $table->json('substitutes')->nullable();
            $table->timestamps();

            $table->foreign('match_id')->references('id')->on('sport_matches')->cascadeOnDelete();
            $table->unique(['match_id', 'team_side']);
        });

        Schema::create('standings', function (Blueprint $table) {
            $table->id();
            $table->string('league_id', 50);
            $table->string('team_id', 50);
            $table->unsignedTinyInteger('rank');
            $table->unsignedSmallInteger('played')->default(0);
            $table->unsignedSmallInteger('won')->default(0);
            $table->unsignedSmallInteger('drawn')->default(0);
            $table->unsignedSmallInteger('lost')->default(0);
            $table->unsignedSmallInteger('goals_for')->default(0);
            $table->unsignedSmallInteger('goals_against')->default(0);
            $table->smallInteger('goal_difference')->default(0);
            $table->unsignedSmallInteger('points')->default(0);
            $table->json('form')->nullable(); // ["W","W","D","L","W"]
            $table->timestamps();

            $table->foreign('league_id')->references('id')->on('leagues')->cascadeOnDelete();
            $table->foreign('team_id')->references('id')->on('teams')->cascadeOnDelete();
            $table->unique(['league_id', 'team_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('standings');
        Schema::dropIfExists('match_lineups');
        Schema::dropIfExists('match_events');
        Schema::dropIfExists('sport_matches');
    }
};
