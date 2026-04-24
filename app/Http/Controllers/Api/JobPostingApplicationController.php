<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\JobApplicationRequest;
use App\Models\JobApplication;
use App\Models\JobPosting;
use App\Services\JobPostingApplicationService;
use App\Services\FileUploadService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class JobPostingApplicationController extends Controller
{
    public function __construct(
        private JobPostingApplicationService $applicationService,
        private FileUploadService $fileUploadService,
    ) {}

    public function apply(int $jobPostingId, JobApplicationRequest $request): JsonResponse
    {
        $job = JobPosting::where('id', $jobPostingId)->where('status', 'active')->first();
        if (!$job) {
            return response()->json(['message' => 'Tin tuyển dụng không tồn tại hoặc đã đóng.'], 404);
        }

        if ($job->isExpired()) {
            return response()->json(['message' => 'Tin tuyển dụng đã hết hạn.'], 422);
        }

        $user = auth('sanctum')->user();
        $email = $request->input('applicant_email');

        if ($this->applicationService->checkExisting($jobPostingId, $email, $user?->id)) {
            return response()->json(['message' => 'Bạn đã ứng tuyển vị trí này rồi.'], 422);
        }

        $resumePath = null;
        if ($request->hasFile('resume')) {
            try {
                $resumePath = $this->fileUploadService->upload($request->file('resume'), 'resumes');
            } catch (\Exception $e) {
                return response()->json(['message' => 'Lỗi upload CV: ' . $e->getMessage()], 422);
            }
        }

        $application = $this->applicationService->create([
            'job_posting_id'    => $jobPostingId,
            'applicant_user_id' => $user?->id,
            'applicant_name'    => $request->input('applicant_name'),
            'applicant_email'   => $email,
            'applicant_phone'   => $request->input('applicant_phone'),
            'cover_letter'      => $request->input('cover_letter'),
            'resume_file_path'  => $resumePath,
            'additional_info'   => $request->input('additional_info'),
        ]);

        $this->applicationService->sendNotifications($application);

        return response()->json([
            'message'          => 'Ứng tuyển thành công! Chúng tôi sẽ liên hệ bạn sớm.',
            'application_code' => $application->application_code,
        ], 201);
    }

    public function getUserApplications(Request $request): JsonResponse
    {
        $user = auth('sanctum')->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $applications = JobApplication::where('applicant_user_id', $user->id)
            ->with('jobPosting:id,title,slug,company_name,location,status')
            ->orderByDesc('applied_at')
            ->paginate(10);

        return response()->json($applications);
    }

    public function getApplicationStatus(int $applicationId): JsonResponse
    {
        $user = auth('sanctum')->user();

        $query = JobApplication::where('id', $applicationId);
        if ($user) {
            $query->where('applicant_user_id', $user->id);
        } else {
            $email = request('email');
            if (!$email) {
                return response()->json(['message' => 'Cần email để tra cứu.'], 422);
            }
            $query->where('applicant_email', $email);
        }

        $application = $query->with('jobPosting:id,title,slug')->first();
        if (!$application) {
            return response()->json(['message' => 'Không tìm thấy đơn ứng tuyển.'], 404);
        }

        return response()->json([
            'application_code' => $application->application_code,
            'status'           => $application->status,
            'applied_at'       => $application->applied_at,
            'job'              => $application->jobPosting ? [
                'title' => $application->jobPosting->title,
                'slug'  => $application->jobPosting->slug,
            ] : null,
        ]);
    }
}
