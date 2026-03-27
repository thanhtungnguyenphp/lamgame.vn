<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class UserCollection extends Model
{
    protected $fillable = ['customer_id', 'name', 'slug', 'description', 'is_public'];

    protected static function booted()
    {
        static::creating(function ($c) {
            $c->slug = $c->slug ?: Str::slug($c->name) . '-' . Str::random(5);
        });
    }

    public function items()
    {
        return $this->hasMany(CollectionItem::class, 'collection_id');
    }
}
