<?php

namespace LamGame\Banner\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use LamGame\Banner\Http\Requests\DeleteBannerRequest;
use LamGame\Banner\Repositories\BannerRepository;
use Symfony\Component\HttpFoundation\Response;

class BannerController extends Controller
{
    public function __construct(
        private BannerRepository $bannerRepository
    ) {}

    /**
     * Get banners for display.
     * 
     * @group Banner API
     * @queryParam position string Banner position. Example: homepage_hero
     * @queryParam device_type string Device type filter (all, desktop, tablet, mobile). Example: mobile
     * @queryParam channel_id integer Channel ID filter. Example: 1
     * @queryParam locale string Locale filter. Example: vi
     * @queryParam limit integer Limit number of results. Example: 5
     * 
     * @response {
     *   "success": true,
     *   "data": [
     *     {
     *       "id": 1,
     *       "name": "Homepage Hero Banner",
     *       "type": "image",
     *       "position": "homepage_hero",
     *       "device_type": "all",
     *       "title": "Welcome to LamGame",
     *       "content": "<p>Learn game development</p>",
     *       "image": "https://domain.com/storage/banners/hero.jpg",
     *       "responsive_images": {
     *         "mobile": "https://domain.com/storage/banners/hero.jpg?w=480",
     *         "tablet": "https://domain.com/storage/banners/hero.jpg?w=768",
     *         "desktop": "https://domain.com/storage/banners/hero.jpg?w=1200",
     *         "large": "https://domain.com/storage/banners/hero.jpg?w=1920"
     *       },
     *       "image_alt": "Homepage hero banner",
     *       "link": "https://lamgame.vn/courses",
     *       "target": "_self",
     *       "css_classes": "hero-banner fade-in",
     *       "html_attributes": "data-analytics=\"banner-hero\"",
     *       "settings": {"animation": "slideUp"},
     *       "sort_order": 0,
     *       "start_date": "2025-01-01T00:00:00Z",
     *       "end_date": null,
     *       "is_active": true,
     *       "channel": {
     *         "id": 1,
     *         "name": "Default",
     *         "code": "default"
     *       }
     *     }
     *   ],
     *   "meta": {
     *     "count": 1,
     *     "filters": {
     *       "position": "homepage_hero",
     *       "device_type": "mobile",
     *       "channel_id": 1,
     *       "locale": "vi"
     *     }
     *   }
     * }
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $filters = $this->validateAndExtractFilters($request);
            $limit = $request->integer('limit', null);
            
            if ($filters['position']) {
                $banners = $this->bannerRepository->getByPosition(
                    $filters['position'],
                    $filters['device_type'],
                    $filters['channel_id'],
                    $filters['locale'],
                    $limit
                );
            } else {
                $banners = $this->bannerRepository->getBannersForDisplay($filters);
                if ($limit) {
                    $banners = $banners->take($limit);
                }
            }

            // Track impressions for returned banners
            $this->trackImpressions($banners, $request);

            $response = [
                'success' => true,
                'data' => $banners->values()->all(),
                'meta' => [
                    'count' => $banners->count(),
                    'filters' => array_filter($filters),
                ],
            ];

            return $this->jsonResponse($response)
                ->header('Cache-Control', 'public, max-age=300') // 5 minutes cache
                ->header('X-Banner-API-Version', '1.0');

        } catch (\Exception $e) {
            return $this->errorResponse('Failed to fetch banners', $e->getMessage());
        }
    }

    /**
     * Get banners by specific position.
     * 
     * @group Banner API
     * @urlParam position string required Banner position. Example: homepage_hero
     * @queryParam device_type string Device type filter. Example: mobile
     * @queryParam channel_id integer Channel ID filter. Example: 1
     * @queryParam locale string Locale filter. Example: vi
     * @queryParam limit integer Limit number of results. Example: 3
     */
    public function getByPosition(Request $request, string $position): JsonResponse
    {
        try {
            $deviceType = $request->string('device_type', 'all');
            $channelId = $request->has('channel_id') ? $request->integer('channel_id') : null;
            $locale = $request->has('locale') && $request->string('locale') ? $request->string('locale') : null;
            $limit = $request->has('limit') ? $request->integer('limit') : null;

            $banners = $this->bannerRepository->getByPosition(
                $position,
                $deviceType,
                $channelId,
                $locale,
                $limit
            );

            // Track impressions
            $this->trackImpressions($banners, $request);

            $response = [
                'success' => true,
                'data' => $banners->values()->all(),
                'meta' => [
                    'position' => $position,
                    'count' => $banners->count(),
                    'filters' => [
                        'device_type' => $deviceType,
                        'channel_id' => $channelId,
                        'locale' => $locale,
                    ],
                ],
            ];

            return $this->jsonResponse($response)
                ->header('Cache-Control', 'public, max-age=300');

        } catch (\Exception $e) {
            return $this->errorResponse("Failed to fetch banners for position: {$position}", $e->getMessage());
        }
    }

    /**
     * Track banner click.
     * 
     * @group Banner API
     * @urlParam id integer required Banner ID. Example: 1
     * @bodyParam user_agent string User agent string.
     * @bodyParam referrer string Referrer URL.
     * 
     * @response {
     *   "success": true,
     *   "message": "Click tracked successfully"
     * }
     */
    public function trackClick(Request $request, int $id): JsonResponse
    {
        try {
            $this->bannerRepository->incrementClicks($id);

            // Log click for analytics
            \Log::info('Banner click tracked', [
                'banner_id' => $id,
                'user_agent' => $request->header('User-Agent'),
                'ip' => $request->ip(),
                'referrer' => $request->header('Referer'),
                'timestamp' => now()->toISOString(),
            ]);

            return $this->jsonResponse([
                'success' => true,
                'message' => 'Click tracked successfully',
            ]);

        } catch (\Exception $e) {
            return $this->errorResponse('Failed to track click', $e->getMessage());
        }
    }
    
