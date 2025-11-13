<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Webkul\Product\Models\Product;
use Webkul\Category\Models\Category;
use Illuminate\Support\Facades\DB;

class FixJobCategories extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'jobs:fix-categories 
                            {--dry-run : Show what would be changed without making changes}
                            {--force : Force the operation without confirmation}';

    /**
     * The console command description.
     */
    protected $description = 'Ensure all job products belong to the "Việc Làm" category';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Checking job categories...');

        // Tìm job category
        $jobCategory = Category::whereHas('translations', function ($query) {
            $query->where('slug', 'viec-lam');
        })->first();

        if (!$jobCategory) {
            $this->error('❌ Job category "Việc Làm" not found!');
            $this->info('💡 Run: php artisan migrate to create the job category');
            return 1;
        }

        $this->info("✅ Job category found: {$jobCategory->name} (ID: {$jobCategory->id})");

        // Tìm tất cả products có thể là jobs (dựa trên attributes hoặc naming pattern)
        $potentialJobs = Product::where(function ($query) {
            // Jobs thường có các attributes như job_title, company_name, etc.
            $query->whereHas('attribute_values', function ($subQuery) {
                $subQuery->whereHas('attribute', function ($attrQuery) {
                    $attrQuery->whereIn('code', [
                        'job_title', 'company_name', 'job_type', 
                        'job_location', 'salary_range', 'job_description'
                    ]);
                });
            })
            // Hoặc SKU có pattern của job
            ->orWhere('sku', 'like', 'JOB-%')
            ->orWhere('sku', 'like', '%-JOB-%');
        })->with('categories')->get();

        if ($potentialJobs->isEmpty()) {
            $this->info('✅ No potential job products found');
            return 0;
        }

        $this->info("📊 Found {$potentialJobs->count()} potential job products");

        $jobsNeedingFix = [];
        $jobsAlreadyCorrect = 0;

        foreach ($potentialJobs as $product) {
            $hasJobCategory = $product->categories->contains('id', $jobCategory->id);
            
            if (!$hasJobCategory) {
                $jobsNeedingFix[] = $product;
            } else {
                $jobsAlreadyCorrect++;
            }
        }

        $this->info("✅ Jobs already in correct category: {$jobsAlreadyCorrect}");
        $this->info("🔧 Jobs needing fix: " . count($jobsNeedingFix));

        if (empty($jobsNeedingFix)) {
            $this->info('🎉 All job products are already in the correct category!');
            return 0;
        }

        // Show details of jobs needing fix
        $this->table(
            ['ID', 'SKU', 'Current Categories'],
            collect($jobsNeedingFix)->map(function ($product) {
                return [
                    $product->id,
                    $product->sku,
                    $product->categories->pluck('name')->join(', ') ?: 'None'
                ];
            })->toArray()
        );

        if ($this->option('dry-run')) {
            $this->warn('🔍 DRY RUN: No changes were made');
            return 0;
        }

        if (!$this->option('force') && !$this->confirm('Do you want to add these products to the "Việc Làm" category?')) {
            $this->info('Operation cancelled');
            return 0;
        }

        // Fix the categories
        $fixed = 0;
        foreach ($jobsNeedingFix as $product) {
            try {
                // Add job category to existing categories
                DB::table('product_categories')->insert([
                    'product_id' => $product->id,
                    'category_id' => $jobCategory->id,
                ]);
                
                $fixed++;
                $this->info("✅ Fixed product ID {$product->id} ({$product->sku})");
            } catch (\Exception $e) {
                $this->error("❌ Failed to fix product ID {$product->id}: {$e->getMessage()}");
            }
        }

        $this->info("🎉 Successfully fixed {$fixed} job products");
        
        if ($fixed > 0) {
            $this->info('💡 Consider running: php artisan indexer:index to update search indexes');
        }

        return 0;
    }
}
