<?php

namespace Webkul\Admin\Http\Controllers\Forum;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Event;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Admin\Http\Requests\MassDestroyRequest;
use Webkul\Admin\Http\Requests\MassUpdateRequest;
use Webkul\Admin\DataGrids\Forum\ForumPostDataGrid;
use Webkul\Admin\DataGrids\Forum\ForumCommentDataGrid;
use Webkul\Admin\DataGrids\Forum\ForumReportDataGrid;
use App\Models\ForumPost;
use App\Models\ForumComment;
use App\Models\ForumReport;

class ForumModerationController extends Controller
{
    /**
     * Display forum posts list
     */
    public function posts()
    {
        if (request()->ajax()) {
            return datagrid(ForumPostDataGrid::class)->process();
        }

        return view('admin::forum.posts.index');
    }

    /**
     * Show forum post detail for moderation
     */
    public function showPost(int $id): JsonResponse
    {
        $post = ForumPost::with(['category', 'tags', 'comments'])->findOrFail($id);

        return new JsonResponse([
            'data' => $post,
        ]);
    }

    /**
     * Update forum post status
     */
    public function updatePost(int $id)
    {
        $this->validate(request(), [
            'status' => 'required|in:published,pending,rejected',
            'is_featured' => 'nullable|boolean',
            'is_sticky' => 'nullable|boolean',
        ]);

        $post = ForumPost::findOrFail($id);
        
        $post->update([
            'status' => request()->input('status'),
            'is_featured' => request()->input('is_featured', $post->is_featured),
            'is_sticky' => request()->input('is_sticky', $post->is_sticky),
        ]);

        return new JsonResponse([
            'message' => 'Bài viết đã được cập nhật thành công',
        ]);
    }

