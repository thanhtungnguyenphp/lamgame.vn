<?php

return [
    'api_key'    => env('YOUTUBE_API_KEY', ''),
    'channel_id' => env('YOUTUBE_CHANNEL_ID', 'UCv2lripWdZDKtlrRy1J0dBw'),
    'cache_ttl'  => env('YOUTUBE_CACHE_TTL', 3600), // 1 hour
];
