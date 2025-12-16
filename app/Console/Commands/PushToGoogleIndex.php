<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Blog;

class PushToGoogleIndex extends Command
{
    protected $signature = 'google:push-index {--type=all : Type to push (jobs|blogs|all)} {--limit=10 : Number of URLs to push}';
    protected $description = 'Push URLs to Google Indexing API';

    private $serviceAccountFile;
    private $accessToken;

    public function handle()
    {
        $this->info('🚀 Starting Google Indexing API push...');

        // Check if service account file exists
        $this->serviceAccountFile = storage_path('app/google-service-account.json');
        
        if (!file_exists($this->serviceAccountFile)) {
            $this->error('❌ Google service account file not found!');
            $this->info('📝 Please create: ' . $this->serviceAccountFile);
            $this->info('📖 Guide: https://developers.google.com/search/apis/indexing-api/v3/prereqs');
            return 1;
        }

        // Get access token
        if (!$this->getAccessToken()) {
            $this->error('❌ Failed to get access token');
            return 1;
        }

        $type = $this->option('type');
        $limit = (int) $this->option('limit');

        switch ($type) {
            case 'jobs':
                $this->pushJobs($limit);
                break;
            case 'blogs':
                $this->pushBlogs($limit);
                break;
            case 'all':
            default:
                $this->pushJobs($limit);
                $this->pushBlogs($limit);
                break;
        }

        $this->info('✅ Push completed!');
        return 0;
    }

    private function getAccessToken()
    {
        try {
            $serviceAccount = json_decode(file_get_contents($this->serviceAccountFile), true);
            
            $now = time();
            $jwt = [
                'iss' => $serviceAccount['client_email'],
                'scope' => 'https://www.googleapis.com/auth/indexing',
                'aud' => 'https://oauth2.googleapis.com/token',
                'exp' => $now + 3600,
                'iat' => $now
            ];

            // Create JWT token
            $jwtHeader = base64_encode(json_encode(['alg' => 'RS256', 'typ' => 'JWT']));
            $jwtPayload = base64_encode(json_encode($jwt));
            $jwtSignature = '';
            
            openssl_sign(
                $jwtHeader . '.' . $jwtPayload,
                $jwtSignature,
                $serviceAccount['private_key'],
                'SHA256'
            );
            
            $jwtToken = $jwtHeader . '.' . $jwtPayload . '.' . base64_encode($jwtSignature);

            // Exchange JWT for access token
            $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
                'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                'assertion' => $jwtToken
            ]);

            if ($response->successful()) {
                $this->accessToken = $response->json()['access_token'];
                $this->info('✅ Access token obtained');
                return true;
            }

            $this->error('Failed to get access token: ' . $response->body());
            return false;

        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
            return false;
        }
    }

    private function pushJobs($limit)
    {
        $this->info('📋 Pushing job posts...');
        
        $jobs = \DB::table('products as p')
            ->join('product_flat as pf', function($join) {
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

            // Rate limiting: 200 requests per minute
            usleep(300000); // 0.3 seconds
        }

        $this->info("📊 Jobs: {$success} success, {$failed} failed");
    }

    private function pushBlogs($limit)
    {
        $this->info('📝 Pushing blog posts...');
        
        $blogs = Blog::published()
            ->select('slug', 'updated_at')
            ->orderBy('updated_at', 'desc')
            ->limit($limit)
            ->get();

        $success = 0;
        $failed = 0;

        foreach ($blogs as $blog) {
            $url = config('app.url') . '/blog/' . $blog->slug;
            
            if ($this->pushUrl($url)) {
                $success++;
                $this->info("✅ {$url}");
            } else {
                $failed++;
                $this->error("❌ {$url}");
            }

            // Rate limiting
            usleep(300000);
        }

        $this->info("📊 Blogs: {$success} success, {$failed} failed");
    }

    private function pushUrl($url)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->accessToken,
                'Content-Type' => 'application/json',
            ])->post('https://indexing.googleapis.com/v3/urlNotifications:publish', [
                'url' => $url,
                'type' => 'URL_UPDATED'
            ]);

            return $response->successful();

        } catch (\Exception $e) {
            $this->error('Error pushing URL: ' . $e->getMessage());
            return false;
        }
    }
}