    /**
     * Delete forum post
     */
    public function destroyPost(int $id): JsonResponse
    {
        try {
            $post = ForumPost::findOrFail($id);
            $post->delete();

            return new JsonResponse([
                'message' => 'Bài viết đã được xóa thành công',
            ]);
        } catch (\Exception $e) {
            return new JsonResponse([
                'message' => 'Không thể xóa bài viết: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Mass delete forum posts
     */
    public function massDestroyPosts(MassDestroyRequest $massDestroyRequest): JsonResponse
    {
        $indices = $massDestroyRequest->input('indices');

        try {
            foreach ($indices as $index) {
                ForumPost::destroy($index);
            }

            return new JsonResponse([
                'message' => 'Đã xóa ' . count($indices) . ' bài viết thành công',
            ], 200);
        } catch (\Exception $e) {
            return new JsonResponse([
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Mass update forum posts status
     */
    public function massUpdatePosts(MassUpdateRequest $massUpdateRequest): JsonResponse
    {
        $indices = $massUpdateRequest->input('indices');
        $value = $massUpdateRequest->input('value');

        foreach ($indices as $id) {
            $post = ForumPost::find($id);
            
            if ($post) {
                $post->update(['status' => $value]);
            }
        }

        return new JsonResponse([
            'message' => 'Đã cập nhật ' . count($indices) . ' bài viết thành công',
        ], 200);
    }

    /**
     * Display forum comments list
     */
    public function comments()
    {
        if (request()->ajax()) {
            return datagrid(ForumCommentDataGrid::class)->process();
        }

        return view('admin::forum.comments.index');
    }

    /**
     * Show comment detail
     */
    public function showComment(int $id): JsonResponse
    {
        $comment = ForumComment::with(['post', 'parent'])->findOrFail($id);

        return new JsonResponse([
            'data' => $comment,
        ]);
    }

    /**
     * Update comment status
     */
    public function updateComment(int $id)
    {
        $this->validate(request(), [
            'status' => 'required|in:published,pending,rejected',
        ]);

        $comment = ForumComment::findOrFail($id);
        $comment->update(['status' => request()->input('status')]);

        // Update post comment stats
        if ($comment->post) {
            $comment->post->updateCommentStats();
        }

        return new JsonResponse([
            'message' => 'Bình luận đã được cập nhật thành công',
        ]);
    }

    /**
     * Delete comment
     */
    public function destroyComment(int $id): JsonResponse
    {
        try {
            $comment = ForumComment::findOrFail($id);
            $post = $comment->post;
            $comment->delete();

            // Update post stats
            if ($post) {
                $post->updateCommentStats();
            }

            return new JsonResponse([
                'message' => 'Bình luận đã được xóa thành công',
            ]);
        } catch (\Exception $e) {
            return new JsonResponse([
                'message' => 'Không thể xóa bình luận: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Mass delete comments
     */
    public function massDestroyComments(MassDestroyRequest $massDestroyRequest): JsonResponse
    {
        $indices = $massDestroyRequest->input('indices');

        try {
            $posts = [];
            foreach ($indices as $index) {
                $comment = ForumComment::find($index);
                if ($comment && $comment->post) {
                    $posts[$comment->post_id] = $comment->post;
                }
                ForumComment::destroy($index);
            }

            // Update all affected posts
            foreach ($posts as $post) {
                $post->updateCommentStats();
            }

            return new JsonResponse([
                'message' => 'Đã xóa ' . count($indices) . ' bình luận thành công',
            ], 200);
        } catch (\Exception $e) {
            return new JsonResponse([
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Mass update comments status
     */
    public function massUpdateComments(MassUpdateRequest $massUpdateRequest): JsonResponse
    {
        $indices = $massUpdateRequest->input('indices');
        $value = $massUpdateRequest->input('value');

        $posts = [];
        foreach ($indices as $id) {
            $comment = ForumComment::find($id);
            
            if ($comment) {
                $comment->update(['status' => $value]);
                if ($comment->post) {
                    $posts[$comment->post_id] = $comment->post;
                }
            }
        }

        // Update all affected posts
        foreach ($posts as $post) {
            $post->updateCommentStats();
        }

        return new JsonResponse([
            'message' => 'Đã cập nhật ' . count($indices) . ' bình luận thành công',
        ], 200);
    }

    /**
     * Display forum reports list
     */
    public function reports()
    {
        if (request()->ajax()) {
            return datagrid(ForumReportDataGrid::class)->process();
        }

        return view('admin::forum.reports.index');
    }

    /**
     * Show report detail
     */
    public function showReport(int $id): JsonResponse
    {
        $report = ForumReport::with(['reporter', 'reviewer', 'reportable'])->findOrFail($id);

        return new JsonResponse([
            'data' => $report,
        ]);
    }

    /**
     * Update report status
     */
    public function updateReport(int $id)
    {
        $this->validate(request(), [
            'status' => 'required|in:pending,reviewed,resolved,dismissed',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $report = ForumReport::findOrFail($id);
        
        $report->update([
            'status' => request()->input('status'),
            'admin_notes' => request()->input('admin_notes'),
            'reviewed_by' => auth()->guard('admin')->id(),
            'reviewed_at' => now(),
        ]);

        return new JsonResponse([
            'message' => 'Báo cáo đã được cập nhật thành công',
        ]);
    }

    /**
     * Delete report
     */
    public function destroyReport(int $id): JsonResponse
    {
        try {
            ForumReport::destroy($id);

            return new JsonResponse([
                'message' => 'Báo cáo đã được xóa thành công',
            ]);
        } catch (\Exception $e) {
            return new JsonResponse([
                'message' => 'Không thể xóa báo cáo: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Mass update reports
     */
    public function massUpdateReports(MassUpdateRequest $massUpdateRequest): JsonResponse
    {
        $indices = $massUpdateRequest->input('indices');
        $value = $massUpdateRequest->input('value');

        foreach ($indices as $id) {
            $report = ForumReport::find($id);
            
            if ($report) {
                $report->update([
                    'status' => $value,
                    'reviewed_by' => auth()->guard('admin')->id(),
                    'reviewed_at' => now(),
                ]);
            }
        }

        return new JsonResponse([
            'message' => 'Đã cập nhật ' . count($indices) . ' báo cáo thành công',
        ], 200);
    }

    /**
     * Mass delete reports
     */
    public function massDestroyReports(MassDestroyRequest $massDestroyRequest): JsonResponse
    {
        $indices = $massDestroyRequest->input('indices');

        try {
            ForumReport::destroy($indices);

            return new JsonResponse([
                'message' => 'Đã xóa ' . count($indices) . ' báo cáo thành công',
            ], 200);
        } catch (\Exception $e) {
            return new JsonResponse([
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get forum statistics for dashboard
     */
    public function stats(): JsonResponse
    {
        $stats = [
            'total_posts' => ForumPost::count(),
            'published_posts' => ForumPost::where('status', 'published')->count(),
            'pending_posts' => ForumPost::where('status', 'pending')->count(),
            'total_comments' => ForumComment::count(),
            'published_comments' => ForumComment::where('status', 'published')->count(),
            'pending_comments' => ForumComment::where('status', 'pending')->count(),
            'pending_reports' => ForumReport::where('status', 'pending')->count(),
            'total_reports' => ForumReport::count(),
        ];

        return new JsonResponse(['data' => $stats]);
    }
}
