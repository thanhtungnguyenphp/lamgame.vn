<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\ForumPostRequest;
use App\Models\ForumCategory;
use App\Models\ForumPost;
use App\Models\ForumTag;
use App\Services\Forum\ForumPostService;
use App\Services\Forum\ForumCommentService;
use App\Services\Forum\ForumVoteService;
use App\Services\Forum\ForumReportService;
use App\Services\Forum\ForumBookmarkService;
use App\Services\Forum\ForumNotificationService;
use App\Services\Forum\ForumReputationService;

class ForumController extends Controller
{
    public function __construct(
        protected ForumPostService $postService,
        protected ForumCommentService $commentService,
        protected ForumVoteService $voteService,
        protected ForumReportService $reportService,
        protected ForumBookmarkService $bookmarkService,
        protected ForumNotificationService $notificationService,
        protected ForumReputationService $reputationService,
    ) {}

    /**
     * Display the forum homepage (public).
     */
    public function index(Request $request)
    {
        $filters = $request->only(['category', 'sort', 'search', 'type']);

        if (!empty($filters['category'])) {
            $cat = ForumCategory::where('slug', $filters['category'])->first();
            $filters['category'] = $cat?->id;
        }

        $data = $this->postService->getIndexData($filters);

        return view('lamgame.pages.forum.index', array_merge($data, [
            'category'    => $request->get('category'),
            'sort'        => $request->get('sort', 'latest'),
            'search'      => $request->get('search'),
            'type'        => $request->get('type'),
            'stickyPosts' => $data['sticky'],
            'popularTags' => $data['tags'],
        ]));
    }

    /**
     * Show the form for creating a new post (auth via middleware).
     */
    public function create(Request $request)
    {
        return view('lamgame.pages.forum.create', [
            'categories'       => ForumCategory::active()->ordered()->get(),
            'tags'             => ForumTag::popular()->get(),
            'selectedCategory' => $request->get('category'),
            'selectedType'     => 'discussion',
            'user'             => auth()->guard('customer')->user(),
        ]);
    }

    /**
     * Store a newly created post (auth via middleware).
     */
    public function store(ForumPostRequest $request)
    {
        $user = auth()->guard('customer')->user();
        $post = $this->postService->create($request->validated(), $user);

        return redirect()->route('forum.posts.show', $post->slug)
            ->with('success', 'Bài viết đã được đăng thành công!');
    }

    /**
     * Display the specified post (public).
     */
    public function show(ForumPost $post, Request $request)
    {
        $data = $this->postService->getDetail($post, $request->ip());

        return view('lamgame.pages.forum.show', [
            'post'         => $data['post'],
            'relatedPosts' => $data['related'],
            'authorPosts'  => $data['authorPosts'],
        ]);
    }

    /**
     * Show the form for editing the specified post (auth via middleware).
     */
    public function edit(ForumPost $post)
    {
        return view('lamgame.pages.forum.edit', [
            'post'        => $post,
            'categories'  => ForumCategory::active()->ordered()->get(),
            'popularTags' => ForumTag::popular()->get(),
            'postTags'    => $post->tags->pluck('name')->toArray(),
            'user'        => auth()->guard('customer')->user(),
        ]);
    }

    /**
     * Update the specified post (auth via middleware).
     */
    public function update(ForumPostRequest $request, ForumPost $post)
    {
        $user = auth()->guard('customer')->user();
        $this->postService->update($post, $request->validated(), $user);

        return redirect()->route('forum.posts.show', $post->slug)
            ->with('success', 'Bài viết đã được cập nhật thành công!');
    }

    /**
     * Remove the specified post (auth via middleware).
     */
    public function destroy(ForumPost $post)
    {
        $this->postService->delete($post);

        return redirect()->route('forum.index')
            ->with('success', 'Bài viết đã được xóa thành công!');
    }

    /**
     * Store a new comment for a post (auth via middleware).
     */
    public function storeComment(Request $request, ForumPost $post)
    {
        $validator = Validator::make($request->all(), [
            'content'   => 'required|string|min:3|max:2000',
            'parent_id' => 'nullable|exists:forum_comments,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = auth()->guard('customer')->user();
        $this->commentService->create($post, $request->only(['content', 'parent_id']), $user);

        return redirect()->route('forum.posts.show', $post->slug)
            ->with('success', 'Bình luận đã được đăng thành công!');
    }

    /**
     * React to a post or comment (emoji reactions).
     */
    public function react(Request $request)
    {
        $request->validate([
            'reactable_type' => 'required|in:post,comment',
            'reactable_id' => 'required|integer',
            'type' => 'required|in:like,love,fire,think,game',
        ]);

        $reactableType = $request->reactable_type === 'post'
            ? 'App\Models\ForumPost'
            : 'App\Models\ForumComment';

        $customerId = auth('customer')->id();
        $voterIdentifier = $customerId
            ? 'customer_' . $customerId
            : 'guest_' . $request->ip();

        $result = \App\Models\ForumReaction::toggle(
            $reactableType,
            $request->reactable_id,
            $voterIdentifier,
            $request->type,
            $customerId
        );

        return response()->json(['success' => true, ...$result]);
    }

    /**
     * Vote on a post or comment (AJAX, auth via middleware).
     */
    public function vote(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'voteable_type'    => 'required|in:App\Models\ForumPost,App\Models\ForumComment',
            'voteable_id'      => 'required|integer',
            'vote_type'        => 'required|in:like,dislike',
            'voter_identifier' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $type = $request->voteable_type === 'App\Models\ForumPost' ? 'post' : 'comment';
        $voteable = $this->voteService->resolveVoteable($type, $request->voteable_id);

        if (!$voteable) {
            return response()->json(['success' => false, 'message' => 'Item not found'], 404);
        }

        $counts = $this->voteService->toggle($voteable, $request->voter_identifier, $request->vote_type);

        return response()->json(array_merge(['success' => true], $counts));
    }

    /**
     * Show posts by category (public).
     */
    public function category(ForumCategory $category)
    {
        $posts = $this->postService->getByCategory($category);
        return view('lamgame.pages.forum.category', compact('category', 'posts'));
    }

    /**
     * Show posts by tag (public).
     */
    public function tag(ForumTag $tag)
    {
        $posts = $this->postService->getByTag($tag);
        return view('lamgame.pages.forum.tag', compact('tag', 'posts'));
    }

    /**
     * Search posts (public).
     */
    public function search(Request $request)
    {
        $query = $request->get('q');

        if (empty($query)) {
            return redirect()->route('forum.index');
        }

        $filters = $request->only(['category', 'type']);

        if (!empty($filters['category'])) {
            $cat = ForumCategory::where('slug', $filters['category'])->first();
            $filters['category'] = $cat?->id;
        }

        $posts = $this->postService->search($query, $filters);

        return view('lamgame.pages.forum.search', compact('posts', 'query') + [
            'category' => $request->get('category'),
            'type'     => $request->get('type'),
        ]);
    }

    /**
     * Report a post or comment (AJAX, auth via middleware).
     */
    public function report(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'reportable_type' => 'required|in:post,comment',
            'reportable_id'   => 'required|integer',
            'reason'          => 'required|string|in:spam,inappropriate,harassment,copyright,other',
            'description'     => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $user = auth()->guard('customer')->user();
        $reportable = $this->reportService->resolveReportable($request->reportable_type, $request->reportable_id);

        if (!$reportable) {
            return response()->json(['success' => false, 'message' => 'Item not found'], 404);
        }

        if ($this->reportService->hasDuplicate($reportable, $user->id)) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn đã báo cáo nội dung này rồi.',
            ], 422);
        }

