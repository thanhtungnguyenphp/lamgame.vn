<?php

namespace Database\Seeders;

use App\Models\Sport\League;
use Illuminate\Database\Seeder;

class SportExternalIdsSeeder extends Seeder
{
    public function run(): void
    {
        // Map leagues to API-Football IDs
        $leagueMapping = [
            'premier-league' => ['api_football' => 39],
            'la-liga' => ['api_football' => 140],
            'serie-a' => ['api_football' => 135],
            'bundesliga' => ['api_football' => 78],
            'ligue-1' => ['api_football' => 61],
            'champions-league' => ['api_football' => 2],
            'europa-league' => ['api_football' => 3],
            'conference-league' => ['api_football' => 848],
            'world-cup' => ['api_football' => 1],
            'euro' => ['api_football' => 4],
            'v-league' => ['api_football' => 253],
            'wc-qualifiers-asia' => ['api_football' => 36],
            'k-league' => ['api_football' => 292],
            'j-league' => ['api_football' => 169],
            'saudi-pro-league' => ['api_football' => 307],
        ];

        foreach ($leagueMapping as $id => $externalIds) {
            League::where('id', $id)->update(['external_ids' => $externalIds]);
        }

        $this->command->info('League external_ids seeded: ' . count($leagueMapping));

        // Team mapping will be populated after first sync or manually
        // Example: Team::where('id', 'manchester-united')->update(['external_ids' => ['api_football' => 33]]);
    }
}
