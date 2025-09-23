<?php

namespace Webkul\Menu\Repositories;

use Webkul\Core\Eloquent\Repository;
use Webkul\Menu\Models\Menu;
use Illuminate\Support\Facades\DB;


class MenuRepository extends Repository
{
    public function model()
    {
        return Menu::class;
    }

    public function getMenus($perPage, $search = null)
    {
        $query = Menu::with('channel', 'menuItems');
        
        if ($search) {
            $query->where('name', 'LIKE', '%' . $search . '%');
        }
        
        return $query->paginate($perPage);
    }
    
    public function getMenusForChannel($channelId)
    {
        return Menu::with('menuItems')
            ->where('channel_id', $channelId)
            ->orderBy('created_at', 'desc')
            ->get();
    }
    
    public function getMenuByName($name, $channelId)
    {
        return Menu::with('menuItems')
            ->where('name', $name)
            ->where('channel_id', $channelId)
            ->first();
    }
} 
