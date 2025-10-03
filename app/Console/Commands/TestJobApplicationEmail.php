<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\JobApplication;
use App\Mail\ApplicationReceivedMail;
use App\Mail\NewApplicationMail;
use Webkul\Product\Models\Product;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class TestJobApplicationEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:job-application-email {type} {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test job application email templates. Usage: test:job-application-email {applicant|employer} {email}';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $type = $this->argument('type');
        $email = $this->argument('email');
        
        if (!in_array($type, ['applicant', 'employer'])) {
            $this->error('Type must be either "applicant" or "employer"');
            return 1;
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->error('Please provide a valid email address');
            return 1;
        }
        
        // Create mock data
        $mockJob = $this->createMockJob();
        $mockApplication = $this->createMockApplication();
        
        try {
            if ($type === 'applicant') {
                $this->info('Sending applicant email to: ' . $email);
                Mail::to($email)->send(new ApplicationReceivedMail($mockApplication, $mockJob));
            } else {
                $this->info('Sending employer email to: ' . $email);
                Mail::to($email)->send(new NewApplicationMail($mockApplication, $mockJob));
            }
            
            $this->info('✅ Email sent successfully!');
            return 0;
            
        } catch (\Exception $e) {
            $this->error('❌ Failed to send email: ' . $e->getMessage());
            return 1;
        }
    }
    
    private function createMockJob()
    {
        $job = new \stdClass();
        $job->id = 123;
        $job->name = 'Senior Game Developer - Công ty Game ABC';
        $job->price = 25000000; // 25 triệu
        
        // Mock attribute_values method
        $job->attribute_values = function() {
            return collect();
        };
        
        return $job;
    }
    
    private function createMockApplication()
    {
        $application = new \stdClass();
        $application->id = 456;
        $application->job_id = 123;
        $application->applicant_user_id = null;
        $application->applicant_name = 'Nguyễn Văn A';
        $application->applicant_email = 'nguyenvana@example.com';
        $application->applicant_phone = '0901234567';
        $application->cover_letter = 'Tôi là một game developer có 5 năm kinh nghiệm với Unity và C#. Tôi đã tham gia phát triển nhiều game mobile thành công và mong muốn đóng góp vào dự án của công ty.';
        $application->resume_file_path = 'cvs/2025/10/nguyen-van-a-cv_20251002_153000_abc123.pdf';
        $application->additional_info = [
            'experience_level' => 'Senior (5+ năm)',
            'applied_via' => 'web_form',
            'user_agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7)',
            'ip_address' => '192.168.1.100'
        ];
        $application->status = 'pending';
        $application->application_code = 'JA-20251002-123-ABCD';
        $application->applied_at = Carbon::now();
        $application->created_at = Carbon::now();
        $application->updated_at = Carbon::now();
        
        return $application;
    }
}
