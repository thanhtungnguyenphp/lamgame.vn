<?php

namespace Webkul\Menu\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Webkul\Menu\Repositories\MenuRepository;
use Webkul\Menu\Http\ViewComposers\MenuComposer;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $menuRepository;

    public function __construct(MenuRepository $menuRepository)
    {
        $this->menuRepository = $menuRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $perPage = $request->input('perPage', 10);
        $search = $request->input('search');
        $menus = $this->menuRepository->getMenus($perPage, $search);
        
        $total = $menus->total();
        return view('menu::admin.index', compact('menus', 'total', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('menu::admin.create-menu');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'channel_id' => 'required|integer|exists:channels,id',
            'menu_items' => 'array',
            'menu_items.*.title' => 'required|string|max:255',
            'menu_items.*.url' => 'required|string|max:500',
            'menu_items.*.sort_order' => 'integer|min:0',
            'menu_items.*.target' => 'in:_self,_blank',
            'menu_items.*.icon' => 'nullable|string|max:100',
        ]);
        
        $menu = $this->menuRepository->create([
            'name' => $data['name'],
            'channel_id' => $data['channel_id']
        ]);
        
        // Create menu items if provided
        if (isset($data['menu_items'])) {
            foreach ($data['menu_items'] as $itemData) {
                $menu->menuItems()->create([
                    'title' => $itemData['title'],
                    'url' => $itemData['url'],
                    'sort_order' => $itemData['sort_order'] ?? 0,
                    'target' => $itemData['target'] ?? '_self',
                    'icon' => $itemData['icon'] ?? null,
                ]);
            }
        }
        
        // Clear menu cache for this channel
        MenuComposer::clearCache($data['channel_id']);
        
        return redirect()->route('admin.menu.index')->with('success', 'Menu created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\View\View
     */
    public function edit(int $id)
    {
        $menu = $this->menuRepository->findOrFail($id);
        return view('menu::admin.edit', compact('menu'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'channel_id' => 'required|integer|exists:channels,id',
        ]);
        
        $menu = $this->menuRepository->findOrFail($id);
        $oldChannelId = $menu->channel_id;
        
        $this->menuRepository->update($data, $id);
        
        // Clear cache for both old and new channels if different
        MenuComposer::clearCache($oldChannelId);
        if ($oldChannelId !== $data['channel_id']) {
            MenuComposer::clearCache($data['channel_id']);
        }
        
        return redirect()->route('admin.menu.index')->with('success', 'Menu updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $menu = $this->menuRepository->findOrFail($id);
        $channelId = $menu->channel_id;
        
        $this->menuRepository->delete($id);
        
        // Clear menu cache for this channel
        MenuComposer::clearCache($channelId);
        
        return redirect()->route('admin.menu.index')->with('success', 'Menu deleted successfully.');
    }
}
