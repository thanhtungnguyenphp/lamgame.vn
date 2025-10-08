<?php

namespace App\Services;

use Webkul\Product\Models\Product;
use Webkul\Category\Models\Category;
use Webkul\Attribute\Models\Attribute;
use Webkul\Attribute\Models\AttributeOption;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Builder;

class JobSearchService
{
    protected int $jobCategoryId;
    
    public function __construct()
    {
        // Get job category ID
        $jobCategory = Category::whereHas('translations', function ($query) {
            $query->where('slug', 'viec-lam');
        })->first();
        
        $this->jobCategoryId = $jobCategory ? $jobCategory->id : 102;
    }
    
    /**
     * Advanced job search with multiple filters
     * 
     * @param array $filters
     * @param int $perPage
     * @param int|null $userId Filter by specific user (null for all users)
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function searchJobs(array $filters, int $perPage = 15, ?int $userId = null)
    {
        $cacheKey = $this->generateCacheKey($filters, $perPage, $userId);
        
        return Cache::remember($cacheKey, 300, function () use ($filters, $perPage, $userId) {
            $query = Product::query()
                ->whereHas('categories', function ($q) {
                    $q->where('category_id', $this->jobCategoryId);
                })
                ->with(['attribute_values.attribute', 'categories.translations']);
            
            // User-specific filter
            if ($userId) {
                $query->where('created_by_admin_id', $userId);
            }
            
            // Apply all filters
            $this->applySearchFilters($query, $filters);
            $this->applySortingFilters($query, $filters);
            
            return $query->paginate($perPage);
        });
    }
    
    /**
     * Full-text search across multiple fields
     * 
     * @param Builder $query
     * @param string $searchTerm
     */
    public function applyFullTextSearch(Builder $query, string $searchTerm): void
    {
        if (empty($searchTerm)) {
            return;
        }
        
        $searchTerms = $this->parseSearchTerms($searchTerm);
        
        $query->where(function ($q) use ($searchTerms) {
            foreach ($searchTerms as $term) {
                $q->orWhereHas('attribute_values', function ($subQ) use ($term) {
                    $subQ->join('attributes', 'product_attribute_values.attribute_id', '=', 'attributes.id')
                        ->whereIn('attributes.code', [
                            'name', 'description', 'short_description', 
                            'required_skills', 'job_benefits'
                        ])
                        ->where('text_value', 'LIKE', '%' . $term . '%');
                });
            }
        });
    }
    
    /**
     * Apply date range filters
     * 
     * @param Builder $query
     * @param array $dateFilters
     */
    public function applyDateFilters(Builder $query, array $dateFilters): void
    {
        // Created date range
        if (!empty($dateFilters['created_from'])) {
            $query->where('created_at', '>=', Carbon::parse($dateFilters['created_from']));
        }
        if (!empty($dateFilters['created_to'])) {
            $query->where('created_at', '<=', Carbon::parse($dateFilters['created_to'])->endOfDay());
        }
        
        // Updated date range
        if (!empty($dateFilters['updated_from'])) {
            $query->where('updated_at', '>=', Carbon::parse($dateFilters['updated_from']));
        }
        if (!empty($dateFilters['updated_to'])) {
            $query->where('updated_at', '<=', Carbon::parse($dateFilters['updated_to'])->endOfDay());
        }
        
        // Application deadline range
        if (!empty($dateFilters['deadline_from']) || !empty($dateFilters['deadline_to'])) {
            $query->whereHas('attribute_values', function ($q) use ($dateFilters) {
                $q->join('attributes', 'product_attribute_values.attribute_id', '=', 'attributes.id')
                  ->where('attributes.code', 'application_deadline');
                
                if (!empty($dateFilters['deadline_from'])) {
                    $q->where('date_value', '>=', Carbon::parse($dateFilters['deadline_from'])->format('Y-m-d'));
                }
                if (!empty($dateFilters['deadline_to'])) {
                    $q->where('date_value', '<=', Carbon::parse($dateFilters['deadline_to'])->format('Y-m-d'));
                }
            });
        }
    }
    
    /**
     * Apply salary range filters
     * 
     * @param Builder $query
     * @param array $salaryFilters
     */
    public function applySalaryFilters(Builder $query, array $salaryFilters): void
    {
        if (!empty($salaryFilters['min']) || !empty($salaryFilters['max'])) {
            $query->whereHas('attribute_values', function ($q) use ($salaryFilters) {
                $q->join('attributes', 'product_attribute_values.attribute_id', '=', 'attributes.id')
                  ->where('attributes.code', 'salary_range');
                
                // This is a simplified approach - in reality, you might want to 
                // parse salary ranges more intelligently
                if (!empty($salaryFilters['min'])) {
                    $q->where('text_value', 'LIKE', '%' . $salaryFilters['min'] . '%');
                }
                if (!empty($salaryFilters['max'])) {
                    $q->where('text_value', 'LIKE', '%' . $salaryFilters['max'] . '%');
                }
            });
        }
    }
    
