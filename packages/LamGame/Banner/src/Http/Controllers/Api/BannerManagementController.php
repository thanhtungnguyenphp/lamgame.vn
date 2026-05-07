<?php

namespace LamGame\Banner\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use LamGame\Banner\Models\Banner;
use LamGame\Banner\Repositories\BannerRepository;
use Symfony\Component\HttpFoundation\Response;

class BannerManagementController extends Controller
{
    public function __construct(
        private BannerRepository $bannerRepository
    ) {}

    /**
     * List all banners with pagination and filters.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Banner::with('channel');

        if ($request->filled('position')) {
            $query->where('position', $request->input('position'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->boolean('status'));
        }

        if ($request->filled('device_type')) {
            $query->where('device_type', $request->input('device_type'));
        }

        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }

        if ($request->filled('channel_id')) {
            $query->where('channel_id', $request->integer('channel_id'));
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('title', 'like', "%{$search}%");
            });
        }

        $sortBy = $request->input('sort_by', 'sort_order');
        $sortDir = $request->input('sort_dir', 'asc');
        $query->orderBy($sortBy, $sortDir);

        $perPage = $request->integer('per_page', 15);
        $banners = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data'    => $banners->items(),
            'meta'    => [
                'current_page' => $banners->currentPage(),
                'last_page'    => $banners->lastPage(),
                'per_page'     => $banners->perPage(),
                'total'        => $banners->total(),
            ],
        ]);
    }

    /**
     * Get a single banner by ID.
     */
    public function show(int $id): JsonResponse
    {
        $banner = Banner::with('channel')->find($id);

        if (! $banner) {
            return response()->json(['success' => false, 'message' => 'Banner not found'], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'success' => true,
            'data'    => $banner,
        ]);
    }

    /**
     * Create a new banner.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), $this->rules());

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors'  => $validator->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $data = $validator->validated();
        $data['status'] = $data['status'] ?? true;
        $data['sort_order'] = $data['sort_order'] ?? 0;

        $banner = $this->bannerRepository->create($data);

        if ($request->hasFile('image')) {
            $banner->image = $request->file('image')->store('banners', 'public');
            $banner->save();
        }

        $this->bannerRepository->clearAllCache();

        return response()->json([
            'success' => true,
            'message' => 'Banner created successfully',
            'data'    => $banner->fresh('channel'),
        ], Response::HTTP_CREATED);
    }

    /**
     * Update an existing banner.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $banner = Banner::find($id);

        if (! $banner) {
            return response()->json(['success' => false, 'message' => 'Banner not found'], Response::HTTP_NOT_FOUND);
        }

        $validator = Validator::make($request->all(), $this->rules($id));

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors'  => $validator->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $data = $validator->validated();

        $this->bannerRepository->update($data, $id);

        if ($request->hasFile('image')) {
            if ($banner->image) {
                Storage::disk('public')->delete($banner->image);
            }
            $banner->image = $request->file('image')->store('banners', 'public');
            $banner->save();
        }

        $this->bannerRepository->clearBannerCache($banner);

        return response()->json([
            'success' => true,
            'message' => 'Banner updated successfully',
            'data'    => $banner->fresh('channel'),
        ]);
    }

    /**
     * Delete a banner.
     */
    public function destroy(int $id): JsonResponse
    {
        $banner = Banner::find($id);

        if (! $banner) {
            return response()->json(['success' => false, 'message' => 'Banner not found'], Response::HTTP_NOT_FOUND);
        }

        if ($banner->image) {
            Storage::disk('public')->delete($banner->image);
        }

        $this->bannerRepository->clearBannerCache($banner);
        $banner->delete();

        return response()->json([
            'success' => true,
            'message' => 'Banner deleted successfully',
        ]);
    }

    /**
     * Mass delete banners.
     */
    public function massDestroy(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'ids'   => 'required|array|min:1',
            'ids.*' => 'integer|exists:banners,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors'  => $validator->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $ids = $request->input('ids');
        Banner::whereIn('id', $ids)->each(function ($banner) {
            if ($banner->image) {
                Storage::disk('public')->delete($banner->image);
            }
            $banner->delete();
        });

        $this->bannerRepository->clearAllCache();

        return response()->json([
            'success' => true,
            'message' => count($ids) . ' banner(s) deleted successfully',
        ]);
    }

    /**
     * Mass update banner status.
     */
    public function massUpdate(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'ids'    => 'required|array|min:1',
            'ids.*'  => 'integer|exists:banners,id',
            'status' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors'  => $validator->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        Banner::whereIn('id', $request->input('ids'))
            ->update(['status' => $request->boolean('status')]);

        $this->bannerRepository->clearAllCache();

        return response()->json([
            'success' => true,
            'message' => 'Banners updated successfully',
        ]);
    }

    /**
     * Update sort order of banners.
     */
    public function updateOrder(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'orders'             => 'required|array|min:1',
            'orders.*.id'        => 'required|integer|exists:banners,id',
            'orders.*.sort_order' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors'  => $validator->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        foreach ($request->input('orders') as $item) {
            Banner::where('id', $item['id'])->update(['sort_order' => $item['sort_order']]);
        }

        $this->bannerRepository->clearAllCache();

        return response()->json([
            'success' => true,
            'message' => 'Sort order updated successfully',
        ]);
    }

    /**
     * Get banner analytics.
     */
    public function analytics(Request $request): JsonResponse
    {
        $filters = $request->only(['position', 'device_type', 'channel_id', 'date_from', 'date_to']);
        $analytics = $this->bannerRepository->getAnalytics($filters);

        return response()->json([
            'success' => true,
            'data'    => $analytics,
        ]);
    }

    /**
     * Get options (positions, device types, banner types).
     */
    public function options(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => [
                'positions'    => Banner::getPositionOptions(),
                'device_types' => Banner::getDeviceTypeOptions(),
                'banner_types' => Banner::getBannerTypeOptions(),
            ],
        ]);
    }

    /**
     * Validation rules.
     */
    private function rules(?int $id = null): array
    {
        $uniqueRule = $id ? "unique:banners,name,{$id}" : 'unique:banners,name';

        return [
            'name'        => "required|string|max:255|{$uniqueRule}",
            'type'        => 'required|in:image,html,video',
            'position'    => 'required|string|max:255',
            'device_type' => 'required|in:all,desktop,tablet,mobile',
            'channel_id'  => 'nullable|integer|exists:channels,id',
            'locale'      => 'nullable|string|max:10',
            'start_date'  => 'nullable|date',
            'end_date'    => 'nullable|date|after_or_equal:start_date',
            'sort_order'  => 'nullable|integer|min:0',
            'status'      => 'nullable|boolean',
            'title'       => 'nullable|string|max:255',
            'content'     => 'nullable|string',
            'image_alt'   => 'nullable|string|max:255',
            'link'        => 'nullable|url|max:500',
            'target'      => 'nullable|in:_self,_blank',
            'css_classes' => 'nullable|string|max:500',
            'attributes'  => 'nullable|string|max:500',
            'settings'    => 'nullable|string|max:1000',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,gif,webp,svg|max:5120',
        ];
    }
}
