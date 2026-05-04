<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SourceGameReview extends Model
{
    protected $fillable = [
        'product_id', 'customer_id', 'rating', 'title', 'content',
        'pros', 'cons', 'status', 'is_verified_purchase', 'helpful_count',
    ];

    protected $casts = [
        'is_verified_purchase' => 'boolean',
        'rating' => 'integer',
    ];

    // Relationships
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function customer()
    {
        return $this->belongsTo(\Webkul\Customer\Models\Customer::class);
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeByProduct($query, int $productId)
    {
        return $query->where('product_id', $productId);
    }
}
