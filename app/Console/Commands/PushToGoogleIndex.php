<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class PushToGoogleIndex extends Command
{
    protected $signature = 'google:push-index {--type=all : Type to push (jobs|ping-sitemap|all)} {--limit=10 : Number of URLs to push}';
    protected $description = 'Push job URLs to Google Indexing API + ping sitemap for other content';

    private $serviceAccountFile;
    private $accessToken;

    public function handle()
    {
        $type = $this->option('type');
        $limit = (int) $this->option('limit');

        switch ($type) {
            case 'jobs':
                return $this->pushJobs($limit);
            case 'ping-sitemap':
                return $this->pingSitemap();
            case 'all':
            default:
                $this->pushJobs($limit);
                $this->pingSitemap();
                return 0;
        }
    }

    /**
     * Google Indexing API — CHỈ dùng cho JobPosting schema.
     * https://developers.google.com/search/apis/indexing-api/v3/quickstart
     */
    private function pushJobs($limit)
    {
        $this->info('📋 Pushing job posts (Indexing API)...');

        $this->serviceAccountFile = storage_path('app/google-service-account.json');

        if (!file_exists($this->serviceAccountFile)) {
            $this->warn('⚠️  Google service account file not found, skipping Indexing API.');
            $this->info('📝 Create: ' . $this->serviceAccountFile);
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
     * Ping sitemap — cách chính thống để thông báo Google về content mới
     * (blogs, source games, sellers, landing pages, v.v.)
     */
    private function pingSitemap()
    {
        $this->info('🔔 Pinging sitemap to search engines...');

        $sitemapUrl = config('app.url') . '/sitemap.xml';

        $engines = [
            'Google' => 'https://www.google.com/ping?sitemap=' . urlencode($sitemapUrl),
            'Bing/IndexNow' => 'https://www.bing.com/ping?sitemap=' . urlencode($sitemapUrl),
        ];

        foreach ($engines as $name => $pingUrl) {
            try {
                $response = Http::timeout(10)->get($pingUrl);
                if ($response->successful()) {
                    $this->info("✅ {$name}: pinged OK");
                } else {
                    $this->warn("⚠️  {$name}: HTTP {$response->status()}");
                }
            } catch (\Exception $e) {
                $this->error("❌ {$name}: {$e->getMessage()}");
            }
        }

        return 0;
    }

    private function getAccessToken()
    {
        try {
            $serviceAccount = json_decode(file_get_contents($this->serviceAccountFile), true);

            $now = time();
            $jwtHeader = base64_encode(json_encode(['alg' => 'RS256', 'typ' => 'JWT']));
            $jwtPayload = base64_encode(json_encode([
                'iss' => $serviceAccount['client_email'],
                'scope' => 'https://www.googleapis.com/auth/indexing',
                'aud' => 'https://oauth2.googleapis.com/token',
                'exp' => $now + 3600,
                'iat' => $now,
            ]));

            $jwtSignature = '';
            openssl_sign($jwtHeader . '.' . $jwtPayload, $jwtSignature, $serviceAccount['private_key'], 'SHA256');

            $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
                'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                'assertion' => $jwtHeader . '.' . $jwtPayload . '.' . base64_encode($jwtSignature),
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
                'url' => $url,
                'type' => 'URL_UPDATED',
            ])->successful();
        } catch (\Exception $e) {
            $this->error('Push error: ' . $e->getMessage());
            return false;
        }
    }
}
