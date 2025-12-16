<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use App\Models\Blog;
use Carbon\Carbon;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';
    protected $description = 'Generate XML sitemap for jobs and blogs';

    public function handle()
    {
        $this->info('🚀 Generating sitemap...');
        
        try {
            $sitemap = Sitemap::create();
            $baseUrl = config('app.url');

            // 1. Homepage
            $sitemap->add(
                Url::create('/')
                    ->setLastModificationDate(Carbon::now())
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                    ->setPriority(1.0)
            );

            // 2. Jobs listing page
            $sitemap->add(
                Url::create('/viec-lam-game')
                    ->setLastModificationDate(Carbon::now())
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_HOURLY)
                    ->setPriority(0.9)
            );

            // 3. Individual job posts
            $this->info('📋 Adding job posts...');
            $jobs = \DB::table('products as p')
            ->join('product_flat as pf', function($join) {
                $join->on('p.id', '=', 'pf.product_id')
                     ->where('pf.locale', '=', 'vi');
            })
            ->where('p.type', 'job')
            ->where('pf.status', 1)
            ->where('pf.visible_individually', 1)
            ->select('pf.url_key', 'p.updated_at')
            ->get();

        foreach ($jobs as $job) {
            if (!empty($job->url_key)) {
                $sitemap->add(
                    Url::create('/viec-lam/' . $job->url_key)
                        ->setLastModificationDate(Carbon::parse($job->updated_at))
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                        ->setPriority(0.8)
                );
            }
        }
        $this->info("✅ Added {$jobs->count()} job posts");

        // 4. Blog listing page
        $sitemap->add(
            Url::create('/blog')
                ->setLastModificationDate(Carbon::now())
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                ->setPriority(0.9)
        );

        // 5. Individual blog posts
        $this->info('📝 Adding blog posts...');
        $blogs = Blog::published()
            ->select('slug', 'updated_at')
            ->get();

        foreach ($blogs as $blog) {
            $sitemap->add(
                Url::create('/blog/' . $blog->slug)
                    ->setLastModificationDate(Carbon::parse($blog->updated_at))
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                    ->setPriority(0.7)
            );
        }
        $this->info("✅ Added {$blogs->count()} blog posts");

        // 6. Forum pages
        $sitemap->add(
            Url::create('/forum')
                ->setLastModificationDate(Carbon::now())
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_HOURLY)
                ->setPriority(0.8)
        );

        // 7. Static pages
        $staticPages = [
            '/source-game' => 0.7,
        ];

        foreach ($staticPages as $url => $priority) {
            $sitemap->add(
                Url::create($url)
                    ->setLastModificationDate(Carbon::now())
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                    ->setPriority($priority)
            );
        }

        // Save sitemap
        $path = public_path('sitemap.xml');
        $sitemap->writeToFile($path);

        $this->info("✅ Sitemap generated successfully!");
        $this->info("📍 Location: {$path}");
        $this->info("🔗 URL: {$baseUrl}/sitemap.xml");
        
            // Generate stats
            $totalUrls = count($sitemap->getTags());
            $this->info("📊 Total URLs: {$totalUrls}");

            return 0;
            
        } catch (\Exception $e) {
            $this->error('❌ Error: ' . $e->getMessage());
            $this->info('💡 Tip: Check database connection in .env');
            return 1;
        }
    }
}
