<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobAlert extends Model
{
    protected $fillable = [
        'user_id', 'keywords', 'skills', 'location', 'frequency', 'is_active', 'last_sent_at',
    ];

    protected $casts = [
        'skills'       => 'array',
        'is_active'    => 'boolean',
        'last_sent_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(\Webkul\Customer\Models\Customer::class, 'user_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Check if a JobPosting matches this alert's criteria.
     */
    public function matchesJob(JobPosting $job): bool
    {
        // Keyword match
        if ($this->keywords) {
            $keywords = array_map('trim', explode(',', strtolower($this->keywords)));
            $jobText = strtolower($job->title . ' ' . $job->description);
            $matched = false;
            foreach ($keywords as $kw) {
                if ($kw && str_contains($jobText, $kw)) {
                    $matched = true;
                    break;
                }
            }
            if (!$matched) return false;
        }

        // Skill match
        if ($this->skills && count($this->skills) > 0) {
            $jobSkills = $job->skills->pluck('skill_name')->map(fn($s) => strtolower($s))->toArray();
            $alertSkills = array_map('strtolower', $this->skills);
            if (empty(array_intersect($alertSkills, $jobSkills))) {
                return false;
            }
        }

        // Location match
        if ($this->location) {
            $jobLocation = strtolower($job->location ?? '');
            if (!str_contains($jobLocation, strtolower($this->location)) && !$job->is_remote) {
                return false;
            }
        }

        return true;
    }
}
