<?php

namespace Webkul\Menu\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = 'menus';
    protected $fillable = ['name', 'channel_id'];

    public function channel()
    {
        return $this->belongsTo(\Webkul\Core\Models\Channel::class, 'channel_id');
    }

    public function menuItems()
    {
        return $this->hasMany(MenuItem::class, 'menu_id')
                    ->with('children')
                    ->whereNull('parent_id')
                    ->orderBy('sort_order');
    }

    public function allMenuItems()
    {
        return $this->hasMany(MenuItem::class, 'menu_id');
    }
}
