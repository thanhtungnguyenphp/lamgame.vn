<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AuditInternalLinks extends Command
{
    protected $signature = 'seo:audit-links {--fix : Auto-remove broken links from navigation}';
    protected $description = 'Audit internal links for broken URLs';

    // Known legacy/removed URLs that should not be linked
    protected $brokenPatterns = [
        '/xo-so',
        '/the-thao', 
        '/world-cup-2026',
        '/lottolive',
        'ca-cuoc-world-cup',
        'bellingham-zidane',
        'messi-vs-mbappe',
        'erling-haaland',
        'vinicius-jr',
        'mbappe',
        'haaland',
        'football',
        'betting',
        '1xbet',
    ];

    public function handle()
    {
        $this->info('=== INTERNAL LINK AUDIT ===');
        $this->newLine();

        // Check navigation files
        $navFiles = [
            'resources/views/partials/footer-redesign.blade.php',
            'resources/views/partials/nav-redesign.blade.php',
            'resources/views/components/v2/header.blade.php',
            'resources/views/home/index.blade.php',
            'resources/views/home-v2/index.blade.php',
        ];

        $issues = [];

        foreach ($navFiles as $file) {
            $path = base_path($file);
            if (!file_exists($path)) continue;

            $content = file_get_contents($path);
            
            foreach ($this->brokenPatterns as $pattern) {
                if (stripos($content, $pattern) !== false) {
                    $issues[] = [
                        'file' => $file,
                        'pattern' => $pattern,
                    ];
                }
            }
        }

        if (empty($issues)) {
            $this->info('✅ No broken link patterns found in navigation files.');
        } else {
            $this->warn('⚠️ Found potential broken links:');
            foreach ($issues as $issue) {
                $this->line("  - {$issue['file']}: contains '{$issue['pattern']}'");
            }
        }

        $this->newLine();

        // Check blog content for internal links to removed content
        $this->info('Checking blog posts for broken internal links...');
        
        $blogs = DB::table('blogs')
            ->where('status', 1)
            ->select('id', 'name', 'description')
            ->get();

        $blogIssues = [];
        foreach ($blogs as $blog) {
            foreach ($this->brokenPatterns as $pattern) {
                if (stripos($blog->description ?? '', $pattern) !== false) {
                    $blogIssues[] = [
                        'id' => $blog->id,
                        'name' => $blog->name,
                        'pattern' => $pattern,
                    ];
                }
            }
        }

        if (empty($blogIssues)) {
            $this->info('✅ No broken link patterns found in blog content.');
        } else {
            $this->warn('⚠️ Found potential broken links in blogs:');
            foreach ($blogIssues as $issue) {
                $this->line("  - [{$issue['id']}] {$issue['name']}: contains '{$issue['pattern']}'");
            }
        }

        $this->newLine();
        $this->info('Audit complete. Total issues: ' . (count($issues) + count($blogIssues)));

        return Command::SUCCESS;
    }
}
