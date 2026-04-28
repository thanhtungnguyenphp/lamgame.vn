<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ForumReputationLog extends Model
{
    protected $fillable = ['customer_id', 'points', 'action', 'reference_type', 'reference_id'];

    protected $casts = ['points' => 'integer'];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(\Webkul\Customer\Models\CustomerProxy::modelClass(), 'customer_id');
    }

    public function reference(): MorphTo
    {
        return $this->morphTo();
    }
}
