<?php

namespace App\Services;

use Webkul\Product\Models\Product;
use Webkul\Product\Models\ProductAttributeValue;
use Webkul\Category\Models\Category;
use Webkul\Attribute\Models\Attribute;
use Webkul\Core\Models\Channel;
use Webkul\Core\Models\Locale;
use Webkul\Product\Helpers\Indexers\Flat;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Pagination\LengthAwarePaginator;

class JobServiceOptimized
{
    protected $defaultChannel;
    protected $defaultLocale;
    protected $jobCategoryId;
    protected $flatIndexer;

    // Job attribute IDs for optimization
    const JOB_ATTRIBUTES = [
        'job_type' => 40,
        'experience_level' => 41,
        'salary_range' => 42,
        'job_location' => 43,
        'required_skills' => 45,
        'job_benefits' => 48,
        'contact_email' => 50,
        'contact_phone' => 51
    ];

    public function __construct(Flat $flatIndexer)
    {
        $this->flatIndexer = $flatIndexer;
        $this->defaultChannel = Channel::first();
        $this->defaultLocale = Locale::where('code', 'vi')->first();
        $this->jobCategoryId = 102; // Việc Làm category ID
    }

    /**
     * Create job posting with optimized single transaction
     */
    public function createJobPosting(array $data): Product
    {
        return DB::transaction(function () use ($data) {
            // 1. Create product with optimized data
            $product = $this->createProductOptimized($data);
            
            // 2. Batch insert attributes
            $this->batchInsertAttributes($product->id, $data);
            
            // 3. Assign category and channel
            $this->assignCategoryAndChannel($product->id, $data['categories'] ?? [$this->jobCategoryId]);
            
            // 4. Create inventory
            $this->createInventoryOptimized($product->id);
            
            // 5. Update flat table
            $this->updateFlatTable($product->id, $data);
            
            // 6. Handle thumbnail if provided
            if (!empty($data['thumbnail'])) {
                $this->saveThumbnail($product->id, $data['thumbnail']);
            }
            
            return $product->fresh();
        });
    }

    /**
     * Update job posting with optimized queries
     */
    public function updateJobPosting(Product $product, array $data): Product
    {
        return DB::transaction(function () use ($product, $data) {
            // 1. Update attributes in batch
            $this->batchUpdateAttributes($product->id, $data);
            
            // 2. Update flat table
            $this->updateFlatTable($product->id, $data);
            
            // 3. Update categories if provided
            if (isset($data['categories'])) {
                $this->updateCategories($product->id, $data['categories']);
            }
            
            // 4. Handle thumbnail update
            if (isset($data['thumbnail'])) {
                $this->updateThumbnail($product->id, $data['thumbnail']);
            }
            
            return $product->fresh();
        });
    }

    /**
     * Get job postings with optimized query
     */
    public function getJobPostings(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->buildOptimizedJobQuery($filters);
        
        return $query->paginate($perPage);
    }

    /**
     * Find job by ID with optimized loading
     */
    public function findJobById(int $id): ?Product
    {
        return Product::with([
            'attribute_values' => function ($query) {
                $query->whereIn('attribute_id', array_values(self::JOB_ATTRIBUTES));
            },
            'categories.translations' => function ($query) {
                $query->where('locale', 'vi');
            },
            'images'
        ])
        ->where('sku', 'LIKE', 'JOB_%')
        ->find($id);
    }

    /**
     * Get job analytics
     */
    public function getJobAnalytics(int $jobId): array
    {
        $cacheKey = "job_analytics_{$jobId}";
        
        return Cache::remember($cacheKey, 300, function () use ($jobId) {
            $views = $this->getJobViews($jobId);
            $applications = $this->getJobApplications($jobId);
            
            return [
                'job_id' => $jobId,
                'views' => $views['total'],
                'applications' => $applications['total'],
                'conversion_rate' => $views['total'] > 0 ? round(($applications['total'] / $views['total']) * 100, 2) : 0,
                'daily_stats' => $this->getDailyStats($jobId),
                'top_referrers' => $this->getTopReferrers($jobId)
            ];
        });
    }

