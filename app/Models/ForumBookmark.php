<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ForumBookmark extends Model
{
    protected $fillable = ['customer_id', 'post_id'];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(\Webkul\Customer\Models\CustomerProxy::modelClass(), 'customer_id');
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(ForumPost::class, 'post_id');
    }
}
