<?php

return [
    /*
     * Revenue catalog selected from paid downloadable products that currently
     * have at least one downloadable-link record. This list controls
     * merchandising labels only and never overwrites product data in the DB.
     */
    'featured_skus' => [
        'tower-defense',
        'endless-runner',
        'top-down-shooter',
        'bubble-shooter',
        'chess-ai',
        'roguelike-dungeon',
        'match3-candy',
        'card-game-engine',
        'quiz-trivia',
        'fps-multiplayer',
    ],

    /*
     * Assets below were verified against current public demos with an
     * automated browser. Entries are intentionally absent for broken or
     * unverified demos; never infer them from SKU/name alone.
     */
    'verified_assets' => [
        'top-down-shooter' => [
            'demo_path' => '/games/ban-tau/',
            'source_path' => 'games/ban-tau',
            'screenshots' => [
                '/images/source-games/top-down-shooter/screenshot-1.webp',
                '/images/source-games/top-down-shooter/screenshot-2.webp',
                '/images/source-games/top-down-shooter/screenshot-3.webp',
            ],
        ],
        'endless-runner' => [
            'demo_path' => '/games/chay-bat-tan/',
            'source_path' => 'games/chay-bat-tan',
            'screenshots' => [
                '/images/source-games/endless-runner/screenshot-1.webp',
                '/images/source-games/endless-runner/screenshot-2.webp',
                '/images/source-games/endless-runner/screenshot-3.webp',
            ],
        ],
        'roguelike-dungeon' => [
            'demo_path' => '/games/thoat-me-cung/',
            'source_path' => 'games/thoat-me-cung',
            'screenshots' => [
                '/images/source-games/roguelike-dungeon/screenshot-1.webp',
                '/images/source-games/roguelike-dungeon/screenshot-2.webp',
                '/images/source-games/roguelike-dungeon/screenshot-3.webp',
            ],
        ],
        'card-game-engine' => [
            'demo_path' => '/games/xep-bai-mot-minh/',
            'source_path' => 'games/xep-bai-mot-minh',
            'screenshots' => [
                '/images/source-games/card-game-engine/screenshot-1.webp',
                '/images/source-games/card-game-engine/screenshot-2.webp',
                '/images/source-games/card-game-engine/screenshot-3.webp',
            ],
        ],
        'quiz-trivia' => [
            'demo_path' => '/games/do-vui-kien-thuc/',
            'source_path' => 'games/do-vui-kien-thuc',
            'screenshots' => [
                '/images/source-games/quiz-trivia/screenshot-1.webp',
                '/images/source-games/quiz-trivia/screenshot-2.webp',
                '/images/source-games/quiz-trivia/screenshot-3.webp',
            ],
        ],
    ],

    'quality_requirements' => [
        'minimum_images' => 3,
        'requires_download' => true,
        'requires_demo' => true,
        'requires_documentation' => true,
        'requires_license_terms' => true,
    ],
];
