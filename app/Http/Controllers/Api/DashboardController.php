<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use App\Http\Resources\JobResource;
use Webkul\Product\Models\Product;
use Webkul\Category\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Get dashboard data for authenticated user
     * 
     * This endpoint returns:
     * - 5 newest jobs posted by the authenticated user (employer)
     * - 5 most recent applicants for these jobs
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated',
                ], Response::HTTP_UNAUTHORIZED);
            }

            // Get the job category ID (Việc Làm)
            $jobCategory = Category::whereHas('translations', function ($query) {
                $query->where('slug', 'viec-lam');
            })->first();

            if (!$jobCategory) {
                return response()->json([
                    'success' => false,
                    'message' => 'Job category not found',
                ], Response::HTTP_NOT_FOUND);
            }

            // Get 5 newest jobs posted by this user
            // For now, we'll get all jobs and later add user association logic
            $recentJobs = Product::whereHas('categories', function ($query) use ($jobCategory) {
                $query->where('category_id', $jobCategory->id);
            })
                ->with(['attribute_values.attribute', 'categories.translations'])
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            // Extract job IDs for getting applications
            $jobIds = $recentJobs->pluck('id')->toArray();

            // Get 5 most recent applicants for these jobs
            $recentApplications = JobApplication::whereIn('job_id', $jobIds)
                ->with(['job', 'applicant'])
                ->orderBy('applied_at', 'desc')
                ->limit(5)
                ->get();

            // Format the data
            $dashboardData = [
                'recent_jobs' => JobResource::collection($recentJobs),
                'recent_applications' => $recentApplications->map(function ($application) {
                    return [
                        'id' => $application->id,
                        'job_id' => $application->job_id,
                        'job_title' => $this->getJobTitle($application->job),
                        'applicant_name' => $application->applicant_name,
                        'applicant_email' => $application->applicant_email,
                        'status' => $application->status,
                        'applied_at' => $application->applied_at->format('Y-m-d H:i:s'),
                        'applied_at_human' => $application->applied_at->diffForHumans(),
                    ];
                }),
                'statistics' => [
                    'total_jobs' => count($recentJobs),
                    'total_applications' => count($recentApplications),
                    'pending_applications' => $recentApplications->where('status', 'pending')->count(),
                    'jobs_with_applications' => JobApplication::whereIn('job_id', $jobIds)->distinct('job_id')->count(),
                ],
            ];

            return response()->json([
                'success' => true,
                'message' => 'Dashboard data retrieved successfully',
                'data' => $dashboardData,
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            \Log::error('Dashboard API failed', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve dashboard data',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Get detailed applications for a specific job
     * 
     * @param Request $request
     * @param int $jobId
     * @return JsonResponse
     */
    public function jobApplications(Request $request, int $jobId): JsonResponse
    {
        try {
            $user = Auth::user();
            
            // Verify the job exists and belongs to user (for future user association)
            $job = Product::whereHas('categories', function ($query) {
                $query->whereHas('translations', function ($subQuery) {
                    $subQuery->where('slug', 'viec-lam');
                });
            })->findOrFail($jobId);

            // Get applications for this job
            $applications = JobApplication::forJob($jobId)
                ->with(['applicant'])
                ->orderBy('applied_at', 'desc')
                ->get();

            $formattedApplications = $applications->map(function ($application) {
                return [
                    'id' => $application->id,
                    'applicant_name' => $application->applicant_name,
                    'applicant_email' => $application->applicant_email,
                    'applicant_phone' => $application->applicant_phone,
                    'cover_letter' => $application->cover_letter,
                    'resume_file_path' => $application->resume_file_path,
                    'status' => $application->status,
                    'employer_notes' => $application->employer_notes,
                    'applied_at' => $application->applied_at->format('Y-m-d H:i:s'),
                    'applied_at_human' => $application->applied_at->diffForHumans(),
                    'additional_info' => $application->additional_info,
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'Job applications retrieved successfully',
                'data' => [
                    'job' => new JobResource($job),
                    'applications' => $formattedApplications,
                    'statistics' => [
                        'total_applications' => $applications->count(),
                        'pending' => $applications->where('status', 'pending')->count(),
                        'reviewed' => $applications->where('status', 'reviewed')->count(),
                        'shortlisted' => $applications->where('status', 'shortlisted')->count(),
                        'accepted' => $applications->where('status', 'accepted')->count(),
                        'rejected' => $applications->where('status', 'rejected')->count(),
                    ],
                ],
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve job applications',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update application status (approve, reject, etc.)
     * 
     * @param Request $request
     * @param int $applicationId
     * @return JsonResponse
     */
    public function updateApplicationStatus(Request $request, int $applicationId): JsonResponse
    {
        $request->validate([
            'status' => 'required|in:pending,reviewed,shortlisted,rejected,accepted',
            'employer_notes' => 'nullable|string|max:1000',
        ]);

        try {
            $application = JobApplication::findOrFail($applicationId);
            
            $application->update([
                'status' => $request->status,
                'employer_notes' => $request->employer_notes,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Application status updated successfully',
                'data' => [
                    'id' => $application->id,
                    'status' => $application->status,
                    'employer_notes' => $application->employer_notes,
                    'updated_at' => $application->updated_at->format('Y-m-d H:i:s'),
                ],
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update application status',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Get job title from product attributes
     * 
     * @param Product|null $job
     * @return string
     */
    private function getJobTitle(?Product $job): string
    {
        if (!$job) {
            return 'Unknown Job';
        }

        // Try to get job title from 'name' attribute
        $nameAttribute = $job->attribute_values
            ->whereIn('attribute.code', ['name', 'title'])
            ->first();

        return $nameAttribute ? $nameAttribute->text_value : 'Job #' . $job->id;
    }
}
