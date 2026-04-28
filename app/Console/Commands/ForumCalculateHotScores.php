<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ForumCalculateHotScores extends Command
{
    protected $signature = 'forum:calculate-hot-scores';
    protected $description = 'Calculate hot_score for forum posts based on recent activity';

    public function handle(): void
    {
        // hot_score = (views_7d * 1 + likes_7d * 3 + comments_7d * 5) / age_hours^1.5
        // Simplified: use total counts weighted by recency
        $updated = DB::update("
            UPDATE forum_posts
            SET hot_score = GREATEST(0, ROUND(
                (views_count + likes_count * 3 + comments_count * 5)
                / POWER(GREATEST(1, TIMESTAMPDIFF(HOUR, created_at, NOW())), 1.5)
                * 1000
            ))
            WHERE status = 'published'
              AND created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
        ");

        // Zero out old posts
        DB::update("
            UPDATE forum_posts SET hot_score = 0
            WHERE created_at < DATE_SUB(NOW(), INTERVAL 30 DAY) AND hot_score > 0
        ");

        $this->info("Updated hot_score for {$updated} posts.");
    }
}
