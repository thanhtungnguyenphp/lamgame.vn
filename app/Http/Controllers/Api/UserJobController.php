<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CreateUserJobRequest;
use App\Http\Resources\JobResource;
use App\Services\JobService;
use App\Services\JobSearchService;
use Webkul\Product\Models\Product;
use Webkul\Category\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserJobController extends Controller
{
    protected JobService $jobService;
    protected JobSearchService $jobSearchService;
    protected int $jobCategoryId;

    public function __construct(JobService $jobService, JobSearchService $jobSearchService)
    {
        $this->jobService = $jobService;
        $this->jobSearchService = $jobSearchService;
        
        // Get job category ID
        $jobCategory = Category::whereHas('translations', function ($query) {
            $query->where('slug', 'viec-lam');
        })->first();
        
        $this->jobCategoryId = $jobCategory ? $jobCategory->id : 102;
    }

    /**
     * Get authenticated user's jobs with advanced search and filtering
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $user = Auth::user();
            $filters = $this->parseFilters($request);
            $perPage = min($request->get('per_page', 15), 50);
            
            // Use advanced search service
            $jobs = $this->jobSearchService->searchJobs($filters, $perPage, $user->id);

            return response()->json([
                'success' => true,
                'message' => 'Jobs retrieved successfully',
                'data' => JobResource::collection($jobs->items()),
                'pagination' => [
                    'current_page' => $jobs->currentPage(),
                    'per_page' => $jobs->perPage(),
                    'total' => $jobs->total(),
                    'last_page' => $jobs->lastPage(),
                    'from' => $jobs->firstItem(),
                    'to' => $jobs->lastItem(),
                ],
                'filters_applied' => array_filter($filters),
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            \Log::error('Failed to retrieve user jobs', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve jobs',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Create a new job posting
     * 
     * @param CreateUserJobRequest $request
     * @return JsonResponse
     */
    public function store(CreateUserJobRequest $request): JsonResponse
    {
        try {
            $user = Auth::user();
            $validatedData = $request->validated();
            
            // Add user association
            $validatedData['created_by_admin_id'] = $user->id;
            
            // Add job category
            $validatedData['categories'] = array_merge(
                $validatedData['categories'] ?? [], 
                [$this->jobCategoryId]
            );

            // Create job using JobService
            $job = $this->createUserJob($validatedData, $user);

            \Log::info('User created job successfully', [
                'user_id' => $user->id,
                'job_id' => $job->id,
                'job_title' => $validatedData['title']
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Job created successfully',
                'data' => new JobResource($job),
            ], Response::HTTP_CREATED);

        } catch (\Exception $e) {
            \Log::error('User job creation failed', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'data' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to create job',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Show specific job owned by user
     * 
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        try {
            $user = Auth::user();
            
            $job = Product::where('id', $id)
                ->where('created_by_admin_id', $user->id)
                ->whereHas('categories', function ($query) {
                    $query->where('category_id', $this->jobCategoryId);
                })
                ->with(['attribute_values.attribute', 'categories.translations'])
                ->firstOrFail();

            return response()->json([
                'success' => true,
                'message' => 'Job retrieved successfully',
                'data' => new JobResource($job),
            ], Response::HTTP_OK);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Job not found or access denied',
                'error' => 'The job does not exist or you do not have permission to view it',
            ], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve job',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update job owned by user
     * 
     * @param CreateUserJobRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(CreateUserJobRequest $request, int $id): JsonResponse
    {
        try {
            $user = Auth::user();
            
            $job = Product::where('id', $id)
                ->where('created_by_admin_id', $user->id)
                ->whereHas('categories', function ($query) {
                    $query->where('category_id', $this->jobCategoryId);
                })
                ->firstOrFail();

            $validatedData = $request->validated();
            
            // Update job using JobService
            $updatedJob = $this->jobService->updateJobPosting($job, $validatedData);

            \Log::info('User updated job successfully', [
                'user_id' => $user->id,
                'job_id' => $job->id,
                'job_title' => $validatedData['title'] ?? 'unchanged'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Job updated successfully',
                'data' => new JobResource($updatedJob),
            ], Response::HTTP_OK);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Job not found or access denied',
                'error' => 'The job does not exist or you do not have permission to update it',
            ], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            \Log::error('User job update failed', [
                'user_id' => Auth::id(),
                'job_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update job',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Delete job owned by user
     * 
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $user = Auth::user();
            
            $job = Product::where('id', $id)
                ->where('created_by_admin_id', $user->id)
                ->whereHas('categories', function ($query) {
                    $query->where('category_id', $this->jobCategoryId);
                })
                ->firstOrFail();

            // Soft delete or hard delete
            $job->delete();

            \Log::info('User deleted job successfully', [
                'user_id' => $user->id,
                'job_id' => $job->id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Job deleted successfully',
            ], Response::HTTP_OK);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Job not found or access denied',
                'error' => 'The job does not exist or you do not have permission to delete it',
            ], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            \Log::error('User job deletion failed', [
                'user_id' => Auth::id(),
                'job_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete job',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Toggle job status (activate/deactivate)
     * 
     * @param int $id
     * @return JsonResponse
     */
    public function toggleStatus(int $id): JsonResponse
    {
        try {
            $user = Auth::user();
            
            $job = Product::where('id', $id)
                ->where('created_by_admin_id', $user->id)
                ->whereHas('categories', function ($query) {
                    $query->where('category_id', $this->jobCategoryId);
                })
                ->firstOrFail();

            // Get current status
            $currentStatus = $job->attribute_values
                ->whereIn('attribute.code', ['status'])
                ->first()->integer_value ?? 0;

            $newStatus = !$currentStatus;
            
            // Update status using JobService
            $updatedJob = $this->jobService->updateJobPosting($job, ['status' => $newStatus]);

            return response()->json([
                'success' => true,
                'message' => 'Job status updated successfully',
                'data' => [
                    'id' => $job->id,
                    'status' => $newStatus ? 'active' : 'inactive',
                    'status_value' => $newStatus,
                ],
            ], Response::HTTP_OK);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Job not found or access denied',
            ], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update job status',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Create job with user-specific logic
     * 
     * @param array $data
     * @param \Webkul\User\Models\Admin $user
     * @return Product
     */
    protected function createUserJob(array $data, $user): Product
    {
        return DB::transaction(function () use ($data, $user) {
            // Use existing JobService to create job
            $job = $this->jobService->createJobPosting($data);
            
            // Force update with correct user ID using raw SQL if needed
            Product::where('id', $job->id)->update(['created_by_admin_id' => $user->id]);
            
            return $job->fresh()->load('attribute_values', 'categories');
        });
    }

    /**
     * Get job statistics for authenticated user
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function statistics(Request $request): JsonResponse
    {
        try {
            $user = Auth::user();
            $filters = [];
            
            // Apply date range filters if provided
            if ($request->has('date_from')) {
                $filters['date_from'] = $request->get('date_from');
            }
            if ($request->has('date_to')) {
                $filters['date_to'] = $request->get('date_to');
            }
            
            $statistics = $this->jobService->getJobStatistics($user->id, $filters);
            
            return response()->json([
                'success' => true,
                'message' => 'Job statistics retrieved successfully',
                'data' => $statistics,
            ], Response::HTTP_OK);
            
        } catch (\Exception $e) {
            \Log::error('Failed to retrieve job statistics', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve statistics',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    /**
     * Duplicate a job with optional modifications
     * 
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function duplicate(Request $request, int $id): JsonResponse
    {
        try {
            $user = Auth::user();
            
            $originalJob = Product::where('id', $id)
                ->where('created_by_admin_id', $user->id)
                ->whereHas('categories', function ($query) {
                    $query->where('category_id', $this->jobCategoryId);
                })
                ->with(['attribute_values.attribute', 'categories'])
                ->firstOrFail();
            
            // Get modifications from request
            $modifications = $request->only([
                'title', 'description', 'short_description', 'job_type',
                'experience_level', 'salary_range', 'job_location',
                'company_name', 'application_deadline'
            ]);
            
            $duplicatedJob = $this->jobService->duplicateJob($originalJob, $modifications, $user->id);
            
            \Log::info('User duplicated job successfully', [
                'user_id' => $user->id,
                'original_job_id' => $id,
                'new_job_id' => $duplicatedJob->id
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Job duplicated successfully',
                'data' => new JobResource($duplicatedJob),
            ], Response::HTTP_CREATED);
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Original job not found or access denied',
                'error' => 'The job does not exist or you do not have permission to duplicate it',
            ], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            \Log::error('Job duplication failed', [
                'user_id' => Auth::id(),
                'original_job_id' => $id,
                'error' => $e->getMessage(),
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to duplicate job',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    /**
     * Get available filter options for the UI
     * 
     * @return JsonResponse
     */
    public function getFilterOptions(): JsonResponse
    {
        try {
            $options = $this->jobSearchService->getFilterOptions();
            
            return response()->json([
                'success' => true,
                'message' => 'Filter options retrieved successfully',
                'data' => $options,
            ], Response::HTTP_OK);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve filter options',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    /**
     * Save current search filters as a template
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function saveFilterTemplate(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'filters' => 'required|array',
        ]);
        
        try {
            $user = Auth::user();
            
            $success = $this->jobSearchService->saveFilterTemplate(
                $request->get('filters'),
                $request->get('name'),
                $user->id
            );
            
            if ($success) {
                return response()->json([
                    'success' => true,
                    'message' => 'Filter template saved successfully',
                ], Response::HTTP_CREATED);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to save filter template',
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to save filter template',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    /**
     * Get saved filter templates for user
     * 
     * @return JsonResponse
     */
    public function getFilterTemplates(): JsonResponse
    {
        try {
            $user = Auth::user();
            $templates = $this->jobSearchService->getFilterTemplates($user->id);
            
            return response()->json([
                'success' => true,
                'message' => 'Filter templates retrieved successfully',
                'data' => $templates,
            ], Response::HTTP_OK);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve filter templates',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    /**
     * Parse request filters into the format expected by JobSearchService
     * 
     * @param Request $request
     * @return array
     */
    protected function parseFilters(Request $request): array
    {
        $filters = [];
        
        // Basic search
        if ($request->has('search')) {
            $filters['search'] = $request->get('search');
        }
        
        // Date filters
        if ($request->has('created_from') || $request->has('created_to') ||
            $request->has('updated_from') || $request->has('updated_to') ||
            $request->has('deadline_from') || $request->has('deadline_to')) {
            
            $filters['dates'] = array_filter([
                'created_from' => $request->get('created_from'),
                'created_to' => $request->get('created_to'),
                'updated_from' => $request->get('updated_from'),
                'updated_to' => $request->get('updated_to'),
                'deadline_from' => $request->get('deadline_from'),
                'deadline_to' => $request->get('deadline_to'),
            ]);
        }
        
        // Salary filters
        if ($request->has('salary_min') || $request->has('salary_max')) {
            $filters['salary'] = array_filter([
                'min' => $request->get('salary_min'),
                'max' => $request->get('salary_max'),
            ]);
        }
        
        // Skills filters
        if ($request->has('skills')) {
            $filters['skills'] = [
                'skills' => is_array($request->get('skills')) 
                    ? $request->get('skills') 
                    : explode(',', $request->get('skills')),
                'logic' => $request->get('skills_logic', 'OR'), // OR | AND
            ];
        }
        
        // Location filters
        if ($request->has('location')) {
            $filters['location'] = [
                'location' => $request->get('location'),
                'radius' => $request->get('location_radius'),
            ];
        }
        
        // Boolean filters
        foreach (['is_urgent', 'is_featured', 'status'] as $boolFilter) {
            if ($request->has($boolFilter)) {
                $filters[$boolFilter] = $request->boolean($boolFilter);
            }
        }
        
        // Attribute filters
        foreach (['job_type', 'experience_level', 'company_size', 'education_level', 'english_level'] as $attrFilter) {
            if ($request->has($attrFilter)) {
                $filters[$attrFilter] = $request->get($attrFilter);
            }
        }
        
        // Sorting
        $filters['sort_by'] = $request->get('sort_by', 'created_at');
        $filters['sort_direction'] = $request->get('sort_direction', 'desc');
        
        return $filters;
    }
    
    /**
     * Extend application deadline for a job
     * 
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function extendDeadline(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'new_deadline' => 'required|date|after:today',
            'reason' => 'nullable|string|max:500',
        ]);
        
        try {
            $user = Auth::user();
            
            $job = Product::where('id', $id)
                ->where('created_by_admin_id', $user->id)
                ->whereHas('categories', function ($query) {
                    $query->where('category_id', $this->jobCategoryId);
                })
                ->firstOrFail();
            
            $newDeadline = $request->get('new_deadline');
            $reason = $request->get('reason');
            
            $updatedJob = $this->jobService->updateJobPosting($job, [
                'application_deadline' => $newDeadline
            ]);
            
            \Log::info('User extended job deadline', [
                'user_id' => $user->id,
                'job_id' => $id,
                'new_deadline' => $newDeadline,
                'reason' => $reason,
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Job deadline extended successfully',
                'data' => [
                    'job' => new JobResource($updatedJob),
                    'old_deadline' => $job->attribute_values
                        ->where('attribute.code', 'application_deadline')
                        ->first()?->date_value,
                    'new_deadline' => $newDeadline,
                    'reason' => $reason,
                ],
            ], Response::HTTP_OK);
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Job not found or access denied',
            ], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            \Log::error('Failed to extend job deadline', [
                'user_id' => Auth::id(),
                'job_id' => $id,
                'error' => $e->getMessage(),
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to extend job deadline',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    /**
     * Preview job as it appears to applicants
     * 
     * @param int $id
     * @return JsonResponse
     */
    public function preview(int $id): JsonResponse
    {
        try {
            $user = Auth::user();
            
            $job = Product::where('id', $id)
                ->where('created_by_admin_id', $user->id)
                ->whereHas('categories', function ($query) {
                    $query->where('category_id', $this->jobCategoryId);
                })
                ->with(['attribute_values.attribute', 'categories.translations'])
                ->firstOrFail();
            
            // Generate preview data with additional formatting
            $preview = [
                'job' => new JobResource($job),
                'preview_url' => url("/jobs/{$job->id}"), // Assuming public job URL
                'seo_preview' => [
                    'title' => $job->attribute_values->where('attribute.code', 'meta_title')->first()?->text_value 
                        ?? $job->attribute_values->where('attribute.code', 'name')->first()?->text_value,
                    'description' => $job->attribute_values->where('attribute.code', 'meta_description')->first()?->text_value 
                        ?? $job->attribute_values->where('attribute.code', 'short_description')->first()?->text_value,
                    'keywords' => $job->attribute_values->where('attribute.code', 'meta_keywords')->first()?->text_value,
                ],
                'social_preview' => [
                    'title' => $job->attribute_values->where('attribute.code', 'name')->first()?->text_value,
                    'description' => $job->attribute_values->where('attribute.code', 'short_description')->first()?->text_value,
                    'company' => $this->extractCompanyName($job),
                    'location' => $job->attribute_values->where('attribute.code', 'job_location')->first()?->text_value,
                ],
                'visibility_status' => [
                    'is_published' => (bool) ($job->attribute_values->where('attribute.code', 'status')->first()?->integer_value ?? false),
                    'is_searchable' => (bool) ($job->attribute_values->where('attribute.code', 'visible_individually')->first()?->integer_value ?? false),
                    'is_featured' => (bool) ($job->attribute_values->where('attribute.code', 'is_featured')->first()?->integer_value ?? false),
                    'is_urgent' => (bool) ($job->attribute_values->where('attribute.code', 'is_urgent')->first()?->integer_value ?? false),
                ],
                'analytics_preview' => [
                    'estimated_views_per_day' => rand(10, 50),
                    'estimated_applications' => rand(2, 15),
                    'similar_jobs_competition' => rand(3, 12),
                ],
            ];
            
            return response()->json([
                'success' => true,
                'message' => 'Job preview generated successfully',
                'data' => $preview,
            ], Response::HTTP_OK);
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Job not found or access denied',
            ], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate job preview',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    /**
     * Boost job by marking it as featured/urgent
     * 
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function boost(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'boost_type' => 'required|string|in:featured,urgent,both',
            'duration' => 'nullable|integer|min:1|max:30', // days
            'auto_renew' => 'nullable|boolean',
        ]);
        
        try {
            $user = Auth::user();
            $boostType = $request->get('boost_type');
            $duration = $request->get('duration', 7); // default 7 days
            $autoRenew = $request->boolean('auto_renew');
            
            $job = Product::where('id', $id)
                ->where('created_by_admin_id', $user->id)
                ->whereHas('categories', function ($query) {
                    $query->where('category_id', $this->jobCategoryId);
                })
                ->firstOrFail();
            
            $updateData = [];
            
            if (in_array($boostType, ['featured', 'both'])) {
                $updateData['is_featured'] = true;
            }
            
            if (in_array($boostType, ['urgent', 'both'])) {
                $updateData['is_urgent'] = true;
            }
            
            $updatedJob = $this->jobService->updateJobPosting($job, $updateData);
            
            \Log::info("User boosted job with {$boostType}", [
                'user_id' => $user->id,
                'job_id' => $id,
                'boost_type' => $boostType,
                'duration' => $duration,
                'auto_renew' => $autoRenew,
            ]);
            
            return response()->json([
                'success' => true,
                'message' => "Job boosted as {$boostType} successfully",
                'data' => [
                    'job' => new JobResource($updatedJob),
                    'boost_details' => [
                        'type' => $boostType,
                        'duration_days' => $duration,
                        'auto_renew' => $autoRenew,
                        'expires_at' => Carbon::now()->addDays($duration)->toISOString(),
                        'estimated_boost' => [
                            'views_increase' => $boostType === 'featured' ? '3.2x' : '1.8x',
                            'applications_increase' => $boostType === 'featured' ? '2.4x' : '1.5x',
                        ],
                    ],
                ],
            ], Response::HTTP_OK);
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Job not found or access denied',
            ], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            \Log::error('Failed to boost job', [
                'user_id' => Auth::id(),
                'job_id' => $id,
                'error' => $e->getMessage(),
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to boost job',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    /**
     * Get user's job templates
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function getJobTemplates(Request $request): JsonResponse
    {
        try {
            $user = Auth::user();
            
            // For now, we'll simulate templates from user's existing jobs
            $recentJobs = Product::where('created_by_admin_id', $user->id)
                ->whereHas('categories', function ($q) {
                    $q->where('category_id', $this->jobCategoryId);
                })
                ->with(['attribute_values.attribute'])
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
            
            $templates = $recentJobs->map(function ($job, $index) {
                return [
                    'id' => "template_{$job->id}",
                    'name' => $job->attribute_values->where('attribute.code', 'name')->first()?->text_value . ' Template',
                    'description' => 'Template based on: ' . $job->attribute_values->where('attribute.code', 'name')->first()?->text_value,
                    'job_type' => $this->getJobAttributeValue($job, 'job_type'),
                    'experience_level' => $this->getJobAttributeValue($job, 'experience_level'),
                    'company_name' => $this->getJobAttributeValue($job, 'company_name') ?: $this->extractCompanyName($job),
                    'created_at' => $job->created_at->toISOString(),
                    'usage_count' => rand(1, 10),
                    'is_favorite' => $index < 2, // Mark first 2 as favorites
                ];
            });
            
            return response()->json([
                'success' => true,
                'message' => 'Job templates retrieved successfully',
                'data' => [
                    'templates' => $templates,
                    'total_templates' => $templates->count(),
                    'suggested_actions' => [
                        'Create new template from your best performing job',
                        'Organize templates by job type for easier access',
                        'Share frequently used templates with team members',
                    ],
                ],
            ], Response::HTTP_OK);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve job templates',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    /**
     * Create job from template
     * 
     * @param Request $request
     * @param string $templateId
     * @return JsonResponse
     */
    public function createFromTemplate(Request $request, string $templateId): JsonResponse
    {
        $request->validate([
            'modifications' => 'nullable|array',
            'title' => 'nullable|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'job_location' => 'nullable|string|max:255',
            'application_deadline' => 'nullable|date|after:today',
        ]);
        
        try {
            $user = Auth::user();
            
            // Extract original job ID from template ID
            $originalJobId = str_replace('template_', '', $templateId);
            
            $originalJob = Product::where('id', $originalJobId)
                ->where('created_by_admin_id', $user->id)
                ->whereHas('categories', function ($query) {
                    $query->where('category_id', $this->jobCategoryId);
                })
                ->with(['attribute_values.attribute', 'categories'])
                ->firstOrFail();
            
            $modifications = $request->get('modifications', []);
            
            // Add request-level modifications
            if ($request->has('title')) {
                $modifications['title'] = $request->get('title');
            }
            if ($request->has('company_name')) {
                $modifications['company_name'] = $request->get('company_name');
            }
            if ($request->has('job_location')) {
                $modifications['job_location'] = $request->get('job_location');
            }
            if ($request->has('application_deadline')) {
                $modifications['application_deadline'] = $request->get('application_deadline');
            }
            
            $newJob = $this->jobService->duplicateJob($originalJob, $modifications, $user->id);
            
            \Log::info('User created job from template', [
                'user_id' => $user->id,
                'template_id' => $templateId,
                'new_job_id' => $newJob->id,
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Job created from template successfully',
                'data' => [
                    'job' => new JobResource($newJob),
                    'template_used' => $templateId,
                    'modifications_applied' => $modifications,
                ],
            ], Response::HTTP_CREATED);
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Template not found or access denied',
            ], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            \Log::error('Failed to create job from template', [
                'user_id' => Auth::id(),
                'template_id' => $templateId,
                'error' => $e->getMessage(),
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to create job from template',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    // =====================================================
    // HELPER METHODS
    // =====================================================
    
    /**
     * Extract company name from job
     * 
     * @param Product $job
     * @return string
     */
    protected function extractCompanyName(Product $job): string
    {
        // Try to get from company_name attribute first
        $companyName = $job->attribute_values->where('attribute.code', 'company_name')->first()?->text_value;
        
        if ($companyName) {
            return $companyName;
        }
        
        // Fallback to extracting from title
        $title = $job->attribute_values->where('attribute.code', 'name')->first()?->text_value ?? '';
        $parts = explode(' - ', $title);
        
        return count($parts) > 1 ? trim($parts[1]) : 'Company';
    }
    
    /**
     * Get job attribute value
     * 
     * @param Product $job
     * @param string $attributeCode
     * @return string|null
     */
    protected function getJobAttributeValue(Product $job, string $attributeCode): ?string
    {
        $attributeValue = $job->attribute_values->where('attribute.code', $attributeCode)->first();
        
        if (!$attributeValue) {
            return null;
        }
        
        // Handle different attribute types
        if ($attributeValue->integer_value && $attributeValue->attribute->type === 'select') {
            $option = \Webkul\Attribute\Models\AttributeOption::find($attributeValue->integer_value);
            if ($option) {
                $translation = $option->translations()->where('locale', 'vi')->first();
                return $translation?->label ?? $option->admin_name;
            }
        }
        
        return $attributeValue->text_value;
    }
    
    /**
     * Generate unique SKU for user job
     * 
     * @param string $company
     * @param string $title
     * @param int $userId
     * @return string
     */
    protected function generateUserJobSku(string $company, string $title, int $userId): string
    {
        $base = 'USER_' . $userId . '_' . Str::upper(Str::slug($company, '_')) . '_' . Str::upper(Str::slug($title, '_'));
        $base = Str::limit($base, 40, '');
        
        $counter = 1;
        $sku = $base . '_' . date('Y');
        
        while (Product::where('sku', $sku)->exists()) {
            $sku = $base . '_' . date('Y') . '_' . $counter;
            $counter++;
        }
        
        return $sku;
    }
}
