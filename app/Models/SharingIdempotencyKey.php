<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SharingIdempotencyKey extends Model
{
    protected $fillable = ['firebase_uid', 'route', 'key_hash', 'payload_hash', 'status_code', 'response_body', 'expires_at'];

    protected $casts = ['response_body' => 'array', 'expires_at' => 'datetime'];
}
