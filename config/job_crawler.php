<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Job Crawler Configuration
    |--------------------------------------------------------------------------
    */

    'sources' => ['topdev'],

    'keywords' => [
        'dev'     => ['unity developer', 'game programmer', 'unreal developer', 'cocos2d', 'godot', 'lập trình game', 'game developer'],
        'art'     => ['game artist', '2D artist', '3D artist', 'game designer', 'level designer'],
        'qc'      => ['game tester', 'QA game', 'QC game'],
        'content' => ['content game', 'game writer', 'narrative designer', 'game marketing', 'community manager'],
        'general' => ['game producer', 'game studio', 'game project manager'],
    ],

    'user_agent' => env('JOB_CRAWLER_USER_AGENT', 'LamGameBot/1.0 (+https://lamgame.vn)'),
    'timeout'    => env('JOB_CRAWLER_TIMEOUT', 15),
    'delay'      => env('JOB_CRAWLER_DELAY', 3),       // giây giữa requests
    'max_per_run' => env('JOB_CRAWLER_MAX_PER_RUN', 50),
    'auto_publish' => false,  // admin duyệt trước khi publish
];
