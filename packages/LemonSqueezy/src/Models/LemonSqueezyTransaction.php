<?php

namespace LemonSqueezy\Models;

use Illuminate\Database\Eloquent\Model;

class LemonSqueezyTransaction extends Model
{
    protected $fillable = [
        'ls_order_id',
        'order_id',
        'cart_id',
        'customer_id',
        'status',
        'amount_usd_cents',
        'amount_vnd',
        'currency',
        'receipt_url',
        'webhook_payload',
    ];

    protected $casts = [
        'webhook_payload' => 'array',
    ];
}
