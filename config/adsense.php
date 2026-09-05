<?php

return [
    /*
     * AdSense stays disabled until a new publisher configuration is reviewed.
     * Never hardcode publisher IDs or ad script snippets in Blade layouts.
     */
    'enabled' => env('ADSENSE_ENABLED', false),
    'client' => env('ADSENSE_CLIENT', ''),
    'seller_id' => env('ADSENSE_SELLER_ID', ''),

    /*
     * Initial monetization scope: editorial pages only. Checkout, account,
     * AI, hire, source products and interactive games are deliberately excluded.
     */
    'allowed_routes' => [
        'lamgame.blog',
        'blog.show',
    ],

    'excluded_path_prefixes' => [
        'checkout',
        'customer',
        'account',
        'admin',
        'api',
        'games',
        'choi-game',
        'source-game',
        'ai-tools',
        'hire',
        'portfolio',
    ],
];
