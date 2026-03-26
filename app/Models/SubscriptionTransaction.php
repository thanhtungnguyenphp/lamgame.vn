<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionTransaction extends Model
{
    protected $fillable = [
        'subscription_id', 'paypal_transaction_id',
        'amount', 'currency', 'status', 'paypal_data',
    ];

    protected $casts = [
        'amount'      => 'decimal:2',
        'paypal_data' => 'array',
    ];

    public function subscription()
    {
        return $this->belongsTo(UserSubscription::class, 'subscription_id');
    }
}
