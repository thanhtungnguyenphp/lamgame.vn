<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SharingBlock extends Model
{
    protected $fillable = ['blocker_uid', 'blocked_uid'];
}
