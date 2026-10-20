<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SharingDevice extends Model
{
    protected $fillable = ['installation_id', 'firebase_uid', 'fcm_token', 'platform'];
}
