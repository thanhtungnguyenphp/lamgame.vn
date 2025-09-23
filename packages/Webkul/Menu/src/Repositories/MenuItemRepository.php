<?php

namespace Webkul\Menu\Repositories;

use Webkul\Core\Eloquent\Repository;
use Webkul\Menu\Models\MenuItem;

class MenuItemRepository extends Repository
{
    public function model()
    {
        return MenuItem::class;
    }

    /**
     * Get the last sort order for a specific menu and parent.
     */
    public function getLastSortOrder($menuId, $parentId = null)
    {
        $query = $this->model->where('menu_id', $menuId);
        
        if ($parentId) {
            $query->where('parent_id', $parentId);
        } else {
            $query->whereNull('parent_id');
        }
        
        return $query->max('sort_order') ?: 0;
    }

    /**
     * Get all menu items for a specific menu ordered by sort_order.
     */
    public function getMenuItems($menuId)
    {
        return $this->model->where('menu_id', $menuId)
            ->with(['children' => function($query) {
                $query->orderBy('sort_order');
            }])
            ->whereNull('parent_id')
            ->orderBy('sort_order')
            ->get();
    }

    /**
     * Get all menu items for a specific menu (including children) as flat array.
     */
    public function getAllMenuItemsFlat($menuId)
    {
        return $this->model->where('menu_id', $menuId)
            ->orderBy('sort_order')
            ->get();
    }

    /**
     * Update multiple menu items' order and parent relationships.
     */
    public function updateOrder(array $items)
    {
        foreach ($items as $item) {
            // Handle empty parent_id (convert empty string to null)
            $parentId = $item['parent_id'] ?? null;
            if ($parentId === '' || $parentId === 'null') {
                $parentId = null;
            }
            
            $this->model->where('id', $item['id'])->update([
                'parent_id' => $parentId,
                'sort_order' => $item['sort_order']
            ]);
        }
    }

    /**
     * Get menu items with their full hierarchy path.
     */
    public function getMenuItemsWithPath($menuId)
    {
        $items = $this->getMenuItems($menuId);
        $result = [];
        
        foreach ($items as $item) {
            $result[] = $item;
            $this->addChildrenWithPath($item->children, $result, 1);
        }
        
        return collect($result);
    }

    /**
     * Recursively add children items with indentation level.
     */
    private function addChildrenWithPath($children, &$result, $level)
    {
        foreach ($children as $child) {
            $child->level = $level;
            $child->indent = str_repeat('— ', $level);
            $result[] = $child;
            
            if ($child->children->count() > 0) {
                $this->addChildrenWithPath($child->children, $result, $level + 1);
            }
        }
    }

    /**
     * Find menu item or fail with relationships.
     */
    public function findOrFailWithRelations($id)
    {
        return $this->model->with(['menu', 'parent', 'children'])->findOrFail($id);
    }
} 
