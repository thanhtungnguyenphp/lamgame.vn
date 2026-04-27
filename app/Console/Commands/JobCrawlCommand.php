<?php

namespace App\Console\Commands;

use App\Jobs\CrawlJobSource;
use App\Services\JobCrawler\JobCrawlerService;
use App\Services\JobCrawler\Sources\TopDevCrawler;
use Illuminate\Console\Command;

class JobCrawlCommand extends Command
{
    protected $signature = 'job:crawl
        {--source=all : Nguồn crawl (topdev, all)}
        {--keyword= : Keyword cụ thể (bỏ qua config)}
        {--category= : Nhóm keyword (dev, art, qc, content, general, all)}
        {--limit=20 : Số job tối đa mỗi keyword}
        {--sync : Chạy đồng bộ thay vì queue}';

    protected $description = 'Crawl job tuyển dụng game từ các nguồn bên ngoài';

    private array $sourceMap = [
        'topdev' => TopDevCrawler::class,
    ];

    public function handle(JobCrawlerService $service): int
    {
        $sourceName = $this->option('source');
        $keyword = $this->option('keyword');
        $category = $this->option('category') ?: 'all';
        $limit = (int) $this->option('limit');
        $sync = $this->option('sync');
        $maxPerRun = config('job_crawler.max_per_run', 50);

        // Xác định sources
        $sources = $sourceName === 'all'
            ? config('job_crawler.sources', ['topdev'])
            : [$sourceName];

        // Xác định keywords
        $keywords = $this->resolveKeywords($keyword, $category);

        if (empty($keywords)) {
            $this->error('Không có keyword nào để crawl.');
            return self::FAILURE;
        }

        $this->info("Crawl {$sourceName} | " . count($keywords) . " keywords | limit={$limit}/keyword | sync=" . ($sync ? 'yes' : 'no'));

        $totalStats = ['crawled' => 0, 'created' => 0, 'duplicates' => 0, 'failed' => 0];
        $dispatched = 0;

        foreach ($sources as $src) {
            if (!isset($this->sourceMap[$src])) {
                $this->warn("Source không hỗ trợ: {$src}");
                continue;
            }

            $sourceClass = $this->sourceMap[$src];

            foreach ($keywords as $kw) {
                if ($totalStats['created'] >= $maxPerRun) {
                    $this->warn("Đạt giới hạn {$maxPerRun} jobs/run. Dừng.");
                    break 2;
                }

                if ($sync) {
                    $this->line("  Crawling [{$src}] \"{$kw}\"...");
                    $source = app($sourceClass);
                    $stats = $service->crawlFromSource($source, $kw, $limit);
                    foreach ($stats as $k => $v) {
                        $totalStats[$k] += $v;
                    }
                    $this->line("    → crawled={$stats['crawled']} created={$stats['created']} dup={$stats['duplicates']} failed={$stats['failed']}");
                } else {
                    CrawlJobSource::dispatch($sourceClass, $kw, $limit);
                    $dispatched++;
                }
            }
        }

        if ($sync) {
            $this->info("Hoàn thành: crawled={$totalStats['crawled']} created={$totalStats['created']} dup={$totalStats['duplicates']} failed={$totalStats['failed']}");
        } else {
            $this->info("Đã dispatch {$dispatched} queue jobs.");
        }

        return self::SUCCESS;
    }

    private function resolveKeywords(?string $keyword, string $category): array
    {
        if ($keyword) {
            return [$keyword];
        }

        $allKeywords = config('job_crawler.keywords', []);

        if ($category === 'all') {
            return array_merge(...array_values($allKeywords));
        }

        return $allKeywords[$category] ?? [];
    }
}
