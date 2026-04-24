<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'title'                => 'required|string|max:255',
            'description'          => 'required|string',
            'short_description'    => 'nullable|string|max:500',
            'job_type'             => 'nullable|string|max:50',
            'experience_level'     => 'nullable|string|max:50',
            'salary_range'         => 'nullable|string',
            'salary_min'           => 'nullable|numeric|min:0',
            'salary_max'           => 'nullable|numeric|min:0',
            'location'             => 'nullable|string',
            'is_remote'            => 'nullable|boolean',
            'education_level'      => 'nullable|string|max:50',
            'english_level'        => 'nullable|string|max:50',
            'company_name'         => 'nullable|string|max:255',
            'company_size'         => 'nullable|string|max:50',
            'contact_email'        => 'nullable|email',
            'contact_phone'        => 'nullable|string|max:20',
            'application_method'   => 'nullable|string',
            'application_url'      => 'nullable|url',
            'application_deadline' => 'nullable|date|after:today',
            'is_featured'          => 'nullable|boolean',
            'is_urgent'            => 'nullable|boolean',
            'status'               => 'nullable|in:draft,active',
            'skills'               => 'nullable|array',
            'skills.*'             => 'string|max:100',
            'benefits'             => 'nullable|array',
            'benefits.*'           => 'string|max:100',
            'meta_title'           => 'nullable|string|max:255',
            'meta_description'     => 'nullable|string|max:500',
        ]);

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

    public function update(Request $request, int $id): JobPostingResource|JsonResponse
    {
        $job = JobPosting::find($id);
        if (!$job) {
            return response()->json(['message' => 'Không tìm thấy tin tuyển dụng.'], 404);
        }

        $data = $request->validate([
            'title'                => 'sometimes|string|max:255',
            'description'          => 'sometimes|string',
            'short_description'    => 'nullable|string|max:500',
            'job_type'             => 'nullable|string|max:50',
            'experience_level'     => 'nullable|string|max:50',
            'salary_range'         => 'nullable|string',
            'salary_min'           => 'nullable|numeric|min:0',
            'salary_max'           => 'nullable|numeric|min:0',
            'location'             => 'nullable|string',
            'is_remote'            => 'nullable|boolean',
            'education_level'      => 'nullable|string|max:50',
            'english_level'        => 'nullable|string|max:50',
            'company_name'         => 'nullable|string|max:255',
            'company_size'         => 'nullable|string|max:50',
            'contact_email'        => 'nullable|email',
            'contact_phone'        => 'nullable|string|max:20',
            'application_method'   => 'nullable|string',
            'application_deadline' => 'nullable|date',
            'is_featured'          => 'nullable|boolean',
            'is_urgent'            => 'nullable|boolean',
            'status'               => 'nullable|in:draft,active,paused',
            'skills'               => 'nullable|array',
            'skills.*'             => 'string|max:100',
            'benefits'             => 'nullable|array',
            'benefits.*'           => 'string|max:100',
        ]);

        $job = $this->service->update($job, $data);
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