    /**
     * Apply skills matching with AND/OR logic
     * 
     * @param Builder $query
     * @param array $skillFilters
     */
    public function applySkillFilters(Builder $query, array $skillFilters): void
    {
        if (empty($skillFilters['skills'])) {
            return;
        }
        
        $skills = is_array($skillFilters['skills']) ? $skillFilters['skills'] : [$skillFilters['skills']];
        $logic = $skillFilters['logic'] ?? 'OR'; // OR | AND
        
        if ($logic === 'AND') {
            // Must have ALL skills
            foreach ($skills as $skill) {
                $query->whereHas('attribute_values', function ($q) use ($skill) {
                    $q->join('attributes', 'product_attribute_values.attribute_id', '=', 'attributes.id')
                      ->where('attributes.code', 'required_skills')
                      ->where('text_value', 'LIKE', '%' . $skill . '%');
                });
            }
        } else {
            // Must have ANY of the skills
            $query->whereHas('attribute_values', function ($q) use ($skills) {
                $q->join('attributes', 'product_attribute_values.attribute_id', '=', 'attributes.id')
                  ->where('attributes.code', 'required_skills')
                  ->where(function ($subQ) use ($skills) {
                      foreach ($skills as $skill) {
                          $subQ->orWhere('text_value', 'LIKE', '%' . $skill . '%');
                      }
                  });
            });
        }
    }
    
    /**
     * Apply location-based search with radius (simplified)
     * 
     * @param Builder $query
     * @param array $locationFilters
     */
    public function applyLocationFilters(Builder $query, array $locationFilters): void
    {
        if (empty($locationFilters['location'])) {
            return;
        }
        
        $location = $locationFilters['location'];
        $radius = $locationFilters['radius'] ?? null; // For future geo-spatial search
        
        $query->whereHas('attribute_values', function ($q) use ($location) {
            $q->join('attributes', 'product_attribute_values.attribute_id', '=', 'attributes.id')
              ->where('attributes.code', 'job_location')
              ->where('text_value', 'LIKE', '%' . $location . '%');
        });
        
        // TODO: Implement radius-based search using geo coordinates
        // This would require storing lat/lng coordinates and using spatial queries
    }
    
    /**
     * Apply attribute-based filters (job_type, experience_level, etc.)
     * 
     * @param Builder $query
     * @param array $attributeFilters
     */
    public function applyAttributeFilters(Builder $query, array $attributeFilters): void
    {
        $attributeMappings = [
            'job_type' => 'job_type',
            'experience_level' => 'experience_level',
            'company_size' => 'company_size',
            'education_level' => 'education_level',
            'english_level' => 'english_level',
            'application_method' => 'application_method',
        ];
        
        foreach ($attributeMappings as $filterKey => $attributeCode) {
            if (!empty($attributeFilters[$filterKey])) {
                $values = is_array($attributeFilters[$filterKey]) 
                    ? $attributeFilters[$filterKey] 
                    : [$attributeFilters[$filterKey]];
                
                $query->whereHas('attribute_values', function ($q) use ($attributeCode, $values) {
                    $q->join('attributes', 'product_attribute_values.attribute_id', '=', 'attributes.id')
                      ->where('attributes.code', $attributeCode);
                    
                    // Handle both option-based and text-based attributes
                    $q->where(function ($subQ) use ($values) {
                        foreach ($values as $value) {
                            $subQ->orWhere('text_value', 'LIKE', '%' . $value . '%')
                                 ->orWhereExists(function ($optionQ) use ($value) {
                                     $optionQ->select(DB::raw(1))
                                         ->from('attribute_options as ao')
                                         ->join('attribute_option_translations as aot', 'ao.id', '=', 'aot.attribute_option_id')
                                         ->whereColumn('ao.id', 'product_attribute_values.integer_value')
                                         ->where('aot.label', 'LIKE', '%' . $value . '%');
                                 });
                        }
                    });
                });
            }
        }
    }
    
    /**
     * Apply boolean filters (is_urgent, is_featured, status)
     * 
     * @param Builder $query
     * @param array $booleanFilters
     */
    public function applyBooleanFilters(Builder $query, array $booleanFilters): void
    {
        $booleanAttributes = [
            'is_urgent' => 'is_urgent',
            'is_featured' => 'is_featured',
            'status' => 'status',
            'visible_individually' => 'visible_individually',
        ];
        
        foreach ($booleanAttributes as $filterKey => $attributeCode) {
            if (isset($booleanFilters[$filterKey])) {
                $value = (bool) $booleanFilters[$filterKey];
                
                $query->whereHas('attribute_values', function ($q) use ($attributeCode, $value) {
                    $q->join('attributes', 'product_attribute_values.attribute_id', '=', 'attributes.id')
                      ->where('attributes.code', $attributeCode)
                      ->where('integer_value', $value ? 1 : 0);
                });
            }
        }
    }
    
