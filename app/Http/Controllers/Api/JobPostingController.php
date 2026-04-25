<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreJobPostingRequest;
use App\Http\Requests\UpdateJobPostingRequest;
use App\Http\Resources\JobPostingResource;
use App\Models\JobPosting;
use App\Services\JobPostingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class JobPostingController extends Controller
{
    public function __construct(private JobPostingService $service) {}

    public function index(Request $request): AnonymousResourceCollection
    {
        $jobs = $this->service->list(
            $request->only(['search', 'job_type', 'location', 'experience_level', 'is_featured', 'is_remote', 'sort_by', 'sort_dir', 'status']),
            $request->integer('per_page', 15)
        );

        return JobPostingResource::collection($jobs);
    }

    public function store(StoreJobPostingRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['created_by'] = auth('admin')->id();
        $job = $this->service->create($data);

        return (new JobPostingResource($job))
            ->response()
            ->setStatusCode(201);
    }

    public function show(int $id): JobPostingResource|JsonResponse
    {
        $job = $this->service->find($id);
        if (!$job) {
            return response()->json(['message' => 'Không tìm thấy tin tuyển dụng.'], 404);
        }
        $job->incrementViews();
        return new JobPostingResource($job);
    }

    public function showBySlug(string $slug): JobPostingResource|JsonResponse
    {
        $job = $this->service->findBySlug($slug);
        if (!$job) {
            return response()->json(['message' => 'Không tìm thấy tin tuyển dụng.'], 404);
        }
        $job->incrementViews();
        return new JobPostingResource($job);
    }

    public function update(UpdateJobPostingRequest $request, int $id): JobPostingResource|JsonResponse
    {
        $job = JobPosting::find($id);
        if (!$job) {
            return response()->json(['message' => 'Không tìm thấy tin tuyển dụng.'], 404);
        }

        $job = $this->service->update($job, $request->validated());
        return new JobPostingResource($job);
    }

    public function destroy(int $id): JsonResponse
    {
        $job = JobPosting::find($id);
        if (!$job) {
            return response()->json(['message' => 'Không tìm thấy tin tuyển dụng.'], 404);
        }
        $this->service->delete($job);
        return response()->json(['message' => 'Đã xóa tin tuyển dụng.']);
    }

    public function publish(int $id): JsonResponse
    {
        $job = JobPosting::find($id);
        if (!$job) {
            return response()->json(['message' => 'Không tìm thấy.'], 404);
        }
        $this->service->publish($job);
        return response()->json(['message' => 'Đã đăng tin tuyển dụng.']);
    }

    public function unpublish(int $id): JsonResponse
    {
        $job = JobPosting::find($id);
        if (!$job) {
            return response()->json(['message' => 'Không tìm thấy.'], 404);
        }
        $this->service->unpublish($job);
        return response()->json(['message' => 'Đã tạm dừng tin tuyển dụng.']);
    }

    public function statistics(): JsonResponse
    {
        $stats = $this->service->getStatistics(auth('admin')->id());
        return response()->json(['data' => $stats]);
    }

    public function filterOptions(): JsonResponse
    {
        return response()->json(['data' => $this->service->getFilterOptions()]);
    }

    public function duplicate(int $id): JobPostingResource|JsonResponse
    {
        $job = JobPosting::find($id);
        if (!$job) {
            return response()->json(['message' => 'Không tìm thấy.'], 404);
        }
        return new JobPostingResource($this->service->duplicate($job));
    }

    public function bulkDelete(Request $request): JsonResponse
    {
        $request->validate([
            'ids'     => 'required|array|max:100',
            'ids.*'   => 'integer',
            'confirm' => 'required|accepted',
        ]);
        $count = $this->service->bulkDelete($request->input('ids'), auth('admin')->id());
        return response()->json(['message' => "Đã xóa {$count} tin."]);
    }

    public function bulkUpdateStatus(Request $request): JsonResponse
    {
        $request->validate([
            'ids'    => 'required|array|max:100',
            'ids.*'  => 'integer',
            'status' => 'required|in:active,paused,archived',
        ]);
        $count = $this->service->bulkUpdateStatus(
            $request->input('ids'),
            $request->input('status'),
            auth('admin')->id()
        );
        return response()->json(['message' => "Đã cập nhật {$count} tin."]);
    }
}
