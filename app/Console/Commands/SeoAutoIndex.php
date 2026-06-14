<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class SeoAutoIndex extends Command
{
    protected $signature = 'seo:auto-index {--force : Push all URLs regardless of cache}';
    protected $description = 'Auto-detect new/updated content and push to IndexNow + Google ping';

    public function handle()
    {
        $this->info('🔍 Scanning for new/updated URLs...');

        $baseUrl = config('app.url');
        $lastRun = Cache::get('seo:auto-index:last_run', now()->subDay());
        $newUrls = collect();

        // Blogs created/updated since last run
        \App\Models\Blog::published()
            ->where('updated_at', '>', $lastRun)
            ->select('slug')
            ->get()
            ->each(fn($b) => $newUrls->push($baseUrl . '/blog/' . $b->slug));

        // Source games
        \DB::table('products as p')
            ->join('product_flat as pf', fn($j) => $j->on('p.id', '=', 'pf.product_id')->where('pf.locale', '=', 'vi'))
            ->where('p.type', 'downloadable')->where('pf.status', 1)->where('pf.visible_individually', 1)
            ->where('p.updated_at', '>', $lastRun)
            ->select('pf.url_key')
            ->get()
            ->each(fn($p) => $p->url_key ? $newUrls->push($baseUrl . '/source-game/' . $p->url_key) : null);

        // Forum posts
        \DB::table('forum_posts')
            ->where('status', 'published')
            ->where('updated_at', '>', $lastRun)
            ->select('slug', 'id')
            ->get()
            ->each(fn($p) => $newUrls->push($baseUrl . '/forum/posts/' . ($p->slug ?? $p->id)));

        // Landing pages
        \App\Models\LandingPage::active()
            ->where('updated_at', '>', $lastRun)
            ->select('slug')
            ->get()
            ->each(fn($p) => $newUrls->push($baseUrl . '/p/' . $p->slug));

        if ($this->option('force')) {
            $newUrls = $this->getAllUrls($baseUrl);
        }

        $uniqueUrls = $newUrls->unique()->values();

        if ($uniqueUrls->isEmpty()) {
            $this->info('✅ No new URLs since last run.');
            Cache::put('seo:auto-index:last_run', now(), now()->addDays(7));
            return 0;
        }

        $this->info("📦 Found {$uniqueUrls->count()} new/updated URLs");

        // Push to IndexNow
        $this->pushIndexNow($uniqueUrls);

        // Ping Google sitemap
        $this->pingGoogle();

        Cache::put('seo:auto-index:last_run', now(), now()->addDays(7));
        return 0;
    }

    private function pushIndexNow($urls)
    {
        $key = config('services.indexnow.key');
        $host = parse_url(config('app.url'), PHP_URL_HOST);

        if (empty($key)) {
            $this->warn('⚠️ INDEXNOW_KEY not set');
            return;
        }

        // IndexNow max 10000 URLs per request, batch if needed
        foreach ($urls->chunk(500) as $chunk) {
            try {
                $response = Http::timeout(15)->post('https://api.indexnow.org/indexnow', [
                    'host' => $host,
                    'key' => $key,
                    'urlList' => $chunk->values()->all(),
                ]);

                if ($response->successful() || $response->status() === 202) {
                    $this->info("✅ IndexNow: {$chunk->count()} URLs (HTTP {$response->status()})");
                } else {
                    $this->error("❌ IndexNow: HTTP {$response->status()}");
                }
            } catch (\Exception $e) {
                $this->error("❌ " . $e->getMessage());
            }
        }
    }

    private function pingGoogle()
    {
        // Google deprecated /ping in 2023. Use Search Console API or just rely on sitemap auto-discovery.
        $this->info('📡 Sitemap auto-discovery via robots.txt (Google crawls sitemap.xml automatically)');
    }

    private function getAllUrls($baseUrl)
    {
        $urls = collect();

        \App\Models\Blog::published()->select('slug')->get()
            ->each(fn($b) => $urls->push($baseUrl . '/blog/' . $b->slug));

        \DB::table('products as p')
            ->join('product_flat as pf', fn($j) => $j->on('p.id', '=', 'pf.product_id')->where('pf.locale', '=', 'vi'))
            ->where('p.type', 'downloadable')->where('pf.status', 1)->where('pf.visible_individually', 1)
            ->select('pf.url_key')->get()
            ->each(fn($p) => $p->url_key ? $urls->push($baseUrl . '/source-game/' . $p->url_key) : null);

        \DB::table('forum_posts')->where('status', 'published')->select('slug', 'id')->get()
            ->each(fn($p) => $urls->push($baseUrl . '/forum/posts/' . ($p->slug ?? $p->id)));

        \App\Models\LandingPage::active()->select('slug')->get()
            ->each(fn($p) => $urls->push($baseUrl . '/p/' . $p->slug));

        return $urls;
    }
}
