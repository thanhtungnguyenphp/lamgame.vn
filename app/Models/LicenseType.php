<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LicenseType extends Model
{
    protected $fillable = ['name', 'slug', 'description', 'max_projects', 'allows_resale', 'allows_modification'];
    protected $casts = ['allows_resale' => 'boolean', 'allows_modification' => 'boolean'];
}
