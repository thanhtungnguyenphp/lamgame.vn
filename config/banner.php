<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Banner Package Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration options for the LamGame Banner package.
    |
    */

    'cache' => [
        'ttl' => env('BANNER_CACHE_TTL', 3600), // Cache TTL in seconds (1 hour)
        'prefix' => env('BANNER_CACHE_PREFIX', 'banner_display:'),
        'enabled' => env('BANNER_CACHE_ENABLED', true),
    ],

    'analytics' => [
        'track_impressions' => env('BANNER_TRACK_IMPRESSIONS', true),
        'track_clicks' => env('BANNER_TRACK_CLICKS', true),
        'ip_anonymization' => env('BANNER_IP_ANONYMIZATION', true),
    ],

    'images' => [
        'disk' => env('BANNER_DISK', 'public'),
        'path' => env('BANNER_PATH', 'banners'),
        'max_size' => env('BANNER_MAX_SIZE', 5120), // KB
        'allowed_types' => ['jpg', 'jpeg', 'png', 'gif', 'webp'],
        'optimize' => env('BANNER_OPTIMIZE_IMAGES', true),
    ],

    'api' => [
        'rate_limit' => env('BANNER_API_RATE_LIMIT', '60,1'), // 60 requests per minute
        'cache_headers' => env('BANNER_API_CACHE_HEADERS', true),
        'cors_enabled' => env('BANNER_API_CORS', true),
    ],

    'admin' => [
        'per_page' => env('BANNER_ADMIN_PER_PAGE', 25),
        'enable_analytics' => env('BANNER_ENABLE_ANALYTICS', true),
        'auto_cache_clear' => env('BANNER_AUTO_CACHE_CLEAR', true),
    ],

    'positions' => [
        'homepage_hero' => 'Homepage Hero',
        'homepage_secondary' => 'Homepage Secondary',
        'sidebar_top' => 'Sidebar Top',
        'sidebar_bottom' => 'Sidebar Bottom',
        'header' => 'Header',
        'footer' => 'Footer',
        'product_detail' => 'Product Detail',
        'category_page' => 'Category Page',
        'checkout' => 'Checkout',
        'custom' => 'Custom Position',
    ],

    'device_types' => [
        'all' => 'All Devices',
        'desktop' => 'Desktop',
        'tablet' => 'Tablet',
        'mobile' => 'Mobile',
    ],

    'banner_types' => [
        'image' => 'Image Banner',
        'html' => 'HTML Content',
        'video' => 'Video Banner',
    ],

    'responsive_breakpoints' => [
        'mobile' => 480,
        'tablet' => 768,
        'desktop' => 1200,
        'large' => 1920,
    ],
];