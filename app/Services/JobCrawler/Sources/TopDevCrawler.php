<?php

namespace App\Services\JobCrawler\Sources;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

/**
 * Crawl job từ TopDev.vn
 *
 * Chiến lược:
 * 1. Fetch listing page HTML → extract job detail slugs
 * 2. Fetch từng detail page → extract JSON-LD (schema.org/JobPosting)
 */
class TopDevCrawler implements CrawlerSourceInterface
{
    private const BASE_URL = 'https://topdev.vn';
    private Client $client;
    private int $delay;

    public function __construct()
    {
        $this->client = new Client([
            'timeout' => config('job_crawler.timeout', 15),
            'headers' => [
                'User-Agent' => config('job_crawler.user_agent', 'LamGameBot/1.0'),
                'Accept'     => 'text/html,application/xhtml+xml',
            ],
            'allow_redirects' => true,
        ]);
        $this->delay = config('job_crawler.delay', 3);
    }

    public function sourceName(): string
    {
        return 'topdev';
    }

    public function crawl(string $keyword, int $limit = 20): array
    {
        $slugs = $this->fetchListingSlugs($keyword, $limit);

        if (empty($slugs)) {
            Log::info("[TopDevCrawler] Không tìm thấy job nào cho keyword: {$keyword}");
            return [];
        }

        $jobs = [];
        foreach (array_slice($slugs, 0, $limit) as $slug) {
            sleep($this->delay);

            try {
                $job = $this->fetchJobDetail($slug);
                if ($job) {
                    $jobs[] = $job;
                }
            } catch (\Throwable $e) {
                Log::warning("[TopDevCrawler] Lỗi fetch detail {$slug}: {$e->getMessage()}");
            }
        }

        return $jobs;
    }

    /**
     * Fetch listing page, extract job detail slugs từ href="/detail-jobs/..."
     */
    private function fetchListingSlugs(string $keyword, int $limit): array
    {
        $slugs = [];
        $page = 1;
        $maxPages = (int) ceil($limit / 15); // ~15 jobs/page

        while (count($slugs) < $limit && $page <= $maxPages) {
            $url = self::BASE_URL . '/viec-lam-it?' . http_build_query([
                'keyword' => $keyword,
                'page'    => $page,
            ]);

            try {
                $response = $this->client->get($url);
                $html = (string) $response->getBody();

                // Extract: /detail-jobs/{slug}
                preg_match_all('#/detail-jobs/([a-z0-9\-]+)#', $html, $matches);
                $found = array_unique($matches[1] ?? []);

                if (empty($found)) {
                    break;
                }

                $slugs = array_merge($slugs, $found);
                $page++;

                if ($page <= $maxPages) {
                    sleep($this->delay);
                }
            } catch (\Throwable $e) {
                Log::warning("[TopDevCrawler] Lỗi fetch listing page {$page}: {$e->getMessage()}");
                break;
            }
        }

        return array_unique($slugs);
    }

    /**
     * Fetch detail page, extract JSON-LD JobPosting structured data.
     */
    private function fetchJobDetail(string $slug): ?array
    {
        $url = self::BASE_URL . '/detail-jobs/' . $slug;
        $start = microtime(true);

        $response = $this->client->get($url);
        $html = (string) $response->getBody();
        $responseTime = (int) ((microtime(true) - $start) * 1000);

        // Extract JSON-LD
        preg_match_all('#<script type="application/ld\+json">(.*?)</script>#s', $html, $matches);

        $jobData = null;
        foreach ($matches[1] ?? [] as $json) {
            $decoded = json_decode($json, true);
            if (($decoded['@type'] ?? '') === 'JobPosting') {
                $jobData = $decoded;
                break;
            }
        }

        if (!$jobData) {
            Log::debug("[TopDevCrawler] Không tìm thấy JSON-LD JobPosting cho: {$slug}");
            return null;
        }

        // Extract source_id từ slug (số cuối cùng)
        preg_match('/(\d+)$/', $slug, $idMatch);
        $sourceId = $idMatch[1] ?? $slug;

        return [
            'source_id'        => $sourceId,
            'source_url'       => $url,
            'title'            => $jobData['title'] ?? '',
            'company_name'     => $jobData['hiringOrganization']['name'] ?? '',
            'company_logo'     => $jobData['hiringOrganization']['logo'] ?? null,
            'description'      => $jobData['description'] ?? '',
            'short_description' => null,
            'salary_text'      => $jobData['baseSalary']['value']['value'] ?? null,
            'salary_min'       => $jobData['baseSalary']['value']['minValue'] ?? null,
            'salary_max'       => $jobData['baseSalary']['value']['maxValue'] ?? null,
            'salary_currency'  => $jobData['baseSalary']['currency'] ?? 'VND',
            'location'         => $this->extractLocation($jobData),
            'job_type'         => $this->extractJobType($jobData),
            'experience'       => $this->extractExperience($jobData),
            'skills'           => $this->extractSkills($jobData),
            'benefits'         => $this->extractBenefits($jobData),
            'valid_through'    => $jobData['validThrough'] ?? null,
            'contact_email'    => null,
            'response_time_ms' => $responseTime,
        ];
    }

    private function extractLocation(array $data): string
    {
        $loc = $data['jobLocation']['address'] ?? [];
        $parts = array_filter([
            $loc['addressLocality'] ?? '',
            $loc['addressRegion'] ?? '',
        ]);
        return implode(', ', $parts) ?: 'Vietnam';
    }

    private function extractJobType(array $data): string
    {
        $types = $data['employmentType'] ?? [];
        if (is_array($types)) {
            $map = ['FULL_TIME' => 'full-time', 'PART_TIME' => 'part-time', 'CONTRACT' => 'contract', 'INTERN' => 'internship'];
            foreach ($types as $t) {
                if (isset($map[strtoupper($t)])) {
                    return $map[strtoupper($t)];
                }
            }
        }
        return 'full-time';
    }

    private function extractExperience(array $data): string
    {
        $months = $data['experienceRequirements']['monthsOfExperience'] ?? 0;
        if ($months <= 0) return 'fresher';
        if ($months <= 12) return 'junior';
        if ($months <= 36) return 'mid';
        return 'senior';
    }

    private function extractSkills(array $data): array
    {
        $skills = $data['skills'] ?? '';
        if (is_string($skills)) {
            return array_map('trim', explode(',', $skills));
        }
        return is_array($skills) ? $skills : [];
    }

    private function extractBenefits(array $data): array
    {
        $benefits = $data['jobBenefits'] ?? '';
        if (is_string($benefits)) {
            // Parse HTML list items
            preg_match_all('#<li[^>]*>(.*?)</li>#s', $benefits, $m);
            if (!empty($m[1])) {
                return array_values(array_filter(
                    array_map(fn($b) => trim(strip_tags($b)), $m[1]),
                    fn($b) => $b !== ''
                ));
            }
            // Fallback: split by newline
            return array_values(array_filter(array_map('trim', preg_split('/[\n;]/', strip_tags($benefits)))));
        }
        return is_array($benefits) ? array_values(array_filter($benefits)) : [];
    }
}
