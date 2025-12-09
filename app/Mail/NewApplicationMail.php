<?php

namespace App\Mail;

use App\Models\JobApplication;
use App\Services\FileUploadService;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Queue\SerializesModels;
use Webkul\Product\Models\Product;

class NewApplicationMail extends Mailable
{
    use Queueable, SerializesModels;

    public JobApplication $application;
    public Product $job;
    public array $jobData;
    public array $applicantData;

    /**
     * Create a new message instance.
     */
    public function __construct(JobApplication $application, Product $job)
    {
        $this->application = $application;
        $this->job = $job;
        $this->jobData = $this->parseJobData();
        $this->applicantData = $this->parseApplicantData();
    }

    /**
     * Get the message envelope.
     */
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

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.job-application.employer-new-application',
            text: 'emails.job-application.employer-new-application-text',
        );
    }

    /**
     * Parse job data from product
     */
    private function parseJobData(): array
    {
        $name = $this->job->name ?? '';
        $parts = explode(' - ', $name);
        
        return [
            'title' => $parts[0] ?? $name,
            'company' => trim(str_replace(' - ', ' ', $parts[1] ?? '')),
            'salary' => $this->formatSalary($this->job->price ?? 0),
            'location' => $this->getJobAttribute('job_location') ?? 'Việt Nam',
            'job_type' => $this->getJobAttribute('job_type') ?? 'Full-time',
            'experience_level' => $this->getJobAttribute('experience_level'),
        ];
    }

    /**
     * Parse applicant data
     */
    private function parseApplicantData(): array
    {
        $additionalInfo = $this->application->additional_info ?? [];
        
        return [
            'name' => $this->application->applicant_name,
            'email' => $this->application->applicant_email,
            'phone' => $this->application->applicant_phone,
            'experience_level' => $additionalInfo['experience_level'] ?? 'Không xác định',
            'has_cv' => !empty($this->application->resume_file_path),
            'cv_filename' => $this->getCVFilename(),
            'cover_letter' => $this->application->cover_letter,
            'applied_via' => $additionalInfo['applied_via'] ?? 'website',
            'location_info' => $this->getLocationInfo($additionalInfo['ip_address'] ?? null),
        ];
    }

    /**
     * Get job attribute value
     */
    private function getJobAttribute(string $attributeCode): ?string
    {
        $attributeValue = $this->job->attribute_values()
            ->whereHas('attribute', function ($query) use ($attributeCode) {
                $query->where('code', $attributeCode);
            })
            ->first();

        return $attributeValue?->text_value;
    }

    /**
     * Format salary for display
     */
    private function formatSalary(float $price): string
    {
        if ($price <= 0) {
            return 'Thỏa thuận';
        }
        
        if ($price >= 1000000) {
            return number_format($price / 1000000, 1) . ' triệu VND';
        }
        
        return number_format($price, 0) . ' VND';
    }

    /**
     * Get CV filename for display
     */
    private function getCVFilename(): ?string
    {
        if (empty($this->application->resume_file_path)) {
            return null;
        }

        $filename = basename($this->application->resume_file_path);
        
        // Try to extract original name before timestamp
        if (preg_match('/^(.+?)_\d{8}_\d{6}_[a-zA-Z0-9]+\.([a-z]+)$/', $filename, $matches)) {
            return str_replace('_', ' ', $matches[1]) . '.' . $matches[2];
        }
        
        return $filename;
    }

    /**
     * Get location info from IP address
     */
    private function getLocationInfo(?string $ipAddress): array
    {
        if (!$ipAddress || $ipAddress === '127.0.0.1' || str_starts_with($ipAddress, '192.168.')) {
            return ['city' => 'Local', 'country' => 'VN'];
        }

        // In production, you might want to use a geolocation service
        // For now, assume Vietnam
        return ['city' => 'Việt Nam', 'country' => 'VN'];
    }

    /**
     * Get quick actions for employer
     */
    private function getQuickActions(): array
    {
        $baseUrl = config('app.url');
        
        return [
            'view_application' => $baseUrl . '/admin/applications/' . $this->application->id,
            'download_cv' => $baseUrl . '/admin/applications/' . $this->application->id . '/cv',
            'contact_applicant' => 'mailto:' . $this->application->applicant_email . '?subject=Re: Ứng tuyển ' . $this->jobData['title'],
            'call_applicant' => 'tel:' . $this->application->applicant_phone,
            'shortlist' => $baseUrl . '/admin/applications/' . $this->application->id . '/status/shortlisted',
            'reject' => $baseUrl . '/admin/applications/' . $this->application->id . '/status/rejected',
        ];
    }

    /**
     * Get application statistics for this job
     */
    private function getApplicationStats(): array
    {
        $jobId = $this->job->id;
        
        // Get stats from database
        $totalApplications = JobApplication::where('job_id', $jobId)->count();
        $todayApplications = JobApplication::where('job_id', $jobId)
            ->whereDate('created_at', today())
            ->count();
        $pendingApplications = JobApplication::where('job_id', $jobId)
            ->where('status', 'pending')
            ->count();
            
        return [
            'total' => $totalApplications,
            'today' => $todayApplications,
            'pending' => $pendingApplications,
            'is_first_application' => $totalApplications === 1,
        ];
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}