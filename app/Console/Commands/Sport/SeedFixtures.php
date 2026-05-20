<?php

namespace App\Console\Commands\Sport;

use App\Models\Sport\League;
use App\Models\Sport\SportMatch;
use App\Models\Sport\Team;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SeedFixtures extends Command
{
    protected $signature = 'sport:seed-fixtures {--days=7 : Days ahead for scheduled} {--fresh : Truncate existing matches}';
    protected $description = 'Seed realistic match fixtures for mobile testing';

    public function handle(): void
    {
        if ($this->option('fresh')) {
            \DB::statement('SET FOREIGN_KEY_CHECKS=0');
            SportMatch::truncate();
            \DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }

        $footballTeams = Team::where('sport_id', 'football')->pluck('id')->toArray();
        $footballLeagues = League::where('sport_id', 'football')->pluck('id')->toArray();

        if (count($footballTeams) < 2) {
            $this->error('Need at least 2 football teams.');
            return;
        }

        $created = 0;
        $days = (int) $this->option('days');

        // Past results (last 3 days)
        for ($d = 3; $d >= 1; $d--) {
            $date = Carbon::today()->subDays($d);
            $created += $this->generateMatchDay($footballTeams, $footballLeagues, $date, 'finished', 4);
        }

        // Today live
        $created += $this->generateMatchDay($footballTeams, $footballLeagues, Carbon::today(), 'live', 2);
        // Today finished
        $created += $this->generateMatchDay($footballTeams, $footballLeagues, Carbon::today(), 'finished', 2);

        // Future scheduled
        for ($d = 1; $d <= $days; $d++) {
            $date = Carbon::today()->addDays($d);
            $created += $this->generateMatchDay($footballTeams, $footballLeagues, $date, 'scheduled', 3);
        }

        $this->info("Seeded {$created} matches.");
    }

    private function generateMatchDay(array $teams, array $leagues, Carbon $date, string $status, int $count): int
    {
        $created = 0;
        $used = [];

        for ($i = 0; $i < $count; $i++) {
            [$home, $away] = $this->pickTwo($teams, $used);
            if (!$home) break;

            $used[] = $home;
            $used[] = $away;

            $startTime = match ($status) {
                'live' => Carbon::now()->subMinutes(rand(5, 75)),
                'finished' => $date->copy()->setTime(rand(14, 21), rand(0, 1) * 30),
                default => $date->copy()->setTime(rand(17, 22), rand(0, 1) * 30),
            };

            $id = "{$home}-vs-{$away}-" . $date->format('Ymd') . "-{$i}";

            SportMatch::updateOrCreate(['id' => $id], [
                'home_team_id' => $home,
                'away_team_id' => $away,
                'league_id' => $leagues[array_rand($leagues)],
                'sport_id' => 'football',
                'status' => $status,
                'home_score' => $status === 'scheduled' ? 0 : rand(0, 4),
                'away_score' => $status === 'scheduled' ? 0 : rand(0, 3),
                'start_time' => $startTime,
                'minute' => $status === 'live' ? rand(10, 85) : null,
                'source' => 'seed',
                'synced_at' => now(),
            ]);
            $created++;
        }

        return $created;
    }

    private function pickTwo(array $teams, array $exclude): array
    {
        $available = array_diff($teams, $exclude);
        if (count($available) < 2) return [null, null];
        $keys = array_rand($available, 2);
        return [$available[$keys[0]], $available[$keys[1]]];
    }
}
