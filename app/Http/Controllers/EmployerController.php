<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\JobApplication;
use App\Models\JobPosting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EmployerController extends Controller
{
    /**
     * Employer Dashboard — overview stats
     */
    public function dashboard()
    {
        $customer = auth('customer')->user();
        $companyId = $customer->company_id;

        $stats = [
            'total_jobs'         => JobPosting::where('company_id', $companyId)->count(),
            'active_jobs'        => JobPosting::where('company_id', $companyId)->where('status', 'active')->count(),
            'total_applications' => JobPosting::where('company_id', $companyId)->sum('application_count'),
            'total_views'        => JobPosting::where('company_id', $companyId)->sum('view_count'),
        ];

        $recentApplications = JobApplication::whereHas('jobPosting', function ($q) use ($companyId) {
            $q->where('company_id', $companyId);
        })->with('jobPosting')->orderByDesc('applied_at')->take(5)->get();

        $recentJobs = JobPosting::where('company_id', $companyId)
            ->orderByDesc('created_at')->take(5)->get();

        return view('lamgame.pages.employer.dashboard', compact('stats', 'recentApplications', 'recentJobs'));
    }

    /**
     * List employer's jobs
     */
    public function jobs(Request $request)
    {
        $customer = auth('customer')->user();
        $status = $request->get('status');

        $query = JobPosting::where('company_id', $customer->company_id)
            ->orderByDesc('created_at');

        if ($status && in_array($status, ['draft', 'active', 'paused', 'expired', 'archived'])) {
            $query->where('status', $status);
        }

        $jobs = $query->paginate(10);

        return view('lamgame.pages.employer.jobs', compact('jobs', 'status'));
    }

    /**
     * Show create job form
     */
    public function createJob()
    {
        return view('lamgame.pages.employer.job-form', ['job' => null]);
    }

    /**
     * Store new job
     */
    public function storeJob(Request $request)
    {
        $customer = auth('customer')->user();

        $validated = $request->validate([
            'title'            => 'required|string|max:255',
            'description'      => 'required|string',
            'short_description' => 'nullable|string|max:500',
            'job_type'         => 'required|in:full-time,part-time,contract,freelance,intern',
            'experience_level' => 'nullable|string|max:50',
            'salary_range'     => 'nullable|string|max:100',
            'salary_min'       => 'nullable|numeric|min:0',
            'salary_max'       => 'nullable|numeric|min:0',
            'location'         => 'nullable|string|max:255',
            'is_remote'        => 'boolean',
            'application_deadline' => 'nullable|date|after:today',
            'skills'           => 'nullable|string',
            'benefits'         => 'nullable|string',
        ]);

        $job = JobPosting::create([
            'title'              => $validated['title'],
            'slug'               => Str::slug($validated['title']) . '-' . Str::random(4),
            'description'        => $validated['description'],
            'short_description'  => $validated['short_description'] ?? null,
            'job_type'           => $validated['job_type'],
            'experience_level'   => $validated['experience_level'] ?? null,
            'salary_range'       => $validated['salary_range'] ?? null,
            'salary_min'         => $validated['salary_min'] ?? null,
            'salary_max'         => $validated['salary_max'] ?? null,
            'location'           => $validated['location'] ?? null,
            'is_remote'          => $validated['is_remote'] ?? false,
            'application_deadline' => $validated['application_deadline'] ?? null,
            'company_id'         => $customer->company_id,
            'company_name'       => $customer->company->name ?? null,
            'company_logo'       => $customer->company->logo ?? null,
            'contact_email'      => $customer->email,
            'status'             => 'draft',
            'created_by'         => null,
        ]);

        // Skills
        if (!empty($validated['skills'])) {
            foreach (explode(',', $validated['skills']) as $skill) {
                $skill = trim($skill);
                if ($skill) {
                    $job->skills()->create(['skill_name' => $skill]);
                }
            }
        }

        // Benefits
        if (!empty($validated['benefits'])) {
            foreach (explode(',', $validated['benefits']) as $benefit) {
                $benefit = trim($benefit);
                if ($benefit) {
                    $job->benefits()->create(['benefit_name' => $benefit]);
                }
            }
        }

        return redirect()->route('employer.jobs')->with('success', 'Đã tạo job. Nhấn Publish để hiển thị.');
    }

    /**
     * Edit job form
     */
    public function editJob(int $id)
    {
        $customer = auth('customer')->user();
        $job = JobPosting::where('company_id', $customer->company_id)->findOrFail($id);

        return view('lamgame.pages.employer.job-form', compact('job'));
    }

    /**
     * Update job
     */
    public function updateJob(Request $request, int $id)
    {
        $customer = auth('customer')->user();
        $job = JobPosting::where('company_id', $customer->company_id)->findOrFail($id);

        $validated = $request->validate([
            'title'            => 'required|string|max:255',
            'description'      => 'required|string',
            'short_description' => 'nullable|string|max:500',
            'job_type'         => 'required|in:full-time,part-time,contract,freelance,intern',
            'experience_level' => 'nullable|string|max:50',
            'salary_range'     => 'nullable|string|max:100',
            'salary_min'       => 'nullable|numeric|min:0',
            'salary_max'       => 'nullable|numeric|min:0',
            'location'         => 'nullable|string|max:255',
            'is_remote'        => 'boolean',
            'application_deadline' => 'nullable|date',
            'skills'           => 'nullable|string',
            'benefits'         => 'nullable|string',
        ]);

        $job->update($validated);

        // Update skills
        if (isset($validated['skills'])) {
            $job->skills()->delete();
            foreach (explode(',', $validated['skills']) as $skill) {
                $skill = trim($skill);
                if ($skill) $job->skills()->create(['skill_name' => $skill]);
            }
        }

        // Update benefits
        if (isset($validated['benefits'])) {
            $job->benefits()->delete();
            foreach (explode(',', $validated['benefits']) as $benefit) {
                $benefit = trim($benefit);
                if ($benefit) $job->benefits()->create(['benefit_name' => $benefit]);
            }
        }

        return redirect()->route('employer.jobs')->with('success', 'Đã cập nhật job.');
    }

    /**
     * Toggle publish/unpublish
     */
    public function togglePublish(int $id)
    {
        $customer = auth('customer')->user();
        $job = JobPosting::where('company_id', $customer->company_id)->findOrFail($id);

        if ($job->status === 'active') {
            $job->update(['status' => 'paused']);
            return back()->with('success', 'Đã tạm dừng job.');
        }

        $job->update(['status' => 'active', 'published_at' => now()]);
        return back()->with('success', 'Đã publish job.');
    }

    /**
     * View applications for a job
     */
    public function applications(int $jobId)
    {
        $customer = auth('customer')->user();
        $job = JobPosting::where('company_id', $customer->company_id)->findOrFail($jobId);

        $applications = JobApplication::where('job_posting_id', $job->id)
            ->orderByDesc('applied_at')
            ->paginate(15);

        return view('lamgame.pages.employer.applications', compact('job', 'applications'));
    }

    /**
     * Update application status
     */
    public function updateApplicationStatus(Request $request, int $applicationId)
    {
        $customer = auth('customer')->user();

        $application = JobApplication::whereHas('jobPosting', function ($q) use ($customer) {
            $q->where('company_id', $customer->company_id);
        })->findOrFail($applicationId);

        $request->validate([
            'status' => 'required|in:pending,reviewed,shortlisted,rejected,accepted',
            'notes'  => 'nullable|string|max:1000',
        ]);

        $application->update([
            'status'         => $request->status,
            'employer_notes' => $request->notes,
        ]);

        return back()->with('success', 'Đã cập nhật trạng thái.');
    }

    /**
     * Employer registration page
     */
    public function showRegister()
    {
        return view('lamgame.pages.employer.register');
    }

    /**
     * Process employer registration
     */
    public function register(Request $request)
    {
        $customer = auth('customer')->user();

        if ($customer->is_employer) {
            return redirect()->route('employer.dashboard');
        }

        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'description'  => 'nullable|string|max:2000',
            'website'      => 'nullable|url|max:255',
            'industry'     => 'nullable|string|max:100',
            'address'      => 'nullable|string|max:255',
        ]);

        $company = Company::create([
            'name'              => $validated['company_name'],
            'description'       => $validated['description'] ?? null,
            'website'           => $validated['website'] ?? null,
            'industry'          => $validated['industry'] ?? 'Game Development',
            'address'           => $validated['address'] ?? null,
            'status'            => true,
            'created_by_admin_id' => null,
        ]);

        $customer->update([
            'is_employer'     => true,
            'company_id'      => $company->id,
            'employer_status' => 'pending',
        ]);

        return redirect()->route('employer.dashboard')->with('success', 'Đăng ký employer thành công! Đang chờ duyệt.');
    }

    /**
     * Send message to candidate (creates or reuses conversation)
     */
    public function sendMessage(Request $request, int $applicationId)
    {
        $customer = auth('customer')->user();
        $application = JobApplication::whereHas('jobPosting', function ($q) use ($customer) {
            $q->where('company_id', $customer->company_id);
        })->findOrFail($applicationId);

        $request->validate(['message' => 'required|string|max:2000']);

        $candidateId = $application->applicant_user_id;
        if (!$candidateId) {
            return back()->with('error', 'Ứng viên chưa có tài khoản trên hệ thống.');
        }

        // Find or create conversation
        $conversation = \App\Models\ForumConversation::where('context_type', 'job')
            ->where('job_application_id', $applicationId)
            ->first();

        if (!$conversation) {
            $conversation = \App\Models\ForumConversation::create([
                'participant_1'      => $customer->id,
                'participant_2'      => $candidateId,
                'job_application_id' => $applicationId,
                'context_type'       => 'job',
                'last_message_at'    => now(),
            ]);
        }

        \App\Models\ForumMessage::create([
            'conversation_id' => $conversation->id,
            'sender_id'       => $customer->id,
            'content'         => $request->message,
        ]);

        $conversation->update(['last_message_at' => now()]);

        // Update application status to reviewed if still pending
        if ($application->status === 'pending') {
            $application->update(['status' => 'reviewed']);
        }

        return back()->with('success', 'Đã gửi tin nhắn cho ứng viên.');
    }

    /**
     * Schedule interview for a candidate
     */
    public function scheduleInterview(Request $request, int $applicationId)
    {
        $customer = auth('customer')->user();
        $application = JobApplication::whereHas('jobPosting', function ($q) use ($customer) {
            $q->where('company_id', $customer->company_id);
        })->findOrFail($applicationId);

        $request->validate([
            'scheduled_at'     => 'required|date|after:now',
            'duration_minutes' => 'nullable|integer|min:15|max:180',
            'type'             => 'required|in:online,onsite',
            'meeting_url'      => 'nullable|url',
            'location'         => 'nullable|string|max:255',
            'notes'            => 'nullable|string|max:1000',
        ]);

        $interview = \App\Models\JobInterview::create([
            'application_id'   => $applicationId,
            'employer_id'      => $customer->id,
            'candidate_id'     => $application->applicant_user_id,
            'scheduled_at'     => $request->scheduled_at,
            'duration_minutes' => $request->duration_minutes ?? 60,
            'type'             => $request->type,
            'meeting_url'      => $request->meeting_url,
            'location'         => $request->location,
            'notes'            => $request->notes,
            'status'           => 'proposed',
        ]);

        // Auto-update application status to shortlisted
        if (in_array($application->status, ['pending', 'reviewed'])) {
            $application->update(['status' => 'shortlisted']);
        }

        return back()->with('success', 'Đã lên lịch phỏng vấn.');
    }

    /**
     * Download interview .ics calendar file
     */
    public function downloadIcs(int $interviewId)
    {
        $customer = auth('customer')->user();
        $interview = \App\Models\JobInterview::forUser($customer->id)->findOrFail($interviewId);

        $filename = 'interview-' . $interview->scheduled_at->format('Y-m-d') . '.ics';

        return response($interview->toIcs(), 200, [
            'Content-Type'        => 'text/calendar; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }
}

