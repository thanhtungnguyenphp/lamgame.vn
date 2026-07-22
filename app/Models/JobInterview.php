<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobInterview extends Model
{
    protected $fillable = [
        'application_id', 'employer_id', 'candidate_id',
        'scheduled_at', 'duration_minutes', 'type',
        'meeting_url', 'location', 'notes', 'status',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'duration_minutes' => 'integer',
    ];

    public function application(): BelongsTo
    {
        return $this->belongsTo(JobApplication::class, 'application_id');
    }

    public function employer(): BelongsTo
    {
        return $this->belongsTo(\Webkul\Customer\Models\Customer::class, 'employer_id');
    }

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(\Webkul\Customer\Models\Customer::class, 'candidate_id');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('scheduled_at', '>', now())
            ->whereIn('status', ['proposed', 'confirmed']);
    }

    public function scopeForUser($query, int $userId)
    {
        return $query->where(function ($q) use ($userId) {
            $q->where('employer_id', $userId)->orWhere('candidate_id', $userId);
        });
    }

    public function getEndTimeAttribute()
    {
        return $this->scheduled_at->addMinutes($this->duration_minutes);
    }

    /**
     * Generate .ics calendar content
     */
    public function toIcs(): string
    {
        $start = $this->scheduled_at->format('Ymd\THis\Z');
        $end = $this->end_time->format('Ymd\THis\Z');
        $summary = 'Interview: ' . ($this->application->jobPosting->title ?? 'Job Interview');
        $location = $this->type === 'online' ? ($this->meeting_url ?? 'Online') : ($this->location ?? 'TBD');

        return "BEGIN:VCALENDAR\r\nVERSION:2.0\r\nBEGIN:VEVENT\r\nDTSTART:{$start}\r\nDTEND:{$end}\r\nSUMMARY:{$summary}\r\nLOCATION:{$location}\r\nDESCRIPTION:{$this->notes}\r\nEND:VEVENT\r\nEND:VCALENDAR";
    }
}
