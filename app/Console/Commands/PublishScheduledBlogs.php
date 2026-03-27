<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Webbycrown\BlogBagisto\Models\Blog;

class PublishScheduledBlogs extends Command
{
    protected $signature = 'blog:publish-scheduled';
    protected $description = 'Publish blogs that have reached their scheduled publish date';

    public function handle(): int
    {
        $count = Blog::where('status', 0)
            ->where('published_at', '<=', now())
            ->update(['status' => 1]);

        $this->info("Published {$count} scheduled blog(s).");

        return self::SUCCESS;
    }
}
