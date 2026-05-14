<?php

namespace App\Console\Commands\Sport;

use App\Services\SportCrawl\SyncHighlightsService;
use Illuminate\Console\Command;

class SyncHighlights extends Command
{
    protected $signature = 'sport:sync-highlights';
    protected $description = 'Sync highlight videos from Scorebat';

    public function handle(SyncHighlightsService $service): int
    {
        $this->info('Syncing highlights...');
        $service->run();
        $this->info('Done.');
        return 0;
    }
}