    /**
     * Apply all search filters
     * 
     * @param Builder $query
     * @param array $filters
     */
    protected function applySearchFilters(Builder $query, array $filters): void
    {
        // Full-text search
        if (!empty($filters['search'])) {
            $this->applyFullTextSearch($query, $filters['search']);
        }
        
        // Date filters
        if (!empty($filters['dates'])) {
            $this->applyDateFilters($query, $filters['dates']);
        }
        
        // Salary filters
        if (!empty($filters['salary'])) {
            $this->applySalaryFilters($query, $filters['salary']);
        }
        
        // Skills filters
        if (!empty($filters['skills'])) {
            $this->applySkillFilters($query, $filters['skills']);
        }
        
        // Location filters
        if (!empty($filters['location'])) {
            $this->applyLocationFilters($query, $filters['location']);
        }
        
        // Attribute filters
        $this->applyAttributeFilters($query, $filters);
        
        // Boolean filters
        $this->applyBooleanFilters($query, $filters);
    }
    
    /**
     * Apply sorting filters
     * 
     * @param Builder $query
     * @param array $filters
     */
    protected function applySortingFilters(Builder $query, array $filters): void
    {
        $sortBy = $filters['sort_by'] ?? 'created_at';
        $sortDirection = $filters['sort_direction'] ?? 'desc';
        
        switch ($sortBy) {
            case 'deadline':
                $query->leftJoin('product_attribute_values as pav_deadline', function ($join) {
                    $join->on('products.id', '=', 'pav_deadline.product_id')
                         ->join('attributes as attr_deadline', 'pav_deadline.attribute_id', '=', 'attr_deadline.id')
                         ->where('attr_deadline.code', 'application_deadline');
                })->orderBy('pav_deadline.date_value', $sortDirection);
                break;
                
            case 'salary':
                $query->leftJoin('product_attribute_values as pav_salary', function ($join) {
                    $join->on('products.id', '=', 'pav_salary.product_id')
                         ->join('attributes as attr_salary', 'pav_salary.attribute_id', '=', 'attr_salary.id')
                         ->where('attr_salary.code', 'salary_range');
                })->orderBy('pav_salary.text_value', $sortDirection);
                break;
                
            case 'title':
                $query->leftJoin('product_attribute_values as pav_title', function ($join) {
                    $join->on('products.id', '=', 'pav_title.product_id')
                         ->join('attributes as attr_title', 'pav_title.attribute_id', '=', 'attr_title.id')
                         ->where('attr_title.code', 'name');
                })->orderBy('pav_title.text_value', $sortDirection);
                break;
                
            case 'featured':
                $query->leftJoin('product_attribute_values as pav_featured', function ($join) {
                    $join->on('products.id', '=', 'pav_featured.product_id')
                         ->join('attributes as attr_featured', 'pav_featured.attribute_id', '=', 'attr_featured.id')
                         ->where('attr_featured.code', 'is_featured');
                })->orderBy('pav_featured.integer_value', 'desc')
                  ->orderBy('products.created_at', 'desc');
                break;
                
            default:
                $query->orderBy($sortBy, $sortDirection);
                break;
        }
    }
    
    /**
     * Parse search terms for full-text search
     * 
     * @param string $searchTerm
     * @return array
     */
    protected function parseSearchTerms(string $searchTerm): array
    {
        // Handle quoted phrases
        if (preg_match_all('/"([^"]+)"/', $searchTerm, $matches)) {
            $phrases = $matches[1];
            $searchTerm = preg_replace('/"[^"]+"/', '', $searchTerm);
        } else {
            $phrases = [];
        }
        
        // Split remaining terms by space
        $words = array_filter(explode(' ', $searchTerm));
        
        return array_merge($phrases, $words);
    }
    
    /**
     * Generate cache key for search results
     * 
     * @param array $filters
     * @param int $perPage
     * @param int|null $userId
     * @return string
     */
    protected function generateCacheKey(array $filters, int $perPage, ?int $userId): string
    {
        $keyData = [
            'filters' => $filters,
            'per_page' => $perPage,
            'user_id' => $userId,
            'category_id' => $this->jobCategoryId
        ];
        
        return 'job_search:' . md5(serialize($keyData));
    }
    
