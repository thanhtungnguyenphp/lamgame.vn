<?php

namespace App\Services;

use Webkul\Product\Models\Product;
use Webkul\Product\Models\ProductAttributeValue;
use Webkul\Category\Models\Category;
use Webkul\Attribute\Models\Attribute;
use Webkul\Attribute\Models\AttributeOption;
use Webkul\Core\Models\Channel;
use Webkul\Core\Models\Locale;
use Webkul\Product\Helpers\Indexers\Flat;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class JobService
{
    protected $defaultChannel;
    protected $defaultLocale;
    protected $jobCategoryId;
    protected $flatIndexer;

    public function __construct(Flat $flatIndexer)
    {
        $this->flatIndexer = $flatIndexer;
        $this->defaultChannel = Channel::first();
        $this->defaultLocale = Locale::where('code', 'vi')->first();
        
        // Tìm job category (Việc Làm)
        $jobCategory = Category::whereHas('translations', function ($query) {
            $query->where('slug', 'viec-lam');
        })->first();
        
        $this->jobCategoryId = $jobCategory ? $jobCategory->id : 102; // Fallback to ID 102
    }

    /**
     * Tạo job posting mới
     */
    public function createJobPosting(array $data): Product
    {
        return DB::transaction(function () use ($data) {
            // 1. Tạo product
            $product = $this->createProduct($data);
            
            // 2. Lưu attributes
            $this->saveJobAttributes($product->id, $data);
            
            // 3. Gán categories
            $this->assignCategories($product->id, $data['categories'] ?? [$this->jobCategoryId]);
            
            // 4. Gán default channel
            $this->assignDefaultChannel($product->id);
            
            // 5. Tạo inventory record
            $this->createInventory($product->id);
            
            // 6. Đồng bộ vào product_flat
            $productWithRelations = $product->fresh()->load('attribute_values', 'categories', 'attribute_family', 'channels');
            $this->flatIndexer->refresh($productWithRelations);
            
            return $productWithRelations;
        });
    }

    /**
     * Cập nhật job posting
     */
    public function updateJobPosting(Product $product, array $data): Product
    {
        return DB::transaction(function () use ($product, $data) {
            // 1. Cập nhật basic product info nếu có
            $this->updateProduct($product, $data);
            
            // 2. Cập nhật attributes
            $this->updateJobAttributes($product->id, $data);
            
            // 3. Cập nhật categories nếu có
            if (isset($data['categories'])) {
                $this->updateCategories($product->id, $data['categories']);
            }
            
            // 4. Đồng bộ vào product_flat
            $productWithRelations = $product->fresh()->load('attribute_values', 'categories', 'attribute_family', 'channels');
            $this->flatIndexer->refresh($productWithRelations);
            
            return $productWithRelations;
        });
    }

    /**
     * Lấy danh sách job postings với filter
     */
    public function getJobPostings(array $filters = [], int $perPage = 15)
    {
        $query = Product::query()
            ->whereHas('categories', function ($q) {
                $q->where('category_id', $this->jobCategoryId);
            })
            ->with(['attribute_values', 'categories.translations']);

        // Apply filters
        $this->applyFilters($query, $filters);

        return $query->paginate($perPage);
    }

    /**
     * Tạo product cơ bản
     */
    protected function createProduct(array $data): Product
    {
        $sku = $this->generateJobSku($data['company_name'], $data['title']);
        
        return Product::create([
            'sku' => $sku,
            'type' => 'simple',
            'attribute_family_id' => 1, // Default attribute family
            'parent_id' => null,
            'additional' => null,
        ]);
    }

    /**
     * Cập nhật product cơ bản
     */
    protected function updateProduct(Product $product, array $data): void
    {
        $updateData = [];
        
        // Chỉ cập nhật những field cần thiết
        if (isset($data['title']) || isset($data['company_name'])) {
            $title = $data['title'] ?? $this->getAttributeValue($product->id, 'name');
            $company = $data['company_name'] ?? $this->extractCompanyFromTitle($title);
            $updateData['sku'] = $this->generateJobSku($company, $title);
        }
        
        if (!empty($updateData)) {
            $product->update($updateData);
        }
    }

    /**
     * Lưu job attributes
     */
    protected function saveJobAttributes(int $productId, array $data): void
    {
        $attributeMap = $this->getJobAttributeMap();
        
        foreach ($data as $key => $value) {
            if (isset($attributeMap[$key])) {
                $this->saveAttributeValue($productId, $attributeMap[$key], $value);
            }
        }
        
        // Lưu các attribute mặc định
        $this->saveDefaultAttributes($productId, $data);
    }

    /**
     * Cập nhật job attributes
     */
    protected function updateJobAttributes(int $productId, array $data): void
    {
        $attributeMap = $this->getJobAttributeMap();
        
        foreach ($data as $key => $value) {
            if (isset($attributeMap[$key])) {
                $this->updateAttributeValue($productId, $attributeMap[$key], $value);
            }
        }
    }

    /**
     * Lưu giá trị attribute
     */
    protected function saveAttributeValue(int $productId, string $attributeCode, $value): void
    {
        $attribute = Attribute::where('code', $attributeCode)->first();
        if (!$attribute) return;

        $attributeValue = [
            'product_id' => $productId,
            'attribute_id' => $attribute->id,
            'locale' => $this->defaultLocale->code,
            'channel' => $this->defaultChannel->code,
        ];

        // Xử lý theo type của attribute
        switch ($attribute->type) {
            case 'select':
                $optionId = $this->getOrCreateAttributeOption($attribute->id, $value);
                $attributeValue['integer_value'] = $optionId;
                break;
                
            case 'multiselect':
                if (is_array($value)) {
                    $optionIds = [];
                    foreach ($value as $val) {
                        $optionIds[] = $this->getOrCreateAttributeOption($attribute->id, $val);
                    }
                    $attributeValue['text_value'] = implode(',', $optionIds);
                } else {
                    $attributeValue['text_value'] = $value;
                }
                break;
                
            case 'boolean':
                $attributeValue['boolean_value'] = (bool) $value;
                $attributeValue['integer_value'] = $value ? 1 : 0;
                break;
                
            case 'date':
                $attributeValue['date_value'] = $value ? Carbon::parse($value)->format('Y-m-d') : null;
                break;
                
            case 'price':
                $attributeValue['float_value'] = (float) $value;
                break;
                
            default:
                $attributeValue['text_value'] = $value;
                break;
        }

        ProductAttributeValue::create($attributeValue);
    }

    /**
     * Cập nhật giá trị attribute
     */
    protected function updateAttributeValue(int $productId, string $attributeCode, $value): void
    {
        $attribute = Attribute::where('code', $attributeCode)->first();
        if (!$attribute) return;

        // Xóa giá trị cũ
        ProductAttributeValue::where('product_id', $productId)
            ->where('attribute_id', $attribute->id)
            ->delete();

        // Tạo giá trị mới
        $this->saveAttributeValue($productId, $attributeCode, $value);
    }

    /**
     * Lấy hoặc tạo attribute option
     */
    protected function getOrCreateAttributeOption(int $attributeId, string $value): int
    {
        $option = AttributeOption::whereHas('translations', function ($query) use ($value) {
            $query->where('label', $value);
        })->where('attribute_id', $attributeId)->first();

        if (!$option) {
            $option = AttributeOption::create([
                'attribute_id' => $attributeId,
                'admin_name' => $value,
                'sort_order' => 1,
            ]);

            // Tạo translation
            $option->translations()->create([
                'locale' => $this->defaultLocale->code,
                'label' => $value,
            ]);
        }

        return $option->id;
    }

    /**
     * Gán categories cho product
     */
    protected function assignCategories(int $productId, array $categoryIds): void
    {
        $data = [];
        foreach ($categoryIds as $categoryId) {
            $data[] = [
                'product_id' => $productId,
                'category_id' => $categoryId,
            ];
        }
        
        DB::table('product_categories')->insert($data);
    }

    /**
     * Cập nhật categories
     */
    protected function updateCategories(int $productId, array $categoryIds): void
    {
        DB::table('product_categories')->where('product_id', $productId)->delete();
        $this->assignCategories($productId, $categoryIds);
    }

    /**
     * Tạo inventory record
     */
    protected function createInventory(int $productId): void
    {
        DB::table('product_inventories')->insert([
            'qty' => 1,
            'product_id' => $productId,
            'inventory_source_id' => 1, // Default inventory source
        ]);
    }

    /**
     * Generate unique SKU cho job
     */
    protected function generateJobSku(string $company, string $title): string
    {
        $base = 'JOB_' . Str::upper(Str::slug($company, '_')) . '_' . Str::upper(Str::slug($title, '_'));
        $base = Str::limit($base, 30, '');
        
        $counter = 1;
        $sku = $base . '_' . date('Y');
        
        while (Product::where('sku', $sku)->exists()) {
            $sku = $base . '_' . date('Y') . '_' . $counter;
            $counter++;
        }
        
        return $sku;
    }

    /**
     * Lấy mapping giữa input fields và attribute codes
     */
    protected function getJobAttributeMap(): array
    {
        return [
            'title' => 'name',
            'description' => 'description',
            'short_description' => 'short_description',
            'job_type' => 'job_type',
            'experience_level' => 'experience_level',
            'salary_range' => 'salary_range',
            'job_location' => 'job_location',
            'company_size' => 'company_size',
            'required_skills' => 'required_skills',
            'education_level' => 'education_level',
            'english_level' => 'english_level',
            'job_benefits' => 'job_benefits',
            'application_deadline' => 'application_deadline',
            'contact_email' => 'contact_email',
            'contact_phone' => 'contact_phone',
            'company_website' => 'company_website',
            'is_urgent' => 'is_urgent',
            'is_featured' => 'is_featured',
            'application_method' => 'application_method',
            'meta_title' => 'meta_title',
            'meta_description' => 'meta_description',
            'meta_keywords' => 'meta_keywords',
        ];
    }

    /**
     * Lưu các attribute mặc định
     */
    protected function saveDefaultAttributes(int $productId, array $data): void
    {
        // Status = active
        $this->saveAttributeValue($productId, 'status', 1);
        
        // Visible individually = true
        $this->saveAttributeValue($productId, 'visible_individually', 1);
        
        // Price = 0 (free job posting)
        $this->saveAttributeValue($productId, 'price', 0);
        
        // URL key
        $urlKey = Str::slug($data['title'] ?? 'job-posting');
        $this->saveAttributeValue($productId, 'url_key', $urlKey);
        
        // New = true (for new job postings)
        $this->saveAttributeValue($productId, 'new', 1);
        
        // Featured
        $this->saveAttributeValue($productId, 'featured', $data['is_featured'] ?? 0);
    }

    /**
     * Apply filters to query
     */
    protected function applyFilters($query, array $filters): void
    {
        if (isset($filters['job_type'])) {
            $query->whereHas('attribute_values', function ($q) use ($filters) {
                $q->join('attributes', 'product_attribute_values.attribute_id', '=', 'attributes.id')
                  ->where('attributes.code', 'job_type')
                  ->whereHas('attribute.options.translations', function ($subQ) use ($filters) {
                      $subQ->where('label', $filters['job_type']);
                  });
            });
        }

        if (isset($filters['location'])) {
            $query->whereHas('attribute_values', function ($q) use ($filters) {
                $q->join('attributes', 'product_attribute_values.attribute_id', '=', 'attributes.id')
                  ->where('attributes.code', 'job_location')
                  ->where('text_value', 'LIKE', '%' . $filters['location'] . '%');
            });
        }

        if (isset($filters['salary_min']) || isset($filters['salary_max'])) {
            // Logic for salary range filtering
        }

        if (isset($filters['company'])) {
            $query->whereHas('attribute_values', function ($q) use ($filters) {
                $q->join('attributes', 'product_attribute_values.attribute_id', '=', 'attributes.id')
                  ->where('attributes.code', 'name')
                  ->where('text_value', 'LIKE', '%' . $filters['company'] . '%');
            });
        }
        
        if (isset($filters['is_urgent']) && $filters['is_urgent']) {
            $query->whereHas('attribute_values', function ($q) {
                $q->join('attributes', 'product_attribute_values.attribute_id', '=', 'attributes.id')
                  ->where('attributes.code', 'is_urgent')
                  ->where('integer_value', 1);
            });
        }

        if (isset($filters['is_featured']) && $filters['is_featured']) {
            $query->whereHas('attribute_values', function ($q) {
                $q->join('attributes', 'product_attribute_values.attribute_id', '=', 'attributes.id')
                  ->where('attributes.code', 'is_featured')
                  ->where('integer_value', 1);
            });
        }

        // Search trong title và description
        if (isset($filters['search']) && !empty($filters['search'])) {
            $searchTerm = $filters['search'];
            $query->whereHas('attribute_values', function ($q) use ($searchTerm) {
                $q->join('attributes', 'product_attribute_values.attribute_id', '=', 'attributes.id')
                  ->whereIn('attributes.code', ['name', 'description', 'short_description'])
                  ->where('text_value', 'LIKE', '%' . $searchTerm . '%');
            });
        }

        // Order by
        $orderBy = $filters['order_by'] ?? 'created_at';
        $orderDirection = $filters['order_direction'] ?? 'desc';
        
        if ($orderBy === 'deadline') {
            $query->leftJoin('product_attribute_values as deadline_pav', function ($join) {
                $join->on('products.id', '=', 'deadline_pav.product_id')
                     ->join('attributes as deadline_attr', 'deadline_pav.attribute_id', '=', 'deadline_attr.id')
                     ->where('deadline_attr.code', 'application_deadline');
            })->orderBy('deadline_pav.date_value', $orderDirection);
        } else {
            $query->orderBy($orderBy, $orderDirection);
        }
    }

    /**
     * Lấy giá trị attribute
     */
    protected function getAttributeValue(int $productId, string $attributeCode): ?string
    {
        $attributeValue = ProductAttributeValue::whereHas('attribute', function ($query) use ($attributeCode) {
            $query->where('code', $attributeCode);
        })->where('product_id', $productId)->first();

        return $attributeValue ? $attributeValue->text_value : null;
    }

    /**
     * Extract company name from title
     */
    protected function extractCompanyFromTitle(string $title): string
    {
        // Simple extraction logic - có thể cải thiện
        $parts = explode(' - ', $title);
        return count($parts) > 1 ? trim($parts[1]) : 'Company';
    }
    
    /**
     * Gán default channel cho product
     */
    protected function assignDefaultChannel(int $productId): void
    {
        DB::table('product_channels')->insert([
            'product_id' => $productId,
            'channel_id' => $this->defaultChannel->id,
        ]);
    }
}
