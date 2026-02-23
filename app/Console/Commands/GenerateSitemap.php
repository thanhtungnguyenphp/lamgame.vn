<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\SitemapIndex;
use Spatie\Sitemap\Tags\Url;
use App\Models\Blog;
use Carbon\Carbon;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';
    protected $description = 'Generate XML sitemap index with separate sitemaps for jobs, blogs, forum, and pages';

    public function handle()
    {
        $this->info('🚀 Generating sitemaps...');

        try {
            $baseUrl = config('app.url');

            // Generate individual sitemaps
            $this->generatePagesSitemap();
            $this->generateJobsSitemap();
            $this->generateBlogsSitemap();
            $this->generateForumSitemap();

            // Create sitemap index
            $index = SitemapIndex::create()
                ->add($baseUrl . '/sitemap-pages.xml')
                ->add($baseUrl . '/sitemap-jobs.xml')
                ->add($baseUrl . '/sitemap-blogs.xml')
                ->add($baseUrl . '/sitemap-forum.xml');

            $index->writeToFile(public_path('sitemap.xml'));

            $this->info("✅ Sitemap index generated at /sitemap.xml");
            return 0;

        } catch (\Exception $e) {
            $this->error('❌ Error: ' . $e->getMessage());
            return 1;
        }
    }

    private function generatePagesSitemap()
    {
        $sitemap = Sitemap::create();

        $sitemap->add(Url::create('/')->setLastModificationDate(Carbon::now())->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)->setPriority(1.0));
        $sitemap->add(Url::create('/viec-lam-game')->setLastModificationDate(Carbon::now())->setChangeFrequency(Url::CHANGE_FREQUENCY_HOURLY)->setPriority(0.9));
        $sitemap->add(Url::create('/blog')->setLastModificationDate(Carbon::now())->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)->setPriority(0.9));
        $sitemap->add(Url::create('/forum')->setLastModificationDate(Carbon::now())->setChangeFrequency(Url::CHANGE_FREQUENCY_HOURLY)->setPriority(0.8));
        $sitemap->add(Url::create('/source-game')->setLastModificationDate(Carbon::now())->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)->setPriority(0.7));
        $sitemap->add(Url::create('/lien-he')->setLastModificationDate(Carbon::now())->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)->setPriority(0.5));

        $sitemap->writeToFile(public_path('sitemap-pages.xml'));
        $this->info("✅ Pages sitemap generated");
    }

    private function generateJobsSitemap()
    {
        $sitemap = Sitemap::create();

        $jobs = \DB::table('products as p')
            ->join('product_flat as pf', function ($join) {
                $join->on('p.id', '=', 'pf.product_id')->where('pf.locale', '=', 'vi');
            })
            ->where('p.type', 'job')
            ->where('pf.status', 1)
            ->where('pf.visible_individually', 1)
            ->select('pf.url_key', 'p.updated_at')
            ->get();

        foreach ($jobs as $job) {
            if (!empty($job->url_key)) {
                $sitemap->add(
                    Url::create('/viec-lam/' . rawurlencode($job->url_key))
                        ->setLastModificationDate(Carbon::parse($job->updated_at))
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                        ->setPriority(0.8)
                );
            }
        }

        $sitemap->writeToFile(public_path('sitemap-jobs.xml'));
        $this->info("✅ Jobs sitemap: {$jobs->count()} URLs");
    }

    private function generateBlogsSitemap()
    {
        $sitemap = Sitemap::create();

        $blogs = Blog::published()->select('slug', 'updated_at')->get();

        foreach ($blogs as $blog) {
            $sitemap->add(
                Url::create('/blog/' . $blog->slug)
                    ->setLastModificationDate(Carbon::parse($blog->updated_at))
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                    ->setPriority(0.7)
            );
        }

        $sitemap->writeToFile(public_path('sitemap-blogs.xml'));
        $this->info("✅ Blogs sitemap: {$blogs->count()} URLs");
    }

    private function generateForumSitemap()
    {
        $sitemap = Sitemap::create();

        $posts = \DB::table('forum_posts')
            ->where('status', 'published')
            ->select('id', 'slug', 'updated_at')
            ->get();

        foreach ($posts as $post) {
            $slug = $post->slug ?? $post->id;
            $sitemap->add(
                Url::create('/forum/posts/' . $slug)
                    ->setLastModificationDate(Carbon::parse($post->updated_at))
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                    ->setPriority(0.6)
            );
        }

        $sitemap->writeToFile(public_path('sitemap-forum.xml'));
        $this->info("✅ Forum sitemap: {$posts->count()} URLs");
    }
}