    /**
     * Bulk update jobs
     */
    public function bulkUpdateJobs(array $jobIds, string $action, array $data = []): array
    {
        $results = ['success' => 0, 'failed' => 0, 'errors' => []];
        
        DB::transaction(function () use ($jobIds, $action, $data, &$results) {
            foreach ($jobIds as $jobId) {
                try {
                    switch ($action) {
                        case 'activate':
                            $this->updateJobStatus($jobId, 1);
                            break;
                        case 'deactivate':
                            $this->updateJobStatus($jobId, 0);
                            break;
                        case 'delete':
                            $this->deleteJob($jobId);
                            break;
                        case 'feature':
                            $this->updateJobAttribute($jobId, 'is_featured', 1);
                            break;
                        case 'unfeature':
                            $this->updateJobAttribute($jobId, 'is_featured', 0);
                            break;
                    }
                    $results['success']++;
                } catch (\Exception $e) {
                    $results['failed']++;
                    $results['errors'][] = "Job {$jobId}: " . $e->getMessage();
                }
            }
        });
        
        return $results;
    }

    /**
     * Create product with optimized data
     */
    private function createProductOptimized(array $data): Product
    {
        $sku = $this->generateJobSku($data['company_name'], $data['title']);
        
        return Product::create([
            'sku' => $sku,
            'type' => 'simple',
            'attribute_family_id' => 1,
            'parent_id' => null,
            'additional' => null,
        ]);
    }

    /**
     * Batch insert attributes for better performance
     */
    private function batchInsertAttributes(int $productId, array $data): void
    {
        $attributes = [];
        $timestamp = now();
        
        // Text attributes
        $textAttributes = [
            'name' => $data['title'],
            'short_description' => $data['short_description'],
            'description' => $data['description'],
            'contact_email' => $data['contact_email'] ?? null,
            'contact_phone' => $data['contact_phone'] ?? null,
            'url_key' => $this->generateUrlKey($data['title'], $productId),
            'application_deadline' => $data['application_deadline'] ?? null
        ];
        
        foreach ($textAttributes as $code => $value) {
            if ($value !== null) {
                $attributeId = $this->getAttributeId($code);
                if ($attributeId) {
                    $attributes[] = [
                        'product_id' => $productId,
                        'attribute_id' => $attributeId,
                        'locale' => 'vi',
                        'channel' => 'default',
                        'text_value' => $value,
                        'created_at' => $timestamp,
                        'updated_at' => $timestamp
                    ];
                }
            }
        }
        
        // Integer attributes (select/multiselect)
        $integerAttributes = [
            'job_type' => $data['job_type'] ?? null,
            'experience_level' => $data['experience_level'] ?? null,
            'salary_range' => $data['salary_range'] ?? null,
            'job_location' => $data['job_location'] ?? null,
            'is_urgent' => $data['is_urgent'] ?? false ? 1 : 0,
            'is_featured' => $data['is_featured'] ?? false ? 1 : 0,
            'status' => 1,
            'visible_individually' => 1,
            'new' => 1,
            'featured' => $data['is_featured'] ?? false ? 1 : 0
        ];
        
        foreach ($integerAttributes as $code => $value) {
            if ($value !== null) {
                $attributeId = $this->getAttributeId($code);
                if ($attributeId) {
                    $attributes[] = [
                        'product_id' => $productId,
                        'attribute_id' => $attributeId,
                        'locale' => 'vi',
                        'channel' => 'default',
                        'integer_value' => $value,
                        'created_at' => $timestamp,
                        'updated_at' => $timestamp
                    ];
                }
            }
        }
        
        // Multiselect attributes
        if (!empty($data['required_skills'])) {
            $attributes[] = [
                'product_id' => $productId,
                'attribute_id' => self::JOB_ATTRIBUTES['required_skills'],
                'locale' => 'vi',
                'channel' => 'default',
                'text_value' => implode(',', $data['required_skills']),
                'created_at' => $timestamp,
                'updated_at' => $timestamp
            ];
        }
        
        if (!empty($data['job_benefits'])) {
            $attributes[] = [
                'product_id' => $productId,
                'attribute_id' => self::JOB_ATTRIBUTES['job_benefits'],
                'locale' => 'vi',
                'channel' => 'default',
                'text_value' => implode(',', $data['job_benefits']),
                'created_at' => $timestamp,
                'updated_at' => $timestamp
            ];
        }
        
        // Batch insert
        if (!empty($attributes)) {
            ProductAttributeValue::insert($attributes);
        }
    }

