<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreJobPostingRequest;
use App\Http\Requests\UpdateJobPostingRequest;
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

    public function store(StoreJobPostingRequest $request)
    {
        $data = $request->validated();
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

    public function update(UpdateJobPostingRequest $request, int $id)
    {
        $job = JobPosting::findOrFail($id);
        $data = $request->validated();

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