    /**
     * Get available filter options for UI
     * 
     * @return array
     */
    public function getFilterOptions(): array
    {
        return Cache::remember('job_filter_options', 3600, function () {
            $options = [];
            
            // Get job types
            $options['job_types'] = $this->getAttributeOptions('job_type');
            
            // Get experience levels
            $options['experience_levels'] = $this->getAttributeOptions('experience_level');
            
            // Get company sizes
            $options['company_sizes'] = $this->getAttributeOptions('company_size');
            
            // Get education levels
            $options['education_levels'] = $this->getAttributeOptions('education_level');
            
            // Get english levels
            $options['english_levels'] = $this->getAttributeOptions('english_level');
            
            // Get common locations (from existing jobs)
            $options['locations'] = $this->getCommonLocations();
            
            // Get common skills
            $options['skills'] = $this->getCommonSkills();
            
            return $options;
        });
    }
    
    /**
     * Get options for a specific attribute
     * 
     * @param string $attributeCode
     * @return array
     */
    protected function getAttributeOptions(string $attributeCode): array
    {
        $attribute = Attribute::where('code', $attributeCode)->first();
        if (!$attribute) {
            return [];
        }
        
        return $attribute->options()
            ->with(['translations' => function ($query) {
                $query->where('locale', 'vi');
            }])
            ->get()
            ->map(function ($option) {
                $translation = $option->translations->first();
                return [
                    'id' => $option->id,
                    'value' => $translation?->label ?? $option->admin_name,
                    'code' => $option->admin_name
                ];
            })
            ->toArray();
    }
    
    /**
     * Get common job locations from existing data
     * 
     * @return array
     */
    protected function getCommonLocations(): array
    {
        return DB::table('product_attribute_values')
            ->join('attributes', 'product_attribute_values.attribute_id', '=', 'attributes.id')
            ->join('products', 'product_attribute_values.product_id', '=', 'products.id')
            ->join('product_categories', 'products.id', '=', 'product_categories.product_id')
            ->where('attributes.code', 'job_location')
            ->where('product_categories.category_id', $this->jobCategoryId)
            ->whereNotNull('text_value')
            ->where('text_value', '!=', '')
            ->select('text_value', DB::raw('COUNT(*) as count'))
            ->groupBy('text_value')
            ->orderBy('count', 'desc')
            ->limit(20)
            ->get()
            ->map(function ($item) {
                return [
                    'location' => $item->text_value,
                    'count' => $item->count
                ];
            })
            ->toArray();
    }
    
    /**
     * Get common skills from existing data
     * 
     * @return array
     */
    protected function getCommonSkills(): array
    {
        $skills = DB::table('product_attribute_values')
            ->join('attributes', 'product_attribute_values.attribute_id', '=', 'attributes.id')
            ->join('products', 'product_attribute_values.product_id', '=', 'products.id')
            ->join('product_categories', 'products.id', '=', 'product_categories.product_id')
            ->where('attributes.code', 'required_skills')
            ->where('product_categories.category_id', $this->jobCategoryId)
            ->whereNotNull('text_value')
            ->where('text_value', '!=', '')
            ->pluck('text_value')
            ->toArray();
        
        $allSkills = [];
        foreach ($skills as $skillString) {
            // Parse multiselect values or comma-separated skills
            $skillArray = explode(',', $skillString);
            foreach ($skillArray as $skill) {
                $skill = trim($skill);
                if (!empty($skill)) {
                    $allSkills[] = $skill;
                }
            }
        }
        
        // Count occurrences and return top skills
        $skillCounts = array_count_values($allSkills);
        arsort($skillCounts);
        
        return array_slice(array_keys($skillCounts), 0, 50);
    }
    
    /**
     * Save search filter as template
     * 
     * @param array $filters
     * @param string $name
     * @param int $userId
     * @return bool
     */
    public function saveFilterTemplate(array $filters, string $name, int $userId): bool
    {
        // This would typically save to a database table like 'job_search_templates'
        // For now, we'll use cache as a simple implementation
        
        $templates = Cache::get("job_search_templates:user:{$userId}", []);
        
        $templates[$name] = [
            'filters' => $filters,
            'created_at' => Carbon::now()->toISOString()
        ];
        
        Cache::put("job_search_templates:user:{$userId}", $templates, 86400 * 30); // 30 days
        
        return true;
    }
    
    /**
     * Get saved search templates for user
     * 
     * @param int $userId
     * @return array
     */
    public function getFilterTemplates(int $userId): array
    {
        return Cache::get("job_search_templates:user:{$userId}", []);
    }
    
    /**
     * Clear search cache
     * 
     * @return bool
     */
    public function clearSearchCache(): bool
    {
        // Clear all job search related cache
        $tags = ['job_search', 'job_filter_options'];
        
        foreach ($tags as $tag) {
            Cache::forget($tag);
        }
        
        return true;
    }
}