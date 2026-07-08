<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\HomepageV2Service;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HomepageApiController extends Controller
{
    private HomepageV2Service $service;

    public function __construct()
    {
        $this->service = new HomepageV2Service();
    }

    /**
     * GET /api/v1/homepage — Full homepage data
     */
    public function index(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->service->getHomepageData(),
        ]);
    }

    /**
     * GET /api/v1/homepage/products — Filtered products (AJAX)
     */
    public function products(Request $request): JsonResponse
    {
        $filters = [
            'engine' => $request->input('engine'),
            'genre' => $request->input('genre'),
            'difficulty' => $request->input('difficulty'),
            'price_min' => $request->input('price_min'),
            'price_max' => $request->input('price_max'),
        ];

        // Remove null filters
        $filters = array_filter($filters);

        $sort = $request->input('sort', 'trending');
        $page = max(1, (int) $request->input('page', 1));

        $products = $this->service->getFilteredProducts($filters, $sort, $page);

        return response()->json([
            'success' => true,
            'data' => $products,
        ]);
    }

    /**
     * GET /api/v1/homepage/categories — Categories with counts
     */
    public function categories(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->service->getCategories(),
        ]);
    }
}
