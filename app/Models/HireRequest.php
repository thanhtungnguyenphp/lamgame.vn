<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HireRequest extends Model
{
    protected $fillable = [
        'name', 'email', 'phone', 'company', 'project_type',
        'budget_range', 'description', 'status', 'admin_notes',
    ];
}
