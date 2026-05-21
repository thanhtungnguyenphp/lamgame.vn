<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerFcmToken extends Model
{
    protected $fillable = ['customer_id', 'token', 'platform'];

    public function customer()
    {
        return $this->belongsTo(\Webkul\Customer\Models\Customer::class);
    }
}
