<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SharingOutboxEvent extends Model
{
    protected $fillable = ['event_id', 'share_id', 'recipient_uid', 'type', 'result_version', 'payload', 'dedup_key', 'status', 'attempts', 'sent_at', 'available_at'];

    protected $casts = ['payload' => 'array', 'sent_at' => 'datetime', 'available_at' => 'datetime'];
}
