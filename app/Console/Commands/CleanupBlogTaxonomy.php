<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CleanupBlogTaxonomy extends Command
{
    protected $signature = 'seo:cleanup-taxonomy {--dry-run : Preview changes without executing}';
    protected $description = 'Clean up blog categories and tags that are not related to Game Development';

    // Categories to REMOVE (betting, football generic, World Cup non-gaming)
    protected $categoriesToRemove = [
        18, // Giải Bóng đá Thế giới 2026
        20, // cá cược World Cup 2026
        21, // cầu thủ nổi bật World Cup 2026
        22, // tin tức World Cup 2026
        23, // mua vé World Cup 2026
        24, // đội hình mạnh nhất World Cup 2026
        25, // công nghệ VAR World Cup 2026
        26, // đồng phục World Cup 2026
        27, // phụ kiện World Cup 2026
        38, // siêu sao bóng đá
        39, // Messi vs Mbappe
        40, // Erling Haaland
        41, // kỹ năng bóng đá
        42, // Jude Bellingham
        43, // Quả bóng vàng 2024
        44, // kỹ thuật Mbappe
        45, // Haaland vs Lewandowski
        46, // khoảnh khắc Messi
        47, // Vinicius Jr
        48, // kỷ lục Mbappe
        49, // Haaland Premier League
        50, // Bellingham Zidane
        51, // trận đấu hay nhất Messi
        52, // Mbappe rời PSG
        53, // huyền thoại đánh giá cầu thủ
        54, // kỹ thuật Messi
        55, // Vinicius Jr vs Rodrygo
        56, // dinh dưỡng Haaland
        57, // thống kê Messi
    ];

    // Categories to KEEP but review content
    // 19 - FIFA Mobile World Cup 2026 (gaming related, keep)
    // 17 - eSports (gaming related, keep)
    // 12 - FPS Games (keep)
    // 16 - RPG Games (keep)
    // 11 - Game Reviews (keep)

    // Categories that are duplicates or thin - merge/deactivate
    protected $categoriesToDeactivate = [
        60, // unity-development (duplicate of 1)
        61, // 2d-game (duplicate of 6)
        62, // game-industry (duplicate of 10)
    ];

    public function handle()
    {
        $dryRun = $this->option('dry-run');
        
        $this->info($dryRun ? '=== DRY RUN MODE ===' : '=== EXECUTING CLEANUP ===');
        $this->newLine();

        // 1. Categories to REMOVE
        $this->info('Categories to REMOVE (football/betting generic):');
        $categories = DB::table('blog_categories')
            ->whereIn('id', $this->categoriesToRemove)
            ->get();
        
        foreach ($categories as $cat) {
            $postCount = DB::table('blogs')->where('default_category', $cat->id)->count();
            $this->line("  [{$cat->id}] {$cat->name} ({$postCount} posts)");
        }

        if (!$dryRun) {
            // Move posts to "Game Industry" (id=10) before deleting
            DB::table('blogs')
                ->whereIn('default_category', $this->categoriesToRemove)
                ->update(['default_category' => 10]);
            
            // Deactivate categories
            DB::table('blog_categories')
                ->whereIn('id', $this->categoriesToRemove)
                ->update(['status' => 0]);
            
            $this->info('  ✓ Categories deactivated, posts moved to Game Industry');
        }

        $this->newLine();

        // 2. Duplicate categories to deactivate
        $this->info('Duplicate categories to DEACTIVATE:');
        $duplicates = DB::table('blog_categories')
            ->whereIn('id', $this->categoriesToDeactivate)
            ->get();
        
        foreach ($duplicates as $cat) {
            $postCount = DB::table('blogs')->where('default_category', $cat->id)->count();
            $this->line("  [{$cat->id}] {$cat->name} ({$postCount} posts)");
        }

        if (!$dryRun) {
            // Merge duplicates
            // unity-development (60) -> Unity Development (1)
            DB::table('blogs')->where('default_category', 60)->update(['default_category' => 1]);
            // 2d-game (61) -> 2D Game (6)
            DB::table('blogs')->where('default_category', 61)->update(['default_category' => 6]);
            // game-industry (62) -> Game Industry (10)
            DB::table('blogs')->where('default_category', 62)->update(['default_category' => 10]);
            
            DB::table('blog_categories')
                ->whereIn('id', $this->categoriesToDeactivate)
                ->update(['status' => 0]);
            
            $this->info('  ✓ Duplicate categories merged and deactivated');
        }

        $this->newLine();

        // 3. Summary of remaining active categories
        $this->info('REMAINING ACTIVE CATEGORIES:');
        $active = DB::table('blog_categories')
            ->where('status', 1)
            ->whereNotIn('id', array_merge($this->categoriesToRemove, $this->categoriesToDeactivate))
            ->get();
        
        foreach ($active as $cat) {
            $postCount = DB::table('blogs')->where('default_category', $cat->id)->count();
            $this->line("  [{$cat->id}] {$cat->name} ({$postCount} posts)");
        }

        $this->newLine();
        
        if ($dryRun) {
            $this->warn('Run without --dry-run to execute changes');
        } else {
            $this->info('✅ Taxonomy cleanup completed!');
        }

        return Command::SUCCESS;
    }
}
