<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HireRequest extends Model
{
    protected $fillable = [
        'name', 'email', 'phone', 'company', 'country', 'project_type',
        'service_package', 'source', 'budget_range', 'description', 'status', 'admin_notes',
    ];
}
