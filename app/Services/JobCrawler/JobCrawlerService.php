<?php

namespace App\Services\JobCrawler;

use App\Models\JobCrawlLog;
use App\Services\JobCrawler\Sources\CrawlerSourceInterface;
use App\Services\JobPostingService;
use Illuminate\Support\Facades\Log;

/**
 * Orchestrator: điều phối crawl từ nhiều nguồn → normalize → dedup → lưu DB.
 */
class JobCrawlerService
{
    public function __construct(
        private JobPostingService $jobPostingService,
        private JobNormalizer $normalizer,
        private DuplicateDetector $detector,
    ) {}

    /**
     * Crawl jobs từ 1 source với 1 keyword.
     *
     * @return array{crawled: int, created: int, duplicates: int, failed: int}
     */
    public function crawlFromSource(CrawlerSourceInterface $source, string $keyword, int $limit = 20): array
    {
        $stats = ['crawled' => 0, 'created' => 0, 'duplicates' => 0, 'failed' => 0];
        $sourceName = $source->sourceName();

        Log::info("[JobCrawler] Bắt đầu crawl {$sourceName} keyword=\"{$keyword}\" limit={$limit}");

        try {
            $rawJobs = $source->crawl($keyword, $limit);
        } catch (\Throwable $e) {
            Log::error("[JobCrawler] Lỗi crawl {$sourceName}: {$e->getMessage()}");
            return $stats;
        }

        $stats['crawled'] = count($rawJobs);

        foreach ($rawJobs as $raw) {
            try {
                $this->processJob($raw, $sourceName, $stats);
            } catch (\Throwable $e) {
                $stats['failed']++;
                $this->logCrawl($sourceName, $raw, 'failed', null, $e->getMessage());
                Log::warning("[JobCrawler] Lỗi xử lý job: {$e->getMessage()}");
            }
        }

        Log::info("[JobCrawler] Hoàn thành {$sourceName} keyword=\"{$keyword}\": " . json_encode($stats));
        return $stats;
    }

    private function processJob(array $raw, string $sourceName, array &$stats): void
    {
        $sourceId = $raw['source_id'] ?? '';
        $title = $raw['title'] ?? '';
        $company = $raw['company_name'] ?? '';

        // Check trùng
        if ($this->detector->isDuplicate($sourceName, $sourceId, $title, $company)) {
            $stats['duplicates']++;
            $this->logCrawl($sourceName, $raw, 'duplicate');
            return;
        }

        // Normalize
        $normalized = $this->normalizer->normalize($raw, $sourceName);

        // Tách skills/benefits
        $skills = $normalized['_skills'] ?? [];
        $benefits = $normalized['_benefits'] ?? [];
        unset($normalized['_skills'], $normalized['_benefits']);

        // Tạo job posting
        $jobPosting = $this->jobPostingService->create(array_merge($normalized, [
            'skills'   => $skills,
            'benefits' => $benefits,
        ]));

        $stats['created']++;
        $this->logCrawl($sourceName, $raw, 'created', $jobPosting->id);
    }

    private function logCrawl(string $source, array $raw, string $status, ?int $jobPostingId = null, ?string $error = null): void
    {
        JobCrawlLog::create([
            'source'          => $source,
            'source_id'       => $raw['source_id'] ?? null,
            'source_url'      => $raw['source_url'] ?? '',
            'job_posting_id'  => $jobPostingId,
            'status'          => $status,
            'raw_data'        => $raw,
            'error_message'   => $error,
            'response_time_ms' => $raw['response_time_ms'] ?? null,
            'created_at'      => now(),
        ]);
    }
}
