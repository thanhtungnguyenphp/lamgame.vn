<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Microsoft Clarity Configuration
    |--------------------------------------------------------------------------
    |
    | Free heatmap and session recording tool by Microsoft
    | Sign up at: https://clarity.microsoft.com
    |
    */

    'project_id' => env('CLARITY_PROJECT_ID', ''),
    
    'enabled' => env('CLARITY_ENABLED', false),
];
