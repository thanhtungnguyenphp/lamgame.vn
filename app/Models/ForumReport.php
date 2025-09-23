<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Webkul\Customer\Models\CustomerProxy;
use Webkul\User\Models\AdminProxy;

class ForumReport extends Model
{
    use HasFactory;

    protected $table = 'forum_reports';
    
    protected $fillable = [
        'reporter_id',
        'reportable_type',
        'reportable_id', 
        'reason',
        'description',
        'status',
        'reviewed_by',
        'admin_notes',
        'reviewed_at',
        'ip_address'
    ];
    
    protected $casts = [
        'reviewed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
    
    public function reporter(): BelongsTo
    {
        return $this->belongsTo(CustomerProxy::modelClass(), 'reporter_id');
    }
    
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(AdminProxy::modelClass(), 'reviewed_by');
    }
    
    public function reportable(): MorphTo
    {
        return $this->morphTo();
    }
    
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
    
    public function scopeReviewed($query)
    {
        return $query->whereIn('status', ['reviewed', 'resolved', 'dismissed']);
    }
    
    public function markAsReviewed($admin, string $notes = null): void
    {
        $this->update([
            'status' => 'reviewed',
            'reviewed_by' => $admin->id,
            'admin_notes' => $notes,
            'reviewed_at' => now()
        ]);
    }
}
