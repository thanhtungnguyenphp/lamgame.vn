<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ForumPoll extends Model
{
    protected $fillable = ['forum_post_id', 'question', 'allow_multiple', 'is_anonymous', 'expires_at', 'total_votes'];
    protected $casts = ['allow_multiple' => 'boolean', 'is_anonymous' => 'boolean', 'expires_at' => 'datetime'];

    public function post() { return $this->belongsTo(ForumPost::class, 'forum_post_id'); }
    public function options() { return $this->hasMany(ForumPollOption::class); }
    public function votes() { return $this->hasMany(ForumPollVote::class); }

    public function isExpired(): bool { return $this->expires_at && $this->expires_at->isPast(); }
}
