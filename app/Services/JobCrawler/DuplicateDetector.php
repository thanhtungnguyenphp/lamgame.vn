<?php

namespace App\Services\JobCrawler;

use App\Models\JobCrawlLog;
use App\Models\JobPosting;
use Illuminate\Support\Str;

/**
 * Chống trùng lặp job crawl.
 */
class DuplicateDetector
{
    /**
     * Check trùng theo source + source_id (cùng nguồn).
     */
    public function existsInSource(string $source, string $sourceId): bool
    {
        return JobCrawlLog::where('source', $source)
            ->where('source_id', $sourceId)
            ->whereIn('status', ['created', 'duplicate'])
            ->exists();
    }

    /**
     * Check trùng cross-source bằng title + company hash.
     */
    public function existsCrossSource(string $title, string $companyName): bool
    {
        $hash = $this->buildHash($title, $companyName);

        return JobPosting::where('title', 'LIKE', '%' . addcslashes(Str::limit($title, 100, ''), '%_') . '%')
            ->where('company_name', 'LIKE', '%' . addcslashes(Str::limit($companyName, 100, ''), '%_') . '%')
            ->exists();
    }

    /**
     * Check cả 2 loại trùng.
     */
    public function isDuplicate(string $source, string $sourceId, string $title, string $companyName): bool
    {
        return $this->existsInSource($source, $sourceId)
            || $this->existsCrossSource($title, $companyName);
    }

    private function buildHash(string $title, string $companyName): string
    {
        return md5(Str::lower(trim($title)) . '|' . Str::lower(trim($companyName)));
    }
}
