<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\JobPostingResource;
use App\Models\JobPosting;
use App\Services\JobPostingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class JobManageController extends Controller
{
    public function __construct(private JobPostingService $service) {}

    public function list(Request $request): JsonResponse
    {
        $admin = $request->auth_admin;

        $filters = $request->only([
            'search', 'job_type', 'location', 'experience_level',
            'is_featured', 'is_remote', 'sort_by', 'sort_dir', 'status',
        ]);
        $filters['created_by'] = $admin->id;

        $jobs = $this->service->list($filters, $request->integer('per_page', 15));

        return response()->json([
            'status' => 'success',
            'data'   => JobPostingResource::collection($jobs),
            'meta'   => [
                'current_page' => $jobs->currentPage(),
                'last_page'    => $jobs->lastPage(),
                'per_page'     => $jobs->perPage(),
                'total'        => $jobs->total(),
            ],
        ]);
    }

    public function detail(string $slug): JsonResponse
    {
        $job = $this->service->findBySlug($slug);
        if (!$job) {
            return response()->json(['status' => 'error', 'message' => 'Không tìm thấy tin tuyển dụng.'], 404);
        }

        return response()->json([
            'status' => 'success',
            'data'   => new JobPostingResource($job),
        ]);
    }

    public function publish(Request $request): JsonResponse
    {
        $admin = $request->auth_admin;

        $request->validate([
            'title'                => 'required|string|max:255',
            'description'          => 'required|string',
            'short_description'    => 'nullable|string|max:500',
            'job_type'             => 'nullable|string|max:50',
            'experience_level'     => 'nullable|string|max:50',
            'salary_range'         => 'nullable|string',
            'salary_min'           => 'nullable|numeric|min:0',
            'salary_max'           => 'nullable|numeric|min:0|gte:salary_min',
            'location'             => 'nullable|string',
            'is_remote'            => 'nullable|boolean',
            'education_level'      => 'nullable|string|max:50',
            'english_level'        => 'nullable|string|max:50',
            'company_name'         => 'nullable|string|max:255',
            'company_id'           => 'nullable|integer|exists:companies,id',
            'company_size'         => 'nullable|string|max:50',
            'contact_email'        => 'nullable|email',
            'contact_phone'        => 'nullable|string|max:20',
            'application_method'   => 'nullable|string',
            'application_url'      => 'nullable|url',
            'application_deadline' => 'nullable|date|after:today',
            'is_featured'          => 'nullable|boolean',
            'is_urgent'            => 'nullable|boolean',
            'status'               => 'nullable|in:draft,active',
            'skills'               => 'nullable|array|max:20',
            'skills.*'             => 'string|max:100',
            'benefits'             => 'nullable|array|max:20',
            'benefits.*'           => 'string|max:100',
            'meta_title'           => 'nullable|string|max:255',
            'meta_description'     => 'nullable|string|max:500',
        ]);

        $data = $request->all();
        $data['created_by'] = $admin->id;

        $job = $this->service->create($data);

        return response()->json([
            'status'  => 'success',
            'message' => 'Đã tạo tin tuyển dụng.',
            'data'    => new JobPostingResource($job),
        ], 201);
    }

    public function update(Request $request, string $slug): JsonResponse
    {
        $admin = $request->auth_admin;
        $job = JobPosting::where('slug', $slug)->where('created_by', $admin->id)->first();

        if (!$job) {
            return response()->json(['status' => 'error', 'message' => 'Không tìm thấy tin tuyển dụng.'], 404);
        }

        $request->validate([
            'title'                => 'sometimes|string|max:255',
            'description'          => 'sometimes|string',
            'short_description'    => 'nullable|string|max:500',
            'job_type'             => 'nullable|string|max:50',
            'experience_level'     => 'nullable|string|max:50',
            'salary_range'         => 'nullable|string',
            'salary_min'           => 'nullable|numeric|min:0',
            'salary_max'           => 'nullable|numeric|min:0|gte:salary_min',
            'location'             => 'nullable|string',
            'is_remote'            => 'nullable|boolean',
            'education_level'      => 'nullable|string|max:50',
            'english_level'        => 'nullable|string|max:50',
            'company_name'         => 'nullable|string|max:255',
            'company_id'           => 'nullable|integer|exists:companies,id',
            'company_size'         => 'nullable|string|max:50',
            'contact_email'        => 'nullable|email',
            'contact_phone'        => 'nullable|string|max:20',
            'application_method'   => 'nullable|string',
            'application_deadline' => 'nullable|date',
            'is_featured'          => 'nullable|boolean',
            'is_urgent'            => 'nullable|boolean',
            'status'               => 'nullable|in:draft,active,paused,archived',
            'skills'               => 'nullable|array|max:20',
            'skills.*'             => 'string|max:100',
            'benefits'             => 'nullable|array|max:20',
            'benefits.*'           => 'string|max:100',
            'meta_title'           => 'nullable|string|max:255',
            'meta_description'     => 'nullable|string|max:500',
        ]);

        $job = $this->service->update($job, $request->all());

        return response()->json([
            'status'  => 'success',
            'message' => 'Đã cập nhật tin tuyển dụng.',
            'data'    => new JobPostingResource($job),
        ]);
    }

    public function destroy(Request $request, string $slug): JsonResponse
    {
        $admin = $request->auth_admin;
        $job = JobPosting::where('slug', $slug)->where('created_by', $admin->id)->first();

        if (!$job) {
            return response()->json(['status' => 'error', 'message' => 'Không tìm thấy tin tuyển dụng.'], 404);
        }

        $this->service->delete($job);

        return response()->json(['status' => 'success', 'message' => 'Đã xóa tin tuyển dụng.']);
    }

    public function changeStatus(Request $request, string $slug): JsonResponse
    {
        $admin = $request->auth_admin;
        $job = JobPosting::where('slug', $slug)->where('created_by', $admin->id)->first();

        if (!$job) {
            return response()->json(['status' => 'error', 'message' => 'Không tìm thấy.'], 404);
        }

        $request->validate(['status' => 'required|in:draft,active,paused,archived']);

        $status = $request->input('status');
        if ($status === 'active') {
            $this->service->publish($job);
        } elseif ($status === 'paused') {
            $this->service->unpublish($job);
        } else {
            $job->update(['status' => $status]);
        }

        return response()->json([
            'status'  => 'success',
            'message' => "Đã chuyển trạng thái sang '{$status}'.",
        ]);
    }

    public function statistics(Request $request): JsonResponse
    {
        $admin = $request->auth_admin;
        $stats = $this->service->getStatistics($admin->id);

        return response()->json(['status' => 'success', 'data' => $stats]);
    }
}
