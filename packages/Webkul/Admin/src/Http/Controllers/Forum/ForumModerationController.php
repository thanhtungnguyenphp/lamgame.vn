<?php

namespace Webkul\Admin\Http\Controllers\Forum;

use Illuminate\Http\JsonResponse;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Admin\Http\Requests\MassDestroyRequest;
use Webkul\Admin\Http\Requests\MassUpdateRequest;
use Webkul\Admin\DataGrids\Forum\ForumPostDataGrid;
use Webkul\Admin\DataGrids\Forum\ForumCommentDataGrid;
use Webkul\Admin\DataGrids\Forum\ForumReportDataGrid;
use App\Models\ForumPost;
use App\Models\ForumComment;
use App\Models\ForumReport;
use App\Services\Forum\ForumPostService;
use App\Services\Forum\ForumCommentService;
use App\Services\Forum\ForumReportService;

class ForumModerationController extends Controller
{
    public function __construct(
        protected ForumPostService $postService,
        protected ForumCommentService $commentService,
        protected ForumReportService $reportService,
    ) {}

    // === POSTS ===

    public function posts()
    {
        if (request()->ajax()) {
            return datagrid(ForumPostDataGrid::class)->process();
        }
        return view('admin::forum.posts.index');
    }

    public function showPost(int $id): JsonResponse
    {
        $post = ForumPost::with(['category', 'tags', 'comments'])->findOrFail($id);
        return new JsonResponse(['data' => $post]);
    }

    public function updatePost(int $id): JsonResponse
    {
        $this->validate(request(), [
            'status'      => 'required|in:published,pending,rejected',
            'is_featured' => 'nullable|boolean',
            'is_sticky'   => 'nullable|boolean',
        ]);

        $post = ForumPost::findOrFail($id);
        $this->postService->updateStatus(
            $post,
            request()->input('status'),
            request()->input('is_featured'),
            request()->input('is_sticky'),
        );

        return new JsonResponse(['message' => 'Bài viết đã được cập nhật thành công']);
    }

    public function destroyPost(int $id): JsonResponse
    {
        try {
            $this->postService->delete(ForumPost::findOrFail($id));
            return new JsonResponse(['message' => 'Bài viết đã được xóa thành công']);
        } catch (\Exception $e) {
            return new JsonResponse(['message' => 'Không thể xóa bài viết: ' . $e->getMessage()], 500);
        }
    }

    public function massDestroyPosts(MassDestroyRequest $request): JsonResponse
    {
        try {
            $count = $this->postService->massDelete($request->input('indices'));
            return new JsonResponse(['message' => "Đã xóa {$count} bài viết thành công"]);
        } catch (\Exception $e) {
            return new JsonResponse(['message' => $e->getMessage()], 500);
        }
    }

    public function massUpdatePosts(MassUpdateRequest $request): JsonResponse
    {
        $count = $this->postService->massUpdateStatus($request->input('indices'), $request->input('value'));
        return new JsonResponse(['message' => "Đã cập nhật {$count} bài viết thành công"]);
    }

    // === COMMENTS ===

    public function comments()
    {
        if (request()->ajax()) {
            return datagrid(ForumCommentDataGrid::class)->process();
        }
        return view('admin::forum.comments.index');
    }

    public function showComment(int $id): JsonResponse
    {
        $comment = ForumComment::with(['post', 'parent'])->findOrFail($id);
        return new JsonResponse(['data' => $comment]);
    }

    public function updateComment(int $id): JsonResponse
    {
        $this->validate(request(), ['status' => 'required|in:published,pending,rejected']);

        $this->commentService->updateStatus(ForumComment::findOrFail($id), request()->input('status'));

        return new JsonResponse(['message' => 'Bình luận đã được cập nhật thành công']);
    }

    public function destroyComment(int $id): JsonResponse
    {
        try {
            $this->commentService->delete(ForumComment::findOrFail($id));
            return new JsonResponse(['message' => 'Bình luận đã được xóa thành công']);
        } catch (\Exception $e) {
            return new JsonResponse(['message' => 'Không thể xóa bình luận: ' . $e->getMessage()], 500);
        }
    }

    public function massDestroyComments(MassDestroyRequest $request): JsonResponse
    {
        try {
            $count = $this->commentService->massDelete($request->input('indices'));
            return new JsonResponse(['message' => "Đã xóa {$count} bình luận thành công"]);
        } catch (\Exception $e) {
            return new JsonResponse(['message' => $e->getMessage()], 500);
        }
    }

    public function massUpdateComments(MassUpdateRequest $request): JsonResponse
    {
        $count = $this->commentService->massUpdateStatus($request->input('indices'), $request->input('value'));
        return new JsonResponse(['message' => "Đã cập nhật {$count} bình luận thành công"]);
    }

    // === REPORTS ===

    public function reports()
    {
        if (request()->ajax()) {
            return datagrid(ForumReportDataGrid::class)->process();
        }
        return view('admin::forum.reports.index');
    }

    public function showReport(int $id): JsonResponse
    {
        $report = ForumReport::with(['reporter', 'reviewer', 'reportable'])->findOrFail($id);
        return new JsonResponse(['data' => $report]);
    }

    public function updateReport(int $id): JsonResponse
    {
        $this->validate(request(), [
            'status'      => 'required|in:pending,reviewed,resolved,dismissed',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $this->reportService->updateStatus(
            ForumReport::findOrFail($id),
            request()->input('status'),
            request()->input('admin_notes'),
            auth()->guard('admin')->id(),
        );

        return new JsonResponse(['message' => 'Báo cáo đã được cập nhật thành công']);
    }

    public function destroyReport(int $id): JsonResponse
    {
        try {
            ForumReport::findOrFail($id)->delete();
            return new JsonResponse(['message' => 'Báo cáo đã được xóa thành công']);
        } catch (\Exception $e) {
            return new JsonResponse(['message' => 'Không thể xóa báo cáo: ' . $e->getMessage()], 500);
        }
    }

    public function massUpdateReports(MassUpdateRequest $request): JsonResponse
    {
        $count = $this->reportService->massUpdateStatus(
            $request->input('indices'),
            $request->input('value'),
            auth()->guard('admin')->id(),
        );
        return new JsonResponse(['message' => "Đã cập nhật {$count} báo cáo thành công"]);
    }

    public function massDestroyReports(MassDestroyRequest $request): JsonResponse
    {
        try {
            $count = $this->reportService->massDelete($request->input('indices'));
            return new JsonResponse(['message' => "Đã xóa {$count} báo cáo thành công"]);
        } catch (\Exception $e) {
            return new JsonResponse(['message' => $e->getMessage()], 500);
        }
    }

    // === STATS ===

    public function stats(): JsonResponse
    {
        return new JsonResponse(['data' => $this->postService->getAdminStats()]);
    }
}
