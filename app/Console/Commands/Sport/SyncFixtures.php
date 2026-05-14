<?php

namespace App\Console\Commands\Sport;

use App\Services\SportCrawl\SyncFixturesService;
use Illuminate\Console\Command;

class SyncFixtures extends Command
{
    protected $signature = 'sport:sync-fixtures';
    protected $description = 'Sync fixtures from API-Football (today + 7 days)';

    public function handle(SyncFixturesService $service): int
    {
        $this->info('Syncing fixtures...');
        $service->run();
        $this->info('Done.');
        return 0;
    }
}
