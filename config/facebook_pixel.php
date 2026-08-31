<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Facebook Pixel Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for Facebook/Meta Pixel tracking for retargeting ads
    |
    */

    'pixel_id' => env('FACEBOOK_PIXEL_ID', ''),
    
    'enabled' => env('FACEBOOK_PIXEL_ENABLED', false),
    
    /*
    |--------------------------------------------------------------------------
    | Events to Track
    |--------------------------------------------------------------------------
    |
    | Standard Facebook events to track automatically
    |
    */
    
    'track_page_view' => true,
    
    'track_contact' => true,
    
    'track_lead' => true,
    
    /*
    |--------------------------------------------------------------------------
    | Advanced Matching
    |--------------------------------------------------------------------------
    |
    | Enable advanced matching for better attribution
    |
    */
    
    'advanced_matching' => env('FACEBOOK_PIXEL_ADVANCED_MATCHING', false),
];
