<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class LicenseKey extends Model
{
    protected $fillable = ['order_id', 'product_id', 'license_type_id', 'key', 'customer_id', 'activated_at', 'expires_at', 'transferred_to'];
    protected $casts = ['activated_at' => 'datetime', 'expires_at' => 'datetime'];

    public function licenseType() { return $this->belongsTo(LicenseType::class); }

    public static function generate(int $productId, int $licenseTypeId, int $customerId, ?int $orderId = null): self
    {
        return static::create([
            'product_id' => $productId,
            'license_type_id' => $licenseTypeId,
            'customer_id' => $customerId,
            'order_id' => $orderId,
            'key' => strtoupper(Str::random(8) . '-' . Str::random(8) . '-' . Str::random(8) . '-' . Str::random(8)),
            'activated_at' => now(),
        ]);
    }
}
