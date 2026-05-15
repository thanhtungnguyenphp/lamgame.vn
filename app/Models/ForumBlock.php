<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ForumBlock extends Model
{
    public $timestamps = false;
    protected $fillable = ['blocker_id', 'blocked_id', 'created_at'];
    protected $casts = ['created_at' => 'datetime'];

    public static function isBlocked(int $userId, int $targetId): bool
    {
        return static::where('blocker_id', $targetId)->where('blocked_id', $userId)->exists();
    }
}
