<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CreateJobRequest;
use App\Http\Requests\Api\UpdateJobRequest;
use App\Http\Resources\JobResource;
use App\Http\Resources\CategoryResource;
use App\Services\JobService;
use App\Services\JobFilterService;
use Webkul\Product\Models\Product;
use Webkul\Category\Models\Category;
use Webkul\Attribute\Models\Attribute;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class JobController extends Controller
{
    protected JobService $jobService;
    protected JobFilterService $jobFilterService;

    public function __construct(JobService $jobService, JobFilterService $jobFilterService)
    {
        $this->jobService = $jobService;
        $this->jobFilterService = $jobFilterService;
    }

    /**
     * Display a listing of job postings
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $filters = [
                'search' => $request->get('search'),
                'job_type' => $request->get('job_type'),
                'location' => $request->get('location'),
                'company' => $request->get('company'),
                'salary_min' => $request->get('salary_min'),
                'salary_max' => $request->get('salary_max'),
                'is_urgent' => $request->boolean('is_urgent'),
                'is_featured' => $request->boolean('is_featured'),
                'order_by' => $request->get('order_by', 'created_at'),
                'order_direction' => $request->get('order_direction', 'desc'),
            ];

            $perPage = $request->get('per_page', 15);
            $perPage = min($perPage, 50); // Max 50 items per page

            $jobs = $this->jobService->getJobPostings($filters, $perPage);

            return response()->json([
                'success' => true,
                'message' => 'Job postings retrieved successfully',
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
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve job postings',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Store a newly created job posting
     * 
     * @param CreateJobRequest $request
     * @return JsonResponse
     */
    public function store(CreateJobRequest $request): JsonResponse
    {
        try {
            $validatedData = $request->validated();
            
            $job = $this->jobService->createJobPosting($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Job posting created successfully',
                'data' => new JobResource($job),
            ], Response::HTTP_CREATED);

        } catch (\Exception $e) {
            \Log::error('Job creation failed', [
                'error' => $e->getMessage(),
                'data' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to create job posting',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified job posting
     * 
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        try {
            $job = Product::with(['attribute_values.attribute', 'categories.translations'])
                ->whereHas('categories', function ($query) {
                    $query->whereHas('translations', function ($subQuery) {
                        $subQuery->where('slug', 'viec-lam');
                    });
                })
                ->findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Job posting retrieved successfully',
                'data' => new JobResource($job),
            ], Response::HTTP_OK);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Job posting not found',
                'error' => 'The specified job posting does not exist',
            ], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve job posting',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update the specified job posting
     * 
     * @param UpdateJobRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(UpdateJobRequest $request, int $id): JsonResponse
    {
        try {
            $job = Product::with(['attribute_values.attribute', 'categories.translations'])
                ->whereHas('categories', function ($query) {
                    $query->whereHas('translations', function ($subQuery) {
                        $subQuery->where('slug', 'viec-lam');
                    });
                })
                ->findOrFail($id);

            $validatedData = $request->validated();
            
            $updatedJob = $this->jobService->updateJobPosting($job, $validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Job posting updated successfully',
                'data' => new JobResource($updatedJob),
            ], Response::HTTP_OK);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Job posting not found',
                'error' => 'The specified job posting does not exist',
            ], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            \Log::error('Job update failed', [
                'job_id' => $id,
                'error' => $e->getMessage(),
                'data' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update job posting',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified job posting
     * 
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $job = Product::whereHas('categories', function ($query) {
                $query->whereHas('translations', function ($subQuery) {
                    $subQuery->where('slug', 'viec-lam');
                });
            })->findOrFail($id);

            // Soft delete hoặc hard delete
            $job->delete();

            return response()->json([
                'success' => true,
                'message' => 'Job posting deleted successfully',
            ], Response::HTTP_OK);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Job posting not found',
                'error' => 'The specified job posting does not exist',
            ], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            \Log::error('Job deletion failed', [
                'job_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete job posting',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Get available job categories
     * 
     * @return JsonResponse
     */
    public function getCategories(): JsonResponse
    {
        try {
            $categories = $this->jobFilterService->getJobCategories();

            return response()->json([
                'success' => true,
                'message' => 'Job categories retrieved successfully',
                'data' => $categories,
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve job categories',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Get available job attributes and their options
     * 
     * @return JsonResponse
     */
    public function getAttributes(): JsonResponse
    {
        try {
            $attributes = $this->jobFilterService->getJobAttributesForForm();

            return response()->json([
                'success' => true,
                'message' => 'Job attributes retrieved successfully',
                'data' => $attributes,
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve job attributes',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Bulk create job postings
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function bulkStore(Request $request): JsonResponse
    {
        $request->validate([
            'jobs' => 'required|array|min:1|max:10', // Max 10 jobs at once
            'jobs.*' => 'required|array',
        ]);

        try {
            $jobs = [];
            $errors = [];

            foreach ($request->jobs as $index => $jobData) {
                try {
                    // Validate individual job data
                    $validator = validator($jobData, (new CreateJobRequest())->rules());
                    
                    if ($validator->fails()) {
                        $errors[$index] = $validator->errors();
                        continue;
                    }

                    $job = $this->jobService->createJobPosting($jobData);
                    $jobs[] = new JobResource($job);

                } catch (\Exception $e) {
                    $errors[$index] = ['error' => $e->getMessage()];
                }
            }

            return response()->json([
                'success' => empty($errors),
                'message' => sprintf('Bulk creation completed. %d jobs created, %d errors', count($jobs), count($errors)),
                'data' => [
                    'created_jobs' => $jobs,
                    'errors' => $errors,
                ],
                'summary' => [
                    'total_attempted' => count($request->jobs),
                    'successful' => count($jobs),
                    'failed' => count($errors),
                ],
            ], empty($errors) ? Response::HTTP_CREATED : Response::HTTP_PARTIAL_CONTENT);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Bulk job creation failed',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Publish job posting
     * 
     * @param int $id
     * @return JsonResponse
     */
    public function publish(int $id): JsonResponse
    {
        return $this->toggleStatus($id, true, 'published');
    }

    /**
     * Unpublish job posting
     * 
     * @param int $id
     * @return JsonResponse
     */
    public function unpublish(int $id): JsonResponse
    {
        return $this->toggleStatus($id, false, 'unpublished');
    }

    /**
     * Toggle job status
     * 
     * @param int $id
     * @param bool $status
     * @param string $action
     * @return JsonResponse
     */
    protected function toggleStatus(int $id, bool $status, string $action): JsonResponse
    {
        try {
            $job = Product::whereHas('categories', function ($query) {
                $query->whereHas('translations', function ($subQuery) {
                    $subQuery->where('slug', 'viec-lam');
                });
            })->findOrFail($id);

            $updatedJob = $this->jobService->updateJobPosting($job, ['status' => $status]);

            return response()->json([
                'success' => true,
                'message' => "Job posting {$action} successfully",
                'data' => new JobResource($updatedJob),
            ], Response::HTTP_OK);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Job posting not found',
                'error' => 'The specified job posting does not exist',
            ], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => "Failed to {$action} job posting",
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}