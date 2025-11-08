<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Company extends Model
{
    protected $fillable = [
        'name',
        'description',
        'logo',
        'website',
        'email',
        'phone',
        'address',
        'employee_count',
        'founded_year',
        'industry',
        'status',
        'created_by_admin_id'
    ];

    protected $casts = [
        'status' => 'boolean',
        'founded_year' => 'integer',
        'employee_count' => 'integer'
    ];

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(\Webkul\User\Models\Admin::class, 'created_by_admin_id');
    }

    public function admins(): HasMany
    {
        return $this->hasMany(\Webkul\User\Models\Admin::class, 'company_id');
    }

    public function products(): HasMany
    {
        return $this->hasMany(\Webkul\Product\Models\Product::class, 'company_id');
    }
}
