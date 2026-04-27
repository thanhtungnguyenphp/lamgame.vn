<?php

namespace App\Jobs;

use App\Services\JobCrawler\JobCrawlerService;
use App\Services\JobCrawler\Sources\CrawlerSourceInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CrawlJobSource implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 1;
    public int $timeout = 600; // 10 phút max

    public function __construct(
        private string $sourceClass,
        private string $keyword,
        private int $limit = 20,
    ) {}

    public function handle(JobCrawlerService $service): void
    {
        /** @var CrawlerSourceInterface $source */
        $source = app($this->sourceClass);

        $stats = $service->crawlFromSource($source, $this->keyword, $this->limit);

        Log::info("[CrawlJobSource] {$source->sourceName()} \"{$this->keyword}\": " . json_encode($stats));
    }
}
