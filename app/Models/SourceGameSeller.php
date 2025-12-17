<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Webkul\Customer\Models\Customer;
use Webkul\Product\Models\Product;

class SourceGameSeller extends Model
{
    protected $fillable = [
        'customer_id',
        'shop_name',
        'shop_slug',
        'shop_description',
        'shop_logo',
        'shop_banner',
        'contact_email',
        'contact_phone',
        'website',
        'business_type',
        'tax_id',
        'bank_name',
        'bank_account',
        'bank_holder',
        'status',
        'verified',
        'verified_at',
    ];

    protected $casts = [
        'verified' => 'boolean',
        'verified_at' => 'datetime',
        'total_revenue' => 'decimal:2',
        'rating_avg' => 'decimal:2',
    ];

    // Relationships
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'company_id', 'id');
    }

    // Status checks
    public function isActive()
    {
        return $this->status === 'active';
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isSuspended()
    {
        return $this->status === 'suspended';
    }

    public function isBanned()
    {
        return $this->status === 'banned';
    }

    public function canUploadProduct()
    {
        return $this->isActive() && $this->verified;
    }

    // Helpers
    public function getLogoUrlAttribute()
    {
        if (!$this->shop_logo) {
            return asset('images/default-shop-logo.png');
        }
        return \Storage::url($this->shop_logo);
    }

    public function getBannerUrlAttribute()
    {
        if (!$this->shop_banner) {
            return asset('images/default-shop-banner.jpg');
        }
        return \Storage::url($this->shop_banner);
    }

    // Auto-generate slug
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($seller) {
            if (empty($seller->shop_slug)) {
                $seller->shop_slug = Str::slug($seller->shop_name);
                
                // Ensure unique slug
                $count = 1;
                while (static::where('shop_slug', $seller->shop_slug)->exists()) {
                    $seller->shop_slug = Str::slug($seller->shop_name) . '-' . $count++;
                }
            }
        });
    }
}
