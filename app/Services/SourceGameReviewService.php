<?php

namespace App\Services;

use App\Models\SourceGameReview;
use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Webkul\Sales\Models\OrderItem;

class SourceGameReviewService
{
    public function create(array $data, int $customerId): SourceGameReview
    {
        // Check already reviewed
        $exists = SourceGameReview::where('product_id', $data['product_id'])
            ->where('customer_id', $customerId)
            ->exists();

        if ($exists) {
            throw new \Exception('Bạn đã đánh giá sản phẩm này rồi.');
        }

        // Check verified purchase
        $hasPurchased = OrderItem::whereHas('order', function ($q) use ($customerId) {
            $q->where('customer_id', $customerId)->where('status', 'completed');
        })->where('product_id', $data['product_id'])->exists();

        $review = SourceGameReview::create([
            'product_id'           => $data['product_id'],
            'customer_id'          => $customerId,
            'rating'               => $data['rating'],
            'title'                => $data['title'] ?? null,
            'content'              => $data['content'],
            'pros'                 => $data['pros'] ?? null,
            'cons'                 => $data['cons'] ?? null,
            'is_verified_purchase' => $hasPurchased,
        ]);

        return $review;
    }

    public function listByProduct(int $productId, int $perPage = 10, string $sortBy = 'created_at'): LengthAwarePaginator
    {
        $allowedSorts = ['created_at', 'rating', 'helpful_count'];
        $sortBy = in_array($sortBy, $allowedSorts) ? $sortBy : 'created_at';

        return SourceGameReview::byProduct($productId)
            ->published()
            ->with('customer:id,first_name,last_name')
            ->orderByDesc($sortBy)
            ->paginate($perPage);
    }

    public function stats(int $productId): array
    {
        $reviews = SourceGameReview::byProduct($productId)->published();

        $distribution = $reviews->clone()
            ->select('rating', DB::raw('COUNT(*) as count'))
            ->groupBy('rating')
            ->pluck('count', 'rating')
            ->toArray();

        $avg = $reviews->clone()->avg('rating');
        $total = $reviews->clone()->count();

        // Fill missing ratings
        $dist = [];
        for ($i = 5; $i >= 1; $i--) {
            $dist[$i] = $distribution[$i] ?? 0;
        }

        return [
            'avg_rating'   => round($avg ?? 0, 1),
            'total'        => $total,
            'distribution' => $dist,
        ];
    }

    public function toggleHelpful(int $reviewId, int $customerId): SourceGameReview
    {
        $review = SourceGameReview::published()->findOrFail($reviewId);

        // Simple increment (no tracking per user for now)
        $review->increment('helpful_count');

        return $review;
    }

    public function refreshProductRating(int $productId): void
    {
        $stats = SourceGameReview::byProduct($productId)->published()
            ->selectRaw('AVG(rating) as avg, COUNT(*) as cnt')
            ->first();

        Product::where('id', $productId)->update([
            'avg_rating'   => round($stats->avg ?? 0, 1),
            'review_count' => $stats->cnt ?? 0,
        ]);
    }

    public function listPending(int $perPage = 15): LengthAwarePaginator
    {
        return SourceGameReview::where('status', 'pending')
            ->with(['product:id,sku', 'customer:id,first_name,last_name,email'])
            ->orderByDesc('created_at')
            ->paginate($perPage);
    }

    public function updateStatus(int $reviewId, string $status): SourceGameReview
    {
        $review = SourceGameReview::findOrFail($reviewId);
        $review->update(['status' => $status]);

        $this->refreshProductRating($review->product_id);

        return $review;
    }

    public function bulkUpdateStatus(array $ids, string $status): int
    {
        $reviews = SourceGameReview::whereIn('id', $ids)->get();
        $count = SourceGameReview::whereIn('id', $ids)->update(['status' => $status]);

        // Refresh ratings for affected products
        $productIds = $reviews->pluck('product_id')->unique();
        foreach ($productIds as $pid) {
            $this->refreshProductRating($pid);
        }

        return $count;
    }
}
