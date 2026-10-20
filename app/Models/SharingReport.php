<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SharingReport extends Model
{
    protected $fillable = ['reporter_uid', 'share_id', 'reason', 'moderation_snapshot', 'status'];
    protected $casts = ['moderation_snapshot' => 'array'];
}
