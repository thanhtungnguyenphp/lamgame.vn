<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ForumPost;
use App\Models\ForumComment;
use App\Models\ForumCategory;
use App\Models\ForumTag;
use App\Models\ForumReport;
use App\Models\ForumNotification;
use App\Services\Forum\ForumPostService;
use App\Services\Forum\ForumCommentService;
use App\Services\Forum\ForumReportService;
use App\Services\Forum\ForumReputationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ForumManageController extends Controller
{
    public function __construct(
        protected ForumPostService $postService,
        protected ForumCommentService $commentService,
        protected ForumReportService $reportService,
        protected ForumReputationService $reputationService,
    ) {}

    // =========================================================================
    // DASHBOARD
    // =========================================================================

    public function dashboard(): JsonResponse
    {
        $stats = $this->postService->getAdminStats();

        $stats['total_categories'] = ForumCategory::count();
        $stats['total_tags'] = ForumTag::count();
        $stats['total_bookmarks'] = \App\Models\ForumBookmark::count();
        $stats['total_notifications'] = ForumNotification::count();

        // Trends (7 ngày gần nhất)
        $stats['posts_last_7_days'] = ForumPost::where('created_at', '>=', now()->subDays(7))->count();
        $stats['comments_last_7_days'] = ForumComment::where('created_at', '>=', now()->subDays(7))->count();

        // Top categories
        $stats['top_categories'] = ForumCategory::orderByDesc('posts_count')->limit(5)
            ->get(['id', 'name', 'slug', 'posts_count', 'comments_count']);

        return response()->json(['status' => 'success', 'data' => $stats]);
    }

    // =========================================================================
    // POSTS
    // =========================================================================

    public function postList(Request $request): JsonResponse
    {
        $query = ForumPost::with(['category:id,name,slug', 'tags:id,name,slug']);

        // Filters
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('author_name', 'like', "%{$search}%");
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($type = $request->input('type')) {
            $query->where('type', $type);
        }

        if ($categoryId = $request->input('category_id')) {
            $query->where('category_id', $categoryId);
        }

        if ($request->has('is_featured')) {
            $query->where('is_featured', $request->boolean('is_featured'));
        }

        if ($request->has('is_sticky')) {
            $query->where('is_sticky', $request->boolean('is_sticky'));
        }

        if ($customerId = $request->input('customer_id')) {
            $query->where('customer_id', $customerId);
        }

        // Sort
        $sortable = ['created_at', 'views_count', 'comments_count', 'likes_count', 'hot_score', 'title'];
        $sortBy = in_array($request->input('sort_by'), $sortable) ? $request->input('sort_by') : 'created_at';
        $sortDir = $request->input('sort_dir') === 'asc' ? 'asc' : 'desc';
        $query->orderBy($sortBy, $sortDir);

        $perPage = min(max($request->integer('per_page', 15), 1), 100);
        $posts = $query->paginate($perPage);

        return response()->json([
            'status' => 'success',
            'data'   => $posts->items(),
            'meta'   => [
                'current_page' => $posts->currentPage(),
                'last_page'    => $posts->lastPage(),
                'per_page'     => $posts->perPage(),
                'total'        => $posts->total(),
            ],
        ]);
    }

    public function postDetail(int $id): JsonResponse
    {
        $post = ForumPost::with(['category', 'tags', 'customer:id,first_name,last_name,email'])
            ->withCount(['comments', 'bookmarks'])
            ->find($id);

        if (!$post) {
            return response()->json(['status' => 'error', 'message' => 'Không tìm thấy bài viết.'], 404);
        }

        return response()->json(['status' => 'success', 'data' => $post]);
    }

    public function postStore(Request $request): JsonResponse
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'content'     => 'required|string',
            'category_id' => 'required|exists:forum_categories,id',
            'type'        => 'nullable|in:discussion,idea,question,showcase,job,review',
            'status'      => 'nullable|in:draft,published,hidden,locked',
            'tags'        => 'nullable|string',
            'is_featured' => 'nullable|boolean',
            'is_sticky'   => 'nullable|boolean',
            'meta_title'       => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords'    => 'nullable|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            $post = ForumPost::create([
                'title'            => $request->input('title'),
                'content'          => $request->input('content'),
                'category_id'      => $request->input('category_id'),
                'type'             => $request->input('type', 'discussion'),
                'status'           => $request->input('status', 'published'),
                'is_featured'      => $request->boolean('is_featured', false),
                'is_sticky'        => $request->boolean('is_sticky', false),
                'author_name'      => 'Admin',
                'meta_title'       => $request->input('meta_title'),
                'meta_description' => $request->input('meta_description'),
                'meta_keywords'    => $request->input('meta_keywords'),
            ]);

            if ($tags = $request->input('tags')) {
                $this->postService->syncTags($post, $tags);
            }

            DB::commit();

            return response()->json([
                'status'  => 'success',
                'message' => 'Tạo bài viết thành công.',
                'data'    => $post->load(['category', 'tags']),
            ], 201);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('ForumManage postStore error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Lỗi tạo bài viết.'], 500);
        }
    }

    public function postUpdate(Request $request, int $id): JsonResponse
    {
        $post = ForumPost::find($id);
        if (!$post) {
            return response()->json(['status' => 'error', 'message' => 'Không tìm thấy bài viết.'], 404);
        }

        $request->validate([
            'title'       => 'nullable|string|max:255',
            'content'     => 'nullable|string',
            'category_id' => 'nullable|exists:forum_categories,id',
            'type'        => 'nullable|in:discussion,idea,question,showcase,job,review',
            'status'      => 'nullable|in:draft,published,hidden,locked',
            'tags'        => 'nullable|string',
            'is_featured' => 'nullable|boolean',
            'is_sticky'   => 'nullable|boolean',
            'meta_title'       => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords'    => 'nullable|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            $data = array_filter($request->only([
                'title', 'content', 'category_id', 'type', 'status',
                'meta_title', 'meta_description', 'meta_keywords',
            ]), fn ($v) => $v !== null);

            if ($request->has('is_featured')) $data['is_featured'] = $request->boolean('is_featured');
            if ($request->has('is_sticky')) $data['is_sticky'] = $request->boolean('is_sticky');

            $post->update($data);

            if ($request->has('tags')) {
                $this->postService->syncTags($post, $request->input('tags', ''));
            }

            DB::commit();

            return response()->json([
                'status'  => 'success',
                'message' => 'Cập nhật bài viết thành công.',
                'data'    => $post->fresh()->load(['category', 'tags']),
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('ForumManage postUpdate error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Lỗi cập nhật bài viết.'], 500);
        }
    }

    public function postDestroy(int $id): JsonResponse
    {
        $post = ForumPost::find($id);
        if (!$post) {
            return response()->json(['status' => 'error', 'message' => 'Không tìm thấy bài viết.'], 404);
        }

        $this->postService->delete($post);

        return response()->json(['status' => 'success', 'message' => 'Xóa bài viết thành công.']);
    }

    public function postChangeStatus(Request $request, int $id): JsonResponse
    {
        $post = ForumPost::find($id);
        if (!$post) {
            return response()->json(['status' => 'error', 'message' => 'Không tìm thấy bài viết.'], 404);
        }

        $request->validate([
            'status'      => 'required|in:draft,published,hidden,locked',
            'is_featured' => 'nullable|boolean',
            'is_sticky'   => 'nullable|boolean',
        ]);

        $this->postService->updateStatus(
            $post,
            $request->input('status'),
            $request->has('is_featured') ? $request->boolean('is_featured') : null,
            $request->has('is_sticky') ? $request->boolean('is_sticky') : null,
        );

        return response()->json([
            'status'  => 'success',
            'message' => 'Cập nhật trạng thái thành công.',
            'data'    => $post->fresh(),
        ]);
    }

    public function postBulkStatus(Request $request): JsonResponse
    {
        $request->validate([
            'ids'    => 'required|array|min:1',
            'ids.*'  => 'integer',
            'status' => 'required|in:draft,published,hidden,locked',
        ]);

        $count = $this->postService->massUpdateStatus($request->input('ids'), $request->input('status'));

        return response()->json([
            'status'  => 'success',
            'message' => "Đã cập nhật {$count} bài viết.",
            'data'    => ['affected' => $count],
        ]);
    }

    public function postBulkDelete(Request $request): JsonResponse
    {
        $request->validate([
            'ids'   => 'required|array|min:1',
            'ids.*' => 'integer',
        ]);

        $count = $this->postService->massDelete($request->input('ids'));

        return response()->json([
            'status'  => 'success',
            'message' => "Đã xóa {$count} bài viết.",
            'data'    => ['deleted' => $count],
        ]);
    }

    // =========================================================================
    // COMMENTS
    // =========================================================================

    public function commentList(Request $request): JsonResponse
    {
        $query = ForumComment::with(['post:id,title,slug', 'customer:id,first_name,last_name,email']);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('content', 'like', "%{$search}%")
                  ->orWhere('author_name', 'like', "%{$search}%");
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($postId = $request->input('post_id')) {
            $query->where('post_id', $postId);
        }

        if ($request->has('is_best_answer')) {
            $query->where('is_best_answer', $request->boolean('is_best_answer'));
        }

        if ($request->has('is_root')) {
            $request->boolean('is_root') ? $query->whereNull('parent_id') : $query->whereNotNull('parent_id');
        }

        $sortable = ['created_at', 'likes_count', 'replies_count'];
        $sortBy = in_array($request->input('sort_by'), $sortable) ? $request->input('sort_by') : 'created_at';
        $sortDir = $request->input('sort_dir') === 'asc' ? 'asc' : 'desc';
        $query->orderBy($sortBy, $sortDir);

        $perPage = min(max($request->integer('per_page', 15), 1), 100);
        $comments = $query->paginate($perPage);

        return response()->json([
            'status' => 'success',
            'data'   => $comments->items(),
            'meta'   => [
                'current_page' => $comments->currentPage(),
                'last_page'    => $comments->lastPage(),
                'per_page'     => $comments->perPage(),
                'total'        => $comments->total(),
            ],
        ]);
    }

    public function commentDetail(int $id): JsonResponse
    {
        $comment = ForumComment::with(['post:id,title,slug', 'customer:id,first_name,last_name,email', 'parent:id,content,author_name'])
            ->find($id);

        if (!$comment) {
            return response()->json(['status' => 'error', 'message' => 'Không tìm thấy bình luận.'], 404);
        }

        return response()->json(['status' => 'success', 'data' => $comment]);
    }

    public function commentChangeStatus(Request $request, int $id): JsonResponse
    {
        $comment = ForumComment::find($id);
        if (!$comment) {
            return response()->json(['status' => 'error', 'message' => 'Không tìm thấy bình luận.'], 404);
        }

        $request->validate(['status' => 'required|in:published,pending,hidden,spam']);

        $this->commentService->updateStatus($comment, $request->input('status'));

        return response()->json([
            'status'  => 'success',
            'message' => 'Cập nhật trạng thái bình luận thành công.',
            'data'    => $comment->fresh(),
        ]);
    }

    public function commentDestroy(int $id): JsonResponse
    {
        $comment = ForumComment::find($id);
        if (!$comment) {
            return response()->json(['status' => 'error', 'message' => 'Không tìm thấy bình luận.'], 404);
        }

        $this->commentService->delete($comment);

        return response()->json(['status' => 'success', 'message' => 'Xóa bình luận thành công.']);
    }

    public function commentBulkStatus(Request $request): JsonResponse
    {
        $request->validate([
            'ids'    => 'required|array|min:1',
            'ids.*'  => 'integer',
            'status' => 'required|in:published,pending,hidden,spam',
        ]);

        $count = $this->commentService->massUpdateStatus($request->input('ids'), $request->input('status'));

        return response()->json([
            'status'  => 'success',
            'message' => "Đã cập nhật {$count} bình luận.",
            'data'    => ['affected' => $count],
        ]);
    }

    public function commentBulkDelete(Request $request): JsonResponse
    {
        $request->validate([
            'ids'   => 'required|array|min:1',
            'ids.*' => 'integer',
        ]);

        $count = $this->commentService->massDelete($request->input('ids'));

        return response()->json([
            'status'  => 'success',
            'message' => "Đã xóa {$count} bình luận.",
            'data'    => ['deleted' => $count],
        ]);
    }

    // =========================================================================
    // CATEGORIES
    // =========================================================================

    public function categoryList(Request $request): JsonResponse
    {
        $query = ForumCategory::query();

        if ($request->has('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        $categories = $query->ordered()->get();

        return response()->json(['status' => 'success', 'data' => $categories]);
    }

    public function categoryStore(Request $request): JsonResponse
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'icon'        => 'nullable|string|max:50',
            'color'       => 'nullable|string|max:7',
            'sort_order'  => 'nullable|integer',
            'is_active'   => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
        ]);

        $category = ForumCategory::create([
            'name'        => $request->input('name'),
            'slug'        => Str::slug($request->input('name')),
            'description' => $request->input('description'),
            'icon'        => $request->input('icon'),
            'color'       => $request->input('color', '#667eea'),
            'sort_order'  => $request->integer('sort_order', 0),
            'is_active'   => $request->boolean('is_active', true),
            'is_featured' => $request->boolean('is_featured', false),
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Tạo danh mục thành công.',
            'data'    => $category,
        ], 201);
    }

    public function categoryUpdate(Request $request, int $id): JsonResponse
    {
        $category = ForumCategory::find($id);
        if (!$category) {
            return response()->json(['status' => 'error', 'message' => 'Không tìm thấy danh mục.'], 404);
        }

        $request->validate([
            'name'        => 'nullable|string|max:255',
            'description' => 'nullable|string|max:500',
            'icon'        => 'nullable|string|max:50',
            'color'       => 'nullable|string|max:7',
            'sort_order'  => 'nullable|integer',
            'is_active'   => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
        ]);

        $data = array_filter($request->only(['name', 'description', 'icon', 'color', 'sort_order']), fn ($v) => $v !== null);
        if (isset($data['name'])) $data['slug'] = Str::slug($data['name']);
        if ($request->has('is_active')) $data['is_active'] = $request->boolean('is_active');
        if ($request->has('is_featured')) $data['is_featured'] = $request->boolean('is_featured');

        $category->update($data);

        return response()->json([
            'status'  => 'success',
            'message' => 'Cập nhật danh mục thành công.',
            'data'    => $category->fresh(),
        ]);
    }

    public function categoryDestroy(int $id): JsonResponse
    {
        $category = ForumCategory::find($id);
        if (!$category) {
            return response()->json(['status' => 'error', 'message' => 'Không tìm thấy danh mục.'], 404);
        }

        if ($category->posts()->count() > 0) {
            return response()->json([
                'status'  => 'error',
                'message' => "Không thể xóa danh mục đang có {$category->posts()->count()} bài viết.",
            ], 422);
        }

        $category->delete();

        return response()->json(['status' => 'success', 'message' => 'Xóa danh mục thành công.']);
    }

    // =========================================================================
    // TAGS
    // =========================================================================

    public function tagList(Request $request): JsonResponse
    {
        $query = ForumTag::query();

        if ($search = $request->input('search')) {
            $query->where('name', 'like', "%{$search}%");
        }

        $sortBy = $request->input('sort_by') === 'posts_count' ? 'posts_count' : 'name';
        $sortDir = $request->input('sort_dir') === 'desc' ? 'desc' : 'asc';

        $perPage = min(max($request->integer('per_page', 50), 1), 200);
        $tags = $query->orderBy($sortBy, $sortDir)->paginate($perPage);

        return response()->json([
            'status' => 'success',
            'data'   => $tags->items(),
            'meta'   => [
                'current_page' => $tags->currentPage(),
                'last_page'    => $tags->lastPage(),
                'per_page'     => $tags->perPage(),
                'total'        => $tags->total(),
            ],
        ]);
    }

    public function tagStore(Request $request): JsonResponse
    {
        $request->validate([
            'name'  => 'required|string|max:100',
            'color' => 'nullable|string|max:7',
        ]);

        $slug = Str::slug($request->input('name'));
        if (ForumTag::where('slug', $slug)->exists()) {
            return response()->json(['status' => 'error', 'message' => 'Tag đã tồn tại.'], 422);
        }

        $tag = ForumTag::create([
            'name'  => $request->input('name'),
            'slug'  => $slug,
            'color' => $request->input('color', '#6b7280'),
        ]);

        return response()->json(['status' => 'success', 'message' => 'Tạo tag thành công.', 'data' => $tag], 201);
    }

    public function tagUpdate(Request $request, int $id): JsonResponse
    {
        $tag = ForumTag::find($id);
        if (!$tag) {
            return response()->json(['status' => 'error', 'message' => 'Không tìm thấy tag.'], 404);
        }

        $request->validate([
            'name'  => 'nullable|string|max:100',
            'color' => 'nullable|string|max:7',
        ]);

        $data = array_filter($request->only(['name', 'color']), fn ($v) => $v !== null);
        if (isset($data['name'])) $data['slug'] = Str::slug($data['name']);

        $tag->update($data);

        return response()->json(['status' => 'success', 'message' => 'Cập nhật tag thành công.', 'data' => $tag->fresh()]);
    }

    public function tagDestroy(int $id): JsonResponse
    {
        $tag = ForumTag::find($id);
        if (!$tag) {
            return response()->json(['status' => 'error', 'message' => 'Không tìm thấy tag.'], 404);
        }

        $tag->posts()->detach();
        $tag->delete();

        return response()->json(['status' => 'success', 'message' => 'Xóa tag thành công.']);
    }

    public function tagBulkDelete(Request $request): JsonResponse
    {
        $request->validate([
            'ids'   => 'required|array|min:1',
            'ids.*' => 'integer',
        ]);

        $ids = $request->input('ids');
        DB::table('forum_post_tags')->whereIn('tag_id', $ids)->delete();
        $count = ForumTag::destroy($ids);

        return response()->json([
            'status'  => 'success',
            'message' => "Đã xóa {$count} tag.",
            'data'    => ['deleted' => $count],
        ]);
    }

    // =========================================================================
    // REPORTS
    // =========================================================================

    public function reportList(Request $request): JsonResponse
    {
        $query = ForumReport::query();

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($reason = $request->input('reason')) {
            $query->where('reason', $reason);
        }

        if ($type = $request->input('type')) {
            $morphMap = [
                'post'    => 'App\\Models\\ForumPost',
                'comment' => 'App\\Models\\ForumComment',
            ];
            if (isset($morphMap[$type])) {
                $query->where('reportable_type', $morphMap[$type]);
            }
        }

        $query->orderByDesc('created_at');

        $perPage = min(max($request->integer('per_page', 15), 1), 100);
        $reports = $query->paginate($perPage);

        return response()->json([
            'status' => 'success',
            'data'   => $reports->items(),
            'meta'   => [
                'current_page' => $reports->currentPage(),
                'last_page'    => $reports->lastPage(),
                'per_page'     => $reports->perPage(),
                'total'        => $reports->total(),
            ],
        ]);
    }

    public function reportResolve(Request $request, int $id): JsonResponse
    {
        $report = ForumReport::find($id);
        if (!$report) {
            return response()->json(['status' => 'error', 'message' => 'Không tìm thấy báo cáo.'], 404);
        }

        $request->validate([
            'status' => 'required|in:reviewed,resolved,dismissed',
            'notes'  => 'nullable|string|max:1000',
        ]);

        $admin = $request->get('auth_admin');
        $this->reportService->updateStatus($report, $request->input('status'), $request->input('notes'), $admin->id);

        return response()->json([
            'status'  => 'success',
            'message' => 'Cập nhật báo cáo thành công.',
            'data'    => $report->fresh(),
        ]);
    }

    public function reportBulkResolve(Request $request): JsonResponse
    {
        $request->validate([
            'ids'    => 'required|array|min:1',
            'ids.*'  => 'integer',
            'status' => 'required|in:reviewed,resolved,dismissed',
        ]);

        $admin = $request->get('auth_admin');
        $count = $this->reportService->massUpdateStatus($request->input('ids'), $request->input('status'), $admin->id);

        return response()->json([
            'status'  => 'success',
            'message' => "Đã xử lý {$count} báo cáo.",
            'data'    => ['affected' => $count],
        ]);
    }

    // =========================================================================
    // REPUTATION & LEADERBOARD
    // =========================================================================

    public function leaderboard(Request $request): JsonResponse
    {
        $period = $request->input('period', 'all'); // all | month
        $limit = min(max($request->integer('limit', 20), 1), 100);

        $data = $this->reputationService->getLeaderboard($period, $limit);

        return response()->json(['status' => 'success', 'data' => $data]);
    }
}
