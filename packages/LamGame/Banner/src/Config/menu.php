<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Banner Menu Configuration
    |--------------------------------------------------------------------------
    |
    | Admin menu configuration for Banner package
    |
    */
    
    [
        'key'        => 'banners',
        'name'       => 'banner::app.admin.banners.title',
        'route'      => 'admin.banners.index',
        'sort'       => 6,
        'icon'       => 'icon-banner',
    ],
    [
        'key'    => 'banners.banners',
        'name'   => 'banner::app.admin.banners.title', 
        'route'  => 'admin.banners.index',
        'sort'   => 1,
        'icon'   => 'icon-list',
    ],
    [
        'key'    => 'banners.analytics',
        'name'   => 'banner::app.admin.analytics.title',
        'route'  => 'admin.banners.analytics', 
        'sort'   => 2,
        'icon'   => 'icon-analytics',
    ],
];