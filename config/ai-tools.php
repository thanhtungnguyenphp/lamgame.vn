<?php

return [
    'ii_agent' => [
        'url'     => env('II_AGENT_URL', 'http://lg-ii-agent:8000'),
        'timeout' => (int) env('II_AGENT_TIMEOUT', 120),
        'attempts' => (int) env('AI_PROVIDER_ATTEMPTS', 2),
        'retry_delay_ms' => (int) env('AI_PROVIDER_RETRY_DELAY_MS', 300),
    ],

    'ohha_api_key' => env('OHHA_API_KEY', ''),
    'ohha_public_url' => env('OHHA_PUBLIC_URL', 'http://45.77.241.79:8100'),

    'openai_key'    => env('OPENAI_API_KEY'),
    'deepseek_key'  => env('DEEPSEEK_API_KEY'),
    'gemini_key'    => env('GEMINI_API_KEY'),
    'anthropic_key' => env('ANTHROPIC_API_KEY'),

    // Keep legacy "business" as an alias while production uses "studio".
    'plan_aliases' => [
        'business' => 'studio',
    ],

    'models' => [
        'free'  => 'gemini-2.5-flash',
        'basic' => 'gemini-2.5-flash',
        'pro'   => 'gemini-2.5-flash',
        'studio' => [
            'default' => 'gemini-2.5-flash',
            'code'    => 'gemini-2.5-flash',
        ],
        'enterprise' => [
            'default' => 'gemini-2.5-flash',
            'code'    => 'gemini-2.5-flash',
        ],
        'business' => [
            'default' => 'gemini-2.5-flash',
            'code'    => 'gemini-2.5-flash',
        ],
    ],

    // Used only when the primary provider fails; duplicates are removed.
    'fallback_models' => [
        'gemini-2.5-flash',
        'deepseek-chat',
    ],

    'max_tokens' => [
        'concept' => 4096,
        'codegen' => 8192,
        'debug'   => 4096,
        'test'    => 4096,
        'review'  => 4096,
        'asset'   => 1024,
    ],

    'quota_map' => [
        'concept' => 'ai_concept',
        'codegen' => 'ai_generate',
        'debug'   => 'ai_debug',
        'test'    => 'ai_test',
        'review'  => 'ai_code_review',
        'asset'   => 'ai_asset',
        'generate_image' => 'ai_asset',
        'gdd_generator'  => 'ai_generate',
        'chat'    => 'ai_concept',
    ],

    'rate_limit' => [
        'per_minute' => 30,
    ],
];
