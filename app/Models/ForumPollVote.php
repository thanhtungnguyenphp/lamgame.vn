<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ForumPollVote extends Model
{
    public $timestamps = false;
    protected $fillable = ['forum_poll_id', 'forum_poll_option_id', 'customer_id', 'created_at'];

    public function option() { return $this->belongsTo(ForumPollOption::class, 'forum_poll_option_id'); }
}
