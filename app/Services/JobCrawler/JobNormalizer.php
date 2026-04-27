<?php

namespace App\Services\JobCrawler;

use Illuminate\Support\Str;

/**
 * Chuẩn hóa raw data từ các nguồn crawl → job_postings schema.
 */
class JobNormalizer
{
    private static array $jobTypeMap = [
        'full-time' => 'full-time', 'fulltime' => 'full-time', 'toàn thời gian' => 'full-time',
        'part-time' => 'part-time', 'parttime' => 'part-time', 'bán thời gian' => 'part-time',
        'contract'  => 'contract',  'hợp đồng' => 'contract',
        'freelance' => 'freelance', 'tự do' => 'freelance',
        'internship' => 'internship', 'thực tập' => 'internship',
        'remote' => 'remote', 'từ xa' => 'remote',
    ];

    private static array $experienceMap = [
        'intern'  => 'intern',  'thực tập' => 'intern',
        'fresher' => 'fresher', 'mới ra trường' => 'fresher',
        'junior'  => 'junior',
        'middle'  => 'mid',     'mid' => 'mid', 'trung cấp' => 'mid',
        'senior'  => 'senior',  'cao cấp' => 'senior',
        'lead'    => 'lead',    'trưởng nhóm' => 'lead',
        'manager' => 'manager', 'quản lý' => 'manager',
    ];

    /**
     * Normalize raw crawled data → job_postings compatible array.
     */
    public function normalize(array $raw, string $source): array
    {
        $salary = $this->parseSalary($raw);

        return [
            'title'             => Str::limit(trim($raw['title'] ?? ''), 255, ''),
            'description'       => $this->sanitizeHtml($raw['description'] ?? ''),
            'short_description' => Str::limit(strip_tags($raw['description'] ?? ''), 300, '...'),
            'job_type'          => $this->normalizeJobType($raw['job_type'] ?? ''),
            'experience_level'  => $this->normalizeExperience($raw['experience'] ?? ''),
            'salary_min'        => $salary['min'],
            'salary_max'        => $salary['max'],
            'salary_currency'   => $salary['currency'] ?? 'VND',
            'salary_range'      => $raw['salary_text'] ?? null,
            'location'          => trim($raw['location'] ?? ''),
            'is_remote'         => $this->detectRemote($raw),
            'company_name'      => trim($raw['company_name'] ?? ''),
            'company_logo'      => $raw['company_logo'] ?? null,
            'contact_email'     => $raw['contact_email'] ?? null,
            'application_method' => 'external',
            'application_url'   => $raw['source_url'] ?? null,
            'status'            => config('job_crawler.auto_publish') ? 'active' : 'draft',
            'meta_title'        => Str::limit($raw['title'] ?? '', 70, ''),
            'meta_description'  => Str::limit(strip_tags($raw['description'] ?? ''), 160, '...'),
            'application_deadline' => $raw['valid_through'] ?? null,
            'crawl_source'      => $source,
            'crawl_source_id'   => $raw['source_id'] ?? null,
            'crawl_source_url'  => $raw['source_url'] ?? null,
            // skills & benefits xử lý riêng
            '_skills'           => array_slice(array_unique(array_filter($raw['skills'] ?? [])), 0, 20),
            '_benefits'         => array_slice(array_unique(array_filter($raw['benefits'] ?? [])), 0, 20),
        ];
    }

    private function parseSalary(array $raw): array
    {
        $min = $raw['salary_min'] ?? null;
        $max = $raw['salary_max'] ?? null;
        $currency = $raw['salary_currency'] ?? 'VND';

        // Nếu có giá trị số rõ ràng
        if ($min || $max) {
            return ['min' => $min ? (float) $min : null, 'max' => $max ? (float) $max : null, 'currency' => $currency];
        }

        // Parse từ text: "10.000.000 VND to 20.000.000 VND", "10 - 20 triệu"
        $text = $raw['salary_text'] ?? '';
        if (preg_match('/(\d[\d.,]*)\s*(?:to|-|~|–)\s*(\d[\d.,]*)/i', $text, $m)) {
            $min = (float) str_replace(['.', ','], ['', '.'], $m[1]);
            $max = (float) str_replace(['.', ','], ['', '.'], $m[2]);
            // Nếu giá trị nhỏ (< 1000), có thể đơn vị triệu
            if ($min < 1000) {
                $min *= 1000000;
                $max *= 1000000;
            }
            return ['min' => $min, 'max' => $max, 'currency' => $currency];
        }

        return ['min' => null, 'max' => null, 'currency' => $currency];
    }

    private function normalizeJobType(string $type): ?string
    {
        $lower = Str::lower(trim($type));
        foreach (self::$jobTypeMap as $key => $value) {
            if (str_contains($lower, $key)) {
                return $value;
            }
        }
        return $type ?: null;
    }

    private function normalizeExperience(string $exp): ?string
    {
        $lower = Str::lower(trim($exp));
        foreach (self::$experienceMap as $key => $value) {
            if (str_contains($lower, $key)) {
                return $value;
            }
        }
        return $exp ?: null;
    }

    private function detectRemote(array $raw): bool
    {
        $text = Str::lower(($raw['title'] ?? '') . ' ' . ($raw['location'] ?? '') . ' ' . ($raw['job_type'] ?? ''));
        return str_contains($text, 'remote') || str_contains($text, 'từ xa');
    }

    private function sanitizeHtml(string $html): string
    {
        // Giữ lại tags an toàn, loại bỏ inline styles
        $html = preg_replace('/\s+style="[^"]*"/i', '', $html);
        return strip_tags($html, '<p><br><ul><ol><li><strong><b><em><i><h3><h4>');
    }
}
