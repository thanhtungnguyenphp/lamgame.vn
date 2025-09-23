<?php

namespace Webkul\Menu\Repositories;

use Illuminate\Support\Facades\Cache;
use Webkul\Menu\Models\Menu;
use Webkul\Menu\Models\MenuItem;

class FrontendMenuRepository
{
    /**
     * Get all menus for a specific channel
     */
    public function getMenusForChannel($channelId)
    {
        return Menu::where('channel_id', $channelId)
            ->with(['menuItems' => function($query) {
                $query->whereNull('parent_id')
                    ->orderBy('sort_order')
                    ->with(['children' => function($childQuery) {
                        $childQuery->orderBy('sort_order')
                            ->with(['children' => function($grandChildQuery) {
                                $grandChildQuery->orderBy('sort_order');
                            }]);
                    }]);
            }])
            ->orderBy('name')
            ->get();
    }

    /**
     * Get menu by name and channel
     */
    public function getMenuByName($name, $channelId)
    {
        return Menu::where('name', $name)
            ->where('channel_id', $channelId)
            ->with(['menuItems' => function($query) {
                $query->whereNull('parent_id')
                    ->orderBy('sort_order')
                    ->with(['children' => function($childQuery) {
                        $childQuery->orderBy('sort_order')
                            ->with('children.children'); // Support up to 3 levels
                    }]);
            }])
            ->first();
    }

    /**
     * Render menu HTML
     */
    public function renderMenu($menuName = null, $channelId = null, $options = [])
    {
        $channelId = $channelId ?? $this->getCurrentChannelId();
        
        $menu = $menuName 
            ? $this->getMenuByName($menuName, $channelId)
            : $this->getMenusForChannel($channelId)->first();

        if (!$menu) {
            return '';
        }

        return $this->renderMenuItems($menu->menuItems, $options);
    }

    /**
     * Render menu items recursively
     */
    public function renderMenuItems($items, $options = [])
    {
        if (!$items || $items->isEmpty()) {
            return '';
        }

        $cssClass = $options['class'] ?? 'nav';
        $containerTag = $options['container'] ?? 'nav';
        $listTag = $options['list'] ?? 'ul';
        $itemTag = $options['item'] ?? 'li';
        $linkTag = $options['link'] ?? 'a';
        
        $html = "<{$listTag} class=\"{$cssClass}\">";

        foreach ($items as $item) {
            $html .= $this->renderMenuItem($item, $options, $itemTag, $linkTag);
        }

        $html .= "</{$listTag}>";

        if ($containerTag) {
            $html = "<{$containerTag}>{$html}</{$containerTag}>";
        }

        return $html;
    }

    /**
     * Render individual menu item
     */
    public function renderMenuItem($item, $options = [], $itemTag = 'li', $linkTag = 'a')
    {
        $hasChildren = $item->children && $item->children->count() > 0;
        $itemClass = ($options['item_class'] ?? '') . ($hasChildren ? ' has-dropdown' : '');
        $linkClass = $options['link_class'] ?? '';
        
        // Handle URL - check if it's a scroll anchor or regular link
        $url = $item->url;
        $onclick = '';
        
        if (strpos($url, '#') === 0) {
            // It's an anchor link - use JavaScript scroll
            $onclick = "onclick=\"scrollToSection('{$url}')\"";
            $url = '#';
        }

        $html = "<{$itemTag}" . ($itemClass ? " class=\"{$itemClass}\"" : '') . ">";
        
        // Add icon if present
        $iconHtml = '';
        if ($item->icon) {
            $iconHtml = "<i class=\"{$item->icon}\"></i> ";
        }

        $html .= "<{$linkTag} href=\"{$url}\" target=\"{$item->target}\" {$onclick}" 
               . ($linkClass ? " class=\"{$linkClass}\"" : '') . ">"
               . $iconHtml . $item->title . "</{$linkTag}>";

        // Render children if exist
        if ($hasChildren) {
            $childOptions = array_merge($options, [
                'class' => $options['submenu_class'] ?? 'dropdown-menu',
                'container' => null // No container for submenus
            ]);
            $html .= $this->renderMenuItems($item->children, $childOptions);
        }

        $html .= "</{$itemTag}>";

        return $html;
    }

    /**
     * Render mobile menu
     */
    public function renderMobileMenu($menuName = null, $channelId = null)
    {
        $options = [
            'class' => 'mobile-nav',
            'item_class' => 'mobile-nav-item',
            'link_class' => 'mobile-nav-link',
            'submenu_class' => 'mobile-submenu'
        ];

        return $this->renderMenu($menuName, $channelId, $options);
    }

    /**
     * Get breadcrumb for current URL
     */
    public function getBreadcrumb($url, $channelId = null)
    {
        $channelId = $channelId ?? $this->getCurrentChannelId();
        
        $menuItem = MenuItem::whereHas('menu', function($query) use ($channelId) {
                $query->where('channel_id', $channelId);
            })
            ->where('url', $url)
            ->with('parent.parent.parent') // Support up to 3 levels
            ->first();

        if (!$menuItem) {
            return [];
        }

        return $this->buildBreadcrumb($menuItem);
    }

    /**
     * Build breadcrumb array
     */
    protected function buildBreadcrumb($menuItem, $breadcrumb = [])
    {
        array_unshift($breadcrumb, [
            'title' => $menuItem->title,
            'url' => $menuItem->url
        ]);

        if ($menuItem->parent) {
            return $this->buildBreadcrumb($menuItem->parent, $breadcrumb);
        }

        return $breadcrumb;
    }

    /**
     * Get current channel ID
     */
    protected function getCurrentChannelId()
    {
        // Try to get from current context (Bagisto way)
        if (function_exists('core') && core()->getCurrentChannel()) {
            return core()->getCurrentChannel()->id;
        }
        
        // Fallback to default channel
        return 1;
    }

    /**
     * Clear menu cache
     */
    public function clearCache($channelId = null)
    {
        if ($channelId) {
            Cache::forget("frontend_menus_{$channelId}");
        } else {
            // Clear cache for all channels
            $channels = \DB::table('channels')->pluck('id');
            foreach ($channels as $id) {
                Cache::forget("frontend_menus_{$id}");
            }
        }
    }

    /**
     * Get menu structure as array (for API or JSON responses)
     */
    public function getMenuAsArray($menuName, $channelId = null)
    {
        $channelId = $channelId ?? $this->getCurrentChannelId();
        $menu = $this->getMenuByName($menuName, $channelId);

        if (!$menu) {
            return [];
        }

        return $this->menuItemsToArray($menu->menuItems);
    }

    /**
     * Convert menu items to array
     */
    protected function menuItemsToArray($items)
    {
        return $items->map(function($item) {
            return [
                'id' => $item->id,
                'title' => $item->title,
                'url' => $item->url,
                'target' => $item->target,
                'icon' => $item->icon,
                'sort_order' => $item->sort_order,
                'children' => $item->children ? $this->menuItemsToArray($item->children) : []
            ];
        })->toArray();
    }
}
