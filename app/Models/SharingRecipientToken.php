<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SharingRecipientToken extends Model
{
    protected $fillable = ['token_hash', 'sender_uid', 'recipient_uid', 'email_hash', 'email_encrypted', 'kind', 'display_label', 'expires_at', 'used_at'];

    protected $casts = ['expires_at' => 'datetime', 'used_at' => 'datetime'];
}