        $this->reportService->create($reportable, $user->id, $request->only(['reason', 'description']));

        return response()->json([
            'success' => true,
            'message' => 'Báo cáo đã được gửi. Chúng tôi sẽ xem xét sớm.',
        ]);
    }

    /**
     * Toggle bookmark on a post (AJAX, auth via middleware).
     */
    public function bookmark(ForumPost $post)
    {
        $user = auth()->guard('customer')->user();
        $bookmarked = $this->bookmarkService->toggle($user->id, $post);

        return response()->json([
            'success'    => true,
            'bookmarked' => $bookmarked,
            'message'    => $bookmarked ? 'Đã lưu bài viết.' : 'Đã bỏ lưu bài viết.',
        ]);
    }

    /**
     * Show bookmarked posts (auth via middleware).
     */
    public function bookmarks()
    {
        $user = auth()->guard('customer')->user();
        $posts = $this->bookmarkService->getByCustomer($user->id);

        return view('lamgame.pages.forum.bookmarks', compact('posts'));
    }

    /**
     * Pin/unpin best answer (AJAX, auth via middleware).
     */
    public function pinBestAnswer(Request $request, ForumPost $post)
    {
        $user = auth()->guard('customer')->user();

        // Only post author or admin can pin
        if ($post->customer_id !== $user->id) {
            return response()->json(['success' => false, 'message' => 'Chỉ tác giả bài viết mới có thể chọn câu trả lời tốt nhất.'], 403);
        }

        $validator = Validator::make($request->all(), [
            'comment_id' => 'required|exists:forum_comments,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $comment = \App\Models\ForumComment::where('id', $request->comment_id)
            ->where('post_id', $post->id)
            ->firstOrFail();

        if ($comment->is_best_answer) {
            $this->commentService->unpinBestAnswer($post);
            return response()->json(['success' => true, 'pinned' => false, 'message' => 'Đã bỏ chọn câu trả lời tốt nhất.']);
        }

        $this->commentService->pinBestAnswer($comment, $post);
        return response()->json(['success' => true, 'pinned' => true, 'message' => 'Đã chọn câu trả lời tốt nhất!']);
    }

    /**
     * Get notifications (auth via middleware).
     */
    public function messages()
    {
        return view('lamgame.pages.forum.messages');
    }

    public function notifications()
    {
        $user = auth()->guard('customer')->user();
        $notifications = $this->notificationService->getForCustomer($user->id);
        $unreadCount = $this->notificationService->getUnreadCount($user->id);

        return view('lamgame.pages.forum.notifications', compact('notifications', 'unreadCount'));
    }

    /**
     * Get unread notification count (AJAX, auth via middleware).
     */
    public function notificationCount()
    {
        $user = auth()->guard('customer')->user();
        return response()->json([
            'count' => $this->notificationService->getUnreadCount($user->id),
        ]);
    }

    /**
     * Mark notification as read (AJAX, auth via middleware).
     */
    public function markNotificationRead(Request $request)
    {
        $user = auth()->guard('customer')->user();

        if ($request->input('all')) {
            $count = $this->notificationService->markAllAsRead($user->id);
            return response()->json(['success' => true, 'marked' => $count]);
        }

        $validator = Validator::make($request->all(), [
            'id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false], 422);
        }

        $this->notificationService->markAsRead($request->id, $user->id);
        return response()->json(['success' => true]);
    }

    /**
     * Trending posts (public).
     */
    public function trending()
    {
        $posts = $this->postService->getTrending(20);
        return view('lamgame.pages.forum.trending', compact('posts'));
    }

    /**
     * Leaderboard (public).
     */
    public function leaderboard(Request $request)
    {
        $period = $request->get('period', 'all');
        $leaders = $this->reputationService->getLeaderboard($period);
        return view('lamgame.pages.forum.leaderboard', compact('leaders', 'period'));
    }
}
