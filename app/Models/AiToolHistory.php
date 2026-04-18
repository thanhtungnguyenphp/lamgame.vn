<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiToolHistory extends Model
{
    protected $fillable = [
        'customer_id', 'tool_type', 'model_used', 'prompt', 'response',
        'metadata', 'tokens_input', 'tokens_output', 'cost_usd',
        'duration_ms', 'status', 'error_message',
    ];

    protected $casts = [
        'metadata' => 'array',
        'cost_usd' => 'decimal:6',
    ];

    public function customer()
    {
        return $this->belongsTo(\Webkul\Customer\Models\Customer::class, 'customer_id');
    }

    public function scopeForCustomer($query, int $customerId)
    {
        return $query->where('customer_id', $customerId);
    }

    public function scopeOfType($query, string $type)
    {
        return $query->where('tool_type', $type);
    }
}
