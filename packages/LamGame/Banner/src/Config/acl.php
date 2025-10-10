<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Banner ACL Configuration
    |--------------------------------------------------------------------------
    |
    | Access Control List configuration for Banner package
    |
    */
    
    'banners' => [
        'banners' => [
            'view',
            'create', 
            'edit',
            'delete',
            'mass-update',
            'mass-delete',
            'export'
        ],
        'analytics' => [
            'view'
        ],
        'settings' => [
            'view',
            'edit'
        ]
    ],
];