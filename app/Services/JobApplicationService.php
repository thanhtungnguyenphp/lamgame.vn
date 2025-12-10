<?php

namespace App\Services;

use App\Models\JobApplication;
use App\Mail\ApplicationReceivedMail;
use App\Mail\NewApplicationMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Webkul\Product\Models\Product;

class JobApplicationService
{
    /**
     * Check if user already applied for this job
     */
    public function checkExistingApplication(int $jobId, string $email, ?int $userId = null): ?JobApplication
    {
        $query = JobApplication::where('job_id', $jobId)
            ->whereIn('status', ['pending', 'reviewed', 'shortlisted', 'accepted']); // Exclude rejected and cancelled

        // Check by user ID if authenticated, otherwise by email
        if ($userId) {
            $query->where('applicant_user_id', $userId);
        } else {
            $query->where('applicant_email', $email);
        }

        return $query->first();
    }

    /**
     * Create a new job application
     */
    public function createApplication(array $data): JobApplication
    {
        return DB::transaction(function () use ($data) {
            // Generate unique application code
            $applicationCode = $this->generateApplicationCode($data['job_id']);
            
            $data['application_code'] = $applicationCode;
            $data['applied_at'] = Carbon::now();

            // Create the application
            $application = JobApplication::create($data);

            // Log the application creation
            Log::info('New job application created', [
                'application_id' => $application->id,
                'job_id' => $data['job_id'],
                'applicant_email' => $data['applicant_email'],
                'applicant_name' => $data['applicant_name'],
                'application_code' => $applicationCode,
            ]);
            
            // Log activity
            app(ApplicationActivityService::class)->logCreated($application);

            return $application;
        });
    }

    /**
     * Generate unique application code
     */
    private function generateApplicationCode(int $jobId): string
    {
        do {
            // Format: JA-{YYYYMMDD}-{JobID}-{Random4}
            $code = sprintf(
                'JA-%s-%d-%s',
                Carbon::now()->format('Ymd'),
                $jobId,
                strtoupper(Str::random(4))
            );
        } while (JobApplication::where('application_code', $code)->exists());

        return $code;
    }

