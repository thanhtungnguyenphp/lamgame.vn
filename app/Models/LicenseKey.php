<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class LicenseKey extends Model
{
    protected $fillable = ['order_id', 'product_id', 'license_type_id', 'key', 'customer_id', 'activated_at', 'expires_at', 'transferred_to'];
    protected $casts = ['activated_at' => 'datetime', 'expires_at' => 'datetime'];

    public function licenseType() { return $this->belongsTo(LicenseType::class); }

    public function product() { return $this->belongsTo(\Webkul\Product\Models\Product::class, 'product_id'); }

    public function customer() { return $this->belongsTo(\Webkul\Customer\Models\Customer::class, 'customer_id'); }

    public static function generate(int $productId, int $licenseTypeId, int $customerId, ?int $orderId = null): self
    {
        // Sinh key duy nhất, thử lại nếu trùng (cột key là unique)
        do {
            $key = strtoupper(Str::random(8) . '-' . Str::random(8) . '-' . Str::random(8) . '-' . Str::random(8));
        } while (static::where('key', $key)->exists());

        return static::create([
            'product_id'      => $productId,
            'license_type_id' => $licenseTypeId,
            'customer_id'     => $customerId,
            'order_id'        => $orderId,
            'key'             => $key,
            'activated_at'    => now(),
        ]);
    }
}
