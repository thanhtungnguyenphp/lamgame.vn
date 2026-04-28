<?php

return [
    'posts_per_page'     => 15,
    'comments_per_page'  => 20,
    'max_tags_per_post'  => 10,
    'max_comment_depth'  => 5,
    'auto_lock_after_days' => 90,

    'rate_limits' => [
        'posts'    => 5,   // per hour
        'comments' => 30,  // per hour
        'votes'    => 60,  // per hour
        'reports'  => 10,  // per hour
    ],

    'cooldown_seconds' => 120, // between posts

    'honeypot_field' => 'website_url', // hidden field name for spam detection
];