    /**
     * Send notifications for new application
     */
    public function sendNotifications(JobApplication $application, Product $job): void
    {
        try {
            // Send confirmation email to applicant
            $this->sendApplicantNotification($application, $job);

            // Send notification email to employer
            $this->sendEmployerNotification($application, $job);

        } catch (\Exception $e) {
            Log::error('Failed to send job application notifications', [
                'application_id' => $application->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Don't throw exception to avoid failing the application process
            // Notifications are secondary to the main application flow
        }
    }

    /**
     * Send confirmation email to applicant
     */
    private function sendApplicantNotification(JobApplication $application, Product $job): void
    {
        try {
            // Send email immediately for testing - can be queued later
            Log::info('Sending applicant notification', [
                'application_id' => $application->id,
                'applicant_name' => $application->applicant_name,
                'applicant_email' => $application->applicant_email,
                'job_id' => $job->id,
                'job_name' => $job->name,
            ]);
            
            $mail = new ApplicationReceivedMail($application, $job);
            
            Mail::to($application->applicant_email)
                ->send($mail);

            Log::info('Applicant notification sent successfully', [
                'application_id' => $application->id,
                'email' => $application->applicant_email,
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to send applicant notification', [
                'application_id' => $application->id,
                'email' => $application->applicant_email,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            // Re-throw to let the caller know there was an issue
            throw $e;
        }
    }

    /**
     * Send notification email to employer
     */
    private function sendEmployerNotification(JobApplication $application, Product $job): void
    {
        // Get employer email from job attributes
        $employerEmail = $this->getJobEmployerEmail($job);
        
        if (!$employerEmail) {
            Log::warning('No employer email found for job notification', [
                'job_id' => $job->id,
                'application_id' => $application->id,
            ]);
            return;
        }

        try {
            Log::info('Sending employer notification', [
                'application_id' => $application->id,
                'job_id' => $job->id,
                'employer_email' => $employerEmail,
            ]);
            
            $mail = new NewApplicationMail($application, $job);
            
            Mail::to($employerEmail)
                ->send($mail);

            Log::info('Employer notification sent successfully', [
                'application_id' => $application->id,
                'job_id' => $job->id,
                'employer_email' => $employerEmail,
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to send employer notification', [
                'application_id' => $application->id,
                'job_id' => $job->id,
                'employer_email' => $employerEmail,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            // Don't re-throw for employer email - it's not critical
        }
    }

    /**
     * Get employer email from job attributes
     */
    private function getJobEmployerEmail(Product $job): ?string
    {
        // Try to get contact email from product attributes
        $contactEmail = $job->attribute_values()
            ->whereHas('attribute', function ($query) {
                $query->where('code', 'contact_email');
            })
            ->first()?->text_value;

        if ($contactEmail && filter_var($contactEmail, FILTER_VALIDATE_EMAIL)) {
            return $contactEmail;
        }

        // Fallback to default HR email or admin email
        return config('mail.default_hr_email', config('mail.from.address'));
    }

    /**
     * Update application status
     */
    public function updateApplicationStatus(int $applicationId, string $status, ?string $notes = null): JobApplication
    {
        $validStatuses = ['pending', 'reviewed', 'shortlisted', 'rejected', 'accepted', 'cancelled'];
        
        if (!in_array($status, $validStatuses)) {
            throw new \InvalidArgumentException("Invalid status: {$status}");
        }

        return DB::transaction(function () use ($applicationId, $status, $notes) {
            $application = JobApplication::findOrFail($applicationId);
            
            $oldStatus = $application->status;
            $application->status = $status;
            
            if ($notes) {
                $application->employer_notes = $notes;
            }
            
            $application->save();

            Log::info('Application status updated', [
                'application_id' => $applicationId,
                'old_status' => $oldStatus,
                'new_status' => $status,
                'notes' => $notes,
            ]);

            // Send status update notification to applicant if status changed significantly
            if ($this->shouldNotifyStatusChange($oldStatus, $status)) {
                $this->sendStatusUpdateNotification($application);
            }

            return $application;
        });
    }

    /**
     * Check if status change should trigger notification
     */
    private function shouldNotifyStatusChange(string $oldStatus, string $newStatus): bool
    {
        $notifiableChanges = [
            'pending' => ['shortlisted', 'rejected', 'accepted'],
            'reviewed' => ['shortlisted', 'rejected', 'accepted'],
            'shortlisted' => ['rejected', 'accepted'],
        ];

        return isset($notifiableChanges[$oldStatus]) && 
               in_array($newStatus, $notifiableChanges[$oldStatus]);
    }

    /**
     * Send status update notification to applicant
     */
    private function sendStatusUpdateNotification(JobApplication $application): void
    {
        Queue::push(function () use ($application) {
            try {
                // You can create a separate mail class for status updates
                // For now, log the status change
                Log::info('Status update notification should be sent', [
                    'application_id' => $application->id,
                    'email' => $application->applicant_email,
                    'status' => $application->status,
                ]);

                // TODO: Implement status update email template
                
            } catch (\Exception $e) {
                Log::error('Failed to send status update notification', [
                    'application_id' => $application->id,
                    'error' => $e->getMessage(),
                ]);
            }
        });
    }

    /**
     * Get applications for a specific job
     */
    public function getJobApplications(int $jobId, array $filters = []): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $query = JobApplication::where('job_id', $jobId)
            ->with(['applicant', 'job'])
            ->orderBy('applied_at', 'desc');

        // Apply status filter
        if (isset($filters['status']) && !empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // Apply date range filter
        if (isset($filters['from_date'])) {
            $query->whereDate('applied_at', '>=', $filters['from_date']);
        }
        
        if (isset($filters['to_date'])) {
            $query->whereDate('applied_at', '<=', $filters['to_date']);
        }

        // Apply search filter
        if (isset($filters['search']) && !empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('applicant_name', 'LIKE', "%{$search}%")
                  ->orWhere('applicant_email', 'LIKE', "%{$search}%")
                  ->orWhere('application_code', 'LIKE', "%{$search}%");
            });
        }

        return $query->paginate($filters['per_page'] ?? 15);
    }

    /**
     * Get application statistics for a job
     */
    public function getJobApplicationStats(int $jobId): array
    {
        $stats = JobApplication::where('job_id', $jobId)
            ->selectRaw('
                COUNT(*) as total,
                COUNT(CASE WHEN status = "pending" THEN 1 END) as pending,
                COUNT(CASE WHEN status = "reviewed" THEN 1 END) as reviewed,
                COUNT(CASE WHEN status = "shortlisted" THEN 1 END) as shortlisted,
                COUNT(CASE WHEN status = "rejected" THEN 1 END) as rejected,
                COUNT(CASE WHEN status = "accepted" THEN 1 END) as accepted
            ')
            ->first();

        return [
            'total' => $stats->total,
            'pending' => $stats->pending,
            'reviewed' => $stats->reviewed,
            'shortlisted' => $stats->shortlisted,
            'rejected' => $stats->rejected,
            'accepted' => $stats->accepted,
            'response_rate' => $stats->total > 0 ? 
                round((($stats->reviewed + $stats->shortlisted + $stats->rejected + $stats->accepted) / $stats->total) * 100, 2) : 0
        ];
    }

    /**
     * Bulk update application statuses
     */
    public function bulkUpdateApplications(array $applicationIds, string $status, ?string $notes = null): int
    {
        $validStatuses = ['pending', 'reviewed', 'shortlisted', 'rejected', 'accepted', 'cancelled'];
        
        if (!in_array($status, $validStatuses)) {
            throw new \InvalidArgumentException("Invalid status: {$status}");
        }

        return DB::transaction(function () use ($applicationIds, $status, $notes) {
            $updateData = ['status' => $status];
            
            if ($notes) {
                $updateData['employer_notes'] = $notes;
            }

            $updated = JobApplication::whereIn('id', $applicationIds)->update($updateData);

            Log::info('Bulk application status update', [
                'application_ids' => $applicationIds,
                'status' => $status,
                'notes' => $notes,
                'updated_count' => $updated,
            ]);

            return $updated;
        });
    }
}