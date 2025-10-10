<?php

namespace LamGame\Banner\Http\Controllers\Admin;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use LamGame\Banner\Models\Banner;
use LamGame\Banner\Repositories\BannerRepository;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Admin\DataGrids\DataGrid;
use Webkul\Core\Models\Channel;

class BannerController extends Controller
{
    public function __construct(
        private BannerRepository $bannerRepository
    ) {}

    /**
     * Display a listing of banners.
     */
    public function index(): View
    {
        if (request()->ajax()) {
            return app(BannerDataGrid::class)->toJson();
        }

        // Get actual banner data
        $banners = Banner::orderBy('sort_order', 'asc')
                        ->orderBy('created_at', 'desc')
                        ->get();
                        
        return view('banner::admin.banners.list', compact('banners'));
    }

    /**
     * Show the form for creating a new banner.
     */
    public function create(): View
    {
        $channels = Channel::all();
        $positions = Banner::getPositionOptions();
        $deviceTypes = Banner::getDeviceTypeOptions();
        $bannerTypes = Banner::getBannerTypeOptions();

        return view('banner::admin.banners.create');
    }

    /**
     * Store a newly created banner in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            $validatedData = $this->validateBannerData($request);
            
            $banner = $this->bannerRepository->create($validatedData);

            // Clear banner cache
            $this->bannerRepository->clearAllCache();

            session()->flash('success', trans('banner::app.admin.banners.create-success'));

            return redirect()->route('admin.banners.index');

        } catch (\Exception $e) {
            session()->flash('error', trans('banner::app.admin.banners.create-error'));

            return redirect()->back()->withInput();
        }
    }

    /**
     * Display the specified banner.
     */
    public function show(int $id): View
    {
        $banner = $this->bannerRepository->findOrFail($id);
        $analytics = $this->bannerRepository->getAnalytics(['banner_id' => $id]);

        return view('banner::admin.banners.show', compact('banner', 'analytics'));
    }

    /**
     * Show the form for editing the specified banner.
     */
    public function edit(int $id): View
    {
        $banner = $this->bannerRepository->findOrFail($id);
        $channels = Channel::all();
        $positions = Banner::getPositionOptions();
        $deviceTypes = Banner::getDeviceTypeOptions();
        $bannerTypes = Banner::getBannerTypeOptions();

        return view('banner::admin.banners.edit', compact(
            'banner',
            'channels', 
            'positions', 
            'deviceTypes', 
            'bannerTypes'
        ));
    }

    /**
     * Update the specified banner in storage.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $validatedData = $this->validateBannerData($request, $id);

        try {
            $banner = $this->bannerRepository->findOrFail($id);
            $this->bannerRepository->update($validatedData, $id);

            // Clear banner cache
            $this->bannerRepository->clearBannerCache($banner);

            session()->flash('success', trans('banner::app.admin.banners.update-success'));

            return redirect()->route('admin.banners.index');

        } catch (\Exception $e) {
            session()->flash('error', trans('banner::app.admin.banners.update-error'));

            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified banner from storage.
     */
    public function destroy(int $id): RedirectResponse
    {
        try {
            $banner = $this->bannerRepository->findOrFail($id);
            
            // Clear banner cache before deletion
            $this->bannerRepository->clearBannerCache($banner);
            
            $this->bannerRepository->deleteBanner($id);

            session()->flash('success', trans('banner::app.admin.banners.delete-success'));

        } catch (\Exception $e) {
            session()->flash('error', trans('banner::app.admin.banners.delete-error'));
        }

        return redirect()->route('admin.banners.index');
    }

    /**
     * Mass delete banners.
     */
    public function massDestroy(Request $request): RedirectResponse
    {
        $ids = $request->input('indices', []);

        if (empty($ids)) {
            session()->flash('error', trans('banner::app.admin.banners.mass-delete-error'));
            return redirect()->back();
        }

        try {
            foreach ($ids as $id) {
                $banner = $this->bannerRepository->find($id);
                if ($banner) {
                    $this->bannerRepository->clearBannerCache($banner);
                    $this->bannerRepository->deleteBanner($id);
                }
            }

            session()->flash('success', trans('banner::app.admin.banners.mass-delete-success'));

        } catch (\Exception $e) {
            session()->flash('error', trans('banner::app.admin.banners.mass-delete-error'));
        }

        return redirect()->route('admin.banners.index');
    }

