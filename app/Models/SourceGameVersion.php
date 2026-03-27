<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SourceGameVersion extends Model
{
    protected $fillable = [
        'product_id', 'version', 'changelog', 'file_path', 'file_size', 'downloads', 'uploaded_by',
    ];

    public function product()
    {
        return $this->belongsTo(\Webkul\Product\Models\Product::class);
    }
}
