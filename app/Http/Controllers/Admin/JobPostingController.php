<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobPosting;
use App\Services\JobPostingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobPostingController extends Controller
{
    public function __construct(private JobPostingService $service) {}

    public function index()
    {
        $stats = $this->service->getStatistics();
        $jobs = $this->service->list(['status' => request('status')], 20);

        return view('admin.jobs.index', compact('jobs', 'stats'));
    }

    public function create()
    {
        $filterOptions = $this->service->getFilterOptions();
        return view('admin.jobs.create', compact('filterOptions'));
    }

    public function store(Request $request)
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
            'application_deadline' => 'nullable|date|after:today',
            'is_featured'          => 'nullable|boolean',
            'is_urgent'            => 'nullable|boolean',
            'status'               => 'nullable|in:draft,active',
            'skills'               => 'nullable|array',
            'skills.*'             => 'string|max:100',
            'benefits'             => 'nullable|array',
            'benefits.*'           => 'string|max:100',
        ]);

        $data['created_by'] = Auth::guard('admin')->id();

        if ($request->hasFile('company_logo')) {
            $data['company_logo'] = $request->file('company_logo')->store('company-logos', 'public');
        }

        $this->service->create($data);

        return redirect()->route('admin.job-postings.index')
            ->with('success', 'Tạo tin tuyển dụng thành công.');
    }

    public function edit(int $id)
    {
        $job = $this->service->find($id);
        if (!$job) abort(404);

        $job->skills_list = $job->skills->pluck('skill_name')->toArray();
        $job->benefits_list = $job->benefits->pluck('benefit_name')->toArray();

        $filterOptions = $this->service->getFilterOptions();
        return view('admin.jobs.edit', compact('job', 'filterOptions'));
    }

    public function update(Request $request, int $id)
    {
        $job = JobPosting::findOrFail($id);

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
            'status'               => 'nullable|in:draft,active,paused,archived',
            'skills'               => 'nullable|array',
            'skills.*'             => 'string|max:100',
            'benefits'             => 'nullable|array',
            'benefits.*'           => 'string|max:100',
        ]);

        if ($request->hasFile('company_logo')) {
            $data['company_logo'] = $request->file('company_logo')->store('company-logos', 'public');
        }

        $this->service->update($job, $data);

        return redirect()->route('admin.job-postings.index')
            ->with('success', 'Cập nhật tin tuyển dụng thành công.');
    }

    public function destroy(int $id)
    {
        $job = JobPosting::findOrFail($id);
        $this->service->delete($job);

        return redirect()->route('admin.job-postings.index')
            ->with('success', 'Đã xóa tin tuyển dụng.');
    }
}
