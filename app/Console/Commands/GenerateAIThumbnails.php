<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\AI\ThumbnailGenerationService;
use App\Models\Blog;
use Webkul\Product\Models\Product;

class GenerateAIThumbnails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ai:generate-thumbnails 
                            {type : Type of items to process (blog|product|all)}
                            {--ids=* : Specific IDs to process}
                            {--dry-run : Show what would be processed without actually generating}
                            {--force : Regenerate thumbnails even if they already exist}
                            {--limit= : Limit number of items to process}
                            {--quality=medium : Quality level (low|medium|high)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate AI thumbnails for blog posts and source game products';

    /**
     * Thumbnail generation service
     *
     * @var ThumbnailGenerationService
     */
    protected $thumbnailService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->thumbnailService = new ThumbnailGenerationService();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $type = $this->argument('type');
        $ids = $this->option('ids');
        $dryRun = $this->option('dry-run');
        $force = $this->option('force');
        $limit = $this->option('limit');
        $quality = $this->option('quality');

        $this->info("🤖 AI Thumbnail Generator");
        $this->info("==========================");

        if ($dryRun) {
            $this->warn("🔍 DRY RUN MODE - No thumbnails will be generated");
        }

        $options = [
            'quality_level' => $quality,
            'context' => $type === 'blog' ? 'blog' : 'product'
        ];

        if ($type === 'all') {
            $this->info("📝 Processing blogs...");
            $this->processType('blog', $ids, $dryRun, $force, $limit, $options);
            
            $this->info("\n🎮 Processing products...");
            $this->processType('product', $ids, $dryRun, $force, $limit, $options);
        } else {
            $this->processType($type, $ids, $dryRun, $force, $limit, $options);
        }

        return 0;
    }

    /**
     * Process specific type of items
     *
     * @param string $type
     * @param array $ids
     * @param bool $dryRun
     * @param bool $force
     * @param int|null $limit
     * @param array $options
     */
    protected function processType($type, $ids, $dryRun, $force, $limit, $options)
    {
        if ($type === 'blog') {
            $this->processBlogThumbnails($ids, $dryRun, $force, $limit, $options);
        } elseif ($type === 'product') {
            $this->processProductThumbnails($ids, $dryRun, $force, $limit, $options);
        } else {
            $this->error("❌ Invalid type: {$type}. Use 'blog', 'product', or 'all'");
        }
    }

    /**
     * Process blog thumbnails
     *
     * @param array $ids
     * @param bool $dryRun
     * @param bool $force
     * @param int|null $limit
     * @param array $options
     */
    protected function processBlogThumbnails($ids, $dryRun, $force, $limit, $options)
    {
        $query = Blog::query();

        if (!empty($ids)) {
            $query->whereIn('id', $ids);
            $this->info("📋 Processing specific blog IDs: " . implode(', ', $ids));
        } else {
            if ($force) {
                $this->info("🔄 Processing ALL blogs (--force mode)");
            } else {
                $query->where(function($q) {
                    $q->whereNull('src')
                      ->orWhere('src', '')
                      ->orWhere('src', 'like', '%placeholder%');
                });
                $this->info("📋 Processing blogs without thumbnails");
            }
        }

        if ($limit) {
            $query->limit($limit);
            $this->info("📊 Limited to {$limit} items");
        }

        $blogs = $query->get();
        $total = $blogs->count();

        if ($total === 0) {
            $this->warn("⚠️  No blogs found to process");
            return;
        }

        $this->info("📊 Found {$total} blogs to process");

        if ($dryRun) {
            $this->table(['ID', 'Name', 'Current Thumbnail'], 
                $blogs->map(function($blog) {
                    return [
                        $blog->id,
                        \Str::limit($blog->name, 50),
                        $blog->src ?: 'None'
                    ];
                })->toArray()
            );
            return;
        }

        $progressBar = $this->output->createProgressBar($total);
        $progressBar->start();

        $successCount = 0;
        $failedCount = 0;

        foreach ($blogs as $blog) {
            $result = $this->thumbnailService->generateBlogThumbnail($blog, $options);
            
            if ($result['success']) {
                $successCount++;
                $this->newLine();
                $this->info("✅ Generated thumbnail for: {$blog->name}");
            } else {
                $failedCount++;
                $this->newLine();
                $this->error("❌ Failed to generate thumbnail for: {$blog->name} - {$result['error']}");
            }
            
            $progressBar->advance();
            
            // Small delay to avoid hitting rate limits
            usleep(500000); // 0.5 seconds
        }

        $progressBar->finish();
        $this->newLine(2);

        $this->info("📊 Blog Thumbnails Summary:");
        $this->info("✅ Success: {$successCount}");
        $this->info("❌ Failed: {$failedCount}");
        $this->info("📈 Total: {$total}");
    }

    /**
     * Process product thumbnails
     *
     * @param array $ids
     * @param bool $dryRun
     * @param bool $force
     * @param int|null $limit
     * @param array $options
     */
    protected function processProductThumbnails($ids, $dryRun, $force, $limit, $options)
    {
        $query = Product::where('type', 'downloadable');

        if (!empty($ids)) {
            $query->whereIn('id', $ids);
            $this->info("🎮 Processing specific product IDs: " . implode(', ', $ids));
        } else {
            if ($force) {
                $this->info("🔄 Processing ALL downloadable products (--force mode)");
            } else {
                $query->whereDoesntHave('images');
                $this->info("🎮 Processing products without images");
            }
        }

        if ($limit) {
            $query->limit($limit);
            $this->info("📊 Limited to {$limit} items");
        }

        $products = $query->with('product_flats')->get();
        $total = $products->count();

        if ($total === 0) {
            $this->warn("⚠️  No products found to process");
            return;
        }

        $this->info("📊 Found {$total} products to process");

        if ($dryRun) {
            $this->table(['ID', 'SKU', 'Name', 'Images Count'], 
                $products->map(function($product) {
                    $flat = $product->product_flats->first();
                    return [
                        $product->id,
                        $product->sku,
                        $flat ? \Str::limit($flat->name, 40) : 'No name',
                        $product->images->count()
                    ];
                })->toArray()
            );
            return;
        }

        $progressBar = $this->output->createProgressBar($total);
        $progressBar->start();

        $successCount = 0;
        $failedCount = 0;

        foreach ($products as $product) {
            $flat = $product->product_flats->first();
            $productName = $flat ? $flat->name : $product->sku;
            
            $result = $this->thumbnailService->generateProductThumbnail($product, $options);
            
            if ($result['success']) {
                $successCount++;
                $this->newLine();
                $this->info("✅ Generated thumbnail for: {$productName}");
            } else {
                $failedCount++;
                $this->newLine();
                $this->error("❌ Failed to generate thumbnail for: {$productName} - {$result['error']}");
            }
            
            $progressBar->advance();
            
            // Small delay to avoid hitting rate limits
            usleep(500000); // 0.5 seconds
        }

        $progressBar->finish();
        $this->newLine(2);

        $this->info("📊 Product Thumbnails Summary:");
        $this->info("✅ Success: {$successCount}");
        $this->info("❌ Failed: {$failedCount}");
        $this->info("📈 Total: {$total}");
    }
}
