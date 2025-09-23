<?php

namespace Webkul\Menu\Models;

use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    protected $table = 'menu_items';
    protected $fillable = ['menu_id', 'parent_id', 'title', 'url', 'sort_order', 'target', 'icon'];

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }

    public function parent()
    {
        return $this->belongsTo(MenuItem::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(MenuItem::class, 'parent_id')
                    ->with('children')
                    ->orderBy('sort_order');
    }
}
