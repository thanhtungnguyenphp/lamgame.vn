<?php

namespace App\Mail;

use App\Models\JobApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Queue\SerializesModels;
use Webkul\Product\Models\Product;

class ApplicationReceivedMail extends Mailable
{
    use Queueable, SerializesModels;

    public JobApplication $application;
    public Product $job;
    public array $jobData;

    /**
     * Create a new message instance.
     */
    public function __construct(JobApplication $application, Product $job)
    {
        $this->application = $application;
        $this->job = $job;
        $this->jobData = $this->parseJobData();
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(
                config('mail.from.address'),
                config('mail.from.name', 'Làm Game')
            ),
            subject: "✅ Đã nhận hồ sơ ứng tuyển - {$this->jobData['title']}",
            tags: ['job-application', 'applicant-notification'],
            metadata: [
                'application_id' => $this->application->id,
                'job_id' => $this->job->id,
                'application_code' => $this->application->application_code,
            ],
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            html: 'emails.job-application.applicant-received',
            text: 'emails.job-application.applicant-received-text',
            with: [
                'application' => $this->application,
                'job' => $this->job,
                'jobData' => $this->jobData,
                'applicantName' => $this->application->applicant_name,
                'companyName' => $this->jobData['company'],
                'jobTitle' => $this->jobData['title'],
                'applicationCode' => $this->application->application_code,
                'appliedAt' => $this->application->applied_at,
                'nextSteps' => $this->getNextSteps(),
            ],
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
     * Get next steps information
     */
    private function getNextSteps(): array
    {
        return [
            'review_time' => '2-3 ngày làm việc',
            'contact_method' => 'email hoặc điện thoại',
            'what_happens_next' => [
                'HR sẽ xem xét hồ sơ của bạn',
                'Nếu phù hợp, chúng tôi sẽ liên hệ để sắp xếp phỏng vấn',
                'Bạn sẽ nhận được thông báo về kết quả qua email',
            ],
            'tips' => [
                'Kiểm tra email thường xuyên',
                'Chuẩn bị sẵn sàng cho cuộc phỏng vấn',
                'Tìm hiểu thêm về công ty',
            ],
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

    /**
     * Build the message (for backward compatibility)
     */
    public function build()
    {
        // Debug logging
        \Log::info('Building ApplicationReceivedMail', [
            'application_id' => $this->application->id,
            'applicant_name' => $this->application->applicant_name,
            'job_title' => $this->jobData['title'],
            'application_code' => $this->application->application_code,
        ]);
        
        return $this->subject("✅ Đã nhận hồ sơ ứng tuyển - {$this->jobData['title']}")
                    ->view('emails.job-application.applicant-received')
                    ->text('emails.job-application.applicant-received-text')
                    ->with([
                        'application' => $this->application,
                        'job' => $this->job,
                        'jobData' => $this->jobData,
                        'applicantName' => $this->application->applicant_name,
                        'companyName' => $this->jobData['company'] ?: 'Làm Game',
                        'jobTitle' => $this->jobData['title'],
                        'applicationCode' => $this->application->application_code,
                        'appliedAt' => $this->application->applied_at,
                        'nextSteps' => $this->getNextSteps(),
                    ]);
    }
}