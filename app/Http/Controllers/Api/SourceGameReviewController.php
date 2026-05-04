<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\SourceGameReviewService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SourceGameReviewController extends Controller
{
    public function __construct(private SourceGameReviewService $service) {}

    /**
     * GET /api/v1/source-game/{productId}/reviews
     */
    public function index(Request $request, int $productId): JsonResponse
    {
        $reviews = $this->service->listByProduct(
            $productId,
            $request->integer('per_page', 10),
            $request->input('sort_by', 'created_at')
        );

        return response()->json(['status' => 'success', 'data' => $reviews]);
    }

    /**
     * GET /api/v1/source-game/{productId}/review-stats
     */
    public function stats(int $productId): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'data'   => $this->service->stats($productId),
        ]);
    }

    /**
     * POST /api/v1/source-game/{productId}/reviews (auth:sanctum)
     */
    public function store(Request $request, int $productId): JsonResponse
    {
        $validated = $request->validate([
            'rating'  => 'required|integer|between:1,5',
            'title'   => 'nullable|string|max:255',
            'content' => 'required|string|max:5000',
            'pros'    => 'nullable|string|max:1000',
            'cons'    => 'nullable|string|max:1000',
        ]);

        $validated['product_id'] = $productId;

        try {
            $review = $this->service->create($validated, $request->user()->id);
            return response()->json(['status' => 'success', 'data' => $review], 201);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 422);
        }
    }

    /**
     * POST /api/v1/reviews/{id}/helpful (auth:sanctum)
     */
    public function helpful(Request $request, int $id): JsonResponse
    {
        $review = $this->service->toggleHelpful($id, $request->user()->id);
        return response()->json(['status' => 'success', 'data' => ['helpful_count' => $review->helpful_count]]);
    }
}
