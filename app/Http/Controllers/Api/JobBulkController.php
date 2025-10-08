<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CreateUserJobRequest;
use App\Http\Resources\JobResource;
use App\Services\JobService;
use Webkul\Product\Models\Product;
use Webkul\Category\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class JobBulkController extends Controller
{
    protected JobService $jobService;
    protected int $jobCategoryId;
    
    public function __construct(JobService $jobService)
    {
        $this->jobService = $jobService;
        
        // Get job category ID
        $jobCategory = Category::whereHas('translations', function ($query) {
            $query->where('slug', 'viec-lam');
        })->first();
        
        $this->jobCategoryId = $jobCategory ? $jobCategory->id : 102;
    }
    
    /**
     * Create multiple jobs at once
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function bulkCreate(Request $request): JsonResponse
    {
        $request->validate([
            'jobs' => 'required|array|min:1|max:20', // Max 20 jobs at once
            'jobs.*' => 'required|array',
        ]);
        
        try {
            $user = Auth::user();
            $jobsData = $request->get('jobs');
            
            // Validate each job individually
            $validationErrors = [];
            foreach ($jobsData as $index => $jobData) {
                $validator = Validator::make($jobData, $this->getJobValidationRules());
                if ($validator->fails()) {
                    $validationErrors["job_{$index}"] = $validator->errors();
                }
            }
            
            if (!empty($validationErrors)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed for one or more jobs',
                    'errors' => $validationErrors,
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }
            
            // Use JobService bulk create method
            $results = $this->jobService->bulkCreateJobs($jobsData, $user->id);
            
            $success = empty($results['errors']);
            $statusCode = $success ? Response::HTTP_CREATED : Response::HTTP_PARTIAL_CONTENT;
            
            \Log::info('User performed bulk job creation', [
                'user_id' => $user->id,
                'attempted' => count($jobsData),
                'created' => count($results['created']),
                'errors' => count($results['errors']),
            ]);
            
            return response()->json([
                'success' => $success,
                'message' => sprintf(
                    'Bulk creation completed. %d jobs created, %d errors',
                    count($results['created']),
                    count($results['errors'])
                ),
                'data' => [
                    'created_jobs' => JobResource::collection($results['created']),
                    'errors' => $results['errors'],
                ],
                'summary' => [
                    'total_attempted' => count($jobsData),
                    'successful' => count($results['created']),
                    'failed' => count($results['errors']),
                    'success_rate' => count($jobsData) > 0 ? round((count($results['created']) / count($jobsData)) * 100, 1) : 0,
                ],
            ], $statusCode);
            
        } catch (\Exception $e) {
            \Log::error('Bulk job creation failed', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Bulk job creation failed',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    /**
     * Update multiple jobs at once
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function bulkUpdate(Request $request): JsonResponse
    {
        $request->validate([
            'updates' => 'required|array|min:1|max:50', // Max 50 jobs at once
            'updates.*.id' => 'required|integer',
            'updates.*.data' => 'required|array',
        ]);
        
        try {
            $user = Auth::user();
            $updates = $request->get('updates');
            
            // Validate each update individually
            $validationErrors = [];
            foreach ($updates as $index => $update) {
                $validator = Validator::make($update['data'], $this->getJobUpdateValidationRules());
                if ($validator->fails()) {
                    $validationErrors["update_{$index}"] = $validator->errors();
                }
            }
            
            if (!empty($validationErrors)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed for one or more updates',
                    'errors' => $validationErrors,
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }
            
            // Use JobService bulk update method
            $results = $this->jobService->bulkUpdateJobs($updates, $user->id);
            
            $success = empty($results['errors']);
            $statusCode = $success ? Response::HTTP_OK : Response::HTTP_PARTIAL_CONTENT;
            
            \Log::info('User performed bulk job update', [
                'user_id' => $user->id,
                'attempted' => count($updates),
                'updated' => count($results['updated']),
                'errors' => count($results['errors']),
            ]);
            
            return response()->json([
                'success' => $success,
                'message' => sprintf(
                    'Bulk update completed. %d jobs updated, %d errors',
                    count($results['updated']),
                    count($results['errors'])
                ),
                'data' => [
                    'updated_jobs' => JobResource::collection($results['updated']),
                    'errors' => $results['errors'],
                ],
                'summary' => [
                    'total_attempted' => count($updates),
                    'successful' => count($results['updated']),
                    'failed' => count($results['errors']),
                    'success_rate' => count($updates) > 0 ? round((count($results['updated']) / count($updates)) * 100, 1) : 0,
                ],
            ], $statusCode);
            
        } catch (\Exception $e) {
            \Log::error('Bulk job update failed', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Bulk job update failed',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    /**
     * Delete multiple jobs at once
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function bulkDelete(Request $request): JsonResponse
    {
        $request->validate([
            'job_ids' => 'required|array|min:1|max:100', // Max 100 jobs at once
            'job_ids.*' => 'required|integer',
            'confirm' => 'required|boolean|accepted', // Require explicit confirmation for safety
        ]);
        
        try {
            $user = Auth::user();
            $jobIds = $request->get('job_ids');
            
            // Use JobService bulk delete method
            $results = $this->jobService->bulkDeleteJobs($jobIds, $user->id);
            
            $success = empty($results['errors']);
            $statusCode = $success ? Response::HTTP_OK : Response::HTTP_PARTIAL_CONTENT;
            
            \Log::info('User performed bulk job deletion', [
                'user_id' => $user->id,
                'attempted' => count($jobIds),
                'deleted' => count($results['deleted']),
                'errors' => count($results['errors']),
            ]);
            
            return response()->json([
                'success' => $success,
                'message' => sprintf(
                    'Bulk deletion completed. %d jobs deleted, %d errors',
                    count($results['deleted']),
                    count($results['errors'])
                ),
                'data' => [
                    'deleted_job_ids' => $results['deleted'],
                    'errors' => $results['errors'],
                ],
                'summary' => [
                    'total_attempted' => count($jobIds),
                    'successful' => count($results['deleted']),
                    'failed' => count($results['errors']),
                    'success_rate' => count($jobIds) > 0 ? round((count($results['deleted']) / count($jobIds)) * 100, 1) : 0,
                ],
            ], $statusCode);
            
        } catch (\Exception $e) {
            \Log::error('Bulk job deletion failed', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Bulk job deletion failed',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    /**
     * Toggle status of multiple jobs at once
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function bulkToggleStatus(Request $request): JsonResponse
    {
        $request->validate([
            'job_ids' => 'required|array|min:1|max:100', // Max 100 jobs at once
            'job_ids.*' => 'required|integer',
            'status' => 'required|boolean',
        ]);
        
        try {
            $user = Auth::user();
            $jobIds = $request->get('job_ids');
            $status = $request->boolean('status');
            
            // Use JobService bulk toggle status method
            $results = $this->jobService->bulkToggleStatus($jobIds, $status, $user->id);
            
            $success = empty($results['errors']);
            $statusCode = $success ? Response::HTTP_OK : Response::HTTP_PARTIAL_CONTENT;
            
            $action = $status ? 'activated' : 'deactivated';
            
            \Log::info("User performed bulk job status toggle to {$action}", [
                'user_id' => $user->id,
                'attempted' => count($jobIds),
                'updated' => count($results['updated']),
                'errors' => count($results['errors']),
                'new_status' => $status,
            ]);
            
            return response()->json([
                'success' => $success,
                'message' => sprintf(
                    'Bulk status update completed. %d jobs %s, %d errors',
                    count($results['updated']),
                    $action,
                    count($results['errors'])
                ),
                'data' => [
                    'updated_jobs' => $results['updated'],
                    'errors' => $results['errors'],
                ],
                'summary' => [
                    'total_attempted' => count($jobIds),
                    'successful' => count($results['updated']),
                    'failed' => count($results['errors']),
                    'new_status' => $status ? 'active' : 'inactive',
                    'success_rate' => count($jobIds) > 0 ? round((count($results['updated']) / count($jobIds)) * 100, 1) : 0,
                ],
            ], $statusCode);
            
        } catch (\Exception $e) {
            \Log::error('Bulk job status toggle failed', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Bulk job status toggle failed',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    /**
     * Duplicate multiple jobs at once
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function bulkDuplicate(Request $request): JsonResponse
    {
        $request->validate([
            'job_ids' => 'required|array|min:1|max:20', // Max 20 jobs at once for duplication
            'job_ids.*' => 'required|integer',
            'modifications' => 'nullable|array',
            'title_suffix' => 'nullable|string|max:50',
        ]);
        
        try {
            $user = Auth::user();
            $jobIds = $request->get('job_ids');
            $modifications = $request->get('modifications', []);
            $titleSuffix = $request->get('title_suffix', ' (Copy)');
            
            // Verify job ownership for all jobs
            $jobs = Product::whereIn('id', $jobIds)
                ->where('created_by_admin_id', $user->id)
                ->whereHas('categories', function ($query) {
                    $query->where('category_id', $this->jobCategoryId);
                })
                ->with(['attribute_values.attribute', 'categories'])
                ->get();
            
            if ($jobs->count() !== count($jobIds)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Some jobs not found or access denied',
                    'error' => 'One or more jobs do not exist or you do not have permission to duplicate them',
                ], Response::HTTP_NOT_FOUND);
            }
            
            $results = ['created' => [], 'errors' => []];
            
            DB::transaction(function () use ($jobs, $modifications, $titleSuffix, $user, &$results) {
                foreach ($jobs as $index => $job) {
                    try {
                        // Add title suffix to avoid duplicate titles
                        $jobModifications = array_merge($modifications, [
                            'title_suffix' => $titleSuffix
                        ]);
                        
                        $duplicatedJob = $this->jobService->duplicateJob($job, $jobModifications, $user->id);
                        $results['created'][] = $duplicatedJob;
                        
                    } catch (\Exception $e) {
                        $results['errors'][$index] = [
                            'original_job_id' => $job->id,
                            'error' => $e->getMessage(),
                        ];
                    }
                }
            });
            
            $success = empty($results['errors']);
            $statusCode = $success ? Response::HTTP_CREATED : Response::HTTP_PARTIAL_CONTENT;
            
            \Log::info('User performed bulk job duplication', [
                'user_id' => $user->id,
                'attempted' => count($jobIds),
                'created' => count($results['created']),
                'errors' => count($results['errors']),
            ]);
            
            return response()->json([
                'success' => $success,
                'message' => sprintf(
                    'Bulk duplication completed. %d jobs duplicated, %d errors',
                    count($results['created']),
                    count($results['errors'])
                ),
                'data' => [
                    'duplicated_jobs' => JobResource::collection($results['created']),
                    'errors' => $results['errors'],
                ],
                'summary' => [
                    'total_attempted' => count($jobIds),
                    'successful' => count($results['created']),
                    'failed' => count($results['errors']),
                    'success_rate' => count($jobIds) > 0 ? round((count($results['created']) / count($jobIds)) * 100, 1) : 0,
                ],
            ], $statusCode);
            
        } catch (\Exception $e) {
            \Log::error('Bulk job duplication failed', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Bulk job duplication failed',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    /**
     * Archive multiple jobs at once
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function bulkArchive(Request $request): JsonResponse
    {
        $request->validate([
            'job_ids' => 'required|array|min:1|max:100',
            'job_ids.*' => 'required|integer',
            'archive_type' => 'required|string|in:expired,inactive,manual',
        ]);
        
        try {
            $user = Auth::user();
            $jobIds = $request->get('job_ids');
            $archiveType = $request->get('archive_type');
            
            // Mark jobs as inactive (archived)
            $results = $this->jobService->bulkToggleStatus($jobIds, false, $user->id);
            
            $success = empty($results['errors']);
            
            \Log::info("User archived jobs in bulk ({$archiveType})", [
                'user_id' => $user->id,
                'attempted' => count($jobIds),
                'archived' => count($results['updated']),
                'errors' => count($results['errors']),
                'archive_type' => $archiveType,
            ]);
            
            return response()->json([
                'success' => $success,
                'message' => sprintf(
                    'Bulk archiving completed. %d jobs archived, %d errors',
                    count($results['updated']),
                    count($results['errors'])
                ),
                'data' => [
                    'archived_jobs' => $results['updated'],
                    'errors' => $results['errors'],
                    'archive_type' => $archiveType,
                ],
                'summary' => [
                    'total_attempted' => count($jobIds),
                    'successful' => count($results['updated']),
                    'failed' => count($results['errors']),
                ],
            ], $success ? Response::HTTP_OK : Response::HTTP_PARTIAL_CONTENT);
            
        } catch (\Exception $e) {
            \Log::error('Bulk job archiving failed', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Bulk job archiving failed',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    /**
     * Get bulk operation status/progress
     * 
     * @param Request $request
     * @param string $operationId
     * @return JsonResponse
     */
    public function getBulkOperationStatus(Request $request, string $operationId): JsonResponse
    {
        try {
            // This would typically check a job queue or cache for operation status
            // For now, we'll return a simulated response
            $status = [
                'operation_id' => $operationId,
                'status' => 'completed', // pending, processing, completed, failed
                'progress' => 100,
                'total_items' => 10,
                'processed_items' => 10,
                'successful_items' => 8,
                'failed_items' => 2,
                'started_at' => now()->subMinutes(5)->toISOString(),
                'completed_at' => now()->subMinute()->toISOString(),
                'errors' => [
                    'Job ID 123: Title is required',
                    'Job ID 456: Invalid job type',
                ],
            ];
            
            return response()->json([
                'success' => true,
                'message' => 'Bulk operation status retrieved successfully',
                'data' => $status,
            ], Response::HTTP_OK);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve bulk operation status',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    // =====================================================
    // VALIDATION METHODS
    // =====================================================
    
    /**
     * Get job validation rules for bulk creation
     * 
     * @return array
     */
    protected function getJobValidationRules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:100',
            'short_description' => 'nullable|string|max:500',
            'job_type' => 'required|string|in:full-time,part-time,contract,freelance,internship',
            'experience_level' => 'required|string|in:entry,junior,mid,senior,lead,executive',
            'salary_range' => 'nullable|string',
            'job_location' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'company_size' => 'nullable|string|in:1-10,11-50,51-200,201-500,500+',
            'company_website' => 'nullable|url|max:255',
            'required_skills' => 'nullable|array',
            'required_skills.*' => 'string|max:100',
            'education_level' => 'nullable|string|in:high-school,bachelor,master,phd,none',
            'english_level' => 'nullable|string|in:basic,intermediate,advanced,native',
            'job_benefits' => 'nullable|array',
            'job_benefits.*' => 'string|max:100',
            'application_deadline' => 'nullable|date|after:today',
            'contact_email' => 'required|email|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'is_urgent' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
            'categories' => 'nullable|array',
            'categories.*' => 'integer|exists:categories,id',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:255',
        ];
    }
    
    /**
     * Get job validation rules for bulk updates (all optional)
     * 
     * @return array
     */
    protected function getJobUpdateValidationRules(): array
    {
        return [
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string|min:100',
            'short_description' => 'nullable|string|max:500',
            'job_type' => 'nullable|string|in:full-time,part-time,contract,freelance,internship',
            'experience_level' => 'nullable|string|in:entry,junior,mid,senior,lead,executive',
            'salary_range' => 'nullable|string',
            'job_location' => 'nullable|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'company_size' => 'nullable|string|in:1-10,11-50,51-200,201-500,500+',
            'company_website' => 'nullable|url|max:255',
            'required_skills' => 'nullable|array',
            'required_skills.*' => 'string|max:100',
            'education_level' => 'nullable|string|in:high-school,bachelor,master,phd,none',
            'english_level' => 'nullable|string|in:basic,intermediate,advanced,native',
            'job_benefits' => 'nullable|array',
            'job_benefits.*' => 'string|max:100',
            'application_deadline' => 'nullable|date|after:today',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'is_urgent' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
            'status' => 'nullable|boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:255',
        ];
    }
}