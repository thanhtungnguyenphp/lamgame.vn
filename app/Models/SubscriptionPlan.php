<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    protected $fillable = [
        'slug', 'name', 'price', 'currency', 'billing_interval',
        'paypal_plan_id', 'features', 'is_active', 'sort_order',
    ];

    protected $casts = [
        'price'     => 'decimal:2',
        'features'  => 'array',
        'is_active' => 'boolean',
    ];

    public function getFeatureLimit(string $feature): int
    {
        return $this->features[$feature] ?? 0;
    }

    public function hasFeature(string $feature): bool
    {
        $val = $this->features[$feature] ?? false;
        return $val === true || $val === -1 || (is_int($val) && $val > 0);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }
}
