<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SourceGameReviewService;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function __construct(private SourceGameReviewService $service) {}

    public function index(Request $request)
    {
        $status = $request->input('status', 'pending');
        $query = \App\Models\SourceGameReview::with(['product:id,sku', 'customer:id,first_name,last_name,email']);

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $reviews = $query->orderByDesc('created_at')->paginate(20);

        return view('admin.reviews.index', compact('reviews', 'status'));
    }

    public function updateStatus(Request $request, int $id)
    {
        $request->validate(['status' => 'required|in:published,hidden,pending']);
        $this->service->updateStatus($id, $request->status);

        return back()->with('success', 'Cập nhật trạng thái thành công.');
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'ids'    => 'required|array',
            'action' => 'required|in:publish,hide,delete',
        ]);

        if ($request->action === 'delete') {
            \App\Models\SourceGameReview::whereIn('id', $request->ids)->delete();
        } else {
            $status = $request->action === 'publish' ? 'published' : 'hidden';
            $this->service->bulkUpdateStatus($request->ids, $status);
        }

        return back()->with('success', 'Đã xử lý ' . count($request->ids) . ' đánh giá.');
    }
}
