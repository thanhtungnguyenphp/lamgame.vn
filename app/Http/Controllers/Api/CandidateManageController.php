<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\JobApplicationResource;
use App\Models\JobApplication;
use App\Models\JobPosting;
use App\Services\JobPostingApplicationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CandidateManageController extends Controller
{
    public function __construct(private JobPostingApplicationService $service) {}

    /**
     * Danh sách ứng viên (tất cả jobs của admin, hoặc theo job cụ thể)
     */
    public function list(Request $request): JsonResponse
    {
        $admin = $request->auth_admin;
        $jobIds = JobPosting::where('created_by', $admin->id)->pluck('id');

        $query = JobApplication::whereIn('job_posting_id', $jobIds)
            ->with('jobPosting:id,title,slug,company_name');

        if ($jobPostingId = $request->input('job_posting_id')) {
            $query->where('job_posting_id', $jobPostingId);
        }
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }
        if ($search = $request->input('search')) {
            $search = str_replace(['%', '_'], ['\%', '\_'], $search);
            $query->where(function ($q) use ($search) {
                $q->where('applicant_name', 'like', "%{$search}%")
                  ->orWhere('applicant_email', 'like', "%{$search}%");
            });
        }

        $applications = $query->orderByDesc('applied_at')
            ->paginate($request->integer('per_page', 15));

        return response()->json([
            'status' => 'success',
            'data'   => JobApplicationResource::collection($applications),
            'meta'   => [
                'current_page' => $applications->currentPage(),
                'last_page'    => $applications->lastPage(),
                'per_page'     => $applications->perPage(),
                'total'        => $applications->total(),
            ],
        ]);
    }

    /**
     * Chi tiết 1 đơn ứng tuyển
     */
    public function detail(Request $request, int $id): JsonResponse
    {
        $admin = $request->auth_admin;
        $jobIds = JobPosting::where('created_by', $admin->id)->pluck('id');

        $application = JobApplication::whereIn('job_posting_id', $jobIds)
            ->with('jobPosting:id,title,slug,company_name')
            ->find($id);

        if (!$application) {
            return response()->json(['status' => 'error', 'message' => 'Không tìm thấy đơn ứng tuyển.'], 404);
        }

        return response()->json([
            'status' => 'success',
            'data'   => new JobApplicationResource($application),
        ]);
    }

    /**
     * Cập nhật trạng thái đơn ứng tuyển
     */
    public function updateStatus(Request $request, int $id): JsonResponse
    {
        $admin = $request->auth_admin;
        $jobIds = JobPosting::where('created_by', $admin->id)->pluck('id');

        $application = JobApplication::whereIn('job_posting_id', $jobIds)->find($id);
        if (!$application) {
            return response()->json(['status' => 'error', 'message' => 'Không tìm thấy đơn ứng tuyển.'], 404);
        }

        $request->validate([
            'status' => 'required|in:pending,reviewed,shortlisted,accepted,rejected',
            'notes'  => 'nullable|string|max:2000',
        ]);

        $application = $this->service->updateStatus(
            $application->id,
            $request->input('status'),
            $request->input('notes')
        );

        return response()->json([
            'status'  => 'success',
            'message' => 'Đã cập nhật trạng thái.',
            'data'    => new JobApplicationResource($application->load('jobPosting:id,title,slug,company_name')),
        ]);
    }

    /**
     * Thống kê ứng viên theo job
     */
    public function statistics(Request $request): JsonResponse
    {
        $admin = $request->auth_admin;
        $jobIds = JobPosting::where('created_by', $admin->id)->pluck('id');

        if ($jobPostingId = $request->input('job_posting_id')) {
            if (!$jobIds->contains($jobPostingId)) {
                return response()->json(['status' => 'error', 'message' => 'Không có quyền.'], 403);
            }
            $stats = $this->service->getStats($jobPostingId);
        } else {
            // Tổng hợp tất cả jobs
            $counts = JobApplication::whereIn('job_posting_id', $jobIds)
                ->selectRaw('status, COUNT(*) as count')
                ->groupBy('status')
                ->pluck('count', 'status')
                ->toArray();

            $stats = [
                'total'       => array_sum($counts),
                'pending'     => $counts['pending'] ?? 0,
                'reviewed'    => $counts['reviewed'] ?? 0,
                'shortlisted' => $counts['shortlisted'] ?? 0,
                'accepted'    => $counts['accepted'] ?? 0,
                'rejected'    => $counts['rejected'] ?? 0,
            ];
        }

        return response()->json(['status' => 'success', 'data' => $stats]);
    }

    /**
     * Xóa đơn ứng tuyển
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        $admin = $request->auth_admin;
        $jobIds = JobPosting::where('created_by', $admin->id)->pluck('id');

        $application = JobApplication::whereIn('job_posting_id', $jobIds)->find($id);
        if (!$application) {
            return response()->json(['status' => 'error', 'message' => 'Không tìm thấy đơn ứng tuyển.'], 404);
        }

        $application->delete();

        return response()->json(['status' => 'success', 'message' => 'Đã xóa đơn ứng tuyển.']);
    }
}
