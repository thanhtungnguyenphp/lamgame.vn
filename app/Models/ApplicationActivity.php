<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApplicationActivity extends Model
{
    const UPDATED_AT = null; // Only created_at
    
    protected $fillable = [
        'application_id',
        'activity_type',
        'description',
        'old_value',
        'new_value',
        'performed_by_type',
        'performed_by_id',
        'metadata',
    ];
    
    protected $casts = [
        'metadata' => 'array',
        'created_at' => 'datetime',
    ];
    
    // Activity types
    const TYPE_CREATED = 'created';
    const TYPE_STATUS_CHANGED = 'status_changed';
    const TYPE_NOTE_ADDED = 'note_added';
    const TYPE_NOTE_UPDATED = 'note_updated';
    const TYPE_EMAIL_SENT = 'email_sent';
    const TYPE_CV_DOWNLOADED = 'cv_downloaded';
    const TYPE_VIEWED = 'viewed';
    
    public function application(): BelongsTo
    {
        return $this->belongsTo(JobApplication::class, 'application_id');
    }
    
    public function getPerformedByNameAttribute(): string
    {
        if ($this->performed_by_type === 'system') {
            return 'Hệ thống';
        }
        
        if ($this->performed_by_type === 'admin' && $this->performed_by_id) {
            $admin = \DB::table('admins')->find($this->performed_by_id);
            return $admin ? $admin->name : 'Admin';
        }
        
        if ($this->performed_by_type === 'applicant' && $this->performed_by_id) {
            $customer = \DB::table('customers')->find($this->performed_by_id);
            return $customer ? $customer->first_name . ' ' . $customer->last_name : 'Ứng viên';
        }
        
        return 'Unknown';
    }
}
