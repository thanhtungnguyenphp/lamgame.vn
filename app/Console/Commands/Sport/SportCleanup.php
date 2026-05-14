<?php

namespace App\Console\Commands\Sport;

use App\Models\Sport\{SportMatch, Highlight, SportArticle, SportCrawlLog};
use Illuminate\Console\Command;

class SportCleanup extends Command
{
    protected $signature = 'sport:cleanup';
    protected $description = 'Remove sport data older than configured days';

    public function handle(): int
    {
        $days = config('sport-crawl.cleanup_days', 90);
        $cutoff = now()->subDays($days);

        $matches = SportMatch::where('start_time', '<', $cutoff)->where('status', 'finished')->delete();
        $highlights = Highlight::where('created_at', '<', $cutoff)->delete();
        $articles = SportArticle::where('created_at', '<', $cutoff)->delete();
        $logs = SportCrawlLog::where('created_at', '<', $cutoff)->delete();

        $this->info("Cleaned: {$matches} matches, {$highlights} highlights, {$articles} articles, {$logs} logs");
        return 0;
    }
}
