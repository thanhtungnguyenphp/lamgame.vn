<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class JobImportLog extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'job_import_logs';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'import_id',
        'user_id',
        'filename',
        'total_rows',
        'imported_rows',
        'skipped_rows',
        'failed_rows',
        'errors',
        'status',
        'started_at',
        'completed_at',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'errors' => 'json',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'total_rows' => 'integer',
        'imported_rows' => 'integer',
        'skipped_rows' => 'integer',
        'failed_rows' => 'integer',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [];

    /**
     * Define the relationship with the Admin/User model.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'user_id');
    }

    /**
     * Get import success rate as percentage.
     */
    public function getSuccessRateAttribute(): float
    {
        if ($this->total_rows == 0) {
            return 0;
        }

        return round(($this->imported_rows / $this->total_rows) * 100, 2);
    }

    /**
     * Get import duration in seconds.
     */
    public function getDurationAttribute(): ?int
    {
        if (!$this->started_at || !$this->completed_at) {
            return null;
        }

        return $this->completed_at->diffInSeconds($this->started_at);
    }

    /**
     * Get formatted duration string.
     */
    public function getFormattedDurationAttribute(): string
    {
        $duration = $this->duration;
        
        if ($duration === null) {
            return 'N/A';
        }

        if ($duration < 60) {
            return $duration . 's';
        }

        $minutes = floor($duration / 60);
        $seconds = $duration % 60;

        return $minutes . 'm ' . $seconds . 's';
    }

    /**
     * Check if import was successful (no failed rows).
     */
    public function isSuccessful(): bool
    {
        return $this->failed_rows == 0 && $this->imported_rows > 0;
    }

    /**
     * Check if import had partial success.
     */
    public function isPartialSuccess(): bool
    {
        return $this->imported_rows > 0 && $this->failed_rows > 0;
    }

    /**
     * Check if import completely failed.
     */
    public function isFailed(): bool
    {
        return $this->imported_rows == 0 && $this->total_rows > 0;
    }

    /**
     * Get import status string.
     */
    public function getStatusTextAttribute(): string
    {
        if ($this->isSuccessful()) {
            return 'Success';
        }

        if ($this->isPartialSuccess()) {
            return 'Partial Success';
        }

        if ($this->isFailed()) {
            return 'Failed';
        }

        return 'Unknown';
    }

    /**
     * Scope for filtering by date range.
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Scope for filtering by success status.
     */
    public function scopeSuccessful($query)
    {
        return $query->where('failed_rows', 0)->where('imported_rows', '>', 0);
    }

    /**
     * Scope for filtering by failed status.
     */
    public function scopeFailed($query)
    {
        return $query->where('imported_rows', 0)->where('total_rows', '>', 0);
    }

    /**
     * Scope for filtering by partial success.
     */
    public function scopePartialSuccess($query)
    {
        return $query->where('imported_rows', '>', 0)->where('failed_rows', '>', 0);
    }

    /**
     * Get summary statistics for imports.
     */
    public static function getSummaryStats($userId = null, $dateFrom = null, $dateTo = null): array
    {
        $query = static::query();

        if ($userId) {
            $query->where('user_id', $userId);
        }

        if ($dateFrom && $dateTo) {
            $query->dateRange($dateFrom, $dateTo);
        }

        $stats = $query->selectRaw('
            COUNT(*) as total_imports,
            SUM(total_rows) as total_rows_processed,
            SUM(imported_rows) as total_imported,
            SUM(skipped_rows) as total_skipped,
            SUM(failed_rows) as total_failed,
            AVG(CASE WHEN total_rows > 0 THEN (imported_rows / total_rows * 100) ELSE 0 END) as avg_success_rate
        ')->first();

        $successCount = $query->successful()->count();
        $failedCount = $query->failed()->count();
        $partialCount = $query->partialSuccess()->count();

        return [
            'total_imports' => $stats->total_imports ?? 0,
            'successful_imports' => $successCount,
            'failed_imports' => $failedCount,
            'partial_imports' => $partialCount,
            'total_rows_processed' => $stats->total_rows_processed ?? 0,
            'total_imported' => $stats->total_imported ?? 0,
            'total_skipped' => $stats->total_skipped ?? 0,
            'total_failed' => $stats->total_failed ?? 0,
            'average_success_rate' => round($stats->avg_success_rate ?? 0, 2),
        ];
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->started_at) {
                $model->started_at = now();
            }
        });

        static::updating(function ($model) {
            if ($model->isDirty(['imported_rows', 'skipped_rows', 'failed_rows']) && !$model->completed_at) {
                $model->completed_at = now();
            }
        });
    }
}