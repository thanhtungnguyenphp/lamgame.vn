<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use App\Services\JobApplicationService;
use App\Services\FileUploadService;
use App\Http\Requests\Api\JobApplicationRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Webkul\Product\Models\Product;

class JobApplicationController extends Controller
{
    protected JobApplicationService $jobApplicationService;
    protected FileUploadService $fileUploadService;

    public function __construct(
        JobApplicationService $jobApplicationService,
        FileUploadService $fileUploadService
    ) {
        $this->jobApplicationService = $jobApplicationService;
        $this->fileUploadService = $fileUploadService;
    }

    /**
     * Submit job application
     * 
     * @param int $jobId
     * @param JobApplicationRequest $request
     * @return JsonResponse
     */
    public function apply(int $jobId, JobApplicationRequest $request): JsonResponse
    {
        try {
            // Verify job exists
            $job = Product::findOrFail($jobId);

            // Check if user already applied for this job
            $existingApplication = $this->jobApplicationService->checkExistingApplication(
                $jobId,
                $request->input('email'),
                auth('sanctum')->user()?->id
            );

            if ($existingApplication) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn đã ứng tuyển vị trí này rồi',
                    'error' => 'DUPLICATE_APPLICATION',
                    'existing_application' => [
                        'applied_at' => $existingApplication->applied_at->format('d/m/Y H:i'),
                        'status' => $existingApplication->status
                    ]
                ], Response::HTTP_CONFLICT);
            }

            // Process file upload if provided
            $cvPath = null;
            if ($request->hasFile('cv')) {
                $cvPath = $this->fileUploadService->uploadCV($request->file('cv'));
            }

            // Create job application
            $applicationData = [
                'job_id' => $jobId,
                'applicant_user_id' => auth('sanctum')->user()?->id,
                'applicant_name' => $request->input('full_name'),
                'applicant_email' => $request->input('email'),
                'applicant_phone' => $request->input('phone'),
                'cover_letter' => $request->input('cover_letter'),
                'resume_file_path' => $cvPath,
                'additional_info' => [
                    'experience_level' => $request->input('experience'),
                    'applied_via' => 'web_form',
                    'user_agent' => $request->userAgent(),
                    'ip_address' => $request->ip(),
                ],
                'status' => 'pending'
            ];

            $application = $this->jobApplicationService->createApplication($applicationData);

            // Send notifications (queued)
            $this->jobApplicationService->sendNotifications($application, $job);

            return response()->json([
                'success' => true,
                'message' => 'Hồ sơ ứng tuyển đã được gửi thành công!',
                'data' => [
                    'application_id' => $application->id,
                    'application_code' => $application->application_code ?? null,
                    'status' => $application->status,
                    'applied_at' => $application->applied_at->format('d/m/Y H:i'),
                    'job_title' => $job->name,
                    'message' => 'Chúng tôi sẽ liên hệ với bạn trong thời gian sớm nhất.'
                ]
            ], Response::HTTP_CREATED);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy công việc này',
                'error' => 'JOB_NOT_FOUND'
            ], Response::HTTP_NOT_FOUND);

        } catch (\App\Exceptions\FileUploadException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi upload CV: ' . $e->getMessage(),
                'error' => 'FILE_UPLOAD_ERROR'
            ], Response::HTTP_BAD_REQUEST);

        } catch (\Exception $e) {
            Log::error('Job application failed', [
                'job_id' => $jobId,
                'email' => $request->input('email'),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Đã xảy ra lỗi khi gửi hồ sơ. Vui lòng thử lại sau.',
                'error' => config('app.debug') ? $e->getMessage() : 'INTERNAL_ERROR'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Get user's job applications
     * 
     * @return JsonResponse
     */
    public function getUserApplications(): JsonResponse
    {
        try {
            $user = auth('sanctum')->user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized',
                    'error' => 'AUTHENTICATION_REQUIRED'
                ], Response::HTTP_UNAUTHORIZED);
            }

            $applications = JobApplication::where('applicant_user_id', $user->id)
                ->with(['job' => function($query) {
                    $query->select('id', 'sku');
                }])
                ->orderBy('applied_at', 'desc')
                ->paginate(10);

            return response()->json([
                'success' => true,
                'message' => 'Lấy danh sách ứng tuyển thành công',
                'data' => $applications
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            Log::error('Failed to get user applications', [
                'user_id' => auth('sanctum')->id(),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Không thể lấy danh sách ứng tuyển',
                'error' => config('app.debug') ? $e->getMessage() : 'INTERNAL_ERROR'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Get application status
     * 
     * @param int $applicationId
     * @return JsonResponse
     */
    public function getApplicationStatus(int $applicationId): JsonResponse
    {
        try {
            $user = auth('sanctum')->user();
            
            $query = JobApplication::query();
            
            if ($user) {
                // If authenticated, only show their applications
                $query->where('applicant_user_id', $user->id);
            } else {
                // If guest, they need to provide email for verification
                $email = request('email');
                if (!$email) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Email required for guest users',
                        'error' => 'EMAIL_REQUIRED'
                    ], Response::HTTP_BAD_REQUEST);
                }
                $query->where('applicant_email', $email);
            }

            $application = $query->with(['job' => function($q) {
                $q->select('id', 'sku');
            }])->findOrFail($applicationId);

            return response()->json([
                'success' => true,
                'message' => 'Lấy trạng thái ứng tuyển thành công',
                'data' => [
                    'id' => $application->id,
                    'status' => $application->status,
                    'applied_at' => $application->applied_at->format('d/m/Y H:i'),
                    'job_title' => $application->job->name ?? 'N/A',
                    'employer_notes' => $application->employer_notes,
                ]
            ], Response::HTTP_OK);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy đơn ứng tuyển',
                'error' => 'APPLICATION_NOT_FOUND'
            ], Response::HTTP_NOT_FOUND);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể lấy trạng thái ứng tuyển',
                'error' => config('app.debug') ? $e->getMessage() : 'INTERNAL_ERROR'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}