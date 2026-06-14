<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class PushToGoogleIndex extends Command
{
    protected $signature = 'google:push-index {--type=all : Type to push (jobs|indexnow|all)} {--limit=10 : Number of URLs to push}';
    protected $description = 'Push jobs to Google Indexing API + notify Bing/Yandex via IndexNow';

    private $serviceAccountFile;
    private $accessToken;

    public function handle()
    {
        $type = $this->option('type');
        $limit = (int) $this->option('limit');

        switch ($type) {
            case 'jobs':
                return $this->pushJobs($limit);
            case 'indexnow':
                return $this->pushIndexNow($limit);
            case 'all':
            default:
                $this->pushJobs($limit);
                $this->pushIndexNow($limit);
                return 0;
        }
    }

    /**
     * Google Indexing API — CHỈ dùng cho JobPosting schema.
     */
    private function pushJobs($limit)
    {
        $this->info('📋 Pushing job posts (Google Indexing API)...');

        $this->serviceAccountFile = storage_path('app/google-service-account.json');

        if (!file_exists($this->serviceAccountFile)) {
            $this->warn('⚠️  Service account not found: ' . $this->serviceAccountFile);
            $this->info('📖 Guide: https://developers.google.com/search/apis/indexing-api/v3/prereqs');
            return 1;
        }

        if (!$this->getAccessToken()) {
            $this->error('❌ Failed to get access token');
            return 1;
        }

        $jobs = \DB::table('products as p')
            ->join('product_flat as pf', function ($join) {
                $join->on('p.id', '=', 'pf.product_id')
                     ->where('pf.locale', '=', 'vi');
            })
            ->where('p.type', 'job')
            ->where('pf.status', 1)
            ->where('pf.visible_individually', 1)
            ->select('pf.url_key', 'p.updated_at')
            ->orderBy('p.updated_at', 'desc')
            ->limit($limit)
            ->get();

        $success = 0;
        $failed = 0;

        foreach ($jobs as $job) {
            if (empty($job->url_key)) continue;

            $url = config('app.url') . '/viec-lam/' . $job->url_key;

            if ($this->pushUrl($url)) {
                $success++;
                $this->info("✅ {$url}");
            } else {
                $failed++;
                $this->error("❌ {$url}");
            }

            usleep(300000);
        }

        $this->info("📊 Jobs: {$success} success, {$failed} failed");
        return 0;
    }

    /**
     * IndexNow — thông báo Bing/Yandex về URLs mới/cập nhật.
     * https://www.indexnow.org/documentation
     */
    private function pushIndexNow($limit)
    {
        $this->info('🔔 Pushing URLs via IndexNow (Bing/Yandex)...');

        $key = config('services.indexnow.key');
        $host = parse_url(config('app.url'), PHP_URL_HOST);

        if (empty($key)) {
            $this->warn('⚠️  IndexNow key not set. Add INDEXNOW_KEY to .env');
            return 1;
        }

        $urls = collect();
        $baseUrl = config('app.url');

        // Source games
        \DB::table('products as p')
            ->join('product_flat as pf', fn($j) => $j->on('p.id', '=', 'pf.product_id')->where('pf.locale', '=', 'vi'))
            ->where('p.type', 'downloadable')->where('pf.status', 1)->where('pf.visible_individually', 1)
            ->select('pf.url_key', 'p.updated_at')
            ->orderBy('p.updated_at', 'desc')->limit($limit)
            ->get()
            ->each(fn($p) => $p->url_key ? $urls->push($baseUrl . '/source-game/' . $p->url_key) : null);

        // Blogs
        \App\Models\Blog::published()
            ->select('slug', 'updated_at')
            ->orderBy('updated_at', 'desc')->limit($limit)
            ->get()
            ->each(fn($b) => $urls->push($baseUrl . '/blog/' . $b->slug));

        // Jobs
        \DB::table('products as p')
            ->join('product_flat as pf', fn($j) => $j->on('p.id', '=', 'pf.product_id')->where('pf.locale', '=', 'vi'))
            ->where('p.type', 'job')->where('pf.status', 1)->where('pf.visible_individually', 1)
            ->select('pf.url_key', 'p.updated_at')
            ->orderBy('p.updated_at', 'desc')->limit($limit)
            ->get()
            ->each(fn($j) => $j->url_key ? $urls->push($baseUrl . '/viec-lam/' . $j->url_key) : null);

        // Forum posts
        \DB::table('forum_posts')
            ->where('status', 'published')
            ->select('slug', 'id', 'updated_at')
            ->orderBy('updated_at', 'desc')->limit($limit)
            ->get()
            ->each(fn($p) => $urls->push($baseUrl . '/forum/posts/' . ($p->slug ?? $p->id)));

        // Landing pages
        \App\Models\LandingPage::active()
            ->select('slug', 'updated_at')
            ->orderBy('updated_at', 'desc')->limit($limit)
            ->get()
            ->each(fn($p) => $urls->push($baseUrl . '/p/' . $p->slug));

        if ($urls->isEmpty()) {
            $this->info('No URLs to push.');
            return 0;
        }

        $uniqueUrls = $urls->unique()->values();

        try {
            $response = Http::timeout(15)->post('https://api.indexnow.org/indexnow', [
                'host' => $host,
                'key' => $key,
                'urlList' => $uniqueUrls->all(),
            ]);

            if ($response->successful() || $response->status() === 202) {
                $this->info("✅ IndexNow: {$uniqueUrls->count()} URLs submitted (HTTP {$response->status()})");
            } else {
                $this->error("❌ IndexNow: HTTP {$response->status()} — {$response->body()}");
            }
        } catch (\Exception $e) {
            $this->error("❌ IndexNow error: {$e->getMessage()}");
        }

        return 0;
    }

    private function getAccessToken()
    {
        try {
            $sa = json_decode(file_get_contents($this->serviceAccountFile), true);
            $now = time();
            $header = base64_encode(json_encode(['alg' => 'RS256', 'typ' => 'JWT']));
            $payload = base64_encode(json_encode([
                'iss' => $sa['client_email'],
                'scope' => 'https://www.googleapis.com/auth/indexing',
                'aud' => 'https://oauth2.googleapis.com/token',
                'exp' => $now + 3600, 'iat' => $now,
            ]));
            $sig = '';
            openssl_sign("{$header}.{$payload}", $sig, $sa['private_key'], 'SHA256');

            $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
                'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                'assertion' => "{$header}.{$payload}." . base64_encode($sig),
            ]);

            if ($response->successful()) {
                $this->accessToken = $response->json()['access_token'];
                return true;
            }
            $this->error('Token error: ' . $response->body());
            return false;
        } catch (\Exception $e) {
            $this->error('Auth error: ' . $e->getMessage());
            return false;
        }
    }

    private function pushUrl($url)
    {
        try {
            return Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->accessToken,
                'Content-Type' => 'application/json',
            ])->post('https://indexing.googleapis.com/v3/urlNotifications:publish', [
                'url' => $url, 'type' => 'URL_UPDATED',
            ])->successful();
        } catch (\Exception $e) {
            return false;
        }
    }
}
