<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\JobService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class JobControllerOptimized extends Controller
{
    protected JobService $jobService;

    public function __construct(JobService $jobService)
    {
        $this->jobService = $jobService;
    }

    /**
     * Create job with optimized validation and response
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'description' => 'required|string',
            'short_description' => 'required|string|max:500',
            'job_type' => 'required|integer|exists:attribute_options,id',
            'experience_level' => 'required|integer|exists:attribute_options,id',
            'salary_range' => 'required|integer|exists:attribute_options,id',
            'job_location' => 'required|integer|exists:attribute_options,id',
            'required_skills' => 'array|exists:attribute_options,id',
            'job_benefits' => 'array|exists:attribute_options,id',
            'contact_email' => 'required|email|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'application_deadline' => 'required|date|after:today',
            'is_urgent' => 'boolean',
            'is_featured' => 'boolean',
            'categories' => 'array|exists:categories,id',
            'thumbnail' => 'nullable|string' // base64 image
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $job = $this->jobService->createJobPosting($validator->validated());
            
            return response()->json([
                'success' => true,
                'message' => 'Job created successfully',
                'data' => $this->formatJobResponse($job)
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create job',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get job form options (cached)
     */
    public function getFormOptions(): JsonResponse
    {
        $options = Cache::remember('job_form_options', 3600, function () {
            return [
                'job_types' => $this->getAttributeOptions(40),
                'experience_levels' => $this->getAttributeOptions(41),
                'salary_ranges' => $this->getAttributeOptions(42),
                'locations' => $this->getAttributeOptions(43),
                'skills' => $this->getAttributeOptions(45),
                'benefits' => $this->getAttributeOptions(48),
                'categories' => $this->getJobCategories()
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $options
        ]);
    }

    /**
     * Get jobs with optimized query and response
     */
    public function index(Request $request): JsonResponse
    {
        $filters = [
            'search' => $request->get('search'),
            'job_type' => $request->get('job_type'),
            'location' => $request->get('location'),
            'experience_level' => $request->get('experience_level'),
            'salary_range' => $request->get('salary_range'),
            'skills' => $request->get('skills', []),
            'is_urgent' => $request->boolean('is_urgent'),
            'is_featured' => $request->boolean('is_featured'),
            'order_by' => $request->get('order_by', 'created_at'),
            'order_direction' => $request->get('order_direction', 'desc')
        ];

        $perPage = min($request->get('per_page', 15), 50);

        try {
            $jobs = $this->jobService->getJobPostings($filters, $perPage);
            
            return response()->json([
                'success' => true,
                'data' => $jobs->items()->map(function ($job) {
                    return $this->formatJobListResponse($job);
                }),
                'pagination' => [
                    'current_page' => $jobs->currentPage(),
                    'per_page' => $jobs->perPage(),
                    'total' => $jobs->total(),
                    'last_page' => $jobs->lastPage(),
                    'from' => $jobs->firstItem(),
                    'to' => $jobs->lastItem()
                ],
                'filters_applied' => array_filter($filters)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve jobs',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Update job with optimized validation
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|string|max:255',
            'company_name' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'short_description' => 'sometimes|string|max:500',
            'job_type' => 'sometimes|integer|exists:attribute_options,id',
            'experience_level' => 'sometimes|integer|exists:attribute_options,id',
            'salary_range' => 'sometimes|integer|exists:attribute_options,id',
            'job_location' => 'sometimes|integer|exists:attribute_options,id',
            'required_skills' => 'sometimes|array|exists:attribute_options,id',
            'job_benefits' => 'sometimes|array|exists:attribute_options,id',
            'contact_email' => 'sometimes|email|max:255',
            'contact_phone' => 'sometimes|nullable|string|max:20',
            'application_deadline' => 'sometimes|date|after:today',
            'is_urgent' => 'sometimes|boolean',
            'is_featured' => 'sometimes|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $job = $this->jobService->findJobById($id);
            if (!$job) {
                return response()->json([
                    'success' => false,
                    'message' => 'Job not found'
                ], 404);
            }

            $updatedJob = $this->jobService->updateJobPosting($job, $validator->validated());
            
            return response()->json([
                'success' => true,
                'message' => 'Job updated successfully',
                'data' => $this->formatJobResponse($updatedJob)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update job',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get job analytics
     */
    public function analytics(int $id): JsonResponse
    {
        try {
            $analytics = $this->jobService->getJobAnalytics($id);
            
            return response()->json([
                'success' => true,
                'data' => $analytics
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve analytics',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Bulk operations
     */
    public function bulkUpdate(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'job_ids' => 'required|array|min:1',
            'job_ids.*' => 'integer|exists:products,id',
            'action' => 'required|in:activate,deactivate,delete,feature,unfeature',
            'data' => 'sometimes|array'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $result = $this->jobService->bulkUpdateJobs(
                $request->job_ids,
                $request->action,
                $request->data ?? []
            );
            
            return response()->json([
                'success' => true,
                'message' => "Bulk {$request->action} completed",
                'data' => $result
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Bulk operation failed',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get attribute options by attribute ID
     */
    private function getAttributeOptions(int $attributeId): array
    {
        return \DB::table('attribute_options as ao')
            ->join('attribute_option_translations as aot', 'ao.id', '=', 'aot.attribute_option_id')
            ->where('ao.attribute_id', $attributeId)
            ->where('aot.locale', 'vi')
            ->select('ao.id', 'aot.label')
            ->orderBy('ao.sort_order')
            ->get()
            ->toArray();
    }

    /**
     * Get job categories
     */
    private function getJobCategories(): array
    {
        return \DB::table('categories as c')
            ->join('category_translations as ct', 'c.id', '=', 'ct.category_id')
            ->where('ct.locale', 'vi')
            ->where('ct.slug', 'LIKE', '%viec-lam%')
            ->select('c.id', 'ct.name', 'ct.slug')
            ->get()
            ->toArray();
    }

    /**
     * Format job response for creation/update
     */
    private function formatJobResponse($job): array
    {
        return [
            'id' => $job->id,
            'sku' => $job->sku,
            'url_key' => $this->getAttributeValue($job, 'url_key'),
            'title' => $this->getAttributeValue($job, 'name'),
            'company_name' => $this->extractCompanyName($job),
            'salary_formatted' => $this->formatSalary($job),
            'location' => $this->getLocationLabel($job),
            'created_at' => $job->created_at->toISOString()
        ];
    }

    /**
     * Format job response for listing
     */
    private function formatJobListResponse($job): array
    {
        return [
            'id' => $job->id,
            'title' => $this->getAttributeValue($job, 'name'),
            'company_name' => $this->extractCompanyName($job),
            'url_key' => $this->getAttributeValue($job, 'url_key'),
            'thumbnail_url' => $this->getThumbnailUrl($job),
            'salary_formatted' => $this->formatSalary($job),
            'location' => $this->getLocationLabel($job),
            'job_type' => $this->getJobTypeLabel($job),
            'experience_level' => $this->getExperienceLevelLabel($job),
            'skills' => $this->getSkillsLabels($job),
            'is_urgent' => $this->getBooleanAttribute($job, 'is_urgent'),
            'is_featured' => $this->getBooleanAttribute($job, 'is_featured'),
            'posted_ago' => $job->created_at->diffForHumans(),
            'applications_count' => $this->getApplicationsCount($job->id)
        ];
    }

    /**
     * Helper methods for data extraction
     */
    private function getAttributeValue($job, string $attribute): ?string
    {
        return $job->attribute_values
            ->where('attribute.code', $attribute)
            ->first()?->text_value;
    }

    private function extractCompanyName($job): string
    {
        $name = $this->getAttributeValue($job, 'name') ?? '';
        $parts = explode(' - ', $name);
        return count($parts) > 1 ? trim($parts[1]) : 'Company';
    }

    private function formatSalary($job): string
    {
        $salaryId = $job->attribute_values
            ->where('attribute.code', 'salary_range')
            ->first()?->integer_value;
            
        if (!$salaryId) return 'Thỏa thuận';
        
        $salaryLabel = \DB::table('attribute_option_translations')
            ->where('attribute_option_id', $salaryId)
            ->where('locale', 'vi')
            ->value('label');
            
        return $salaryLabel ?? 'Thỏa thuận';
    }

    private function getLocationLabel($job): string
    {
        $locationId = $job->attribute_values
            ->where('attribute.code', 'job_location')
            ->first()?->integer_value;
            
        if (!$locationId) return 'Việt Nam';
        
        return \DB::table('attribute_option_translations')
            ->where('attribute_option_id', $locationId)
            ->where('locale', 'vi')
            ->value('label') ?? 'Việt Nam';
    }

    private function getJobTypeLabel($job): string
    {
        $typeId = $job->attribute_values
            ->where('attribute.code', 'job_type')
            ->first()?->integer_value;
            
        if (!$typeId) return 'Full-time';
        
        return \DB::table('attribute_option_translations')
            ->where('attribute_option_id', $typeId)
            ->where('locale', 'vi')
            ->value('label') ?? 'Full-time';
    }

    private function getExperienceLevelLabel($job): string
    {
        $levelId = $job->attribute_values
            ->where('attribute.code', 'experience_level')
            ->first()?->integer_value;
            
        if (!$levelId) return 'Tất cả cấp độ';
        
        return \DB::table('attribute_option_translations')
            ->where('attribute_option_id', $levelId)
            ->where('locale', 'vi')
            ->value('label') ?? 'Tất cả cấp độ';
    }

    private function getSkillsLabels($job): array
    {
        $skillsValue = $job->attribute_values
            ->where('attribute.code', 'required_skills')
            ->first()?->text_value;
            
        if (!$skillsValue) return [];
        
        $skillIds = explode(',', $skillsValue);
        
        return \DB::table('attribute_option_translations')
            ->whereIn('attribute_option_id', $skillIds)
            ->where('locale', 'vi')
            ->pluck('label')
            ->toArray();
    }

    private function getBooleanAttribute($job, string $attribute): bool
    {
        return (bool) $job->attribute_values
            ->where('attribute.code', $attribute)
            ->first()?->integer_value;
    }

    private function getThumbnailUrl($job): string
    {
        // Implementation for getting thumbnail URL
        return 'https://images.unsplash.com/photo-1560472354-b33ff0c44a43?w=400&h=250&fit=crop&q=80';
    }

    private function getApplicationsCount(int $jobId): int
    {
        return \DB::table('job_applications')
            ->where('job_id', $jobId)
            ->count();
    }
}
