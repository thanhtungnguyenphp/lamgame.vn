<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use App\Models\JobPosting;
use App\Services\ApplicationActivityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApplicationController extends Controller
{
    public function __construct(private ApplicationActivityService $activityService) {}

    public function index(Request $request)
    {
        $jobId = $request->get('job_id');

        $query = JobApplication::whereNotNull('job_posting_id')
            ->with('jobPosting:id,title,slug');

        if ($jobId) {
            $query->where('job_posting_id', $jobId);
        }

        $applications = $query->orderByDesc('applied_at')->paginate(20);
        $jobs = JobPosting::select('id', 'title')->orderBy('title')->get();

        return view('admin.applications.index', compact('applications', 'jobs', 'jobId'));
    }

    public function show($id)
    {
        $application = JobApplication::with('jobPosting:id,title,slug')->findOrFail($id);
        $this->activityService->logViewed($id);
        $activities = $this->activityService->getActivities($id);

        return view('admin.applications.show', compact('application', 'activities'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,reviewed,shortlisted,rejected,accepted',
            'employer_notes' => 'nullable|string|max:2000',
        ]);

        $application = JobApplication::findOrFail($id);
        $oldStatus = $application->status;

        $application->update([
            'status' => $request->status,
            'employer_notes' => $request->employer_notes,
        ]);

        if ($oldStatus !== $request->status) {
            $this->activityService->logStatusChanged($id, $oldStatus, $request->status);
        }
        if ($application->wasChanged('employer_notes')) {
            $this->activityService->logNoteChanged($id, $application->getOriginal('employer_notes'), $request->employer_notes);
        }

        return redirect()->route('admin.applications.show', $id)
            ->with('success', 'Đã cập nhật trạng thái thành công!');
    }

    public function destroy($id)
    {
        JobApplication::findOrFail($id)->delete();
        return redirect()->route('admin.applications.index')
            ->with('success', 'Đã xóa ứng viên thành công!');
    }

    public function downloadCV($id)
    {
        $application = JobApplication::findOrFail($id);

        if (!$application->resume_file_path) {
            abort(404, 'CV không tồn tại');
        }

        $filePath = storage_path('app/private/' . $application->resume_file_path);
        if (!file_exists($filePath)) {
            abort(404, 'File CV không tồn tại');
        }

        $this->activityService->logCVDownloaded($id);
        return response()->download($filePath);
    }
}
