<?php

return [
    'ii_agent' => [
        'url'     => env('II_AGENT_URL', 'http://lg-ii-agent:8000'),
        'timeout' => (int) env('II_AGENT_TIMEOUT', 120),
    ],

    // OHHA Core API key (for proxy auth)
    'ohha_api_key' => env('OHHA_API_KEY', ''),

    // OHHA public URL (accessible from browser)
    'ohha_public_url' => env('OHHA_PUBLIC_URL', 'http://45.77.241.79:8100'),

    // LLM provider keys
    'openai_key'    => env('OPENAI_API_KEY'),
    'deepseek_key'  => env('DEEPSEEK_API_KEY'),
    'gemini_key'    => env('GEMINI_API_KEY'),
    'anthropic_key' => env('ANTHROPIC_API_KEY'),

    // Model per plan
    'models' => [
        'free'     => 'gemini-2.5-flash',
        'pro'      => 'gemini-2.5-flash',
        'business' => [
            'default' => 'gemini-2.5-flash',
            'code'    => 'gemini-2.5-flash',
        ],
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
        'chat'    => 'ai_concept',  // Chat uses same quota as concept
    ],

    'rate_limit' => [
        'per_minute' => 30,
    ],
];
