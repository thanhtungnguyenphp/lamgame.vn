<?php

namespace Webkul\Menu\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Webkul\Menu\Repositories\MenuRepository;
use Webkul\Menu\Repositories\MenuItemRepository;
use Webkul\Menu\Http\ViewComposers\MenuComposer;
use Illuminate\Http\Request;

class MenuItemController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $menuRepository;
    protected $menuItemRepository;

    public function __construct(
        MenuRepository $menuRepository,
        MenuItemRepository $menuItemRepository
    ) {
        $this->menuRepository = $menuRepository;
        $this->menuItemRepository = $menuItemRepository;
    }

    /**
     * Display menu items for a specific menu.
     */
    public function index(int $menuId)
    {
        $menu = $this->menuRepository->findOrFail($menuId);
        $menuItems = $menu->menuItems()->with('children')->orderBy('sort_order')->get();
        
        return view('menu::admin.items.index', compact('menu', 'menuItems'));
    }

    /**
     * Show the form for creating a new menu item.
     */
    public function create(Request $request)
    {
        $menuId = $request->get('menu_id');
        $parentId = $request->get('parent_id');
        
        $menu = $this->menuRepository->findOrFail($menuId);
        $parentItem = $parentId ? $this->menuItemRepository->findOrFail($parentId) : null;
        
        // Get potential parents (exclude current item and its children to prevent circular reference)
        $potentialParents = $menu->allMenuItems()
            ->whereNull('parent_id')
            ->orderBy('sort_order')
            ->get();

        return view('menu::admin.items.create', compact('menu', 'parentItem', 'potentialParents'));
    }

    /**
     * Store a newly created menu item.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'menu_id' => 'required|integer|exists:menus,id',
            'parent_id' => 'nullable|integer|exists:menu_items,id',
            'title' => 'required|string|max:255',
            'url' => 'required|string|max:500',
            'sort_order' => 'integer|min:0',
            'target' => 'in:_self,_blank',
            'icon' => 'nullable|string|max:100',
        ]);
        
        // Handle empty parent_id (convert empty string to null)
        if (isset($data['parent_id']) && $data['parent_id'] === '') {
            $data['parent_id'] = null;
        }

        // Validate parent_id belongs to the same menu
        if ($data['parent_id']) {
            $parentItem = $this->menuItemRepository->findOrFail($data['parent_id']);
            if ($parentItem->menu_id != $data['menu_id']) {
                return redirect()->back()
                    ->withErrors(['parent_id' => 'Parent item must belong to the same menu.'])
                    ->withInput();
            }
        }

        // Set default sort_order if not provided
        if (!isset($data['sort_order'])) {
            $lastOrder = $this->menuItemRepository->getLastSortOrder($data['menu_id'], $data['parent_id']);
            $data['sort_order'] = $lastOrder + 1;
        }

        $menuItem = $this->menuItemRepository->create($data);
        
        // Clear menu cache
        $menu = $this->menuRepository->findOrFail($data['menu_id']);
        MenuComposer::clearCache($menu->channel_id);

        return redirect()->route('admin.menu.items.index', $data['menu_id'])
            ->with('success', 'Menu item created successfully.');
    }

    /**
     * Show the form for editing a menu item.
     */
    public function edit(int $id)
    {
        $menuItem = $this->menuItemRepository->findOrFail($id);
        $menu = $menuItem->menu;
        
        // Get potential parents (exclude current item and its children to prevent circular reference)
        $potentialParents = $menu->allMenuItems()
            ->where('id', '!=', $id)
            ->whereNotIn('id', $this->getChildrenIds($menuItem))
            ->whereNull('parent_id')
            ->orderBy('sort_order')
            ->get();

        return view('menu::admin.items.edit', compact('menuItem', 'menu', 'potentialParents'));
    }

    /**
     * Update a menu item.
     */
    public function update(Request $request, int $id)
    {
        $menuItem = $this->menuItemRepository->findOrFail($id);
        
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'required|string|max:500',
            'parent_id' => 'nullable|integer|exists:menu_items,id',
            'sort_order' => 'integer|min:0',
            'target' => 'in:_self,_blank',
            'icon' => 'nullable|string|max:100',
        ]);
        
        // Handle empty parent_id (convert empty string to null)
        if (isset($data['parent_id']) && $data['parent_id'] === '') {
            $data['parent_id'] = null;
        }

        // Validate parent_id
        if ($data['parent_id']) {
            // Cannot set self as parent
            if ($data['parent_id'] == $id) {
                return redirect()->back()
                    ->withErrors(['parent_id' => 'Cannot set item as its own parent.'])
                    ->withInput();
            }

            $parentItem = $this->menuItemRepository->findOrFail($data['parent_id']);
            
            // Parent must belong to the same menu
            if ($parentItem->menu_id != $menuItem->menu_id) {
                return redirect()->back()
                    ->withErrors(['parent_id' => 'Parent item must belong to the same menu.'])
                    ->withInput();
            }

            // Cannot set a descendant as parent (prevent circular reference)
            if (in_array($data['parent_id'], $this->getChildrenIds($menuItem))) {
                return redirect()->back()
                    ->withErrors(['parent_id' => 'Cannot set a descendant as parent.'])
                    ->withInput();
            }
        }

        $this->menuItemRepository->update($data, $id);
        
        // Clear menu cache
        MenuComposer::clearCache($menuItem->menu->channel_id);

        return redirect()->route('admin.menu.items.index', $menuItem->menu_id)
            ->with('success', 'Menu item updated successfully.');
    }

    /**
     * Remove a menu item.
     */
    public function destroy(int $id)
    {
        $menuItem = $this->menuItemRepository->findOrFail($id);
        $menuId = $menuItem->menu_id;
        $channelId = $menuItem->menu->channel_id;
        
        $this->menuItemRepository->delete($id);
        
        // Clear menu cache
        MenuComposer::clearCache($channelId);

        return redirect()->route('admin.menu.items.index', $menuId)
            ->with('success', 'Menu item deleted successfully.');
    }

    /**
     * Update menu items order via AJAX.
     */
    public function updateOrder(Request $request)
    {
        $data = $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|integer|exists:menu_items,id',
            'items.*.parent_id' => 'nullable|integer|exists:menu_items,id',
            'items.*.sort_order' => 'required|integer|min:0',
        ]);

        foreach ($data['items'] as $itemData) {
            // Handle empty parent_id (convert empty string to null)
            $parentId = $itemData['parent_id'];
            if ($parentId === '' || $parentId === 'null') {
                $parentId = null;
            }
            
            $this->menuItemRepository->update([
                'parent_id' => $parentId,
                'sort_order' => $itemData['sort_order']
            ], $itemData['id']);
        }

        // Clear cache for all affected menus
        $menuIds = $this->menuItemRepository
            ->whereIn('id', collect($data['items'])->pluck('id'))
            ->pluck('menu_id')
            ->unique();

        foreach ($menuIds as $menuId) {
            $menu = $this->menuRepository->find($menuId);
            if ($menu) {
                MenuComposer::clearCache($menu->channel_id);
            }
        }

        return response()->json(['success' => true, 'message' => 'Menu order updated successfully.']);
    }

    /**
     * Get all children IDs of a menu item (recursive).
     */
    private function getChildrenIds($menuItem)
    {
        $ids = [];
        
        foreach ($menuItem->children as $child) {
            $ids[] = $child->id;
            $ids = array_merge($ids, $this->getChildrenIds($child));
        }
        
        return $ids;
    }
}
