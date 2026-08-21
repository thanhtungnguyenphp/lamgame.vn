<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

/**
 * Phase 1 SEO Audit — Export all URLs for classification
 * 
 * Usage: php artisan seo:export-urls
 * Output: docs/seo/url_audit_export.csv
 */
class ExportUrlsForAudit extends Command
{
    protected $signature = 'seo:export-urls {--include-content : Include content type detection}';
    protected $description = 'Export all website URLs for SEO audit (Phase 1)';

    public function handle()
    {
        $this->info('🔍 Starting URL export for SEO audit...');
        
        $urls = collect();
        
        // 1. Blog URLs
        $this->info('📝 Fetching blog URLs...');
        $blogs = DB::table('blogs')
            ->select('id', 'slug', 'name', 'status', 'created_at', 'updated_at')
            ->get();
        
        foreach ($blogs as $blog) {
            $urls->push([
                'url' => '/blog/' . $blog->slug,
                'title' => $blog->name,
                'content_type' => 'blog',
                'category' => $this->detectBlogCategory($blog->slug, $blog->name),
                'topic' => $this->detectTopic($blog->slug, $blog->name),
                'status' => $blog->status ? 'active' : 'draft',
                'created_at' => $blog->created_at,
                'updated_at' => $blog->updated_at,
                'action' => '', // To be filled manually
                'redirect_url' => '',
                'priority' => '',
                'note' => '',
            ]);
        }
        $this->info("  → Found {$blogs->count()} blog posts");
        
        // 2. Forum URLs
        $this->info('💬 Fetching forum URLs...');
        $forumPosts = DB::table('forum_posts')
            ->select('id', 'slug', 'title', 'status', 'created_at', 'updated_at')
            ->get();
        
        foreach ($forumPosts as $post) {
            $urls->push([
                'url' => '/forum/' . $post->slug,
                'title' => $post->title,
                'content_type' => 'forum',
                'category' => 'Forum',
                'topic' => 'Game Development',
                'status' => $post->status === 'published' ? 'active' : $post->status,
                'created_at' => $post->created_at,
                'updated_at' => $post->updated_at,
                'action' => 'KEEP',
                'redirect_url' => '',
                'priority' => '',
                'note' => '',
            ]);
        }
        $this->info("  → Found {$forumPosts->count()} forum posts");
        
        // 3. Source Game URLs
        $this->info('🎮 Fetching source game URLs...');
        $products = DB::table('product_flat')
            ->where('type', 'downloadable')
            ->select('id', 'url_key', 'name', 'status', 'created_at', 'updated_at')
            ->get();
        
        foreach ($products as $product) {
            $urls->push([
                'url' => '/source-game/' . $product->url_key,
                'title' => $product->name,
                'content_type' => 'source_game',
                'category' => 'Source Game',
                'topic' => 'Game Development',
                'status' => $product->status ? 'active' : 'inactive',
                'created_at' => $product->created_at,
                'updated_at' => $product->updated_at,
                'action' => 'KEEP',
                'redirect_url' => '',
                'priority' => '',
                'note' => '',
            ]);
        }
        $this->info("  → Found {$products->count()} source games");
        
        // 4. Job URLs
        $this->info('💼 Fetching job URLs...');
        $jobs = DB::table('job_postings')
            ->select('id', 'slug', 'title', 'status', 'created_at', 'updated_at')
            ->get();
        
        foreach ($jobs as $job) {
            $urls->push([
                'url' => '/viec-lam-game/' . $job->slug,
                'title' => $job->title,
                'content_type' => 'job',
                'category' => 'Việc làm',
                'topic' => 'Game Developer Career',
                'status' => $job->status,
                'created_at' => $job->created_at,
                'updated_at' => $job->updated_at,
                'action' => 'KEEP',
                'redirect_url' => '',
                'priority' => '',
                'note' => '',
            ]);
        }
        $this->info("  → Found {$jobs->count()} job postings");
        
        // 5. Landing Pages
        $this->info('📄 Fetching landing pages...');
        $landingPages = DB::table('landing_pages')
            ->select('id', 'slug', 'name', 'status', 'created_at', 'updated_at')
            ->whereNull('deleted_at')
            ->get();
        
        foreach ($landingPages as $page) {
            $action = $this->classifyLandingPage($page->slug);
            $urls->push([
                'url' => '/p/' . $page->slug,
                'title' => $page->name,
                'content_type' => 'landing_page',
                'category' => 'Landing Page',
                'topic' => $this->detectTopic($page->slug, $page->name),
                'status' => $page->status ? 'active' : 'inactive',
                'created_at' => $page->created_at,
                'updated_at' => $page->updated_at,
                'action' => $action,
                'redirect_url' => '',
                'priority' => $action === 'REMOVE' ? 'P0' : '',
                'note' => $action === 'REMOVE' ? 'Non-Game Dev content' : '',
            ]);
        }
        $this->info("  → Found {$landingPages->count()} landing pages");
        
        // 6. Static Routes (Lottery, Sport, etc.)
        $this->info('🚫 Adding non-Game Dev routes for audit...');
        $nonGameDevRoutes = [
            // Lottery
            ['url' => '/xo-so', 'title' => 'Xổ số', 'topic' => 'Lottery'],
            ['url' => '/xo-so/mien-bac', 'title' => 'Xổ số Miền Bắc', 'topic' => 'Lottery'],
            ['url' => '/xo-so/mien-trung', 'title' => 'Xổ số Miền Trung', 'topic' => 'Lottery'],
            ['url' => '/xo-so/mien-nam', 'title' => 'Xổ số Miền Nam', 'topic' => 'Lottery'],
            ['url' => '/xo-so/vietlott', 'title' => 'Vietlott', 'topic' => 'Lottery'],
            ['url' => '/xo-so/vietlott/keno', 'title' => 'Keno', 'topic' => 'Lottery'],
            ['url' => '/xo-so/vietlott/power-655', 'title' => 'Power 6/55', 'topic' => 'Lottery'],
            ['url' => '/xo-so/vietlott/mega-645', 'title' => 'Mega 6/45', 'topic' => 'Lottery'],
            ['url' => '/xo-so/thong-ke', 'title' => 'Thống kê xổ số', 'topic' => 'Lottery'],
            ['url' => '/xo-so/do-so', 'title' => 'Dò số', 'topic' => 'Lottery'],
            ['url' => '/xo-so/lich-quay', 'title' => 'Lịch quay', 'topic' => 'Lottery'],
            ['url' => '/lottolive', 'title' => 'Lotto Live', 'topic' => 'Lottery'],
            
            // Sport
            ['url' => '/the-thao', 'title' => 'Thể thao', 'topic' => 'Sport'],
            ['url' => '/the-thao/lich-thi-dau', 'title' => 'Lịch thi đấu', 'topic' => 'Sport'],
            ['url' => '/world-cup-2026', 'title' => 'World Cup 2026', 'topic' => 'Sport/Football'],
        ];
        
        foreach ($nonGameDevRoutes as $route) {
            $urls->push([
                'url' => $route['url'],
                'title' => $route['title'],
                'content_type' => 'static_page',
                'category' => 'Non-Core',
                'topic' => $route['topic'],
                'status' => 'active',
                'created_at' => null,
                'updated_at' => null,
                'action' => 'MIGRATE/REMOVE',
                'redirect_url' => '',
                'priority' => 'P0',
                'note' => 'Non-Game Dev content - check GSC before action',
            ]);
        }
        $this->info("  → Added " . count($nonGameDevRoutes) . " non-Game Dev routes");
        
        // Export to CSV
        $this->info('📊 Exporting to CSV...');
        $outputPath = base_path('docs/seo/url_audit_export.csv');
        
        // Ensure directory exists
        File::ensureDirectoryExists(dirname($outputPath));
        
        $csv = fopen($outputPath, 'w');
        
        // Header
        fputcsv($csv, [
            'URL',
            'Title',
            'Content Type',
            'Category',
            'Topic',
            'Status',
            'Created At',
            'Updated At',
            'Organic Clicks 3M',
            'Organic Clicks 12M',
            'Impressions',
            'Avg Position',
            'Backlinks',
            'Action',
            'Redirect URL',
            'Priority',
            'Note',
        ]);
        
        // Data
        foreach ($urls as $url) {
            fputcsv($csv, [
                $url['url'],
                $url['title'],
                $url['content_type'],
                $url['category'],
                $url['topic'],
                $url['status'],
                $url['created_at'],
                $url['updated_at'],
                '', // Organic Clicks 3M - fill from GSC
                '', // Organic Clicks 12M - fill from GSC
                '', // Impressions - fill from GSC
                '', // Avg Position - fill from GSC
                '', // Backlinks - fill from Ahrefs/Semrush
                $url['action'],
                $url['redirect_url'],
                $url['priority'],
                $url['note'],
            ]);
        }
        
        fclose($csv);
        
        $this->info("✅ Export complete!");
        $this->info("📁 File: {$outputPath}");
        $this->info("📈 Total URLs: {$urls->count()}");
        
        // Summary
        $this->newLine();
        $this->info('Summary:');
        $this->table(
            ['Content Type', 'Count'],
            $urls->groupBy('content_type')
                ->map(fn($group, $type) => [$type, $group->count()])
                ->values()
        );
        
        $this->newLine();
        $this->warn('⚠️  Next steps:');
        $this->line('  1. Export GSC data and merge with this CSV');
        $this->line('  2. Check backlinks for top URLs');
        $this->line('  3. Fill Action column based on traffic/backlinks');
        $this->line('  4. Review with team before executing');
        
        return Command::SUCCESS;
    }
    
