<?php

namespace App\Console\Commands\Sport;

use App\Services\SportCrawl\SyncStandingsService;
use Illuminate\Console\Command;

class SyncStandings extends Command
{
    protected $signature = 'sport:sync-standings';
    protected $description = 'Sync league standings from API-Football';

    public function handle(SyncStandingsService $service): int
    {
        $this->info('Syncing standings...');
        $service->run();
        $this->info('Done.');
        return 0;
    }
}
