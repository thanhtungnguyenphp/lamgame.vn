<?php

namespace App\Console\Commands\Sport;

use App\Services\SportCrawl\SyncLiveService;
use Illuminate\Console\Command;

class SyncLive extends Command
{
    protected $signature = 'sport:sync-live';
    protected $description = 'Sync live scores from API-Football';

    public function handle(SyncLiveService $service): int
    {
        $service->run();
        return 0;
    }
}