    /**
     * Detect blog category based on slug/title
     */
    private function detectBlogCategory(string $slug, string $title): string
    {
        $text = strtolower($slug . ' ' . $title);
        
        if (str_contains($text, 'unity')) return 'Unity Development';
        if (str_contains($text, 'unreal')) return 'Unreal Engine';
        if (str_contains($text, 'godot')) return 'Godot';
        if (str_contains($text, 'game-design') || str_contains($text, 'thiet-ke')) return 'Game Design';
        if (str_contains($text, 'mobile') || str_contains($text, 'android') || str_contains($text, 'ios')) return 'Mobile Game';
        if (str_contains($text, '2d')) return '2D Game';
        if (str_contains($text, '3d')) return '3D Game';
        if (str_contains($text, 'ai') || str_contains($text, 'machine-learning')) return 'AI Game Dev';
        if (str_contains($text, 'career') || str_contains($text, 'salary') || str_contains($text, 'viec-lam')) return 'Career';
        
        return 'General';
    }
    
    /**
     * Detect topic relevancy
     */
    private function detectTopic(string $slug, string $title): string
    {
        $text = strtolower($slug . ' ' . $title);
        
        // Non-Game Dev topics
        if (str_contains($text, 'xo-so') || str_contains($text, 'lottery') || str_contains($text, 'lotto')) {
            return 'Lottery';
        }
        if (str_contains($text, 'betting') || str_contains($text, 'nha-cai') || str_contains($text, '1xbet') || str_contains($text, 'fun88')) {
            return 'Betting';
        }
        if (str_contains($text, 'world-cup') || str_contains($text, 'bong-da') || str_contains($text, 'football')) {
            // Check if it's about game development
            if (str_contains($text, 'game') || str_contains($text, 'ea-sports') || str_contains($text, 'fifa')) {
                return 'Football Game';
            }
            return 'Football/Sport';
        }
        if (str_contains($text, 'the-thao') || str_contains($text, 'sport')) {
            return 'Sport';
        }
        
        // Game Dev topics
        if (str_contains($text, 'unity') || str_contains($text, 'unreal') || str_contains($text, 'godot')) {
            return 'Game Engine';
        }
        if (str_contains($text, 'tutorial') || str_contains($text, 'huong-dan')) {
            return 'Tutorial';
        }
        if (str_contains($text, 'game-design')) {
            return 'Game Design';
        }
        
        return 'Game Development';
    }
    
    /**
     * Classify landing page action
     */
    private function classifyLandingPage(string $slug): string
    {
        $nonGameDev = ['lottolive', 'ung-dung-lotto-live', 'world-cup', 'betting'];
        
        foreach ($nonGameDev as $pattern) {
            if (str_contains(strtolower($slug), $pattern)) {
                return 'REMOVE';
            }
        }
        
        return 'KEEP';
    }
}
