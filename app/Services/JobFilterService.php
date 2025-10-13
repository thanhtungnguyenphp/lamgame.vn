<?php

namespace App\Services;

use Webkul\Product\Models\Product;
use Webkul\Category\Models\Category;
use Webkul\Attribute\Models\Attribute;
use Webkul\Attribute\Models\AttributeOption;
use Webkul\Product\Models\ProductAttributeValue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class JobFilterService
{
    protected $jobCategoryId;
    protected $cacheTime = 3600; // 1 hour

    public function __construct()
    {
        // Tìm job category (Việc Làm)
        $jobCategory = Category::whereHas('translations', function ($query) {
            $query->where('slug', 'viec-lam');
        })->first();
        
        $this->jobCategoryId = $jobCategory ? $jobCategory->id : 102;
    }

    /**
     * Get all filter options for job search/filter forms
     * 
     * @return array
     */
    public function getAllFilterOptions(): array
    {
        return Cache::remember('job_filter_options', $this->cacheTime, function () {
            return [
                'job_types' => $this->getAttributeOptions('job_type'),
                'experience_levels' => $this->getAttributeOptions('experience_level'),
                'salary_ranges' => $this->getAttributeOptions('salary_range'),
                'education_levels' => $this->getAttributeOptions('education_level'),
                'english_levels' => $this->getAttributeOptions('english_level'),
                'company_sizes' => $this->getAttributeOptions('company_size'),
                'application_methods' => $this->getAttributeOptions('application_method'),
                'categories' => $this->getJobCategories(),
                'locations' => $this->getLocations(null, 20),
                'popular_skills' => $this->getSkills(null, null, 20),
                'common_benefits' => $this->getBenefits(null, 15),
            ];
        });
    }

    /**
     * Get job attributes formatted for forms
     * 
     * @return array
     */
    public function getJobAttributesForForm(): array
    {
        return Cache::remember('job_form_attributes', $this->cacheTime, function () {
            $jobAttributeCodes = [
                'job_type', 'experience_level', 'salary_range', 'job_location',
                'company_size', 'required_skills', 'education_level', 'english_level',
                'job_benefits', 'application_method'
            ];

            $attributes = Attribute::whereIn('code', $jobAttributeCodes)
                ->with(['options.translations' => function ($query) {
                    $query->where('locale', 'vi');
                }, 'translations' => function ($query) {
                    $query->where('locale', 'vi');
                }])
                ->get()
                ->map(function ($attribute) {
                    $translation = $attribute->translations->first();
                    
                    return [
                        'code' => $attribute->code,
                        'name' => $translation?->name ?? $attribute->admin_name,
                        'type' => $attribute->type,
                        'is_required' => $attribute->is_required,
                        'is_filterable' => $attribute->is_filterable,
                        'options' => $attribute->options->map(function ($option) {
                            $optionTranslation = $option->translations->first();
                            return [
                                'id' => $option->id,
                                'value' => $optionTranslation?->label ?? $option->admin_name,
                                'sort_order' => $option->sort_order,
                            ];
                        })->sortBy('sort_order')->values(),
                    ];
                });

            return $attributes->keyBy('code')->toArray();
        });
    }

    /**
     * Get job categories with job counts
     * 
     * @return array
     */
    public function getJobCategories(): array
    {
        return Cache::remember('job_categories', $this->cacheTime, function () {
            $categories = Category::where('parent_id', $this->jobCategoryId)
                ->where('status', 1)
                ->with('translations')
                ->withCount(['products as job_count' => function ($query) {
                    $query->whereHas('categories', function ($q) {
                        $q->where('category_id', $this->jobCategoryId);
                    });
                }])
                ->orderBy('position')
                ->get();

            return $categories->map(function ($category) {
                $translation = $category->translations->where('locale', 'vi')->first();
                return [
                    'id' => $category->id,
                    'name' => $translation?->name ?? $category->name,
                    'slug' => $translation?->slug ?? $category->slug,
                    'job_count' => $category->job_count ?? 0,
                    'position' => $category->position,
                ];
            })->toArray();
        });
    }

    /**
     * Get locations from existing job postings and predefined list
     * 
     * @param string|null $search
     * @param int $limit
     * @return array
     */
    public function getLocations(?string $search = null, int $limit = 50): array
    {
        $cacheKey = "job_locations_" . md5($search . $limit);
        
        return Cache::remember($cacheKey, $this->cacheTime / 2, function () use ($search, $limit) {
            // Get locations from existing job postings
            $existingLocations = $this->getAttributeValues('job_location', $search, $limit / 2);
            
            // Predefined Vietnamese locations
            $vietnamLocations = $this->getVietnameseLocations($search, $limit / 2);
            
            // Combine and remove duplicates
            $allLocations = collect($existingLocations)
                ->merge($vietnamLocations)
                ->unique('value')
                ->take($limit)
                ->values()
                ->toArray();

            return $allLocations;
        });
    }

    /**
     * Get skills from job postings
     * 
     * @param string|null $search
     * @param string|null $category
     * @param int $limit
     * @return array
     */
    public function getSkills(?string $search = null, ?string $category = null, int $limit = 50): array
    {
        $cacheKey = "job_skills_" . md5($search . $category . $limit);
        
        return Cache::remember($cacheKey, $this->cacheTime / 2, function () use ($search, $category, $limit) {
            // Get skills from existing job postings
            $existingSkills = $this->getMultiSelectAttributeValues('required_skills', $search, $limit);
            
            // Add predefined popular skills if needed
            if (count($existingSkills) < $limit) {
                $popularSkills = $this->getPopularSkills($search, $category, $limit - count($existingSkills));
                $existingSkills = array_merge($existingSkills, $popularSkills);
            }
            
            return array_slice(array_unique($existingSkills, SORT_REGULAR), 0, $limit);
        });
    }

    /**
     * Get companies from job postings
     * 
     * @param string|null $search
     * @param int $limit
     * @return array
     */
    public function getCompanies(?string $search = null, int $limit = 50): array
    {
        $cacheKey = "job_companies_" . md5($search . $limit);
        
        return Cache::remember($cacheKey, $this->cacheTime / 2, function () use ($search, $limit) {
            $query = Product::whereHas('categories', function ($q) {
                    $q->where('category_id', $this->jobCategoryId);
                })
                ->whereHas('attribute_values', function ($q) {
                    $q->whereHas('attribute', function ($attr) {
                        $attr->where('code', 'name');
                    });
                })
                ->with(['attribute_values' => function ($q) {
                    $q->whereHas('attribute', function ($attr) {
                        $attr->where('code', 'name');
                    });
                }]);

            if ($search) {
                $query->whereHas('attribute_values', function ($q) use ($search) {
                    $q->whereHas('attribute', function ($attr) {
                        $attr->where('code', 'name');
                    })->where('text_value', 'LIKE', "%{$search}%");
                });
            }

            $companies = $query->take($limit * 2)
                ->get()
                ->map(function ($product) {
                    $nameAttribute = $product->attribute_values
                        ->where('attribute.code', 'name')
                        ->first();
                    
                    if ($nameAttribute && $nameAttribute->text_value) {
                        // Extract company name from job title
                        $title = $nameAttribute->text_value;
                        $parts = explode(' - ', $title);
                        $companyName = count($parts) > 1 ? trim($parts[1]) : null;
                        
                        if ($companyName) {
                            return [
                                'value' => $companyName,
                                'job_count' => 1, // Will be aggregated below
                            ];
                        }
                    }
                    return null;
                })
                ->filter()
                ->groupBy('value')
                ->map(function ($companies, $companyName) {
                    return [
                        'value' => $companyName,
                        'job_count' => $companies->count(),
                    ];
                })
                ->sortByDesc('job_count')
                ->take($limit)
                ->values()
                ->toArray();

            return $companies;
        });
    }

    /**
     * Get benefits from job postings
     * 
     * @param string|null $search
     * @param int $limit
     * @return array
     */
    public function getBenefits(?string $search = null, int $limit = 50): array
    {
        $cacheKey = "job_benefits_" . md5($search . $limit);
        
        return Cache::remember($cacheKey, $this->cacheTime / 2, function () use ($search, $limit) {
            $existingBenefits = $this->getMultiSelectAttributeValues('job_benefits', $search, $limit);
            
            // Add common benefits if needed
            if (count($existingBenefits) < $limit) {
                $commonBenefits = $this->getCommonBenefits($search, $limit - count($existingBenefits));
                $existingBenefits = array_merge($existingBenefits, $commonBenefits);
            }
            
            return array_slice(array_unique($existingBenefits, SORT_REGULAR), 0, $limit);
        });
    }

    /**
     * Get salary ranges with statistics
     * 
     * @return array
     */
    public function getSalaryRangesWithStats(): array
    {
        return Cache::remember('salary_ranges_stats', $this->cacheTime, function () {
            $salaryRanges = $this->getAttributeOptions('salary_range');
            
            // Add job count for each salary range
            foreach ($salaryRanges as &$range) {
                $range['job_count'] = $this->getJobCountForAttributeOption('salary_range', $range['id']);
            }
            
            return $salaryRanges;
        });
    }

    /**
     * Get industries/categories with job counts
     * 
     * @return array
     */
    public function getIndustriesWithJobCounts(): array
    {
        return $this->getJobCategories();
    }

    /**
     * Get popular job search keywords
     * 
     * @return array
     */
    public function getPopularKeywords(): array
    {
        return Cache::remember('popular_job_keywords', $this->cacheTime * 2, function () {
            // This would ideally come from search logs
            // For now, return predefined popular keywords
            return [
                ['keyword' => 'PHP', 'count' => 150],
                ['keyword' => 'Laravel', 'count' => 120],
                ['keyword' => 'JavaScript', 'count' => 200],
                ['keyword' => 'React', 'count' => 180],
                ['keyword' => 'Marketing', 'count' => 90],
                ['keyword' => 'Designer', 'count' => 75],
                ['keyword' => 'Manager', 'count' => 60],
                ['keyword' => 'Sales', 'count' => 85],
                ['keyword' => 'Python', 'count' => 110],
                ['keyword' => 'Mobile App', 'count' => 95],
            ];
        });
    }

    /**
     * Get application methods
     * 
     * @return array
     */
    public function getApplicationMethods(): array
    {
        return $this->getAttributeOptions('application_method');
    }

    /**
     * Search across multiple option types
     * 
     * @param string $query
     * @param array $types
     * @param int $limit
     * @return array
     */
    public function searchAcrossOptions(string $query, array $types = [], int $limit = 10): array
    {
        $results = [];
        
        foreach ($types as $type) {
            switch ($type) {
                case 'skills':
                    $results['skills'] = $this->getSkills($query, null, $limit);
                    break;
                case 'locations':
                    $results['locations'] = $this->getLocations($query, $limit);
                    break;
                case 'companies':
                    $results['companies'] = $this->getCompanies($query, $limit);
                    break;
                case 'benefits':
                    $results['benefits'] = $this->getBenefits($query, $limit);
                    break;
            }
        }
        
        return $results;
    }

    // =====================================================
    // HELPER METHODS
    // =====================================================

    /**
     * Get options for a specific attribute
     * 
     * @param string $attributeCode
     * @return array
     */
    protected function getAttributeOptions(string $attributeCode): array
    {
        $attribute = Attribute::where('code', $attributeCode)
            ->with(['options.translations' => function ($query) {
                $query->where('locale', 'vi');
            }])
            ->first();

        if (!$attribute) {
            return [];
        }

        return $attribute->options->map(function ($option) {
            $translation = $option->translations->first();
            return [
                'id' => $option->id,
                'value' => $translation?->label ?? $option->admin_name,
                'sort_order' => $option->sort_order,
            ];
        })->sortBy('sort_order')->values()->toArray();
    }

    /**
     * Get attribute values from existing products
     * 
     * @param string $attributeCode
     * @param string|null $search
     * @param int $limit
     * @return array
     */
    protected function getAttributeValues(string $attributeCode, ?string $search = null, int $limit = 50): array
    {
        $query = ProductAttributeValue::whereHas('product.categories', function ($q) {
                $q->where('category_id', $this->jobCategoryId);
            })
            ->whereHas('attribute', function ($q) use ($attributeCode) {
                $q->where('code', $attributeCode);
            })
            ->whereNotNull('text_value')
            ->where('text_value', '!=', '');

        if ($search) {
            $query->where('text_value', 'LIKE', "%{$search}%");
        }

        return $query->select('text_value')
            ->groupBy('text_value')
            ->orderByRaw('COUNT(*) DESC')
            ->limit($limit)
            ->get()
            ->map(function ($item) {
                return ['value' => $item->text_value];
            })
            ->toArray();
    }

    /**
     * Get multi-select attribute values (for skills, benefits)
     * 
     * @param string $attributeCode
     * @param string|null $search
     * @param int $limit
     * @return array
     */
    protected function getMultiSelectAttributeValues(string $attributeCode, ?string $search = null, int $limit = 50): array
    {
        $values = ProductAttributeValue::whereHas('product.categories', function ($q) {
                $q->where('category_id', $this->jobCategoryId);
            })
            ->whereHas('attribute', function ($q) use ($attributeCode) {
                $q->where('code', $attributeCode);
            })
            ->whereNotNull('text_value')
            ->where('text_value', '!=', '')
            ->pluck('text_value')
            ->flatMap(function ($value) {
                // Split comma-separated option IDs and convert to labels
                $optionIds = explode(',', $value);
                $labels = [];
                
                foreach ($optionIds as $optionId) {
                    if (is_numeric($optionId)) {
                        $option = AttributeOption::find($optionId);
                        if ($option) {
                            $translation = $option->translations()->where('locale', 'vi')->first();
                            $label = $translation?->label ?? $option->admin_name;
                            if ($label) {
                                $labels[] = $label;
                            }
                        }
                    }
                }
                
                return $labels;
            })
            ->filter()
            ->countBy()
            ->sortDesc();

        if ($search) {
            $values = $values->filter(function ($count, $value) use ($search) {
                return stripos($value, $search) !== false;
            });
        }

        return $values->take($limit)
            ->map(function ($count, $value) {
                return ['value' => $value, 'count' => $count];
            })
            ->values()
            ->toArray();
    }

    /**
     * Get Vietnamese provinces/cities
     * 
     * @param string|null $search
     * @param int $limit
     * @return array
     */
    protected function getVietnameseLocations(?string $search = null, int $limit = 25): array
    {
        $locations = [
            'TP.HCM', 'Hà Nội', 'Đà Nẵng', 'Hải Phòng', 'Cần Thơ',
            'An Giang', 'Bà Rịa-Vũng Tàu', 'Bắc Giang', 'Bắc Kạn', 'Bạc Liêu',
            'Bắc Ninh', 'Bến Tre', 'Bình Định', 'Bình Dương', 'Bình Phước',
            'Bình Thuận', 'Cà Mau', 'Cao Bằng', 'Đắk Lắk', 'Đắk Nông',
            'Điện Biên', 'Đồng Nai', 'Đồng Tháp', 'Gia Lai', 'Hà Giang',
            'Hà Nam', 'Hà Tĩnh', 'Hải Dương', 'Hậu Giang', 'Hòa Bình',
            'Hưng Yên', 'Khánh Hòa', 'Kiên Giang', 'Kon Tum', 'Lai Châu',
            'Lâm Đồng', 'Lạng Sơn', 'Lào Cai', 'Long An', 'Nam Định',
            'Nghệ An', 'Ninh Bình', 'Ninh Thuận', 'Phú Thọ', 'Phú Yên',
            'Quảng Bình', 'Quảng Nam', 'Quảng Ngãi', 'Quảng Ninh', 'Quảng Trị',
            'Sóc Trăng', 'Sơn La', 'Tây Ninh', 'Thái Bình', 'Thái Nguyên',
            'Thanh Hóa', 'Thừa Thiên Huế', 'Tiền Giang', 'Trà Vinh', 'Tuyên Quang',
            'Vĩnh Long', 'Vĩnh Phúc', 'Yên Bái'
        ];

        if ($search) {
            $locations = array_filter($locations, function ($location) use ($search) {
                return stripos($location, $search) !== false;
            });
        }

        return array_slice(array_map(function ($location) {
            return ['value' => $location];
        }, array_values($locations)), 0, $limit);
    }

    /**
     * Get popular skills by category
     * 
     * @param string|null $search
     * @param string|null $category
     * @param int $limit
     * @return array
     */
    protected function getPopularSkills(?string $search = null, ?string $category = null, int $limit = 20): array
    {
        $skillsByCategory = [
            'IT' => [
                'PHP', 'Laravel', 'JavaScript', 'React', 'Vue.js', 'Node.js',
                'Python', 'Java', 'C#', '.NET', 'SQL', 'MySQL', 'PostgreSQL',
                'MongoDB', 'Redis', 'Docker', 'AWS', 'Git', 'HTML/CSS', 'Bootstrap'
            ],
            'Marketing' => [
                'Digital Marketing', 'SEO', 'SEM', 'Google Ads', 'Facebook Ads',
                'Content Marketing', 'Email Marketing', 'Social Media', 'Analytics',
                'Photoshop', 'Adobe Creative', 'Copywriting', 'Brand Management'
            ],
            'Design' => [
                'UI/UX', 'Photoshop', 'Illustrator', 'Figma', 'Sketch',
                'Adobe XD', 'InDesign', 'After Effects', 'Premiere Pro',
                'Web Design', 'Graphic Design', 'Logo Design'
            ]
        ];

        $allSkills = [];
        if ($category && isset($skillsByCategory[$category])) {
            $allSkills = $skillsByCategory[$category];
        } else {
            foreach ($skillsByCategory as $skills) {
                $allSkills = array_merge($allSkills, $skills);
            }
        }

        if ($search) {
            $allSkills = array_filter($allSkills, function ($skill) use ($search) {
                return stripos($skill, $search) !== false;
            });
        }

        return array_slice(array_map(function ($skill) {
            return ['value' => $skill];
        }, array_values($allSkills)), 0, $limit);
    }

    /**
     * Get common job benefits
     * 
     * @param string|null $search
     * @param int $limit
     * @return array
     */
    protected function getCommonBenefits(?string $search = null, int $limit = 15): array
    {
        $benefits = [
            'Bảo hiểm sức khỏe', 'Bảo hiểm xã hội', 'Thưởng tháng 13',
            'Thưởng hiệu quả', 'Du lịch công ty', 'Team building',
            'Đào tạo và phát triển', 'Làm việc từ xa', 'Giờ làm việc linh hoạt',
            'Phụ cấp ăn trưa', 'Phụ cấp xăng xe', 'Nghỉ phép có lương',
            'Chăm sóc sức khỏe định kỳ', 'Môi trường làm việc trẻ trung',
            'Cơ hội thăng tiến', 'Laptop và thiết bị làm việc'
        ];

        if ($search) {
            $benefits = array_filter($benefits, function ($benefit) use ($search) {
                return stripos($benefit, $search) !== false;
            });
        }

        return array_slice(array_map(function ($benefit) {
            return ['value' => $benefit];
        }, array_values($benefits)), 0, $limit);
    }

    /**
     * Get job count for specific attribute option
     * 
     * @param string $attributeCode
     * @param int $optionId
     * @return int
     */
    protected function getJobCountForAttributeOption(string $attributeCode, int $optionId): int
    {
        return ProductAttributeValue::whereHas('product.categories', function ($q) {
                $q->where('category_id', $this->jobCategoryId);
            })
            ->whereHas('attribute', function ($q) use ($attributeCode) {
                $q->where('code', $attributeCode);
            })
            ->where('integer_value', $optionId)
            ->count();
    }
}