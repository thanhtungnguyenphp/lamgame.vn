<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Firebase Service Account Credentials
    |--------------------------------------------------------------------------
    | Path to the Firebase service account JSON file.
    | Download from: Firebase Console → Project Settings → Service accounts
    |                → Generate new private key
    */
    'credentials' => env('FIREBASE_CREDENTIALS', storage_path('app/firebase-credentials.json')),

    /*
    |--------------------------------------------------------------------------
    | Firebase Web Config (for FCM on browser)
    |--------------------------------------------------------------------------
    | Set these in .env. If FIREBASE_WEB_API_KEY is empty, FCM web is disabled.
    */
    'web' => env('FIREBASE_WEB_API_KEY') ? [
        'apiKey'            => env('FIREBASE_WEB_API_KEY'),
        'authDomain'        => env('FIREBASE_WEB_AUTH_DOMAIN'),
        'projectId'         => env('FIREBASE_PROJECT_ID', 'lotto-live-vn'),
        'messagingSenderId' => env('FIREBASE_WEB_MESSAGING_SENDER_ID'),
        'appId'             => env('FIREBASE_WEB_APP_ID'),
        'vapidKey'          => env('FIREBASE_WEB_VAPID_KEY'),
    ] : null,

    /*
    |--------------------------------------------------------------------------
    | FCM Project ID
    |--------------------------------------------------------------------------
    | Firebase project ID, used for FCM v1 API endpoint.
    */
    'project_id' => env('FIREBASE_PROJECT_ID', 'lotto-live-vn'),

    /*
    |--------------------------------------------------------------------------
    | FCM Topic Mapping
    |--------------------------------------------------------------------------
    */
    'topics' => [
        'mien-nam'   => 'kqxs_mien_nam',
        'mien-trung' => 'kqxs_mien_trung',
        'mien-bac'   => 'kqxs_mien_bac',
        'vietlot'    => 'vietlot',
    ],

    /*
    |--------------------------------------------------------------------------
    | Sport Pulse — Notification Channels
    |--------------------------------------------------------------------------
    | Prefix 'sport_' để phân biệt với Lotto-Live notifications.
    */
    'sport_channels' => [
        'match_start' => 'sport_match_start',
        'live_score'  => 'sport_live_score',
        'highlights'  => 'sport_highlights',
    ],
];
