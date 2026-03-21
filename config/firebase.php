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
];
