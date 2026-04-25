<?php

namespace App\Mail;

use App\Models\JobApplication;
use App\Models\JobPosting;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ApplicationReceivedMail extends Mailable
{
    use Queueable, SerializesModels;

    public JobApplication $application;
    public JobPosting $job;
    public string $applicantName;
    public string $companyName;
    public string $jobTitle;
    public string $applicationCode;
    public $appliedAt;
    public array $jobData;
    public array $nextSteps;

    public function __construct(JobApplication $application, JobPosting $job)
    {
        $this->application = $application;
        $this->job = $job;
        $this->applicantName = $application->applicant_name;
        $this->jobTitle = $job->title;
        $this->companyName = $job->company_name ?: 'LAMGAME';
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
        $this->nextSteps = [
            'review_time'       => '2-3 ngày làm việc',
            'contact_method'    => 'email hoặc điện thoại',
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

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "✅ Đã nhận hồ sơ ứng tuyển - {$this->jobTitle}",
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.job-application.applicant-received',
            text: 'emails.job-application.applicant-received-text',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
