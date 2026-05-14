<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\Sport\Team;
use App\Models\Sport\League;

return new class extends Migration
{
    public function up(): void
    {
        // League external_ids (API-Football)
        $leagues = [
            'premier-league' => ['api_football' => 39],
            'la-liga' => ['api_football' => 140],
            'serie-a' => ['api_football' => 135],
            'bundesliga' => ['api_football' => 78],
            'ligue-1' => ['api_football' => 61],
            'champions-league' => ['api_football' => 2],
            'europa-league' => ['api_football' => 3],
            'v-league' => ['api_football' => 253],
        ];

        foreach ($leagues as $id => $externalIds) {
            League::where('id', $id)->update(['external_ids' => json_encode($externalIds)]);
        }

        // Football teams (API-Football)
        $footballTeams = [
            'ac-milan' => ['api_football' => 489],
            'arsenal' => ['api_football' => 42],
            'atletico' => ['api_football' => 530],
            'barcelona' => ['api_football' => 529],
            'bayern' => ['api_football' => 157],
            'chelsea' => ['api_football' => 49],
            'dortmund' => ['api_football' => 165],
            'inter' => ['api_football' => 505],
            'juventus' => ['api_football' => 496],
            'liverpool' => ['api_football' => 40],
            'man-city' => ['api_football' => 50],
            'man-utd' => ['api_football' => 33],
            'psg' => ['api_football' => 85],
            'real-madrid' => ['api_football' => 541],
            'tottenham' => ['api_football' => 47],
        ];

        foreach ($footballTeams as $id => $externalIds) {
            Team::where('id', $id)->update(['external_ids' => json_encode($externalIds)]);
        }

        // NBA teams (BallDontLie)
        $nbaTeams = [
            'celtics' => ['balldontlie' => 2],
            'nuggets' => ['balldontlie' => 8],
            'warriors' => ['balldontlie' => 10],
            'lakers' => ['balldontlie' => 14],
            'bucks' => ['balldontlie' => 17],
        ];

        foreach ($nbaTeams as $id => $externalIds) {
            Team::where('id', $id)->update(['external_ids' => json_encode($externalIds)]);
        }
    }

    public function down(): void
    {
        Team::whereNotNull('external_ids')->update(['external_ids' => null]);
        League::whereNotNull('external_ids')->update(['external_ids' => null]);
    }
};
