<?php

namespace App\Services\JobCrawler\Sources;

/**
 * Contract cho mỗi nguồn crawl job.
 */
interface CrawlerSourceInterface
{
    /** Tên nguồn, VD: 'topdev', 'itviec' */
    public function sourceName(): string;

    /**
     * Crawl jobs theo keyword.
     * Trả về array of raw job data (chưa normalize).
     *
     * @return array<int, array{
     *   source_id: string,
     *   source_url: string,
     *   title: string,
     *   company_name: ?string,
     *   company_logo: ?string,
     *   description: ?string,
     *   short_description: ?string,
     *   salary_text: ?string,
     *   location: ?string,
     *   job_type: ?string,
     *   experience: ?string,
     *   skills: string[],
     *   benefits: string[],
     * }>
     */
    public function crawl(string $keyword, int $limit = 20): array;
}
