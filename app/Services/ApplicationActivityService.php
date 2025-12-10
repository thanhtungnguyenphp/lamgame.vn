<?php

namespace App\Services;

use App\Models\ApplicationActivity;
use App\Models\JobApplication;
use Illuminate\Support\Facades\Auth;

class ApplicationActivityService
{
    /**
     * Log activity
     */
    public function log(
        int $applicationId,
        string $activityType,
        string $description,
        ?string $oldValue = null,
        ?string $newValue = null,
        ?array $metadata = null
    ): ApplicationActivity {
        $performedByType = null;
        $performedById = null;
        
        if (Auth::guard('admin')->check()) {
            $performedByType = 'admin';
            $performedById = Auth::guard('admin')->id();
        } elseif (Auth::guard('customer')->check()) {
            $performedByType = 'applicant';
            $performedById = Auth::guard('customer')->id();
        } else {
            $performedByType = 'system';
        }
        
        return ApplicationActivity::create([
            'application_id' => $applicationId,
            'activity_type' => $activityType,
            'description' => $description,
            'old_value' => $oldValue,
            'new_value' => $newValue,
            'performed_by_type' => $performedByType,
            'performed_by_id' => $performedById,
            'metadata' => $metadata,
        ]);
    }
    
    /**
     * Log application created
     */
    public function logCreated(JobApplication $application): void
    {
        $this->log(
            $application->id,
            ApplicationActivity::TYPE_CREATED,
            "Đơn ứng tuyển được tạo bởi {$application->applicant_name}",
            null,
            null,
            [
                'email' => $application->applicant_email,
                'phone' => $application->applicant_phone,
            ]
        );
    }
    
    /**
     * Log status changed
     */
    public function logStatusChanged(int $applicationId, string $oldStatus, string $newStatus): void
    {
        $statusLabels = [
            'pending' => 'Chờ xử lý',
            'reviewed' => 'Đã xem',
            'shortlisted' => 'Lọt vòng',
            'rejected' => 'Từ chối',
            'accepted' => 'Chấp nhận',
        ];
        
        $this->log(
            $applicationId,
            ApplicationActivity::TYPE_STATUS_CHANGED,
            "Trạng thái thay đổi từ '{$statusLabels[$oldStatus]}' sang '{$statusLabels[$newStatus]}'",
            $oldStatus,
            $newStatus
        );
    }
    
    /**
     * Log note added/updated
     */
    public function logNoteChanged(int $applicationId, ?string $oldNote, ?string $newNote): void
    {
        if (empty($oldNote) && !empty($newNote)) {
            $this->log(
                $applicationId,
                ApplicationActivity::TYPE_NOTE_ADDED,
                "Ghi chú được thêm"
            );
        } elseif (!empty($oldNote) && !empty($newNote)) {
            $this->log(
                $applicationId,
                ApplicationActivity::TYPE_NOTE_UPDATED,
                "Ghi chú được cập nhật"
            );
        }
    }
    
    /**
     * Log email sent
     */
    public function logEmailSent(int $applicationId, string $emailType, string $recipient): void
    {
        $this->log(
            $applicationId,
            ApplicationActivity::TYPE_EMAIL_SENT,
            "Email '{$emailType}' được gửi đến {$recipient}",
            null,
            null,
            ['email_type' => $emailType, 'recipient' => $recipient]
        );
    }
    
    /**
     * Log CV downloaded
     */
    public function logCVDownloaded(int $applicationId): void
    {
        $this->log(
            $applicationId,
            ApplicationActivity::TYPE_CV_DOWNLOADED,
            "CV được tải xuống"
        );
    }
    
    /**
     * Log application viewed
     */
    public function logViewed(int $applicationId): void
    {
        $this->log(
            $applicationId,
            ApplicationActivity::TYPE_VIEWED,
            "Đơn ứng tuyển được xem"
        );
    }
    
    /**
     * Get activities for application
     */
    public function getActivities(int $applicationId, int $limit = 50)
    {
        return ApplicationActivity::where('application_id', $applicationId)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }
}
