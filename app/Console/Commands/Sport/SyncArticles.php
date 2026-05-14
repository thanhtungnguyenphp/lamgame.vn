<?php

namespace App\Console\Commands\Sport;

use App\Services\SportCrawl\SyncArticlesService;
use Illuminate\Console\Command;

class SyncArticles extends Command
{
    protected $signature = 'sport:sync-articles';
    protected $description = 'Sync sport articles from RSS feeds';

    public function handle(SyncArticlesService $service): int
    {
        $this->info('Syncing articles...');
        $service->run();
        $this->info('Done.');
        return 0;
    }
}
