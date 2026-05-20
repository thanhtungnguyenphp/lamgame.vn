<?php

namespace App\Console\Commands\Sport;

use App\Models\Sport\League;
use App\Models\Sport\SportMatch;
use App\Models\Sport\Team;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ImportHistorical extends Command
{
    protected $signature = 'sport:import-historical {--league=39 : API-Football league ID} {--shift-to-today : Shift dates to current week}';
    protected $description = 'Import real fixtures from API-Football (season 2024) for mobile testing';

    public function handle(): void
    {
        $config = config('sport-crawl.api_football');
        if (!$config['key']) {
            $this->error('API_FOOTBALL_KEY not set.');
            return;
        }

        $leagueId = (int) $this->option('league');

        // Fetch last month of season 2024
        $resp = Http::timeout(15)
            ->withHeaders(['x-apisports-key' => $config['key']])
            ->get($config['base_url'] . '/fixtures', [
                'league' => $leagueId,
                'season' => 2024,
                'from' => '2025-05-01',
                'to' => '2025-05-25',
            ]);

        $fixtures = $resp->json('response') ?? [];
        $this->info("Fetched " . count($fixtures) . " fixtures from API-Football.");

        if (empty($fixtures)) return;

        // Calculate date shift
        $firstDate = Carbon::parse($fixtures[0]['fixture']['date']);
        $shift = $this->option('shift-to-today') ? Carbon::today()->diffInDays($firstDate, false) * -1 : 0;

        $created = 0;
        $teamsCreated = 0;

        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        foreach ($fixtures as $f) {
            $homeExt = $f['teams']['home']['id'];
            $awayExt = $f['teams']['away']['id'];

            // Ensure teams exist
            $homeTeam = $this->ensureTeam($f['teams']['home'], $teamsCreated);
            $awayTeam = $this->ensureTeam($f['teams']['away'], $teamsCreated);

            // Map league
            $dbLeague = League::whereJsonContains('external_ids->api_football', $leagueId)->first();

            $status = match ($f['fixture']['status']['short']) {
                'NS', 'TBD' => 'scheduled',
                '1H', '2H', 'ET', 'P', 'LIVE' => 'live',
                'HT' => 'halftime',
                'FT', 'AET', 'PEN' => 'finished',
                default => 'scheduled',
            };

            $startTime = Carbon::parse($f['fixture']['date'])->addDays($shift);

            // Make recent finished matches "live" for testing
            if ($shift && $status === 'finished' && $startTime->isToday()) {
                $status = 'live';
            }

            $matchId = Str::slug($homeTeam . '-vs-' . $awayTeam . '-' . $f['fixture']['id']);

            SportMatch::updateOrCreate(['id' => $matchId], [
                'external_id' => (string) $f['fixture']['id'],
                'home_team_id' => $homeTeam,
                'away_team_id' => $awayTeam,
                'league_id' => $dbLeague?->id,
                'sport_id' => 'football',
                'status' => $status,
                'home_score' => $f['goals']['home'] ?? 0,
                'away_score' => $f['goals']['away'] ?? 0,
                'start_time' => $startTime,
                'venue' => $f['fixture']['venue']['name'] ?? null,
                'referee' => $f['fixture']['referee'] ?? null,
                'source' => 'api-football',
                'synced_at' => now(),
            ]);
            $created++;
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $this->info("Imported {$created} matches. Created {$teamsCreated} new teams.");
    }

    private function ensureTeam(array $apiTeam, int &$counter): string
    {
        $extId = $apiTeam['id'];
        $team = Team::whereJsonContains('external_ids->api_football', $extId)->first();

        if ($team) return $team->id;

        $id = Str::slug($apiTeam['name']);
        $existing = Team::find($id);
        if ($existing) return $id;

        Team::create([
            'id' => $id,
            'name' => $apiTeam['name'],
            'short_name' => strtoupper(substr($apiTeam['name'], 0, 3)),
            'logo_url' => $apiTeam['logo'] ?? "https://media.api-sports.io/football/teams/{$extId}.png",
            'sport_id' => 'football',
            'external_ids' => json_encode(['api_football' => $extId]),
        ]);
        $counter++;

        return $id;
    }
}
