<?php

return [
    'cache' => [
        'ttl_live'     => env('LOTTERY_CACHE_TTL_LIVE', 300),
        'ttl_done'     => env('LOTTERY_CACHE_TTL_DONE', 3600),
        'ttl_history'  => env('LOTTERY_CACHE_TTL_HISTORY', 86400),
        'ttl_keno'     => env('LOTTERY_CACHE_TTL_KENO', 120),
        'ttl_schedule' => env('LOTTERY_CACHE_TTL_SCHEDULE', 604800),
    ],
    'scrape' => [
        'timeout'    => env('LOTTERY_SCRAPE_TIMEOUT', 10),
        'user_agent' => env('LOTTERY_SCRAPE_UA', 'Mozilla/5.0 (compatible; LottoLiveBot/1.0)'),
        'sources'    => [
            'traditional' => 'https://xoso.com.vn',
            'vietlot'     => 'https://vietlott.vn',
            'xoso_me'     => 'https://xoso.me',
            'github_raw'  => 'https://raw.githubusercontent.com/vietvudanh/vietlott-data/main/data',
        ],
    ],
    'draw_times' => [
        'mien-nam'      => '16:15',
        'mien-trung'    => '17:15',
        'mien-bac'      => '18:15',
        'vietlot'       => '18:00',
        'keno_start'    => '06:00',
        'keno_end'      => '21:55',
        'keno_interval' => 10,
    ],
    'games' => ['mega645', 'power655', 'max3d', 'max3d_pro', 'keno'],
    'regions' => ['mien-nam', 'mien-trung', 'mien-bac'],
];
