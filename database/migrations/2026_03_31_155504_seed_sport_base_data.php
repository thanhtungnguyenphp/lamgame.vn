<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Sports
        DB::table('sports')->insert([
            ['id' => 'football',   'name' => 'Bóng đá',    'icon' => '⚽', 'order' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 'basketball', 'name' => 'Bóng rổ',    'icon' => '🏀', 'order' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 'tennis',     'name' => 'Tennis',      'icon' => '🎾', 'order' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 'mma',        'name' => 'MMA/Võ thuật','icon' => '🥊', 'order' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 'esports',    'name' => 'Esports',     'icon' => '🎮', 'order' => 5, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Leagues
        $leagues = [
            ['id' => 'premier-league',   'name' => 'Premier League',    'sport_id' => 'football',   'country' => 'England', 'season' => '2025-2026', 'order' => 1],
            ['id' => 'la-liga',          'name' => 'La Liga',           'sport_id' => 'football',   'country' => 'Spain',   'season' => '2025-2026', 'order' => 2],
            ['id' => 'serie-a',          'name' => 'Serie A',           'sport_id' => 'football',   'country' => 'Italy',   'season' => '2025-2026', 'order' => 3],
            ['id' => 'bundesliga',       'name' => 'Bundesliga',        'sport_id' => 'football',   'country' => 'Germany', 'season' => '2025-2026', 'order' => 4],
            ['id' => 'ligue-1',          'name' => 'Ligue 1',           'sport_id' => 'football',   'country' => 'France',  'season' => '2025-2026', 'order' => 5],
            ['id' => 'champions-league', 'name' => 'Champions League',  'sport_id' => 'football',   'country' => 'Europe',  'season' => '2025-2026', 'order' => 6],
            ['id' => 'v-league',         'name' => 'V-League',          'sport_id' => 'football',   'country' => 'Vietnam', 'season' => '2026',      'order' => 7],
            ['id' => 'nba',              'name' => 'NBA',               'sport_id' => 'basketball', 'country' => 'USA',     'season' => '2025-2026', 'order' => 1],
            ['id' => 'euroleague',       'name' => 'EuroLeague',        'sport_id' => 'basketball', 'country' => 'Europe',  'season' => '2025-2026', 'order' => 2],
            ['id' => 'atp',              'name' => 'ATP Tour',          'sport_id' => 'tennis',     'country' => null,      'season' => '2026',      'order' => 1],
            ['id' => 'wta',              'name' => 'WTA Tour',          'sport_id' => 'tennis',     'country' => null,      'season' => '2026',      'order' => 2],
            ['id' => 'ufc',              'name' => 'UFC',               'sport_id' => 'mma',        'country' => 'USA',     'season' => '2026',      'order' => 1],
            ['id' => 'one-championship', 'name' => 'ONE Championship',  'sport_id' => 'mma',        'country' => 'Asia',    'season' => '2026',      'order' => 2],
            ['id' => 'lck',              'name' => 'LCK',               'sport_id' => 'esports',    'country' => 'Korea',   'season' => '2026',      'order' => 1],
            ['id' => 'vcs',              'name' => 'VCS',               'sport_id' => 'esports',    'country' => 'Vietnam', 'season' => '2026',      'order' => 2],
        ];

        foreach ($leagues as &$l) {
            $l['is_active'] = true;
            $l['created_at'] = now();
            $l['updated_at'] = now();
        }
        DB::table('leagues')->insert($leagues);

        // Teams (top EPL + NBA)
        $teams = [
            // EPL
            ['id' => 'arsenal',    'name' => 'Arsenal',            'short_name' => 'ARS', 'sport_id' => 'football', 'country' => 'England', 'venue' => 'Emirates Stadium',    'founded' => 1886],
            ['id' => 'man-city',   'name' => 'Manchester City',    'short_name' => 'MCI', 'sport_id' => 'football', 'country' => 'England', 'venue' => 'Etihad Stadium',      'founded' => 1880],
            ['id' => 'man-utd',    'name' => 'Manchester United',  'short_name' => 'MU',  'sport_id' => 'football', 'country' => 'England', 'venue' => 'Old Trafford',        'founded' => 1878],
            ['id' => 'liverpool',  'name' => 'Liverpool',          'short_name' => 'LIV', 'sport_id' => 'football', 'country' => 'England', 'venue' => 'Anfield',             'founded' => 1892],
            ['id' => 'chelsea',    'name' => 'Chelsea',            'short_name' => 'CHE', 'sport_id' => 'football', 'country' => 'England', 'venue' => 'Stamford Bridge',     'founded' => 1905],
            ['id' => 'tottenham',  'name' => 'Tottenham Hotspur',  'short_name' => 'TOT', 'sport_id' => 'football', 'country' => 'England', 'venue' => 'Tottenham Stadium',   'founded' => 1882],
            // La Liga
            ['id' => 'barcelona',  'name' => 'FC Barcelona',       'short_name' => 'BAR', 'sport_id' => 'football', 'country' => 'Spain',   'venue' => 'Spotify Camp Nou',    'founded' => 1899],
            ['id' => 'real-madrid','name' => 'Real Madrid',        'short_name' => 'RMA', 'sport_id' => 'football', 'country' => 'Spain',   'venue' => 'Santiago Bernabéu',   'founded' => 1902],
            ['id' => 'atletico',   'name' => 'Atlético Madrid',    'short_name' => 'ATM', 'sport_id' => 'football', 'country' => 'Spain',   'venue' => 'Metropolitano',       'founded' => 1903],
            // Serie A
            ['id' => 'inter',      'name' => 'Inter Milan',        'short_name' => 'INT', 'sport_id' => 'football', 'country' => 'Italy',   'venue' => 'San Siro',            'founded' => 1908],
            ['id' => 'ac-milan',   'name' => 'AC Milan',           'short_name' => 'MIL', 'sport_id' => 'football', 'country' => 'Italy',   'venue' => 'San Siro',            'founded' => 1899],
            ['id' => 'juventus',   'name' => 'Juventus',           'short_name' => 'JUV', 'sport_id' => 'football', 'country' => 'Italy',   'venue' => 'Allianz Stadium',     'founded' => 1897],
            // Bundesliga
            ['id' => 'bayern',     'name' => 'Bayern Munich',      'short_name' => 'BAY', 'sport_id' => 'football', 'country' => 'Germany', 'venue' => 'Allianz Arena',       'founded' => 1900],
            ['id' => 'dortmund',   'name' => 'Borussia Dortmund',  'short_name' => 'BVB', 'sport_id' => 'football', 'country' => 'Germany', 'venue' => 'Signal Iduna Park',   'founded' => 1909],
            // Ligue 1
            ['id' => 'psg',        'name' => 'Paris Saint-Germain','short_name' => 'PSG', 'sport_id' => 'football', 'country' => 'France',  'venue' => 'Parc des Princes',    'founded' => 1970],
            // NBA
            ['id' => 'lakers',     'name' => 'Los Angeles Lakers', 'short_name' => 'LAL', 'sport_id' => 'basketball', 'country' => 'USA', 'venue' => 'Crypto.com Arena',    'founded' => 1947],
            ['id' => 'warriors',   'name' => 'Golden State Warriors','short_name' => 'GSW','sport_id' => 'basketball', 'country' => 'USA', 'venue' => 'Chase Center',        'founded' => 1946],
            ['id' => 'celtics',    'name' => 'Boston Celtics',     'short_name' => 'BOS', 'sport_id' => 'basketball', 'country' => 'USA', 'venue' => 'TD Garden',            'founded' => 1946],
            ['id' => 'bucks',      'name' => 'Milwaukee Bucks',    'short_name' => 'MIL', 'sport_id' => 'basketball', 'country' => 'USA', 'venue' => 'Fiserv Forum',         'founded' => 1968],
            ['id' => 'nuggets',    'name' => 'Denver Nuggets',     'short_name' => 'DEN', 'sport_id' => 'basketball', 'country' => 'USA', 'venue' => 'Ball Arena',           'founded' => 1967],
        ];

        foreach ($teams as &$t) {
            $t['created_at'] = now();
            $t['updated_at'] = now();
        }
        DB::table('teams')->insert($teams);

        // League-Team pivot
        $pivots = [];
        $eplTeams = ['arsenal', 'man-city', 'man-utd', 'liverpool', 'chelsea', 'tottenham'];
        foreach ($eplTeams as $t) $pivots[] = ['league_id' => 'premier-league', 'team_id' => $t];
        foreach (['barcelona', 'real-madrid', 'atletico'] as $t) $pivots[] = ['league_id' => 'la-liga', 'team_id' => $t];
        foreach (['inter', 'ac-milan', 'juventus'] as $t) $pivots[] = ['league_id' => 'serie-a', 'team_id' => $t];
        foreach (['bayern', 'dortmund'] as $t) $pivots[] = ['league_id' => 'bundesliga', 'team_id' => $t];
        $pivots[] = ['league_id' => 'ligue-1', 'team_id' => 'psg'];
        foreach (['lakers', 'warriors', 'celtics', 'bucks', 'nuggets'] as $t) $pivots[] = ['league_id' => 'nba', 'team_id' => $t];

        DB::table('league_team')->insert($pivots);
    }

    public function down(): void
    {
        DB::table('league_team')->truncate();
        DB::table('teams')->truncate();
        DB::table('leagues')->truncate();
        DB::table('sports')->truncate();
    }
};
