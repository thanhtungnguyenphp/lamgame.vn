<?php

namespace LamGame\Banner\Console\Commands;

use Illuminate\Console\Command;
use LamGame\Banner\Repositories\BannerRepository;

class ClearBannerCacheCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'banner:clear-cache 
                            {--all : Clear all application cache instead of just banner cache}';

    /**
     * The console command description.
     */
    protected $description = 'Clear banner cache to ensure API returns fresh data';

    /**
     * Execute the console command.
     */
    public function handle(BannerRepository $bannerRepository): int
    {
        $this->info('🔄 Clearing banner cache...');

        try {
            if ($this->option('all')) {
                $this->info('🗑️  Clearing all application cache...');
                \Illuminate\Support\Facades\Cache::flush();
                $this->info('✅ All cache cleared successfully!');
            } else {
                $bannerRepository->clearAllCache();
                $this->info('✅ Banner cache cleared successfully!');
            }

            // Show current banner count
            $count = \LamGame\Banner\Models\Banner::count();
            $this->info("📊 Current banners in database: {$count}");

            return self::SUCCESS;

        } catch (\Exception $e) {
            $this->error('❌ Failed to clear banner cache: ' . $e->getMessage());
            return self::FAILURE;
        }
    }
}