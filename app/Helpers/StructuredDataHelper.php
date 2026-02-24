<?php

namespace App\Helpers;

class StructuredDataHelper
{
    /**
     * Generate JobPosting schema for job detail page
     */
    public static function jobPosting($job)
    {
        $location = $job->location ?? 'Hồ Chí Minh';
        $addressMap = self::getAddressDetails($location);

        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'JobPosting',
            'title' => $job->name,
            'description' => strip_tags($job->description ?? $job->short_description),
            'datePosted' => date('Y-m-d', strtotime($job->created_at)),
            'validThrough' => date('Y-m-d', strtotime('+30 days', strtotime($job->created_at))),
            'employmentType' => self::getEmploymentType($job),
            'hiringOrganization' => [
                '@type' => 'Organization',
                'name' => $job->company_name ?? 'Làm Game',
                'sameAs' => config('app.url'),
                'logo' => config('app.url') . '/assets/logos/png/logo-square-512.png'
            ],
            'jobLocation' => [
                '@type' => 'Place',
                'address' => [
                    '@type' => 'PostalAddress',
                    'streetAddress' => $job->street_address ?? $addressMap['streetAddress'],
                    'addressLocality' => $addressMap['addressLocality'],
                    'addressRegion' => $addressMap['addressRegion'],
                    'postalCode' => $addressMap['postalCode'],
                    'addressCountry' => 'VN'
                ]
            ],
            // Always provide baseSalary - use range when salary not specified
            'baseSalary' => [
                '@type' => 'MonetaryAmount',
                'currency' => 'VND',
                'value' => !empty($job->price) && $job->price > 0
                    ? [
                        '@type' => 'QuantitativeValue',
                        'value' => $job->price,
                        'unitText' => 'MONTH'
                    ]
                    : [
                        '@type' => 'QuantitativeValue',
                        'minValue' => 8000000,
                        'maxValue' => 50000000,
                        'unitText' => 'MONTH'
                    ]
            ]
        ];

        return json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    /**
     * Map location string to full address details for structured data
     */
    private static function getAddressDetails(string $location): array
    {
        $locationMap = [
            'Hồ Chí Minh' => ['streetAddress' => 'Quận 1', 'addressLocality' => 'Hồ Chí Minh', 'addressRegion' => 'Hồ Chí Minh', 'postalCode' => '700000'],
            'Hà Nội'      => ['streetAddress' => 'Quận Cầu Giấy', 'addressLocality' => 'Hà Nội', 'addressRegion' => 'Hà Nội', 'postalCode' => '100000'],
            'Đà Nẵng'     => ['streetAddress' => 'Quận Hải Châu', 'addressLocality' => 'Đà Nẵng', 'addressRegion' => 'Đà Nẵng', 'postalCode' => '550000'],
        ];

        foreach ($locationMap as $key => $details) {
            if (stripos($location, $key) !== false) {
                return $details;
            }
        }

        // Default fallback
        return [
            'streetAddress' => $location,
            'addressLocality' => $location,
            'addressRegion' => $location,
            'postalCode' => '700000',
        ];
    }

    /**
     * Generate Article schema for blog post
     */
    public static function article($blog)
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => $blog->name,
            'description' => $blog->meta_description ?? strip_tags(substr($blog->short_description, 0, 160)),
            'image' => $blog->featured_image,
            'datePublished' => date('c', strtotime($blog->published_at ?? $blog->created_at)),
            'dateModified' => date('c', strtotime($blog->updated_at)),
            'author' => [
                '@type' => 'Organization',
                'name' => 'Làm Game',
                'url' => config('app.url')
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name' => 'Làm Game',
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => config('app.url') . '/logo/lamgame-logo.png'
                ]
            ],
            'mainEntityOfPage' => [
                '@type' => 'WebPage',
                '@id' => config('app.url') . '/blog/' . $blog->slug
            ]
        ];

        return json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    /**
     * Generate BreadcrumbList schema
     */
    public static function breadcrumb($items)
    {
        $listItems = [];
        $position = 1;

        foreach ($items as $item) {
            $listItems[] = [
                '@type' => 'ListItem',
                'position' => $position++,
                'name' => $item['name'],
                'item' => $item['url']
            ];
        }

        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $listItems
        ];

        return json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    /**
     * Generate Organization schema
     */
    public static function organization()
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => 'Làm Game',
            'url' => config('app.url'),
            'logo' => config('app.url') . '/logo/lamgame-logo.png',
            'description' => 'Nền tảng tuyển dụng và chia sẻ kiến thức về phát triển game tại Việt Nam',
            'sameAs' => [
                'https://www.facebook.com/lamgame.vn',
                'https://www.youtube.com/@lamgame',
            ],
            'contactPoint' => [
                '@type' => 'ContactPoint',
                'contactType' => 'Customer Service',
                'email' => 'contact@lamgame.vn'
            ]
        ];

        return json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    /**
     * Generate WebSite schema with search action
     */
    public static function website()
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => 'Làm Game',
            'url' => config('app.url'),
            'potentialAction' => [
                '@type' => 'SearchAction',
                'target' => [
                    '@type' => 'EntryPoint',
                    'urlTemplate' => config('app.url') . '/viec-lam-game?keyword={search_term_string}'
                ],
                'query-input' => 'required name=search_term_string'
            ]
        ];

        return json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    /**
     * Get employment type from job attributes
     */
    private static function getEmploymentType($job)
    {
        // Map job types to schema.org employment types
        $typeMap = [
            'full-time' => 'FULL_TIME',
            'part-time' => 'PART_TIME',
            'contract' => 'CONTRACTOR',
            'internship' => 'INTERN',
            'freelance' => 'CONTRACTOR'
        ];

        $jobType = strtolower($job->job_type ?? 'full-time');
        return $typeMap[$jobType] ?? 'FULL_TIME';
    }

    /**
     * Generate all schemas for a page
     */
    public static function generateAll($type, $data = null)
    {
        $schemas = [];

        // Always include Organization and WebSite
        $schemas[] = self::organization();
        $schemas[] = self::website();

        // Add specific schema based on page type
        switch ($type) {
            case 'job':
                if ($data) {
                    $schemas[] = self::jobPosting($data);
                    $schemas[] = self::breadcrumb([
                        ['name' => 'Trang chủ', 'url' => config('app.url')],
                        ['name' => 'Việc làm Game', 'url' => config('app.url') . '/viec-lam-game'],
                        ['name' => $data->name, 'url' => config('app.url') . '/viec-lam/' . $data->url_key]
                    ]);
                }
                break;

            case 'blog':
                if ($data) {
                    $schemas[] = self::article($data);
                    $schemas[] = self::breadcrumb([
                        ['name' => 'Trang chủ', 'url' => config('app.url')],
                        ['name' => 'Blog', 'url' => config('app.url') . '/blog'],
                        ['name' => $data->name, 'url' => config('app.url') . '/blog/' . $data->slug]
                    ]);
                }
                break;
        }

        return $schemas;
    }
}
