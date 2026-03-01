<?php

namespace App\Jobs;

use App\Services\Lottery\TraditionalScraper;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ScrapeTraditionalLottery implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 60;

    public function __construct(
        private string $region,
        private ?string $date = null,
    ) {}

    public function handle(TraditionalScraper $scraper): void
    {
        $scraper->scrape($this->region, $this->date);
    }
}
