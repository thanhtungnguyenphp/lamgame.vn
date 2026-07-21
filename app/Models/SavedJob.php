<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SavedJob extends Model
{
    public $timestamps = false;

    protected $fillable = ['user_id', 'job_posting_id', 'saved_at'];

    protected $casts = [
        'saved_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(\Webkul\Customer\Models\Customer::class, 'user_id');
    }

    public function jobPosting(): BelongsTo
    {
        return $this->belongsTo(JobPosting::class, 'job_posting_id');
    }
}
