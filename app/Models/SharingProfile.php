<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SharingProfile extends Model
{
    protected $fillable = ['firebase_uid', 'public_id', 'display_name', 'email_hash', 'email_encrypted', 'email_discoverable', 'email_verified_at'];

    protected $casts = ['email_discoverable' => 'boolean', 'email_verified_at' => 'datetime'];
}
