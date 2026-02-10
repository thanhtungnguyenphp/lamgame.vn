<?php

namespace App\Models;

use Webkul\Product\Models\Product as BaseProduct;

class Product extends BaseProduct
{
    protected $casts = [
        'pending_review' => 'boolean',
    ];

    // Accessor for flat (single product_flat for current locale/channel)
    public function getFlatAttribute()
    {
        $locale = core()->getCurrentLocale()->code ?? 'vi';

        return $this->product_flats->firstWhere('locale', $locale)
            ?? $this->product_flats->first();
    }

    // Relationships
    public function seller()
    {
        return $this->belongsTo(\App\Models\SourceGameSeller::class, 'seller_id');
    }

    // Status helpers
    public function getStatusLabelAttribute()
    {
        if ($this->pending_review) {
            return 'Chờ duyệt';
        }
        
        return $this->status ? 'Đã duyệt' : 'Nháp';
    }

    public function getStatusBadgeClassAttribute()
    {
        if ($this->pending_review) {
            return 'status-pending';
        }
        
        return $this->status ? 'status-active' : 'status-draft';
    }

    public function isPendingReview()
    {
        return $this->pending_review;
    }

    public function isPublished()
    {
        return $this->status == 1 && !$this->pending_review;
    }

    public function isDraft()
    {
        return $this->status == 0 && !$this->pending_review;
    }
}
