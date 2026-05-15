<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ForumMessage extends Model
{
    public $timestamps = false;
    protected $fillable = ['conversation_id', 'sender_id', 'content', 'read_at', 'created_at'];
    protected $casts = ['read_at' => 'datetime', 'created_at' => 'datetime'];
}