    /**
     * Build optimized job query with proper joins
     */
    private function buildOptimizedJobQuery(array $filters)
    {
        $query = DB::table('products as p')
            ->join('product_flat as pf', function ($join) {
                $join->on('p.id', '=', 'pf.product_id')
                     ->where('pf.locale', '=', 'vi');
            })
            ->leftJoin('product_categories as pc', 'p.id', '=', 'pc.product_id')
            ->leftJoin('product_images as pi', function ($join) {
                $join->on('p.id', '=', 'pi.product_id')
                     ->where('pi.type', '=', 'images')
                     ->whereRaw('pi.id = (SELECT MIN(id) FROM product_images WHERE product_id = p.id AND type = "images")');
            })
            ->where('p.sku', 'LIKE', 'JOB_%')
            ->where('pf.status', 1)
            ->where('pf.visible_individually', 1)
            ->select([
                'p.id',
                'p.sku',
                'p.created_at',
                'pf.name',
                'pf.short_description',
                'pf.price',
                'pf.url_key',
                'pi.path as thumbnail'
            ]);

        // Apply filters
        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('pf.name', 'LIKE', '%' . $filters['search'] . '%')
                  ->orWhere('pf.short_description', 'LIKE', '%' . $filters['search'] . '%');
            });
        }

        if (!empty($filters['job_type'])) {
            $query->whereExists(function ($q) use ($filters) {
                $q->select(DB::raw(1))
                  ->from('product_attribute_values as pav')
                  ->whereRaw('pav.product_id = p.id')
                  ->where('pav.attribute_id', self::JOB_ATTRIBUTES['job_type'])
                  ->where('pav.integer_value', $filters['job_type']);
            });
        }

        if (!empty($filters['location'])) {
            $query->whereExists(function ($q) use ($filters) {
                $q->select(DB::raw(1))
                  ->from('product_attribute_values as pav')
                  ->whereRaw('pav.product_id = p.id')
                  ->where('pav.attribute_id', self::JOB_ATTRIBUTES['job_location'])
                  ->where('pav.integer_value', $filters['location']);
            });
        }

        if (!empty($filters['experience_level'])) {
            $query->whereExists(function ($q) use ($filters) {
                $q->select(DB::raw(1))
                  ->from('product_attribute_values as pav')
                  ->whereRaw('pav.product_id = p.id')
                  ->where('pav.attribute_id', self::JOB_ATTRIBUTES['experience_level'])
                  ->where('pav.integer_value', $filters['experience_level']);
            });
        }

        if (!empty($filters['skills']) && is_array($filters['skills'])) {
            $query->whereExists(function ($q) use ($filters) {
                $q->select(DB::raw(1))
                  ->from('product_attribute_values as pav')
                  ->whereRaw('pav.product_id = p.id')
                  ->where('pav.attribute_id', self::JOB_ATTRIBUTES['required_skills']);
                
                foreach ($filters['skills'] as $skill) {
                    $q->where('pav.text_value', 'LIKE', '%' . $skill . '%');
                }
            });
        }

        if ($filters['is_urgent'] ?? false) {
            $query->whereExists(function ($q) {
                $q->select(DB::raw(1))
                  ->from('product_attribute_values as pav')
                  ->whereRaw('pav.product_id = p.id')
                  ->where('pav.attribute_id', $this->getAttributeId('is_urgent'))
                  ->where('pav.integer_value', 1);
            });
        }

        if ($filters['is_featured'] ?? false) {
            $query->whereExists(function ($q) {
                $q->select(DB::raw(1))
                  ->from('product_attribute_values as pav')
                  ->whereRaw('pav.product_id = p.id')
                  ->where('pav.attribute_id', $this->getAttributeId('is_featured'))
                  ->where('pav.integer_value', 1);
            });
        }

        // Ordering
        $orderBy = $filters['order_by'] ?? 'created_at';
        $orderDirection = $filters['order_direction'] ?? 'desc';
        
        if ($orderBy === 'salary') {
            $query->orderByRaw('(SELECT pav.integer_value FROM product_attribute_values pav WHERE pav.product_id = p.id AND pav.attribute_id = ' . self::JOB_ATTRIBUTES['salary_range'] . ') ' . $orderDirection);
        } else {
            $query->orderBy('p.' . $orderBy, $orderDirection);
        }

        return $query;
    }

    /**
     * Helper methods
     */
    private function generateJobSku(string $company, string $title): string
    {
        $company = Str::slug($company, '_');
        $title = Str::slug($title, '_');
        $year = date('Y');
        
        return 'JOB_' . strtoupper($company) . '_' . strtoupper($title) . '_' . $year;
    }

    private function generateUrlKey(string $title, int $productId): string
    {
        return Str::slug($title) . '-' . $productId;
    }

    private function getAttributeId(string $code): ?int
    {
        return Cache::remember("attribute_id_{$code}", 3600, function () use ($code) {
            return Attribute::where('code', $code)->value('id');
        });
    }

    private function updateFlatTable(int $productId, array $data): void
    {
        $flatData = [
            'name' => $data['title'] ?? null,
            'short_description' => $data['short_description'] ?? null,
            'description' => $data['description'] ?? null,
            'url_key' => $this->generateUrlKey($data['title'] ?? 'job', $productId),
            'status' => 1,
            'visible_individually' => 1,
            'updated_at' => now()
        ];

        DB::table('product_flat')
            ->updateOrInsert(
                ['product_id' => $productId, 'locale' => 'vi', 'channel' => 'default'],
                array_filter($flatData)
            );
    }

    private function assignCategoryAndChannel(int $productId, array $categoryIds): void
    {
        // Assign categories
        $categoryData = [];
        foreach ($categoryIds as $categoryId) {
            $categoryData[] = [
                'product_id' => $productId,
                'category_id' => $categoryId
            ];
        }
        DB::table('product_categories')->insert($categoryData);

        // Assign default channel
        DB::table('product_channels')->insert([
            'product_id' => $productId,
            'channel_id' => $this->defaultChannel->id
        ]);
    }

    private function createInventoryOptimized(int $productId): void
    {
        DB::table('product_inventories')->insert([
            'qty' => 1,
            'product_id' => $productId,
            'inventory_source_id' => 1,
            'vendor_id' => 0
        ]);
    }

    private function getJobViews(int $jobId): array
    {
        // Placeholder - implement with actual analytics
        return ['total' => rand(100, 1000)];
    }

    private function getJobApplications(int $jobId): array
    {
        $total = DB::table('job_applications')->where('job_id', $jobId)->count();
        return ['total' => $total];
    }

    private function getDailyStats(int $jobId): array
    {
        // Placeholder - implement with actual analytics
        return [];
    }

    private function getTopReferrers(int $jobId): array
    {
        // Placeholder - implement with actual analytics
        return [];
    }

    private function updateJobStatus(int $jobId, int $status): void
    {
        DB::table('product_flat')
            ->where('product_id', $jobId)
            ->update(['status' => $status]);
    }

    private function updateJobAttribute(int $jobId, string $attribute, $value): void
    {
        $attributeId = $this->getAttributeId($attribute);
        if ($attributeId) {
            DB::table('product_attribute_values')
                ->where('product_id', $jobId)
                ->where('attribute_id', $attributeId)
                ->update(['integer_value' => $value]);
        }
    }

    private function deleteJob(int $jobId): void
    {
        DB::transaction(function () use ($jobId) {
            DB::table('product_attribute_values')->where('product_id', $jobId)->delete();
            DB::table('product_categories')->where('product_id', $jobId)->delete();
            DB::table('product_channels')->where('product_id', $jobId)->delete();
            DB::table('product_flat')->where('product_id', $jobId)->delete();
            DB::table('product_inventories')->where('product_id', $jobId)->delete();
            DB::table('products')->where('id', $jobId)->delete();
        });
    }

    private function saveThumbnail(int $productId, string $base64Image): void
    {
        // Implementation for saving base64 image as thumbnail
        // This would involve decoding base64, saving to storage, and creating product_images record
    }

    private function updateThumbnail(int $productId, string $base64Image): void
    {
        // Implementation for updating thumbnail
    }

    private function batchUpdateAttributes(int $productId, array $data): void
    {
        // Implementation for batch updating attributes
        // Similar to batchInsertAttributes but with UPDATE queries
    }

    private function updateCategories(int $productId, array $categoryIds): void
    {
        DB::table('product_categories')->where('product_id', $productId)->delete();
        $this->assignCategoryAndChannel($productId, $categoryIds);
    }
}