    /**
     * Mass update banner status.
     */
    public function massUpdate(Request $request): RedirectResponse
    {
        $ids = $request->input('indices', []);
        $action = $request->input('action');

        if (empty($ids) || !in_array($action, ['enable', 'disable'])) {
            session()->flash('error', trans('banner::app.admin.banners.mass-update-error'));
            return redirect()->back();
        }

        try {
            $status = $action === 'enable';
            
            foreach ($ids as $id) {
                $banner = $this->bannerRepository->find($id);
                if ($banner) {
                    $this->bannerRepository->update(['status' => $status], $id);
                    $this->bannerRepository->clearBannerCache($banner);
                }
            }

            $message = $action === 'enable' 
                ? trans('banner::app.admin.banners.mass-enable-success')
                : trans('banner::app.admin.banners.mass-disable-success');
            
            session()->flash('success', $message);

        } catch (\Exception $e) {
            session()->flash('error', trans('banner::app.admin.banners.mass-update-error'));
        }

        return redirect()->route('admin.banners.index');
    }

    /**
     * Clear all banner caches.
     */
    public function clearCache(): RedirectResponse
    {
        try {
            $this->bannerRepository->clearAllCache();

            session()->flash('success', trans('banner::app.admin.banners.cache-clear-success'));

        } catch (\Exception $e) {
            session()->flash('error', trans('banner::app.admin.banners.cache-clear-error'));
        }

        return redirect()->back();
    }

    /**
     * Get banner analytics.
     */
    public function analytics(Request $request): View
    {
        // Get real analytics data from the repository
        $analytics = $this->bannerRepository->getAnalytics();
        
        // Calculate click-through rate
        $clickRate = 0;
        if ($analytics['total_impressions'] > 0) {
            $clickRate = ($analytics['total_clicks'] / $analytics['total_impressions']) * 100;
        }
        
        return view('banner::admin.banners.analytics-simple', [
            'totalBanners' => $analytics['total_banners'],
            'totalImpressions' => $analytics['total_impressions'],
            'totalClicks' => $analytics['total_clicks'],
            'clickRate' => number_format($clickRate, 1) . '%',
            'analytics' => $analytics,
        ]);
    }

    /**
     * Validate banner data.
     */
    private function validateBannerData(Request $request, ?int $id = null): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'type' => 'required|in:image,html,video',
            'position' => 'required|string|max:255',
            'device_type' => 'required|in:all,desktop,tablet,mobile',
            'channel_id' => 'nullable|exists:channels,id',
            'locale' => 'nullable|string|max:10',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'sort_order' => 'nullable|integer|min:0',
            'status' => 'nullable|boolean',
            
            'title' => 'nullable|string|max:255',
            'content' => 'nullable|string',
            'image' => 'nullable|file|image|max:5120',
            'image_alt' => 'nullable|string|max:255',
            'link' => 'nullable|url|max:255',
            'target' => 'nullable|in:_self,_blank',
            
            'css_classes' => 'nullable|string|max:500',
            'attributes' => 'nullable|string|max:500',
            'settings' => 'nullable|string|max:500',
        ];

        // Add unique rule for name if creating new banner
        if (!$id) {
            $rules['name'] = 'required|string|max:255|unique:banners,name';
        } else {
            $rules['name'] = "required|string|max:255|unique:banners,name,{$id}";
        }

        $validatedData = $request->validate($rules);

        // Handle status checkbox (if not checked, it won't be in request)
        $validatedData['status'] = $request->has('status') ? 1 : 0;
        
        // Set default values
        $validatedData['sort_order'] = $validatedData['sort_order'] ?? 0;
        $validatedData['target'] = $validatedData['target'] ?? '_self';

        // Handle image upload if present
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $path = $image->store('banners', 'public');
            $validatedData['image'] = $path;
        } else {
            // Remove image key if no file uploaded
            unset($validatedData['image']);
        }

        return $validatedData;
    }
}