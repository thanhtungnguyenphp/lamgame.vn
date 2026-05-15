<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductLicense extends Model
{
    protected $fillable = ['product_id', 'license_type_id', 'price', 'is_active'];
    protected $casts = ['price' => 'decimal:2', 'is_active' => 'boolean'];

    public function licenseType() { return $this->belongsTo(LicenseType::class); }
}
