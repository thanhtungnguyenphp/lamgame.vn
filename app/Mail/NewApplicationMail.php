<?php

namespace App\Mail;

use App\Models\JobApplication;
use App\Models\JobPosting;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Queue\SerializesModels;

class NewApplicationMail extends Mailable
{
    use Queueable, SerializesModels;

    public JobApplication $application;
    public JobPosting $job;
    public array $jobData;
    public array $applicantData;
    public string $applicationCode;
    public $appliedAt;
    public array $quickActions;
    public array $applicationStats;

    public function __construct(JobApplication $application, JobPosting $job)
    {
        $this->application = $application;
        $this->job = $job;
        $this->applicationCode = $application->application_code;
        $this->appliedAt = $application->applied_at;
        $this->jobData = [
            'title'            => $job->title,
            'company'          => $job->company_name ?: 'LAMGAME',
            'salary'           => $job->salary_range ?: 'Thỏa thuận',
            'location'         => $job->location ?: 'Việt Nam',
            'job_type'         => $job->job_type ?: 'Full-time',
            'experience_level' => $job->experience_level,
        ];
        $this->applicantData = $this->parseApplicantData();
        $this->quickActions = $this->getQuickActions();
        $this->applicationStats = $this->getApplicationStats();
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            replyTo: [
                new Address(
                    $this->application->applicant_email,
                    $this->application->applicant_name
                )
            ],
            subject: "🎯 Ứng viên mới: {$this->application->applicant_name} - {$this->jobData['title']}",
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.job-application.employer-new-application',
            text: 'emails.job-application.employer-new-application-text',
        );
    }

    private function parseApplicantData(): array
    {
        $additionalInfo = $this->application->additional_info ?? [];

        return [
            'name'             => $this->application->applicant_name,
            'email'            => $this->application->applicant_email,
            'phone'            => $this->application->applicant_phone,
            'experience_level' => $additionalInfo['experience_level'] ?? 'Không xác định',
            'has_cv'           => !empty($this->application->resume_file_path),
            'cv_filename'      => $this->getCVFilename(),
            'cover_letter'     => $this->application->cover_letter,
            'applied_via'      => $additionalInfo['applied_via'] ?? 'website',
        ];
    }

    private function getCVFilename(): ?string
    {
        if (empty($this->application->resume_file_path)) {
            return null;
        }
        return basename($this->application->resume_file_path);
    }

    private function getQuickActions(): array
    {
        $baseUrl = config('app.url');

        return [
            'view_application'  => $baseUrl . '/admin/applications/' . $this->application->id,
            'download_cv'       => $baseUrl . '/admin/applications/' . $this->application->id . '/cv',
            'contact_applicant' => 'mailto:' . $this->application->applicant_email . '?subject=Re: Ứng tuyển ' . $this->jobData['title'],
            'call_applicant'    => 'tel:' . $this->application->applicant_phone,
        ];
    }

    private function getApplicationStats(): array
    {
        $jobPostingId = $this->job->id;

        $totalApplications = JobApplication::where('job_posting_id', $jobPostingId)->count();
        $todayApplications = JobApplication::where('job_posting_id', $jobPostingId)
            ->whereDate('created_at', today())->count();
        $pendingApplications = JobApplication::where('job_posting_id', $jobPostingId)
            ->where('status', 'pending')->count();

        return [
            'total'                => $totalApplications,
            'today'                => $todayApplications,
            'pending'              => $pendingApplications,
            'is_first_application' => $totalApplications === 1,
        ];
    }

    public function attachments(): array
    {
        return [];
    }
}