    /**
     * Track banner impression.
     * 
     * @group Banner API
     * @urlParam id integer required Banner ID. Example: 1
     * @bodyParam user_agent string User agent string.
     * @bodyParam referrer string Referrer URL.
     * 
     * @response {
     *   "success": true,
     *   "message": "Impression tracked successfully"
     * }
     */
    public function trackImpression(Request $request, int $id): JsonResponse
    {
        try {
            $this->bannerRepository->incrementImpressions($id);

            // Log impression for analytics
            \Log::info('Banner impression tracked', [
                'banner_id' => $id,
                'user_agent' => $request->header('User-Agent'),
                'ip' => $request->ip(),
                'referrer' => $request->header('Referer'),
                'timestamp' => now()->toISOString(),
            ]);

            return $this->jsonResponse([
                'success' => true,
                'message' => 'Impression tracked successfully',
            ]);

        } catch (\Exception $e) {
            return $this->errorResponse('Failed to track impression', $e->getMessage());
        }
    }

    /**
     * Get banner positions.
     * 
     * @group Banner API
     * 
     * @response {
     *   "success": true,
     *   "data": [
     *     {"value": "homepage_hero", "label": "Homepage Hero"},
     *     {"value": "sidebar_top", "label": "Sidebar Top"}
     *   ]
     * }
     */
    public function positions(): JsonResponse
    {
        try {
            $positions = \LamGame\Banner\Models\Banner::getPositionOptions();

            return $this->jsonResponse([
                'success' => true,
                'data' => $positions,
            ])->header('Cache-Control', 'public, max-age=3600'); // 1 hour cache

        } catch (\Exception $e) {
            return $this->errorResponse('Failed to fetch positions', $e->getMessage());
        }
    }

    /**
     * Get device types.
     * 
     * @group Banner API
     */
    public function deviceTypes(): JsonResponse
    {
        try {
            $deviceTypes = \LamGame\Banner\Models\Banner::getDeviceTypeOptions();

            return $this->jsonResponse([
                'success' => true,
                'data' => $deviceTypes,
            ])->header('Cache-Control', 'public, max-age=3600');

        } catch (\Exception $e) {
            return $this->errorResponse('Failed to fetch device types', $e->getMessage());
        }
    }

    /**
     * Delete a banner.
     * 
     * @group Banner API
     * @urlParam id integer required Banner ID to delete. Example: 1
     * 
     * @response 200 {
     *   "success": true,
     *   "message": "Banner deleted successfully",
     *   "data": {
     *     "id": 1,
     *     "name": "Homepage Hero Banner"
     *   }
     * }
     * 
     * @response 404 {
     *   "success": false,
     *   "message": "Banner not found"
     * }
     * 
     * @response 500 {
     *   "success": false,
     *   "message": "Failed to delete banner"
     * }
     */
    public function destroy(DeleteBannerRequest $request, int $id): JsonResponse
    {
        try {
            // Validate banner ID
            if ($id <= 0) {
                return $this->errorResponse('Invalid banner ID', null, Response::HTTP_BAD_REQUEST);
            }

            // Find and delete the banner
            $bannerData = $this->bannerRepository->deleteBanner($id);

            // Log the deletion
            \Log::info('Banner deleted successfully', [
                'banner_id' => $id,
                'banner_name' => $bannerData['name'] ?? 'Unknown',
                'deleted_at' => now()->toISOString(),
                'user_agent' => request()->header('User-Agent'),
                'ip' => request()->ip(),
            ]);

            return $this->jsonResponse([
                'success' => true,
                'message' => 'Banner deleted successfully',
                'data' => array_merge($bannerData, [
                    'deleted_at' => now()->toISOString(),
                ]),
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->errorResponse('Banner not found', $e->getMessage(), Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            \Log::error('Failed to delete banner', [
                'banner_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            return $this->errorResponse('Failed to delete banner', $e->getMessage());
        }
    }

    /**
     * Validate and extract filters from request.
     */
    private function validateAndExtractFilters(Request $request): array
    {
        $request->validate([
            'position' => 'nullable|string|max:255',
            'device_type' => 'nullable|in:all,desktop,tablet,mobile',
            'channel_id' => 'nullable|integer|min:1',
            'locale' => 'nullable|string|max:10',
            'limit' => 'nullable|integer|min:1|max:50',
        ]);

        return [
            'position' => $request->has('position') && $request->string('position') ? $request->string('position') : null,
            'device_type' => $request->string('device_type', 'all'),
            'channel_id' => $request->has('channel_id') ? $request->integer('channel_id') : null,
            'locale' => $request->has('locale') && $request->string('locale') ? $request->string('locale') : null,
        ];
    }

    /**
     * Track impressions for banners.
     */
    private function trackImpressions($banners, Request $request): void
    {
        // Only track impressions from actual frontend requests (not API testing)
        if ($request->header('X-Track-Impressions') !== 'false') {
            foreach ($banners as $banner) {
                if (isset($banner['id'])) {
                    $this->bannerRepository->incrementImpressions($banner['id']);
                }
            }
        }
    }

    /**
     * Create JSON response with consistent format.
     */
    private function jsonResponse(array $data, int $status = Response::HTTP_OK): JsonResponse
    {
        return response()->json($data, $status);
    }

    /**
     * Create error response with consistent format.
     */
    private function errorResponse(string $message, string $detail = null, int $status = Response::HTTP_INTERNAL_SERVER_ERROR): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        if ($detail && config('app.debug')) {
            $response['detail'] = $detail;
        }

        return $this->jsonResponse($response, $status);
    }
}