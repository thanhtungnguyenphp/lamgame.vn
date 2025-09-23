<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Menu Cache Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains the cache configuration for the menu system.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Cache Duration
    |--------------------------------------------------------------------------
    |
    | The time in seconds that menu data should be cached.
    | Default is 3600 seconds (1 hour).
    |
    */
    'duration' => env('MENU_CACHE_DURATION', 3600),

    /*
    |--------------------------------------------------------------------------
    | Cache Prefix
    |--------------------------------------------------------------------------
    |
    | The prefix to use for menu cache keys to avoid conflicts.
    |
    */
    'prefix' => env('MENU_CACHE_PREFIX', 'menu_'),

    /*
    |--------------------------------------------------------------------------
    | Frontend Cache Tags
    |--------------------------------------------------------------------------
    |
    | Tags to use for frontend menu caching. This allows for selective
    | cache invalidation.
    |
    */
    'tags' => [
        'frontend_menus',
        'menus',
        'navigation'
    ],

    /*
    |--------------------------------------------------------------------------
    | Auto Clear Events
    |--------------------------------------------------------------------------
    |
    | Events that should trigger automatic menu cache clearing.
    |
    */
    'auto_clear_events' => [
        'menu.created',
        'menu.updated',
        'menu.deleted',
        'menu_item.created',
        'menu_item.updated',
        'menu_item.deleted',
        'menu_item.order_updated',
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache Strategies
    |--------------------------------------------------------------------------
    |
    | Different caching strategies for different use cases.
    |
    */
    'strategies' => [
        'frontend' => [
            'enabled' => env('MENU_FRONTEND_CACHE_ENABLED', true),
            'duration' => env('MENU_FRONTEND_CACHE_DURATION', 3600),
            'tags' => ['frontend_menus', 'menus']
        ],
        
        'admin' => [
            'enabled' => env('MENU_ADMIN_CACHE_ENABLED', false),
            'duration' => env('MENU_ADMIN_CACHE_DURATION', 300), // 5 minutes
            'tags' => ['admin_menus', 'menus']
        ],
        
        'api' => [
            'enabled' => env('MENU_API_CACHE_ENABLED', true),
            'duration' => env('MENU_API_CACHE_DURATION', 1800), // 30 minutes
            'tags' => ['api_menus', 'menus']
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Warmup Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for warming up menu caches.
    |
    */
    'warmup' => [
        'enabled' => env('MENU_CACHE_WARMUP_ENABLED', true),
        'channels' => 'all', // 'all' or array of channel IDs
        'queue' => env('MENU_CACHE_WARMUP_QUEUE', null), // Queue name or null for sync
    ],

];
