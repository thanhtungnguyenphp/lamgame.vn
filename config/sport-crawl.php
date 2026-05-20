<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Sport Crawl Configuration
    |--------------------------------------------------------------------------
    */

    'api_football' => [
        'key' => env('API_FOOTBALL_KEY'),
        'base_url' => 'https://v3.football.api-sports.io',
        'daily_limit' => 100,
    ],

    'balldontlie' => [
        'key' => env('BALLDONTLIE_KEY'),
        'base_url' => 'https://api.balldontlie.io/v1',
    ],

    'scorebat' => [
        'base_url' => 'https://www.scorebat.com/video-api/v1/',
    ],

    'rss_feeds' => [
        'https://vnexpress.net/rss/the-thao.rss',
        'https://bongda24h.vn/RSS/',
    ],

    // 15 leagues to track (API-Football IDs)
    'leagues' => [
        39,  // Premier League
        140, // La Liga
        135, // Serie A
        78,  // Bundesliga
        61,  // Ligue 1
        2,   // Champions League
        3,   // Europa League
        848, // Conference League
        1,   // World Cup
        4,   // Euro
        253, // V-League
        36,  // World Cup Qualifiers Asia
        292, // K-League
        169, // J-League
        307, // Saudi Pro League
    ],

    // Season year (free plan: 2022-2024 only)
    'season' => (int) env('SPORT_CRAWL_SEASON', 2024),

    'retry' => [
        'times' => 3,
        'sleep_ms' => 1000,
    ],

    'timeout' => 10,

    'cleanup_days' => 90,
];
