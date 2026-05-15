<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ForumPollOption extends Model
{
    public $timestamps = false;
    protected $fillable = ['forum_poll_id', 'text', 'vote_count', 'sort_order'];

    public function poll() { return $this->belongsTo(ForumPoll::class, 'forum_poll_id'); }
}
