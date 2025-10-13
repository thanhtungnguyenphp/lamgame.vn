<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Google Analytics Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for Google Analytics tracking
    |
    */

    'tracking_id' => env('GOOGLE_ANALYTICS_ID', 'G-WPXBBHC7XJ'),
    
    'enabled' => env('GOOGLE_ANALYTICS_ENABLED', true),
    
    /*
    |--------------------------------------------------------------------------
    | Privacy Settings
    |--------------------------------------------------------------------------
    |
    | These settings help ensure GDPR compliance
    |
    */
    
    'anonymize_ip' => env('GOOGLE_ANALYTICS_ANONYMIZE_IP', true),
    
    'allow_google_signals' => env('GOOGLE_ANALYTICS_ALLOW_SIGNALS', false),
    
    /*
    |--------------------------------------------------------------------------
    | Enhanced E-commerce Settings
    |--------------------------------------------------------------------------
    |
    | Enable enhanced e-commerce tracking for job applications, 
    | course enrollments, etc.
    |
    */
    
    'enhanced_ecommerce' => env('GOOGLE_ANALYTICS_ENHANCED_ECOMMERCE', true),
    
    /*
    |--------------------------------------------------------------------------
    | Custom Events
    |--------------------------------------------------------------------------
    |
    | Define custom events that should be tracked
    |
    */
    
    'events' => [
        'job_view' => true,
        'job_application' => true,
        'blog_view' => true,
        'forum_post' => true,
        'course_enrollment' => true,
        'contact_form_submit' => true,
        'newsletter_signup' => true,
        'file_download' => true,
    ],
];