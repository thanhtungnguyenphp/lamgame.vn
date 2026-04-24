<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class JobPosting extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title', 'slug', 'description', 'short_description',
        'job_type', 'experience_level', 'salary_range', 'salary_min', 'salary_max', 'salary_currency',
        'location', 'is_remote', 'education_level', 'english_level', 'company_size',
        'company_id', 'company_name', 'company_logo',
        'contact_email', 'contact_phone', 'application_method', 'application_url',
        'status', 'is_featured', 'is_urgent', 'application_deadline', 'published_at',
        'meta_title', 'meta_description', 'meta_keywords',
        'view_count', 'application_count', 'click_count',
        'created_by',
    ];

    protected $casts = [
        'is_featured'          => 'boolean',
        'is_urgent'            => 'boolean',
        'is_remote'            => 'boolean',
        'salary_min'           => 'decimal:2',
        'salary_max'           => 'decimal:2',
        'application_deadline' => 'date',
        'published_at'         => 'datetime',
        'view_count'           => 'integer',
        'application_count'    => 'integer',
        'click_count'          => 'integer',
    ];

    // Auto-generate slug
    protected static function booted(): void
    {
        static::creating(function (self $job) {
            if (empty($job->slug)) {
                $job->slug = Str::slug($job->title) . '-' . Str::random(6);
            }
        });
    }

    // Relationships
    public function skills(): HasMany
    {
        return $this->hasMany(JobPostingSkill::class);
    }

    public function benefits(): HasMany
    {
        return $this->hasMany(JobPostingBenefit::class);
    }

    public function applications(): HasMany
    {
        return $this->hasMany(JobApplication::class, 'job_posting_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(\Webkul\User\Models\Admin::class, 'created_by');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
            ->where(function ($q) {
                $q->whereNull('application_deadline')
                  ->orWhere('application_deadline', '>=', now());
            });
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('job_type', $type);
    }

    public function scopeByLocation($query, string $location)
    {
        return $query->where('location', 'like', "%{$location}%");
    }

    public function scopeSearch($query, string $keyword)
    {
        return $query->where(function ($q) use ($keyword) {
            $q->where('title', 'like', "%{$keyword}%")
              ->orWhere('description', 'like', "%{$keyword}%")
              ->orWhere('company_name', 'like', "%{$keyword}%");
        });
    }

    // Helpers
    public function isExpired(): bool
    {
        return $this->application_deadline && $this->application_deadline->isPast();
    }

    public function daysRemaining(): ?int
    {
        return $this->application_deadline ? (int) now()->diffInDays($this->application_deadline, false) : null;
    }

    public function incrementViews(): void
    {
        $this->increment('view_count');
    }

    public function getUrlAttribute(): string
    {
        return url("/viec-lam/{$this->slug}");
    }
}
