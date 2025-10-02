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
use Illuminate\Support\Str;

class UserJobController extends Controller
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
     * Get authenticated user's jobs
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $user = Auth::user();
            
            $query = Product::where('created_by_admin_id', $user->id)
                ->whereHas('categories', function ($q) {
                    $q->where('category_id', $this->jobCategoryId);
                })
                ->with(['attribute_values.attribute', 'categories.translations']);

            // Apply filters
            if ($search = $request->get('search')) {
                $query->whereHas('attribute_values', function ($q) use ($search) {
                    $q->join('attributes', 'product_attribute_values.attribute_id', '=', 'attributes.id')
                      ->whereIn('attributes.code', ['name', 'description'])
                      ->where('text_value', 'LIKE', '%' . $search . '%');
                });
            }

            if ($status = $request->get('status')) {
                $query->whereHas('attribute_values', function ($q) use ($status) {
                    $q->join('attributes', 'product_attribute_values.attribute_id', '=', 'attributes.id')
                      ->where('attributes.code', 'status')
                      ->where('integer_value', $status === 'active' ? 1 : 0);
                });
            }

            // Sorting
            $orderBy = $request->get('sort', 'created_at');
            $orderDirection = $request->get('direction', 'desc');
            $query->orderBy($orderBy, $orderDirection);

            // Pagination
            $perPage = min($request->get('per_page', 15), 50);
            $jobs = $query->paginate($perPage);

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
