<?php

namespace App\Console\Commands;

use App\Models\LotteryDraw;
use App\Models\LotteryProvince;
use App\Services\Lottery\TraditionalScraper;
use Carbon\Carbon;
use Illuminate\Console\Command;

class LotteryScrapeAndMigrateCommand extends Command
{
    protected $signature = 'lottery:scrape-migrate
                            {--region=mien-nam : mien-nam, mien-trung, mien-bac, or all}
                            {--date= : Date YYYY-MM-DD (default: today)}
                            {--days=1 : Number of days to scrape (backwards from date)}
                            {--scrape-only : Only scrape, skip migration generation}';

    protected $description = 'Scrape kết quả XS và tự động tạo JSON + migration file cho deploy';

    public function handle(TraditionalScraper $scraper): int
    {
        $endDate = Carbon::parse($this->option('date') ?: Carbon::today()->toDateString());
        $days = (int) $this->option('days');
        $regionOpt = $this->option('region');

        $regions = $regionOpt === 'all' ? config('lottery.regions') : [$regionOpt];

        // Step 1: Scrape
        $this->info('=== STEP 1: Scrape ===');
        $scrapedDates = [];

        for ($i = 0; $i < $days; $i++) {
            $date = $endDate->copy()->subDays($i)->toDateString();
            foreach ($regions as $region) {
                // Skip if already exists
                $exists = LotteryDraw::where('type', 'traditional')
                    ->where('region', $region)
                    ->where('date', $date)
                    ->where('status', 'completed')
                    ->exists();

                if ($exists) {
                    $this->line("  ⏭ {$region} {$date} — already exists");
                    $scrapedDates[$region][] = $date;
                    continue;
                }

                $this->line("  Scraping {$region} {$date}...");
                if ($scraper->scrape($region, $date)) {
                    $this->info("  ✅ {$region} {$date}");
                    $scrapedDates[$region][] = $date;
                } else {
                    $this->error("  ❌ {$region} {$date}");
                }
            }
        }

        if ($this->option('scrape-only') || empty($scrapedDates)) {
            return self::SUCCESS;
        }

        // Step 2: Export JSON
        $this->info('=== STEP 2: Export JSON ===');
        $dataDir = database_path('seeders/data');
        if (!is_dir($dataDir)) mkdir($dataDir, 0755, true);

        $allDates = collect($scrapedDates)->flatten()->unique()->sort()->values()->toArray();
        $dateTag = count($allDates) === 1
            ? str_replace('-', '', $allDates[0])
            : str_replace('-', '', $allDates[0]) . '_' . str_replace('-', '', end($allDates));

        $regionTag = $regionOpt === 'all' ? 'all' : str_replace('-', '_', $regionOpt);
        $jsonFile = "lottery_{$regionTag}_{$dateTag}.json";

        $data = [];
        foreach ($regions as $region) {
            $dates = $scrapedDates[$region] ?? [];
            if (empty($dates)) continue;

            $draws = LotteryDraw::where('type', 'traditional')
                ->where('region', $region)
                ->whereIn('date', $dates)
                ->where('status', 'completed')
                ->with(['results.province'])
                ->orderBy('date')
                ->get();

            foreach ($draws as $draw) {
                $entry = [
                    'type'      => $draw->type,
                    'region'    => $draw->region,
                    'date'      => $draw->date->toDateString(),
                    'draw_time' => $draw->draw_time,
                    'status'    => $draw->status,
                    'source'    => $draw->source,
                    'results'   => $draw->results->map(fn ($r) => [
                        'province_code' => $r->province->code,
                        'prize_data'    => $r->prize_data,
                    ])->sortBy('province_code')->values()->toArray(),
                ];
                $data[] = $entry;
            }
        }

        $jsonPath = "{$dataDir}/{$jsonFile}";
        file_put_contents($jsonPath, json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        $this->info("  📄 {$jsonFile} — " . count($data) . " draws");

        // Step 3: Generate migration
        $this->info('=== STEP 3: Generate Migration ===');
        $timestamp = now()->format('Y_m_d_His');
        $migrationName = "{$timestamp}_seed_lottery_{$regionTag}_{$dateTag}";
        $migrationFile = database_path("migrations/{$migrationName}.php");

        $datesPhp = "['" . implode("', '", $allDates) . "']";
        $regionsPhp = $regionOpt === 'all'
            ? "config('lottery.regions')"
            : "['{$regionOpt}']";

        $migrationContent = <<<PHP
<?php

use App\Models\LotteryDraw;
use App\Models\LotteryProvince;
use App\Models\LotteryResult;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        \$json = file_get_contents(database_path('seeders/data/{$jsonFile}'));
        \$draws = json_decode(\$json, true);
        \$provinceMap = LotteryProvince::pluck('id', 'code')->toArray();

        foreach (\$draws as \$d) {
            \$draw = LotteryDraw::updateOrCreate(
                ['type' => \$d['type'], 'region' => \$d['region'], 'date' => \$d['date'], 'game' => null],
                [
                    'draw_time'  => \$d['draw_time'],
                    'status'     => \$d['status'],
                    'source'     => \$d['source'],
                    'scraped_at' => now(),
                ]
            );

            foreach (\$d['results'] as \$r) {
                \$provinceId = \$provinceMap[\$r['province_code']] ?? null;
                if (!\$provinceId) continue;

                LotteryResult::updateOrCreate(
                    ['draw_id' => \$draw->id, 'province_id' => \$provinceId],
                    ['prize_data' => \$r['prize_data']]
                );
            }
        }
    }

    public function down(): void
    {
        \$dates = {$datesPhp};
        \$regions = {$regionsPhp};

        LotteryDraw::where('type', 'traditional')
            ->whereIn('region', \$regions)
            ->whereIn('date', \$dates)
            ->each(fn (\$draw) => \$draw->delete());
    }
};
PHP;

        file_put_contents($migrationFile, $migrationContent);
        $this->info("  📝 {$migrationName}.php");

        $this->newLine();
        $this->info('✅ Done! Deploy: php artisan migrate');

        return self::SUCCESS;
    }
}
