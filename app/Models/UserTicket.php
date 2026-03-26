<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserTicket extends Model
{
    protected $fillable = [
        'ticket_id', 'fcm_token', 'numbers', 'region',
        'province_code', 'draw_date', 'status', 'matched_prizes', 'notified_at',
        'firebase_uid', 'client_id',
    ];

    protected $casts = [
        'numbers'         => 'array',
        'matched_prizes'  => 'array',
        'draw_date'       => 'date',
        'notified_at'     => 'datetime',
    ];

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeForDate($query, string $date)
    {
        return $query->where('draw_date', $date);
    }

    public function scopeForRegion($query, string $region)
    {
        return $query->where('region', $region);
    }
}
