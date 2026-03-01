<?php

namespace App\Jobs;

use App\Services\Lottery\VietlotScraper;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ScrapeVietlotLottery implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 60;

    public function __construct(
        private ?string $game = null,
    ) {}

    public function handle(VietlotScraper $scraper): void
    {
        $games = $this->game ? [$this->game] : config('lottery.games');

        foreach ($games as $game) {
            $scraper->scrape($game);
        }
    }
}
