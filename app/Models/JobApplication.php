<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Webkul\Product\Models\Product;
use Webkul\Customer\Models\Customer;
use Carbon\Carbon;

class JobApplication extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'job_id',
        'applicant_user_id',
        'applicant_name',
        'applicant_email',
        'applicant_phone',
        'cover_letter',
        'resume_file_path',
        'additional_info',
        'status',
        'employer_notes',
        'applied_at',
    ];
    
    protected $casts = [
        'additional_info' => 'array',
        'applied_at' => 'datetime',
    ];
    
    protected $dates = [
        'applied_at',
        'created_at',
        'updated_at',
    ];
    
    /**
     * Relationship with job (Product model)
     */
    public function job(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'job_id');
    }
    
    /**
     * Relationship with applicant (Customer model)
     */
    public function applicant(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'applicant_user_id');
    }
    
    /**
     * Scope for filtering by status
     */
    public function scopeWithStatus($query, $status)
    {
        return $query->where('status', $status);
    }
    
    /**
     * Scope for filtering by job
     */
    public function scopeForJob($query, $jobId)
    {
        return $query->where('job_id', $jobId);
    }
    
    /**
     * Scope for filtering by applicant
     */
    public function scopeForApplicant($query, $userId)
    {
        return $query->where('applicant_user_id', $userId);
    }
    
    /**
     * Scope for recent applications
     */
    public function scopeRecent($query, $limit = 10)
    {
        return $query->orderBy('applied_at', 'desc')->limit($limit);
    }
    
    /**
     * Get the employer of the job (from products)
     */
    public function getJobEmployerAttribute()
    {
        // This would need to be implemented based on how you store employer info in products
        // For now, we'll extract from job attributes
        return $this->job ? $this->job->getAttribute('name') : null;
    }
    
    /**
     * Boot model events
     */
    protected static function boot()
    {
        parent::boot();
        
        // Set applied_at when creating
        static::creating(function ($model) {
            if (!$model->applied_at) {
                $model->applied_at = Carbon::now();
            }
        });
    }
}
