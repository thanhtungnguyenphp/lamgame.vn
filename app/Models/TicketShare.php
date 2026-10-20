<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketShare extends Model
{
    protected $fillable = [
        'share_id', 'owner_uid', 'recipient_uid', 'recipient_email_hash', 'recipient_email_encrypted', 'recipient_label', 'client_ticket_id',
        'number', 'province', 'province_code', 'region', 'draw_date', 'image_path', 'image_sha256',
        'share_percent', 'message', 'terms_version', 'status', 'result_status', 'result_final',
        'matched_prizes', 'result_version', 'result_source_hash', 'version', 'expires_at', 'accepted_at', 'declined_at',
        'cancelled_at', 'expired_at',
    ];

    protected $casts = [
        'draw_date' => 'date', 'expires_at' => 'datetime', 'accepted_at' => 'datetime',
        'declined_at' => 'datetime', 'cancelled_at' => 'datetime', 'expired_at' => 'datetime',
        'matched_prizes' => 'array', 'result_final' => 'boolean',
    ];
}
