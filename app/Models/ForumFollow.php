<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ForumFollow extends Model
{
    public $timestamps = false;
    protected $fillable = ['follower_id', 'followable_type', 'followable_id', 'created_at'];
    protected $casts = ['created_at' => 'datetime'];

    public static function isFollowing(int $userId, string $type, string $id): bool
    {
        return static::where('follower_id', $userId)
            ->where('followable_type', $type)
            ->where('followable_id', $id)
            ->exists();
    }
}
