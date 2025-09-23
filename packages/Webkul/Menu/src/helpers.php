<?php

/**
 * Global Menu Helper Functions
 * 
 * These functions provide a convenient way to access menu functionality
 * from anywhere in the application.
 */

if (!function_exists('menu')) {
    /**
     * Get menu helper instance.
     */
    function menu()
    {
        return app(\Webkul\Menu\Helpers\MenuHelper::class);
    }
}

if (!function_exists('render_menu')) {
    /**
     * Render a menu by name.
     */
    function render_menu($menuName = null, $options = [])
    {
        return menu()->renderMenu($menuName, $options);
    }
}

if (!function_exists('get_menu')) {
    /**
     * Get menu data as array.
     */
    function get_menu($menuName, $channelId = null)
    {
        return menu()->getMenuAsArray($menuName, $channelId);
    }
}

if (!function_exists('get_primary_menu')) {
    /**
     * Get primary navigation menu.
     */
    function get_primary_menu($channelId = null)
    {
        return menu()->getPrimaryMenu($channelId);
    }
}

if (!function_exists('get_header_menu')) {
    /**
     * Get header menu with fallback.
     */
    function get_header_menu($channelId = null)
    {
        return menu()->getHeaderMenu($channelId);
    }
}

if (!function_exists('get_footer_menu')) {
    /**
     * Get footer menu.
     */
    function get_footer_menu($channelId = null)
    {
        return menu()->getFooterMenu($channelId);
    }
}

if (!function_exists('get_mobile_menu')) {
    /**
     * Get mobile menu with fallback.
     */
    function get_mobile_menu($channelId = null)
    {
        return menu()->getMobileMenu($channelId);
    }
}

if (!function_exists('is_menu_item_active')) {
    /**
     * Check if menu item is active.
     */
    function is_menu_item_active($menuItem, $currentUrl = null)
    {
        return menu()->isMenuItemActive($menuItem, $currentUrl);
    }
}

if (!function_exists('get_menu_breadcrumb')) {
    /**
     * Get breadcrumb for current page.
     */
    function get_menu_breadcrumb($url = null, $channelId = null)
    {
        return menu()->getBreadcrumb($url, $channelId);
    }
}

if (!function_exists('get_menu_tree')) {
    /**
     * Get menu tree with active states.
     */
    function get_menu_tree($menuName, $currentUrl = null, $channelId = null)
    {
        return menu()->getMenuTree($menuName, $currentUrl, $channelId);
    }
}

if (!function_exists('get_navigation_structured_data')) {
    /**
     * Get navigation structured data (JSON-LD).
     */
    function get_navigation_structured_data($menuName = null, $channelId = null)
    {
        return menu()->getNavigationStructuredData($menuName, $channelId);
    }
}

if (!function_exists('get_menu_stats')) {
    /**
     * Get menu statistics.
     */
    function get_menu_stats($channelId = null)
    {
        return menu()->getMenuStats($channelId);
    }
}

if (!function_exists('clear_menu_cache')) {
    /**
     * Clear menu cache.
     */
    function clear_menu_cache($channelId = null)
    {
        return menu()->clearCache($channelId);
    }
}

if (!function_exists('warmup_menu_cache')) {
    /**
     * Warm up menu cache.
     */
    function warmup_menu_cache($channelId = null)
    {
        return menu()->warmupCache($channelId);
    }
}
