<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobCrawlLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'source',
        'source_id',
        'source_url',
        'job_posting_id',
        'status',
        'raw_data',
        'error_message',
        'response_time_ms',
        'created_at',
    ];

    protected $casts = [
        'raw_data'         => 'array',
        'response_time_ms' => 'integer',
        'created_at'       => 'datetime',
    ];

    public function jobPosting(): BelongsTo
    {
        return $this->belongsTo(JobPosting::class);
    }

    public function scopeBySource($query, string $source)
    {
        return $query->where('source', $source);
    }
}
