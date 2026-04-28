<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\ForumPostResource;
use App\Http\Resources\ForumCommentResource;
use App\Models\ForumPost;
use App\Models\ForumCategory;
use App\Models\ForumTag;
use App\Services\Forum\ForumPostService;
use App\Services\Forum\ForumCommentService;
use App\Services\Forum\ForumVoteService;
use App\Services\Forum\ForumBookmarkService;
use App\Services\Forum\ForumNotificationService;
use App\Services\Forum\ForumReputationService;

class ForumApiController extends Controller
{
    public function __construct(
        protected ForumPostService $postService,
        protected ForumCommentService $commentService,
        protected ForumVoteService $voteService,
        protected ForumBookmarkService $bookmarkService,
        protected ForumNotificationService $notificationService,
        protected ForumReputationService $reputationService,
    ) {}

    // --- Posts ---

    public function index(Request $request)
    {
        $filters = $request->only(['category', 'sort', 'search', 'type']);

        if (!empty($filters['category'])) {
            $cat = ForumCategory::where('slug', $filters['category'])->first();
            $filters['category'] = $cat?->id;
        }

        $data = $this->postService->getIndexData($filters);

        return ForumPostResource::collection($data['posts'])
            ->additional(['meta' => ['stats' => $data['stats']]]);
    }

    public function show(string $slug)
    {
        $post = ForumPost::where('slug', $slug)->published()->firstOrFail();
        $data = $this->postService->getDetail($post, request()->ip());

        $post->load(['rootComments' => fn ($q) => $q->with('publishedReplies')->published()->oldest()]);

        return (new ForumPostResource($data['post']))
            ->additional([
                'comments' => ForumCommentResource::collection($post->rootComments),
                'related'  => ForumPostResource::collection($data['related']),
            ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title'       => 'required|string|max:255',
            'content'     => 'required|string|min:10',
            'category_id' => 'required|exists:forum_categories,id',
            'type'        => 'nullable|in:discussion,idea,question,showcase,job,review',
            'tags'        => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $post = $this->postService->create($validator->validated(), $request->user());

        return new ForumPostResource($post->load(['category', 'tags']));
    }

    public function update(Request $request, int $id)
    {
        $post = ForumPost::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title'       => 'required|string|max:255',
            'content'     => 'required|string|min:10',
            'category_id' => 'required|exists:forum_categories,id',
            'type'        => 'nullable|in:discussion,idea,question,showcase,job,review',
            'tags'        => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $post = $this->postService->update($post, $validator->validated(), $request->user());

        return new ForumPostResource($post->load(['category', 'tags']));
    }

    public function destroy(int $id)
    {
        $this->postService->delete(ForumPost::findOrFail($id));
        return response()->json(['message' => 'Deleted']);
    }

    // --- Comments ---

    public function storeComment(Request $request, int $postId)
    {
        $post = ForumPost::findOrFail($postId);

        $validator = Validator::make($request->all(), [
            'content'   => 'required|string|min:3|max:2000',
            'parent_id' => 'nullable|exists:forum_comments,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $comment = $this->commentService->create($post, $validator->validated(), $request->user());

        return new ForumCommentResource($comment);
    }

    // --- Bookmark ---

    public function bookmark(Request $request, int $postId)
    {
        $post = ForumPost::findOrFail($postId);
        $bookmarked = $this->bookmarkService->toggle($request->user()->id, $post);

        return response()->json(['bookmarked' => $bookmarked]);
    }

    // --- Vote ---

    public function vote(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type'    => 'required|in:post,comment',
            'id'      => 'required|integer',
            'vote'    => 'required|in:like,dislike',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $voteable = $this->voteService->resolveVoteable($request->type, $request->id);
        if (!$voteable) return response()->json(['message' => 'Not found'], 404);

        $counts = $this->voteService->toggle($voteable, (string) $request->user()->id, $request->vote);

        return response()->json($counts);
    }

    // --- Categories & Tags ---

    public function categories()
    {
        return ForumCategory::active()->ordered()->withCount('publishedPosts')->get()
            ->map(fn ($c) => ['id' => $c->id, 'name' => $c->name, 'slug' => $c->slug, 'icon' => $c->icon, 'posts_count' => $c->published_posts_count]);
    }

    public function tags()
    {
        return ForumTag::popular()->limit(50)->get()
            ->map(fn ($t) => ['id' => $t->id, 'name' => $t->name, 'slug' => $t->slug, 'color' => $t->color, 'posts_count' => $t->posts_count]);
    }

    // --- Trending ---

    public function trending()
    {
        return ForumPostResource::collection($this->postService->getTrending(10));
    }

    // --- Notifications ---

    public function notifications(Request $request)
    {
        return $this->notificationService->getForCustomer($request->user()->id);
    }

    public function markNotificationRead(Request $request)
    {
        if ($request->input('all')) {
            $count = $this->notificationService->markAllAsRead($request->user()->id);
            return response()->json(['marked' => $count]);
        }

        $this->notificationService->markAsRead($request->input('id'), $request->user()->id);
        return response()->json(['success' => true]);
    }

    // --- Leaderboard ---

    public function leaderboard(Request $request)
    {
        $period = $request->get('period', 'all');
        return response()->json($this->reputationService->getLeaderboard($period));
    }
}
