<?php

namespace App\Services;

use App\Models\JobApplication;
use App\Models\JobPosting;
use App\Mail\ApplicationReceivedMail;
use App\Mail\NewApplicationMail;
use App\Services\ApplicationActivityService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class JobPostingApplicationService
{
    public function __construct(private ApplicationActivityService $activityService) {}

    public function checkExisting(int $jobPostingId, string $email, ?int $userId = null): bool
    {
        return JobApplication::where('job_posting_id', $jobPostingId)
            ->where(function ($q) use ($email, $userId) {
                $q->where('applicant_email', $email);
                if ($userId) $q->orWhere('applicant_user_id', $userId);
            })
            ->exists();
    }

    public function create(array $data): JobApplication
    {
        return DB::transaction(function () use ($data) {
            $application = JobApplication::create([
                'job_posting_id'    => $data['job_posting_id'],
                'job_id'            => $data['job_id'] ?? null, // legacy FK
                'applicant_user_id' => $data['applicant_user_id'] ?? 0,
                'applicant_name'    => $data['applicant_name'],
                'applicant_email'   => $data['applicant_email'],
                'applicant_phone'   => $data['applicant_phone'] ?? null,
                'cover_letter'      => $data['cover_letter'] ?? null,
                'resume_file_path'  => $data['resume_file_path'] ?? null,
                'additional_info'   => $data['additional_info'] ?? null,
                'status'            => 'pending',
                'application_code'  => $this->generateCode($data['job_posting_id']),
                'applied_at'        => now(),
            ]);

            // Increment application count
            JobPosting::where('id', $data['job_posting_id'])->increment('application_count');

            $this->activityService->log($application->id, 'created', 'Ứng viên nộp đơn ứng tuyển.');

            return $application;
        });
    }

    public function sendNotifications(JobApplication $application): void
    {
        $job = JobPosting::find($application->job_posting_id);
        if (!$job) return;

        try {
            Mail::to($application->applicant_email)
                ->queue(new ApplicationReceivedMail($application, $job));

            if ($job->contact_email) {
                Mail::to($job->contact_email)
                    ->queue(new NewApplicationMail($application, $job));
            }
        } catch (\Exception $e) {
            Log::error('Job application notification failed', [
                'application_id' => $application->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function updateStatus(int $applicationId, string $status, ?string $notes = null): JobApplication
    {
        $application = JobApplication::findOrFail($applicationId);
        $oldStatus = $application->status;

        $application->update([
            'status'         => $status,
            'employer_notes' => $notes ?? $application->employer_notes,
        ]);

        $this->activityService->log($application->id, 'status_changed',
            "Trạng thái thay đổi: {$oldStatus} → {$status}", $oldStatus, $status);

        return $application;
    }

    public function getApplications(int $jobPostingId, array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = JobApplication::where('job_posting_id', $jobPostingId);

        if ($status = $filters['status'] ?? null) {
            $query->where('status', $status);
        }
        if ($search = $filters['search'] ?? null) {
            $query->where(function ($q) use ($search) {
                $q->where('applicant_name', 'like', "%{$search}%")
                  ->orWhere('applicant_email', 'like', "%{$search}%");
            });
        }

        return $query->orderByDesc('applied_at')->paginate($perPage);
    }

    public function getStats(int $jobPostingId): array
    {
        $counts = JobApplication::where('job_posting_id', $jobPostingId)
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        return [
            'total'       => array_sum($counts),
            'pending'     => $counts['pending'] ?? 0,
            'reviewed'    => $counts['reviewed'] ?? 0,
            'shortlisted' => $counts['shortlisted'] ?? 0,
            'accepted'    => $counts['accepted'] ?? 0,
            'rejected'    => $counts['rejected'] ?? 0,
        ];
    }

    private function generateCode(int $jobPostingId): string
    {
        return 'JA-' . now()->format('Ymd') . '-' . $jobPostingId . '-' . strtoupper(Str::random(4));
    }
}
