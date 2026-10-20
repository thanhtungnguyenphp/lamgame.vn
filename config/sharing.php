<?php

return [
    'image_disk' => env('SHARING_IMAGE_DISK', 'private'),
    'image_max_bytes' => 8 * 1024 * 1024,
    'image_max_pixels' => env('SHARING_IMAGE_MAX_PIXELS', 24_000_000),
    'image_url_minutes' => 5,
    'recipient_token_minutes' => 10,
    'idempotency_days' => 7,
    // Fail closed: the sharing API is never allowed to trust a JWT alone.
    'require_account_lookup' => env('SHARING_REQUIRE_FIREBASE_ACCOUNT_LOOKUP', true),
    'terms_version' => env('SHARING_TERMS_VERSION', '2026-10-19.v1'),
    'invitation_hours_before_draw' => env('SHARING_INVITATION_HOURS_BEFORE_DRAW', 0),
];
