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
